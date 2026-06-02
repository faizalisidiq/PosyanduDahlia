<?php

namespace App\Http\Controllers;

use App\Models\Mother;
use App\Models\HealthPost;
use App\Http\Requests\StoreMotherRequest;
use App\Http\Requests\UpdateMotherRequest;
use Illuminate\Http\Request;
use App\Services\ExcelExportService;

class MotherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'hamil');
        
        $mothers = Mother::query()
            ->where('status', $status)
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->paginate(10);
        
        $healthPosts = HealthPost::all();
        
        return view('apps.mothers.index', compact('mothers', 'status', 'healthPosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('apps.mothers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMotherRequest $request)
    {
        try {
            $data = $request->validated();

            $mother = Mother::make($data);
            $mother->saveOrFail();

            return redirect()->route('mothers.index')->with('success', 'Mother created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create mother: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Mother $mother)
    {
        return view('apps.mothers.show', compact('mother'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mother $mother)
    {
        return view('apps.mothers.edit', compact('mother'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMotherRequest $request, Mother $mother)
    {
        try {
            $data = $request->validated();

            $mother->fill($data);
            $mother->saveOrFail();

            return redirect()->route('mothers.index')->with('success', 'Mother updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update mother: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mother $mother)
    {
        try {
            $mother->deleteOrFail();

            return redirect()->route('mothers.index')->with('success', 'Mother deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete mother: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Mother $mother)
    {
        $validated = $request->validate([
            'status' => 'required|in:hamil,menyusui,lainnya'
        ]);

        try {
            $mother->update(['status' => $validated['status']]);
            return redirect()->back()->with('success', 'Status ibu berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function export(Request $request, ExcelExportService $exportService)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $status = $request->input('status', 'hamil');
            
            $filters = [
                'health_post_id' => $request->input('health_post_id'),
                'address' => $request->input('address'),
            ];

            $fileName = $exportService->exportMothers($startDate, $endDate, $status, $filters);
            return response()->download(storage_path('app/public/' . $fileName))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}
