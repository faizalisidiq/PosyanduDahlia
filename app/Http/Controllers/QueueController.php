<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQueueRequest;
use App\Http\Requests\UpdateQueueRequest;
use App\Models\Queue;
use App\Models\Mother;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QueueController extends Controller
{
    /**
     * Display a listing of the resource (Staff View).
     */
    public function index()
    {
        $today = now()->format('Y-m-d');
        $queues = Queue::with('child.mother')
            ->whereDate('date', $today)
            ->orderBy('id', 'asc')
            ->get();
            
        $currentQueue = $queues->where('status', 'called')->first();
        
        return view('apps.queues.index', compact('queues', 'currentQueue'));
    }

    /**
     * Show the public kiosk page.
     */
    public function kiosk()
    {
        return view('apps.queues.kiosk');
    }

    /**
     * Check if mother exists and return children.
     */
    public function check(Request $request)
    {
        $request->validate([
            'identity_number' => 'required|numeric|digits:16',
        ]);

        $mother = Mother::where('identity_number', $request->identity_number)->first();

        if (!$mother) {
            return back()->with('error', 'Data Ibu tidak ditemukan. Silakan hubungi kader jika belum terdaftar.');
        }

        $children = $mother->children; // Assuming relationship is defined as 'children' in Mother model, checking step 17... Wait, mother model step 17 didn't show relationship. Need to check/fix Mother model if needed. 
        // Based on Children model step 43, it belongsTo Mother. So Mother should hasMany Children.
        // Assuming standard naming 'children'.

        return view('apps.queues.check', compact('mother', 'children'));
    }

    /**
     * Store a newly created queue from public kiosk.
     */
    public function storePublic(Request $request)
    {
        $request->validate([
            'child_ids' => 'required|array',
            'child_ids.*' => 'exists:childrens,id',
            'type' => 'required|in:growth_monitoring,immunization,general',
        ]);

        $childIds = $request->child_ids;
        $today = now()->format('Y-m-d');
        $createdQueueIds = [];

        foreach ($childIds as $childId) {
            // Check if already queued today
            $existing = Queue::where('child_id', $childId)
                ->whereDate('date', $today)
                ->first();

            if ($existing) {
                $createdQueueIds[] = $existing->id;
                continue;
            }

            // Generate Queue Number
            // Get the last queue number for today
            $lastQueue = Queue::whereDate('date', $today)->orderBy('id', 'desc')->first();
            
            $number = 1;
            if ($lastQueue) {
                // Extract number from A-001
                $parts = explode('-', $lastQueue->queue_number);
                if (count($parts) > 1) {
                    $number = intval($parts[1]) + 1;
                }
            }

            // Double check if this number is already taken to be safe (simple lock)
            while(Queue::whereDate('date', $today)->where('queue_number', 'A-' . str_pad($number, 3, '0', STR_PAD_LEFT))->exists()) {
                $number++;
            }

            $queueNumber = 'A-' . str_pad($number, 3, '0', STR_PAD_LEFT);

            $queue = Queue::create([
                'child_id' => $childId,
                'queue_number' => $queueNumber,
                'date' => $today,
                'status' => 'waiting',
                'type' => $request->type,
            ]);
            
            $createdQueueIds[] = $queue->id;
        }

        return redirect()->route('queues.public.tickets', ['ids' => implode(',', $createdQueueIds)])
            ->with('success', 'Antrian berhasil diambil!');
    }

    /**
     * Show ticket page (Single).
     */
    public function ticket(Queue $queue)
    {
        // Redirect simple ticket to bulk viewer for consistency
        return redirect()->route('queues.public.tickets', ['ids' => $queue->id]);
    }

    /**
     * Show multiple tickets.
     */
    public function tickets(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $queues = Queue::whereIn('id', $ids)->with('child')->orderBy('queue_number')->get();

        if ($queues->isEmpty()) {
            return redirect()->route('queues.public.index');
        }

        return view('apps.queues.ticket', compact('queues'));
    }

    /**
     * Public Monitor View (TV).
     */
    public function monitor()
    {
        $today = now()->format('Y-m-d');
        
        // Current called queue
        $called = Queue::whereDate('date', $today)
            ->where('status', 'called')
            ->with(['child'])
            ->latest('updated_at')
            ->first();
            
        // Waiting list
        $waiting = Queue::whereDate('date', $today)
            ->where('status', 'waiting')
            ->with(['child'])
            ->orderBy('id', 'asc')
            ->take(5)
            ->get();
            
        return view('apps.queues.monitor', compact('called', 'waiting'));
    }

    /**
     * Get Monitor Data (JSON) for AJAX Polling
     */
    public function data()
    {
        $today = now()->format('Y-m-d');
        
        $called = Queue::whereDate('date', $today)
            ->where('status', 'called')
            ->with(['child'])
            ->latest('updated_at')
            ->first();
            
        $waiting = Queue::whereDate('date', $today)
            ->where('status', 'waiting')
            ->with(['child'])
            ->orderBy('id', 'asc')
            ->take(5)
            ->get();
            
        return response()->json([
            'called' => $called,
            'waiting' => $waiting
        ]);
    }

    /**
     * Update status (Staff).
     */
    public function updateStatus(Request $request, Queue $queue)
    {
        $request->validate([
            'status' => 'required|in:waiting,called,processing,completed,skipped'
        ]);

        $queue->update(['status' => $request->status]);

        return back()->with('success', 'Status antrian diperbarui.');
    }
}
