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
                    <p class="text-blue-100 text-lg opacity-90 mt-2">Administrator Sistem</p>
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
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white">System Administrator</h3>
                        <p class="text-blue-100 text-lg opacity-90">Kelola seluruh data user dan konfigurasi sistem.</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-blue-900 font-bold rounded-lg shadow hover:bg-blue-50 transition-all transform hover:-translate-y-0.5 text-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah User Baru
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
                            @else
                                <p class="text-lg font-bold text-orange-800">Hari Kerja</p>
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
                            @else
                                <p class="text-sm font-bold text-purple-800">Tidak ada</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-lg border-l-4 border-red-500 p-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">Sedang Cuti</p>
                            <p class="text-lg font-bold text-red-800">{{ $employeesOnLeave->count() }} Orang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $totalActiveEmployees = \App\Models\User::where('role', 'karyawan')->where('active_status', true)->count();
            $totalInactiveEmployees = \App\Models\User::where('role', 'karyawan')->where('active_status', false)->count();
            $totalUsers = $totalActiveEmployees + $totalInactiveEmployees;
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();
            $monthlyLeaves = \App\Models\LeaveApplication::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $pendingApprovals = \App\Models\LeaveApplication::where('status', 'pending')->count();
            $oneYearAgo = now()->subYear();
            $newEmployees = \App\Models\User::where('role', 'karyawan')
                ->where('join_date', '>', $oneYearAgo)
                ->where('active_status', true)
                ->with('division')
                ->orderBy('join_date', 'desc')
                ->get();
            $totalDivisions = \App\Models\Division::count();
        @endphp

        <div class="bg-white rounded-xl shadow-lg border-b-4 border-blue-600 p-6 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Manajemen Sistem</h3>
                    <p class="text-gray-500 text-sm mt-1">Pusat kontrol utama aplikasi</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('admin.users.index') }}" class="bg-purple-50 border border-purple-100 rounded-xl p-6 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-1 group cursor-pointer block">
                    <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition duration-200">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-purple-700 mb-1">{{ $totalUsers }}</div>
                    <div class="text-sm text-purple-600 font-bold uppercase tracking-wide">Total Pengguna</div>
                </a>

                <a href="{{ route('admin.divisions.index') }}" class="bg-blue-50 border border-blue-100 rounded-xl p-6 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-1 group cursor-pointer block">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition duration-200">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-blue-700 mb-1">{{ $totalDivisions }}</div>
                    <div class="text-sm text-blue-600 font-bold uppercase tracking-wide">Total Divisi</div>
                </a>

                <a href="#" class="bg-green-50 border border-green-100 rounded-xl p-6 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-1 group cursor-pointer block">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition duration-200">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-green-700 mb-1">{{ $monthlyLeaves }}</div>
                    <div class="text-sm text-green-600 font-bold uppercase tracking-wide"> Laporan Cuti Bulan Ini</div>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-b-4 border-orange-500 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Statistik Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-center hover:shadow-sm transition-all">
                    <div class="text-3xl font-bold text-yellow-700 mb-1">{{ $pendingApprovals }}</div>
                    <div class="text-xs text-yellow-600 font-bold uppercase tracking-wider">Pending Approval</div>
                </div>
                <div class="bg-green-50 border border-green-100 rounded-xl p-4 text-center hover:shadow-sm transition-all">
                    <div class="text-3xl font-bold text-green-700 mb-1">{{ $totalActiveEmployees }}</div>
                    <div class="text-xs text-green-600 font-bold uppercase tracking-wider">Karyawan Aktif</div>
                </div>
                <div class="bg-red-50 border border-red-100 rounded-xl p-4 text-center hover:shadow-sm transition-all">
                    <div class="text-3xl font-bold text-red-700 mb-1">{{ $totalInactiveEmployees }}</div>
                    <div class="text-xs text-red-600 font-bold uppercase tracking-wider">Non-Aktif</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <div>
                        <h4 class="font-bold text-gray-800 text-lg">Karyawan Baru</h4>
                        <p class="text-xs text-red-500 font-medium">*Belum eligible cuti tahunan</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Karyawan</th>
                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Masa Kerja</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($newEmployees as $employee)
                                @php
                                    $joinDate = \Carbon\Carbon::parse($employee->join_date);
                                    $totalDaysWorked = (int) $joinDate->diffInDays(now()); 
                                    $daysToEligible = 365 - $totalDaysWorked;
                                    if ($daysToEligible < 0) $daysToEligible = 0;
                                @endphp
                                <tr class="hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold shadow-sm">
                                                {{ substr($employee->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 text-base">{{ $employee->name }}</p>
                                                <span class="text-xs text-gray-500 font-medium">{{ $employee->division->name ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-base font-bold text-blue-700">{{ $totalDaysWorked }} Hari</div>
                                        <div class="text-xs text-gray-400 font-medium">Kurang {{ $daysToEligible }} hari</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-gray-400 text-sm italic bg-gray-50">Tidak ada karyawan baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <div>
                        <h4 class="font-bold text-gray-800 text-lg">Daftar Divisi</h4>
                        <p class="text-sm text-gray-500">Struktur organisasi</p>
                    </div>
                </div>
                <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
                    <table class="w-full">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Nama Divisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @php $divisions = \App\Models\Division::with(['leader', 'members'])->get(); @endphp
                            @forelse($divisions as $division)
                                <tr class="hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold shadow-sm border border-indigo-200">
                                                    {{ substr($division->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800 text-base">{{ $division->name }}</p>
                                                    <p class="text-xs text-gray-500 font-medium">{{ $division->leader->name ?? 'Vacant' }}</p>
                                                </div>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                                {{ $division->members->count() }} Anggota
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="px-6 py-8 text-center text-gray-400 text-sm italic bg-gray-50">Data Kosong</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>