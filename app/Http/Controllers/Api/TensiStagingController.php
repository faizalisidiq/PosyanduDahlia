<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TensiStagingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'systolic' => 'required|integer|min:60|max:250',
            'diastolic' => 'required|integer|min:40|max:150',
            'pulse' => 'required|integer|min:30|max:200',
            'measured_at' => 'required|date',
        ]);

        Cache::put('latest_tensi_reading', $data, now()->addMinutes(10));

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function latest()
    {
        $data = Cache::get('latest_tensi_reading');

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Belum ada data'], 404);
        }

        return response()->json(['success' => true, 'data' => $data]);
    }
}