@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="space-y-6">
        <!-- Hero Section -->
        <div class="relative bg-gray-900 text-white rounded-xl overflow-hidden p-12 bg-cover bg-center shadow-lg"
            style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
            <div class="absolute inset-0 bg-black/40"></div> {{-- Overlay --}}
            <div class="relative z-10 text-center">
                <h1 class="text-4xl md:text-5xl font-bold font-serif italic mb-4">{{ config('app.name', 'Posyandu') }}</h1>
                <p class="text-lg font-light tracking-widest uppercase mb-6">Kesehatan Keluarga Prioritas Kami</p>
                <div class="opacity-90">
                    <p class="font-bold text-xl">Sistem Informasi Terpadu</p>
                </div>
            </div>
        </div>
        <!-- Welcome Section -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name ?? 'User' }}! 👋</h2>
                <p class="text-gray-500 mt-1">Berikut adalah ringkasan kegiatan Posyandu hari ini.</p>
            </div>
            <div class="hidden md:block">
                <button
                    class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    + Data Baru
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-semibold {{ $childrenGrowth >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded-full">
                        {{ $childrenGrowth >= 0 ? '+' : '' }}{{ number_format($childrenGrowth, 1) }}%
                    </span>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Balita</h3>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalChildren) }}</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-50 text-red-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-semibold {{ $mothersGrowth >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded-full">
                        {{ $mothersGrowth >= 0 ? '+' : '' }}{{ number_format($mothersGrowth, 1) }}%
                    </span>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Ibu Terdaftar</h3>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalMothers) }}</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded-full">Aktif</span>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Petugas Kesehatan</h3>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalStaff) }}</p>
            </div>

            <!-- Card 4 -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-yellow-50 text-yellow-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-semibold {{ $visitGrowth >= 0 ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50' }} px-2 py-1 rounded-full">
                        {{ $visitGrowth >= 0 ? '+' : '' }}{{ number_format($visitGrowth, 1) }}%
                    </span>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Kunjungan Bulan Ini</h3>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($currentMonthVisits) }}</p>
            </div>
        </div>

        <!-- Analytics Charts Section -->
        <div class="space-y-6">
            <!-- Main Trend Chart -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Analisis Tren Bulanan</h3>
                        <p class="text-sm text-gray-500">Perbandingan Kunjungan vs Pendaftaran Anak Baru (12 Bulan Terakhir)
                        </p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-teal-600 mr-1"></span>
                            Kunjungan</span>
                        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-500 mr-1"></span> Anak
                            Baru</span>
                    </div>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Secondary Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Gender Demographics -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col">
                    <h3 class="font-bold text-gray-800 mb-2">Demografi Gender</h3>
                    <p class="text-xs text-gray-500 mb-6">Distribusi jenis kelamin anak terdaftar.</p>
                    <div class="relative flex-1 min-h-[250px] flex items-center justify-center">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>

                <!-- Nutritional Status -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col">
                    <h3 class="font-bold text-gray-800 mb-2">Status Gizi Balita</h3>
                    <p class="text-xs text-gray-500 mb-6">Berdasarkan data pemeriksaan terakhir.</p>
                    <div class="relative flex-1 min-h-[250px]">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Top Staff -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col">
                    <h3 class="font-bold text-gray-800 mb-2">Top Performance Staff</h3>
                    <p class="text-xs text-gray-500 mb-6">Jumlah pemeriksaan bulan ini.</p>
                    <div class="relative flex-1 min-h-[250px]">
                        <canvas id="staffChart"></canvas>
                    </div>
                </div>

                <!-- Age Distribution -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col">
                    <h3 class="font-bold text-gray-800 mb-2">Distribusi Umur Balita</h3>
                    <p class="text-xs text-gray-500 mb-6">Berdasarkan kunjungan bulan ini.</p>
                    <div class="relative flex-1 min-h-[250px]">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Riwayat Kunjungan & Pertumbuhan Terakhir</h3>
                <a href="{{ route('growth-monitorings.index') }}"
                    class="text-sm text-teal-600 hover:text-teal-700 font-medium">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Nama Balita</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Nama Orang Tua</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Tanggal</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Petugas</th>
                            <th class="px-6 py-4 font-medium text-right whitespace-nowrap">Status Gizi</th>
                        </tr>
                    </thead>
                    @forelse($recentActivities as $activity)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-800 whitespace-nowrap">
                                {{ $activity->child->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $activity->child->mother->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $activity->checkup_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                {{ $activity->staff->user->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                @if ($activity->status == 'Gizi Buruk' || $activity->status == 'Stunted')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $activity->status }}
                                    </span>
                                @elseif($activity->status == 'Gizi Kurang')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $activity->status }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $activity->status ?? 'Normal' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 whitespace-nowrap">
                                Belum ada data kunjungan terbaru.
                            </td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection

<!-- Script for Data -->
<script>
    window.dashboardData = {
        months: @json($months),
        visits: @json($visitsData),
        newChildren: @json($newChildrenData),
        gender: @json($genderData),
        status: {
            labels: @json($statusLabels),
            values: @json($statusValues)
        },
        topStaff: @json($topStaff),
        age: @json($age)
    };
</script>

@vite(['resources/js/dashboard.js'])
