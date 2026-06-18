<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IotMeasurementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'birth_height' => ['required', 'numeric'],
            'birth_weight' => ['nullable', 'numeric'],
        ]);

        $data = [
            'birth_height' => $validated['birth_height'],
            'birth_weight' => $validated['birth_weight'] ?? 0,
            'time' => now()->format('Y-m-d H:i:s'),
        ];

        Cache::put('iot_latest_measurement', $data, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'message' => 'Data IoT berhasil diterima',
            'data' => $data,
        ]);
    }

    public function latest()
    {
        $data = Cache::get('iot_latest_measurement');

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada data IoT',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data IoT terbaru berhasil diambil',
            'data' => $data,
        ]);
    }
}
