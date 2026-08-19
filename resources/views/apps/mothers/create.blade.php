@extends('layouts.app')

@section('title', 'Tambah Data Ibu')

@section('content')
    <div class="w-full mx-auto space-y-6">

        <!-- Breadcrumb -->
        <x-breadcrumb
            :items="[
                ['label' => 'Data Ibu', 'url' => route('mothers.index')],
                ['label' => 'Tambah Baru']
            ]"
        />

        <!-- Flash Message -->
        @if (session('success'))
            <div
                class="p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center shadow-sm"
                role="alert"
            >
                <svg
                    class="w-5 h-5 mr-3 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

                <span class="font-medium text-base">
                    {{ session('success') }}
                </span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center shadow-sm"
                role="alert"
            >
                <svg
                    class="w-5 h-5 mr-3 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

                <span class="font-medium text-base">
                    {{ session('error') }}
                </span>
            </div>
        @endif


        <!-- Main Card -->
        <div
            class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)]
                   border border-gray-100 overflow-hidden"
        >

            <!-- Header -->
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">
                    Form Tambah Data Ibu
                </h2>

                <p class="text-base text-gray-500">
                    Isi informasi untuk mendaftarkan ibu hamil atau menyusui baru.
                </p>
            </div>


            <!-- Form -->
            <form
                action="{{ route('mothers.store') }}"
                method="POST"
                class="p-6 space-y-8"
                autocomplete="off"
            >

                @csrf


                <!-- =====================================================
                     IDENTITAS DIRI
                ====================================================== -->

                <div class="space-y-4">

                    <h3
                        class="text-sm font-semibold text-teal-700 uppercase
                               tracking-wider border-b border-gray-100 pb-2"
                    >
                        Identitas Diri
                    </h3>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        <!-- Nama -->
                        <div class="w-full">

                            <label
                                for="name"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                Nama Lengkap
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                required
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('name') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: Siti Aminah"
                            >

                            @error('name')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- Nama Suami -->
                        <div class="w-full">

                            <label
                                for="husband_name"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                Nama Suami
                            </label>

                            <input
                                type="text"
                                name="husband_name"
                                id="husband_name"
                                value="{{ old('husband_name') }}"
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('husband_name') border-red-500 bg-red-50 @enderror"
                                placeholder="Nama Suami"
                            >

                            @error('husband_name')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- NIK -->
                        <div class="w-full">

                            <label
                                for="identity_number"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                NIK
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="identity_number"
                                id="identity_number"
                                value="{{ old('identity_number') }}"
                                required
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('identity_number') border-red-500 bg-red-50 @enderror"
                                placeholder="16 digit NIK"
                            >

                            @error('identity_number')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- Nomor Telepon -->
                        <div class="w-full">

                            <label
                                for="phone_number"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                Nomor Telepon
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="phone_number"
                                id="phone_number"
                                value="{{ old('phone_number') }}"
                                required
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('phone_number') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 08123456789"
                            >

                            @error('phone_number')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- BPJS -->
                        <div class="w-full">

                            <label
                                for="social_security_number"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                No. BPJS/KIS
                                <span class="text-sm text-gray-500">
                                    (Opsional)
                                </span>
                            </label>

                            <input
                                type="text"
                                name="social_security_number"
                                id="social_security_number"
                                value="{{ old('social_security_number') }}"
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('social_security_number') border-red-500 bg-red-50 @enderror"
                                placeholder="Nomor BPJS Kesehatan"
                            >

                            @error('social_security_number')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- Faskes -->
                        <div class="w-full">

                            <label
                                for="health_facility"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                Faskes (BPJS)
                                <span class="text-sm text-gray-500">
                                    (Opsional)
                                </span>
                            </label>

                            <select
                                name="health_facility"
                                id="health_facility"
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('health_facility') border-red-500 bg-red-50 @enderror"
                            >
                                <option value="">
                                    Pilih Tipe Faskes
                                </option>

                                <option
                                    value="Klinik"
                                    {{ old('health_facility') == 'Klinik' ? 'selected' : '' }}
                                >
                                    Klinik
                                </option>

                                <option
                                    value="Puskesmas"
                                    {{ old('health_facility') == 'Puskesmas' ? 'selected' : '' }}
                                >
                                    Puskesmas
                                </option>

                                <option
                                    value="RS"
                                    {{ old('health_facility') == 'RS' ? 'selected' : '' }}
                                >
                                    RS
                                </option>
                            </select>

                            @error('health_facility')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- Tempat Lahir -->
                        <div class="w-full">

                            <label
                                for="birth_place"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                Tempat Lahir
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="birth_place"
                                id="birth_place"
                                value="{{ old('birth_place') }}"
                                required
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('birth_place') border-red-500 bg-red-50 @enderror"
                                placeholder="Kota Kelahiran"
                            >

                            @error('birth_place')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- Tanggal Lahir -->
                        <div class="w-full">

                            <label
                                for="birth_date"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                Tanggal Lahir
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="date"
                                name="birth_date"
                                id="birth_date"
                                value="{{ old('birth_date') }}"
                                required
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('birth_date') border-red-500 bg-red-50 @enderror"
                            >

                            @error('birth_date')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- Golongan Darah -->
                        <div class="w-full">

                            <label
                                for="blood_type"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                Golongan Darah
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="blood_type"
                                id="blood_type"
                                required
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('blood_type') border-red-500 bg-red-50 @enderror"
                            >
                                <option value="">
                                    Pilih Golongan Darah
                                </option>

                                @foreach (['A', 'B', 'AB', 'O'] as $blood)

                                    <option
                                        value="{{ $blood }}"
                                        {{ old('blood_type') == $blood ? 'selected' : '' }}
                                    >
                                        {{ $blood }}
                                    </option>

                                @endforeach

                            </select>

                            @error('blood_type')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- Tinggi Badan -->
                        <div class="w-full">

                            <label
                                for="height"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                Tinggi Badan (cm)
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="number"
                                step="0.1"
                                name="height"
                                id="height"
                                value="{{ old('height') }}"
                                required
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('height') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 160"
                            >

                            @error('height')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- Berat Badan -->
                        <div class="w-full">

                            <label
                                for="weight"
                                class="block text-base font-medium text-gray-700 mb-1"
                            >
                                Berat Badan Awal (kg)
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="number"
                                step="0.1"
                                name="weight"
                                id="weight"
                                value="{{ old('weight') }}"
                                required
                                class="block w-full rounded-lg border-gray-200
                                       bg-gray-50 text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('weight') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 55"
                            >

                            @error('weight')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- =================================================
                             ALAT UKUR TINGGI & BERAT
                        ================================================== -->

                        <div class="w-full">

                            <div class="flex flex-wrap items-center gap-2 mt-3">

                                <button
                                    type="button"
                                    id="connectiotScaleButton"
                                    class="px-3 py-2 text-sm font-medium
                                           text-white bg-teal-600
                                           rounded-lg hover:bg-teal-700
                                           transition-all"
                                >
                                    Hubungkan Alat Ukur Tinggi dan Berat
                                </button>


                                <button
                                    type="button"
                                    id="measureiotScaleButton"
                                    class="hidden px-3 py-2 text-sm font-medium
                                           text-white bg-blue-600
                                           rounded-lg hover:bg-blue-700
                                           transition-all"
                                >
                                    Ambil Data
                                </button>


                                <button
                                    type="button"
                                    id="disconnectiotScaleButton"
                                    class="hidden px-3 py-2 text-sm font-medium
                                           text-white bg-red-600
                                           rounded-lg hover:bg-red-700
                                           transition-all"
                                >
                                    Putuskan Alat
                                </button>


                                <span
                                    id="iotscaleStatus"
                                    class="text-sm text-gray-500"
                                >
                                    Alat belum terhubung
                                </span>

                            </div>

                        </div>


                        <!-- =================================================
                             TERMOMETER
                        ================================================== -->

                        <div class="w-full">

                            <label
                                for="temperature"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Suhu Tubuh (°C)
                                <span class="text-xs text-gray-500">
                                    (Opsional)
                                </span>
                            </label>


                            <input
                                type="number"
                                step="0.1"
                                name="temperature"
                                id="temperature"
                                value="{{ old('temperature') }}"
                                class="block w-full rounded-lg
                                       border-gray-200 bg-gray-50
                                       text-gray-900
                                       focus:bg-white focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-sm p-2.5 transition-all
                                       @error('temperature') border-red-500 bg-red-50 @enderror"
                                placeholder="Contoh: 36.5"
                            >


                            <!-- Status Suhu -->
                            <div class="mt-2">
                                <span
                                    id="suhuStatusBadge"
                                    class="hidden inline-flex items-center
                                           px-2.5 py-1 rounded-full
                                           text-xs font-semibold"
                                ></span>
                            </div>


                            <div
                                class="flex flex-wrap items-center gap-2 mt-3"
                            >

                                <button
                                    type="button"
                                    id="connectThermometerButton"
                                    class="px-3 py-2 text-sm font-medium
                                           text-white bg-teal-600
                                           rounded-lg hover:bg-teal-700
                                           transition-all"
                                >
                                    Hubungkan Termometer
                                </button>


                                <button
                                    type="button"
                                    id="disconnectThermometerButton"
                                    class="hidden px-3 py-2 text-sm font-medium
                                           text-white bg-red-600
                                           rounded-lg hover:bg-red-700
                                           transition-all"
                                >
                                    Putuskan Termometer
                                </button>


                                <span
                                    id="thermometerStatus"
                                    class="text-sm text-gray-500"
                                >
                                    Termometer belum terhubung
                                </span>

                            </div>


                            @error('temperature')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- =================================================
                             TENSIMETER YUWELL BP-YE670CR
                        ================================================== -->

                        <div class="w-full md:col-span-2">

                            <div
                                class="rounded-xl border border-gray-200
                                       bg-gray-50 p-5"
                            >

                                <!-- Header -->
                                <div
                                    class="flex flex-col sm:flex-row
                                           sm:items-center
                                           sm:justify-between gap-3 mb-5"
                                >

                                    <div>

                                        <h3
                                            class="text-base font-bold
                                                   text-gray-800"
                                        >
                                            Tekanan Darah & Nadi
                                        </h3>

                                        <p
                                            class="text-sm text-gray-500 mt-1"
                                        >
                                            Yuwell BP-YE670CR
                                        </p>

                                    </div>


                                    <!-- Status Badge -->
                                    <span
                                        id="tensiStatusBadge"
                                        class="hidden inline-flex
                                               items-center
                                               px-3 py-1.5 rounded-full
                                               text-xs font-semibold"
                                    ></span>

                                </div>


                                <!-- Measurement Cards -->
                                <div
                                    class="grid grid-cols-1
                                           sm:grid-cols-3 gap-4"
                                >

                                    <!-- SYS -->
                                    <div
                                        class="bg-white rounded-lg
                                               border border-gray-200
                                               p-4"
                                    >

                                        <label
                                            for="systolic_pressure"
                                            class="block text-sm
                                                   font-medium
                                                   text-gray-500 mb-2"
                                        >
                                            Sistol
                                        </label>

                                        <div class="flex items-end gap-2">

                                            <input
                                                type="number"
                                                name="systolic_pressure"
                                                id="systolic_pressure"
                                                value="{{ old('systolic_pressure') }}"
                                                class="w-full border-0
                                                       border-b-2
                                                       border-gray-200
                                                       bg-transparent
                                                       text-2xl font-bold
                                                       text-gray-800
                                                       p-0 pb-1
                                                       focus:border-teal-500
                                                       focus:ring-0"
                                                placeholder="--"
                                            >

                                            <span
                                                class="text-sm text-gray-400
                                                       pb-1"
                                            >
                                                mmHg
                                            </span>

                                        </div>

                                        @error('systolic_pressure')
                                            <p
                                                class="mt-1 text-sm
                                                       text-red-600"
                                            >
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>


                                    <!-- DIA -->
                                    <div
                                        class="bg-white rounded-lg
                                               border border-gray-200
                                               p-4"
                                    >

                                        <label
                                            for="diastolic_pressure"
                                            class="block text-sm
                                                   font-medium
                                                   text-gray-500 mb-2"
                                        >
                                            Diastol
                                        </label>

                                        <div class="flex items-end gap-2">

                                            <input
                                                type="number"
                                                name="diastolic_pressure"
                                                id="diastolic_pressure"
                                                value="{{ old('diastolic_pressure') }}"
                                                class="w-full border-0
                                                       border-b-2
                                                       border-gray-200
                                                       bg-transparent
                                                       text-2xl font-bold
                                                       text-gray-800
                                                       p-0 pb-1
                                                       focus:border-teal-500
                                                       focus:ring-0"
                                                placeholder="--"
                                            >

                                            <span
                                                class="text-sm text-gray-400
                                                       pb-1"
                                            >
                                                mmHg
                                            </span>

                                        </div>

                                        @error('diastolic_pressure')
                                            <p
                                                class="mt-1 text-sm
                                                       text-red-600"
                                            >
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>


                                    <!-- PULSE -->
                                    <div
                                        class="bg-white rounded-lg
                                               border border-gray-200
                                               p-4"
                                    >

                                        <label
                                            for="pulse"
                                            class="block text-sm
                                                   font-medium
                                                   text-gray-500 mb-2"
                                        >
                                            Nadi
                                        </label>

                                        <div class="flex items-end gap-2">

                                            <input
                                                type="number"
                                                name="pulse"
                                                id="pulse"
                                                value="{{ old('pulse') }}"
                                                class="w-full border-0
                                                       border-b-2
                                                       border-gray-200
                                                       bg-transparent
                                                       text-2xl font-bold
                                                       text-gray-800
                                                       p-0 pb-1
                                                       focus:border-teal-500
                                                       focus:ring-0"
                                                placeholder="--"
                                            >

                                            <span
                                                class="text-sm text-gray-400
                                                       pb-1"
                                            >
                                                bpm
                                            </span>

                                        </div>

                                        @error('pulse')
                                            <p
                                                class="mt-1 text-sm
                                                       text-red-600"
                                            >
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>

                                </div>


                                <!-- Connection Controls -->
                                <div
                                    class="flex flex-wrap
                                           items-center gap-3 mt-5"
                                >

                                    <!-- CONNECT -->
                                    <button
                                        type="button"
                                        id="connectYuwellButton"
                                        class="inline-flex items-center
                                               gap-2 px-4 py-2.5
                                               text-sm font-semibold
                                               text-white bg-teal-600
                                               rounded-lg
                                               hover:bg-teal-700
                                               active:bg-teal-800
                                               transition-all
                                               shadow-sm"
                                    >

                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8.5 12a3.5 3.5 0 017 0m-9.5 0a6 6 0 0012 0m-15 0a9 9 0 0018 0"
                                            />
                                        </svg>

                                        Hubungkan Tensimeter

                                    </button>


                                    <!-- DISCONNECT -->
                                    <button
                                        type="button"
                                        id="disconnectYuwellButton"
                                        class="hidden inline-flex
                                               items-center gap-2
                                               px-4 py-2.5
                                               text-sm font-semibold
                                               text-white bg-red-600
                                               rounded-lg
                                               hover:bg-red-700
                                               active:bg-red-800
                                               transition-all
                                               shadow-sm"
                                    >

                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>

                                        Putuskan Tensimeter

                                    </button>


                                    <!-- STATUS -->
                                    <span
                                        id="tensiStatus"
                                        class="text-sm text-gray-500"
                                    >
                                        Tensimeter belum terhubung
                                    </span>

                                </div>


                                <!-- Information -->
                                <div
                                    class="mt-4 p-3 rounded-lg
                                           bg-blue-50 border
                                           border-blue-100"
                                >

                                    <div
                                        class="flex items-start gap-2"
                                    >

                                        <svg
                                            class="w-5 h-5 text-blue-500
                                                   flex-shrink-0 mt-0.5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"
                                            />
                                        </svg>

                                        <p
                                            class="text-xs text-blue-700"
                                        >
                                            Hubungkan Yuwell BP-YE670CR
                                            melalui Bluetooth. Setelah
                                            perangkat terhubung, lakukan
                                            pengukuran menggunakan tensimeter.
                                            Hasil sistol, diastol, dan nadi
                                            akan otomatis masuk ke form.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- =================================================
                             STATUS IBU
                        ================================================== -->

                        <div class="w-full">

                            <label
                                for="status"
                                class="block text-base font-medium
                                       text-gray-700 mb-1"
                            >
                                Status
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="status"
                                id="status"
                                required
                                class="block w-full rounded-lg
                                       border-gray-200 bg-gray-50
                                       text-gray-900
                                       focus:bg-white
                                       focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('status') border-red-500 bg-red-50 @enderror"
                            >

                                <option
                                    value="hamil"
                                    {{ old('status') == 'hamil' ? 'selected' : '' }}
                                >
                                    Hamil
                                </option>

                                <option
                                    value="menyusui"
                                    {{ old('status') == 'menyusui' ? 'selected' : '' }}
                                >
                                    Menyusui
                                </option>

                                <option
                                    value="lainnya"
                                    {{ old('status') == 'lainnya' ? 'selected' : '' }}
                                >
                                    Anak > 2 Tahun
                                </option>

                            </select>

                            @error('status')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- =================================================
                             ALAMAT
                        ================================================== -->

                        <div class="w-full md:col-span-2">

                            <label
                                for="address"
                                class="block text-base font-medium
                                       text-gray-700 mb-1"
                            >
                                Alamat Lengkap
                                <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                name="address"
                                id="address"
                                rows="3"
                                required
                                class="block w-full rounded-lg
                                       border-gray-200 bg-gray-50
                                       text-gray-900
                                       focus:bg-white
                                       focus:border-teal-500
                                       focus:ring-teal-500 shadow-sm
                                       sm:text-base p-2.5 transition-all
                                       @error('address') border-red-500 bg-red-50 @enderror"
                                placeholder="Alamat domisili lengkap..."
                            >{{ old('address') }}</textarea>

                            @error('address')
                                <p class="mt-1 text-base text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>
                </div>


                <!-- =====================================================
                     ACTIONS
                ====================================================== -->

                <div
                    class="flex items-center justify-end
                           space-x-3 pt-4
                           border-t border-gray-100"
                >

                    <a
                        href="{{ route('mothers.index') }}"
                        class="px-4 py-2 bg-white
                               border border-gray-200
                               text-gray-700 text-base
                               font-medium rounded-lg
                               hover:bg-gray-50
                               transition-colors"
                    >
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="px-4 py-2
                               bg-teal-600 hover:bg-teal-700
                               text-white text-base
                               font-medium rounded-lg
                               shadow-sm transition-colors"
                    >
                        Simpan Data
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection


<!-- ============================================================
     JAVASCRIPT
============================================================= -->

<script
    src="{{ asset('js/iotScale.js') }}?v={{ filemtime(public_path('js/iotScale.js')) }}"
    defer
></script>

<script
    src="{{ asset('js/thermometer.js') }}?v={{ filemtime(public_path('js/thermometer.js')) }}"
    defer
></script>

<script
    src="{{ asset('js/tensimeter.js') }}?v={{ filemtime(public_path('js/tensimeter.js')) }}"
    defer
></script>

<script
    src="{{ asset('js/vital-status.js') }}?v={{ filemtime(public_path('js/vital-status.js')) }}"
    defer
></script>