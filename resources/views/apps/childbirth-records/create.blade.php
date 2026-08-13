@extends('layouts.app')

@section('title', 'Tambah Persalinan')

@section('content')
    <div class="w-full mx-auto space-y-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Data Persalinan', 'url' => route('childbirth-records.index')],
            ['label' => 'Tambah Baru'],
        ]" />

        <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Form Data Persalinan</h2>
                <p class="text-sm text-gray-500">Catat informasi persalinan ibu hamil.</p>
            </div>

            <form action="{{ route('childbirth-records.store') }}" method="POST" class="p-6 space-y-8" autocomplete="off">
                @csrf

                <!-- Record Info Section -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                        Detail Persalinan & Data Bayi</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mother Field -->
                        <div class="w-full">
                            <label for="mother_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu <span
                                    class="text-red-500">*</span></label>
                            <select name="mother_id" id="mother_id" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('mother_id') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Ibu...</option>
                                @foreach ($mothers as $mother)
                                    <option value="{{ $mother->id }}"
                                        {{ old('mother_id') == $mother->id ? 'selected' : '' }}>{{ $mother->name }} -
                                        {{ $mother->identity_number }}</option>
                                @endforeach
                            </select>
                            @error('mother_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Staff Field -->
                        <div class="w-full">
                            <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-1">Petugas Penolong
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



                        <!-- Child Order -->
                        <div class="w-full">
                            <label for="child_order" class="block text-sm font-medium text-gray-700 mb-1">Anak Ke- <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="child_order" id="child_order" value="{{ old('child_order', 1) }}"
                                min="1" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('child_order') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 1">
                            @error('child_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Delivery Method -->
                        <div class="w-full">
                            <label for="delivery_method" class="block text-sm font-medium text-gray-700 mb-1">Metode
                                Persalinan <span class="text-red-500">*</span></label>
                            <select name="delivery_method" id="delivery_method" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('delivery_method') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Metode...</option>
                                <option value="Normal" {{ old('delivery_method') == 'Normal' ? 'selected' : '' }}>Normal
                                </option>
                                <option value="Caesar (Sesar)"
                                    {{ old('delivery_method') == 'Caesar (Sesar)' ? 'selected' : '' }}>Caesar (Sesar)
                                </option>
                                <option value="Water Birth"
                                    {{ old('delivery_method') == 'Water Birth' ? 'selected' : '' }}>Water Birth</option>
                                <option value="Lainnya" {{ old('delivery_method') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                            @error('delivery_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Delivery Date -->
                        <div class="w-full">
                            <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Persalinan <span class="text-red-500">*</span></label>
                            <input type="date" name="delivery_date" id="delivery_date"
                                value="{{ old('delivery_date', date('Y-m-d')) }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('delivery_date') border-red-500 bg-red-50 @enderror">
                            @error('delivery_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Delivery Location -->
                        <div class="w-full">
                            <label for="delivery_location" class="block text-sm font-medium text-gray-700 mb-1">Tempat
                                Persalinan <span class="text-red-500">*</span></label>
                            <input type="text" name="delivery_location" id="delivery_location"
                                value="{{ old('delivery_location') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('delivery_location') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: Puskesmas, Rumah Sakit, Bidan">
                            @error('delivery_location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Child Data Section -->
                        <div class="w-full md:col-span-2 pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-medium text-gray-800 mb-4">Informasi Bayi Baru Lahir</h4>
                        </div>

                        <!-- Child NIK -->
                        <div class="w-full">
                            <label for="child_identity_number" class="block text-sm font-medium text-gray-700 mb-1">NIK Bayi
                                <span class="text-xs text-gray-500">(Opsional)</span></label>
                            <input type="text" name="child_identity_number" id="child_identity_number"
                                value="{{ old('child_identity_number') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('child_identity_number') border-red-500 bg-red-50 @enderror"
                                placeholder="NIK Bayi">
                            @error('child_identity_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Child Name -->
                        <div class="w-full">
                            <label for="child_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Bayi <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="child_name" id="child_name" value="{{ old('child_name') }}"
                                required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('child_name') border-red-500 bg-red-50 @enderror"
                                placeholder="Nama Bayi">
                            @error('child_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="w-full">
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span
                                    class="text-red-500">*</span></label>
                            <select name="gender" id="gender" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('gender') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Place -->
                        <div class="w-full">
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}"
                                required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('birth_place') border-red-500 bg-red-50 @enderror"
                                placeholder="Kota Tempat Lahir (contoh: Delivery Location)">
                            @error('birth_place')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Weight -->
                        <div class="w-full">
                            <label for="birth_weight" class="block text-sm font-medium text-gray-700 mb-1">Berat Lahir
                                (kg) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="birth_weight" id="birth_weight"
                                value="{{ old('birth_weight') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('birth_weight') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 3.2">
                            @error('birth_weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Height -->
                        <div class="w-full">
                            <label for="birth_height" class="block text-sm font-medium text-gray-700 mb-1">Panjang Lahir
                                (cm) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" name="birth_height" id="birth_height"
                                value="{{ old('birth_height') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('birth_height') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 48">
                            @error('birth_height')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Baby Condition -->
                        <div class="w-full md:col-span-2">
                            <label for="baby_condition" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Bayi
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="baby_condition" id="baby_condition"
                                value="{{ old('baby_condition') }}" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('baby_condition') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: Sehat, Menangis Kuat, Berat 3.2kg">
                            @error('baby_condition')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('childbirth-records.index') }}"
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
