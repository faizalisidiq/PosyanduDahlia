@extends('layouts.app')

@section('title', 'Detail Posyandu')

@section('content')
<div class="w-full mx-auto space-y-6">
    <!-- Breadcrumb / Back -->
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Posyandu', 'url' => route('health-posts.index')],
        ['label' => $healthPost->name]
    ]" />

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ $healthPost->name }}</h1>
        <div class="flex items-center space-x-3">
            <a href="{{ route('health-posts.edit', $healthPost) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Ubah
            </a>
            <form action="{{ route('health-posts.destroy', $healthPost) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline-block">
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Info Card -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Informasi Umum</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nama Posyandu</label>
                            <p class="text-gray-900 font-medium">{{ $healthPost->name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nomor Telepon</label>
                            <p class="text-gray-900">{{ $healthPost->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Alamat</label>
                        <p class="text-gray-900 leading-relaxed">{{ $healthPost->address ?? '-' }}</p>
                    </div>
                     <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Dibuat Pada</label>
                        <p class="text-gray-600 text-sm">{{ $healthPost->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Side Card (Stats or Other Info Mockup) -->
        <div class="md:col-span-1 space-y-6">
             <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-[0_5px_15px_rgba(20,184,166,0.3)] border border-transparent overflow-hidden text-white p-6 relative">
                 <div class="relative z-10">
                     <h3 class="font-bold text-lg mb-1">Statistik Cepat</h3>
                     <p class="text-teal-100 text-sm mb-4">Ringkasan data posyandu ini.</p>
                     
                     <div class="space-y-4">
                         <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                             <div class="text-2xl font-bold">{{ $healthPost->staffs_count }}</div>
                             <div class="text-xs text-teal-100 uppercase">Total Kader</div>
                         </div>
                         <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                             <div class="text-2xl font-bold">-</div>
                             <div class="text-xs text-teal-100 uppercase">Total Kegiatan</div>
                         </div>
                     </div>
                 </div>
                 
                 <!-- Decoration -->
                 <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                 <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-teal-400/20 rounded-full blur-xl"></div>
            </div>
        </div>
    </div>
</div>
@endsection
