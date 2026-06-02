@extends('layouts.app')

@section('title', 'Detail Anak')

@section('content')
<div class="w-full mx-auto space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Anak', 'url' => route('childrens.index')],
        ['label' => $children->name]
    ]" />

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
             <div class="h-16 w-16 flex-shrink-0">
                @if($children->gender == 'male')
                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-2xl border-2 border-white shadow-sm">
                        {{ substr($children->name, 0, 1) }}
                    </div>
                @else
                    <div class="h-16 w-16 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 font-bold text-2xl border-2 border-white shadow-sm">
                        {{ substr($children->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $children->name }}</h1>
                <p class="text-sm text-gray-500">Ibu: {{ $children->mother->name }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('childrens.edit', $children) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Ubah
            </a>
            <form action="{{ route('childrens.destroy', $children) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline-block">
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
                    <h3 class="font-bold text-gray-800">Biodata Lengkap</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</label>
                            <p class="text-gray-900 font-medium break-words">{{ $children->birth_place }}, {{ $children->birth_date->format('d F Y') }}</p>
                        </div>
                         <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Usia Saat Ini</label>
                            @php
                                $diff = \Carbon\Carbon::parse($children->birth_date)->diff(now());
                            @endphp
                            <p class="text-gray-900 font-mono">{{ $diff->y }} Tahun {{ $diff->m }} Bulan</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                            <p class="text-gray-900 font-medium capitalize">{{ $children->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nama Ibu</label>
                            <a href="{{ route('mothers.show', $children->mother) }}" class="text-teal-600 hover:underline font-medium">
                                {{ $children->mother->name }}
                            </a>
                        </div>
                         <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Berat Lahir</label>
                            <p class="text-gray-900 font-medium">{{ $children->birth_weight }} kg</p>
                        </div>
                         <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tinggi Lahir</label>
                            <p class="text-gray-900 font-medium">{{ $children->birth_height }} cm</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Faskes (BPJS)</label>
                            <p class="text-gray-900 font-medium">{{ $children->bpjs_facility ?? '-' }}</p>
                        </div>
                    </div>
                     <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Terdaftar Sejak</label>
                        <p class="text-gray-600 text-sm">{{ $children->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Growth Status Placeholder -->
        <div class="md:col-span-1 space-y-6">
             <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-[0_5px_15px_rgba(99,102,241,0.3)] border border-transparent overflow-hidden text-white p-6 relative">
                 <div class="relative z-10">
                     <h3 class="font-bold text-lg mb-1">Status Pertumbuhan</h3>
                     <p class="text-indigo-100 text-sm mb-4">Pengukuran terakhir.</p>
                     
                     {{-- Latest growth passed from controller --}}

                     <!-- Mockup Stats -->
                     <div class="space-y-4">
                         <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                             <div class="text-xs text-indigo-100 uppercase">Berat Badan (BB)</div>
                             <div class="font-medium text-2xl mt-1">
                                 {{ $latestGrowth ? $latestGrowth->weight . ' kg' : '- kg' }}
                             </div>
                             @if($latestGrowth)
                                <div class="text-[10px] text-indigo-200 mt-1">
                                    {{ $latestGrowth->checkup_date->format('d M Y') }}
                                </div>
                             @endif
                         </div>
                         <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                             <div class="text-xs text-indigo-100 uppercase">Tinggi Badan (TB)</div>
                             <div class="font-medium text-2xl mt-1">
                                 {{ $latestGrowth ? $latestGrowth->height . ' cm' : '- cm' }}
                             </div>
                         </div>
                     </div>
                 </div>
                 
                 <!-- Decoration -->
                 <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                 <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-indigo-400/20 rounded-full blur-xl"></div>
            </div>
        </div>
    </div>


    <!-- KMS Chart & Info Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Chart Column (2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden h-full">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Grafik Pertumbuhan KMS</h3>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded font-medium">WHO 2020 (0-60 Bulan)</span>
                </div>
                <div class="p-6">
                    <!-- Data storage -->
                    <script>
                        window.kmsData = {
                            gender: @json($children->gender),
                            standards: @json($standards),
                            childData: @json($childGrowthData)
                        };
                    </script>
                    
                    <div class="relative w-full" style="height: 400px;">
                        <canvas id="kmsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Column (1/3) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden h-full">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Keterangan Status Gizi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Status Legends -->
                    <div class="flex items-start space-x-3">
                        <div class="w-4 h-4 rounded-full bg-yellow-200 border border-yellow-300 mt-1 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Gizi Lebih (> +3 SD)</p>
                            <p class="text-xs text-gray-500">Anak memiliki berat badan berlebih. Perlu konsultasi dokter untuk pengaturan pola makan.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="w-4 h-4 rounded-full bg-teal-100 border border-teal-200 mt-1 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Gizi Baik (-2 SD s/d +2 SD)</p>
                            <p class="text-xs text-gray-500">Pertumbuhan anak normal dan sehat. Pertahankan pola asuh dan makan yang baik.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="w-4 h-4 rounded-full bg-yellow-100 border border-yellow-200 mt-1 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Gizi Kurang (-3 SD s/d -2 SD)</p>
                            <p class="text-xs text-gray-500">Berat badan anak kurang. Perlu perhatian khusus pada asupan nutrisi.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="w-4 h-4 rounded-full bg-red-100 border border-red-200 mt-1 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Gizi Buruk (< -3 SD)</p>
                            <p class="text-xs text-gray-500">Kondisi kritis. Segera rujuk ke fasilitas kesehatan untuk penanganan medis.</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Rekomendasi</h4>
                        <div class="bg-blue-50 text-blue-800 text-xs p-3 rounded-lg border border-blue-100">
                            <strong>Pantau Terus!</strong> Lakukan penimbangan rutin setiap bulan di Posyandu untuk memastikan tumbuh kembang anak optimal.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Table Section -->
    <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden mt-6">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Riwayat Pemeriksaan</h3>
            <div class="flex items-center space-x-2">
                @php
                    $waLink = URL::signedRoute('childrens.public-export-history', $children);
                    $waMessage = "Halo Ibu " . $children->mother->name . ",\n\nBerikut adalah riwayat pertumbuhan anak Anda (" . $children->name . ").\nSilakan unduh melalui tautan berikut:\n" . $waLink;
                    
                    $phone = $children->mother->phone_number ?? '';
                    // Remove non-numeric characters
                    $phone = preg_replace('/[^0-9]/', '', $phone);
                    // Replace leading 0 with 62
                    if (str_starts_with($phone, '0')) {
                        $phone = '62' . substr($phone, 1);
                    }
                    
                    $waUrl = "https://wa.me/" . $phone . "?text=" . urlencode($waMessage);
                @endphp
                
                <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 border border-green-100 text-xs font-medium rounded-lg hover:bg-green-100 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Kirim WA
                </a>

                <a href="{{ route('childrens.export-history', $children) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Usia</th>
                        <th class="px-6 py-4 font-semibold">BB (kg)</th>
                        <th class="px-6 py-4 font-semibold">TB (cm)</th>
                        <th class="px-6 py-4 font-semibold">L. Kepala (cm)</th>
                         <th class="px-6 py-4 font-semibold">L. Lengan (cm)</th>
                        <th class="px-6 py-4 font-semibold">Status Gizi</th>
                        <th class="px-6 py-4 font-semibold">Catatan</th>
                        <th class="px-6 py-4 font-semibold">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($growthHistory as $record)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $record->checkup_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $ageInMonths = \Carbon\Carbon::parse($children->birth_date)->diffInMonths($record->checkup_date);
                                @endphp
                                <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs font-semibold">
                                    {{ $ageInMonths }} Bulan
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $record->weight }}</td>
                            <td class="px-6 py-4 font-medium">{{ $record->height }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $record->head_circumference ?? '-' }}</td>
                             <td class="px-6 py-4 text-gray-500">{{ $record->arm_circumference ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($record->status == 'Gizi Buruk')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $record->status }}
                                    </span>
                                @elseif($record->status == 'Gizi Kurang')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $record->status }}
                                    </span>
                                @elseif($record->status == 'Gizi Lebih')
                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $record->status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                        {{ $record->status ?? 'Normal' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate" title="{{ $record->note }}">
                                {{ $record->note ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600 mr-2">
                                        {{ substr($record->staff->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="text-xs font-medium">{{ $record->staff->name ?? '-' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>Belum ada riwayat pemeriksaan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $growthHistory->links() }}
        </div>
    </div>
</div>

<!-- Load KMS Chart Logic -->
@vite(['resources/js/kms-chart.js'])
<script type="module">
    // Since vite scripts use type="module" which are deferred by nature, 
    // we use an interval check or simple timeout to ensure the window.initKmsChart is loaded.
    // A cleaner way in production is importing, but we chose window attachment for flexibility.
    
    const initChart = () => {
        if (typeof window.initKmsChart === 'function') {
            window.initKmsChart('kmsChart', window.kmsData.gender, window.kmsData.standards, window.kmsData.childData);
        } else {
            setTimeout(initChart, 100);
        }
    };
    
    initChart();
</script>
@endsection
