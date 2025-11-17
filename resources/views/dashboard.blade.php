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

                {{-- Logika untuk menampilkan dashboard berdasarkan Peran --}}

                @if (Auth::user()->role == 'admin')
                        {{-- (Biarkan dasbor Admin apa adanya untuk saat ini) --}}
                        <h3 class="font-semibold text-lg">Selamat Datang, Admin</h3>
                        <p>Ini adalah Dasbor Admin.</p>
                    
                    {{-- GANTI BLOK KARYAWAN DI BAWAH INI --}}
                    @elseif (Auth::user()->role == 'karyawan')
                        <h3 class="font-semibold text-lg mb-4">Selamat Datang, {{ Auth::user()->name }}</h3>

                        {{-- Tombol Aksi Utama --}}
                        <div class="mb-6">
                            <a href="{{ route('leave-applications.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-150 ease-in-out">
                                + Buat Pengajuan Cuti Baru
                            </a>
                        </div>

                        {{-- Grid untuk Kartu Statistik --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Sisa Kuota Cuti Tahunan</h4>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ Auth::user()->annual_leave_quota }} <span class="text-xl font-medium">hari</span></p>
                            </div>

                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Divisi Anda</h4>
                                <p class="text-xl font-semibold text-gray-900 mt-2">{{ Auth::user()->division->name ?? 'Belum ada Divisi' }}</p>
                                <span class="text-gray-500 text-sm">Ketua: {{ Auth::user()->division->leader->name ?? '-' }}</span>
                            </div>

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
                    {{-- BATAS PENGGANTIAN BLOK KARYAWAN --}}

                    @elseif (Auth::user()->role == 'ketua_divisi')
                        <h3 class="font-semibold text-lg mb-4">Selamat Datang, {{ Auth::user()->name }} (Ketua Divisi)</h3>

                        <div class="mb-6">
                            <a href="{{ route('leave-applications.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg">
                                + Buat Pengajuan Cuti (Pribadi)
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div class="bg-yellow-100 p-6 rounded-lg shadow-md border border-yellow-200">
                                <h4 class="text-yellow-700 uppercase text-sm font-medium tracking-wider">Verifikasi Cuti Anggota</h4>

                                {{-- Logika PHP untuk menghitung cuti pending --}}
                                @php
                                    $teamMemberIds = \App\Models\User::where('division_id', Auth::user()->division_id)->pluck('id');
                                    $pendingCount = \App\Models\LeaveApplication::whereIn('user_id', $teamMemberIds)
                                                                            ->where('status', 'pending')
                                                                            ->count();
                                @endphp

                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    {{ $pendingCount }} Pengajuan
                                </p>
                                <a href="{{ route('leave-verifications.index') }}" class="text-yellow-800 hover:underline font-semibold mt-4 inline-block">
                                    Lihat Daftar Verifikasi &rarr;
                                </a>
                            </div>

                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Riwayat Cuti (Pribadi)</h4>
                                <p class="text-gray-600 mt-2 mb-4">Lihat riwayat pengajuan cuti Anda pribadi.</p>
                                <a href="{{ route('leave-applications.index') }}" class="text-blue-600 hover:underline font-semibold self-start">
                                    Lihat Riwayat Saya &rarr;
                                </a>
                            </div>

                            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                <h4 class="text-gray-500 uppercase text-sm font-medium tracking-wider">Divisi Anda</h4>
                                <p class="text-xl font-semibold text-gray-900 mt-2">{{ Auth::user()->division->name ?? 'Belum ada Divisi' }}</p>
                                <span class="text-gray-500 text-sm">Jumlah Anggota: {{ Auth::user()->division->members->count() ?? 0 }}</span>
                            </div>
                        </div>

                    @elseif (Auth::user()->role == 'hrd')
                        <h3 class="font-semibold text-lg">Selamat Datang, HRD</h3>
                        <p>Ini adalah Dasbor HRD.</p>
                    @endif

            </div>
        </div>
    </div>
</div>
</x-app-layout>
