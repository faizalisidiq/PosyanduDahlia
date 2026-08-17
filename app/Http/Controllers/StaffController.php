<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $staffs = Staff::with(['user', 'healthPost'])
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('apps.staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $healthPosts = \App\Models\HealthPost::all();
        return view('apps.staffs.create', compact('healthPosts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffRequest $request)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            try {
                $data = $request->validated();

                // handle new user
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);

                if ($request->hasFile('avatar')) {
                    $data['avatar'] = Storage::disk('public')->putFile(Staff::AVATAR_PATH, $request->file('avatar'));
                }

                $data['status'] = 'active';
                $staff = Staff::make($data);
                $staff->user()->associate($user);
                $staff->saveOrFail();

                return redirect()->route('staffs.index')->with('success', 'Staff created successfully');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Failed to create staff: ' . $th->getMessage())->withInput();
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        $staff->load(['user', 'healthPost']);
        return view('apps.staffs.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        $healthPosts = \App\Models\HealthPost::all();
        return view('apps.staffs.edit', compact('staff', 'healthPosts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $staff) {
            try {
                $data = $request->validated();
                
                // Update User info
                $userData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                ];
                
                if (!empty($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }

                $staff->user->update($userData);

                if ($request->hasFile('avatar')) {
                    if ($staff->avatar) {
                        Storage::disk('public')->delete($staff->avatar);
                    }
                    $data['avatar'] = Storage::disk('public')->putFile(Staff::AVATAR_PATH, $request->file('avatar'));
                }

                $staff->fill($data);
                $staff->saveOrFail();

                return redirect()->route('staffs.index')->with('success', 'Staff updated successfully');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Failed to update staff: ' . $th->getMessage())->withInput();
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($staff) {
            try {
                if ($staff->avatar) {
                    Storage::disk('public')->delete($staff->avatar);
                }
                
                // Delete user (cascade will delete staff usually, but specific logic here)
                $user = $staff->user;
                $staff->delete(); // Delete staff first
                if ($user) $user->delete(); // Then user

                return redirect()->route('staffs.index')->with('success', 'Staff deleted successfully');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Failed to delete staff: ' . $th->getMessage());
            }
        });
    }

    /**
     * Approve the specialized staff.
     */
    public function approve(Staff $staff)
    {
        $staff->update(['status' => 'active']);
        return redirect()->back()->with('success', 'Staff registration approved successfully.');
    }

    /**
     * Get tasks for the staff.
     */
    public function getTasks(Staff $staff)
    {
        // 1. Check if phone is empty
        if (empty($staff->phone)) {
            return response()->json([
                'success' => false,
                'error' => 'phone_empty',
                'message' => 'Nomor WhatsApp staff belum tersedia.'
            ]);
        }

        // 2. Format phone number
        $formattedPhone = $this->formatPhoneNumber($staff->phone);

        // 3. Fetch related tasks
        $pregnancyRecords = $staff->pregnancyRecords()->with('mother')->get();
        $childbirthRecords = \App\Models\ChildbirthRecord::where('staff_id', $staff->id)->with(['mother', 'children'])->get();
        $growthMonitorings = $staff->growthMonitorings()->with('child')->get();
        $ilpScreenings = $staff->ilpScreenings()->with('subjectable')->get();

        $mothers = collect();
        $children = collect();
        $elderlies = collect();

        // DATA IBU
        foreach ($pregnancyRecords as $record) {
            if ($record->mother) {
                $mothers->push($record->mother->name);
            }
        }
        foreach ($childbirthRecords as $record) {
            if ($record->mother) {
                $mothers->push($record->mother->name);
            }
        }
        foreach ($ilpScreenings as $record) {
            if ($record->subjectable_type === 'App\Models\Mother' && $record->subjectable) {
                $mothers->push($record->subjectable->name);
            }
        }

        // DATA BALITA
        foreach ($growthMonitorings as $record) {
            if ($record->child) {
                $children->push($record->child->name);
            }
        }
        foreach ($childbirthRecords as $record) {
            if ($record->children) {
                $children->push($record->children->name);
            }
        }
        foreach ($ilpScreenings as $record) {
            if ($record->subjectable_type === 'App\Models\Children' && $record->subjectable) {
                $children->push($record->subjectable->name);
            }
        }

        // DATA LANSIA
        foreach ($ilpScreenings as $record) {
            if ($record->subjectable_type === 'App\Models\Elderly' && $record->subjectable) {
                $elderlies->push($record->subjectable->name);
            }
        }

        $mothers = $mothers->unique()->sort()->values();
        $children = $children->unique()->sort()->values();
        $elderlies = $elderlies->unique()->sort()->values();

        // 4. Check if tasks are empty
        if ($mothers->isEmpty() && $children->isEmpty() && $elderlies->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => 'tasks_empty',
                'message' => 'Belum ada data pemeriksaan yang ditugaskan kepada staff ini.'
            ]);
        }

        // 5. Generate formatted message
        $name = $staff->user->name;
        $message = "Halo {$name},\n\nBerikut daftar tugas pemeriksaan Anda.\n\n";

        $categoriesCount = 0;
        if ($mothers->isNotEmpty()) $categoriesCount++;
        if ($children->isNotEmpty()) $categoriesCount++;
        if ($elderlies->isNotEmpty()) $categoriesCount++;

        $useDivider = $categoriesCount > 1;

        if ($mothers->isNotEmpty()) {
            if ($useDivider) {
                $message .= "========================\n\n";
            }
            $message .= "DATA IBU\n\n";
            foreach ($mothers as $mother) {
                $message .= "• {$mother}\n";
            }
            $message .= "\n";
        }

        if ($children->isNotEmpty()) {
            if ($useDivider) {
                $message .= "========================\n\n";
            }
            $message .= "DATA BALITA\n\n";
            foreach ($children as $child) {
                $message .= "• {$child}\n";
            }
            $message .= "\n";
        }

        if ($elderlies->isNotEmpty()) {
            if ($useDivider) {
                $message .= "========================\n\n";
            }
            $message .= "DATA LANSIA\n\n";
            foreach ($elderlies as $elderly) {
                $message .= "• {$elderly}\n";
            }
            $message .= "\n";
        }

        if ($useDivider) {
            $message .= "========================\n\n";
        }

        $message .= "Silakan melakukan pemeriksaan sesuai jadwal.\n\nTerima kasih.";

        return response()->json([
            'success' => true,
            'phone' => $formattedPhone,
            'message' => $message,
            'tasks' => [
                'mothers' => $mothers,
                'children' => $children,
                'elderlies' => $elderlies,
            ]
        ]);
    }

    /**
     * Format Indonesian phone numbers to international standard format.
     */
    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) {
            return null;
        }
        
        // Remove any non-numeric characters
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        // If it starts with 0, replace the leading 0 with 62
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }
        
        // If it starts with 62, return it
        if (str_starts_with($clean, '62')) {
            return $clean;
        }
        
        // If it starts with 8, prepend 62
        if (str_starts_with($clean, '8')) {
            $clean = '62' . $clean;
        }

        return $clean;
    }
}
