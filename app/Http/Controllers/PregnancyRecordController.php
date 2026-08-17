<?php

namespace App\Http\Controllers;

use App\Models\PregnancyRecord;
use App\Http\Requests\StorePregnancyRecordRequest;
use App\Http\Requests\UpdatePregnancyRecordRequest;
use Illuminate\Http\Request;

class PregnancyRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $staff = $user->staff;
        $hasFullAccess = $staff && $staff->role === 'ketua-kader';

        $pregnancyRecords = PregnancyRecord::with(['mother', 'staff.user'])
            ->when(!$hasFullAccess && $staff, function ($query) use ($staff) {
                $query->where('staff_id', $staff->id);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('mother', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(10);
            
        return view('apps.pregnancy-records.index', compact('pregnancyRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $preselectedMotherId = request('mother_id');
        $mothers = \App\Models\Mother::all();
        $staffs = \App\Models\Staff::with('user')->get();
        return view('apps.pregnancy-records.create', compact('mothers', 'staffs', 'preselectedMotherId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePregnancyRecordRequest $request)
    {
        try {
            $data = $request->validated();

            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                // Create Record
                $pregnancyRecord = PregnancyRecord::make($data);
                $pregnancyRecord->saveOrFail();

                // Update Mother's Weight
                $mother = \App\Models\Mother::findOrFail($data['mother_id']);
                $mother->update(['weight' => $data['weight']]);
            });
            
            return redirect()->route('mothers.show', [$data['mother_id'], 'tab' => 'pemeriksaan'])->with('success', 'Pemeriksaan kehamilan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan pemeriksaan kehamilan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PregnancyRecord $pregnancyRecord)
    {
        return view('apps.pregnancy-records.show', compact('pregnancyRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PregnancyRecord $pregnancyRecord)
    {
        $mothers = \App\Models\Mother::all();
        $staffs = \App\Models\Staff::with('user')->get();
        return view('apps.pregnancy-records.edit', compact('pregnancyRecord', 'mothers', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePregnancyRecordRequest $request, PregnancyRecord $pregnancyRecord)
    {
        try {
            $data = $request->validated();

            \Illuminate\Support\Facades\DB::transaction(function () use ($data, $pregnancyRecord) {
                // Update Record
                $pregnancyRecord->fill($data);
                $pregnancyRecord->saveOrFail();

                // Update Mother's Weight
                $pregnancyRecord->mother->update(['weight' => $data['weight']]);
            });
            
            return redirect()->route('mothers.show', [$pregnancyRecord->mother_id, 'tab' => 'pemeriksaan'])->with('success', 'Pemeriksaan kehamilan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pemeriksaan kehamilan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PregnancyRecord $pregnancyRecord)
    {
        try {
            $motherId = $pregnancyRecord->mother_id;
            $pregnancyRecord->deleteOrFail();
            
            return redirect()->route('mothers.show', [$motherId, 'tab' => 'pemeriksaan'])->with('success', 'Pemeriksaan kehamilan berhasil diarsipkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengarsipkan pemeriksaan kehamilan: ' . $e->getMessage());
        }
    }
}
