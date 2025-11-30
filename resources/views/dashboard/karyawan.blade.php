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
                    <p class="text-blue-100 text-lg opacity-90 mt-2">
                        Anggota Divisi {{ Auth::user()->division->name ?? '' }}
                    </p>
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
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-12 relative z-10">

        <div class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div class="bg-blue-100 rounded-xl shadow-lg border-l-4 border-blue-500 p-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Hari Ini</p>
                            <p class="text-lg font-bold text-gray-800">{{ now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                </div>
               
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-lg border-l-4 border-orange-500 p-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-orange-600 uppercase tracking-wide">Besok</p>
                            @if($tomorrowHoliday)
                                <p class="text-lg font-bold text-orange-800">{{ $tomorrowHoliday->name }}</p>
                                <p class="text-xs text-orange-600 mt-1">Hari Libur Nasional</p>
                            @else
                                <p class="text-lg font-bold text-orange-800">Hari Kerja</p>
                                <p class="text-xs text-orange-600 mt-1">Tidak ada libur</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg border-l-4 border-purple-500 p-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide">Libur Mendatang</p>
                            @if($upcomingHolidays->count() > 0)
                                <p class="text-sm font-bold text-purple-800">{{ $upcomingHolidays->first()->name }}</p>
                                <p class="text-xs text-purple-600 mt-1">{{ \Carbon\Carbon::parse($upcomingHolidays->first()->date)->translatedFormat('d M Y') }}</p>
                            @else
                                <p class="text-sm font-bold text-purple-800">Tidak ada</p>
                                <p class="text-xs text-purple-600 mt-1">Dalam 30 hari</p>
                            @endif
                        </div>
                    </div>
                </div>
               
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-lg border-l-4 border-red-500 p-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">Sedang Cuti</p>
                            <p class="text-lg font-bold text-red-800">{{ $employeesOnLeave->count() }} Orang</p>
                            <p class="text-xs text-red-600 mt-1">Hari ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $user = Auth::user();
            $usedAnnualLeave = \App\Models\LeaveApplication::where('user_id', $user->id)
                                ->where('leave_type', 'cuti_tahunan')
                                ->where('status', 'approved_by_hrd')
                                ->sum('total_days');
            $quota = 12;
            $remainingQuota = $quota - $usedAnnualLeave;
            $sickLeaveCount = \App\Models\LeaveApplication::where('user_id', $user->id)
                                ->where('leave_type', 'sakit')
                                ->count();
            $totalApplications = \App\Models\LeaveApplication::where('user_id', $user->id)->count();
            $divisionName = $user->division->name ?? 'Belum ada divisi';
            $leaderName = $user->division->leader->name ?? 'Belum ada ketua';
        @endphp

        <div class="mb-8 relative z-20">
            <div class="bg-white border-b-4 border-blue-600 rounded-xl p-6 flex flex-col md:flex-row justify-between items-center gap-4 shadow-lg">
                <div class="flex items-center gap-5 w-full md:w-auto">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center shadow-sm border border-blue-100 shrink-0">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Panel Karyawan</h3>
                        <p class="text-gray-500 text-sm md:text-base">
                            Divisi: <span class="font-bold text-blue-600">{{ $divisionName }}</span> 
                            <span class="mx-2 text-gray-300">|</span> 
                            Ketua: <span class="font-bold text-blue-600">{{ $leaderName }}</span>
                        </p>
                    </div>
                </div>
                <div class="w-full md:w-auto">
                    <a href="{{ route('leave-applications.create') }}" class="inline-flex items-center justify-center w-full md:w-auto px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700 transition-all transform hover:-translate-y-0.5 text-base">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajukan Cuti
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-b-4 border-blue-600 p-6 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Statistik Cuti Saya</h3>
                    <p class="text-gray-500 text-sm mt-1">Ringkasan riwayat dan kuota cuti Anda</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-green-50 border border-green-100 rounded-xl p-6 text-center hover:shadow-md transition-all duration-200">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-green-700 mb-1">{{ $remainingQuota }} Hari</div>
                    <div class="text-sm text-green-600 font-bold uppercase tracking-wide">Sisa Kuota Tahunan</div>
                </div>
                <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-6 text-center hover:shadow-md transition-all duration-200">
                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-yellow-700 mb-1">{{ $sickLeaveCount }} Kali</div>
                    <div class="text-sm text-yellow-600 font-bold uppercase tracking-wide">Pengajuan Sakit</div>
                </div>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 text-center hover:shadow-md transition-all duration-200">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-blue-700 mb-1">{{ $totalApplications }}</div>
                    <div class="text-sm text-blue-600 font-bold uppercase tracking-wide">Total Pengajuan</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
           
            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl shadow-lg border border-blue-200 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Informasi Cepat</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-blue-100">
                        <span class="text-sm font-medium text-gray-700">Pengajuan Bulan Ini</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold">{{ $currentMonthLeaves ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-blue-100">
                        <span class="text-sm font-medium text-gray-700">Sisa Kuota Tahunan</span>
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">{{ $remainingQuota }} Hari</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Hari Libur Mendatang</h3>
                </div>
                <div class="space-y-3">
                    @forelse($upcomingHolidays as $holiday)
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-100">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $holiday->name }}</p>
                            <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($holiday->date)->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-bold">
                            {{ \Carbon\Carbon::parse($holiday->date)->diffForHumans() }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <p class="text-sm">Tidak ada hari libur mendatang</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>