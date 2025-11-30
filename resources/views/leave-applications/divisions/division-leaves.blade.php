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
                        Pengajuan Cuti Divisi {{ Auth::user()->division->name ?? '' }}
                    </h2>
                    <p class="text-blue-100 text-lg opacity-90 mt-2">Kelola semua pengajuan cuti anggota divisi Anda</p>
                </div>
                
                <div class="flex gap-3 mt-4 md:mt-0">
                    <a href="{{ route('leave-verifications.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-white/20 text-sm font-medium rounded-md text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Verifikasi Cuti
                    </a>
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-900 bg-white hover:bg-blue-50 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-12 relative z-10">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border-b-4 border-green-500 p-6 mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ $totalApplications ?? 0 }}</div>
                <div class="text-sm font-semibold text-gray-600 mt-1">Total Pengajuan</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border-b-4 border-orange-500 p-6 mb-6">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-yellow-700">{{ $pendingCount ?? 0 }}</div>
                <div class="text-sm font-semibold text-yellow-600 mt-1">Menunggu</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border-b-4 border-blue-500 p-6 mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-blue-700">{{ $approvedCount ?? 0 }}</div>
                <div class="text-sm font-semibold text-blue-600 mt-1">Disetujui</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border-b-4 border-red-500 p-6 mb-6">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-red-700">{{ $rejectedCount ?? 0 }}</div>
                <div class="text-sm font-semibold text-red-600 mt-1">Ditolak</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Filter Data Pengajuan</h3>
                    <p class="text-gray-600 mt-1">Saring data pengajuan cuti anggota divisi berdasarkan kriteria tertentu</p>
                </div>
            </div>

            <form method="GET" action="{{ route('division.leaves') }}" class="space-y-4 pt-4 border-t border-gray-100">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" 
                                class="w-full border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 py-2.5 px-3 bg-white">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved_by_leader" {{ request('status') == 'approved_by_leader' ? 'selected' : '' }}>Disetujui Saya</option>
                            <option value="approved_by_hrd" {{ request('status') == 'approved_by_hrd' ? 'selected' : '' }}>Disetujui HRD</option>
                            <option value="rejected_by_leader" {{ request('status') == 'rejected_by_leader' ? 'selected' : '' }}>Ditolak Saya</option>
                            <option value="rejected_by_hrd" {{ request('status') == 'rejected_by_hrd' ? 'selected' : '' }}>Ditolak HRD</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Cuti</label>
                        <select name="leave_type" 
                                class="w-full border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 py-2.5 px-3 bg-white">
                            <option value="">Semua Jenis</option>
                            <option value="tahunan" {{ request('leave_type') == 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                            <option value="sakit" {{ request('leave_type') == 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                            <option value="melahirkan" {{ request('leave_type') == 'melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                            <option value="penting" {{ request('leave_type') == 'penting' ? 'selected' : '' }}>Cuti Penting</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <select name="year" 
                                class="w-full border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 py-2.5 px-3 bg-white">
                            <option value="">Semua Tahun</option>
                            @php
                                $currentYear = date('Y');
                                $startYear = 2020;
                            @endphp
                            @for($year = $currentYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                        <select name="month" 
                                class="w-full border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 py-2.5 px-3 bg-white">
                            <option value="">Semua Bulan</option>
                            @php
                                $months = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Anggota</label>
                        <input type="text" name="employee" 
                               placeholder="Masukkan nama anggota..."
                               value="{{ request('employee') }}"
                               class="w-full border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 py-2.5 px-3 bg-white">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                            <input type="date" name="date_from" 
                                   value="{{ request('date_from') }}"
                                   class="w-full border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 py-2.5 px-3 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                            <input type="date" name="date_to" 
                                   value="{{ request('date_to') }}"
                                   class="w-full border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 py-2.5 px-3 bg-white">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 justify-end pt-2">
                    <button type="submit" 
                            class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('division.leaves') }}" 
                       class="inline-flex items-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Daftar Pengajuan Cuti Anggota Divisi</h3>
                <p class="text-gray-600 text-sm mt-1">Kelola dan pantau semua pengajuan cuti anggota divisi {{ Auth::user()->division->name ?? '' }}</p>
            </div>
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-green-600">
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Anggota Divisi
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Jenis & Periode
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Durasi
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Timeline
                            </th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($leaveApplications as $application)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($application->applicant->profile_photo_path ?? false)
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" 
                                                     src="{{ asset('storage/' . $application->applicant->profile_photo_path) }}" 
                                                     alt="{{ $application->applicant->name }}">
                                            </div>
                                        @else
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center border border-green-200">
                                                <span class="text-green-600 text-sm font-bold">
                                                    {{ substr($application->applicant->name ?? 'N/A', 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $application->applicant->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $application->applicant->position ?? 'Karyawan' }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                Bergabung: {{ $application->applicant->created_at->format('M Y') ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full 
                                            {{ $application->leave_type == 'tahunan' ? 'bg-blue-100 border border-blue-200' : 
                                               ($application->leave_type == 'sakit' ? 'bg-green-100 border border-green-200' : 
                                               ($application->leave_type == 'melahirkan' ? 'bg-pink-100 border border-pink-200' : 
                                               'bg-purple-100 border border-purple-200')) }} 
                                            flex items-center justify-center">
                                            @if($application->leave_type == 'tahunan')
                                                <span class="text-blue-600 text-sm">🏖️</span>
                                            @elseif($application->leave_type == 'sakit')
                                                <span class="text-green-600 text-sm">🏥</span>
                                            @elseif($application->leave_type == 'melahirkan')
                                                <span class="text-pink-600 text-sm">👶</span>
                                            @else
                                                <span class="text-purple-600 text-sm">📅</span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900 capitalize">
                                                {{ $application->leave_type ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($application->start_date)->format('d M Y') }} - 
                                                {{ \Carbon\Carbon::parse($application->end_date)->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                Diajukan: {{ $application->created_at->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $application->total_days ?? 0 }} hari</div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($application->start_date)->format('d M') }} - 
                                        {{ \Carbon\Carbon::parse($application->end_date)->format('d M') }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        @if($application->end_date->isPast())
                                            <span class="text-green-600">✅ Selesai</span>
                                        @elseif($application->start_date->isFuture())
                                            <span class="text-blue-600">⏳ Akan datang</span>
                                        @else
                                            <span class="text-orange-600">📅 Sedang berlangsung</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['color' => 'bg-yellow-100 text-yellow-800 border border-yellow-200', 'icon' => '⏳', 'text' => 'Menunggu'],
                                            'approved_by_leader' => ['color' => 'bg-blue-100 text-blue-800 border border-blue-200', 'icon' => '✅', 'text' => 'Disetujui Saya'],
                                            'approved_by_hrd' => ['color' => 'bg-green-100 text-green-800 border border-green-200', 'icon' => '🎉', 'text' => 'Disetujui HRD'],
                                            'rejected_by_leader' => ['color' => 'bg-red-100 text-red-800 border border-red-200', 'icon' => '❌', 'text' => 'Ditolak Saya'],
                                            'rejected_by_hrd' => ['color' => 'bg-red-100 text-red-800 border border-red-200', 'icon' => '❌', 'text' => 'Ditolak HRD'],
                                            'cancelled' => ['color' => 'bg-gray-100 text-gray-800 border border-gray-200', 'icon' => '🚫', 'text' => 'Dibatalkan'],
                                        ];
                                        
                                        $config = $statusConfig[$application->status] ?? ['color' => 'bg-gray-100 text-gray-800 border border-gray-200', 'icon' => '❓', 'text' => $application->status ?? 'Unknown'];
                                    @endphp
                                    
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $config['color'] }}">
                                        {{ $config['icon'] }}
                                        <span class="ml-1">{{ $config['text'] }}</span>
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 space-y-1">
                                        @if($application->leader_approval_at)
                                            <div class="text-xs text-green-600">
                                                ✅ Anda: {{ \Carbon\Carbon::parse($application->leader_approval_at)->format('d M Y') }}
                                            </div>
                                        @endif
                                        @if($application->hrd_approval_at)
                                            <div class="text-xs text-blue-600">
                                                ✅ HRD: {{ \Carbon\Carbon::parse($application->hrd_approval_at)->format('d M Y') }}
                                            </div>
                                        @endif
                                        @if($application->status == 'pending')
                                            <div class="text-xs text-yellow-600">
                                                ⏳ Menunggu persetujuan Anda
                                            </div>
                                        @endif
                                        @if($application->status == 'rejected_by_leader')
                                            <div class="text-xs text-red-600">
                                                ❌ Ditolak oleh Anda
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('leave-applications.show', $application) }}" 
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Detail
                                        </a>

                                        @if($application->status === 'approved_by_hrd' && $application->hrd_approval_at)
                                            <a href="{{ route('leave-applications.download-letter', $application) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200"
                                               title="Download Surat Cuti">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                PDF
                                            </a>
                                        @endif

                                        @if($application->status === 'pending')
                                            <a href="{{ route('leave-verifications.index') }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200"
                                               title="Verifikasi Pengajuan">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Verifikasi
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-center">
                                        <div class="max-w-md mx-auto">
                                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Data</h3>
                                            <p class="text-gray-500 text-sm">Tidak ada pengajuan cuti yang sesuai dengan filter yang dipilih.</p>
                                            @if(request()->anyFilled(['status', 'leave_type', 'year', 'month', 'employee', 'date_from', 'date_to']))
                                                <a href="{{ route('division.leaves') }}" 
                                                   class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition duration-200">
                                                    Tampilkan Semua Data
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($leaveApplications->hasPages())
            <div class="mt-6 bg-white rounded-xl border border-gray-200 p-4">
                {{ $leaveApplications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>