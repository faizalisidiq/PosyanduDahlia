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
}
