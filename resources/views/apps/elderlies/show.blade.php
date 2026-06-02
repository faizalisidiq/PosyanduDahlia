@extends('layouts.app')

@section('title', 'Detail Lansia')

@section('content')
    <div class="w-full mx-auto space-y-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Data Lansia', 'url' => route('elderlies.index')],
            ['label' => 'Detail Lansia']
        ]" />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar - Profile Summary -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                    <div class="p-6 text-center">
                        <div
                            class="w-24 h-24 mx-auto bg-teal-50 rounded-full flex items-center justify-center mb-4 border-2 border-teal-100">
                            <span class="text-3xl font-bold text-teal-600">{{ substr($elderly->name, 0, 1) }}</span>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">{{ $elderly->name }}</h2>
                        <p class="text-sm text-gray-500 mb-4">{{ $elderly->identity_number }}</p>

                        <div class="flex items-center justify-center gap-2 mb-6">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $elderly->gender === 'male' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-pink-50 text-pink-700 border border-pink-100' }}">
                                {{ $elderly->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                {{ \Carbon\Carbon::parse($elderly->birth_date)->age }} Tahun
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Gol. Darah</p>
                                <p class="font-semibold text-gray-900">{{ $elderly->blood_type ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">BPJS/KIS</p>
                                <p class="font-semibold text-gray-900 text-xs">{{ $elderly->social_security_number ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Address -->
                <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800">Kontak & Alamat</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Nomor Telepon</p>
                            <div class="flex items-center text-sm font-medium text-gray-900">
                                <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $elderly->phone_number ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Alamat</p>
                            <div class="flex items-start text-sm font-medium text-gray-900">
                                <svg class="h-4 w-4 text-gray-400 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $elderly->address ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tempat, Tanggal Lahir</p>
                            <div class="flex items-center text-sm font-medium text-gray-900">
                                <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $elderly->birth_place ?? '' }}, {{ $elderly->birth_date->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content - Screenings & Stats -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Latest Screening Info -->
                <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Riwayat Screening ILP</h3>
                        <a href="{{ route('ilp-screenings.index', ['subject_id' => $elderly->id, 'subject_type' => 'App\Models\Elderly']) }}"
                            class="text-sm text-teal-600 hover:text-teal-700 font-medium hover:underline">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold">Tanggal</th>
                                        <th class="px-6 py-3 font-semibold">Petugas</th>
                                        <th class="px-6 py-3 font-semibold">Tekanan Darah</th>
                                        <th class="px-6 py-3 font-semibold">Gula Darah</th>
                                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                    @forelse($elderly->screenings()->latest()->limit(5)->get() as $screening)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                                {{ $screening->checkup_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                                {{ $screening->staff->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                                {{ $screening->results['blood_pressure'] ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                                {{ $screening->results['blood_sugar'] ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <a href="{{ route('ilp-screenings.show', $screening) }}"
                                                    class="text-teal-600 hover:text-teal-700 font-medium text-xs">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                Belum ada riwayat screening.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('ilp-screenings.create', ['subject_id' => $elderly->id, 'subject_type' => 'App\Models\Elderly']) }}"
                        class="flex items-center p-4 bg-teal-50 border border-teal-100 rounded-xl hover:bg-teal-100 transition-colors group cursor-pointer">
                        <div
                            class="w-10 h-10 rounded-full bg-teal-200 text-teal-700 flex items-center justify-center mr-4 group-hover:bg-teal-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-teal-800">Screening Baru (ILP)</h4>
                            <p class="text-xs text-teal-600 mt-1">Catat hasil pemeriksaan kesehatan rutin.</p>
                        </div>
                    </a>
                    <a href="{{ route('elderlies.edit', $elderly) }}"
                        class="flex items-center p-4 bg-blue-50 border border-blue-100 rounded-xl hover:bg-blue-100 transition-colors group cursor-pointer">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-200 text-blue-700 flex items-center justify-center mr-4 group-hover:bg-blue-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-800">Ubah Data Diri</h4>
                            <p class="text-xs text-blue-600 mt-1">Perbarui informasi detail lansia.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection