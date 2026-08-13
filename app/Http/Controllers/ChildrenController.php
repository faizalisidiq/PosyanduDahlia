<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\HealthPost;
use App\Http\Requests\StoreChildrenRequest;
use App\Http\Requests\UpdateChildrenRequest;
use Illuminate\Http\Request;
use App\Services\ExcelExportService;

class ChildrenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $children = Children::with('mother')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                      ->orWhereHas('mother', function ($q) use ($request) {
                          $q->where('name', 'like', "%{$request->search}%");
                      });
            })
            ->latest()
            ->paginate(10);
        
        $healthPosts = HealthPost::all();
        
        return view('apps.children.index', compact('children', 'healthPosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mothers = \App\Models\Mother::all();
        return view('apps.children.create', compact('mothers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChildrenRequest $request)
    {
        try {
            $data = $request->validated();

            $children = Children::make($data);
            $children->saveOrFail();

            return redirect()->route('childrens.index')->with('success', 'Children created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create children: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Children $children)
    {
        // Load relationships
        $children->load('mother');

        // Get all data for Chart (Ordered by date ASC)
        // We load this manually to separate it from the paginated result
        $allGrowthData = $children->growthMonitorings()->orderBy('checkup_date', 'asc')->get();

        // Get Paginated data for Table (Ordered by date DESC)
        $growthHistory = $children->growthMonitorings()
            ->with('staff') // Eager load staff to avoid N+1 in table
            ->orderBy('checkup_date', 'desc')
            ->paginate(10);

        // Standards for 0-60 months (5 years)
        $standards = \App\Models\AnthropometryStandard::where('gender', $children->gender)
            ->where('age_in_months', '<=', 60)
            ->orderBy('age_in_months')
            ->get();
            
        // Prepare Child Data for JS using the full dataset
        $childGrowthData = $allGrowthData->map(function($item) use ($children) {
            // Calculate age in months. If checkup is before birth (shouldn't happen), assume 0.
            $ageInMonths = max(0, $children->birth_date->diffInMonths($item->checkup_date));
            return [
                'age_in_months' => $ageInMonths,
                'weight' => $item->weight,
                'checkup_date' => $item->checkup_date->format('d M Y')
            ];
        });

        $latestGrowth = $allGrowthData->last();

        // Use a custom view variable for the table to avoid confusion with the model relation
        return view('apps.children.show', compact('children', 'standards', 'childGrowthData', 'growthHistory', 'latestGrowth'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Children $children)
    {
        $mothers = \App\Models\Mother::all();
        return view('apps.children.edit', compact('children', 'mothers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChildrenRequest $request, Children $children)
    {
        try {
            $data = $request->validated();

            $children->fill($data);
            $children->saveOrFail();

            return redirect()->route('childrens.index')->with('success', 'Children updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update children: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Children $children)
    {
        try {
            $children->deleteOrFail();

            return redirect()->route('childrens.index')->with('success', 'Children deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete children: ' . $e->getMessage());
        }
    }

    public function exportGrowth(Request $request, ExcelExportService $exportService)
    {
        try {
            $fromYear = $request->input('from_year', date('Y'));
            $untilYear = $request->input('until_year', date('Y'));
            
            $filters = [
                'health_post_id' => $request->input('health_post_id'),
                'address' => $request->input('address'),
                'age_min' => $request->input('age_min'),
                'age_max' => $request->input('age_max'),
            ];

            $fileName = $exportService->exportChildrenGrowth($fromYear, $untilYear, $filters);
            return response()->download(storage_path('app/public/' . $fileName))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function exportHistory(Children $children, ExcelExportService $exportService)
    {
        try {
            $fileName = $exportService->exportChildHistory($children);
            return response()->download(storage_path('app/public/' . $fileName))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor riwayat: ' . $e->getMessage());
        }
    }
}
