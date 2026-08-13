@extends('layouts.app')

@section('title', 'Menu Arsip Data')

@section('content')
<div class="w-full mx-auto space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Arsip']]" />

    <!-- Page Header -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Arsip Data Terhapus</h1>
            <p class="text-base text-gray-500 mt-1">Kelola data yang telah diarsipkan (Soft Deleted). Anda dapat memulihkan (Restore) atau menghapusnya secara permanen.</p>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Filter Type Dropdown -->
            <form action="{{ route('archives.index') }}" method="GET" class="flex flex-wrap items-center gap-4 w-full md:w-auto">
                <div class="min-w-[200px]">
                    <label for="type" class="block text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Tipe Data</label>
                    <select name="type" id="type" onchange="this.form.submit()"
                        class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 text-base p-2.5 transition-all">
                        <option value="mother" {{ $type === 'mother' ? 'selected' : '' }}>Data Ibu</option>
                        <option value="children" {{ $type === 'children' ? 'selected' : '' }}>Data Anak</option>
                        <option value="elderly" {{ $type === 'elderly' ? 'selected' : '' }}>Data Lansia</option>
                        <option value="pregnancy_record" {{ $type === 'pregnancy_record' ? 'selected' : '' }}>Pemeriksaan Kehamilan</option>
                        <option value="childbirth_record" {{ $type === 'childbirth_record' ? 'selected' : '' }}>Persalinan</option>
                        <option value="growth_monitoring" {{ $type === 'growth_monitoring' ? 'selected' : '' }}>Pertumbuhan Anak</option>
                        <option value="ilp_screening" {{ $type === 'ilp_screening' ? 'selected' : '' }}>Screening ILP</option>
                    </select>
                </div>
                
                <div class="min-w-[240px]">
                    <label for="search" class="block text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Pencarian</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari kata kunci..."
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 text-base p-2.5">
                    </div>
                </div>
                
                <div class="mt-5 flex items-center gap-2">
                    <button type="submit" class="px-4 py-2.5 bg-teal-600 text-white rounded-lg text-base font-semibold hover:bg-teal-700 transition-colors shadow-sm">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('archives.index', ['type' => $type]) }}" class="px-3 py-2 text-gray-500 text-base hover:underline">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Listing Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider border-b border-gray-100">
                        @if($type === 'mother')
                            <th class="px-6 py-4 font-semibold">Nama Ibu</th>
                            <th class="px-6 py-4 font-semibold">NIK</th>
                            <th class="px-6 py-4 font-semibold">Nomor Telpon</th>
                            <th class="px-6 py-4 font-semibold">Jumlah Anak</th>
                            <th class="px-6 py-4 font-semibold">Tgl Dihapus</th>
                        @elseif($type === 'children')
                            <th class="px-6 py-4 font-semibold">Nama Anak</th>
                            <th class="px-6 py-4 font-semibold">NIK</th>
                            <th class="px-6 py-4 font-semibold">Nama Ibu</th>
                            <th class="px-6 py-4 font-semibold">Tgl Dihapus</th>
                        @elseif($type === 'elderly')
                            <th class="px-6 py-4 font-semibold">Nama Lansia</th>
                            <th class="px-6 py-4 font-semibold">NIK</th>
                            <th class="px-6 py-4 font-semibold">Gender</th>
                            <th class="px-6 py-4 font-semibold">Tgl Dihapus</th>
                        @elseif($type === 'pregnancy_record')
                            <th class="px-6 py-4 font-semibold">Nama Ibu</th>
                            <th class="px-6 py-4 font-semibold">Tgl Periksa</th>
                            <th class="px-6 py-4 font-semibold">Usia Kandungan</th>
                            <th class="px-6 py-4 font-semibold">Petugas</th>
                            <th class="px-6 py-4 font-semibold">Tgl Dihapus</th>
                        @elseif($type === 'childbirth_record')
                            <th class="px-6 py-4 font-semibold">Nama Ibu</th>
                            <th class="px-6 py-4 font-semibold">Tgl Persalinan</th>
                            <th class="px-6 py-4 font-semibold">Metode</th>
                            <th class="px-6 py-4 font-semibold">Petugas</th>
                            <th class="px-6 py-4 font-semibold">Tgl Dihapus</th>
                        @elseif($type === 'growth_monitoring')
                            <th class="px-6 py-4 font-semibold">Nama Anak</th>
                            <th class="px-6 py-4 font-semibold">Tgl Penimbangan</th>
                            <th class="px-6 py-4 font-semibold">BB / TB</th>
                            <th class="px-6 py-4 font-semibold">Status Gizi</th>
                            <th class="px-6 py-4 font-semibold">Tgl Dihapus</th>
                        @elseif($type === 'ilp_screening')
                            <th class="px-6 py-4 font-semibold">Nama Pasien</th>
                            <th class="px-6 py-4 font-semibold">Tipe Pasien</th>
                            <th class="px-6 py-4 font-semibold">Tgl Screening</th>
                            <th class="px-6 py-4 font-semibold">Petugas</th>
                            <th class="px-6 py-4 font-semibold">Tgl Dihapus</th>
                        @endif
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-base text-gray-700">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            @if($type === 'mother')
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->name }}</td>
                                <td class="px-6 py-4 font-mono">{{ $item->identity_number }}</td>
                                <td class="px-6 py-4">{{ $item->phone_number ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $item->children_count }}</td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                            @elseif($type === 'children')
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->name }}</td>
                                <td class="px-6 py-4 font-mono">{{ $item->identity_number ?? '-' }}</td>
                                <td class="px-6 py-4 text-teal-600 font-semibold">{{ $item->mother->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                            @elseif($type === 'elderly')
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->name }}</td>
                                <td class="px-6 py-4 font-mono">{{ $item->identity_number }}</td>
                                <td class="px-6 py-4">{{ $item->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                            @elseif($type === 'pregnancy_record')
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->mother->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-mono">{{ $item->visit_date->format('d M Y') }}</td>
                                <td class="px-6 py-4">{{ $item->gestational_age }}</td>
                                <td class="px-6 py-4">{{ $item->staff->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                            @elseif($type === 'childbirth_record')
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->mother->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-mono">{{ $item->delivery_date->format('d M Y') }}</td>
                                <td class="px-6 py-4">{{ $item->delivery_method }}</td>
                                <td class="px-6 py-4">{{ $item->staff->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                            @elseif($type === 'growth_monitoring')
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->child->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-mono">{{ $item->checkup_date->format('d M Y') }}</td>
                                <td class="px-6 py-4">{{ $item->weight }} kg / {{ $item->height }} cm</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-sm font-semibold bg-gray-100 text-gray-800">
                                        {{ $item->status ?? 'Normal' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                            @elseif($type === 'ilp_screening')
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->subjectable->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold">
                                    {{ $item->subjectable_type === 'App\Models\Mother' ? 'Ibu' : ($item->subjectable_type === 'App\Models\Children' ? 'Anak' : 'Lansia') }}
                                </td>
                                <td class="px-6 py-4 font-mono">{{ $item->checkup_date->format('d M Y') }}</td>
                                <td class="px-6 py-4">{{ $item->staff->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                            @endif
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <form action="{{ route('archives.restore', ['type' => $type, 'id' => $item->id]) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg text-sm font-semibold transition-colors">
                                        Pulihkan
                                    </button>
                                </form>
                                <form action="{{ route('archives.force-delete', ['type' => $type, 'id' => $item->id]) }}" method="POST" class="delete-permanent-form inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg text-sm font-semibold transition-colors">
                                        Hapus Permanen
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada data terhapus dalam arsip ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
