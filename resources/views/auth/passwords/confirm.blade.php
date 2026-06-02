@extends('layouts.auth')

@section('content')
    <div class="mb-10 text-center md:text-left">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Konfirmasi Kata Sandi</h2>
        <p class="text-sm text-gray-500">Harap konfirmasi kata sandi Anda sebelum melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" autocomplete="off">
        @csrf

        <!-- Password -->
        <div class="mb-6 relative">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="block w-full rounded-md border-gray-100 bg-gray-100/50 py-4 pl-12 pr-6 text-gray-900 placeholder-gray-400 focus:bg-white focus:border-gray-300 focus:ring-0 shadow-xs transition-colors duration-200 @error('password') border-red-500 @enderror"
                placeholder="Kata Sandi">
            @error('password')
                <p class="mt-2 text-sm text-red-600 pl-4">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col space-y-6 mt-8">
            <button type="submit"
                class="w-full inline-flex justify-center items-center px-8 py-3 bg-gray-800 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('KONFIRMASI') }}
                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </button>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-gray-900 text-center underline" href="{{ route('password.request') }}">
                    {{ __('Lupa Kata Sandi?') }}
                </a>
            @endif
        </div>
    </form>
@endsection
