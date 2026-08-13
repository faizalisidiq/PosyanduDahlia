@extends('layouts.app')

@section('title', 'Pemantauan Pertumbuhan')

@section('content')
<div class="w-full space-y-6" x-data="bulkMessage()">
    <div class="flex flex-col md:flex-row md:items-center justify-end gap-4">
        <div class="md:text-right">
            <p class="text-lg text-black font-semibold">Kelola data pemantauan pertumbuhan anak (timbang & ukur).</p>
        </div>
        
        <div x-show="selectedIds.length > 0" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100">
            <button @click="sendBulkMessages()" 
                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                Kirim WA Massal (<span x-text="selectedIds.length"></span>)
            </button>
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
                <form action="{{ route('growth-monitorings.index') }}" method="GET" class="flex gap-2">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            style="padding-left: 3rem !important;"
                            class="block w-full border border-gray-200 rounded-lg text-sm bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-colors py-2 pr-3" 
                            placeholder="Cari nama anak...">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                        Cari
                    </button>
                </form>
            </div>
            <a href="{{ route('growth-monitorings.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 flex-shrink-0 w-full md:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Data
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-4 py-4 w-10">
                            <input type="checkbox" @change="toggleAll($event)" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Tanggal</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Nama Anak</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">BB / TB</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">LILA / Linkep</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Status Gizi</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Petugas</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($growthMonitorings as $monitoring)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-4 py-4">
                                @if($monitoring->child->mother->phone_number)
                                    <input type="checkbox" :value="{{ $monitoring->id }}" x-model="selectedIds" 
                                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium whitespace-nowrap">
                                {{ $monitoring->checkup_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <a href="{{ route('childrens.show', $monitoring->child) }}" class="text-teal-600 hover:underline">
                                    {{ $monitoring->child->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $monitoring->weight }} kg / {{ $monitoring->height }} cm
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $monitoring->arm_circumference ?? '-' }} / {{ $monitoring->head_circumference ?? '-' }} cm
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $monitoring->status == 'Gizi Baik' ? 'bg-green-50 text-green-700 border border-green-100' : 
                                       ($monitoring->status == 'Gizi Kurang' ? 'bg-yellow-50 text-yellow-700 border border-yellow-100' : 
                                       'bg-red-50 text-red-700 border border-red-100') }}">
                                    {{ $monitoring->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-2">
                                        {{ substr($monitoring->staff->user->name, 0, 1) }}
                                    </div>
                                    {{ $monitoring->staff->user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-2">
                                    @if(Auth::user()->staff && Auth::user()->staff->role === 'ketua-kader' && $monitoring->child->mother->phone_number)
                                        @php
                                            $phone = preg_replace('/[^0-9]/', '', $monitoring->child->mother->phone_number);
                                            if (str_starts_with($phone, '0')) {
                                                $phone = '62' . substr($phone, 1);
                                            }
                                            
                                            $ageInMonths = round($monitoring->child->birth_date->diffInMonths($monitoring->checkup_date, false));
                                            
                                            $nextScheduleInfo = "";
                                            if ($monitoring->next_checkup_date) {
                                                $nextScheduleInfo = "\n\nJadwal pemeriksaan berikutnya: " . $monitoring->next_checkup_date->format('d/m/Y');
                                            }

                                            $message = "Halo Ibu " . $monitoring->child->mother->name . ", ini dari Posyandu. Memberitahukan hasil pemeriksaan " . $monitoring->child->name . " hari ini:\n" .
                                                       "- Umur: " . $ageInMonths . " Bulan\n" .
                                                       "- Berat Badan (BB): " . $monitoring->weight . " kg\n" .
                                                       "- Tinggi Badan (TB): " . $monitoring->height . " cm\n" .
                                                       "- LILA: " . ($monitoring->arm_circumference ?? '-') . " cm\n" .
                                                       "- Lingkar Kepala: " . ($monitoring->head_circumference ?? '-') . " cm\n\n" .
                                                       "Status Gizi: " . $monitoring->status . ".\n" .
                                                       "Jangan lupa jadwal imunisasi berikutnya ya Bu." . $nextScheduleInfo;
                                            
                                            $waUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);
                                        @endphp
                                        <a href="{{ $waUrl }}" target="_blank" 
                                           data-id="{{ $monitoring->id }}"
                                           data-wa-url="{{ $waUrl }}"
                                           class="wa-link inline-flex items-center justify-center w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors shadow-sm" title="Kirim WhatsApp">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('growth-monitorings.show', $monitoring) }}" class="inline-flex items-center justify-center w-8 h-8 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors shadow-sm" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('growth-monitorings.edit', $monitoring) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-sm" title="Ubah Data">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('growth-monitorings.destroy', $monitoring) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline-block">
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
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-gray-900 font-medium text-sm mb-1">Belum ada data pemantauan</h3>
                                    <p class="text-xs text-gray-500 mb-4">Silakan tambahkan data pengukuran baru.</p>
                                    <a href="{{ route('growth-monitorings.create') }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium hover:underline">+ Tambah Data</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($growthMonitorings->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $growthMonitorings->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function bulkMessage() {
        return {
            selectedIds: [],
            toggleAll(e) {
                if (e.target.checked) {
                    this.selectedIds = Array.from(document.querySelectorAll('input[type="checkbox"][value]'))
                        .map(el => parseInt(el.value));
                } else {
                    this.selectedIds = [];
                }
            },
            async sendBulkMessages() {
                if (!confirm(`Kirim pesan ke ${this.selectedIds.length} orang? Jendela WhatsApp akan terbuka secara berurutan.`)) {
                    return;
                }

                for (let id of this.selectedIds) {
                    const link = document.querySelector(`.wa-link[data-id="${id}"]`);
                    if (link) {
                        const url = link.getAttribute('data-wa-url');
                        window.open(url, '_blank');
                        // Kasih jeda sedikit biar browser ga anggap spam/popup block berlebihan
                        await new Promise(resolve => setTimeout(resolve, 2000));
                    }
                }
                
                alert('Selesai! Pastikan semua jendela WhatsApp telah terbuka.');
                this.selectedIds = [];
            }
        }
    }
</script>
@endsection
