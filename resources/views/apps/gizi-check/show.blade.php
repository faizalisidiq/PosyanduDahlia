@extends('layouts.auth')

@section('title', 'Grafik Pertumbuhan - ' . $child->name)

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $child->name }}</h2>
        <p class="text-sm text-gray-500">
            {{ $child->gender == 'male' ? 'Laki-laki' : 'Perempuan' }} &middot;
            Lahir {{ \Carbon\Carbon::parse($child->birth_date)->format('d F Y') }}
        </p>
    </div>

    @if ($latestGrowth)
        <div class="mb-6 grid grid-cols-3 gap-4 text-center">
            <div class="bg-gray-50 rounded-md p-4">
                <p class="text-xs text-gray-500">Berat Badan</p>
                <p class="font-bold text-gray-900">{{ $latestGrowth->weight }} kg</p>
            </div>
            <div class="bg-gray-50 rounded-md p-4">
                <p class="text-xs text-gray-500">Tinggi Badan</p>
                <p class="font-bold text-gray-900">{{ $latestGrowth->height }} cm</p>
            </div>
            <div class="bg-gray-50 rounded-md p-4">
                <p class="text-xs text-gray-500">Status Gizi</p>
                <p class="font-bold text-gray-900">{{ $latestGrowth->status }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Grafik Pertumbuhan KMS</h3>
        </div>
        <div class="p-6">
            <script>
                window.kmsData = {
                    gender: @json($child->gender),
                    standards: @json($standards),
                    childData: @json($childGrowthData)
                };
            </script>
            <div class="relative w-full" style="height: 350px;">
                <canvas id="kmsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Riwayat Pemeriksaan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Berat</th>
                        <th class="px-6 py-3 text-left">Tinggi</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($growthHistory as $record)
                        <tr>
                            <td class="px-6 py-3">{{ $record->checkup_date->format('d M Y') }}</td>
                            <td class="px-6 py-3">{{ $record->weight }} kg</td>
                            <td class="px-6 py-3">{{ $record->height }} cm</td>
                            <td class="px-6 py-3">{{ $record->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat pemeriksaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('gizi.index') }}" class="text-base text-gray-500 hover:text-teal-600 transition-colors">Cek Anak Lain</a>
    </div>

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