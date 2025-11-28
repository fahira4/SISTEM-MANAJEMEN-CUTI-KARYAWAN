@php
    $isSick = $application->leave_type == 'sakit';
    $typeColors = [
        'tahunan' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'sakit' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
    ];
    $colors = $typeColors[$application->leave_type] ?? $typeColors['tahunan'];
    
    $hasAttachment = !empty($application->attachment_path);
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300 verification-card relative"
     data-application-id="{{ $application->id }}">
    
    {{-- Checkbox for multiple selection - POSISI KIRI DENGAN MARGIN --}}
<div class="absolute top-4 left-0 ml-4 z-10">
    <input type="checkbox" 
           class="application-checkbox w-5 h-5 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 ml-2"
           value="{{ $application->id }}"
           data-user-name="{{ $application->applicant->name ?? 'N/A' }}">
</div>

    {{-- Card Content --}}
    <div class="p-6 border-b border-gray-100">
        <div class="flex justify-between items-start mb-4 pl-10">
            <div class="flex items-center gap-3">
                {{-- Profile Photo --}}
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">
                    {{ substr($application->applicant->name ?? 'N', 0, 1) }}
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">{{ $application->applicant->name ?? 'Nama tidak tersedia' }}</h3>
                    <p class="text-sm text-gray-500">{{ $application->applicant->email ?? 'Email tidak tersedia' }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        Divisi: {{ $application->applicant->division->name ?? 'Belum ada divisi' }}
                    </p>
                </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colors['bg'] }} {{ $colors['text'] }}">
                {{ ucfirst($application->leave_type) }}
            </span>
        </div>
        
        {{-- APPROVAL/REJECTION INFORMATION --}}
        <div class="mt-4 space-y-3">
            {{-- Untuk HRD: Tampilkan info approval atasan --}}
            @if(Auth::user()->role == 'hrd')
                {{-- Jika sudah disetujui atasan --}}
                @if($application->status == 'approved_by_leader' && $application->leader_approval_at)
                <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-sm font-medium text-green-800">Disetujui Atasan</span>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs text-green-700">
                            <strong>Oleh:</strong> {{ $application->leaderApprover->name ?? 'Atasan Divisi' }}
                        </p>
                        <p class="text-xs text-green-600">
                            <strong>Pada:</strong> {{ $application->leader_approval_at->format('d M Y H:i') }}
                        </p>
                        {{-- TAMPILKAN "Tidak ada catatan" JIKA KOSONG --}}
                        @if($application->leader_approval_note)
                        <p class="text-xs text-green-600">
                            <strong>Catatan:</strong> {{ $application->leader_approval_note }}
                        </p>
                        @else
                        <p class="text-xs text-green-600 italic">
                            <strong>Catatan:</strong> Tidak ada catatan
                        </p>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Jika masih menunggu persetujuan atasan --}}
                @if($application->status == 'pending')
                <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium text-yellow-800">Menunggu Persetujuan Atasan</span>
                    </div>
                    <p class="text-xs text-yellow-600 mt-1">
                        Pengajuan ini masih menunggu persetujuan dari ketua divisi sebelum dapat diproses oleh HRD.
                    </p>
                </div>
                @endif
            @endif

            {{-- Untuk Ketua Divisi: Status sederhana --}}
            @if(Auth::user()->role == 'ketua_divisi' && $application->status == 'pending')
                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium text-blue-800">Menunggu Verifikasi</span>
                    </div>
                    <p class="text-xs text-blue-600 mt-1">
                        Pengajuan cuti dari bawahan menunggu persetujuan Anda.
                    </p>
                </div>
            @endif
        </div>

        {{-- LEAVE INFORMATION --}}
        <div class="space-y-3 mt-4">
            {{-- Periode Cuti --}}
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="text-center">
                    <p class="text-xs text-gray-500 font-medium">Mulai</p>
                    <p class="text-sm font-bold text-gray-900">{{ $application->start_date->format('d M Y') }}</p>
                </div>
                <div class="text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500 font-medium">Selesai</p>
                    <p class="text-sm font-bold text-gray-900">{{ $application->end_date->format('d M Y') }}</p>
                </div>
                <div class="text-center border-l border-gray-200 pl-3 ml-3">
                    <p class="text-xs text-gray-500 font-medium">Total</p>
                    <p class="text-lg font-bold text-blue-600">{{ $application->total_days }} Hari</p>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100">
                <p class="text-sm text-gray-600"><strong>Alasan Cuti:</strong></p>
                <p class="text-sm text-gray-700 mt-1">{{ $application->reason }}</p>
            </div>

            @if($application->address_during_leave)
            <div class="pt-2 border-t border-gray-100">
                <p class="text-sm text-gray-600"><strong>Alamat selama cuti:</strong></p>
                <p class="text-sm text-gray-700 mt-1">{{ $application->address_during_leave }}</p>
            </div>
            @endif

            {{-- DOKUMEN LAMPIRAN --}}
            @if($hasAttachment)
            <div class="pt-3 border-t border-gray-100">
                <p class="text-sm text-gray-600 font-medium mb-2">Dokumen Lampiran:</p>
                <a href="{{ Storage::url($application->attachment_path) }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm bg-blue-50 px-3 py-2 rounded-lg border border-blue-100 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    @if($isSick)
                        Lihat Surat Keterangan Dokter
                    @else
                        Lihat Dokumen Pendukung
                    @endif
                </a>
            </div>
            @endif

            <div class="text-xs text-gray-400 pt-2 border-t border-gray-100">
                <strong>Diajukan:</strong> {{ $application->created_at->format('d M Y H:i') }}
            </div>
        </div>
    </div>

    {{-- Card Footer with Action Buttons --}}
    <div class="p-4 bg-gray-50 rounded-b-xl">
        <div class="flex flex-col gap-3">
            {{-- ACTION BUTTONS --}}
            <div class="flex gap-2">
                @if(Auth::user()->role == 'ketua_divisi' && $application->status == 'pending')
                    <button type="button" 
                            class="approve-btn flex-1 bg-green-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-green-700 transition duration-200 flex items-center justify-center gap-2"
                            data-application-id="{{ $application->id }}"
                            data-user-name="{{ $application->applicant->name ?? 'N/A' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Setujui
                    </button>
                    
                    <button type="button" 
                            class="reject-btn flex-1 bg-red-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-red-700 transition duration-200 flex items-center justify-center gap-2"
                            data-application-id="{{ $application->id }}"
                            data-user-name="{{ $application->applicant->name ?? 'N/A' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tolak
                    </button>
                
                @elseif(Auth::user()->role == 'hrd' && $application->status == 'approved_by_leader')
                    <button type="button" 
                            class="approve-btn flex-1 bg-green-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-green-700 transition duration-200 flex items-center justify-center gap-2"
                            data-application-id="{{ $application->id }}"
                            data-user-name="{{ $application->applicant->name ?? 'N/A' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Approve
                    </button>
                    
                    <button type="button" 
                            class="reject-btn flex-1 bg-red-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-red-700 transition duration-200 flex items-center justify-center gap-2"
                            data-application-id="{{ $application->id }}"
                            data-user-name="{{ $application->applicant->name ?? 'N/A' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reject
                    </button>
                
                @elseif(Auth::user()->role == 'hrd' && $application->status == 'pending')
                    <div class="text-center py-2 w-full">
                        <p class="text-sm text-gray-500 italic">
                            Menunggu persetujuan atasan terlebih dahulu
                        </p>
                    </div>
                @endif
            </div>

            {{-- VIEW DETAILS BUTTON --}}
            <a href="{{ route('leave-applications.show', $application) }}" 
               class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-700 transition duration-200 flex items-center justify-center gap-2 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Detail Lengkap
            </a>
        </div>
    </div>
</div>