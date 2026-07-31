<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuhuReading;
use Illuminate\Http\Request;

class SuhuReadingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'nullable|integer',
            'suhu'       => 'required|numeric|min:30|max:45',
            'satuan'     => 'required|in:C,F',
            'waktu'      => 'required|date',
        ]);

        $reading = SuhuReading::create([
            'patient_id'  => $data['patient_id'] ?? null,
            'suhu'        => $data['suhu'],
            'satuan'      => $data['satuan'],
            'recorded_at' => $data['waktu'],
        ]);

        return response()->json(['success' => true, 'data' => $reading]);
    }
}