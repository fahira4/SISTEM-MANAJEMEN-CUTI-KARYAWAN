<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengajuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Header dengan Tombol Kembali -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Detail Pengajuan Cuti</h3>
                            <p class="text-sm text-gray-600 mt-1">ID: #{{ $leaveApplication->id }}</p>
                        </div>
                        <a href="{{ route('leave-applications.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            &larr; Kembali ke Riwayat
                        </a>
                    </div>

                    <!-- Alert Status -->
                    @php
                        $statusConfig = [
                            'pending' => ['color' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => '⏳'],
                            'approved_by_leader' => ['color' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => '✅'],
                            'approved' => ['color' => 'bg-green-100 text-green-800 border-green-200', 'icon' => '🎉'],
                            'rejected_by_leader' => ['color' => 'bg-red-100 text-red-800 border-red-200', 'icon' => '❌'],
                            'rejected_by_hrd' => ['color' => 'bg-red-100 text-red-800 border-red-200', 'icon' => '❌'],
                            'cancelled' => ['color' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => '🚫'],
                        ];
                        $config = $statusConfig[$leaveApplication->status] ?? ['color' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => '❓'];
                        $statusText = str_replace(['_', 'by'], [' ', 'oleh'], $leaveApplication->status);
                    @endphp

                    <div class="mb-6 p-4 border rounded-lg {{ $config['color'] }}">
                        <div class="flex items-center">
                            <span class="text-xl mr-3">{{ $config['icon'] }}</span>
                            <div>
                                <h4 class="font-semibold">Status: {{ ucwords($statusText) }}</h4>
                                <p class="text-sm mt-1">Diajukan pada: {{ $leaveApplication->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Layout untuk Informasi Cuti -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        
                        <!-- Informasi Pemohon -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Pemohon</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium">{{ $leaveApplication->applicant->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jabatan:</span>
                                    <span class="font-medium capitalize">{{ $leaveApplication->applicant->role }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Divisi:</span>
                                    <span class="font-medium">{{ $leaveApplication->applicant->division->name ?? 'Tidak ada divisi' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{ $leaveApplication->applicant->email }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Cuti -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-700 mb-3 border-b pb-2">Detail Cuti</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jenis Cuti:</span>
                                    <span class="font-medium capitalize">{{ $leaveApplication->leave_type }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Periode:</span>
                                    <span class="font-medium">{{ $leaveApplication->start_date->format('d M Y') }} - {{ $leaveApplication->end_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Lama Cuti:</span>
                                    <span class="font-medium">{{ $leaveApplication->total_days }} hari kerja</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Alamat Selama Cuti:</span>
                                    <span class="font-medium text-right">{{ $leaveApplication->address_during_leave }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kontak Darurat:</span>
                                    <span class="font-medium">{{ $leaveApplication->emergency_contact }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alasan Cuti -->
                    <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Alasan Cuti</h4>
                        <p class="text-gray-800">{{ $leaveApplication->reason }}</p>
                    </div>

                    <!-- Timeline Approval -->
                    <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-4">Timeline Persetujuan</h4>
                        <div class="space-y-4">
                            
                            <!-- Step 1: Pengajuan -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    1
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">Pengajuan Diajukan</p>
                                    <p class="text-sm text-gray-600">Oleh: {{ $leaveApplication->applicant->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $leaveApplication->created_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>

                            <!-- Step 2: Persetujuan Ketua Divisi -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 
                                    @if($leaveApplication->leader_approval_at) bg-green-500
                                    @elseif(in_array($leaveApplication->status, ['rejected_by_leader', 'rejected_by_hrd'])) bg-red-500
                                    @else bg-gray-300 @endif
                                    rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    2
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">
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
                                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded">
                                            <p class="text-sm font-medium text-red-800">Catatan Penolakan:</p>
                                            <p class="text-sm text-red-700 mt-1">{{ $leaveApplication->leader_rejection_notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Step 3: Persetujuan HRD -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 
                                    @if($leaveApplication->hrd_approval_at) bg-green-500
                                    @elseif($leaveApplication->status == 'rejected_by_hrd') bg-red-500
                                    @elseif(in_array($leaveApplication->status, ['approved_by_leader', 'pending'])) bg-yellow-300
                                    @else bg-gray-300 @endif
                                    rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    3
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">
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
                                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded">
                                            <p class="text-sm font-medium text-red-800">Catatan Penolakan:</p>
                                            <p class="text-sm text-red-700 mt-1">{{ $leaveApplication->hrd_rejection_notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Surat Dokter (Jika Cuti Sakit) -->
                    @if($leaveApplication->leave_type == 'sakit' && $leaveApplication->attachment_path)
                    <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3">Surat Keterangan Dokter</h4>
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div>
                                <p class="font-medium text-blue-800">File terlampir</p>
                                <p class="text-sm text-blue-600">Format: {{ pathinfo($leaveApplication->attachment_path, PATHINFO_EXTENSION) }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $leaveApplication->attachment_path) }}" 
                               target="_blank"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                📄 Lihat Surat Dokter
                            </a>
                        </div>
                    </div>
                    @endif

<!-- Surat Izin Cuti (Hanya tampil jika sudah disetujui HRD) -->
@if($leaveApplication->status === 'approved_by_hrd' && $leaveApplication->hrd_approval_at)
<div class="mt-6 pt-6 border-t border-gray-200">
    <h4 class="text-lg font-medium text-gray-900 mb-4">Surat Izin Cuti</h4>
    <div class="flex space-x-4">
        <a href="{{ route('leave-applications.view-letter', $leaveApplication) }}" 
           target="_blank"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
            Lihat Surat Izin
        </a>
        
        <a href="{{ route('leave-applications.download-letter', $leaveApplication) }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out">
            Download PDF
        </a>
    </div>
    <p class="text-xs text-gray-500 mt-2">
        Surat izin cuti resmi yang sudah disetujui HRD
    </p>
</div>
@else
<div class="mt-6 pt-6 border-t border-gray-200">
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <div>
                <p class="text-sm text-yellow-700 font-medium">
                    Surat izin cuti belum tersedia
                </p>
                <p class="text-sm text-yellow-600 mt-1">
                    Surat izin cuti resmi akan tersedia setelah disetujui oleh HRD.
                </p>
            </div>
        </div>
    </div>
</div>
@endif

                    <!-- Tombol Aksi -->
                    <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                        <div>
                            <!-- Tombol Batalkan (Hanya untuk pemohon & status pending) -->
                            @if(Auth::id() == $leaveApplication->user_id && $leaveApplication->status == 'pending')
                                <button onclick="openCancelModal()"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Batalkan Pengajuan
                                </button>
                            @endif
                        </div>

                        <!-- Tombol untuk HRD/Ketua Divisi -->
                        @if(in_array(Auth::user()->role, ['hrd', 'ketua_divisi']) && 
                            (($leaveApplication->status == 'pending' && Auth::user()->role == 'ketua_divisi') || 
                             ($leaveApplication->status == 'approved_by_leader' && Auth::user()->role == 'hrd')))
                            <a href="{{ route('leave-verifications.index') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Proses Verifikasi
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pembatalan -->
    @if(Auth::id() == $leaveApplication->user_id && $leaveApplication->status == 'pending')
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900">Batalkan Pengajuan Cuti</h3>
                <form id="cancelForm" action="{{ route('leave-applications.cancel', $leaveApplication) }}" method="POST">
                    @csrf
                    <div class="mt-4">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">Alasan Pembatalan *</label>
                        <textarea id="cancellation_reason" name="cancellation_reason" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                                  required placeholder="Masukkan alasan pembatalan..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" onclick="closeCancelModal()"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </button>
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
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