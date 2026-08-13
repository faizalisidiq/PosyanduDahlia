@extends('layouts.app')

@section('title', 'Tambah Staff')

@section('content')
<div class="w-full mx-auto space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Staff', 'url' => route('staffs.index')],
        ['label' => 'Tambah Baru']
    ]" />

    <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Form Tambah Staff</h2>
            <p class="text-sm text-gray-500">Isi informasi untuk mendaftarkan staff atau kader baru.</p>
        </div>
        
        <form action="{{ route('staffs.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8" autocomplete="off">
            @csrf

            <!-- User Information Section -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">Informasi Akun</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name Field -->
                    <div class="w-full">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('name') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: Siti Aminah">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="w-full">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('email') border-red-500 bg-red-50 @enderror"
                            placeholder="email@contoh.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="w-full">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('password') border-red-500 bg-red-50 @enderror"
                            placeholder="********">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="w-full">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                            placeholder="********">
                    </div>
                </div>
            </div>

            <!-- Staff Details Section -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">Detail Staff</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Role Field -->
                    <div class="w-full">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Peran / Jabatan <span class="text-red-500">*</span></label>
                        <select name="role" id="role" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('role') border-red-500 bg-red-50 @enderror">
                            <option value="">Pilih Peran...</option>
                            <option value="ketua-kader" {{ old('role') == 'ketua-kader' ? 'selected' : '' }}>Ketua Kader</option>
                            <option value="anggota-kader" {{ old('role') == 'anggota-kader' ? 'selected' : '' }}>Anggota Kader</option>
                            <option value="bidan-desa" {{ old('role') == 'bidan-desa' ? 'selected' : '' }}>Bidan Desa</option>
                            <option value="administrator" {{ old('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
                        </select>
                         @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Health Post Field -->
                    <div class="w-full">
                        <label for="health_post_id" class="block text-sm font-medium text-gray-700 mb-1">Posyandu <span class="text-red-500">*</span></label>
                        <select name="health_post_id" id="health_post_id" required
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('health_post_id') border-red-500 bg-red-50 @enderror">
                            <option value="">Pilih Posyandu...</option>
                            @foreach($healthPosts as $hp)
                                <option value="{{ $hp->id }}" {{ old('health_post_id') == $hp->id ? 'selected' : '' }}>{{ $hp->name }}</option>
                            @endforeach
                        </select>
                         @error('health_post_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="w-full">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('phone') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: 08123456789">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Avatar Field -->
                    <div class="w-full">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition-all border border-gray-200 rounded-lg bg-gray-50 p-1">
                        <p class="mt-1 text-xs text-gray-500">Maksimal 2MB (JPG, PNG)</p>
                         @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                     <!-- Address Field -->
                    <div class="w-full md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" id="address" rows="3"
                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('address') border-red-500 bg-red-50 @enderror"
                            placeholder="Alamat domisili staff...">{{ old('address') }}</textarea>
                         @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('staffs.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
