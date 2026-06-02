@extends('layouts.app')

@section('title', 'Tambah Pemantauan')

@section('content')
    <div class="w-full mx-auto space-y-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Pemantauan Pertumbuhan', 'url' => route('growth-monitorings.index')],
            ['label' => 'Tambah Data'],
        ]" />

        <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Form Pemantauan Pertumbuhan</h2>
                <p class="text-sm text-gray-500">Catat hasil pengukuran berat dan tinggi badan anak.</p>
            </div>

            <form action="{{ route('growth-monitorings.store') }}" method="POST" class="p-6 space-y-8" autocomplete="off">
                @csrf

                <!-- Monitoring Info Section -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                        Detail Pengukuran</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Child Field -->
                        <div class="w-full">
                            <label for="child_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Anak <span
                                    class="text-red-500">*</span></label>

                            @if (request()->has('child_id'))
                                @php
                                    $selectedChild = $childrens->firstWhere('id', request('child_id'));
                                @endphp
                                @if ($selectedChild)
                                    <div
                                        class="block w-full rounded-lg border border-gray-200 bg-gray-100 text-gray-900 shadow-sm sm:text-sm p-2.5">
                                        {{ $selectedChild->name }} - (Ibu: {{ $selectedChild->mother->name }})
                                    </div>
                                    <input type="hidden" name="child_id" value="{{ $selectedChild->id }}">
                                @else
                                    <!-- Fallback if ID invalid -->
                                    <select name="child_id" id="child_id" required
                                        class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('child_id') border-red-500 bg-red-50 @enderror">
                                        <option value="">Pilih Anak...</option>
                                        @foreach ($childrens as $child)
                                            <option value="{{ $child->id }}"
                                                {{ old('child_id', request('child_id')) == $child->id ? 'selected' : '' }}>
                                                {{ $child->name }} - (Ibu:
                                                {{ $child->mother->name }})</option>
                                        @endforeach
                                    </select>
                                @endif
                            @else
                                <select name="child_id" id="child_id" required
                                    class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('child_id') border-red-500 bg-red-50 @enderror">
                                    <option value="">Pilih Anak...</option>
                                    @foreach ($childrens as $child)
                                        <option value="{{ $child->id }}"
                                            {{ old('child_id') == $child->id ? 'selected' : '' }}>{{ $child->name }} -
                                            (Ibu:
                                            {{ $child->mother->name }})</option>
                                    @endforeach
                                </select>
                            @endif

                            @error('child_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if (!request()->has('child_id'))
                                <div class="mt-2 text-xs text-gray-500">
                                    Data anak tidak ditemukan? <a href="{{ route('childrens.create') }}" target="_blank"
                                        class="text-teal-600 hover:text-teal-700 font-medium hover:underline">Tambah Data
                                        Anak
                                        Baru</a>
                                </div>
                            @endif
                        </div>

                        <!-- Staff Field -->
                        <div class="w-full">
                            <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-1">Petugas Pemeriksa
                                <span class="text-red-500">*</span></label>
                            <select name="staff_id" id="staff_id" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('staff_id') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Petugas...</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}"
                                        {{ old('staff_id') == $staff->id ? 'selected' : '' }}>{{ $staff->user->name }} -
                                        {{ $staff->role }}</option>
                                @endforeach
                            </select>
                            @error('staff_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Checkup Date -->
                        <div class="w-full">
                            <label for="checkup_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Pemeriksaan <span class="text-red-500">*</span></label>
                            <input type="date" name="checkup_date" id="checkup_date"
                                value="{{ old('checkup_date', date('Y-m-d')) }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('checkup_date') border-red-500 bg-red-50 @enderror">
                            @error('checkup_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Weight -->
                        <div class="w-full">
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan (kg)
                                <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" name="weight" id="weight" value="{{ old('weight') }}"
                                required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('weight') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 12.5">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Height -->
                        <div class="w-full">
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan (cm)
                                <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" name="height" id="height" value="{{ old('height') }}"
                                required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('height') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 85.0">
                            @error('height')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Arm Circumference -->
                        <div class="w-full">
                            <label for="arm_circumference" class="block text-sm font-medium text-gray-700 mb-1">Lingkar
                                Lengan Atas / LILA (cm)</label>
                            <input type="number" step="0.1" name="arm_circumference" id="arm_circumference"
                                value="{{ old('arm_circumference') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('arm_circumference') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 14.5">
                            @error('arm_circumference')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Head Circumference -->
                        <div class="w-full">
                            <label for="head_circumference" class="block text-sm font-medium text-gray-700 mb-1">Lingkar
                                Kepala (cm)</label>
                            <input type="number" step="0.1" name="head_circumference" id="head_circumference"
                                value="{{ old('head_circumference') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('head_circumference') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 35.0">
                            @error('head_circumference')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Next Checkup Date -->
                        <div class="w-full">
                            <label for="next_checkup_date" class="block text-sm font-medium text-gray-700 mb-1">Jadwal Pemeriksaan Berikutnya</label>
                            <input type="date" name="next_checkup_date" id="next_checkup_date"
                                value="{{ old('next_checkup_date') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('next_checkup_date') border-red-500 bg-red-50 @enderror">
                            @error('next_checkup_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Gizi & Z-Score Info -->
                        <div class="w-full md:col-span-2 bg-blue-50 border border-blue-100 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Informasi Perhitungan Otomatis</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Nilai <strong>Z-Score</strong> dan <strong>Status Gizi</strong> akan dihitung
                                            secara otomatis oleh sistem berdasarkan Berat Badan, Umur, dan Jenis Kelamin
                                            anak sesuai standar WHO.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Note -->
                        <div class="w-full md:col-span-2">
                            <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Catatan
                                Tambahan</label>
                            <textarea name="note" id="note" rows="3"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('note') border-red-500 bg-red-50 @enderror"
                                placeholder="Catatan kesehatan anak...">{{ old('note') }}</textarea>
                            @error('note')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('growth-monitorings.index') }}"
                        class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
