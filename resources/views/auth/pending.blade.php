@extends('layouts.auth')

@section('content')
    <div class="text-center">
        <div class="mb-6 flex justify-center">
            <div class="p-4 bg-amber-50 rounded-full">
                <svg class="w-12 h-12 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Menunggu Validasi</h2>
        
        <p class="text-gray-600 mb-8 leading-relaxed">
            Terima kasih telah mendaftar sebagai <strong>Anggota Kader</strong>. 
            <br><br>
            Saat ini akun Anda sedang dalam proses peninjauan oleh <strong>Ketua Kader</strong>. Anda akan mendapatkan akses penuh setelah akun Anda divalidasi.
        </p>

        <div class="space-y-4">
            <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-sm text-blue-700">
                    Silakan hubungi Ketua Kader di Posyandu Anda untuk mempercepat proses validasi.
                </p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full inline-flex justify-center items-center px-8 py-3 bg-gray-800 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 transition ease-in-out duration-150">
                    Keluar Sekarang
                </button>
            </form>
        </div>
    </div>
@endsection
