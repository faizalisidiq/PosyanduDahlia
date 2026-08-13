<?php

namespace App\Http\Controllers;

use App\Models\ChildbirthRecord;
use App\Models\HealthPost;
use App\Http\Requests\StoreChildbirthRecordRequest;
use App\Http\Requests\UpdateChildbirthRecordRequest;
use Illuminate\Http\Request;
use App\Services\ExcelExportService;

class ChildbirthRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $staff = $user->staff;
        $hasFullAccess = $staff && $staff->role === 'ketua-kader';

        $childbirthRecords = ChildbirthRecord::with(['mother', 'staff.user'])
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
        
        $healthPosts = HealthPost::all();

        return view('apps.childbirth-records.index', compact('childbirthRecords', 'healthPosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $preselectedMotherId = request('mother_id');
        $mothers = \App\Models\Mother::all();
        $staffs = \App\Models\Staff::with('user')->get();
        return view('apps.childbirth-records.create', compact('mothers', 'staffs', 'preselectedMotherId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChildbirthRecordRequest $request)
    {
        try {
            $data = $request->validated();

            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                // 1. Create Child Record
                $child = \App\Models\Children::create([
                    'identity_number' => $data['child_identity_number'] ?? null,
                    'name' => $data['child_name'],
                    'mother_id' => $data['mother_id'],
                    'gender' => $data['gender'],
                    'birth_place' => $data['birth_place'],
                    'birth_date' => $data['delivery_date'], // Birth date is same as delivery date
                    'birth_weight' => $data['birth_weight'],
                    'birth_height' => $data['birth_height'],
                ]);

                // 2. Create Childbirth Record linked to child
                $childbirthRecord = ChildbirthRecord::make([
                    'mother_id' => $data['mother_id'],
                    'children_id' => $child->id, // Link to new child
                    'staff_id' => $data['staff_id'],
                    'child_order' => $data['child_order'],
                    'delivery_method' => $data['delivery_method'],
                    'delivery_date' => $data['delivery_date'],
                    'delivery_location' => $data['delivery_location'],
                    'baby_condition' => $data['baby_condition'],
                ]);
                
                $childbirthRecord->saveOrFail();

                // 3. Update Mother status to 'menyusui'
                $mother = \App\Models\Mother::find($data['mother_id']);
                if ($mother) {
                    $mother->update(['status' => 'menyusui']);
                }
            });

            return redirect()->route('mothers.show', [$data['mother_id'], 'tab' => 'persalinan'])->with('success', 'Data persalinan dan data anak berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChildbirthRecord $childbirthRecord)
    {
        return view('apps.childbirth-records.show', compact('childbirthRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChildbirthRecord $childbirthRecord)
    {
        $mothers = \App\Models\Mother::all();
        $staffs = \App\Models\Staff::with('user')->get();
        return view('apps.childbirth-records.edit', compact('childbirthRecord', 'mothers', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChildbirthRecordRequest $request, ChildbirthRecord $childbirthRecord)
    {
        try {
            $data = $request->validated();

            $childbirthRecord->fill($data);
            $childbirthRecord->saveOrFail();

            return redirect()->route('mothers.show', [$childbirthRecord->mother_id, 'tab' => 'persalinan'])->with('success', 'Data persalinan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data persalinan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChildbirthRecord $childbirthRecord)
    {
        try {
            $motherId = $childbirthRecord->mother_id;
            $childbirthRecord->deleteOrFail();

            return redirect()->route('mothers.show', [$motherId, 'tab' => 'persalinan'])->with('success', 'Data persalinan berhasil diarsipkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengarsipkan data persalinan: ' . $e->getMessage());
        }
    }

    public function export(Request $request, ExcelExportService $exportService)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            $filters = [
                'health_post_id' => $request->input('health_post_id'),
                'address' => $request->input('address'),
            ];

            $fileName = $exportService->exportChildbirths($startDate, $endDate, $filters);
            return response()->download(storage_path('app/public/' . $fileName))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}
