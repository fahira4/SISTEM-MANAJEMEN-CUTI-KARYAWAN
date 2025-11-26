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
                    <h2 class="text-2xl font-bold tracking-tight">Dashboard</h2>
                    <p class="text-blue-200 text-lg mt-1">
                        Selamat Datang, <span class="font-semibold text-white">{{ Auth::user()->name }}</span>!
                    </p>
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

            {{-- ######################################################## --}}
            {{-- GLASS CARD: KHUSUS KETUA DIVISI (DIMASUKKAN KE BG BIRU) --}}
            {{-- ######################################################## --}}
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

            {{-- STATS CARDS (UKURAN LEBIH KECIL & RAPI) --}}
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

        {{-- #################### ROLE LAIN (ADMIN/HRD/KARYAWAN - COBA TERAPKAN POLA YANG SAMA) #################### --}}
        @elseif (Auth::user()->role == 'admin')
             {{-- Copas logic admin disini tapi gunakan style card & layout seperti di atas (ukuran lebih kecil) --}}
             <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                 <p class="text-gray-500">Tampilan Admin (Silakan sesuaikan style dengan contoh Ketua Divisi di atas).</p>
             </div>
        @elseif (Auth::user()->role == 'karyawan')
             <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                 <p class="text-gray-500">Tampilan Karyawan (Silakan sesuaikan style dengan contoh Ketua Divisi di atas).</p>
             </div>
        @elseif (Auth::user()->role == 'hrd')
             <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                 <p class="text-gray-500">Tampilan HRD (Silakan sesuaikan style dengan contoh Ketua Divisi di atas).</p>
             </div>
        @endif
        
    </div>
</x-app-layout>