@extends('layouts.app')

@section('title', 'Detail Lansia')

@section('content')
    <div class="w-full mx-auto space-y-6" x-data="{ activeTab: '{{ request('tab', 'biodata') }}' }">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Data Lansia', 'url' => route('elderlies.index')], ['label' => $elderly->name]]" />

        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="h-16 w-16 flex-shrink-0">
                    <div
                        class="h-16 w-16 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold text-2xl border-2 border-white shadow-sm">
                        {{ substr($elderly->name, 0, 1) }}
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $elderly->name }}</h1>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold {{ $elderly->gender === 'male' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-pink-50 text-pink-700 border border-pink-100' }}">
                            {{ $elderly->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                            {{ \Carbon\Carbon::parse($elderly->birth_date)->age }} Tahun
                        </span>
                    </div>
                    <p class="text-base text-gray-500">NIK: {{ $elderly->identity_number }} | BPJS:
                        {{ $elderly->social_security_number ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('elderlies.edit', $elderly) }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-base font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Ubah
                </a>
                <form action="{{ route('elderlies.destroy', $elderly) }}" method="POST" class="delete-form inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 border border-red-100 text-base font-medium rounded-lg hover:bg-red-100 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                    :class="activeTab === 'biodata' ? 'border-teal-500 text-teal-600 font-bold' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-base whitespace-nowrap transition-all focus:outline-none">
                    Biodata
                </button>
                <button @click="activeTab = 'screening'"
                    :class="activeTab === 'screening' ? 'border-teal-500 text-teal-600 font-bold' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-base whitespace-nowrap transition-all focus:outline-none">
                    Screening ILP
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <!-- Biodata Tab -->
        <div x-show="activeTab === 'biodata'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Biodata Lengkap</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">NIK
                                    (KTP)</label>
                                <p class="text-gray-900 font-medium font-mono tracking-wide">
                                    {{ $elderly->identity_number }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Tempat,
                                    Tanggal Lahir</label>
                                <p class="text-gray-900 font-medium break-words">{{ $elderly->birth_place ?? '-' }},
                                    {{ $elderly->birth_date->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Usia</label>
                                <p class="text-gray-900 font-mono">{{ \Carbon\Carbon::parse($elderly->birth_date)->age }}
                                    Tahun</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Jenis
                                    Kelamin</label>
                                <p class="text-gray-900 font-medium">
                                    {{ $elderly->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Nomor
                                    Telepon</label>
                                <p class="text-gray-900 font-medium">{{ $elderly->phone_number ?? '-' }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Golongan
                                    Darah</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-50 text-red-700 border border-red-100">
                                    {{ $elderly->blood_type ?? '-' }}
                                </span>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Faskes
                                    (BPJS)</label>
                                <p class="text-gray-900 font-medium">{{ $elderly->health_facility ?? '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Alamat
                                Domisili</label>
                            <p class="text-gray-900 leading-relaxed">{{ $elderly->address ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Terdaftar
                                Sejak</label>
                            <p class="text-gray-600 text-base">{{ $elderly->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Health Status Sidebar -->
            <div class="md:col-span-1 space-y-6">
                <div
                    class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-md border border-transparent overflow-hidden text-white p-6 relative">
                    <div class="relative z-10">
                        <h3 class="font-bold text-lg mb-1">Pemeriksaan Terakhir</h3>
                        <p class="text-teal-100 text-base mb-4 font-light">Ringkasan Screening ILP Terakhir.</p>

                        @php
                            $latestScreening = $screenings->first();
                        @endphp

                        <div class="space-y-4">
                            <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                <div class="text-sm text-teal-100 uppercase">Tekanan Darah</div>
                                <div class="font-bold text-xl mt-1">
                                    {{ $latestScreening->results['blood_pressure'] ?? '-' }}
                                </div>
                            </div>
                            <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                <div class="text-sm text-teal-100 uppercase">Gula Darah</div>
                                <div class="font-bold text-xl mt-1">
                                    {{ $latestScreening->results['blood_sugar'] ?? '-' }} mg/dL
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-teal-400/20 rounded-full blur-xl"></div>
                </div>
                <!-- Vital Signs Card -->
                <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800">Tanda Vital Terakhir</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        @php
                            $suhuStatus = null;
                            $suhuColor = 'gray';
                            if ($elderly->temperature) {
                                if ($elderly->temperature < 35.0) {
                                    $suhuStatus = 'Suhu Rendah';
                                    $suhuColor = 'blue';
                                } elseif ($elderly->temperature <= 37.5) {
                                    $suhuStatus = 'Normal';
                                    $suhuColor = 'green';
                                } else {
                                    $suhuStatus = 'Demam';
                                    $suhuColor = 'red';
                                }
                            }

                            $tensiStatus = null;
                            $tensiColor = 'gray';
                            if ($elderly->systolic_pressure && $elderly->diastolic_pressure) {
                                $sys = $elderly->systolic_pressure;
                                $dia = $elderly->diastolic_pressure;
                                if ($sys < 90 || $dia < 60) {
                                    $tensiStatus = 'Hipotensi';
                                    $tensiColor = 'blue';
                                } elseif ($sys < 120 && $dia < 80) {
                                    $tensiStatus = 'Normal';
                                    $tensiColor = 'green';
                                } elseif ($sys < 140 || $dia < 90) {
                                    $tensiStatus = 'Prahipertensi';
                                    $tensiColor = 'yellow';
                                } elseif ($sys < 160 || $dia < 100) {
                                    $tensiStatus = 'Hipertensi Tingkat 1';
                                    $tensiColor = 'orange';
                                } else {
                                    $tensiStatus = 'Hipertensi Tingkat 2';
                                    $tensiColor = 'red';
                                }
                            }

                            $badgeClasses = [
                                'gray' => 'bg-gray-100 text-gray-500',
                                'blue' => 'bg-blue-100 text-blue-700',
                                'green' => 'bg-green-100 text-green-700',
                                'yellow' => 'bg-yellow-100 text-yellow-700',
                                'orange' => 'bg-orange-100 text-orange-700',
                                'red' => 'bg-red-100 text-red-700',
                            ];
                        @endphp

                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Suhu Tubuh</span>
                                <span class="text-gray-900 font-semibold text-sm">
                                    {{ $elderly->temperature ? number_format($elderly->temperature, 1) . '°C' : '-' }}
                                </span>
                            </div>
                            @if ($suhuStatus)
                                <div class="flex justify-end mt-1">
                                    <span
                                        class="text-xs font-medium px-2 py-0.5 rounded-full {{ $badgeClasses[$suhuColor] }}">{{ $suhuStatus }}</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Tekanan Darah</span>
                                <span class="text-gray-900 font-semibold text-sm">
                                    @if ($elderly->systolic_pressure && $elderly->diastolic_pressure)
                                        {{ $elderly->systolic_pressure }}/{{ $elderly->diastolic_pressure }}
                                        mmHg
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            @if ($tensiStatus)
                                <div class="flex justify-end mt-1">
                                    <span
                                        class="text-xs font-medium px-2 py-0.5 rounded-full {{ $badgeClasses[$tensiColor] }}">{{ $tensiStatus }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Nadi</span>
                            <span class="text-gray-900 font-semibold text-sm">
                                {{ $elderly->pulse ? $elderly->pulse . ' bpm' : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Screening ILP Tab -->
        <div x-show="activeTab === 'screening'" class="space-y-6" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800">Riwayat Screening ILP</h3>
                        <p class="text-sm text-gray-500 mt-1">Daftar screening kesehatan Integrasi Layanan Primer (ILP)
                            lansia.</p>
                    </div>
                    <a href="{{ route('ilp-screenings.create', ['lansia_id' => $elderly->id]) }}"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-base font-medium rounded-lg shadow-sm transition-colors">
                        + Tambah Screening
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider border-b border-gray-100">
                                <th class="px-6 py-4 font-semibold">Tgl Pemeriksaan</th>
                                <th class="px-6 py-4 font-semibold">Tekanan Darah</th>
                                <th class="px-6 py-4 font-semibold">Gula Darah (mg/dL)</th>
                                <th class="px-6 py-4 font-semibold">Kolesterol (mg/dL)</th>
                                <th class="px-6 py-4 font-semibold">Gejala / Diagnosa</th>
                                <th class="px-6 py-4 font-semibold">Petugas</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-base text-gray-700">
                            @forelse($screenings as $record)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $record->checkup_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-mono font-medium text-gray-800">
                                        {{ $record->results['blood_pressure'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-mono">
                                        {{ $record->results['blood_sugar'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-mono">
                                        {{ $record->results['cholesterol'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate"
                                        title="{{ $record->results['note'] ?? '-' }}">
                                        {{ $record->results['note'] ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="h-6 w-6 rounded-full bg-teal-50 text-teal-700 font-bold flex items-center justify-center text-[10px] mr-2">
                                                {{ substr($record->staff->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <span class="text-sm">{{ $record->staff->user->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('ilp-screenings.show', $record) }}"
                                            class="text-teal-600 hover:text-teal-700 font-medium">Detail</a>
                                        <a href="{{ route('ilp-screenings.edit', $record) }}"
                                            class="text-blue-600 hover:text-blue-700 font-medium">Ubah</a>
                                        <form action="{{ route('ilp-screenings.destroy', $record) }}" method="POST"
                                            class="delete-form inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-700 font-medium">Arsipkan</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        Belum ada riwayat screening ILP.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
