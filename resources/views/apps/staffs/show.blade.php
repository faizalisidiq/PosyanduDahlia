@extends('layouts.app')

@section('title', 'Detail Staff')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Staff', 'url' => route('staffs.index')],
        ['label' => $staff->user->name]
    ]" />

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
             <div class="h-16 w-16 flex-shrink-0">
                @if($staff->avatar)
                    <img class="h-16 w-16 rounded-full object-cover border-2 border-white shadow-sm" src="{{ asset('storage/' . $staff->avatar) }}" alt="{{ $staff->user->name }}">
                @else
                    <div class="h-16 w-16 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold text-2xl border-2 border-white shadow-sm">
                        {{ substr($staff->user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $staff->user->name }}</h1>
                <p class="text-sm text-gray-500">{{ $staff->role }} - {{ $staff->healthPost->name }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('staffs.edit', $staff) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Ubah
            </a>
            <form action="{{ route('staffs.destroy', $staff) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Akun pengguna ini juga akan dihapus.');" class="inline-block">
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
                    <h3 class="font-bold text-gray-800">Informasi Detail</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Email</label>
                            <p class="text-gray-900 font-medium break-words">{{ $staff->user->email }}</p>
                        </div>
                         <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nomor Telepon</label>
                            <p class="text-gray-900 font-mono">{{ $staff->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Posyandu</label>
                            <p class="text-gray-900 font-medium">{{ $staff->healthPost->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Peran</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $staff->role }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Alamat Domisili</label>
                        <p class="text-gray-900 leading-relaxed">{{ $staff->address ?? '-' }}</p>
                    </div>
                     <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Terdaftar Sejak</label>
                        <p class="text-gray-600 text-sm">{{ $staff->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Descriptions or Other Info -->
        <div class="md:col-span-1 space-y-6">
             <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-[0_5px_15px_rgba(37,99,235,0.3)] border border-transparent overflow-hidden text-white p-6 relative">
                 <div class="relative z-10">
                     <h3 class="font-bold text-lg mb-1">Aktivitas</h3>
                     <p class="text-blue-100 text-sm mb-4">Ringkasan aktivitas pengguna.</p>
                     
                     <!-- Mockup Stats -->
                     <div class="space-y-4">
                         <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                             <div class="text-xs text-blue-100 uppercase">Login Terakhir</div>
                             <div class="font-medium">Belum pernah login</div>
                         </div>
                     </div>
                 </div>
                 
                 <!-- Decoration -->
                 <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                 <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-blue-400/20 rounded-full blur-xl"></div>
            </div>
        </div>
    </div>
</div>
@endsection
