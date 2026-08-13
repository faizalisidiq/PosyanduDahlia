@extends('layouts.auth')

@section('title', 'Nomor Antrian')

@section('content')
    <div class="text-center py-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-6">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h2 class="text-3xl font-bold text-gray-900 mb-2">Berhasil!</h2>
        <p class="text-gray-500 mb-8">Silakan simpan nomor antrian Anda.</p>

        <div id="tickets-container" class="bg-white p-2 rounded-xl">
            @foreach ($queues as $queue)
                <div
                    class="ticket-card bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-6 mb-6 relative overflow-hidden bg-white shadow-sm">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-teal-400 to-teal-600"></div>

                    <p class="text-sm uppercase tracking-widest text-gray-500 mb-2 font-semibold">Nomor Antrian</p>
                    <div class="text-6xl font-black text-gray-800 mb-4 tracking-tight">{{ $queue->queue_number }}</div>

                    <div
                        class="flex flex-col gap-1 items-center justify-center text-sm text-gray-600 border-t border-gray-200 pt-4 mt-4">
                        <p><span class="font-bold">{{ $queue->child->name }}</span></p>
                        <p>{{ $queue->date->format('d M Y') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        @if (session('info'))
            <div class="mb-6 text-sm text-blue-600 bg-blue-50 p-3 rounded-lg">
                {{ session('info') }}
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <div class="grid grid-cols-2 gap-3">
                <button onclick="window.print()"
                    class="w-full py-3 px-4 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-lg shadow-md transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </button>
                <button id="download-btn"
                    class="w-full py-3 px-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg shadow-md transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Simpan
                </button>
            </div>

            <a href="{{ route('queues.public.index') }}"
                class="w-full py-3 px-4 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition-colors">
                Kembali ke Menu Utama
            </a>
        </div>
    </div>

    <script>
        document.getElementById('download-btn').addEventListener('click', function() {
            const container = document.getElementById('tickets-container');
            const button = this;

            // Visual feedback
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="animate-pulse">Memproses...</span>';
            button.disabled = true;

            if (window.htmlToImage) {
                window.htmlToImage.toPng(container, {
                        skipFonts: true,
                        backgroundColor: '#ffffff'
                    })
                    .then(function(dataUrl) {
                        const link = document.createElement('a');
                        @if ($queues->count() > 1)
                            link.download =
                                'Tiket-Antrian-Batch-{{ $queues->first()->date->format('Ymd') }}.png';
                        @else
                            link.download = 'Antrian-{{ $queues->first()->queue_number }}.png';
                        @endif
                        link.href = dataUrl;
                        link.click();

                        // Reset button
                        button.innerHTML = originalText;
                        button.disabled = false;
                    })
                    .catch(function(error) {
                        console.error('oops, something went wrong!', error);
                        alert('Gagal mengunduh tiket. Silakan coba lagi.');
                        button.innerHTML = originalText;
                        button.disabled = false;
                    });
            } else {
                alert('Library download belum siap. Silakan refresh halaman.');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        });
    </script>
@endsection
