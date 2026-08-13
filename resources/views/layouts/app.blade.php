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
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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

    <!-- SweetAlert2 Global Confirm Dialog Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('submit', function(e) {
                const form = e.target;
                
                // Regular soft delete
                if (form.classList.contains('delete-form')) {
                    if (form.dataset.confirmed) {
                        return;
                    }
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin ingin mengarsipkan data ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#0d9488', // Teal 600
                        cancelButtonColor: '#ef4444',  // Red 500
                        confirmButtonText: 'Ya, Arsipkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.dataset.confirmed = true;
                            form.submit();
                        }
                    });
                }
                
                // Permanent delete
                if (form.classList.contains('delete-permanent-form')) {
                    if (form.dataset.confirmed) {
                        return;
                    }
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin ingin menghapus data ini secara permanen?',
                        text: 'Tindakan ini tidak dapat dibatalkan!',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444', // Red 500
                        cancelButtonColor: '#6b7280', // Gray 500
                        confirmButtonText: 'Ya, Hapus Permanen',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.dataset.confirmed = true;
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
