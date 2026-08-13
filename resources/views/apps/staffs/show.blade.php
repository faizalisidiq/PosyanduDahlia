@extends('layouts.app')

@section('title', 'Detail Staff')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Staff', 'url' => route('staffs.index')],
        ['label' => $staff->user->name]
    ]" />

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
             <div class="h-16 w-16 flex-shrink-0">
                @if($staff->avatar)
                    <img class="h-16 w-16 rounded-full object-cover border-2 border-white shadow-sm" src="{{ asset('storage/' . $staff->avatar) }}" alt="{{ $staff->user->name }}">
                @else
                    <div class="h-16 w-16 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold text-2xl border-2 border-white shadow-sm">
                        {{ substr($staff->user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $staff->user->name }}</h1>
                <p class="text-base text-gray-500">{{ $staff->role }} - {{ $staff->healthPost->name }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <button type="button" onclick="showStaffTasks({{ $staff->id }})" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 00-2 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Lihat Tugas
            </button>
            <button type="button" onclick="sendStaffWhatsApp({{ $staff->id }})" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-base font-medium rounded-lg transition-colors shadow-sm" title="Kirim daftar tugas melalui WhatsApp">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436.002 9.861-4.422 9.864-9.863.002-2.635-1.023-5.113-2.884-6.977C16.59 1.899 14.116.877 11.48.877c-5.44 0-9.866 4.422-9.869 9.864-.001 1.77.464 3.504 1.349 5.045l-.988 3.606 3.693-.97c1.503.82 3.19 1.253 4.892 1.254zm10.982-4.943c-.27-.135-1.602-.79-1.85-.882-.25-.092-.432-.136-.614.137-.182.273-.701.882-.86 1.064-.158.182-.317.205-.587.07-1.32-.662-2.188-1.09-3.071-2.607-.234-.402-.024-.316.242-.58.24-.237.27-.318.406-.54.135-.227.068-.426-.035-.626-.101-.2-.86-2.075-1.178-2.842-.31-.749-.627-.648-.86-.661-.182-.01-.391-.012-.6-.012s-.548.078-.836.39c-.288.313-1.1.1-1.1 2.686s1.828 5.08 2.08 5.424c.254.344 3.6 5.498 8.72 7.712 1.218.527 2.17.84 2.91 1.076 1.226.39 2.342.335 3.224.203.983-.147 2.623-1.073 2.993-2.11.37-1.037.37-1.929.26-2.11-.11-.18-.306-.27-.576-.405z"/>
                </svg>
                Kirim WA
            </button>
            <a href="{{ route('staffs.edit', $staff) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-base font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Ubah
            </a>
            <form action="{{ route('staffs.destroy', $staff) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Akun pengguna ini juga akan dihapus.');" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 border border-red-100 text-base font-medium rounded-lg hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Info Card -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-[0_0_20px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Informasi Detail</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Email</label>
                            <p class="text-gray-900 font-medium break-words">{{ $staff->user->email }}</p>
                        </div>
                         <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Nomor Telepon</label>
                            <p class="text-gray-900 font-mono">{{ $staff->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Posyandu</label>
                            <p class="text-gray-900 font-medium">{{ $staff->healthPost->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Peran</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $staff->role }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Alamat Domisili</label>
                        <p class="text-gray-900 leading-relaxed">{{ $staff->address ?? '-' }}</p>
                    </div>
                     <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Terdaftar Sejak</label>
                        <p class="text-gray-600 text-base">{{ $staff->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Descriptions or Other Info -->
        <div class="md:col-span-1 space-y-6">
             <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-[0_5px_15px_rgba(37,99,235,0.3)] border border-transparent overflow-hidden text-white p-6 relative">
                 <div class="relative z-10">
                     <h3 class="font-bold text-lg mb-1">Aktivitas</h3>
                     <p class="text-blue-100 text-base mb-4">Ringkasan aktivitas pengguna.</p>
                     
                     <!-- Mockup Stats -->
                     <div class="space-y-4">
                         <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                             <div class="text-sm text-blue-100 uppercase">Login Terakhir</div>
                             <div class="font-medium">Belum pernah login</div>
                         </div>
                     </div>
                 </div>
                 
                 <!-- Decoration -->
                 <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                 <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-blue-400/20 rounded-full blur-xl"></div>
            </div>
        </div>
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
