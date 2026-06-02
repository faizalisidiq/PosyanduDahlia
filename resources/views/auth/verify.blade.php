@extends('layouts.auth')

@section('content')
    <div class="mb-10 text-center md:text-left">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Verify Email</h2>
        <p class="text-sm text-gray-500">Please verify your email address to continue.</p>
    </div>

    @if (session('resent'))
        <div class="mb-4 p-4 rounded-md bg-green-50 text-green-700 text-sm" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    <div class="text-gray-700 text-sm mb-6">
        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email') }},
    </div>

    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <div class="flex flex-col space-y-6">
            <button type="submit"
                class="w-full inline-flex justify-center items-center px-8 py-3 bg-gray-800 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Click here to request another') }}
                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </button>
        </div>
    </form>

    <div class="mt-8 text-center text-xs text-gray-500">
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="underline hover:text-gray-900">
            {{ __('Logout') }}
        </a>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
@endsection
