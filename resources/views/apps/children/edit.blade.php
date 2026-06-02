@extends('layouts.app')

@section('title', 'Ubah Data Anak')

@section('content')
<div class="w-full mx-auto space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Anak', 'url' => route('childrens.index')],
        ['label' => 'Ubah Data']
    ]" />

    <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Form Ubah Data Anak</h2>
            <p class="text-sm text-gray-500">Perbarui informasi anak.</p>
        </div>
        
        <form action="{{ route('childrens.update', $children) }}" method="POST" class="p-6 space-y-8" autocomplete="off">
            @csrf
            @method('PUT')

            <!-- Child Identity Section -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">Data Anak</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Identity Number Field (NIK) -->
                    <div class="w-full">
                        <label for="identity_number" class="block text-sm font-medium text-gray-700 mb-1">NIK Anak <span class="text-xs text-gray-500">(Opsional)</span></label>
                        <input type="text" name="identity_number" id="identity_number" value="{{ old('identity_number', $children->identity_number) }}"
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('identity_number') border-red-500 bg-red-50 @enderror"
                            placeholder="Nomor Induk Kependudukan (16 digit)">
                        @error('identity_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name Field -->
                    <div class="w-full">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $children->name) }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('name') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: Budi Santoso">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                     <!-- Mother Field (Disabled as changing mother usually requires re-validation or logic) -->
                    <div class="w-full">
                        <label for="mother_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                         <select name="mother_id" id="mother_id" disabled
                            class="block w-full rounded-lg border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed shadow-sm sm:text-sm p-2.5">
                            <option value="{{ $children->mother_id }}" selected>{{ $children->mother->name }}</option>
                        </select>
                         <p class="text-xs text-gray-500 mt-1">Data ibu tidak dapat diubah dari menu ini.</p>
                    </div>

                    <!-- Gender Field -->
                    <div class="w-full">
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="gender" id="gender" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('gender') border-red-500 bg-red-50 @enderror">
                            <option value="">Pilih Jenis Kelamin...</option>
                            <option value="male" {{ old('gender', $children->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $children->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                         @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birth Place Field -->
                    <div class="w-full">
                        <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $children->birth_place) }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('birth_place') border-red-500 bg-red-50 @enderror"
                            placeholder="Kota Kelahiran">
                        @error('birth_place')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birth Date Field -->
                    <div class="w-full">
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $children->birth_date->format('Y-m-d')) }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('birth_date') border-red-500 bg-red-50 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                     <!-- Birth Weight Field -->
                    <div class="w-full">
                        <label for="birth_weight" class="block text-sm font-medium text-gray-700 mb-1">Berat Lahir (kg) <span class="text-red-500">*</span></label>
                        <input type="text" name="birth_weight" id="birth_weight" value="{{ old('birth_weight', $children->birth_weight) }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('birth_weight') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: 3.5">
                        @error('birth_weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                     <!-- Birth Height Field -->
                    <div class="w-full">
                        <label for="birth_height" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Lahir (cm) <span class="text-red-500">*</span></label>
                        <input type="text" name="birth_height" id="birth_height" value="{{ old('birth_height', $children->birth_height) }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('birth_height') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: 50">
                        @error('birth_height')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- BPJS Facility Field -->
                    <div class="w-full">
                        <label for="bpjs_facility" class="block text-sm font-medium text-gray-700 mb-1">Faskes (BPJS) <span class="text-xs text-gray-500">(Opsional)</span></label>
                        <select name="bpjs_facility" id="bpjs_facility"
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('bpjs_facility') border-red-500 bg-red-50 @enderror">
                            <option value="">Pilih Faskes...</option>
                            <option value="Klinik" {{ old('bpjs_facility', $children->bpjs_facility) == 'Klinik' ? 'selected' : '' }}>Klinik</option>
                            <option value="Puskesmas" {{ old('bpjs_facility', $children->bpjs_facility) == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                            <option value="RS" {{ old('bpjs_facility', $children->bpjs_facility) == 'RS' ? 'selected' : '' }}>RS</option>
                        </select>
                        @error('bpjs_facility')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('childrens.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
