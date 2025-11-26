@php
    $isSick = $application->leave_type == 'sakit';
    $typeColors = [
        'tahunan' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
        'sakit' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200'],
    ];
    $colors = $typeColors[$application->leave_type] ?? $typeColors['tahunan'];
    
    $hasLeaderApproval = $application->leader_approver && $application->leader_approval_at;
    $isHrdView = Auth::user()->role == 'hrd';
    $isLeaderView = Auth::user()->role == 'ketua_divisi';
@endphp

{{-- CARD --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 overflow-hidden">
    {{-- Checkbox for Bulk Selection --}}
    <div class="absolute top-4 right-7 z-10">
        <input type="checkbox" 
            name="leave_ids[]" 
            value="{{ $application->id }}" 
            class="leave-checkbox h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all duration-200 hover:scale-110"
            onchange="updateBulkActions()">
    </div>
                                            
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
        @if($application->leader_approver_id && $application->leader_approval_at)
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
                        <p class="text-xs text-green-700 font-semibold">
                            {{ $application->leaderApprover ? $application->leaderApprover->name : 'Unknown User' }}
                        </p>
                        @if($application->leaderApprover && $application->leaderApprover->division)
                            <p class="text-xs text-green-600">{{ $application->leaderApprover->division->name }}</p>
                        @else
                            <p class="text-xs text-green-600">Ketua Divisi</p>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium text-green-800">{{ \Carbon\Carbon::parse($application->leader_approval_at)->format('d M Y') }}</p>
                    <p class="text-xs text-green-600">{{ \Carbon\Carbon::parse($application->leader_approval_at)->format('H:i') }}</p>
                </div>
            </div>
            
            {{-- TAMPILKAN CATATAN ATASAN --}}
            @if(!empty($application->leader_approval_note) && $application->leader_approval_note != 'Disetujui tanpa catatan')
            <div class="mt-2 p-2 bg-white rounded border border-green-100">
                <p class="text-xs text-green-700 italic">"{{ $application->leader_approval_note }}"</p>
            </div>
            @else
            <div class="mt-2 p-2 bg-green-100 rounded border border-green-200">
                <p class="text-xs text-green-700 italic">Disetujui tanpa catatan</p>
            </div>
            @endif
        </div>
        @endif

        {{-- INFORMASI TAMBAHAN UNTUK HRD --}}
        @if(Auth::user()->role == 'hrd')
        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
            <h4 class="text-sm font-semibold text-blue-800 mb-2">Informasi Persetujuan Final</h4>
            
            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <p class="text-blue-700 font-medium">Jenis Pengajuan:</p>
                    <p class="text-blue-600">
                        @if($application->applicant->role == 'ketua_divisi')
                            Langsung ke HRD
                        @else
                            Via Atasan Divisi
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-blue-700 font-medium">Status Saat Ini:</p>
                    <p class="text-blue-600">
                        @if($application->status == 'approved_by_leader')
                            Menunggu Persetujuan Final
                        @else
                            Pengajuan Langsung
                        @endif
                    </p>
                </div>
            </div>
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