@extends('layouts.app')

@section('title', 'Detail Anak')

@section('content')
<div class="w-full mx-auto space-y-6" x-data="{ activeTab: '{{ request('tab', 'biodata') }}' }">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Anak', 'url' => route('childrens.index')],
        ['label' => $children->name]
    ]" />

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
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
                <p class="text-base text-gray-500">Ibu: {{ $children->mother->name }} | Gender: {{ $children->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('childrens.edit', $children) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-base font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Ubah
            </a>
            <form action="{{ route('childrens.destroy', $children) }}" method="POST" class="delete-form inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 border border-red-100 text-base font-medium rounded-lg hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Arsipkan
                </button>
            </form>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-6 -mb-px" aria-label="Tabs">
            <button @click="activeTab = 'biodata'"
                :class="activeTab === 'biodata' ? 'border-teal-500 text-teal-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="py-4 px-1 border-b-2 font-medium text-base whitespace-nowrap transition-all focus:outline-none">
                Biodata
            </button>
            <button @click="activeTab = 'pertumbuhan'"
                :class="activeTab === 'pertumbuhan' ? 'border-teal-500 text-teal-600 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="py-4 px-1 border-b-2 font-medium text-base whitespace-nowrap transition-all focus:outline-none">
                Riwayat Pertumbuhan
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <!-- Biodata Tab -->
    <div x-show="activeTab === 'biodata'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Info Card -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Biodata Lengkap</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</label>
                            <p class="text-gray-900 font-medium break-words">{{ $children->birth_place }}, {{ $children->birth_date->format('d F Y') }}</p>
                        </div>
                         <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Usia Saat Ini</label>
                            @php
                                $diff = \Carbon\Carbon::parse($children->birth_date)->diff(now());
                            @endphp
                            <p class="text-gray-900 font-mono">{{ $diff->y }} Tahun {{ $diff->m }} Bulan</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                            <p class="text-gray-900 font-medium capitalize">{{ $children->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Nama Ibu</label>
                            <a href="{{ route('mothers.show', $children->mother) }}" class="text-teal-600 hover:underline font-medium">
                                {{ $children->mother->name }}
                            </a>
                        </div>
                         <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Berat Lahir</label>
                            <p class="text-gray-900 font-medium">{{ $children->birth_weight }} kg</p>
                        </div>
                         <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Tinggi Lahir</label>
                            <p class="text-gray-900 font-medium">{{ $children->birth_height }} cm</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Faskes (BPJS)</label>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Suhu Tubuh</label>
                            <p class="text-gray-900 font-medium">
                                {{ $children->temperature ? number_format($children->temperature, 1) . '°C' : '-' }}
                            </p>
                            @php
                                $suhuStatus = null;
                                $suhuColor = 'gray';
                                if ($children->temperature) {
                                    if ($children->temperature < 35.0) {
                                        $suhuStatus = 'Suhu Rendah';
                                        $suhuColor = 'blue';
                                    } elseif ($children->temperature <= 37.5) {
                                        $suhuStatus = 'Normal';
                                        $suhuColor = 'green';
                                    } else {
                                        $suhuStatus = 'Demam';
                                        $suhuColor = 'red';
                                    }
                                }
                                $badgeClasses = [
                                    'gray'  => 'bg-gray-100 text-gray-500',
                                    'blue'  => 'bg-blue-100 text-blue-700',
                                    'green' => 'bg-green-100 text-green-700',
                                    'red'   => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            @if($suhuStatus)
                                <span class="inline-block mt-1 text-xs font-medium px-2 py-0.5 rounded-full {{ $badgeClasses[$suhuColor] }}">{{ $suhuStatus }}</span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Faskes (BPJS)</label>
                            <p class="text-gray-900 font-medium">{{ $children->bpjs_facility ?? '-' }}</p>
                        </div>
                    </div>
                     <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Terdaftar Sejak</label>
                        <p class="text-gray-600 text-base">{{ $children->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Growth Status Sidebar -->
        <div class="md:col-span-1 space-y-6">
             <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-md border border-transparent overflow-hidden text-white p-6 relative">
                 <div class="relative z-10">
                     <h3 class="font-bold text-lg mb-1">Status Pertumbuhan</h3>
                     <p class="text-indigo-100 text-base mb-4">Pengukuran terakhir.</p>

                     <div class="space-y-4">
                          <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                              <div class="text-sm text-indigo-100 uppercase">Berat Badan (BB)</div>
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
                              <div class="text-sm text-indigo-100 uppercase">Tinggi Badan (TB)</div>
                              <div class="font-medium text-2xl mt-1">
                                  {{ $latestGrowth ? $latestGrowth->height . ' cm' : '- cm' }}
                              </div>
                          </div>
                      </div>
                 </div>
                 <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                 <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-indigo-400/20 rounded-full blur-xl"></div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pertumbuhan Tab -->
    <div x-show="activeTab === 'pertumbuhan'" class="space-y-6" x-cloak>
        <!-- KMS Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Grafik Pertumbuhan KMS</h3>
                        <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded font-medium">WHO 2020 (0-60 Bulan)</span>
                    </div>
                    <div class="p-6">
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

            <!-- Info Status Legends -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Keterangan Status Gizi</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-4 h-4 rounded-full bg-yellow-200 border border-yellow-300 mt-1 flex-shrink-0"></div>
                            <div>
                                <p class="text-base font-semibold text-gray-900">Gizi Lebih (> +3 SD)</p>
                                <p class="text-sm text-gray-500">Anak memiliki berat badan berlebih. Perlu konsultasi dokter untuk pengaturan pola makan.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-4 h-4 rounded-full bg-teal-100 border border-teal-200 mt-1 flex-shrink-0"></div>
                            <div>
                                <p class="text-base font-semibold text-gray-900">Gizi Baik (-2 SD s/d +2 SD)</p>
                                <p class="text-sm text-gray-500">Pertumbuhan anak normal dan sehat. Pertahankan pola asuh dan makan yang baik.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-4 h-4 rounded-full bg-yellow-100 border border-yellow-200 mt-1 flex-shrink-0"></div>
                            <div>
                                <p class="text-base font-semibold text-gray-900">Gizi Kurang (-3 SD s/d -2 SD)</p>
                                <p class="text-sm text-gray-500">Berat badan anak kurang. Perlu perhatian khusus pada asupan nutrisi.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-4 h-4 rounded-full bg-red-100 border border-red-200 mt-1 flex-shrink-0"></div>
                            <div>
                                <p class="text-base font-semibold text-gray-900">Gizi Buruk (< -3 SD)</p>
                                <p class="text-sm text-gray-500">Kondisi kritis. Segera rujuk ke fasilitas kesehatan untuk penanganan medis.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h3 class="font-bold text-gray-800">Riwayat Penimbangan & Pengukuran</h3>
                    <p class="text-sm text-gray-500 mt-1">Daftar perkembangan berat, tinggi, lingkar kepala, dan status gizi anak.</p>
                </div>
                <div class="flex items-center space-x-2">
                    @php
                        $waLink = URL::signedRoute('childrens.public-export-history', $children);
                        $waMessage = "Halo Ibu " . $children->mother->name . ",\n\nBerikut adalah riwayat pertumbuhan anak Anda (" . $children->name . ").\nSilakan unduh melalui tautan berikut:\n" . $waLink;

                        $phone = $children->mother->phone_number ?? '';
                        $phone = preg_replace('/[^0-9]/', '', $phone);
                        if (str_starts_with($phone, '0')) {
                            $phone = '62' . substr($phone, 1);
                        }
                        $waUrl = "https://wa.me/" . $phone . "?text=" . urlencode($waMessage);
                    @endphp

                    <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 border border-green-100 text-sm font-medium rounded-lg hover:bg-green-100 transition-colors">
                        Kirim WA
                    </a>
                    <a href="{{ route('childrens.export-history', $children) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        Export Excel
                    </a>
                    <a href="{{ route('growth-monitorings.create', ['child_id' => $children->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors shadow-sm">
                        + Tambah Pertumbuhan
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-500 text-sm uppercase tracking-wider border-b border-gray-100">
                            <th class="px-6 py-4 font-semibold">Tgl Pemeriksaan</th>
                            <th class="px-6 py-4 font-semibold">Usia</th>
                            <th class="px-6 py-4 font-semibold">BB (kg)</th>
                            <th class="px-6 py-4 font-semibold">TB (cm)</th>
                            <th class="px-6 py-4 font-semibold">L. Kepala</th>
                            <th class="px-6 py-4 font-semibold">LILA</th>
                            <th class="px-6 py-4 font-semibold">Status Gizi</th>
                            <th class="px-6 py-4 font-semibold">Petugas</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-base text-gray-700">
                        @forelse($growthHistory as $record)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $record->checkup_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $ageInMonths = \Carbon\Carbon::parse($children->birth_date)->diffInMonths($record->checkup_date);
                                    @endphp
                                    <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-sm font-semibold">
                                        {{ $ageInMonths }} Bulan
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium">{{ $record->weight }} kg</td>
                                <td class="px-6 py-4 font-medium">{{ $record->height }} cm</td>
                                <td class="px-6 py-4 text-gray-500">{{ $record->head_circumference ? $record->head_circumference . ' cm' : '-' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $record->arm_circumference ? $record->arm_circumference . ' cm' : '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($record->status == 'Gizi Buruk')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            {{ $record->status }}
                                        </span>
                                    @elseif($record->status == 'Gizi Kurang')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            {{ $record->status }}
                                        </span>
                                    @elseif($record->status == 'Gizi Lebih')
                                         <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            {{ $record->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-teal-100 text-teal-800">
                                            {{ $record->status ?? 'Normal' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-6 w-6 rounded-full bg-indigo-55 bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-[10px] mr-2">
                                            {{ substr($record->staff->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-sm">{{ $record->staff->user->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('growth-monitorings.show', $record) }}" class="text-teal-600 hover:text-teal-700 font-medium">Detail</a>
                                    <a href="{{ route('growth-monitorings.edit', $record) }}" class="text-blue-600 hover:text-blue-700 font-medium">Ubah</a>
                                    <form action="{{ route('growth-monitorings.destroy', $record) }}" method="POST" class="delete-form inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium">Arsipkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada data pemeriksaan pertumbuhan.
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
</div>

<!-- Load KMS Chart Logic -->
@vite(['resources/js/kms-chart.js'])
<script type="module">
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
