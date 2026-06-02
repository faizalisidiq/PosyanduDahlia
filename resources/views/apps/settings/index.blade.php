@extends('layouts.app')

@section('title', 'Konfigurasi Aplikasi')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="relative bg-gray-900 text-white rounded-xl overflow-hidden p-8 bg-cover bg-center shadow-lg"
        style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold font-serif italic mb-2">Pengaturan Aplikasi</h1>
                <p class="text-gray-200">Kelola identitas dan konfigurasi dasar aplikasi Posyandu.</p>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Informasi Dasar</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui nama aplikasi dan lokasi posyandu.</p>
        </div>

        @if(session('success'))
        <div class="mx-6 mt-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start space-x-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-green-800">Berhasil Disimpan</h3>
                <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <form id="settings-form" action="{{ route('settings.update') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($settings as $key => $config)
                <div>
                    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $config['label'] }}
                    </label>
                    <div class="relative">
                        <input type="{{ $config['type'] ?? 'text' }}" 
                               name="{{ $key }}" 
                               id="{{ $key }}" 
                               value="{{ old($key, $currentValues[$key] ?? '') }}"
                               class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 transition-colors
                               @error($key) border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                               placeholder="Masukkan {{ strtolower($config['label']) }}...">
                        
                        @error($key)
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        @enderror
                    </div>
                    @error($key)
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">Variable: <code class="bg-gray-100 px-1 py-0.5 rounded text-gray-700">{{ $key }}</code></p>
                </div>
                @endforeach
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" 
                        class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-sm transition-all transform hover:scale-[1.02]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('settings-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnContent = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;

        try {
            await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            // If successful, reload to show flush message or update APP_NAME
            setTimeout(() => {
                window.location.reload();
            }, 1000);

        } catch (error) {
            // Network error (likely due to server restart)
            // Wait a bit longer for server to come back up
            console.log('Server restarting...');
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        }
    });
</script>
    </div>
</div>
@endsection
