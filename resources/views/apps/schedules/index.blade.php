@extends('layouts.app')

@section('title', 'Jadwal Pemeriksaan')

@section('content')
<div class="w-full space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-end gap-4">
        <div class="md:text-right">
           <p class="text-lg text-black font-semibold">Kirim pengingat H-1 kepada orang tua melalui WhatsApp.</p>
        </div>
    </div>
    
    <div class="flex justify-end">
        <form action="{{ route('schedules.index') }}" method="GET" class="flex items-center gap-2">
            <input type="date" name="date" value="{{ $date }}" 
                class="block rounded-lg border-gray-200 bg-white text-gray-900 focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2 transition-all">
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-colors">
                Filter
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Nama Anak</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Nama Ibu</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">No. WhatsApp</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Jadwal</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $schedule->child->name }}</td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $schedule->child->mother->name }}</td>
                            <td class="px-6 py-4 text-gray-600 font-mono text-xs whitespace-nowrap">{{ $schedule->child->mother->phone_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $schedule->next_checkup_date->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                @if($schedule->child->mother->phone_number)
                                    @php
                                        $phone = preg_replace('/[^0-9]/', '', $schedule->child->mother->phone_number);
                                        if (str_starts_with($phone, '0')) {
                                            $phone = '62' . substr($phone, 1);
                                        }

                                        $message = "Halo Ibu " . $schedule->child->mother->name . ", mengingatkan kembali jadwal pemeriksaan/imunisasi untuk " . $schedule->child->name . " besok pada tanggal " . $schedule->next_checkup_date->format('d/m/Y') . " di Posyandu. Jangan lupa hadir ya Bu!";
                                        $waUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);
                                    @endphp
                                    <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                                        <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        Ingatkan H-1
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">No WhatsApp tidak ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-gray-900 font-medium text-sm mb-1">Tidak ada jadwal pemeriksaan</h3>
                                    <p class="text-xs text-gray-500">Untuk tanggal {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
