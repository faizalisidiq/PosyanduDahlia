<?php

namespace App\Http\Controllers;

use App\Models\GrowthMonitoring;
use App\Http\Requests\StoreGrowthMonitoringRequest;
use App\Http\Requests\UpdateGrowthMonitoringRequest;
use Illuminate\Http\Request;

use App\Services\AnthropometryService;

class GrowthMonitoringController extends Controller
{
    protected $anthropometryService;

    public function __construct(AnthropometryService $anthropometryService)
    {
        $this->anthropometryService = $anthropometryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $growthMonitorings = GrowthMonitoring::with(['child.mother', 'staff.user'])
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('child', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
            })
            ->latest('checkup_date')
            ->paginate(10);
        return view('apps.growth-monitorings.index', compact('growthMonitorings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $childrens = \App\Models\Children::all();
        $staffs = \App\Models\Staff::with('user')->get();
        return view('apps.growth-monitorings.create', compact('childrens', 'staffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGrowthMonitoringRequest $request)
    {
        try {
            $data = $request->validated();

            // Autofill Z-Score and Status
            $child = \App\Models\Children::findOrFail($data['child_id']);
            
            $calculation = $this->anthropometryService->calculate(
                $child->gender,
                $child->birth_date,
                $data['checkup_date'],
                $data['weight']
            );

            $data['z_score'] = $calculation['z_score'];
            $data['status'] = $calculation['status'];

            $growthMonitoring = GrowthMonitoring::make($data);
            $growthMonitoring->saveOrFail();

            return redirect()->route('growth-monitorings.index')->with('success', 'Data pemantauan berhasil disimpan. Status Gizi: ' . $data['status']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GrowthMonitoring $growthMonitoring)
    {
        return view('apps.growth-monitorings.show', compact('growthMonitoring'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GrowthMonitoring $growthMonitoring)
    {
        $childrens = \App\Models\Children::all();
        $staffs = \App\Models\Staff::with('user')->get();
        return view('apps.growth-monitorings.edit', compact('growthMonitoring', 'childrens', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGrowthMonitoringRequest $request, GrowthMonitoring $growthMonitoring)
    {
        try {
            $data = $request->validated();

            // Recalculate if weight or date changed (or just always recalculate to be safe)
            $child = $growthMonitoring->child; // Relationship
            
            $calculation = $this->anthropometryService->calculate(
                $child->gender,
                $child->birth_date,
                $data['checkup_date'],
                $data['weight']
            );

            $data['z_score'] = $calculation['z_score'];
            $data['status'] = $calculation['status'];

            $growthMonitoring->fill($data);
            $growthMonitoring->saveOrFail();

            return redirect()->route('growth-monitorings.index')->with('success', 'Data pemantauan berhasil diperbarui. Status Gizi: ' . $data['status']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GrowthMonitoring $growthMonitoring)
    {
        try {
            $growthMonitoring->deleteOrFail();

            return redirect()->route('growth-monitorings.index')->with('success', 'Growth monitoring deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete growth monitoring: ' . $e->getMessage());
        }
    }
}
