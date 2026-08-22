@extends('layouts.auth')

@section('title', 'Layanan Posyandu')

@section('content')
    <div class="mb-10 text-center md:text-left">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
        <p class="text-gray-500">Silakan pilih layanan yang ingin Anda gunakan.</p>
    </div>

    <div class="space-y-4">
        <a href="{{ route('queues.public.index') }}"
            class="block border-2 border-teal-100 rounded-xl p-5 hover:border-teal-500 hover:shadow-md transition-all bg-teal-50">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-teal-600 text-white mr-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Ambil Nomor Antrian</h3>
                    <p class="text-sm text-gray-500">Daftar antrian pemeriksaan hari ini</p>
                </div>
            </div>
        </a>

        <a href="{{ route('gizi.index') }}"
            class="block border-2 border-indigo-100 rounded-xl p-5 hover:border-indigo-500 hover:shadow-md transition-all bg-indigo-50">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600 text-white mr-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Cek Gizi &amp; Pertumbuhan Anak</h3>
                    <p class="text-sm text-gray-500">Lihat perkembangan gizi anak dengan NIK</p>
                </div>
            </div>
        </a>
    </div>
@endsection