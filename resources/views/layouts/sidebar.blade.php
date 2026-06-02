<aside
    class="flex-shrink-0 w-64 bg-white flex-col hidden md:flex m-4 rounded-2xl border border-gray-200 h-[calc(100vh-2rem)]">

    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b border-gray-100">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center text-white font-bold text-xl">
                P
            </div>
            <span class="text-xl font-bold text-gray-800 tracking-wide">Posyandu</span>
        </a>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <!-- Dashboard Link -->
        <!-- Group: Overview -->
        <div class="px-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Overview
        </div>
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 mb-2
           {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Dashboard
        </a>

        <!-- Group: Master Data -->
        <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Manajemen Data
        </div>

        <a href="{{ route('health-posts.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
           {{ request()->routeIs('health-posts.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('health-posts.*') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Data Posyandu
        </a>

        @if (Auth::user()->staff && Auth::user()->staff->role === 'ketua-kader')
            <a href="{{ route('staffs.index') }}"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
                   {{ request()->routeIs('staffs.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('staffs.*') ? 'text-teal-600' : 'text-gray-400' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Data Staff
            </a>
        @endif

        <a href="{{ route('mothers.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
           {{ request()->routeIs('mothers.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('mothers.*') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            Data Ibu
        </a>

        <a href="{{ route('childrens.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
           {{ request()->routeIs('childrens.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('childrens.*') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Data Anak
        </a>

        <a href="{{ route('elderlies.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
           {{ request()->routeIs('elderlies.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('elderlies.*') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Data Lansia
        </a>

        <!-- Group: Layanan Kesehatan -->
        <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Layanan Kesehatan
        </div>

        <a href="{{ route('queues.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
           {{ request()->routeIs('queues.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('queues.*') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Antrian
        </a>

        <a href="{{ route('growth-monitorings.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
           {{ request()->routeIs('growth-monitorings.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('growth-monitorings.*') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Data Pertumbuhan
        </a>

        <a href="{{ route('pregnancy-records.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
           {{ request()->routeIs('pregnancy-records.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('pregnancy-records.*') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Pemeriksaan Kehamilan
        </a>

        <a href="{{ route('childbirth-records.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
           {{ request()->routeIs('childbirth-records.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('childbirth-records.*') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Persalinan
        </a>

        <a href="{{ route('ilp-screenings.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
           {{ request()->routeIs('ilp-screenings.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('ilp-screenings.*') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Screening ILP
        </a>

        <a href="{{ route('schedules.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
           {{ request()->routeIs('schedules.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('schedules.*') ? 'text-teal-600' : 'text-gray-400' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Jadwal Pemeriksaan
        </a>

        @if (Auth::user()->staff && in_array(Auth::user()->staff->role, ['ketua-kader', 'anggota-kader']))
            <!-- Group: Lainnya -->
            <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Lainnya
            </div>

            <a href="{{ route('settings.index') }}"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 
            {{ request()->routeIs('settings.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('settings.*') ? 'text-teal-600' : 'text-gray-400' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Pengaturan
            </a>
        @endif

        <!-- Add more links here later -->

    </div>

    <!-- User Profile (Bottom) -->
    <div class="border-t border-gray-100 p-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ Auth::user()->name ?? 'Guest' }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    {{ Auth::user()->email ?? 'guest@posyandu.id' }}
                </p>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Overlay & Slide-over -->
<!-- Mobile Sidebar Overlay & Slide-over -->
<div x-show="sidebarOpen" class="fixed inset-0 z-50 flex md:hidden" role="dialog" aria-modal="true">
    <!-- Backdrop with blur -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" aria-hidden="true"
        @click="sidebarOpen = false"></div>

    <!-- Sidebar Panel -->
    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="relative flex-1 flex flex-col max-w-xs w-full bg-white rounded-r-2xl border-r border-gray-200">

        <!-- Close Button (Inside Header) -->
        <div class="flex items-center justify-between px-4 pt-5 pb-2">
            <div class="flex items-center">
                <div
                    class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center text-white font-bold text-xl mr-2">
                    P
                </div>
                <span class="text-xl font-bold text-gray-800 tracking-wide">Posyandu</span>
            </div>
            <button type="button"
                class="ml-1 flex items-center justify-center h-8 w-8 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-teal-500 transition-colors"
                @click="sidebarOpen = false">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 h-0 overflow-y-auto mt-2">
            <nav class="px-2 space-y-1">
                <!-- Mobile Dashboard Link -->
                <!-- Group: Overview -->
                <div class="px-4 mb-2 mt-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Overview
                </div>
                <a href="{{ route('dashboard') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                    {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>

                <!-- Group: Manajemen Data -->
                <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Manajemen Data
                </div>

                <a href="{{ route('health-posts.index') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                    {{ request()->routeIs('health-posts.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('health-posts.*') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Data Posyandu
                </a>

                @if (Auth::user()->staff && Auth::user()->staff->role === 'ketua-kader')
                    <a href="{{ route('staffs.index') }}"
                        class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                            {{ request()->routeIs('staffs.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="mr-4 h-6 w-6 {{ request()->routeIs('staffs.*') ? 'text-teal-600' : 'text-gray-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Data Staff
                    </a>
                @endif

                <a href="{{ route('mothers.index') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                    {{ request()->routeIs('mothers.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('mothers.*') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    Data Ibu
                    </a>
                <a href="{{ route('childrens.index') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                   {{ request()->routeIs('childrens.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('childrens.*') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Data Anak
                </a>

                <a href="{{ route('elderlies.index') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                   {{ request()->routeIs('elderlies.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('elderlies.*') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Data Lansia
                </a>

                <!-- Group: Layanan Kesehatan -->
                <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Layanan Kesehatan
                </div>

                <a href="{{ route('queues.index') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                    {{ request()->routeIs('queues.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('queues.*') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Antrian
                </a>

                <a href="{{ route('growth-monitorings.index') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                    {{ request()->routeIs('growth-monitorings.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('growth-monitorings.*') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Data Pertumbuhan
                </a>

                <a href="{{ route('pregnancy-records.index') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                    {{ request()->routeIs('pregnancy-records.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('pregnancy-records.*') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Pemeriksaan Kehamilan
                </a>

                <a href="{{ route('childbirth-records.index') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                    {{ request()->routeIs('childbirth-records.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('childbirth-records.*') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Persalinan
                </a>

                <a href="{{ route('ilp-screenings.index') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                    {{ request()->routeIs('ilp-screenings.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('ilp-screenings.*') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                            Screening ILP
                </a>

                <a href="{{ route('schedules.index') }}"
                    class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                    {{ request()->routeIs('schedules.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-4 h-6 w-6 {{ request()->routeIs('schedules.*') ? 'text-teal-600' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                            Jadwal Pemeriksaan
                </a>

                @if (Auth::user()->staff && in_array(Auth::user()->staff->role, ['ketua-kader', 'anggota-kader']))
                    <!-- Group: Lainnya -->
                    <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Lainnya
                    </div>

                    <a href="{{ route('settings.index') }}"
                        class="group flex items-center px-4 py-3 text-base font-medium rounded-xl transition-all duration-200
                    {{ request()->routeIs('settings.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="mr-4 h-6 w-6 {{ request()->routeIs('settings.*') ? 'text-teal-600' : 'text-gray-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Pengaturan
                    </a>
                @endif
            </nav>
        </div>
        <div class="flex-shrink-0 flex border-t border-gray-100 p-4 bg-white rounded-br-2xl">
            <div class="flex items-center">
                <div
                    class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-teal-600 font-bold shadow-sm">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="ml-3">
                    <p class="text-base font-medium text-gray-800">{{ Auth::user()->name ?? 'Guest' }}</p>
                    <p class="text-xs font-medium text-gray-500">{{ Auth::user()->email ?? 'guest@posyandu.id' }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex-shrink-0 w-14">
        <!-- Force sidebar to shrink to fit close icon -->
    </div>
</div>
