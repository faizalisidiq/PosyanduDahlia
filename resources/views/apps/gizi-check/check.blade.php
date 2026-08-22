@extends('layouts.auth')

@section('title', 'Pilih Anak')

@section('content')
    <div class="mb-8 text-center md:text-left">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Halo, {{ $mother->name }}</h2>
        <p class="text-gray-500">Pilih anak untuk melihat perkembangan gizi &amp; pertumbuhannya.</p>
    </div>

    @if ($children->isEmpty())
        <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-6 text-center">
            <p class="text-yellow-700 font-medium mb-2">Tidak ada data anak ditemukan.</p>
            <p class="text-base text-yellow-600">Pastikan data anak sudah didaftarkan oleh kader.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($children as $child)
                @php $latest = $child->growthMonitorings->first(); @endphp
                <a href="{{ route('gizi.child.show', $child) }}"
                    class="block border border-gray-200 rounded-xl p-4 hover:border-teal-500 hover:shadow-md transition-all bg-gray-50 hover:bg-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">{{ $child->name }}</h3>
                            <p class="text-base text-gray-500">
                                {{ $child->gender == 'male' ? 'Laki-laki' : 'Perempuan' }} &middot;
                                {{ \Carbon\Carbon::parse($child->birth_date)->format('d M Y') }}
                            </p>
                        </div>
                        @if ($latest)
                            <span class="text-sm font-semibold px-3 py-1 rounded-full
                                {{ str_contains($latest->status, 'Baik') ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $latest->status }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400">Belum ada data</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    <div class="mt-6 text-center border-t border-gray-100 pt-4">
        <a href="{{ route('gizi.index') }}" class="text-base text-gray-500 hover:text-teal-600 transition-colors">Gunakan NIK Lain</a>
    </div>
@endsection