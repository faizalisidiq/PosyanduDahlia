@extends('layouts.app')

@section('title', $status == 'hamil' ? 'Data Ibu Hamil' : ($status == 'menyusui' ? 'Data Ibu Menyusui' : 'Data Ibu Lainnya'))

@section('content')
<div class="w-full space-y-6" x-data="{ exportModal: false }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Status Switcher -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('mothers.index', ['status' => 'hamil']) }}" 
               class="px-4 py-2 text-sm font-bold rounded-lg transition-all duration-200 shadow-sm {{ $status == 'hamil' ? 'bg-teal-600 text-white' : 'bg-white text-teal-600 border border-teal-200 hover:bg-teal-600 hover:text-white' }}">
                Ibu Hamil
            </a>
            <a href="{{ route('mothers.index', ['status' => 'menyusui']) }}" 
               class="px-4 py-2 text-sm font-bold rounded-lg transition-all duration-200 shadow-sm {{ $status == 'menyusui' ? 'bg-teal-500 text-white' : 'bg-white text-teal-500 border border-teal-200 hover:bg-teal-500 hover:text-white' }}">
                Ibu Menyusui
            </a>
            <a href="{{ route('mothers.index', ['status' => 'lainnya']) }}" 
               class="px-4 py-2 text-sm font-bold rounded-lg transition-all duration-200 shadow-sm {{ $status == 'lainnya' ? 'bg-emerald-600 text-white' : 'bg-white text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white' }}">
                Anak > 2 Tahun
            </a>
        </div>

        <div class="md:text-right">
            <p class="text-lg text-black font-semibold">Kelola data ibu {{ $status == 'hamil' ? 'hamil' : ($status == 'menyusui' ? 'menyusui' : 'dengan anak > 2 tahun') }} yang terdaftar di posyandu.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center shadow-sm" role="alert">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center shadow-sm" role="alert">
             <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative w-full max-w-lg">
                <form action="{{ route('mothers.index') }}" method="GET" class="flex gap-2">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            style="padding-left: 3rem !important;"
                            class="block w-full border border-gray-200 rounded-lg text-sm bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-colors py-2 pr-3" 
                            placeholder="Cari nama ibu...">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                        Cari
                    </button>
                </form>
            </div>
            <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                <button type="button" @click="exportModal = true" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 flex-shrink-0 w-full md:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </button>
                <a href="{{ route('mothers.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 flex-shrink-0 w-full md:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Ibu
                </a>
            </div>
        </div>

        <!-- Export Modal -->
        <div x-cloak x-show="exportModal" 
             class="fixed inset-0 z-[999] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="exportModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="exportModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                    <form action="{{ route('mothers.export') }}" method="GET" @submit="exportModal = false">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="bg-white px-6 pt-6 pb-4">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100">
                                    <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Export Rekap Ibu {{ $status == 'hamil' ? 'Hamil' : 'Menyusui' }}</h3>
                                    <p class="text-sm text-gray-500">Berdasarkan tanggal kunjungan pemeriksaan.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Dari Tanggal</label>
                                        <input type="date" name="start_date" class="block w-full border border-gray-200 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 p-2.5 transition-all outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Sampai Tanggal</label>
                                        <input type="date" name="end_date" class="block w-full border border-gray-200 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 p-2.5 transition-all outline-none">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Posyandu</label>
                                    <select name="health_post_id" class="block w-full border border-gray-200 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 p-2.5 transition-all outline-none">
                                        <option value="">Semua Posyandu</option>
                                        @foreach($healthPosts as $hp)
                                            <option value="{{ $hp->id }}">{{ $hp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat (Keywords)</label>
                                    <input type="text" name="address" placeholder="Contoh: Sukamaju" class="block w-full border border-gray-200 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 p-2.5 transition-all outline-none">
                                </div>
                                
                                <p class="text-[10px] text-gray-400 italic">* Kosongkan untuk ekspor seluruh data</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row justify-end gap-2">
                            <button type="button" @click="exportModal = false" class="inline-flex justify-center rounded-lg border border-gray-300 px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex justify-center rounded-lg border border-transparent px-4 py-2 bg-emerald-600 text-sm font-medium text-white hover:bg-emerald-700 transition-colors shadow-sm">
                                Download Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Nama Ibu</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Usia</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Telepon</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Gol. Darah</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($mothers as $mother)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $mother->name }}</td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($mother->birth_date)->age }} Tahun
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-mono text-xs whitespace-nowrap">{{ $mother->phone_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                    {{ $mother->blood_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
    <form action="{{ route('mothers.update-status', $mother) }}" method="POST">
        @csrf
        @method('PATCH')

        <select 
            name="status"
            onchange="this.form.submit()"
            class="text-sm font-semibold rounded-lg px-3 py-2 border border-gray-200 shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all duration-200

            {{ $mother->status == 'hamil'
                ? 'bg-pink-100 text-pink-700'

                : ($mother->status == 'menyusui'
                    ? 'bg-blue-100 text-blue-700'

                    : 'bg-green-100 text-green-700') }}"
        >

            <!-- Status Hamil -->
            <option 
                value="hamil"
                class="bg-white text-gray-900"
                {{ $mother->status == 'hamil' ? 'selected' : '' }}
            >
                Hamil
            </option>

            <!-- Status Menyusui -->
            <option 
                value="menyusui"
                class="bg-white text-gray-900"
                {{ $mother->status == 'menyusui' ? 'selected' : '' }}
            >
                Menyusui
            </option>

            <!-- Status Anak > 2 Tahun -->
            <option 
                value="lainnya"
                class="bg-white text-gray-900"
                {{ $mother->status == 'lainnya' ? 'selected' : '' }}
            >
                Anak > 2 Tahun
            </option>

        </select>
    </form>
</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('mothers.show', $mother) }}" class="inline-flex items-center justify-center w-8 h-8 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors shadow-sm" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('mothers.edit', $mother) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-sm" title="Ubah Data">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('mothers.destroy', $mother) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background-color: #dc2626 !important; color: white !important;" class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors shadow-sm hover:opacity-90" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-gray-900 font-medium text-sm mb-1">Belum ada data ibu {{ $status == 'hamil' ? 'hamil' : 'menyusui' }}</h3>
                                    <p class="text-xs text-gray-500 mb-4">Silakan tambahkan data ibu baru.</p>
                                    <a href="{{ route('mothers.create') }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium hover:underline">+ Tambah Ibu</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($mothers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $mothers->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
