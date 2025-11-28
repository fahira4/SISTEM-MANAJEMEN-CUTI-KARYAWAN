<x-app-layout>
    {{-- ================================================== --}}
    {{-- 1. HERO SECTION & GLASS CARD --}}
    {{-- ================================================== --}}
    <div class="relative bg-blue-900 pb-24 pt-8 overflow-hidden">
        {{-- Background Pattern Halus --}}
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header Text --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div class="text-white">
                    <h2 class="text-2xl font-bold tracking-tight">
                        Selamat Datang, <span class="font-semibold text-white">{{ Auth::user()->name }}</span>!</h2>
                </div>
                
                <div class="hidden md:block">
                    <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                        <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-blue-100 text-sm font-medium">{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm animate-fade-in-down">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- GLASS CARD BERDASARKAN ROLE --}}
            @if (Auth::user()->role == 'ketua_divisi')
                @php
                    $leadingDivision = Auth::user()->leadingDivision;
                @endphp
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-5 flex flex-col md:flex-row justify-between items-center gap-4 shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm shadow-inner">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Divisi {{ $leadingDivision->name ?? 'Umum' }}</h3>
                            <p class="text-blue-100 text-lg opacity-90">Kelola anggota tim dan persetujuan cuti.</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('leave-applications.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-blue-900 font-bold rounded-lg shadow hover:bg-blue-50 transition-all transform hover:-translate-y-0.5 text-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Ajukan Cuti
                        </a>
                    </div>
                </div>
            @elseif (Auth::user()->role == 'hrd')
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-5 flex flex-col md:flex-row justify-between items-center gap-4 shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm shadow-inner">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Human Resource Department</h3>
                            <p class="text-blue-100 text-lg opacity-90">Kelola seluruh pengajuan cuti perusahaan.</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('leave-verifications.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-blue-900 font-bold rounded-lg shadow hover:bg-blue-50 transition-all transform hover:-translate-y-0.5 text-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Verifikasi Cuti
                        </a>
                    </div>
                </div>
            @elseif (Auth::user()->role == 'admin')
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-5 flex flex-col md:flex-row justify-between items-center gap-4 shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm shadow-inner">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Administrator System</h3>
                            <p class="text-blue-100 text-lg opacity-90">Kelola sistem dan pengguna perusahaan.</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-blue-900 font-bold rounded-lg shadow hover:bg-blue-50 transition-all transform hover:-translate-y-0.5 text-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                            Kelola User
                        </a>
                    </div>
                </div>
            @elseif (Auth::user()->role == 'karyawan')
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-5 flex flex-col md:flex-row justify-between items-center gap-4 shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm shadow-inner">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Karyawan</h3>
                            <p class="text-blue-100 text-lg opacity-90">Kelola pengajuan cuti dan riwayat Anda.</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('leave-applications.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-blue-900 font-bold rounded-lg shadow hover:bg-blue-50 transition-all transform hover:-translate-y-0.5 text-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Ajukan Cuti
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ================================================== --}}
    {{-- 2. KONTEN UTAMA (STATS & TABLES) --}}
    {{-- ================================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-12 relative z-10">

        {{-- #################### ROLE: KETUA DIVISI #################### --}}
        @if (Auth::user()->role == 'ketua_divisi')
            @php
                // Data Logic Re-used
                if($leadingDivision) {
                    $teamMembers = $leadingDivision->members;
                    $teamMemberIds = $teamMembers->pluck('id');
                } else {
                    $teamMembers = collect();
                    $teamMemberIds = collect();
                }
                $totalIncomingLeaves = \App\Models\LeaveApplication::whereIn('user_id', $teamMemberIds)->count();
                $pendingCount = \App\Models\LeaveApplication::whereIn('user_id', $teamMemberIds)->where('status', 'pending')->count();
                $startOfWeek = now()->startOfWeek(); 
                $endOfWeek = now()->endOfWeek();
                $teamOnLeave = \App\Models\LeaveApplication::whereIn('user_id', $teamMemberIds)
                    ->whereIn('status', ['approved_by_leader', 'approved_by_hrd'])
                    ->where(function($q) use ($startOfWeek, $endOfWeek) { 
                        $q->whereBetween('start_date', [$startOfWeek, $endOfWeek])
                          ->orWhereBetween('end_date', [$startOfWeek, $endOfWeek]); 
                    })->with('applicant')->get();
            @endphp

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="bg-white rounded-xl shadow-sm border-b-4 border-blue-500 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pengajuan</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalIncomingLeaves }}</h3>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Dokumen dari tim</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-orange-400 p-5 hover:shadow-md transition-shadow duration-300 relative overflow-hidden">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-xs font-bold text-orange-500 uppercase tracking-wider">Perlu Verifikasi</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $pendingCount }}</h3>
                            @if($pendingCount > 0)
                                <a href="{{ route('leave-verifications.index') }}" class="text-xs font-bold text-orange-600 hover:underline mt-2 inline-block">Proses Sekarang &rarr;</a>
                            @else
                                <p class="text-sm text-gray-400 mt-2">Semua aman</p>
                            @endif
                        </div>
                        <div class="p-2 bg-orange-50 rounded-lg text-orange-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-emerald-500 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Sedang Cuti</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $teamOnLeave->count() }}</h3>
                        </div>
                        <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Minggu ini</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h4 class="font-bold text-gray-700 text-lg">Sedang Cuti</h4>
                        <span class="text-[10px] font-semibold text-gray-500 bg-white border px-2 py-1 rounded">{{ $startOfWeek->format('d M') }} - {{ $endOfWeek->format('d M') }}</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <tbody class="divide-y divide-gray-50">
                                @forelse($teamOnLeave as $leave)
                                    <tr class="hover:bg-blue-50/40 transition-colors duration-200 cursor-default group">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold group-hover:scale-110 transition-transform">
                                                    {{ substr($leave->applicant->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800 text-sm">{{ $leave->applicant->name }}</p>
                                                    <span class="text-[10px] text-gray-500 uppercase">{{ $leave->leave_type }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <div class="text-sm font-bold text-blue-600">{{ $leave->total_days }} Hari</div>
                                            <div class="text-[10px] text-gray-400">s.d {{ $leave->end_date->format('d M') }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-5 py-8 text-center text-gray-400 text-sm">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Semua anggota hadir!
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h4 class="font-bold text-gray-700 text-lg">Anggota Tim</h4>
                    </div>
                    <div class="overflow-x-auto max-h-[300px] overflow-y-auto">
                        <table class="w-full">
                            <tbody class="divide-y divide-gray-50">
                                @forelse($teamMembers as $member)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-sm font-bold border border-gray-200">
                                                        {{ substr($member->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-800 text-sm group-hover:text-blue-600 transition-colors">{{ $member->name }}</p>
                                                        <p class="text-[10px] text-gray-400">{{ $member->email }}</p>
                                                    </div>
                                                </div>
                                                
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border {{ $member->active_status ? 'bg-green-50 text-green-600 border-green-100' : 'bg-red-50 text-red-600 border-red-100' }}">
                                                    {{ $member->active_status ? 'Aktif' : 'Non' }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td class="px-5 py-8 text-center text-gray-400 text-sm italic">Belum ada anggota.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        {{-- #################### ROLE: ADMIN #################### --}}
        @elseif (Auth::user()->role == 'admin')
            @php
                // Data untuk Admin
                $totalActiveUsers = \App\Models\User::where('active_status', true)->count();
                $totalInactiveUsers = \App\Models\User::where('active_status', false)->count();
                $startOfMonth = now()->startOfMonth();
                $endOfMonth = now()->endOfMonth();
                $monthlyLeaves = \App\Models\LeaveApplication::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $pendingApprovals = \App\Models\LeaveApplication::where('status', 'pending')->count();
                $newEmployees = \App\Models\User::where('join_date', '>=', now()->subYear())->get();
                $totalDivisions = \App\Models\Division::count();
            @endphp

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
                <div class="bg-white rounded-xl shadow-sm border-b-4 border-blue-500 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Karyawan Aktif</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalActiveUsers }}</h3>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Total: {{ $totalActiveUsers + $totalInactiveUsers }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-orange-400 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-orange-500 uppercase tracking-wider">Pengajuan Bulan Ini</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $monthlyLeaves }}</h3>
                        </div>
                        <div class="p-2 bg-orange-50 rounded-lg text-orange-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">{{ now()->format('M Y') }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-yellow-500 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-yellow-600 uppercase tracking-wider">Pending Approval</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $pendingApprovals }}</h3>
                        </div>
                        <div class="p-2 bg-yellow-50 rounded-lg text-yellow-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Menunggu persetujuan</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-purple-500 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-purple-600 uppercase tracking-wider">Total Divisi</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalDivisions }}</h3>
                        </div>
                        <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Divisi aktif</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-blue-600 to-blue-700">
                        <h4 class="font-bold text-white text-lg">Karyawan Baru (< 1 Tahun)</h4>
                        <span class="text-[10px] font-semibold text-gray-500 bg-white border px-2 py-1 rounded">{{ $newEmployees->count() }} orang</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <tbody class="divide-y divide-gray-50">
                                @forelse($newEmployees as $employee)
                                    <tr class="hover:bg-blue-50/40 transition-colors duration-200 cursor-default group">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold group-hover:scale-110 transition-transform">
                                                    {{ substr($employee->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800 text-sm">{{ $employee->name }}</p>
                                                    <span class="text-[10px] text-gray-500">{{ $employee->division->name ?? 'Belum ada divisi' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <div class="text-sm font-bold text-blue-600">{{ \Carbon\Carbon::parse($employee->join_date)->diffForHumans() }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d M Y') : '-' }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-5 py-8 text-center text-gray-400 text-sm">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                                                Tidak ada karyawan baru
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h4 class="font-bold text-gray-700 text-lg">Statistik Pengguna</h4>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Karyawan Aktif</span>
                                <span class="text-sm font-bold text-green-600">{{ $totalActiveUsers }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Karyawan Non-Aktif</span>
                                <span class="text-sm font-bold text-red-600">{{ $totalInactiveUsers }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Total Pengguna</span>
                                <span class="text-sm font-bold text-blue-600">{{ $totalActiveUsers + $totalInactiveUsers }}</span>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.users.index') }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-700 transition duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    Kelola Pengguna
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        {{-- #################### ROLE: KARYAWAN #################### --}}
        @elseif (Auth::user()->role == 'karyawan')
            @php
                // Data untuk Karyawan
                $user = Auth::user();
                $remainingQuota = $user->annual_leave_quota ?? 12;
                $sickLeaves = \App\Models\LeaveApplication::where('user_id', $user->id)->where('leave_type', 'sakit')->count();
                $totalLeaves = \App\Models\LeaveApplication::where('user_id', $user->id)->count();
                $division = $user->division;
                $divisionHead = $division ? $division->leader : null;
            @endphp

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="bg-white rounded-xl shadow-sm border-b-4 border-blue-500 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sisa Kuota Cuti</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $remainingQuota }} hari</h3>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Tahunan {{ now()->year }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-green-500 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Cuti Sakit</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $sickLeaves }}</h3>
                        </div>
                        <div class="p-2 bg-green-50 rounded-lg text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Total yang diajukan</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-purple-500 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-purple-600 uppercase tracking-wider">Total Pengajuan</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalLeaves }}</h3>
                        </div>
                        <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Semua jenis cuti</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h4 class="font-bold text-gray-700 text-lg">Informasi Divisi</h4>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Nama Divisi</span>
                                <span class="text-sm font-bold text-blue-600">{{ $division->name ?? 'Belum ada divisi' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Ketua Divisi</span>
                                <span class="text-sm font-bold text-green-600">{{ $divisionHead->name ?? 'Belum ada ketua' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Email Ketua</span>
                                <span class="text-sm font-bold text-purple-600">{{ $divisionHead->email ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

<div class="col-12 col-xl-6">
    <div class="card border-0 shadow-sm h-100 bg-white">
        <div class="card-body p-4">
            
            <h5 class="card-title mb-4 fw-bold text-dark" style="font-size: 1.1rem;">
                📅 Hari Libur Terdekat
            </h5>
            
            @if($upcomingHolidays->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    @foreach($upcomingHolidays as $holiday)
                    
                    <div style="display: flex; align-items: center; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px;">
                        
                        <div style="width: 60px; height: 60px; min-width: 60px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; margin-right: 15px;">
                            <span class="fw-bold text-danger" style="font-size: 1.4rem; line-height: 1;">
                                {{ \Carbon\Carbon::parse($holiday->date)->format('d') }}
                            </span>
                            <span class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem; line-height: 1; margin-top: 4px;">
                                {{ \Carbon\Carbon::parse($holiday->date)->translatedFormat('M') }}
                            </span>
                        </div>
                        
                        <div>
                            <h6 class="mb-1 fw-bold text-dark" style="font-size: 1rem; margin: 0;">
                                {{ $holiday->name }}
                            </h6>
                            <small class="text-secondary" style="font-size: 0.85rem;">
                                {{ \Carbon\Carbon::parse($holiday->date)->translatedFormat('l, d F Y') }}
                            </small>
                        </div>

                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <p class="mb-0 small">Tidak ada libur dalam waktu dekat.</p>
                </div>
            @endif

        </div>
    </div>
</div>

        {{-- #################### ROLE: HRD #################### --}}
        @elseif (Auth::user()->role == 'hrd')
            @php
                // Data untuk HRD
                $startOfMonth = now()->startOfMonth();
                $endOfMonth = now()->endOfMonth();
                $monthlyLeaves = \App\Models\LeaveApplication::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                $pendingFinalApprovals = \App\Models\LeaveApplication::where('status', 'approved_by_leader')->count();
                $employeesOnLeave = \App\Models\LeaveApplication::whereIn('status', ['approved_by_leader', 'approved_by_hrd'])
                    ->where(function($q) use ($startOfMonth, $endOfMonth) { 
                        $q->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                          ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth]); 
                    })->with('applicant')->get();
                $divisions = \App\Models\Division::withCount('members')->get();
            @endphp

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="bg-white rounded-xl shadow-sm border-b-4 border-blue-500 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pengajuan Bulan Ini</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $monthlyLeaves }}</h3>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">{{ now()->format('M Y') }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-orange-400 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-orange-500 uppercase tracking-wider">Pending Final Approval</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $pendingFinalApprovals }}</h3>
                            @if($pendingFinalApprovals > 0)
                                <a href="{{ route('leave-verifications.index') }}" class="text-xs font-bold text-orange-600 hover:underline mt-2 inline-block">Proses Sekarang &rarr;</a>
                            @else
                                <p class="text-sm text-gray-400 mt-2">Semua aman</p>
                            @endif
                        </div>
                        <div class="p-2 bg-orange-50 rounded-lg text-orange-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border-b-4 border-emerald-500 p-5 hover:shadow-md transition-shadow duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Sedang Cuti</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $employeesOnLeave->count() }}</h3>
                        </div>
                        <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Bulan ini</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h4 class="font-bold text-gray-700 text-lg">Karyawan Sedang Cuti</h4>
                        <span class="text-[10px] font-semibold text-gray-500 bg-white border px-2 py-1 rounded">{{ now()->format('M Y') }}</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <tbody class="divide-y divide-gray-50">
                                @forelse($employeesOnLeave as $leave)
                                    <tr class="hover:bg-blue-50/40 transition-colors duration-200 cursor-default group">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold group-hover:scale-110 transition-transform">
                                                    {{ substr($leave->applicant->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800 text-sm">{{ $leave->applicant->name }}</p>
                                                    <span class="text-[10px] text-gray-500">{{ $leave->applicant->division->name ?? 'Belum ada divisi' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <div class="text-sm font-bold text-blue-600">{{ $leave->total_days }} Hari</div>
                                            <div class="text-[10px] text-gray-400">{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M') }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-5 py-8 text-center text-gray-400 text-sm">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Tidak ada karyawan cuti
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h4 class="font-bold text-gray-700 text-lg">Daftar Divisi</h4>
                    </div>
                    <div class="overflow-x-auto max-h-[300px] overflow-y-auto">
                        <table class="w-full">
                            <tbody class="divide-y divide-gray-50">
                                @forelse($divisions as $division)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-sm font-bold border border-purple-200">
                                                        {{ substr($division->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-800 text-sm group-hover:text-purple-600 transition-colors">{{ $division->name }}</p>
                                                        <p class="text-[10px] text-gray-400">{{ $division->head->name ?? 'Belum ada ketua' }}</p>
                                                    </div>
                                                </div>
                                                
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border bg-blue-50 text-blue-600 border-blue-100">
                                                    {{ $division->members_count }} anggota
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td class="px-5 py-8 text-center text-gray-400 text-sm italic">Belum ada divisi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        
    </div>
</x-app-layout>