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

                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                @if(auth()->user()->role == 'ketua_divisi')
                                    Detail Verifikasi Cuti
                                @elseif(auth()->user()->role == 'hrd')
                                    Detail Persetujuan Final Cuti
                                @else
                                    Detail Pengajuan Cuti
                                @endif
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">ID: #{{ $application->id }}</p>
                        </div>
                        <a href="{{ route('leave-verifications.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            &larr; Kembali ke List
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
                            <strong class="font-medium flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Whoops! Ada yang salah:
                            </strong>
                            <ul class="mt-2 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $statusConfig = [
                            'pending' => [
                                'color' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 
                                'icon' => '⏳',
                                'title' => 'Menunggu Persetujuan',
                                'description' => 'Pengajuan cuti menunggu verifikasi ' . (auth()->user()->role == 'ketua_divisi' ? 'Anda' : ($application->status == 'pending' ? 'Ketua Divisi' : 'HRD'))
                            ],
                            'approved_by_leader' => [
                                'color' => 'bg-blue-100 text-blue-800 border-blue-200', 
                                'icon' => '✅',
                                'title' => 'Disetujui Ketua Divisi',
                                'description' => 'Pengajuan telah disetujui atasan dan menunggu persetujuan final HRD'
                            ],
                            'approved_by_hrd' => [
                                'color' => 'bg-green-100 text-green-800 border-green-200', 
                                'icon' => '🎉',
                                'title' => 'Disetujui HRD',
                                'description' => 'Pengajuan cuti telah disetujui secara final'
                            ],
                            'rejected_by_leader' => [
                                'color' => 'bg-red-100 text-red-800 border-red-200', 
                                'icon' => '❌',
                                'title' => 'Ditolak Ketua Divisi',
                                'description' => 'Pengajuan cuti ditolak oleh atasan'
                            ],
                            'rejected_by_hrd' => [
                                'color' => 'bg-red-100 text-red-800 border-red-200', 
                                'icon' => '❌',
                                'title' => 'Ditolak HRD',
                                'description' => 'Pengajuan cuti ditolak oleh HRD'
                            ],
                        ];
                        
                        $config = $statusConfig[$application->status] ?? [
                            'color' => 'bg-gray-100 text-gray-800 border-gray-200', 
                            'icon' => '❓',
                            'title' => ucfirst($application->status),
                            'description' => 'Status pengajuan cuti'
                        ];
                    @endphp

                    <div class="mb-6 p-4 rounded-lg border {{ $config['color'] }}">
                        <div class="flex items-start">
                            <span class="text-xl mr-3 mt-1">{{ $config['icon'] }}</span>
                            <div>
                                <h4 class="font-semibold">{{ $config['title'] }}</h4>
                                <p class="text-sm mt-1">{{ $config['description'] }}</p>
                                <p class="text-xs mt-2 opacity-75">
                                    Diajukan pada: {{ $application->created_at->format('d F Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Informasi Pemohon
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-600">Nama Lengkap:</span>
                                        <span class="text-sm text-gray-900">{{ $application->applicant->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-600">Divisi:</span>
                                        <span class="text-sm text-gray-900">{{ $application->applicant->division->name ?? 'Tidak ada divisi' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-600">Email:</span>
                                        <span class="text-sm text-gray-900">{{ $application->applicant->email }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-600">Tanggal Bergabung:</span>
                                        <span class="text-sm text-gray-900">{{ $application->applicant->join_date ? $application->applicant->join_date->format('d M Y') : '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Detail Cuti
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-600">Jenis Cuti:</span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                            {{ $application->leave_type == 'tahunan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }} capitalize">
                                            {{ $application->leave_type }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-600">Periode:</span>
                                        <span class="text-sm text-gray-900">
                                            {{ $application->start_date->format('d M Y') }} - {{ $application->end_date->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-600">Total Hari Kerja:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $application->total_days }} hari</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-600">Diajukan pada:</span>
                                        <span class="text-sm text-gray-900">{{ $application->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white p-4 rounded-lg border">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Alasan & Kontak
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Alasan Cuti:</span>
                                        <p class="text-sm text-gray-900 mt-1 bg-gray-50 p-3 rounded">{{ $application->reason }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Alamat Selama Cuti:</span>
                                        <p class="text-sm text-gray-900 mt-1">{{ $application->address_during_leave }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-600">Kontak Darurat:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $application->emergency_contact }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-lg border">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    Lampiran & Status
                                </h3>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Timeline Persetujuan
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        1
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Pengajuan Diajukan</p>
                                        <p class="text-sm text-gray-600">Oleh: {{ $application->applicant->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $application->created_at->format('d F Y H:i') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 
                                        @if($application->leader_approval_at) bg-green-500
                                        @elseif(in_array($application->status, ['rejected_by_leader', 'rejected_by_hrd'])) bg-red-500
                                        @else bg-gray-300 @endif
                                        rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        2
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">
                                            @if($application->leader_approval_at)
                                                Disetujui oleh Ketua Divisi
                                            @elseif(in_array($application->status, ['rejected_by_leader', 'rejected_by_hrd']))
                                                Ditolak oleh Ketua Divisi
                                            @else
                                                Menunggu Persetujuan Ketua Divisi
                                            @endif
                                        </p>
                                        @if($application->leader_approver)
                                            <p class="text-sm text-gray-600">Oleh: {{ $application->leader_approver->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $application->leader_approval_at->format('d F Y H:i') }}</p>
                                        @endif
                                        @if($application->leader_rejection_notes)
                                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded">
                                                <p class="text-sm font-medium text-red-800">Catatan Penolakan:</p>
                                                <p class="text-sm text-red-700 mt-1">{{ $application->leader_rejection_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 
                                        @if($application->hrd_approval_at) bg-green-500
                                        @elseif($application->status == 'rejected_by_hrd') bg-red-500
                                        @elseif(in_array($application->status, ['approved_by_leader', 'pending'])) bg-yellow-300
                                        @else bg-gray-300 @endif
                                        rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        3
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">
                                            @if($application->hrd_approval_at)
                                                Disetujui oleh HRD
                                            @elseif($application->status == 'rejected_by_hrd')
                                                Ditolak oleh HRD
                                            @elseif($application->status == 'approved_by_leader')
                                                Menunggu Persetujuan HRD
                                            @else
                                                Persetujuan HRD
                                            @endif
                                        </p>
                                        @if($application->hrd_approver)
                                            <p class="text-sm text-gray-600">Oleh: {{ $application->hrd_approver->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $application->hrd_approval_at->format('d F Y H:i') }}</p>
                                        @endif
                                        @if($application->hrd_rejection_notes)
                                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded">
                                                <p class="text-sm font-medium text-red-800">Catatan Penolakan:</p>
                                                <p class="text-sm text-red-700 mt-1">{{ $application->hrd_rejection_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(($application->status == 'pending' && auth()->user()->role == 'ketua_divisi') || 
                        ($application->status == 'approved_by_leader' && auth()->user()->role == 'hrd'))
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            @if(auth()->user()->role == 'ketua_divisi')
                                Verifikasi Pengajuan Cuti
                            @elseif(auth()->user()->role == 'hrd')
                                Persetujuan Final Cuti
                            @endif
                        </h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <h4 class="text-lg font-medium text-green-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Setujui Pengajuan
                                </h4>
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

                            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                <h4 class="text-lg font-medium text-red-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Tolak Pengajuan
                                </h4>
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
                                    Pengajuan cuti telah disetujui secara final.
                                @else
                                    Pengajuan cuti telah ditolak.
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