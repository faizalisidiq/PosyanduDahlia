@extends('layouts.auth')

@section('title', 'Ambil Antrian')

@section('content')
    <div class="mb-10 text-center md:text-left">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Ambil Antrian</h2>
        <p class="text-gray-500">Masukkan NIK Ibu untuk mengambil nomor antrian posyandu.</p>
    </div>

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg border border-red-100 flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('queues.public.check') }}" method="POST">
        @csrf
        <div class="mb-6">
            <label for="identity_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Induk Kependudukan (NIK)
                Ibu</label>
            <input type="text" id="identity_number" name="identity_number"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
                placeholder="Contoh: 3201234567890001" required value="{{ old('identity_number') }}" autofocus>
            @error('identity_number')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full py-3 px-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg shadow-md transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
            Cari Data
        </button>

        <div class="mt-6 text-center">
            <a href="/" class="text-sm text-gray-500 hover:text-teal-600 transition-colors">Kembali ke Beranda</a>
        </div>
    </form>
@endsection
