@extends('layouts.auth')
@section('title', 'Masuk Portal')

@section('content')
    <div class="mb-10 text-center md:text-left">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang!</h2>
        <p class="text-sm text-gray-500">Silakan masuk untuk melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" autocomplete="off">
        @csrf

        <!-- Email Address -->
        <div class="mb-6 relative">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="off"
                class="block w-full rounded-md border-gray-100 bg-gray-100/50 py-4 pl-12 pr-6 text-gray-900 placeholder-gray-400 focus:bg-white focus:border-gray-300 focus:ring-0 shadow-xs transition-colors duration-200 @error('email') border-red-500 @enderror"
                placeholder="Email / Nama Pengguna">
            @error('email')
                <p class="mt-2 text-sm text-red-600 pl-4">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-6 relative">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="block w-full rounded-md border-gray-100 bg-gray-100/50 py-4 pl-12 pr-12 text-gray-900 placeholder-gray-400 focus:bg-white focus:border-gray-300 focus:ring-0 shadow-xs transition-colors duration-200 @error('password') border-red-500 @enderror"
                placeholder="Kata Sandi">
            <div class="absolute inset-y-0 right-0 pr-5 flex items-center cursor-pointer text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600 pl-4">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col space-y-6 mt-8">
             <label for="remember" class="inline-flex items-center">
                <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                    class="rounded border-gray-300 text-gray-900 shadow-xs focus:ring-gray-900 h-4 w-4">
                <span class="ms-2 text-sm text-gray-500">Ingat Saya</span>
            </label>

            <button type="submit"
                class="w-full inline-flex justify-center items-center px-8 py-3 bg-gray-800 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('MASUK') }}
                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </button>
        </div>

        <div class="mt-8 text-center text-xs text-gray-500">
            Belum punya akun? <a href="{{ route('register') }}" class="underline hover:text-gray-900">Daftar sekarang!</a>
            @if (Route::has('password.request'))
                <br>
                <a class="underline hover:text-gray-900 mt-2 inline-block" href="{{ route('password.request') }}">
                    {{ __('Lupa Kata Sandi?') }}
                </a>
            @endif
        </div>
    </form>
@endsection
