@extends('layouts.app')

@section('title', 'Manajemen Antrian')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Manajemen Antrian Hari Ini</h2>
                <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('queues.public.monitor') }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Buka Layar Monitor
                </a>
                <a href="{{ route('queues.public.index') }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Kiosk Pendaftaran
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center shadow-sm">
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Current Active Queue Card --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-32 h-32 text-teal-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
                    </svg>
                </div>

                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">Sedang Dipanggil</h3>

                @if ($currentQueue)
                    <div class="flex items-center gap-6">
                        <div
                            class="bg-teal-50 text-teal-700 px-6 py-4 rounded-lg border border-teal-100 text-center min-w-[120px]">
                            <span class="block text-4xl font-black">{{ $currentQueue->queue_number }}</span>
                            <span class="text-xs font-semibold uppercase">Nomor</span>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900">{{ $currentQueue->child->name }}</h4>
                            <p class="text-gray-500 mb-2">{{ $currentQueue->child->mother->name }} (Ibu)</p>
                            <div class="flex gap-2">
                                <form action="{{ route('queues.status', $currentQueue) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit"
                                        class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md font-medium transition-colors">
                                        Selesai Periksa
                                    </button>
                                </form>
                                <a href="{{ route('growth-monitorings.create', ['child_id' => $currentQueue->child_id]) }}"
                                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md font-medium transition-colors">
                                    Input Data Timbang
                                </a>
                                <form action="{{ route('queues.status', $currentQueue) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="skipped">
                                    <button type="submit"
                                        class="px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white text-sm rounded-md font-medium transition-colors">
                                        Lewati
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center h-32 text-gray-400 font-medium italic">
                        Belum ada antrian dipanggil.
                    </div>
                @endif
            </div>

            <div
                class="bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white flex flex-col justify-between">
                <div>
                    <p class="text-teal-100 text-sm font-medium mb-1">Total Antrian</p>
                    <h3 class="text-4xl font-bold">{{ $queues->count() }}</h3>
                </div>
                <div>
                    <p class="text-teal-100 text-sm font-medium mb-1">Menunggu</p>
                    <h3 class="text-2xl font-bold">{{ $queues->where('status', 'waiting')->count() }}</h3>
                </div>
                <div>
                    <p class="text-teal-100 text-sm font-medium mb-1">Selesai</p>
                    <h3 class="text-2xl font-bold">{{ $queues->where('status', 'completed')->count() }}</h3>
                </div>
            </div>
        </div>

        {{-- Queue List Table --}}
        <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Daftar Antrian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold whitespace-nowrap">No. Antrian</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap">Nama Anak</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap">Nama Ibu</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap">Status</th>
                            <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($queues as $queue)
                            <tr
                                class="hover:bg-gray-50/80 transition-colors {{ $queue->status == 'called' ? 'bg-teal-50/50' : '' }}">
                                <td class="px-6 py-4 font-bold text-gray-800 whitespace-nowrap">
                                    {{ $queue->queue_number }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $queue->child->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                    {{ $queue->child->mother->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'waiting' => 'bg-gray-100 text-gray-600',
                                            'called' => 'bg-teal-100 text-teal-700 animate-pulse',
                                            'processing' => 'bg-blue-100 text-blue-700',
                                            'completed' => 'bg-green-100 text-green-700',
                                            'skipped' => 'bg-red-100 text-red-700',
                                        ];
                                        $statusLabels = [
                                            'waiting' => 'Menunggu',
                                            'called' => 'Dipanggil',
                                            'processing' => 'Sedang Diperiksa',
                                            'completed' => 'Selesai',
                                            'skipped' => 'Dilewati',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-bold uppercase {{ $statusClasses[$queue->status] ?? 'bg-gray-100' }}">
                                        {{ $statusLabels[$queue->status] ?? $queue->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    @if ($queue->status == 'waiting')
                                        <form action="{{ route('queues.status', $queue) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="called">
                                            <button type="submit"
                                                class="text-teal-600 hover:text-teal-800 font-bold hover:underline">
                                                Panggil
                                            </button>
                                        </form>
                                    @endif

                                    @if (in_array($queue->status, ['called', 'processing']))
                                        <form action="{{ route('queues.status', $queue) }}" method="POST"
                                            class="inline-block ml-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit"
                                                class="text-green-600 hover:text-green-800 font-bold hover:underline">
                                                Selesai
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">
                                    Belum ada antrian hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
