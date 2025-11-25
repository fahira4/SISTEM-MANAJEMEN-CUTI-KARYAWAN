<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Pesan Sukses/Error --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
                    @endif
                    
                    {{-- ================================================== --}}
                    {{-- DASBOR ADMIN --}}
                    {{-- ================================================== --}}
                    @if (Auth::user()->role == 'admin')
                        <h3 class="font-semibold text-lg mb-4">Selamat Datang, {{ Auth::user()->name }} (Administrator)</h3>

                        @php
                            // Statistik User
                            $activeUsers = \App\Models\User::where('active_status', true)->count();
                            $inactiveUsers = \App\Models\User::where('active_status', false)->count();
                            $totalUsers = $activeUsers + $inactiveUsers;

                            $totalDivisions = \App\Models\Division::count();
                            
                            // Total Cuti Bulan Ini
                            $totalLeavesThisMonth = \App\Models\LeaveApplication::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

                            // Pending Approvals (Global - Menunggu HRD atau Leader)
                            // Definisi "Pending Approval" admin bisa berarti semua yang belum final
                            $pendingApprovals = \App\Models\LeaveApplication::whereIn('status', ['pending', 'approved_by_leader'])->count();

                            // Karyawan masa kerja < 1 tahun (List)
                            $newEmployeesList = \App\Models\User::where('role', 'karyawan')
                                ->where('active_status', true)
                                ->where('join_date', '>=', now()->subYear())
                                ->with('division')
                                ->orderBy('join_date', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        {{-- Grid Statistik Utama --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            <!-- User Stats -->
                            <div class="bg-blue-50 p-6 rounded-lg shadow-md border border-blue-200">
                                <h4 class="text-blue-700 uppercase text-sm font-medium tracking-wider">Karyawan</h4>
                                <div class="mt-2 flex items-end gap-2">
                                    <span class="text-3xl font-bold text-gray-900">{{ $activeUsers }}</span>
                                    <span class="text-sm text-green-600 font-medium mb-1">Aktif</span>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    Non-Aktif: <span class="font-medium text-gray-700">{{ $inactiveUsers }}</span>
                                </div>
                                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline text-sm font-semibold mt-3 inline-block">Kelola User &rarr;</a>
                            </div>

                            <!-- Pengajuan Bulan Ini -->
                            <div class="bg-indigo-50 p-6 rounded-lg shadow-md border border-indigo-200">
                                <h4 class="text-indigo-700 uppercase text-sm font-medium tracking-wider">Pengajuan Bulan Ini</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalLeavesThisMonth }}</p>
                                <span class="text-sm text-indigo-600">Total Masuk</span>
                            </div>

                            <!-- Pending Approval -->
                            <div class="bg-yellow-50 p-6 rounded-lg shadow-md border border-yellow-200">
                                <h4 class="text-yellow-700 uppercase text-sm font-medium tracking-wider">Pending Approval</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingApprovals }}</p>
                                <span class="text-sm text-yellow-600">Belum Final</span>
                            </div>

                            <!-- Total Divisi -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Total Divisi</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalDivisions }}</p>
                                <a href="{{ route('admin.divisions.index') }}" class="text-gray-600 hover:underline text-sm font-semibold mt-3 inline-block">Lihat Divisi &rarr;</a>
                            </div>
                        </div>

                        {{-- Tabel Karyawan Baru (< 1 Tahun) --}}
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-8">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h4 class="font-semibold text-gray-700">Daftar Karyawan Baru (Belum Eligible Cuti Tahunan)</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Divisi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Gabung</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masa Kerja</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($newEmployeesList as $emp)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $emp->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->division->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->join_date->format('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">{{ $emp->employment_period }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada karyawan baru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    {{-- ================================================== --}}
                    {{-- DASBOR KARYAWAN --}}
                    {{-- ================================================== --}}
                    @elseif (Auth::user()->role == 'karyawan')
                        <h3 class="font-semibold text-lg mb-4">Selamat Datang, {{ Auth::user()->name }} (Karyawan)</h3>

                        @php
                            $userId = Auth::id();
                            $sickLeaveCount = \App\Models\LeaveApplication::where('user_id', $userId)
                                ->where('leave_type', 'sakit')
                                ->count();
                            
                            $totalLeaves = \App\Models\LeaveApplication::where('user_id', $userId)->count();
                        @endphp

                        <div class="mb-6">
                            @if(auth()->user()->division_id)
                                <a href="{{ route('leave-applications.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-150 ease-in-out">
                                    + Buat Pengajuan Cuti Baru
                                </a>
                            @else
                                <button disabled class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                    🚫 Akun Belum Siap (Tanpa Divisi)
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Sisa Kuota -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Sisa Kuota Cuti Tahunan</h4>
                                @php
                                    $quota = Auth::user()->annual_leave_quota;
                                    $quotaColor = $quota > 5 ? 'text-green-600' : ($quota > 2 ? 'text-yellow-600' : 'text-red-600');
                                @endphp
                                <p class="text-3xl font-bold {{ $quotaColor }} mt-2">{{ $quota }} <span class="text-xl text-gray-600 font-medium">hari</span></p>
                            </div>

                            <!-- Total Cuti Sakit -->
                            <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-200">
                                <h4 class="text-green-700 uppercase text-sm font-medium tracking-wider">Total Cuti Sakit</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $sickLeaveCount }} <span class="text-sm text-gray-600 font-normal">kali</span></p>
                                <span class="text-xs text-green-600">Diajukan</span>
                            </div>

                            <!-- Total Pengajuan -->
                            <div class="bg-blue-50 p-6 rounded-lg shadow-md border border-blue-200">
                                <h4 class="text-blue-700 uppercase text-sm font-medium tracking-wider">Total Pengajuan</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalLeaves }}</p>
                                <a href="{{ route('leave-applications.index') }}" class="text-blue-600 hover:underline text-xs font-semibold mt-1 inline-block">Lihat Riwayat &rarr;</a>
                            </div>

                            <!-- Info Divisi -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Divisi Anda</h4>
                                <p class="text-lg font-semibold text-gray-900 mt-2 truncate" title="{{ Auth::user()->division?->name }}">{{ Auth::user()->division?->name ?? 'Belum ada' }}</p>
                                <p class="text-xs text-gray-500 mt-1">Ketua: <span class="font-medium">{{ Auth::user()->division?->leader?->name ?? '-' }}</span></p>
                            </div>
                        </div>

                    {{-- ================================================== --}}
                    {{-- DASBOR KETUA DIVISI --}}
                    {{-- ================================================== --}}
                    @elseif (Auth::user()->role == 'ketua_divisi')
                        <h3 class="font-semibold text-lg mb-4">Selamat Datang, {{ Auth::user()->name }} (Ketua Divisi)</h3>
                        
                        @php
                            $division = Auth::user()->division; // Divisi yang dipimpin (lewat relasi user->division mungkin salah jika user->division_id null, gunakan leadingDivision)
                            // Perbaikan: Ambil divisi yang dipimpin
                            $leadingDivision = Auth::user()->leadingDivision;
                            
                            if($leadingDivision) {
                                $teamMembers = $leadingDivision->members; // Semua anggota
                                $teamMemberIds = $teamMembers->pluck('id');
                            } else {
                                $teamMembers = collect();
                                $teamMemberIds = collect();
                            }

                            // 1. Total Pengajuan Masuk (Semua status)
                            $totalIncomingLeaves = \App\Models\LeaveApplication::whereIn('user_id', $teamMemberIds)->count();

                            // 2. Pending Verifikasi
                            $pendingCount = \App\Models\LeaveApplication::whereIn('user_id', $teamMemberIds)
                                ->where('status', 'pending')
                                ->count();

                            // 3. Sedang Cuti Minggu Ini
                            $startOfWeek = now()->startOfWeek();
                            $endOfWeek = now()->endOfWeek();
                            
                            $teamOnLeave = \App\Models\LeaveApplication::whereIn('user_id', $teamMemberIds)
                                ->whereIn('status', ['approved_by_leader', 'approved_by_hrd']) // Disetujui min. leader
                                ->where(function($q) use ($startOfWeek, $endOfWeek) {
                                    $q->whereBetween('start_date', [$startOfWeek, $endOfWeek])
                                      ->orWhereBetween('end_date', [$startOfWeek, $endOfWeek])
                                      ->orWhere(function($sub) use ($startOfWeek, $endOfWeek) {
                                          $sub->where('start_date', '<', $startOfWeek)
                                              ->where('end_date', '>', $endOfWeek);
                                      });
                                })
                                ->with('applicant')
                                ->get();
                        @endphp
                        
                        <div class="mb-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Divisi: <span class="font-bold text-gray-700">{{ $leadingDivision->name ?? 'Tidak ada' }}</span> | 
                                Anggota: <span class="font-bold text-gray-700">{{ $teamMembers->count() }}</span>
                            </div>
                            <a href="{{ route('leave-applications.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg">
                                + Buat Pengajuan Pribadi
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <!-- Total Masuk -->
                            <div class="bg-blue-50 p-6 rounded-lg shadow-md border border-blue-200">
                                <h4 class="text-blue-700 uppercase text-sm font-medium tracking-wider">Total Pengajuan Masuk</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalIncomingLeaves }}</p>
                                <span class="text-sm text-blue-600">Riwayat Tim</span>
                            </div>

                            <!-- Pending Verifikasi -->
                            <div class="bg-yellow-100 p-6 rounded-lg shadow-md border border-yellow-200">
                                <h4 class="text-yellow-700 uppercase text-sm font-medium tracking-wider">Perlu Verifikasi</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingCount }}</p>
                                <a href="{{ route('leave-verifications.index') }}" class="text-yellow-800 hover:underline font-semibold mt-2 inline-block">Proses Sekarang &rarr;</a>
                            </div>

                            <!-- Cuti Minggu Ini -->
                            <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-200">
                                <h4 class="text-green-700 uppercase text-sm font-medium tracking-wider">Sedang Cuti (Minggu Ini)</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $teamOnLeave->count() }}</p>
                                <span class="text-sm text-green-600">Orang</span>
                            </div>
                        </div>

                        {{-- TABEL 1: SEDANG CUTI MINGGU INI (VERSI PERCANTIK) --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm h-full">
                                <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                                    <h4 class="font-bold text-gray-700 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Sedang Cuti (Minggu Ini)
                                    </h4>
                                    <span class="text-xs font-medium bg-white border border-gray-300 text-gray-600 px-2 py-1 rounded shadow-sm">
                                        {{ $startOfWeek->format('d M') }} - {{ $endOfWeek->format('d M') }}
                                    </span>
                                </div>
                                <div class="overflow-y-auto max-h-96">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($teamOnLeave as $leave)
                                                <tr class="hover:bg-gray-50 transition duration-150">
                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            {{-- Avatar Inisial / Foto --}}
                                                            @if($leave->applicant->profile_photo_path)
                                                                <img class="flex-shrink-0 h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" 
                                                                     src="{{ asset('storage/' . $leave->applicant->profile_photo_path) }}" 
                                                                     alt="{{ $leave->applicant->name }}">
                                                            @else
                                                                <div class="flex-shrink-0 h-10 w-10 rounded-full {{ $leave->leave_type == 'sakit' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center font-bold text-sm border-2 border-white shadow-sm">
                                                                    {{ substr($leave->applicant->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                            
                                                            <div class="ml-3">
                                                                <div class="text-sm font-semibold text-gray-900">{{ $leave->applicant->name }}</div>
                                                                <div class="flex items-center mt-0.5">
                                                                    {{-- HAPUS TITIK MERAH --}}
                                                                    <span class="text-xs text-gray-500 capitalize">{{ ucfirst($leave->leave_type) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-5 py-4 whitespace-nowrap text-right">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M') }}
                                                        </div>
                                                        <div class="mt-1">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $leave->leave_type == 'sakit' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                                {{ $leave->total_days }} Hari
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="px-6 py-12 text-center">
                                                        <div class="flex flex-col items-center justify-center">
                                                            <div class="bg-green-50 rounded-full p-3 mb-3">
                                                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </div>
                                                            <p class="text-sm font-medium text-gray-900">Semua Hadir!</p>
                                                            <p class="text-xs text-gray-500 mt-1">Tidak ada anggota tim yang cuti minggu ini.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- TABEL 2: DAFTAR ANGGOTA DIVISI --}}
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                    <h4 class="font-semibold text-gray-700 text-sm">Daftar Anggota Divisi</h4>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($teamMembers as $member)
                                                <tr>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $member->name }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">{{ $member->email }}</td>
                                                    <td class="px-4 py-3 text-xs">
                                                        <span class="px-2 py-1 rounded-full {{ $member->active_status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $member->active_status ? 'Aktif' : 'Non-Aktif' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="px-4 py-3 text-center text-xs text-gray-500">Belum ada anggota.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    {{-- ================================================== --}}
                    {{-- DASBOR HRD --}}
                    {{-- ================================================== --}}
                    @elseif (Auth::user()->role == 'hrd')
                        <h3 class="font-semibold text-lg mb-4">Selamat Datang, {{ Auth::user()->name }} (HRD)</h3>

                        @php
                            // Total Pengajuan Cuti Bulan Ini (Semua Status)
                            $totalLeavesMonth = \App\Models\LeaveApplication::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

                            // Pending Final Approval
                            $pendingHrdCount = \App\Models\LeaveApplication::where('status', 'approved_by_leader')
                                ->orWhere(function($query) {
                                    $query->where('status', 'pending')
                                          ->whereHas('applicant', function($q) {
                                              $q->where('role', 'ketua_divisi');
                                          });
                                })
                                ->count();

                            // Karyawan Sedang Cuti Bulan Ini
                            $startOfMonth = now()->startOfMonth();
                            $endOfMonth = now()->endOfMonth();
                            $employeesOnLeaveMonth = \App\Models\LeaveApplication::where('status', 'approved_by_hrd')
                                ->where(function($q) use ($startOfMonth, $endOfMonth) {
                                    $q->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                                      ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth]);
                                })
                                ->with(['applicant', 'applicant.division'])
                                ->orderBy('start_date', 'asc')
                                ->limit(10)
                                ->get();

                            // Daftar Divisi
                            $divisions = \App\Models\Division::with('leader')->withCount('members')->get();
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <!-- Pengajuan Bulan Ini -->
                            <div class="bg-blue-50 p-6 rounded-lg shadow-md border border-blue-200">
                                <h4 class="text-blue-700 uppercase text-sm font-medium tracking-wider">Total Pengajuan (Bulan Ini)</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalLeavesMonth }}</p>
                            </div>

                            <!-- Pending Final -->
                            <div class="bg-red-100 p-6 rounded-lg shadow-md border border-red-200">
                                <h4 class="text-red-700 uppercase text-sm font-medium tracking-wider">Pending Final Approval</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingHrdCount }}</p>
                                <a href="{{ route('leave-verifications.index') }}" class="text-red-800 hover:underline font-semibold mt-2 inline-block">Proses Approval &rarr;</a>
                            </div>

                            <!-- Sedang Cuti -->
                            <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-200">
                                <h4 class="text-green-700 uppercase text-sm font-medium tracking-wider">Sedang Cuti (Bulan Ini)</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $employeesOnLeaveMonth->count() }}</p>
                                <span class="text-sm text-green-600">Karyawan</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- TABEL 1: KARYAWAN CUTI BULAN INI --}}
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                    <h4 class="font-semibold text-gray-700 text-sm">Karyawan Cuti (Bulan Ini)</h4>
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Nama</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Divisi</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($employeesOnLeaveMonth as $leave)
                                                <tr>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $leave->applicant->name }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">{{ $leave->applicant->division->name ?? '-' }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">{{ $leave->start_date->format('d/m') }} - {{ $leave->end_date->format('d/m') }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="px-4 py-3 text-center text-xs text-gray-500">Nihil.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- TABEL 2: DAFTAR DIVISI --}}
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                    <h4 class="font-semibold text-gray-700 text-sm">Daftar Divisi</h4>
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Nama Divisi</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Ketua</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Anggota</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($divisions as $div)
                                                <tr>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $div->name }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">{{ $div->leader->name ?? '-' }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-600 text-center">{{ $div->members_count }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="px-4 py-3 text-center text-xs text-gray-500">Belum ada divisi.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>