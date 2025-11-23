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

                    {{-- Logic for displaying messages --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
                    @endif
                    
                    {{-- Logika untuk menampilkan dashboard berdasarkan Peran --}}
                    
                    {{-- ---------------------------------------------------- --}}
                    {{-- DASBOR ADMIN --}}
                    {{-- ---------------------------------------------------- --}}
                    @if (Auth::user()->role == 'admin')
                        <h3 class="font-semibold text-lg mb-4">Selamat Datang, {{ Auth::user()->name }} (Administrator)</h3>

                        @php
                            // Ambil data statistik Admin
                            $totalUsers = \App\Models\User::count();
                            $totalDivisions = \App\Models\Division::count();
                            
                            // Pending Approvals HRD (logic dari fungsi HRD)
                            $pendingHrdCount = \App\Models\LeaveApplication::where('status', 'approved_by_leader')
                                                                           ->orWhere(function($query) {
                                                                               $query->where('status', 'pending')
                                                                                     ->whereHas('applicant', function($q) {
                                                                                         $q->where('role', 'ketua_divisi');
                                                                                     });
                                                                           })
                                                                           ->count();
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Kartu 1: Persetujuan Final (HRD Queue) -->
                            <div class="bg-red-100 p-6 rounded-lg shadow-md border border-red-200">
                                <h4 class="text-red-700 uppercase text-sm font-medium tracking-wider">Persetujuan Final (HRD Queue)</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingHrdCount }} Pengajuan</p>
                                <a href="{{ route('leave-verifications.index') }}" class="text-red-800 hover:underline font-semibold mt-4 inline-block">
                                    Lihat Antrian &rarr;
                                </a>
                            </div>

                            <!-- Kartu 2: Total Pengguna -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Total Pengguna (Semua Role)</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</p>
                                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline font-semibold self-start">
                                    Kelola Pengguna &rarr;
                                </a>
                            </div>

                            <!-- Kartu 3: Total Divisi -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Total Divisi Terdaftar</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalDivisions }}</p>
                                <a href="{{ route('admin.divisions.index') }}" class="text-blue-600 hover:underline font-semibold self-start">
                                    Kelola Divisi &rarr;
                                </a>
                            </div>
                        </div>
                    
                    {{-- ---------------------------------------------------- --}}
                    {{-- DASBOR KARYAWAN --}}
                    {{-- ---------------------------------------------------- --}}
                    @elseif (Auth::user()->role == 'karyawan')
                        <h3 class="font-semibold text-lg mb-4">Selamat Datang, {{ Auth::user()->name }} (Karyawan)</h3>

                        {{-- Tombol Aksi Utama --}}
                        <div class="mb-6">
                            <a href="{{ route('leave-applications.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-150 ease-in-out">
                                + Buat Pengajuan Cuti Baru
                            </a>
                        </div>

                        {{-- Grid untuk Kartu Statistik --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Kartu 1: Sisa Kuota Cuti -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Sisa Kuota Cuti Tahunan</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ Auth::user()->annual_leave_quota }} <span class="text-xl font-medium">hari</span></p>
                            </div>

                            <!-- Kartu 2: Info Divisi -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Divisi Anda</h4>
                                {{-- Menggunakan operator null-safe: jika division null, maka name juga null. --}}
                                <p class="text-xl font-semibold text-gray-900 mt-2">{{ Auth::user()->division?->name ?? 'Belum ada Divisi' }}</p>
                                <span class="text-gray-500 text-sm">Ketua: {{ Auth::user()->division?->leader?->name ?? '-' }}</span>
                            </div>

                            <!-- Kartu 3: Riwayat Cuti (Link) -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex flex-col justify-between">
                                <div>
                                    <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Riwayat Cuti</h4>
                                    <p class="text-gray-600 mt-2 mb-4">Lihat semua status pengajuan cuti Anda yang lalu dan yang sedang berjalan.</p>
                                </div>
                                <a href="{{ route('leave-applications.index') }}" class="text-blue-600 hover:underline font-semibold self-start">
                                    Lihat Riwayat Pengajuan Cuti &rarr;
                                </a>
                            </div>

                        </div>
                    
                    {{-- ---------------------------------------------------- --}}
                    {{-- DASBOR KETUA DIVISI --}}
                    {{-- ---------------------------------------------------- --}}
                    @elseif (Auth::user()->role == 'ketua_divisi')
                        <h3 class="font-semibold text-lg mb-4">Selamat Datang, {{ Auth::user()->name }} (Ketua Divisi)</h3>
                        
                        {{-- Logika untuk menghitung cuti pending ketua divisi --}}
                        @php
                            // Ambil ID anggota tim
                            $teamMemberIds = Auth::user()->division?->members->pluck('id') ?? collect();
                            // Hitung cuti pending dari anggota tim
                            $pendingCount = \App\Models\LeaveApplication::whereIn('user_id', $teamMemberIds)
                                                                       ->where('status', 'pending')
                                                                       ->count();
                        @endphp
                        
                        <!-- Tombol Aksi Pribadi -->
                        <div class="mb-6">
                            <a href="{{ route('leave-applications.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg">
                                + Buat Pengajuan Cuti (Pribadi)
                            </a>
                        </div>
                        
                        <!-- Grid Kartu Statistik -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Kartu 1: Verifikasi Cuti (Link Manajerial) -->
                            <div class="bg-yellow-100 p-6 rounded-lg shadow-md border border-yellow-200">
                                <h4 class="text-yellow-700 uppercase text-sm font-medium tracking-wider">Verifikasi Cuti Anggota</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    {{ $pendingCount }} Pengajuan
                                </p>
                                <a href="{{ route('leave-verifications.index') }}" class="text-yellow-800 hover:underline font-semibold mt-4 inline-block">
                                    Lihat Daftar Verifikasi &rarr;
                                </a>
                            </div>

                            <!-- Kartu 2: Riwayat Cuti (Link Pribadi) -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Riwayat Cuti (Pribadi)</h4>
                                <p class="text-gray-600 mt-2 mb-4">Lihat riwayat pengajuan cuti Anda pribadi.</p>
                                <a href="{{ route('leave-applications.index') }}" class="text-blue-600 hover:underline font-semibold self-start">
                                    Lihat Riwayat Saya &rarr;
                                </a>
                            </div>

                            <!-- Kartu 3: Info Tim Anda -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Divisi Anda</h4>
                                <p class="text-xl font-semibold text-gray-900 mt-2">{{ Auth::user()->division?->name ?? 'Belum ada Divisi' }}</p>
                                <span class="text-gray-500 text-sm">Jumlah Anggota: {{ Auth::user()->division?->members->count() ?? 0 }}</span>
                            </div>
                        </div>

                    {{-- ---------------------------------------------------- --}}
                    {{-- DASBOR HRD --}}
                    {{-- ---------------------------------------------------- --}}
                    @elseif (Auth::user()->role == 'hrd')
                        <h3 class="font-semibold text-lg mb-4">Selamat Datang, {{ Auth::user()->name }} (HRD)</h3>

                        {{-- Logika untuk menghitung cuti pending HRD --}}
                        @php
                            $pendingHrdCount = \App\Models\LeaveApplication::where('status', 'approved_by_leader')
                                                                           ->orWhere(function($query) {
                                                                               $query->where('status', 'pending')
                                                                                     ->whereHas('applicant', function($q) {
                                                                                         $q->where('role', 'ketua_divisi');
                                                                                     });
                                                                           })
                                                                           ->count();
                            $totalApprovedFinal = \App\Models\LeaveApplication::where('status', 'approved_by_hrd')->count();
                            $totalUsers = \App\Models\User::count();
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Kartu 1: Persetujuan Final (Link Utama) -->
                            <div class="bg-red-100 p-6 rounded-lg shadow-md border border-red-200">
                                <h4 class="text-red-700 uppercase text-sm font-medium tracking-wider">Antrian Persetujuan Final</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingHrdCount }} Pengajuan</p>
                                <a href="{{ route('leave-verifications.index') }}" class="text-red-800 hover:underline font-semibold mt-4 inline-block">
                                    Lihat Antrian &rarr;
                                </a>
                            </div>

                            <!-- Kartu 2: Total Cuti Disetujui (Final) -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Total Cuti Disetujui (Final)</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalApprovedFinal }}</p>
                                <span class="text-gray-500 text-sm">Sepanjang waktu</span>
                            </div>

                            <!-- Kartu 3: Total Pengguna -->
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Total Pengguna Aktif</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</p>
                                <span class="text-gray-500 text-sm">Untuk referensi</span>
                            </div>

                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>