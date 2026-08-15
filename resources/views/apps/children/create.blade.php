@extends('layouts.app')

@section('title', 'Tambah Anak')

@section('content')
    <div class="w-full mx-auto space-y-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Data Anak', 'url' => route('childrens.index')], ['label' => 'Tambah Baru']]" />

        <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Form Tambah Anak</h2>
                <p class="text-base text-gray-500">Isi informasi untuk mendaftarkan anak baru.</p>
            </div>

            <form action="{{ route('childrens.store') }}" method="POST" class="p-6 space-y-8" autocomplete="off">
                @csrf

                <!-- Child Identity Section -->
                <div class="space-y-4">
                    <h3
                        class="text-base font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                        Data Anak</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Identity Number Field (NIK) -->
                        <div class="w-full">
                            <label for="identity_number" class="block text-base font-medium text-gray-700 mb-1">NIK Anak
                                <span class="text-sm text-gray-500">(Opsional)</span></label>
                            <input type="text" name="identity_number" id="identity_number"
                                value="{{ old('identity_number') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('identity_number') border-red-500 bg-red-50 @enderror"
                                placeholder="Nomor Induk Kependudukan (16 digit)">
                            @error('identity_number')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name Field -->
                        <div class="w-full">
                            <label for="name" class="block text-base font-medium text-gray-700 mb-1">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('name') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: Budi Santoso">
                            @error('name')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mother Field -->
                        <div class="w-full">
                            <label for="mother_id" class="block text-base font-medium text-gray-700 mb-1">Nama Ibu <span
                                    class="text-red-500">*</span></label>
                            <select name="mother_id" id="mother_id" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('mother_id') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Ibu...</option>
                                @foreach ($mothers as $mother)
                                    <option value="{{ $mother->id }}"
                                        {{ old('mother_id') == $mother->id ? 'selected' : '' }}>{{ $mother->name }} -
                                        {{ $mother->identity_number }}</option>
                                @endforeach
                            </select>
                            @error('mother_id')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender Field -->
                        <div class="w-full">
                            <label for="gender" class="block text-base font-medium text-gray-700 mb-1">Jenis Kelamin <span
                                    class="text-red-500">*</span></label>
                            <select name="gender" id="gender" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('gender') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Jenis Kelamin...</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Place Field -->
                        <div class="w-full">
                            <label for="birth_place" class="block text-base font-medium text-gray-700 mb-1">Tempat Lahir
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}"
                                required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('birth_place') border-red-500 bg-red-50 @enderror"
                                placeholder="Kota Kelahiran">
                            @error('birth_place')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Date Field -->
                        <div class="w-full">
                            <label for="birth_date" class="block text-base font-medium text-gray-700 mb-1">Tanggal Lahir
                                <span class="text-red-500">*</span></label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                                required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('birth_date') border-red-500 bg-red-50 @enderror">
                            @error('birth_date')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Weight Field -->
                        <div class="w-full">
                            <label for="birth_weight" class="block text-base font-medium text-gray-700 mb-1">Berat Lahir
                                (kg) <span class="text-red-500">*</span></label>
                            <input type="text" name="birth_weight" id="birth_weight" value="{{ old('birth_weight') }}"
                                required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('birth_weight') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 3.5">
                            @error('birth_weight')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Height Field -->
                        <div class="w-full">
                            <label for="birth_height" class="block text-base font-medium text-gray-700 mb-1">Tinggi Lahir
                                (cm) <span class="text-red-500">*</span></label>
                            <input type="text" name="birth_height" id="birth_height" value="{{ old('birth_height') }}"
                                required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('birth_height') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 50">
                            @error('birth_height')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Timbangan Bayi --}}
                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <button type="button" id="connectScaleButton"
                                class="px-3 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-all">
                                Hubungkan Timbangan Bayi
                            </button>

                            <button type="button" id="disconnectScaleButton"
                                class="hidden px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all"
                                style="background:red !important; color:white !important;">
                                Putuskan Timbangan Bayi
                            </button>

                            <span id="scaleStatus" class="text-sm text-gray-500">
                                Timbangan bayi belum terhubung
                            </span>
                        </div>

                        {{-- Alat Ukur Tinggi Balita --}}
                        <div class="w-full">
                            <div class="flex flex-wrap items-center gap-2 mt-3">

                                {{-- Tombol Hubungkan --}}
                                <button type="button" id="connectiotScaleButton"
                                    class="px-3 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-all">
                                    Hubungkan Alat Ukur Tinggi
                                </button>

                                {{-- Tombol Ambil Data --}}
                                <button type="button" id="measureiotScaleButton"
                                    class="hidden px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all">
                                    Ambil Data
                                </button>

                                {{-- Tombol Putuskan --}}
                                <button type="button" id="disconnectiotScaleButton"
                                    class="hidden px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all">
                                    Putuskan Alat
                                </button>

                                {{-- Status --}}
                                <span id="iotscaleStatus" class="text-sm text-gray-500">
                                    Alat belum terhubung
                                </span>

                            </div>
                        </div>

                        <!-- Temperature Field -->
                        <div class="w-full">
                            <label for="temperature" class="block text-sm font-medium text-gray-700 mb-1">
                                Suhu Tubuh (°C) <span class="text-xs text-gray-500">(Opsional)</span>
                            </label>
                            <input type="number" step="0.1" name="temperature" id="temperature"
                                value="{{ old('temperature') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('temperature') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 36.5">
                            <span id="suhuStatusBadge" class="hidden"></span>

                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                <button type="button" id="connectThermometerButton"
                                    class="px-3 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-all">
                                    Hubungkan Termometer
                                </button>
                                <button type="button" id="disconnectThermometerButton"
                                    class="hidden px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all">
                                    Putuskan Termometer
                                </button>
                                <span id="thermometerStatus" class="text-sm text-gray-500">
                                    Termometer belum terhubung
                                </span>
                            </div>

                            @error('temperature')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- BPJS Facility Field -->
                        <div class="w-full">
                            <label for="bpjs_facility" class="block text-base font-medium text-gray-700 mb-1">Faskes
                                (BPJS)
                                <span class="text-sm text-gray-500">(Opsional)</span></label>
                            <select name="bpjs_facility" id="bpjs_facility"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('bpjs_facility') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Faskes...</option>
                                <option value="Klinik" {{ old('bpjs_facility') == 'Klinik' ? 'selected' : '' }}>Klinik
                                </option>
                                <option value="Puskesmas" {{ old('bpjs_facility') == 'Puskesmas' ? 'selected' : '' }}>
                                    Puskesmas</option>
                                <option value="RS" {{ old('bpjs_facility') == 'RS' ? 'selected' : '' }}>RS</option>
                            </select>
                            @error('bpjs_facility')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('childrens.index') }}"
                        class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-base font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-base font-medium rounded-lg shadow-sm transition-colors">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
<script src="{{ asset('js/scale.js') }}?v={{ filemtime(public_path('js/scale.js')) }}" defer></script>
<script src="{{ asset('js/iotScale.js') }}?v={{ filemtime(public_path('js/iotScale.js')) }}" defer></script>
<script src="{{ asset('js/thermometer.js') }}?v={{ filemtime(public_path('js/thermometer.js')) }}" defer></script>

<script src="{{ asset('js/vital-status.js') }}?v={{ filemtime(public_path('js/vital-status.js')) }}" defer></script>
