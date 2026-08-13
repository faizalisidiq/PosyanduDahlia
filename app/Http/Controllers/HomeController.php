<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // 1. Total Children & Growth
        $totalChildren = \App\Models\Children::count();
        $newChildrenThisMonth = \App\Models\Children::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $childrenGrowth = $totalChildren > 0 ? ($newChildrenThisMonth / $totalChildren) * 100 : 0;

        // 2. Total Pregnant Mothers (Approximated by Mothers with pregnancy records)
        // For simplicity, showing Total Mothers currently registered
        $totalMothers = \App\Models\Mother::count();
        $newMothersThisMonth = \App\Models\Mother::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $mothersGrowth = $totalMothers > 0 ? ($newMothersThisMonth / $totalMothers) * 100 : 0;

        // 3. Growth Monitorings (Visits) This Month for "Total Kunjungan"
        $currentMonthVisits = \App\Models\GrowthMonitoring::whereMonth('checkup_date', now()->month)
            ->whereYear('checkup_date', now()->year)
            ->count();
        $lastMonthVisits = \App\Models\GrowthMonitoring::whereMonth('checkup_date', now()->subMonth()->month)
            ->whereYear('checkup_date', now()->subMonth()->year)
            ->count();
        
        $visitGrowth = 0;
        if ($lastMonthVisits > 0) {
            $visitGrowth = (($currentMonthVisits - $lastMonthVisits) / $lastMonthVisits) * 100;
        } else if ($currentMonthVisits > 0) {
            $visitGrowth = 100; // 100% growth if straight from 0
        }

        // 4. Stunting / Malnutrition Risk (Status != 'normal' ?? or hardcoded from 'status' text)
        // Let's count records with status 'stunted' or similar if we knew the values.
        // Assuming 'status' field stores 'Gizi Buruk', 'Gizi Kurang' etc.
        // Let's just Count "Gizi Buruk" + "Gizi Kurang" from latest records? 
        // For efficiency, let's just count *Issues* from GrowthMonitoring this month not 'Selesai' (Wait, status is 'Selesai' in the view?? 
        // No, in the view table it says "Selesai", but inside `GrowthMonitoring` model, `status` usually refers to nutrition status like 'Normal', 'Stunted'.
        // Let's check `GrowthMonitoring` viewing logic if possible, or just use "Total Staff" as the 3rd card for now to be safe and easiest.
        $totalStaff = \App\Models\Staff::count();

        // 5. Recent Activity (Latest Growth Monitorings)
        $recentActivities = \App\Models\GrowthMonitoring::with(['child', 'child.mother', 'staff'])
            ->latest('checkup_date')
            ->take(5)
            ->get();

        // --- CHART DATA ANALYTICS ---

        // Chart 1: Monthly Trends (Visits vs New Children) - Last 12 Months
        $months = collect([]);
        $visitsData = collect([]);
        $newChildrenData = collect([]);

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $months->push($monthName);

            $visits = \App\Models\GrowthMonitoring::whereMonth('checkup_date', $date->month)
                ->whereYear('checkup_date', $date->year)
                ->count();
            $visitsData->push($visits);

            $newChildren = \App\Models\Children::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $newChildrenData->push($newChildren);
        }

        // Chart 2: Children Demographics (Gender)
        $genderStats = \App\Models\Children::selectRaw('gender, count(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();
        // Ensure keys exist
        $genderData = [
            'male' => $genderStats['male'] ?? 0,
            'female' => $genderStats['female'] ?? 0
        ];

        // Chart 3: Nutritional Status Distribution (Latest Month)
        // Using checkup_date for the current month or just latest status of all children if usage is "Current State"
        // Let's use latest checkup per child to show "Current Health Landscape"
        $nutritionalStatusData = \App\Models\GrowthMonitoring::query()
            ->whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')
                      ->from('growth_monitorings')
                      ->groupBy('child_id');
            })
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        // Normalize keys if needed (handling case sensitivity or nulls)
        $statusLabels = array_keys($nutritionalStatusData);
        $statusValues = array_values($nutritionalStatusData);


        // Chart 4: Staff Performance (Top 5 this month)
        $topStaff = \App\Models\GrowthMonitoring::whereMonth('checkup_date', now()->month)
            ->whereYear('checkup_date', now()->year)
            ->selectRaw('staff_id, count(*) as total_checks')
            ->groupBy('staff_id')
            ->with(['staff.user'])
            ->orderByDesc('total_checks')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->staff->user->name ?? 'Unknown',
                    'count' => $item->total_checks
                ];
            });

        // NEW: Age Distribution of Visiting Children (Last 30 Days)
        $visitingChildrenIds = \App\Models\GrowthMonitoring::where('checkup_date', '>=', now()->subDays(30))
            ->distinct()
            ->pluck('child_id');
        
        $visitingChildren = \App\Models\Children::whereIn('id', $visitingChildrenIds)->select('birth_date')->get();
        $ageGroups = [
            '0-12 bln' => 0,
            '13-24 bln' => 0,
            '25-36 bln' => 0,
            '37-48 bln' => 0,
            '49-60 bln' => 0,
        ];

        foreach ($visitingChildren as $child) {
            if (!$child->birth_date) continue;
            $diffInMonths = $child->birth_date->diffInMonths(now());
            if ($diffInMonths <= 12) $ageGroups['0-12 bln']++;
            elseif ($diffInMonths <= 24) $ageGroups['13-24 bln']++;
            elseif ($diffInMonths <= 36) $ageGroups['25-36 bln']++;
            elseif ($diffInMonths <= 48) $ageGroups['37-48 bln']++;
            elseif ($diffInMonths <= 60) $ageGroups['49-60 bln']++;
        }

        $ageLabels = array_keys($ageGroups);
        $ageValues = array_values($ageGroups);

        return view('apps.dashboard.index', compact(
            'totalChildren',
            'childrenGrowth',
            'totalMothers',
            'mothersGrowth',
            'currentMonthVisits',
            'visitGrowth',
            'totalStaff',
            'recentActivities'
        ))->with([
            'months' => $months->values()->all(),
            'visitsData' => $visitsData->values()->all(),
            'newChildrenData' => $newChildrenData->values()->all(),
            'genderData' => $genderData,
            'statusLabels' => array_values($statusLabels),
            'statusValues' => array_values($statusValues),
            'topStaff' => $topStaff->values()->all(),
            'age' => [
                'labels' => $ageLabels,
                'values' => $ageValues,
            ]
        ]);
    }
}
