<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title') | {{ config('app.name', 'Posyandu') }} - Sistem Informasi Kesehatan Terpadu</title>
    <meta name="description" content="Sistem Informasi Posyandu Terintegrasi (Nexus Studio Hub) - Platform digital profesional untuk pemantauan kesehatan ibu, anak, dan layanan primer (ILP).">
    <meta name="keywords" content="posyandu digital, kesehatan ibu dan anak, sistem informasi posyandu, screening ILP, rekap penimbangan balita, nexus studio hub">
    <meta name="author" content="Nexus Studio Hub">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title') | {{ config('app.name', 'Posyandu') }}">
    <meta property="og:description" content="Pantau kesehatan ibu dan anak secara digital dan terintegrasi dengan Sistem Informasi Posyandu.">
    <meta property="og:image" content="{{ asset('assets/images/bg_1.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title') | {{ config('app.name', 'Posyandu') }}">
    <meta property="twitter:description" content="Pantau kesehatan ibu dan anak secara digital dan terintegrasi dengan Sistem Informasi Posyandu.">
    <meta property="twitter:image" content="{{ asset('assets/images/bg_1.jpg') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            
            <!-- Topbar -->
            @include('layouts.topbar')

            <!-- Page Content -->
            <main class="w-full grow p-6">
                @yield('content')
            </main>
            
        </div>
    </div>
</body>

</html>
