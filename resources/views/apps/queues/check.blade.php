@extends('layouts.auth')

@section('title', 'Pilih Anak')

@section('content')
    <div class="mb-8 text-center md:text-left">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Halo, {{ $mother->name }}</h2>
        <p class="text-gray-500">Silakan pilih anak yang akan melakukan pemeriksaan.</p>
    </div>

    @if ($children->isEmpty())
        <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-6 text-center">
            <p class="text-yellow-700 font-medium mb-2">Tidak ada data anak ditemukan.</p>
            <p class="text-sm text-yellow-600">Pastikan data anak sudah didaftarkan oleh kader.</p>
        </div>
        <div class="mt-6 text-center">
            <a href="{{ route('queues.public.index') }}" class="text-teal-600 font-medium hover:underline">Cari NIK Lain</a>
        </div>
    @else
        <form action="{{ route('queues.public.store') }}" method="POST" id="queueForm">
            @csrf
            <input type="hidden" name="type" value="growth_monitoring">

            <div class="mb-4 flex items-center justify-between bg-teal-50 p-3 rounded-lg border border-teal-100">
                <div class="flex items-center">
                    <input type="checkbox" id="selectAll"
                        class="w-5 h-5 text-teal-600 rounded border-gray-300 focus:ring-teal-500">
                    <label for="selectAll" class="ml-2 text-sm font-medium text-teal-800 cursor-pointer">Pilih Semua
                        Anak</label>
                </div>
                <span class="text-xs text-teal-600 font-medium">Bisa pilih lebih dari satu</span>
            </div>

            <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 mb-6">
                @foreach ($children as $child)
                    <div class="relative border border-gray-200 rounded-xl p-4 hover:border-teal-500 hover:shadow-md transition-all bg-gray-50 hover:bg-white cursor-pointer"
                        onclick="document.getElementById('child_{{ $child->id }}').click()">
                        <div class="flex items-center">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="child_ids[]" value="{{ $child->id }}"
                                    id="child_{{ $child->id }}"
                                    class="child-checkbox w-5 h-5 text-teal-600 rounded border-gray-300 focus:ring-teal-500"
                                    onclick="event.stopPropagation()">
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-bold text-gray-800 text-lg">{{ $child->name }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($child->birth_date)->format('d M Y') }}
                                    ({{ \Carbon\Carbon::parse($child->birth_date)->age }} Tahun)
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" id="submitBtn" disabled
                class="w-full py-3 px-4 bg-teal-600 text-white font-bold rounded-lg shadow-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                Ambil Antrian Terpilih
            </button>
        </form>

        <script>
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.child-checkbox');
            const submitBtn = document.getElementById('submitBtn');

            function updateSubmitButton() {
                const checkedCount = document.querySelectorAll('.child-checkbox:checked').length;
                submitBtn.disabled = checkedCount === 0;
                submitBtn.textContent = checkedCount > 0 ? `Ambil Antrian (${checkedCount} Anak)` : 'Ambil Antrian Terpilih';
            }

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateSubmitButton();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    selectAll.checked = checkboxes.length === document.querySelectorAll(
                        '.child-checkbox:checked').length;
                    updateSubmitButton();
                });
            });
        </script>

        <div class="mt-6 text-center border-t border-gray-100 pt-4">
            <a href="{{ route('queues.public.index') }}"
                class="text-sm text-gray-500 hover:text-teal-600 transition-colors">Gunakan NIK Lain</a>
        </div>
    @endif
@endsection
