@extends('layouts.app')

@section('title', 'Detail Pemantauan')

@section('content')
<div class="w-full mx-auto space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Pemantauan Pertumbuhan', 'url' => route('growth-monitorings.index')],
        ['label' => 'Detail Pemantauan']
    ]" />

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
             <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                 <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pemantauan #{{ $growthMonitoring->id }}</h1>
                <p class="text-sm text-gray-500">Tanggal: {{ $growthMonitoring->checkup_date->format('d F Y') }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            @if(Auth::user()->staff && Auth::user()->staff->role === 'ketua-kader' && $growthMonitoring->child->mother->phone_number)
                @php
                    $phone = preg_replace('/[^0-9]/', '', $growthMonitoring->child->mother->phone_number);
                    if (str_starts_with($phone, '0')) {
                        $phone = '62' . substr($phone, 1);
                    }

                    $ageInMonths = round($growthMonitoring->child->birth_date->diffInMonths($growthMonitoring->checkup_date, false));
                    
                    $nextScheduleInfo = "";
                    if ($growthMonitoring->next_checkup_date) {
                        $nextScheduleInfo = "\n\nJadwal pemeriksaan berikutnya: " . $growthMonitoring->next_checkup_date->format('d/m/Y');
                    }

                    $message = "Halo Ibu " . $growthMonitoring->child->mother->name . ", ini dari Posyandu. Memberitahukan hasil pemeriksaan " . $growthMonitoring->child->name . " hari ini:\n" .
                               "- Umur: " . $ageInMonths . " Bulan\n" .
                               "- Berat Badan (BB): " . $growthMonitoring->weight . " kg\n" .
                               "- Tinggi Badan (TB): " . $growthMonitoring->height . " cm\n" .
                               "- LILA: " . ($growthMonitoring->arm_circumference ?? '-') . " cm\n" .
                               "- Lingkar Kepala: " . ($growthMonitoring->head_circumference ?? '-') . " cm\n\n" .
                               "Status Gizi: " . $growthMonitoring->status . ".\n" .
                               "Jangan lupa jadwal imunisasi berikutnya ya Bu." . $nextScheduleInfo;
                    $waUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);
                @endphp
                <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Kirim WA
                </a>
            @endif
            <a href="{{ route('growth-monitorings.edit', $growthMonitoring) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Ubah
            </a>
            <form action="{{ route('growth-monitorings.destroy', $growthMonitoring) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline-block">
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
                <h3 class="font-bold text-gray-800">Hasil Pengukuran</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Nama Anak</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            <a href="{{ route('childrens.show', $growthMonitoring->child) }}" class="text-teal-600 hover:text-teal-700 hover:underline">
                                {{ $growthMonitoring->child->name }}
                            </a>
                        </dd>
                    </div>
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Petugas Pemeriksa</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $growthMonitoring->staff->user->name }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Berat Badan</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $growthMonitoring->weight }} <span class="text-sm font-normal text-gray-500">kg</span></dd>
                    </div>
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Tinggi Badan</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $growthMonitoring->height }} <span class="text-sm font-normal text-gray-500">cm</span></dd>
                    </div>
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Status Gizi</dt>
                        <dd class="mt-1">
                             <span class="px-2.5 py-1 rounded-full text-sm font-medium inline-block
                                {{ $growthMonitoring->status == 'Gizi Baik' ? 'bg-green-50 text-green-700 border border-green-100' : 
                                   ($growthMonitoring->status == 'Gizi Kurang' ? 'bg-yellow-50 text-yellow-700 border border-yellow-100' : 
                                   'bg-red-50 text-red-700 border border-red-100') }}">
                                {{ $growthMonitoring->status }}
                            </span>
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Z-Score</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $growthMonitoring->z_score }} SD</dd>
                    </div>
                     <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                        <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $growthMonitoring->note ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Info / Placeholder Card -->
        <div class="space-y-6">
             <div class="bg-blue-50 rounded-xl border border-blue-100 p-6">
                 <h3 class="font-bold text-blue-900 mb-2">Informasi Gizi</h3>
                 <p class="text-sm text-blue-800 leading-relaxed">
                     Pastikan anak mendapatkan asupan gizi yang seimbang sesuai dengan usianya. Pantau terus grafik pertumbuhannya di Kartu Menuju Sehat (KMS).
                 </p>
             </div>
        </div>
    </div>
</div>
@endsection
