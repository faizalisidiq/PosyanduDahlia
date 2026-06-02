@extends('layouts.app')

@section('title', 'Ubah Posyandu')

@section('content')
<div class="w-full mx-auto space-y-6">
    <!-- Breadcrumb / Back -->
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Posyandu', 'url' => route('health-posts.index')],
        ['label' => 'Ubah Data']
    ]" />

    <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Form Ubah Posyandu</h2>
            <p class="text-sm text-gray-500">Perbarui informasi posyandu.</p>
        </div>
        
        <form action="{{ route('health-posts.update', $healthPost) }}" method="POST" class="p-6 space-y-6" autocomplete="off">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div class="w-full">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Posyandu <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $healthPost->name) }}" required
                    class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('name') border-red-500 bg-red-50 @enderror"
                    placeholder="Contoh: Posyandu Melati 1">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Field -->
            <div class="w-full">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon / Kontak</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $healthPost->phone) }}"
                    class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('phone') border-red-500 bg-red-50 @enderror"
                    placeholder="Contoh: 08123456789">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address Field -->
            <div class="w-full">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                <textarea name="address" id="address" rows="3"
                    class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('address') border-red-500 bg-red-50 @enderror"
                    placeholder="Tuliskan alamat lengkap posyandu disini...">{{ old('address', $healthPost->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('health-posts.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
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
