@extends('layouts.app')

@section('title', 'Tambah Data Ibu')

@section('content')
    <div class="w-full mx-auto space-y-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Data Ibu', 'url' => route('mothers.index')], ['label' => 'Tambah Baru']]" />

        @if (session('success'))
            <div class="p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center shadow-sm"
                role="alert">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium text-base">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center shadow-sm"
                role="alert">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium text-base">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Form Tambah Data Ibu</h2>
                <p class="text-base text-gray-500">Isi informasi untuk mendaftarkan ibu hamil atau menyusui baru.</p>
            </div>

            <form action="{{ route('mothers.store') }}" method="POST" class="p-6 space-y-8" autocomplete="off">
                @csrf

                <!-- Personal Identity Section -->
                <div class="space-y-4">
                    <h3
                        class="text-base font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                        Identitas Diri</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Field -->
                        <div class="w-full">
                            <label for="name" class="block text-base font-medium text-gray-700 mb-1">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('name') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: Siti Aminah">
                            @error('name')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Husband Name Field -->
                        <div class="w-full">
                            <label for="husband_name" class="block text-base font-medium text-gray-700 mb-1">Nama
                                Suami</label>
                            <input type="text" name="husband_name" id="husband_name" value="{{ old('husband_name') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('husband_name') border-red-500 bg-red-50 @enderror"
                                placeholder="Nama Suami">
                            @error('husband_name')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Identity Number (NIK) Field -->
                        <div class="w-full">
                            <label for="identity_number" class="block text-base font-medium text-gray-700 mb-1">NIK <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="identity_number" id="identity_number"
                                value="{{ old('identity_number') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('identity_number') border-red-500 bg-red-50 @enderror"
                                placeholder="16 digit NIK">
                            @error('identity_number')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number Field -->
                        <div class="w-full">
                            <label for="phone_number" class="block text-base font-medium text-gray-700 mb-1">Nomor Telepon
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                                required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('phone_number') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 08123456789">
                            @error('phone_number')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Social Security Number (BPJS) Field -->
                        <div class="w-full">
                            <label for="social_security_number" class="block text-base font-medium text-gray-700 mb-1">No.
                                BPJS/KIS <span class="text-sm text-gray-500">(Opsional)</span></label>
                            <input type="text" name="social_security_number" id="social_security_number"
                                value="{{ old('social_security_number') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('social_security_number') border-red-500 bg-red-50 @enderror"
                                placeholder="Nomor BPJS Kesehatan">
                            @error('social_security_number')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Health Facility (Faskes) Field -->
                        <div class="w-full">
                            <label for="health_facility" class="block text-base font-medium text-gray-700 mb-1">Faskes
                                (BPJS) <span class="text-sm text-gray-500">(Opsional)</span></label>
                            <select name="health_facility" id="health_facility"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('health_facility') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Tipe Faskes</option>
                                <option value="Klinik" {{ old('health_facility') == 'Klinik' ? 'selected' : '' }}>Klinik
                                </option>
                                <option value="Puskesmas" {{ old('health_facility') == 'Puskesmas' ? 'selected' : '' }}>
                                    Puskesmas</option>
                                <option value="RS" {{ old('health_facility') == 'RS' ? 'selected' : '' }}>RS</option>
                            </select>
                            @error('health_facility')
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

                        <!-- Blood Type Field -->
                        <div class="w-full">
                            <label for="blood_type" class="block text-base font-medium text-gray-700 mb-1">Golongan Darah
                                <span class="text-red-500">*</span></label>
                            <select name="blood_type" id="blood_type" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('blood_type') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Golongan Darah</option>
                                @foreach (['A', 'B', 'AB', 'O'] as $blood)
                                    <option value="{{ $blood }}"
                                        {{ old('blood_type') == $blood ? 'selected' : '' }}>{{ $blood }}</option>
                                @endforeach
                            </select>
                            @error('blood_type')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Height Field -->
                        <div class="w-full">
                            <label for="height" class="block text-base font-medium text-gray-700 mb-1">Tinggi Badan (cm)
                                <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" name="height" id="height"
                                value="{{ old('height') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('height') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 160">
                            @error('height')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Weight Field (Initial) -->
                        <div class="w-full">
                            <label for="weight" class="block text-base font-medium text-gray-700 mb-1">Berat Badan Awal
                                (kg) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" name="weight" id="weight"
                                value="{{ old('weight') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('weight') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 55">
                            @error('weight')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
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

                        <!-- Blood Pressure Field -->
                        <div class="w-full md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tekanan Darah &amp; Nadi <span class="text-xs text-gray-500">(Opsional)</span>
                            </label>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <input type="number" name="systolic_pressure" id="systolic_pressure"
                                        value="{{ old('systolic_pressure') }}"
                                        class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('systolic_pressure') border-red-500 bg-red-50 @enderror"
                                        placeholder="Sistol (mmHg)">
                                    @error('systolic_pressure')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <input type="number" name="diastolic_pressure" id="diastolic_pressure"
                                        value="{{ old('diastolic_pressure') }}"
                                        class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('diastolic_pressure') border-red-500 bg-red-50 @enderror"
                                        placeholder="Diastol (mmHg)">
                                    @error('diastolic_pressure')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <input type="number" name="pulse" id="pulse" value="{{ old('pulse') }}"
                                        class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('pulse') border-red-500 bg-red-50 @enderror"
                                        placeholder="Nadi (bpm)">
                                    @error('pulse')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <span id="tensiStatusBadge" class="hidden"></span>

                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                <button type="button" id="ambilTensiButton"
                                    class="px-3 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-all">
                                    Ambil dari Tensimeter
                                </button>
                                <span id="tensiStatus" class="text-sm text-gray-500">
                                    Belum ada data diambil
                                </span>
                            </div>
                            <p class="mt-2 text-xs text-gray-400">
                                Ukur tensi di alat, jalankan <code>tensimeter_bridge.py</code> di komputer, baru klik tombol
                                di atas.
                            </p>
                        </div>

                        <!-- Status Field -->
                        <div class="w-full">
                            <label for="status" class="block text-base font-medium text-gray-700 mb-1">Status <span
                                    class="text-red-500">*</span></label>
                            <select name="status" id="status" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('status') border-red-500 bg-red-50 @enderror">
                                <option value="hamil" {{ old('status') == 'hamil' ? 'selected' : '' }}>Hamil</option>
                                <option value="menyusui" {{ old('status') == 'menyusui' ? 'selected' : '' }}>Menyusui
                                </option>
                                <option value="lainnya" {{ old('status') == 'lainnya' ? 'selected' : '' }}>Anak > 2 Tahun
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="w-full md:col-span-2">
                            <label for="address" class="block text-base font-medium text-gray-700 mb-1">Alamat Lengkap
                                <span class="text-red-500">*</span></label>
                            <textarea name="address" id="address" rows="3" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-base p-2.5 transition-all @error('address') border-red-500 bg-red-50 @enderror"
                                placeholder="Alamat domisili lengkap...">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('mothers.index') }}"
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
<script src="{{ asset('js/thermometer.js') }}?v={{ filemtime(public_path('js/thermometer.js')) }}" defer></script>
<script src="{{ asset('js/tensimeter.js') }}?v={{ filemtime(public_path('js/tensimeter.js')) }}" defer></script>
<script src="{{ asset('js/vital-status.js') }}?v={{ filemtime(public_path('js/vital-status.js')) }}" defer></script>
