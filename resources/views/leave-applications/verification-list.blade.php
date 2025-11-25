<x-app-layout>
    {{-- HEADER --}}
<x-slot name="header">
    <div class="bg-white border-b border-gray-200 -mx-6 -mt-6 px-6 py-6 shadow-sm">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                {{-- Title Section --}}
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Verifikasi Cuti</h1>
                        <p class="text-gray-500 mt-1">Tinjau dan kelola pengajuan cuti tim Anda</p>
                    </div>
                </div>

                {{-- User Info & Actions --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    {{-- Quick Filter --}}
                    <div class="flex items-center space-x-2 bg-gray-50 rounded-lg px-3 py-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $pendingApplications->count() }} menunggu</span>
                    </div>
                    
                    {{-- User Profile --}}
                    <div class="flex items-center space-x-3 bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200">
                        <div class="flex items-center space-x-2">
                            <div class="h-2 w-2 rounded-full bg-green-500"></div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->role == 'ketua_divisi' ? 'Ketua Divisi' : 'HRD' }}</p>
                            </div>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- STATS & FILTER --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    {{-- Statistik --}}
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">Menunggu Verifikasi</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $pendingApplications->count() }} Pengajuan</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" 
                     class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-green-500 hover:text-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                    class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm backdrop-blur-sm bg-white/80">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

{{-- CARD GRID --}}
@if($pendingApplications->isEmpty())
    {{-- Empty State --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-24 h-24 mx-auto mb-6 bg-blue-50 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Pengajuan</h3>
            <p class="text-gray-500">Semua pengajuan cuti telah diverifikasi. Tidak ada yang menunggu persetujuan saat ini.</p>
        </div>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($pendingApplications as $application)
            @php
                $isSick = $application->leave_type == 'sakit';
                $typeColors = [
                    'tahunan' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                    'sakit' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200'],
                    'melahirkan' => ['bg' => 'bg-pink-50', 'text' => 'text-pink-700', 'border' => 'border-pink-200'],
                    'penting' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200']
                ];
                $colors = $typeColors[$application->leave_type] ?? $typeColors['tahunan'];
                
                // ✅ TAMBAHKAN: Cek apakah ada approval atasan
                $hasLeaderApproval = $application->leader_approver && $application->leader_approval_at;
                $isHrdView = Auth::user()->role == 'hrd';
                $isLeaderView = Auth::user()->role == 'ketua_divisi';
            @endphp
            
            {{-- CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 overflow-hidden">
                {{-- Card Header --}}
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            {{-- Foto Profil --}}
                            <div class="relative">
                                @if($application->applicant->profile_photo_path)
                                    <img src="{{ Storage::url($application->applicant->profile_photo_path) }}" 
                                         class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                        {{ substr($application->applicant->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $application->applicant->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $application->applicant->division->name ?? 'Staff' }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colors['bg'] }} {{ $colors['text'] }} {{ $colors['border'] }} border">
                            {{ ucfirst($application->leave_type) }}
                        </span>
                    </div>

                    {{-- INFORMASI APPROVAL ATASAN (TAMPILKAN JIKA ADA) --}}
                    @if($hasLeaderApproval)
                    <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="p-1 bg-green-100 rounded">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-green-800">Disetujui oleh Atasan</p>
                                    <p class="text-xs text-green-700 font-semibold">{{ $application->leader_approver->name }}</p>
                                    <p class="text-xs text-green-600">{{ $application->leader_approver->division->name ?? 'Ketua Divisi' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium text-green-800">{{ \Carbon\Carbon::parse($application->leader_approval_at)->format('d M Y') }}</p>
                                <p class="text-xs text-green-600">{{ \Carbon\Carbon::parse($application->leader_approval_at)->format('H:i') }}</p>
                            </div>
                        </div>
                        
                        {{-- TAMPILKAN CATATAN ATASAN JIKA ADA --}}
                        @if($application->leader_approval_note)
                        <div class="mt-2 p-2 bg-white rounded border border-green-100">
                            <p class="text-xs text-green-700 italic">"{{ $application->leader_approval_note }}"</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- INFORMASI UNTUK KETUA DIVISI YANG LANGSUNG KE HRD --}}
                    @if($isHrdView && $application->status == 'pending' && $application->applicant->role == 'ketua_divisi')
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center space-x-2">
                            <div class="p-1 bg-blue-100 rounded">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-blue-800">Pengajuan Langsung</p>
                                <p class="text-xs text-blue-700">Ketua Divisi - Langsung ke Persetujuan Final</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- STATUS UNTUK KETUA DIVISI YANG SUDAH APPROVE --}}
                    @if($isLeaderView && $application->leader_approver_id == Auth::id())
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="p-1 bg-blue-100 rounded">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-blue-800">Telah Anda Setujui</p>
                                    <p class="text-xs text-blue-700">Menunggu persetujuan HRD</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium text-blue-800">{{ \Carbon\Carbon::parse($application->leader_approval_at)->format('d M Y') }}</p>
                                <p class="text-xs text-blue-600">{{ \Carbon\Carbon::parse($application->leader_approval_at)->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Informasi Cuti --}}
                    <div class="space-y-3">
                        {{-- Periode Cuti --}}
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="text-center">
                                <p class="text-xs text-gray-500 font-medium">Mulai</p>
                                <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($application->start_date)->format('d M Y') }}</p>
                            </div>
                            <div class="text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500 font-medium">Selesai</p>
                                <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($application->end_date)->format('d M Y') }}</p>
                            </div>
                            <div class="text-center border-l border-gray-200 pl-3 ml-3">
                                <p class="text-xs text-gray-500 font-medium">Total</p>
                                <p class="text-lg font-bold text-blue-600">{{ $application->total_days }} Hari</p>
                            </div>
                        </div>

                        {{-- Tanggal Pengajuan --}}
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Diajukan pada {{ \Carbon\Carbon::parse($application->created_at)->format('d M Y') }}
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    {{-- Alasan Cuti --}}
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Alasan Cuti</h4>
                        <p class="text-gray-600 text-sm leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">
                            {{ $application->reason }}
                        </p>
                    </div>

                    {{-- Lampiran --}}
                    @if($isSick && $application->attachment_path)
                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Lampiran</h4>
                            <a href="{{ Storage::url($application->attachment_path) }}" target="_blank" 
                               class="inline-flex items-center text-sm text-orange-600 hover:text-orange-700 font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                Dokumen Pendukung
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Card Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <div class="flex space-x-3">
                        <button onclick="openRejectModal('{{ $application->id }}', '{{ $application->applicant->name }}')" 
                                class="flex-1 py-2.5 px-4 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
                            Tolak
                        </button>
                        <button onclick="openApproveModal('{{ $application->id }}', '{{ $application->applicant->name }}')" 
                                class="flex-1 py-2.5 px-4 bg-green-600 rounded-lg text-sm font-semibold text-white hover:bg-green-700 transition-colors duration-200 shadow-sm">
                            Setujui
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
        </div>
    </div>

    {{-- MODAL APPROVE --}}
    <div id="approveModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeApproveModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form id="approveForm" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Setujui Pengajuan Cuti</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Anda akan menyetujui pengajuan cuti dari <span class="font-semibold text-gray-900" id="approveNamePlaceholder"></span>.
                                        </p>
                                        <div class="mt-4">
                                            <label for="approval_note" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                            <textarea name="approval_note" id="approval_note" rows="3" 
                                                      class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-3"
                                                      placeholder="Tambahkan catatan untuk karyawan..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" 
                                    class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto transition-colors duration-200">
                                Setujui Pengajuan
                            </button>
                            <button type="button" 
                                    onclick="closeApproveModal()"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors duration-200">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL REJECT --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRejectModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form id="rejectForm" method="POST" action="" onsubmit="return validateRejectionNotes()">
                        @csrf
                        @method('PATCH')
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Tolak Pengajuan Cuti</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Anda akan menolak pengajuan cuti dari <span class="font-semibold text-gray-900" id="rejectNamePlaceholder"></span>.
                                        </p>
                                        <div class="mt-4">
                                            <label for="rejection_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                                Alasan Penolakan <span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="rejection_notes" id="rejection_notes" rows="4" required
                                                      class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm p-3 rejection-notes-textarea"
                                                      placeholder="Jelaskan alasan penolakan pengajuan cuti (minimal 10 karakter)..."
                                                      oninput="validateRejectionNotesLive(this)"></textarea>
                                            <div id="rejectionNotesError" class="mt-1 text-xs text-red-600 hidden">
                                                Alasan penolakan harus minimal 10 karakter
                                            </div>
                                            <div id="rejectionNotesCounter" class="mt-1 text-xs text-gray-500 text-right">
                                                <span id="currentChars">0</span>/500 karakter
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" id="rejectSubmitBtn"
                                    class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                Tolak Pengajuan
                            </button>
                            <button type="button" 
                                    onclick="closeRejectModal()"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors duration-200">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        function openApproveModal(id, name) {
            let url = "{{ route('leave-applications.approve', ':id') }}".replace(':id', id);
            document.getElementById('approveForm').action = url;
            document.getElementById('approveNamePlaceholder').textContent = name;
            document.getElementById('approveModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openRejectModal(id, name) {
            let url = "{{ route('leave-applications.reject', ':id') }}".replace(':id', id);
            document.getElementById('rejectForm').action = url;
            document.getElementById('rejectNamePlaceholder').textContent = name;
            document.getElementById('rejectModal').classList.remove('hidden');
            
            // Reset form state ketika modal dibuka
            setTimeout(() => {
                const textarea = document.getElementById('rejection_notes');
                const errorElement = document.getElementById('rejectionNotesError');
                const counterElement = document.getElementById('currentChars');
                const submitBtn = document.getElementById('rejectSubmitBtn');
                
                textarea.value = '';
                errorElement.classList.add('hidden');
                counterElement.textContent = '0';
                textarea.classList.remove('border-red-300', 'bg-red-50');
                textarea.classList.add('border-gray-300');
                submitBtn.disabled = true;
                textarea.focus();
            }, 100);
            
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Validasi real-time saat user mengetik
        function validateRejectionNotesLive(textarea) {
            const text = textarea.value;
            const errorElement = document.getElementById('rejectionNotesError');
            const counterElement = document.getElementById('currentChars');
            const submitBtn = document.getElementById('rejectSubmitBtn');
            
            // Update character counter
            counterElement.textContent = text.length;
            
            // Validate minimum length
            if (text.length < 10 && text.length > 0) {
                errorElement.classList.remove('hidden');
                textarea.classList.add('border-red-300', 'bg-red-50');
                textarea.classList.remove('border-gray-300');
                submitBtn.disabled = true;
            } else {
                errorElement.classList.add('hidden');
                textarea.classList.remove('border-red-300', 'bg-red-50');
                textarea.classList.add('border-gray-300');
                submitBtn.disabled = text.length === 0;
            }
            
            // Validate maximum length
            if (text.length > 500) {
                errorElement.textContent = 'Alasan penolakan maksimal 500 karakter';
                errorElement.classList.remove('hidden');
                submitBtn.disabled = true;
            } else if (text.length < 10) {
                errorElement.textContent = 'Alasan penolakan harus minimal 10 karakter';
            }
        }

        // Validasi sebelum submit form
        function validateRejectionNotes() {
            const textarea = document.getElementById('rejection_notes');
            const text = textarea.value.trim();
            
            if (text.length < 10) {
                // Show error and focus
                const errorElement = document.getElementById('rejectionNotesError');
                errorElement.textContent = 'Alasan penolakan harus minimal 10 karakter';
                errorElement.classList.remove('hidden');
                textarea.classList.add('border-red-300', 'bg-red-50');
                textarea.focus();
                return false;
            }
            
            if (text.length > 500) {
                const errorElement = document.getElementById('rejectionNotesError');
                errorElement.textContent = 'Alasan penolakan maksimal 500 karakter';
                errorElement.classList.remove('hidden');
                textarea.classList.add('border-red-300', 'bg-red-50');
                textarea.focus();
                return false;
            }
            
            return true;
        }

        // Close modal dengan ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape") {
                closeApproveModal();
                closeRejectModal();
            }
        });

        // Close modal ketika klik di luar modal content
        document.addEventListener('click', (e) => {
            if (e.target.id === 'approveModal') {
                closeApproveModal();
            }
            if (e.target.id === 'rejectModal') {
                closeRejectModal();
            }
        });
    </script>
</x-app-layout>