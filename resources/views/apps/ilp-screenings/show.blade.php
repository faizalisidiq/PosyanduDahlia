@extends('layouts.app')

@section('title', 'Detail Screening ILP')

@section('content')
    <div class="w-full mx-auto space-y-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Screening ILP', 'url' => route('ilp-screenings.index')], ['label' => 'Detail Data']]" />

        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div
                    class="h-16 w-16 bg-purple-100 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                    <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Screening #{{ $ilpScreening->id }}</h1>
                    <p class="text-sm text-gray-500">Tanggal:
                        {{ \Carbon\Carbon::parse($ilpScreening->checkup_date)->format('d F Y') }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('ilp-screenings.edit', $ilpScreening) }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Ubah
                </a>
                <form action="{{ route('ilp-screenings.destroy', $ilpScreening) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 border border-red-100 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                    <h3 class="font-bold text-gray-800">Detail Pasien & Pemeriksaan</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Nama Pasien</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">
                                @if ($ilpScreening->subjectable_type == 'App\Models\Children')
                                    <a href="{{ route('childrens.show', $ilpScreening->subjectable_id) }}"
                                        class="text-teal-600 hover:underline">
                                        {{ $ilpScreening->subjectable->name ?? '-' }}
                                    </a>
                                    <span class="text-xs text-gray-500 block">(Anak)</span>
                                @elseif($ilpScreening->subjectable_type == 'App\Models\Mother')
                                    <a href="{{ route('mothers.show', $ilpScreening->subjectable_id) }}"
                                        class="text-teal-600 hover:underline">
                                        {{ $ilpScreening->subjectable->name ?? '-' }}
                                    </a>
                                    <span class="text-xs text-gray-500 block">(Ibu)</span>
                                @else
                                    {{ $ilpScreening->subjectable->name ?? '-' }}
                                @endif
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Usia Saat Pemeriksaan</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">
                                @php
                                    $results = is_array($ilpScreening->results)
                                        ? $ilpScreening->results
                                        : json_decode($ilpScreening->results ?? '[]', true);
                                    $age = $results['age_snapshot'] ?? null;
                                @endphp

                                @if ($age)
                                    {{ $age['years'] }} Tahun {{ $age['months'] }} Bulan
                                @elseif($ilpScreening->subjectable && $ilpScreening->subjectable->birth_date)
                                    @php
                                        $diff = \Carbon\Carbon::parse($ilpScreening->checkup_date)->diff(
                                            $ilpScreening->subjectable->birth_date,
                                        );
                                    @endphp
                                    {{ $diff->y }} Tahun {{ $diff->m }} Bulan
                                @else
                                    -
                                @endif
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Petugas Pemeriksa</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $ilpScreening->staff->user->name ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Results Card -->
            @php
                $results = is_array($ilpScreening->results)
                    ? $ilpScreening->results
                    : json_decode($ilpScreening->results, true) ?? [];
            @endphp
            <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-teal-50/30">
                    <h3 class="font-bold text-teal-800">Hasil Indikator Kesehatan</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div class="sm:col-span-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Berat Badan</dt>
                            <dd class="mt-1 text-lg font-medium text-gray-900">{{ $results['weight'] ?? '-' }} kg</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tinggi Badan</dt>
                            <dd class="mt-1 text-lg font-medium text-gray-900">{{ $results['height'] ?? '-' }} cm</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tekanan Darah</dt>
                            <dd class="mt-1 text-lg font-medium text-gray-900">{{ $results['blood_pressure'] ?? '-' }} mmHg
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gula Darah</dt>
                            <dd class="mt-1 text-lg font-medium text-gray-900">{{ $results['blood_sugar'] ?? '-' }} mg/dL
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan / Diagnosis
                            </dt>
                            <dd
                                class="mt-2 text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 whitespace-pre-line">
                                {{ $results['note'] ?? 'Tidak ada catatan.' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
