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
        $user = auth()->user();
        $staff = $user->staff;
        $hasFullAccess = $staff && $staff->role === 'ketua-kader';
        $staffId = $staff ? $staff->id : null;

        // 1. Children scoping
        if ($hasFullAccess || !$staff) {
            $totalChildren = \App\Models\Children::count();
            $newChildrenThisMonth = \App\Models\Children::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        } else {
            $myChildIds = \App\Models\GrowthMonitoring::where('staff_id', $staffId)->distinct()->pluck('child_id');
            $totalChildren = \App\Models\Children::whereIn('id', $myChildIds)->count();
            $newChildrenThisMonth = \App\Models\Children::whereIn('id', $myChildIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }
        $childrenGrowth = $totalChildren > 0 ? ($newChildrenThisMonth / $totalChildren) * 100 : 0;

        // 2. Mothers scoping
        if ($hasFullAccess || !$staff) {
            $totalMothers = \App\Models\Mother::count();
            $newMothersThisMonth = \App\Models\Mother::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        } else {
            $myChildIds = \App\Models\GrowthMonitoring::where('staff_id', $staffId)->distinct()->pluck('child_id');
            $myMotherIds = \App\Models\PregnancyRecord::where('staff_id', $staffId)->distinct()->pluck('mother_id')
                ->merge(\App\Models\ChildbirthRecord::where('staff_id', $staffId)->distinct()->pluck('mother_id'))
                ->merge(\App\Models\Children::whereIn('id', $myChildIds)->pluck('mother_id'))
                ->unique();
            $totalMothers = \App\Models\Mother::whereIn('id', $myMotherIds)->count();
            $newMothersThisMonth = \App\Models\Mother::whereIn('id', $myMotherIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }
        $mothersGrowth = $totalMothers > 0 ? ($newMothersThisMonth / $totalMothers) * 100 : 0;

        // 3. Growth Monitorings (Visits) This Month
        $visitsQuery = \App\Models\GrowthMonitoring::query();
        if (!$hasFullAccess && $staff) {
            $visitsQuery->where('staff_id', $staffId);
        }
        
        $currentMonthVisits = (clone $visitsQuery)->whereMonth('checkup_date', now()->month)
            ->whereYear('checkup_date', now()->year)
            ->count();
        $lastMonthVisits = (clone $visitsQuery)->whereMonth('checkup_date', now()->subMonth()->month)
            ->whereYear('checkup_date', now()->subMonth()->year)
            ->count();
        
        $visitGrowth = 0;
        if ($lastMonthVisits > 0) {
            $visitGrowth = (($currentMonthVisits - $lastMonthVisits) / $lastMonthVisits) * 100;
        } else if ($currentMonthVisits > 0) {
            $visitGrowth = 100;
        }

        $totalStaff = \App\Models\Staff::count();

        // 5. Recent Activity
        $recentQuery = \App\Models\GrowthMonitoring::with(['child', 'child.mother', 'staff']);
        if (!$hasFullAccess && $staff) {
            $recentQuery->where('staff_id', $staffId);
        }
        $recentActivities = $recentQuery->latest('checkup_date')->take(5)->get();

        // --- CHART DATA ANALYTICS ---
        $months = collect([]);
        $visitsData = collect([]);
        $newChildrenData = collect([]);

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $months->push($monthName);

            $visitsQ = \App\Models\GrowthMonitoring::whereMonth('checkup_date', $date->month)
                ->whereYear('checkup_date', $date->year);
            if (!$hasFullAccess && $staff) {
                $visitsQ->where('staff_id', $staffId);
            }
            $visitsData->push($visitsQ->count());

            $newChildrenQ = \App\Models\Children::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year);
            if (!$hasFullAccess && $staff) {
                $newChildrenQ->whereIn('id', $myChildIds ?? []);
            }
            $newChildrenData->push($newChildrenQ->count());
        }

        // Gender Chart
        $genderQ = \App\Models\Children::selectRaw('gender, count(*) as count');
        if (!$hasFullAccess && $staff) {
            $genderQ->whereIn('id', $myChildIds ?? []);
        }
        $genderStats = $genderQ->groupBy('gender')->pluck('count', 'gender')->toArray();
        $genderData = [
            'male' => $genderStats['male'] ?? 0,
            'female' => $genderStats['female'] ?? 0
        ];

        // Nutritional Chart
        $nutritionalQuery = \App\Models\GrowthMonitoring::query()
            ->whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')
                      ->from('growth_monitorings')
                      ->groupBy('child_id');
            });
        if (!$hasFullAccess && $staff) {
            $nutritionalQuery->where('staff_id', $staffId);
        }
        $nutritionalStatusData = $nutritionalQuery->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $statusLabels = array_keys($nutritionalStatusData);
        $statusValues = array_values($nutritionalStatusData);

        // Top Staff
        $topStaffQ = \App\Models\GrowthMonitoring::whereMonth('checkup_date', now()->month)
            ->whereYear('checkup_date', now()->year);
        if (!$hasFullAccess && $staff) {
            $topStaffQ->where('staff_id', $staffId);
        }
        $topStaff = $topStaffQ->selectRaw('staff_id, count(*) as total_checks')
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

        // Age Distribution
        if ($hasFullAccess || !$staff) {
            $visitingChildrenIds = \App\Models\GrowthMonitoring::where('checkup_date', '>=', now()->subDays(30))
                ->distinct()
                ->pluck('child_id');
            $visitingChildren = \App\Models\Children::whereIn('id', $visitingChildrenIds)->select('birth_date')->get();
        } else {
            $visitingChildrenIds = \App\Models\GrowthMonitoring::where('staff_id', $staffId)
                ->where('checkup_date', '>=', now()->subDays(30))
                ->distinct()
                ->pluck('child_id');
            $visitingChildren = \App\Models\Children::whereIn('id', $visitingChildrenIds)->select('birth_date')->get();
        }
        
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

        // TODAY'S PATIENTS
        $todayPregnancies = \App\Models\PregnancyRecord::with('mother')->whereDate('visit_date', today());
        $todayDeliveries = \App\Models\ChildbirthRecord::with('mother')->whereDate('delivery_date', today());
        $todayGrowths = \App\Models\GrowthMonitoring::with('child')->whereDate('checkup_date', today());
        $todayScreenings = \App\Models\IlpScreening::with('subjectable')->whereDate('checkup_date', today());

        if (!$hasFullAccess && $staff) {
            $todayPregnancies->where('staff_id', $staffId);
            $todayDeliveries->where('staff_id', $staffId);
            $todayGrowths->where('staff_id', $staffId);
            $todayScreenings->where('staff_id', $staffId);
        }

        $todayPatients = collect();
        foreach ($todayPregnancies->get() as $r) {
            if ($r->mother) {
                $todayPatients->push([
                    'name' => $r->mother->name,
                    'type' => 'Ibu Hamil',
                    'detail_url' => route('mothers.show', $r->mother_id) . '?tab=pemeriksaan',
                ]);
            }
        }
        foreach ($todayDeliveries->get() as $r) {
            if ($r->mother) {
                $todayPatients->push([
                    'name' => $r->mother->name,
                    'type' => 'Ibu Bersalin',
                    'detail_url' => route('mothers.show', $r->mother_id) . '?tab=persalinan',
                ]);
            }
        }
        foreach ($todayGrowths->get() as $r) {
            if ($r->child) {
                $todayPatients->push([
                    'name' => $r->child->name,
                    'type' => 'Balita',
                    'detail_url' => route('childrens.show', $r->child_id) . '?tab=pertumbuhan',
                ]);
            }
        }
        foreach ($todayScreenings->get() as $r) {
            if ($r->subjectable) {
                $todayPatients->push([
                    'name' => $r->subjectable->name,
                    'type' => $r->subjectable_type === \App\Models\Elderly::class ? 'Lansia' : ($r->subjectable_type === \App\Models\Mother::class ? 'Ibu' : 'Anak'),
                    'detail_url' => $r->subjectable_type === \App\Models\Elderly::class 
                        ? route('elderlies.show', $r->subjectable_id) . '?tab=screening'
                        : ($r->subjectable_type === \App\Models\Mother::class ? route('mothers.show', $r->subjectable_id) : route('childrens.show', $r->subjectable_id)),
                ]);
            }
        }
        $todayPatients = $todayPatients->unique('name')->values();

        return view('apps.dashboard.index', compact(
            'totalChildren',
            'childrenGrowth',
            'totalMothers',
            'mothersGrowth',
            'currentMonthVisits',
            'visitGrowth',
            'totalStaff',
            'recentActivities',
            'todayPatients'
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
