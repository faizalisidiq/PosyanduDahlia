@extends('layouts.app')

@section('title', 'Tambah Lansia')

@section('content')
    <div class="w-full mx-auto space-y-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Data Lansia', 'url' => route('elderlies.index')],
            ['label' => 'Tambah Baru']
        ]" />

        <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Form Tambah Lansia</h2>
                <p class="text-sm text-gray-500">Isi informasi untuk mendaftarkan lansia baru.</p>
            </div>

            <form action="{{ route('elderlies.store') }}" method="POST" class="p-6 space-y-8" autocomplete="off">
                @csrf

                <!-- Personal Identity Section -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                        Identitas Diri</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Field -->
                        <div class="w-full">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('name') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: Budi Santoso">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Identity Number (NIK) Field -->
                        <div class="w-full">
                            <label for="identity_number" class="block text-sm font-medium text-gray-700 mb-1">NIK <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="identity_number" id="identity_number"
                                value="{{ old('identity_number') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('identity_number') border-red-500 bg-red-50 @enderror"
                                placeholder="16 digit NIK">
                            @error('identity_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender Field -->
                        <div class="w-full">
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span
                                    class="text-red-500">*</span></label>
                            <select name="gender" id="gender" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('gender') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Place Field -->
                        <div class="w-full">
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">Tempat
                                Lahir</label>
                            <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('birth_place') border-red-500 bg-red-50 @enderror"
                                placeholder="Kota Kelahiran">
                            @error('birth_place')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Date Field -->
                        <div class="w-full">
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('birth_date') border-red-500 bg-red-50 @enderror">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Blood Type Field -->
                        <div class="w-full">
                            <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-1">Golongan
                                Darah</label>
                            <select name="blood_type" id="blood_type"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('blood_type') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Golongan Darah</option>
                                @foreach(['A', 'B', 'AB', 'O'] as $blood)
                                    <option value="{{ $blood }}" {{ old('blood_type') == $blood ? 'selected' : '' }}>{{ $blood }}
                                    </option>
                                @endforeach
                            </select>
                            @error('blood_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number Field -->
                        <div class="w-full">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                Telepon</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('phone_number') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 08123456789">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Social Security Number (BPJS) Field -->
                        <div class="w-full">
                            <label for="social_security_number" class="block text-sm font-medium text-gray-700 mb-1">No.
                                BPJS/KIS</label>
                            <input type="text" name="social_security_number" id="social_security_number"
                                value="{{ old('social_security_number') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('social_security_number') border-red-500 bg-red-50 @enderror"
                                placeholder="Nomor BPJS Kesehatan">
                            @error('social_security_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Health Facility (Faskes) Field -->
                        <div class="w-full">
                            <label for="health_facility" class="block text-sm font-medium text-gray-700 mb-1">Faskes
                                (BPJS)</label>
                            <select name="health_facility" id="health_facility"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('health_facility') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Faskes</option>
                                <option value="Klinik" {{ old('health_facility') == 'Klinik' ? 'selected' : '' }}>Klinik</option>
                                <option value="Puskesmas" {{ old('health_facility') == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                                <option value="RS" {{ old('health_facility') == 'RS' ? 'selected' : '' }}>RS</option>
                            </select>
                            @error('health_facility')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="w-full md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="address" id="address" rows="3"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('address') border-red-500 bg-red-50 @enderror"
                                placeholder="Alamat domisili lengkap...">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('elderlies.index') }}"
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