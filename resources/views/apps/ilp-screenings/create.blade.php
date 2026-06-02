@extends('layouts.app')

@section('title', 'Tambah Screening ILP')

@section('content')
    <div class="w-full mx-auto space-y-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Screening ILP', 'url' => route('ilp-screenings.index')],
            ['label' => 'Tambah Data']
        ]" />

        <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Form Screening ILP</h2>
                <p class="text-sm text-gray-500">Catat hasil screening kesehatan Integrasi Layanan Primer.</p>
            </div>

            <form action="{{ route('ilp-screenings.store') }}" method="POST" class="p-6 space-y-8" autocomplete="off"
                id="ilpForm">
                @csrf

                <!-- Patient Info Section -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                        Data Pasien</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Subject Type -->
                        <div class="w-full">
                            <label for="subjectable_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Pasien
                                <span class="text-red-500">*</span></label>
                            <select name="subjectable_type" id="subjectable_type" required onchange="toggleSubjectList()"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('subjectable_type') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Tipe...</option>
                                <option value="App\Models\Mother" {{ old('subjectable_type') == 'App\Models\Mother' ? 'selected' : '' }}>Ibu</option>
                                <option value="App\Models\Children" {{ old('subjectable_type') == 'App\Models\Children' ? 'selected' : '' }}>Anak</option>
                                <option value="App\Models\Elderly" {{ old('subjectable_type') == 'App\Models\Elderly' ? 'selected' : '' }}>Lansia</option>
                            </select>
                            @error('subjectable_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject ID (Dynamic) -->
                        <div class="w-full">
                            <label for="subjectable_id" class="block text-sm font-medium text-gray-700 mb-1">Nama Pasien
                                <span class="text-red-500">*</span></label>

                            <!-- Mothers List -->
                            <select name="subjectable_id" id="mother_list"
                                class="hidden w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all">
                                <option value="">Pilih Ibu...</option>
                                @foreach($mothers as $mother)
                                    <option value="{{ $mother->id }}" {{ old('subjectable_id') == $mother->id && old('subjectable_type') == 'App\Models\Mother' ? 'selected' : '' }}>{{ $mother->name }}
                                        (NIK: {{ $mother->identity_number }})</option>
                                @endforeach
                            </select>

                            <!-- Children List -->
                            <select name="subjectable_id" id="children_list"
                                class="hidden w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all">
                                <option value="">Pilih Anak...</option>
                                @foreach($childrens as $child)
                                    <option value="{{ $child->id }}" {{ old('subjectable_id') == $child->id && old('subjectable_type') == 'App\Models\Children' ? 'selected' : '' }}>{{ $child->name }}
                                        (Ibu: {{ $child->mother->name }})</option>
                                @endforeach
                            </select>

                            <!-- Elderly List -->
                            <select name="subjectable_id" id="elderly_list"
                                class="hidden w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all">
                                <option value="">Pilih Lansia...</option>
                                @foreach($elderlies as $elderly)
                                    <option value="{{ $elderly->id }}" {{ old('subjectable_id') == $elderly->id && old('subjectable_type') == 'App\Models\Elderly' ? 'selected' : '' }}>{{ $elderly->name }}
                                        (NIK: {{ $elderly->identity_number }})</option>
                                @endforeach
                            </select>

                            @error('subjectable_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Staff Field -->
                        <div class="w-full">
                            <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-1">Petugas Pemeriksa
                                <span class="text-red-500">*</span></label>
                            <select name="staff_id" id="staff_id" required
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all @error('staff_id') border-red-500 bg-red-50 @enderror">
                                <option value="">Pilih Petugas...</option>
                                @foreach($staffs as $staff)
                                    <option value="{{ $staff->id }}" {{ old('staff_id') == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->user->name }} - {{ $staff->role }}
                                    </option>
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
                    </div>
                </div>

                <!-- Screening Results Section -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-teal-700 uppercase tracking-wider border-b border-gray-100 pb-2">
                        Hasil Screening (Indikator Kesehatan)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Weight -->
                        <div class="w-full">
                            <label for="results_weight" class="block text-sm font-medium text-gray-700 mb-1">Berat Badan
                                (kg)</label>
                            <input type="number" step="0.1" name="results[weight]" id="results_weight"
                                value="{{ old('results.weight') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                                placeholder="Contoh: 55.5">
                        </div>

                        <!-- Height -->
                        <div class="w-full">
                            <label for="results_height" class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan
                                (cm)</label>
                            <input type="number" step="0.1" name="results[height]" id="results_height"
                                value="{{ old('results.height') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                                placeholder="Contoh: 160">
                        </div>

                        <!-- Waist -->
                        <div class="w-full">
                            <label for="results_waist" class="block text-sm font-medium text-gray-700 mb-1">Lingkar Perut
                                (cm)</label>
                            <input type="number" step="0.1" name="results[waist_circumference]" id="results_waist"
                                value="{{ old('results.waist_circumference') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                                placeholder="Contoh: 80">
                        </div>

                        <!-- Blood Pressure -->
                        <div class="w-full">
                            <label for="results_blood_pressure" class="block text-sm font-medium text-gray-700 mb-1">Tekanan
                                Darah (mmHg)</label>
                            <input type="text" name="results[blood_pressure]" id="results_blood_pressure"
                                value="{{ old('results.blood_pressure') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                                placeholder="Contoh: 120/80">
                        </div>

                        <!-- Blood Sugar -->
                        <div class="w-full">
                            <label for="results_blood_sugar" class="block text-sm font-medium text-gray-700 mb-1">Gula Darah
                                (mg/dL)</label>
                            <input type="number" name="results[blood_sugar]" id="results_blood_sugar"
                                value="{{ old('results.blood_sugar') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                                placeholder="Contoh: 100">
                        </div>

                        <!-- Uric Acid -->
                        <div class="w-full">
                            <label for="results_uric_acid" class="block text-sm font-medium text-gray-700 mb-1">Asam Urat
                                (mg/dL)</label>
                            <input type="number" step="0.1" name="results[uric_acid]" id="results_uric_acid"
                                value="{{ old('results.uric_acid') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                                placeholder="Contoh: 6.0">
                        </div>

                        <!-- Cholesterol -->
                        <div class="w-full">
                            <label for="results_cholesterol" class="block text-sm font-medium text-gray-700 mb-1">Kolesterol
                                (mg/dL)</label>
                            <input type="number" name="results[cholesterol]" id="results_cholesterol"
                                value="{{ old('results.cholesterol') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                                placeholder="Contoh: 180">
                        </div>

                        <!-- Eyes -->
                        <div class="w-full">
                            <label for="results_eyes" class="block text-sm font-medium text-gray-700 mb-1">Pemeriksaan
                                Mata</label>
                            <input type="text" name="results[eyes]" id="results_eyes" value="{{ old('results.eyes') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                                placeholder="Normal / Buram">
                        </div>

                        <!-- Ears -->
                        <div class="w-full">
                            <label for="results_ears" class="block text-sm font-medium text-gray-700 mb-1">Pemeriksaan
                                Telinga</label>
                            <input type="text" name="results[ears]" id="results_ears" value="{{ old('results.ears') }}"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                                placeholder="Normal / Terganggu">
                        </div>

                        <!-- Note -->
                        <div class="w-full md:col-span-3">
                            <label for="results_note" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan
                                / Diagnosis</label>
                            <textarea name="results[note]" id="results_note" rows="3"
                                class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:border-teal-500 focus:ring-teal-500 shadow-sm sm:text-sm p-2.5 transition-all"
                                placeholder="Catatan hasil screening...">{{ old('results.note') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('ilp-screenings.index') }}"
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

    <script>
        function toggleSubjectList() {
            const type = document.getElementById('subjectable_type').value;
            const motherList = document.getElementById('mother_list');
            const childrenList = document.getElementById('children_list');
            const elderlyList = document.getElementById('elderly_list');

            // Hide all first
            motherList.classList.add('hidden');
            childrenList.classList.add('hidden');
            elderlyList.classList.add('hidden');

            motherList.removeAttribute('name');
            childrenList.removeAttribute('name');
            elderlyList.removeAttribute('name');

            if (type === 'App\\Models\\Mother') {
                motherList.classList.remove('hidden');
                motherList.setAttribute('name', 'subjectable_id');
            } else if (type === 'App\\Models\\Children') {
                childrenList.classList.remove('hidden');
                childrenList.setAttribute('name', 'subjectable_id');
            } else if (type === 'App\\Models\\Elderly') {
                elderlyList.classList.remove('hidden');
                elderlyList.setAttribute('name', 'subjectable_id');
            }
        }

        // Initialize on load to handle validation errors returning old input
        document.addEventListener('DOMContentLoaded', function () {
            toggleSubjectList();
        });
    </script>
@endsection