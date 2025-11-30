<x-app-layout>
    <div class="relative bg-blue-900 pb-24 pt-8 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div class="text-white">
                    <h2 class="text-2xl font-bold tracking-tight">
                        Detail Pengajuan Cuti
                    </h2>
                    <p class="text-blue-100 text-lg opacity-90 mt-2">
                        ID: #{{ $leaveApplication->id }} - {{ $leaveApplication->applicant->name }}
                    </p>
                </div>
                
                <div class="flex gap-3 mt-4 md:mt-0">
                    @if(Auth::user()->role == 'hrd')
                        <a href="{{ route('hrd.all-leaves') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-900 bg-white hover:bg-blue-50 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Semua Pengajuan
                        </a>
                    @else
                        <a href="{{ route('leave-applications.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-900 bg-white hover:bg-blue-50 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Riwayat
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-12 relative z-10">
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Informasi Lengkap Pengajuan Cuti</h3>
                        <p class="text-gray-600 text-sm mt-1">
                            Diajukan pada: {{ $leaveApplication->created_at->format('d F Y H:i') }}
                        </p>
                    </div>
                    
                    @php
                        $statusConfig = [
                            'pending' => ['color' => 'bg-yellow-100 text-yellow-800 border border-yellow-200', 'icon' => '⏳'],
                            'approved_by_leader' => ['color' => 'bg-blue-100 text-blue-800 border border-blue-200', 'icon' => '✅'],
                            'approved_by_hrd' => ['color' => 'bg-green-100 text-green-800 border border-green-200', 'icon' => '🎉'],
                            'rejected_by_leader' => ['color' => 'bg-red-100 text-red-800 border border-red-200', 'icon' => '❌'],
                            'rejected_by_hrd' => ['color' => 'bg-red-100 text-red-800 border border-red-200', 'icon' => '❌'],
                            'cancelled' => ['color' => 'bg-gray-100 text-gray-800 border border-gray-200', 'icon' => '🚫'],
                        ];
                        $config = $statusConfig[$leaveApplication->status] ?? ['color' => 'bg-gray-100 text-gray-800 border border-gray-200', 'icon' => '❓'];
                        $statusText = str_replace(['_', 'by'], [' ', 'oleh'], $leaveApplication->status);
                    @endphp
                    
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $config['color'] }}">
                        {{ $config['icon'] }}
                        <span class="ml-1">{{ ucwords($statusText) }}</span>
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    
                    <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                        <h4 class="font-bold text-blue-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Informasi Pemohon
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-blue-700 font-medium">Nama:</span>
                                <span class="font-semibold text-blue-900">{{ $leaveApplication->applicant->name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-blue-700 font-medium">Jabatan:</span>
                                <span class="font-semibold text-blue-900 capitalize">{{ $leaveApplication->applicant->role }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-blue-700 font-medium">Divisi:</span>
                                <span class="font-semibold text-blue-900">{{ $leaveApplication->applicant->division->name ?? 'Tidak ada divisi' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-blue-700 font-medium">Email:</span>
                                <span class="font-semibold text-blue-900 text-sm">{{ $leaveApplication->applicant->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-xl p-5 border border-green-100">
                        <h4 class="font-bold text-green-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Detail Cuti
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-green-700 font-medium">Jenis Cuti:</span>
                                <span class="font-semibold text-green-900 capitalize">{{ $leaveApplication->leave_type }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-green-700 font-medium">Periode:</span>
                                <span class="font-semibold text-green-900">{{ $leaveApplication->start_date->format('d M Y') }} - {{ $leaveApplication->end_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-green-700 font-medium">Lama Cuti:</span>
                                <span class="font-semibold text-green-900">{{ $leaveApplication->total_days }} hari kerja</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-green-700 font-medium">Kontak Darurat:</span>
                                <span class="font-semibold text-green-900">{{ $leaveApplication->emergency_contact }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6 bg-orange-50 rounded-xl p-5 border border-orange-100">
                    <h4 class="font-bold text-orange-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Alasan Cuti
                    </h4>
                    <p class="text-orange-800 leading-relaxed">{{ $leaveApplication->reason }}</p>
                </div>

                <div class="mb-6 bg-purple-50 rounded-xl p-5 border border-purple-100">
                    <h4 class="font-bold text-purple-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Alamat Selama Cuti
                    </h4>
                    <p class="text-purple-800 leading-relaxed">{{ $leaveApplication->address_during_leave }}</p>
                </div>

                <div class="mb-6 bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <h4 class="font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Timeline Persetujuan
                    </h4>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                1
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900">Pengajuan Diajukan</p>
                                <p class="text-sm text-gray-600">Oleh: {{ $leaveApplication->applicant->name }}</p>
                                <p class="text-sm text-gray-500">{{ $leaveApplication->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 
                                @if($leaveApplication->leader_approval_at) bg-green-500
                                @elseif(in_array($leaveApplication->status, ['rejected_by_leader', 'rejected_by_hrd'])) bg-red-500
                                @else bg-gray-300 @endif
                                rounded-full flex items-center justify-center text-white text-sm font-bold">
                                2
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900">
                                    @if($leaveApplication->leader_approval_at)
                                        Disetujui oleh Ketua Divisi
                                    @elseif(in_array($leaveApplication->status, ['rejected_by_leader', 'rejected_by_hrd']))
                                        Ditolak oleh Ketua Divisi
                                    @else
                                        Menunggu Persetujuan Ketua Divisi
                                    @endif
                                </p>
                                @if($leaveApplication->leader_approver)
                                    <p class="text-sm text-gray-600">Oleh: {{ $leaveApplication->leader_approver->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $leaveApplication->leader_approval_at->format('d F Y H:i') }}</p>
                                @endif
                                @if($leaveApplication->leader_rejection_notes)
                                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-sm font-medium text-red-800">Catatan Penolakan:</p>
                                        <p class="text-sm text-red-700 mt-1">{{ $leaveApplication->leader_rejection_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 
                                @if($leaveApplication->hrd_approval_at) bg-green-500
                                @elseif($leaveApplication->status == 'rejected_by_hrd') bg-red-500
                                @elseif(in_array($leaveApplication->status, ['approved_by_leader', 'pending'])) bg-yellow-500
                                @else bg-gray-300 @endif
                                rounded-full flex items-center justify-center text-white text-sm font-bold">
                                3
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900">
                                    @if($leaveApplication->hrd_approval_at)
                                        Disetujui oleh HRD
                                    @elseif($leaveApplication->status == 'rejected_by_hrd')
                                        Ditolak oleh HRD
                                    @elseif($leaveApplication->status == 'approved_by_leader')
                                        Menunggu Persetujuan HRD
                                    @else
                                        Persetujuan HRD
                                    @endif
                                </p>
                                @if($leaveApplication->hrd_approver)
                                    <p class="text-sm text-gray-600">Oleh: {{ $leaveApplication->hrd_approver->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $leaveApplication->hrd_approval_at->format('d F Y H:i') }}</p>
                                @endif
                                @if($leaveApplication->hrd_rejection_notes)
                                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-sm font-medium text-red-800">Catatan Penolakan:</p>
                                        <p class="text-sm text-red-700 mt-1">{{ $leaveApplication->hrd_rejection_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($leaveApplication->leave_type == 'sakit' && $leaveApplication->attachment_path)
                <div class="mb-6 bg-blue-50 rounded-xl p-5 border border-blue-100">
                    <h4 class="font-bold text-blue-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Surat Keterangan Dokter
                    </h4>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-white rounded-lg border border-blue-200">
                        <div class="mb-3 sm:mb-0">
                            <p class="font-medium text-blue-800">File terlampir</p>
                            <p class="text-sm text-blue-600">Format: {{ pathinfo($leaveApplication->attachment_path, PATHINFO_EXTENSION) }}</p>
                        </div>
                        <a href="{{ asset('storage/' . $leaveApplication->attachment_path) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat Surat Dokter
                        </a>
                    </div>
                </div>
                @endif

                @if($leaveApplication->status === 'approved_by_hrd' && $leaveApplication->hrd_approval_at)
                <div class="mb-6 bg-green-50 rounded-xl p-5 border border-green-100">
                    <h4 class="font-bold text-green-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Surat Izin Cuti
                    </h4>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('leave-applications.view-letter', $leaveApplication) }}" 
                           target="_blank"
                           class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat Surat Izin
                        </a>
                        
                        <a href="{{ route('leave-applications.download-letter', $leaveApplication) }}" 
                           class="inline-flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                    <p class="text-sm text-green-700 mt-3">
                        Surat izin cuti resmi yang sudah disetujui HRD
                    </p>
                </div>
                @else
                <div class="mb-6 bg-yellow-50 rounded-xl p-5 border border-yellow-100">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <p class="text-yellow-800 font-medium">
                                Surat izin cuti belum tersedia
                            </p>
                            <p class="text-yellow-700 text-sm mt-1">
                                Surat izin cuti resmi akan tersedia setelah disetujui oleh HRD.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-6 border-t border-gray-200">
                    <div>
                        @if(Auth::id() == $leaveApplication->user_id && $leaveApplication->status == 'pending')
                            <button onclick="openCancelModal()"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batalkan Pengajuan
                            </button>
                        @endif
                    </div>

                    @if(in_array(Auth::user()->role, ['hrd', 'ketua_divisi']) && 
                        (($leaveApplication->status == 'pending' && Auth::user()->role == 'ketua_divisi') || 
                         ($leaveApplication->status == 'approved_by_leader' && Auth::user()->role == 'hrd')))
                        <a href="{{ route('leave-verifications.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Proses Verifikasi
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(Auth::id() == $leaveApplication->user_id && $leaveApplication->status == 'pending')
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900">Batalkan Pengajuan Cuti</h3>
                <form id="cancelForm" action="{{ route('leave-applications.cancel', $leaveApplication) }}" method="POST">
                    @csrf
                    <div class="mt-4">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">Alasan Pembatalan *</label>
                        <textarea id="cancellation_reason" name="cancellation_reason" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                  required placeholder="Masukkan alasan pembatalan..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" onclick="closeCancelModal()"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded transition duration-200">
                            Batal
                        </button>
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded transition duration-200">
                            Konfirmasi Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
        }
        
        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    </script>
    @endif

</x-app-layout>