<?php

namespace App\Http\Controllers;

use App\Models\AnthropometryStandard;
use App\Models\Children;
use App\Models\Mother;
use Illuminate\Http\Request;

class GrowthCheckController extends Controller
{
    /**
     * Form NIK publik.
     */
    public function index()
    {
        return view('apps.gizi-check.index');
    }

    /**
     * Cari ibu berdasarkan NIK, tampilkan daftar anaknya.
     */
    public function check(Request $request)
    {
        $request->validate([
            'identity_number' => 'required|numeric|digits:16',
        ], [
            'identity_number.required' => 'NIK wajib diisi.',
            'identity_number.digits' => 'NIK harus 16 digit angka.',
        ]);

        $mother = Mother::where('identity_number', $request->identity_number)->first();

        if (!$mother) {
            return back()->withInput()->with('error', 'Data Ibu tidak ditemukan. Silakan hubungi kader jika belum terdaftar.');
        }

        $children = $mother->children()->with(['growthMonitorings' => function ($q) {
            $q->orderBy('checkup_date', 'desc');
        }])->get();

        return view('apps.gizi-check.check', compact('mother', 'children'));
    }

    /**
     * Detail pertumbuhan satu anak (grafik KMS + riwayat) — publik, sama seperti nomor tiket antrian.
     */
    public function show(Children $child)
    {
        $child->load('mother');

        $allGrowthData = $child->growthMonitorings()->orderBy('checkup_date', 'asc')->get();
        $growthHistory = $child->growthMonitorings()->orderBy('checkup_date', 'desc')->get();

        $standards = AnthropometryStandard::where('gender', $child->gender)
            ->where('age_in_months', '<=', 60)
            ->orderBy('age_in_months')
            ->get();

        $childGrowthData = $allGrowthData->map(function ($item) use ($child) {
            $ageInMonths = max(0, $child->birth_date->diffInMonths($item->checkup_date));
            return [
                'age_in_months' => $ageInMonths,
                'weight' => $item->weight,
                'checkup_date' => $item->checkup_date->format('d M Y'),
            ];
        });

        $latestGrowth = $allGrowthData->last();

        return view('apps.gizi-check.show', compact('child', 'standards', 'childGrowthData', 'growthHistory', 'latestGrowth'));
    }
}