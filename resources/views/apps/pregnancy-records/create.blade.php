@extends('layouts.app')

@section('title', 'Tambah Pemeriksaan')

@section('content')
<div class="w-full mx-auto space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Pemeriksaan Kehamilan', 'url' => route('pregnancy-records.index')],
        ['label' => 'Tambah Baru']
    ]" />

    <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Form Pemeriksaan Kehamilan</h2>
            <p class="text-sm text-gray-500">Catat hasil pemeriksaan ibu hamil.</p>
        </div>
        
        <form action="{{ route('pregnancy-records.store') }}" method="POST" class="p-6 space-y-8" autocomplete="off">
            @csrf

            <!-- Record Info Section -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">Detail Pemeriksaan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mother Field -->
                    <div class="w-full">
                        <label for="mother_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu <span class="text-red-500">*</span></label>
                        <select name="mother_id" id="mother_id" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('mother_id') border-red-500 bg-red-50 @enderror">
                            <option value="">Pilih Ibu...</option>
                            @foreach($mothers as $mother)
                                <option value="{{ $mother->id }}" {{ old('mother_id') == $mother->id ? 'selected' : '' }}>{{ $mother->name }} - {{ $mother->identity_number }}</option>
                            @endforeach
                        </select>
                         @error('mother_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Staff Field -->
                    <div class="w-full">
                         <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-1">Petugas Pemeriksa <span class="text-red-500">*</span></label>
                        <select name="staff_id" id="staff_id" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('staff_id') border-red-500 bg-red-50 @enderror">
                            <option value="">Pilih Petugas...</option>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}" {{ old('staff_id') == $staff->id ? 'selected' : '' }}>{{ $staff->user->name }} - {{ $staff->role }}</option>
                            @endforeach
                        </select>
                         @error('staff_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Visit Date -->
                    <div class="w-full">
                        <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pemeriksaan <span class="text-red-500">*</span></label>
                        <input type="date" name="visit_date" id="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('visit_date') border-red-500 bg-red-50 @enderror">
                        @error('visit_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pregnancy Order -->
                    <div class="w-full">
                        <label for="pregnancy_order" class="block text-sm font-medium text-gray-700 mb-1">Hamil Ke- <span class="text-red-500">*</span></label>
                        <input type="number" name="pregnancy_order" id="pregnancy_order" value="{{ old('pregnancy_order', 1) }}" min="1" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('pregnancy_order') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: 1">
                        @error('pregnancy_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gestational Age -->
                    <div class="w-full">
                        <label for="gestational_age" class="block text-sm font-medium text-gray-700 mb-1">Usia Kandungan (Minggu/Bulan) <span class="text-red-500">*</span></label>
                        <input type="text" name="gestational_age" id="gestational_age" value="{{ old('gestational_age') }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('gestational_age') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: 12 Minggu">
                        @error('gestational_age')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                     <!-- Weight -->
                    <div class="w-full">
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan (kg) <span class="text-red-500">*</span></label>
                        <input type="text" name="weight" id="weight" value="{{ old('weight') }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('weight') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: 65">
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Arm Circumference (LILA) -->
                    <div class="w-full">
                        <label for="arm_circumference" class="block text-sm font-medium text-gray-700 mb-1">Lingkar Lengan Atas (LILA) dalam cm <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" name="arm_circumference" id="arm_circumference" value="{{ old('arm_circumference') }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('arm_circumference') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: 23.5">
                        @error('arm_circumference')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                     <!-- Blood Pressure -->
                    <div class="w-full">
                        <label for="blood_pressure" class="block text-sm font-medium text-gray-700 mb-1">Tekanan Darah (mmHg) <span class="text-red-500">*</span></label>
                        <input type="text" name="blood_pressure" id="blood_pressure" value="{{ old('blood_pressure') }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('blood_pressure') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: 120/80">
                         @error('blood_pressure')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('pregnancy-records.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    Simpan Pemeriksaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
