<?php

namespace App\Http\Controllers;

use App\Models\GrowthMonitoring;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Default to showing tomorrow's appointments (H-1 reminder context)
        $date = $request->get('date', Carbon::tomorrow()->format('Y-m-d'));
        $targetDate = Carbon::parse($date);

        // Get children who have next_checkup_date on the target date
        // We get the latest growth monitoring for each child that has a next_checkup_date
        $schedules = GrowthMonitoring::with(['child.mother'])
            ->whereDate('next_checkup_date', $targetDate)
            ->get();

        return view('apps.schedules.index', compact('schedules', 'date'));
    }
}
