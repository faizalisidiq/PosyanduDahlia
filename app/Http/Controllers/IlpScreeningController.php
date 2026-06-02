<?php

namespace App\Http\Controllers;

use App\Models\Elderly;
use App\Models\IlpScreening;
use App\Models\Mother;
use App\Models\Children;
use App\Models\Staff;
use App\Models\HealthPost;
use App\Http\Requests\StoreIlpScreeningRequest;
use App\Http\Requests\UpdateIlpScreeningRequest;
use Illuminate\Http\Request;

class IlpScreeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ilpScreenings = IlpScreening::with(['subjectable', 'staff.user'])
            ->when($request->search, function ($query) use ($request) {
                // Search by staff name or subject name
                $term = $request->search;
                $query->whereHasMorph('subjectable', [Mother::class, Children::class, Elderly::class], function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%");
                })->orWhereHas('staff.user', function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%");
                });
            })
            ->latest('checkup_date')
            ->paginate(10);
        
        $healthPosts = HealthPost::all();

        return view('apps.ilp-screenings.index', compact('ilpScreenings', 'healthPosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mothers = Mother::all();
        $childrens = Children::with('mother')->get();
        $elderlies = Elderly::all();
        $staffs = Staff::with('user')->get();

        return view('apps.ilp-screenings.create', compact('mothers', 'childrens', 'elderlies', 'staffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIlpScreeningRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Calculate and capture age snapshot
            if (isset($data['subjectable_type']) && isset($data['subjectable_id']) && isset($data['checkup_date'])) {
                $subject = $data['subjectable_type']::find($data['subjectable_id']);
                if ($subject && $subject->birth_date) {
                    $checkupDate = \Carbon\Carbon::parse($data['checkup_date']);
                    $diff = $checkupDate->diff($subject->birth_date);
                    
                    $results = $data['results'] ?? [];
                    $results['age_snapshot'] = [
                        'years' => $diff->y,
                        'months' => $diff->m,
                        'days' => $diff->d,
                        'formatted' => $diff->y . ' tahun ' . $diff->m . ' bulan'
                    ];
                    $data['results'] = $results;
                }
            }

            IlpScreening::create($data);
            return redirect()->route('ilp-screenings.index')->with('success', 'Screening ILP berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(IlpScreening $ilpScreening)
    {
        $ilpScreening->load(['subjectable', 'staff.user']);
        return view('apps.ilp-screenings.show', compact('ilpScreening'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IlpScreening $ilpScreening)
    {
        $mothers = Mother::all();
        $childrens = Children::with('mother')->get();
        $elderlies = Elderly::all();
        $staffs = Staff::with('user')->get();

        return view('apps.ilp-screenings.edit', compact('ilpScreening', 'mothers', 'childrens', 'elderlies', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIlpScreeningRequest $request, IlpScreening $ilpScreening)
    {
        try {
            $ilpScreening->update($request->validated());
            return redirect()->route('ilp-screenings.index')->with('success', 'Screening ILP berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IlpScreening $ilpScreening)
    {
        try {
            $ilpScreening->delete();
            return redirect()->route('ilp-screenings.index')->with('success', 'Screening ILP berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function export(Request $request, \App\Services\ExcelExportService $exportService)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            $filters = [
                'health_post_id' => $request->input('health_post_id'),
                'address' => $request->input('address'),
            ];

            $fileName = $exportService->exportIlp($startDate, $endDate, $filters);
            return response()->download(storage_path('app/public/' . $fileName))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}
