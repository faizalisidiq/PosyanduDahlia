<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreElderlyRequest;
use App\Http\Requests\UpdateElderlyRequest;
use App\Models\Elderly;

class ElderlyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $elderlies = Elderly::query()
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                    ->orWhere('identity_number', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('apps.elderlies.index', compact('elderlies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('apps.elderlies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreElderlyRequest $request)
    {
        try {
            $data = $request->validated();

            $elderly = Elderly::make($data);
            $elderly->saveOrFail();

            return redirect()->route('elderlies.index')->with('success', 'Elderly data created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create elderly: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Elderly $elderly)
    {
        return view('apps.elderlies.show', compact('elderly'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Elderly $elderly)
    {
        return view('apps.elderlies.edit', compact('elderly'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateElderlyRequest $request, Elderly $elderly)
    {
        try {
            $data = $request->validated();

            $elderly->fill($data);
            $elderly->saveOrFail();

            return redirect()->route('elderlies.index')->with('success', 'Elderly data updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update elderly: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Elderly $elderly)
    {
        try {
            $elderly->deleteOrFail();

            return redirect()->route('elderlies.index')->with('success', 'Elderly data deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete elderly: ' . $e->getMessage());
        }
    }
}
