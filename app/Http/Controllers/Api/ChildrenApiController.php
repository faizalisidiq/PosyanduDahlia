<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Children;
use Illuminate\Http\Request;

class ChildrenApiController extends Controller
{
    public function index()
    {
        $childrens = Children::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data children berhasil diambil',
            'data' => $childrens
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'identity_number' => ['nullable', 'string', 'size:16'],
            'name' => ['required', 'string', 'max:255'],
            'mother_id' => ['required', 'exists:mothers,id'],
            'gender' => ['required', 'in:male,female'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'birth_weight' => ['required', 'numeric', 'min:0'],
            'birth_height' => ['required', 'numeric', 'min:0'],
            'bpjs_facility' => ['nullable', 'string', 'max:255'],
        ]);

        $children = Children::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data children berhasil ditambahkan',
            'data' => $children
        ], 201);
    }

    public function show($id)
    {
        $children = Children::find($id);

        if (!$children) {
            return response()->json([
                'success' => false,
                'message' => 'Data children tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail children berhasil diambil',
            'data' => $children
        ]);
    }
}
