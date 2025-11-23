<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Verifikasi Cuti: ') }} {{ $application->applicant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Breadcrumb --}}
                    <div class="mb-6 flex items-center text-sm text-gray-500">
                        <a href="{{ route('leave-verifications.index') }}" class="hover:text-gray-700">Verifikasi Cuti</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-900 font-medium">Detail Pengajuan</span>
                    </div>

                    {{-- Pesan Sukses/Error --}}
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
                            <strong class="font-medium">Whoops! Ada yang salah:</strong>
                            <ul class="mt-2 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Informasi Pengajuan --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        {{-- Kolom 1: Data Pemohon --}}
                        <div class="space-y-6">
                            {{-- Card: Informasi Pemohon --}}
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">👤 Informasi Pemohon</h3>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Nama Lengkap:</span>
                                        <p class="text-sm text-gray-900">{{ $application->applicant->name }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Divisi:</span>
                                        <p class="text-sm text-gray-900">{{ $application->applicant->division->name ?? 'Tidak ada divisi' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Email:</span>
                                        <p class="text-sm text-gray-900">{{ $application->applicant->email }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Tanggal Bergabung:</span>
                                        <p class="text-sm text-gray-900">{{ $application->applicant->join_date ? $application->applicant->join_date->format('d M Y') : '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Card: Detail Cuti --}}
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">📅 Detail Cuti</h3>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Jenis Cuti:</span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                            {{ $application->leave_type == 'tahunan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }} capitalize">
                                            {{ $application->leave_type }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Periode:</span>
                                        <p class="text-sm text-gray-900">
                                            {{ $application->start_date->format('d M Y') }} - {{ $application->end_date->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Total Hari Kerja:</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $application->total_days }} hari</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Diajukan pada:</span>
                                        <p class="text-sm text-gray-900">{{ $application->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom 2: Informasi Tambahan --}}
                        <div class="space-y-6">
                            {{-- Card: Alasan & Kontak --}}
                            <div class="bg-white p-4 rounded-lg border">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">📋 Alasan & Kontak</h3>
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Alasan Cuti:</span>
                                        <p class="text-sm text-gray-900 mt-1 bg-gray-50 p-3 rounded">{{ $application->reason }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Alamat Selama Cuti:</span>
                                        <p class="text-sm text-gray-900 mt-1">{{ $application->address_during_leave }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Kontak Darurat:</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $application->emergency_contact }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Card: Lampiran & Status --}}
                            <div class="bg-white p-4 rounded-lg border">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">📎 Lampiran & Status</h3>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Lampiran Surat Dokter:</span>
                                        <div class="mt-1">
                                            @if ($application->attachment_path)
                                                <a href="{{ asset('storage/' . $application->attachment_path) }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded text-sm font-medium transition duration-150 ease-in-out">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Lihat Surat Dokter
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-500">Tidak ada lampiran</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Status Saat Ini:</span>
                                        @php
                                            $statusConfig = [
                                                'pending' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => '⏳', 'text' => 'Menunggu Persetujuan'],
                                                'approved_by_leader' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => '✅', 'text' => 'Disetujui Atasan'],
                                                'approved_by_hrd' => ['color' => 'bg-green-100 text-green-800', 'icon' => '🎉', 'text' => 'Disetujui HRD'],
                                                'rejected_by_leader' => ['color' => 'bg-red-100 text-red-800', 'icon' => '❌', 'text' => 'Ditolak Atasan'],
                                                'rejected_by_hrd' => ['color' => 'bg-red-100 text-red-800', 'icon' => '❌', 'text' => 'Ditolak HRD'],
                                            ];
                                            
                                            $config = $statusConfig[$application->status] ?? ['color' => 'bg-gray-100 text-gray-800', 'icon' => '❓', 'text' => $application->status];
                                        @endphp
                                        <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['color'] }}">
                                            {{ $config['icon'] }}
                                            <span class="ml-1">{{ $config['text'] }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Approval --}}
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">⏱️ Timeline Persetujuan</h3>
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Diajukan oleh Karyawan</p>
                                        <p class="text-sm text-gray-500">{{ $application->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>

                                @if($application->leader_approval_at)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Disetujui oleh Ketua Divisi</p>
                                            <p class="text-sm text-gray-500">{{ $application->leader_approval_at->format('d M Y H:i') }}</p>
                                            @if($application->leaderApprover)
                                                <p class="text-xs text-gray-400">Oleh: {{ $application->leaderApprover->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($application->hrd_approval_at)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Disetujui oleh HRD (Final)</p>
                                            <p class="text-sm text-gray-500">{{ $application->hrd_approval_at->format('d M Y H:i') }}</p>
                                            @if($application->hrdApprover)
                                                <p class="text-xs text-gray-400">Oleh: {{ $application->hrdApprover->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($application->leader_rejection_notes)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Ditolak oleh Ketua Divisi</p>
                                            <p class="text-sm text-gray-500">{{ $application->leader_approval_at->format('d M Y H:i') }}</p>
                                            <p class="text-sm text-red-600 mt-1">Alasan: {{ $application->leader_rejection_notes }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions Section --}}
                    @if($application->status == 'pending' || ($application->status == 'approved_by_leader' && auth()->user()->role == 'hrd'))
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            @if(auth()->user()->role == 'ketua_divisi')
                                Verifikasi Pengajuan Cuti
                            @elseif(auth()->user()->role == 'hrd')
                                Persetujuan Final Cuti
                            @endif
                        </h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- Approve Form --}}
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <h4 class="text-lg font-medium text-green-800 mb-3">✅ Setujui Pengajuan</h4>
                                <p class="text-sm text-green-700 mb-4">
                                    @if(auth()->user()->role == 'ketua_divisi')
                                        Setujui pengajuan cuti untuk proses selanjutnya ke HRD.
                                    @else
                                        Setujui pengajuan cuti sebagai persetujuan final.
                                    @endif
                                </p>
                                <form action="{{ route('leave-verifications.approve', $application->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="approval_notes" class="block text-sm font-medium text-green-700 mb-2">
                                            Catatan (Opsional)
                                        </label>
                                        <textarea id="approval_notes" 
                                                  name="approval_notes" 
                                                  rows="2"
                                                  class="block w-full border-green-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm"
                                                  placeholder="Tambahkan catatan persetujuan..."></textarea>
                                    </div>
                                    <button type="submit" 
                                            class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium"
                                            onclick="return confirm('Setujui pengajuan cuti dari {{ $application->applicant->name }}?')">
                                        {{ auth()->user()->role == 'ketua_divisi' ? 'Setujui & Lanjut ke HRD' : 'Setujui Final' }}
                                    </button>
                                </form>
                            </div>

                            {{-- Reject Form --}}
                            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                <h4 class="text-lg font-medium text-red-800 mb-3">❌ Tolak Pengajuan</h4>
                                <p class="text-sm text-red-700 mb-4">
                                    Tolak pengajuan cuti dengan memberikan alasan yang jelas.
                                </p>
                                <form action="{{ route('leave-verifications.reject', $application->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rejection_notes" class="block text-sm font-medium text-red-700 mb-2">
                                            Alasan Penolakan *
                                        </label>
                                        <textarea id="rejection_notes" 
                                                  name="rejection_notes" 
                                                  rows="2"
                                                  class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                                  placeholder="Jelaskan alasan penolakan..."
                                                  required></textarea>
                                        <p class="text-xs text-red-600 mt-1">Wajib diisi untuk penolakan</p>
                                    </div>
                                    <button type="submit" 
                                            class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium"
                                            onclick="return confirm('Tolak pengajuan cuti dari {{ $application->applicant->name }}?')">
                                        Tolak Pengajuan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @elseif(in_array($application->status, ['approved_by_hrd', 'rejected_by_leader', 'rejected_by_hrd']))
                    <div class="border-t pt-6">
                        <div class="bg-gray-100 p-4 rounded-lg text-center">
                            <p class="text-gray-600">
                                @if($application->status == 'approved_by_hrd')
                                    ✅ Pengajuan cuti telah disetujui secara final.
                                @else
                                    ❌ Pengajuan cuti telah ditolak.
                                @endif
                            </p>
                            <a href="{{ route('leave-verifications.index') }}" 
                               class="inline-flex items-center mt-2 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                ← Kembali ke Daftar Verifikasi
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>