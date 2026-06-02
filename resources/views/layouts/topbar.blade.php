<header
    class="sticky top-0 z-50 flex items-center justify-between px-6 py-4 bg-transparent">

    <!-- Mobile Sidebar Toggle -->
    <div class="flex items-center md:hidden">
        <button
            @click="sidebarOpen = true"
            type="button"
            class="p-2 rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none">

            <svg
                class="h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Navbar -->
    <div class="flex items-center flex-1 mr-4">

        <div
            class="w-full bg-white border border-gray-200 rounded-full px-8 py-4 text-gray-900 text-2xl font-black uppercase">

            @php
                if(request()->routeIs('mothers.*')) echo 'DATA IBU';
                elseif(request()->routeIs('childrens.*')) echo 'DATA ANAK';
                elseif(request()->routeIs('elderlies.*')) echo 'DATA LANSIA';
                elseif(request()->routeIs('staffs.*')) echo 'DATA STAFF';
                elseif(request()->routeIs('queues.*')) echo 'ANTRIAN';
                elseif(request()->routeIs('growth-monitorings.*')) echo 'PERTUMBUHAN';
                elseif(request()->routeIs('pregnancy-records.*')) echo 'PEMERIKSAAN HAMIL';
                elseif(request()->routeIs('childbirth-records.*')) echo 'PERSALINAN';
                elseif(request()->routeIs('ilp-screenings.*')) echo 'SCREENING ILP';
                elseif(request()->routeIs('health-posts.*')) echo 'POSYANDU';
                elseif(request()->routeIs('schedules.*')) echo 'JADWAL';
                elseif(request()->routeIs('settings.*')) echo 'PENGATURAN';
                else echo 'DASHBOARD';
            @endphp

        </div>
    </div>

    <!-- Profile -->
    <div class="flex items-center ml-4">

        <div class="relative" x-data="{ open: false }">

            <button
                @click="open = !open"
                class="focus:outline-none rounded-full">

                <div
                    class="w-10 h-10 rounded-full bg-teal-600 flex items-center justify-center text-white font-bold border border-white">

                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}

                </div>
            </button>

            <!-- Dropdown -->
            <div
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl py-1 z-50"
                style="display: none;">

                <div class="px-4 py-2 border-b border-gray-100">

                    <p class="text-xs font-semibold text-gray-500 uppercase">
                        Signed in as
                    </p>

                    <p class="text-sm font-bold text-gray-900 truncate">
                        {{ Auth::user()->name ?? 'Guest' }}
                    </p>

                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">

                        Sign out

                    </button>
                </form>

            </div>
        </div>
    </div>

</header>