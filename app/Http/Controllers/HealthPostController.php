<?php

namespace App\Http\Controllers;

use App\Models\HealthPost;
use App\Http\Requests\StoreHealthPostRequest;
use App\Http\Requests\UpdateHealthPostRequest;
use Illuminate\Http\Request;

class HealthPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $healthPosts = HealthPost::withCount('staffs')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('apps.health-posts.index', compact('healthPosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('apps.health-posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHealthPostRequest $request)
    {
        try {
            $data = $request->validated();

            $healthPost = HealthPost::create($data);
            $healthPost->saveOrFail();

            return redirect()->route('health-posts.index')->with('success', 'Posyandu berhasil ditambahkan.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menambahkan posyandu.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HealthPost $healthPost)
    {
        $healthPost->loadCount('staffs');
        return view('apps.health-posts.show', compact('healthPost'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HealthPost $healthPost)
    {
        return view('apps.health-posts.edit', compact('healthPost'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHealthPostRequest $request, HealthPost $healthPost)
    {
        try {
            $data = $request->validated();

            $healthPost->fill($data);
            $healthPost->saveOrFail();

            return redirect()->route('health-posts.index')->with('success', 'Posyandu berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal memperbarui posyandu.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HealthPost $healthPost)
    {
        // Check for associated staff
        if ($healthPost->staffs()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus posyandu. Masih terdapat petugas yang terdaftar di posyandu ini. Silakan pindahkan atau hapus petugas terlebih dahulu.');
        }

        try {
            $healthPost->deleteOrFail();

            return redirect()->route('health-posts.index')->with('success', 'Posyandu berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus posyandu: ' . $th->getMessage());
        }
    }
}
