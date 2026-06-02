@extends('layouts.app')

@section('title', 'Detail Persalinan')

@section('content')
<div class="w-full mx-auto space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Persalinan', 'url' => route('childbirth-records.index')],
        ['label' => 'Detail Persalinan']
    ]" />

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
             <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                 <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Persalinan #{{ $childbirthRecord->id }}</h1>
                <p class="text-sm text-gray-500">Tanggal: {{ $childbirthRecord->delivery_date->format('d F Y') }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('childbirth-records.edit', $childbirthRecord) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Ubah
            </a>
            <form action="{{ route('childbirth-records.destroy', $childbirthRecord) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 border border-red-100 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
             <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Detail Persalinan</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Nama Ibu</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            <a href="{{ route('mothers.show', $childbirthRecord->mother) }}" class="text-teal-600 hover:text-teal-700 hover:underline">
                                {{ $childbirthRecord->mother->name }}
                            </a>
                        </dd>
                    </div>
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Petugas Penolong</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $childbirthRecord->staff->user->name }} - {{ $childbirthRecord->staff->role }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Tempat Persalinan</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $childbirthRecord->delivery_location }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Kondisi Bayi</dt>
                        <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $childbirthRecord->baby_condition }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Note / Placeholder Card -->
        <div class="space-y-6">
             <div class="bg-teal-50 rounded-xl border border-teal-100 p-6">
                 <h3 class="font-bold text-teal-900 mb-2">Penting!</h3>
                 <p class="text-sm text-teal-800 leading-relaxed">
                     Segera daftarkan bayi sebagai anak baru (Data Anak) untuk memantau tumbuh kembangnya di posyandu.
                 </p>
                 <div class="mt-4">
                     <a href="{{ route('childrens.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                         + Tambah Data Anak
                     </a>
                 </div>
             </div>
        </div>
    </div>
</div>
@endsection
