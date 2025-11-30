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
                        Selamat Datang, <span class="font-semibold text-white">{{ Auth::user()->name }}</span>!
                    </h2>
                    <p class="text-blue-100 text-lg opacity-90 mt-2">Human Resource Department</p>
                </div>
                <div class="hidden md:block">
                    <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                        <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-blue-100 text-sm font-medium">{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm animate-fade-in-down">
                    {{ session('success') }}
                </div>
            @endif

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
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-12 relative z-10">
        
        <div class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
                <div class="bg-blue-100 rounded-xl shadow-lg border-l-4 border-blue-500 p-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Hari Ini</p>
                            <p class="text-lg font-bold text-gray-800">{{ now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-lg border-l-4 border-orange-500 p-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-orange-600 uppercase tracking-wide">Besok</p>
                            @if($tomorrowHoliday)
                                <p class="text-lg font-bold text-orange-800">{{ $tomorrowHoliday->name }}</p>
                            @else
                                <p class="text-lg font-bold text-orange-800">Hari Kerja</p>
                            @endif
                        </div>
                    </div>
                </div>
               
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg border-l-4 border-purple-500 p-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-purple-600 uppercase tracking-wide">Libur Mendatang</p>
                            @if($upcomingHolidays->count() > 0)
                                <p class="text-sm font-bold text-purple-800">{{ $upcomingHolidays->first()->name }}</p>
                            @else
                                <p class="text-sm font-bold text-purple-800">Tidak ada</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-lg border-l-4 border-red-500 p-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-red-600 uppercase tracking-wide">Sedang Cuti</p>
                            <p class="text-lg font-bold text-red-800">{{ $employeesOnLeave->count() }} Orang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();
            $monthlyLeaves = $monthlyLeavesCount ?? \App\Models\LeaveApplication::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $pendingFinalApprovals = $pendingFinalApprovalsCount ?? \App\Models\LeaveApplication::where('status', 'approved_by_leader')->count();
            
            $today = now()->format('Y-m-d');
            $employeesOnLeave = \App\Models\LeaveApplication::whereIn('status', ['approved_by_leader', 'approved_by_hrd'])
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->with('applicant')
                ->get();
                
            $divisions = \App\Models\Division::withCount('members')->get();
            $totalApplications = \App\Models\LeaveApplication::count();
            $pendingCount = \App\Models\LeaveApplication::where('status', 'pending')->count();
            $approvedCount = \App\Models\LeaveApplication::where('status', 'approved_by_hrd')->count();
            $rejectedCount = \App\Models\LeaveApplication::whereIn('status', ['rejected_by_leader', 'rejected_by_hrd'])->count();
        @endphp

       <div class="mb-8">
            <div class="bg-white rounded-xl shadow-lg border-b-4 border-orange-500 p-6 mb-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Manajemen Cuti HRD</h3>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <a href="{{ route('hrd.all-leaves') }}" class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-200 transition duration-200">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                        </div>
                        <div class="text-xl font-bold text-purple-700">{{ $totalApplications }}</div>
                        <div class="text-lg text-purple-600 font-medium mt-1">Semua Pengajuan</div>
                    </a>

                    <a href="{{ route('leave-verifications.index') }}" class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 group">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-200 transition duration-200">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-xl font-bold text-blue-700">{{ $pendingFinalApprovals }}</div>
                        <div class="text-lg text-blue-600 font-medium mt-1">Perlu Verifikasi</div>
                    </a>

                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-b-4 border-orange-500 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Statistik Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                    <div class="text-xl font-bold text-yellow-700">{{ $pendingCount }}</div>
                    <div class="text-lg text-yellow-600 font-medium mt-1">Menunggu</div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                    <div class="text-xl font-bold text-green-700">{{ $approvedCount }}</div>
                    <div class="text-lg text-green-600 font-medium mt-1">Disetujui</div>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                    <div class="text-xl font-bold text-red-700">{{ $rejectedCount }}</div>
                    <div class="text-lg text-red-600 font-medium mt-1">Ditolak</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Aktivitas Minggu Ini</h3>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-sm font-bold">
                        {{ \Carbon\Carbon::now()->startOfWeek()->format('d M') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('d M') }}
                    </span>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Pengajuan Baru</p>
                                <p class="text-sm text-gray-600">Minggu ini</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-gray-800">{{ $weeklyLeaves }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Perlu Verifikasi</p>
                                <p class="text-sm text-gray-600">Menunggu persetujuan</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-orange-600">{{ $pendingVerifications ?? $pendingCount }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
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
                                                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-lg font-bold border border-purple-200">
                                                    {{ substr($division->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800 text-lg group-hover:text-purple-600 transition-colors">{{ $division->name }}</p>
                                                    <p class="text-sm text-gray-400">{{ $division->leader->name ?? 'Belum ada ketua' }}</p>
                                                </div>
                                            </div>
                                            <span class="px-2 py-0.5 rounded text-sm font-bold uppercase tracking-wide border bg-blue-50 text-blue-600 border-blue-100">
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h4 class="font-bold text-gray-700 text-lg">Karyawan Sedang Cuti</h4>
                    <span class="text-[10px] font-semibold text-gray-500 bg-white border px-2 py-1 rounded">
                        {{ \Carbon\Carbon::now()->format('d M Y') }}
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($employeesOnLeave as $leave)
                                <tr class="hover:bg-blue-50/40 transition-colors duration-200 cursor-default group">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold group-hover:scale-110 transition-transform">
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
                                            Tidak ada karyawan cuti hari ini
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>