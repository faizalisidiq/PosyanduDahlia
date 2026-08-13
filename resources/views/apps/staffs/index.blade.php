@extends('layouts.app')

@section('title', 'Daftar Staff')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-end gap-4">
        <div class="md:text-right">
            <p class="text-lg text-black font-semibold">Kelola data staff dan kader posyandu.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center shadow-sm" role="alert">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium text-base">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center shadow-sm" role="alert">
             <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium text-base">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative w-full max-w-lg">
                <form action="{{ route('staffs.index') }}" method="GET" class="flex gap-2">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            style="padding-left: 3rem !important;"
                            class="block w-full border border-gray-200 rounded-lg text-base bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-colors py-2 pr-3" 
                            placeholder="Cari nama staff...">
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-base font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                        Cari
                    </button>
                </form>
            </div>
            <a href="{{ route('staffs.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-base font-medium rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 flex-shrink-0 w-full md:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Staff
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 text-sm uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Nama Staff</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Peran</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Posyandu</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Kontak</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-base">
                    @forelse($staffs as $staff)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        @if($staff->avatar)
                                            <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="{{ asset('storage/' . $staff->avatar) }}" alt="{{ $staff->user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold border border-teal-200">
                                                {{ substr($staff->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $staff->user->name }}</div>
                                        <div class="text-gray-500 text-sm">{{ $staff->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-100 w-max">
                                        {{ $staff->role }}
                                    </span>
                                    @if($staff->status === 'pending')
                                         <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-50 text-yellow-700 border border-yellow-100 w-max">
                                            Menunggu Validasi
                                        </span>
                                    @elseif($staff->status === 'active')
                                         <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-50 text-green-700 border border-green-100 w-max">
                                            Aktif
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 truncate max-w-xs whitespace-nowrap">{{ $staff->healthPost->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600 font-mono text-sm whitespace-nowrap">{{ $staff->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($staff->status === 'pending')
                                        <form action="{{ route('staffs.approve', $staff) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors shadow-sm" title="Validasi Anggota">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" onclick="showStaffTasks({{ $staff->id }})" class="inline-flex items-center justify-center w-8 h-8 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors shadow-sm" title="Lihat Tugas">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 00-2 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                    </button>
                                    <button type="button" onclick="sendStaffWhatsApp({{ $staff->id }})" class="inline-flex items-center justify-center w-8 h-8 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors shadow-sm" title="Kirim daftar tugas melalui WhatsApp">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436.002 9.861-4.422 9.864-9.863.002-2.635-1.023-5.113-2.884-6.977C16.59 1.899 14.116.877 11.48.877c-5.44 0-9.866 4.422-9.869 9.864-.001 1.77.464 3.504 1.349 5.045l-.988 3.606 3.693-.97c1.503.82 3.19 1.253 4.892 1.254zm10.982-4.943c-.27-.135-1.602-.79-1.85-.882-.25-.092-.432-.136-.614.137-.182.273-.701.882-.86 1.064-.158.182-.317.205-.587.07-1.32-.662-2.188-1.09-3.071-2.607-.234-.402-.024-.316.242-.58.24-.237.27-.318.406-.54.135-.227.068-.426-.035-.626-.101-.2-.86-2.075-1.178-2.842-.31-.749-.627-.648-.86-.661-.182-.01-.391-.012-.6-.012s-.548.078-.836.39c-.288.313-1.1.1-1.1 2.686s1.828 5.08 2.08 5.424c.254.344 3.6 5.498 8.72 7.712 1.218.527 2.17.84 2.91 1.076 1.226.39 2.342.335 3.224.203.983-.147 2.623-1.073 2.993-2.11.37-1.037.37-1.929.26-2.11-.11-.18-.306-.27-.576-.405z"/>
                                        </svg>
                                    </button>
                                    <a href="{{ route('staffs.show', $staff) }}" class="inline-flex items-center justify-center w-8 h-8 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors shadow-sm" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('staffs.edit', $staff) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-sm" title="Ubah Data">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('staffs.destroy', $staff) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Akun pengguna ini juga akan dihapus.');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background-color: #dc2626 !important; color: white !important;" class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors shadow-sm hover:opacity-90" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center max-w-xs mx-auto">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-gray-900 font-medium text-base mb-1">Belum ada data staff</h3>
                                    <p class="text-sm text-gray-500 mb-4">Silakan tambahkan data staff baru.</p>
                                    <a href="{{ route('staffs.create') }}" class="text-teal-600 hover:text-teal-700 text-base font-medium hover:underline">+ Tambah Staff</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($staffs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $staffs->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function showStaffTasks(staffId) {
    Swal.fire({
        title: 'Memuat Data...',
        text: 'Silakan tunggu sebentar.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/staffs/${staffId}/tasks`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            Swal.fire({
                title: 'Informasi',
                text: data.message,
                icon: 'warning',
                confirmButtonColor: '#0d9488'
            });
            return;
        }

        let html = '<div class="text-left space-y-4 font-sans text-base">';

        if (data.tasks.mothers && data.tasks.mothers.length > 0) {
            html += '<div class="bg-gray-50 p-4 rounded-lg border border-gray-100">';
            html += '  <h4 class="font-bold text-teal-700 border-b border-teal-100 pb-1 mb-2 flex items-center gap-2">';
            html += '    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>';
            html += '    DATA IBU';
            html += '  </h4>';
            html += '  <ul class="list-disc pl-5 space-y-1 text-gray-700 font-medium">';
            data.tasks.mothers.forEach(function(m) {
                html += '    <li>' + m + '</li>';
            });
            html += '  </ul>';
            html += '</div>';
        }

        if (data.tasks.children && data.tasks.children.length > 0) {
            html += '<div class="bg-gray-50 p-4 rounded-lg border border-gray-100">';
            html += '  <h4 class="font-bold text-blue-700 border-b border-blue-100 pb-1 mb-2 flex items-center gap-2">';
            html += '    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>';
            html += '    DATA BALITA';
            html += '  </h4>';
            html += '  <ul class="list-disc pl-5 space-y-1 text-gray-700 font-medium">';
            data.tasks.children.forEach(function(c) {
                html += '    <li>' + c + '</li>';
            });
            html += '  </ul>';
            html += '</div>';
        }

        if (data.tasks.elderlies && data.tasks.elderlies.length > 0) {
            html += '<div class="bg-gray-50 p-4 rounded-lg border border-gray-100">';
            html += '  <h4 class="font-bold text-purple-700 border-b border-purple-100 pb-1 mb-2 flex items-center gap-2">';
            html += '    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>';
            html += '    DATA LANSIA';
            html += '  </h4>';
            html += '  <ul class="list-disc pl-5 space-y-1 text-gray-700 font-medium">';
            data.tasks.elderlies.forEach(function(e) {
                html += '    <li>' + e + '</li>';
            });
            html += '  </ul>';
            html += '</div>';
        }

        html += '</div>';

        Swal.fire({
            title: 'Daftar Tugas Pemeriksaan',
            html: html,
            icon: 'info',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#0d9488'
        });
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'Gagal memuat data tugas.',
            icon: 'error',
            confirmButtonColor: '#ef4444'
        });
    });
}

function sendStaffWhatsApp(staffId) {
    Swal.fire({
        title: 'Menyiapkan Pesan...',
        text: 'Silakan tunggu sebentar.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/staffs/${staffId}/tasks`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            Swal.fire({
                title: 'Informasi',
                text: data.message,
                icon: 'warning',
                confirmButtonColor: '#0d9488'
            });
            return;
        }

        Swal.close();
        
        const url = `https://wa.me/${data.phone}?text=${encodeURIComponent(data.message)}`;
        window.open(url, '_blank');
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'Gagal mengirim pesan WhatsApp.',
            icon: 'error',
            confirmButtonColor: '#ef4444'
        });
    });
}
</script>
@endsection
