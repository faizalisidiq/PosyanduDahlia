<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title') | {{ config('app.name', 'Posyandu') }} - Administrasi Kesehatan Profesional</title>
    <meta name="description"
        content="Portal Masuk Sistem Informasi Posyandu. Akses aman untuk kader dan staf kesehatan untuk pemantauan kesehatan ibu dan anak.">
    <meta name="keywords" content="login posyandu, portal kader, kesehatan ibu dan anak, administrasi posyandu">
    <meta name="author" content="Nexus Studio Hub">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Akses Portal | {{ config('app.name', 'Posyandu') }}">
    <meta property="og:description" content="Masuk ke sistem manajemen posyandu terpadu.">
    <meta property="og:image" content="{{ asset('assets/images/bg_1.jpg') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-5xl bg-white shadow-2xl overflow-hidden rounded-md flex flex-row">
            {{-- Left Side: Image & Branding --}}
            <div class="hidden md:flex w-1/2 relative bg-gray-900 text-white flex-col justify-center items-center p-12 bg-cover bg-center"
                style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
                <div class="absolute inset-0 bg-black/40"></div> {{-- Overlay --}}
                <div class="relative z-10 text-center">
                    <h1 class="text-5xl font-bold font-serif italic mb-2">{{ config('app.name', 'Posyandu') }}</h1>
                    <p class="text-2xl font-semibold tracking-wider mb-4">
                        {{ config('app.location_name', 'DESA REJOSARI') }}</p>
                    <p class="text-lg font-light tracking-widest uppercase mb-8">Kesehatan Keluarga Prioritas Kami</p>
                </div>
                <div class="absolute bottom-10 text-center z-10 opacity-80">
                    <p class="font-bold text-xl">Sistem Informasi Terpadu</p>
                    <p class="text-xs mt-2">Hubungi Kami : +62 812 3456 7890</p>
                    <p class="text-xs">www.posyandu.id</p>
                </div>
            </div>

            {{-- Right Side: Content (Login Form) --}}
            <div class="w-full md:w-1/2 p-10 md:p-16 flex flex-col justify-center bg-white">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
