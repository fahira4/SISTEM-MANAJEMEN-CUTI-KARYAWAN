<x-app-layout>
    {{-- ================================================== --}}
    {{-- 1. HERO SECTION & GLASS CARD --}}
    {{-- ================================================== --}}
    <div class="relative bg-blue-900 min-h-[40vh] overflow-hidden">
        {{-- Background Pattern Halus --}}
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-24">
            {{-- Header Text --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div class="text-white">
                    <h2 class="text-4xl font-bold tracking-tight">Manajemen Pengguna</h2>
                    <p class="text-blue-200 text-xl mt-2">
                        Kelola semua pengguna sistem C-OPS
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="hidden md:block">
                        <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                            <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                            <span class="text-blue-100 text-sm font-medium">Total: {{ $users->total() }} Pengguna</span>
                        </div>
                    </div>

                    <a href="{{ route('admin.users.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white text-blue-900 font-bold rounded-lg shadow hover:bg-blue-50 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Pengguna Baru
                    </a>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm animate-fade-in-down">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-500/20 border border-red-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm animate-fade-in-down">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

{{-- ================================================== --}}
{{-- 2. FILTER & SORTING SECTION --}}
{{-- ================================================== --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-8 relative z-10">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden border-b-4 border-b-orange-500">
        {{-- Form Wrapper --}}
        <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                    
                    {{-- Filter Card --}}
                    <div class="bg-gray-50 p-5 border border-gray-200 rounded-lg flex flex-col">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter Data Pengguna
                        </h4>
                        <div class="grid grid-cols-1 gap-4 flex-1">
                            <!-- Filter Role -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                <select name="role" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="karyawan" {{ request('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="ketua_divisi" {{ request('role') == 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi</option>
                                    <option value="hrd" {{ request('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                </select>
                            </div>

                            <!-- Filter Status Aktif -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="active_status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('active_status') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ request('active_status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>

                            <!-- Filter Divisi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Divisi</label>
                                <select name="division_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                                    <option value="">Semua Divisi</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Masa Kerja -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Masa Kerja</label>
                                <select name="employment_period" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                                    <option value="">Semua Masa Kerja</option>
                                    <option value="less_than_30_days" {{ request('employment_period') == 'less_than_30_days' ? 'selected' : '' }}>Kurang dari 30 hari</option>
                                    <option value="30_90_days" {{ request('employment_period') == '30_90_days' ? 'selected' : '' }}>30 - 90 hari</option>
                                    <option value="90_180_days" {{ request('employment_period') == '90_180_days' ? 'selected' : '' }}>90 - 180 hari</option>
                                    <option value="180_365_days" {{ request('employment_period') == '180_365_days' ? 'selected' : '' }}>180 - 365 hari</option>
                                    <option value="more_than_1_year" {{ request('employment_period') == 'more_than_1_year' ? 'selected' : '' }}>Lebih dari 1 tahun</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Sort Card --}}
                    <div class="bg-gray-50 p-5 border border-gray-200 rounded-lg flex flex-col">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                            </svg>
                            Urutkan Data
                        </h4>
                        
                        <div class="space-y-4 flex-1">
                            <!-- Sorting Options Grid -->
                            <div class="grid grid-cols-1 gap-3">
                                <!-- Nama -->
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-white transition duration-150 bg-white">
                                    <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                        <input type="checkbox" name="sort_fields[]" value="name" 
                                               {{ in_array('name', request('sort_fields', [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm font-medium text-gray-700">Nama</span>
                                    </label>
                                    <select name="sort_directions[]" class="text-sm border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 transition duration-200" 
                                            {{ !in_array('name', request('sort_fields', [])) ? 'disabled' : '' }}>
                                        <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'name') ? 'selected' : '' }}>A → Z</option>
                                        <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'name') ? 'selected' : '' }}>Z → A</option>
                                    </select>
                                </div>

                                <!-- Tanggal Bergabung -->
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-white transition duration-150 bg-white">
                                    <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                        <input type="checkbox" name="sort_fields[]" value="join_date" 
                                               {{ in_array('join_date', request('sort_fields', [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm font-medium text-gray-700">Tanggal Bergabung</span>
                                    </label>
                                    <select name="sort_directions[]" class="text-sm border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 transition duration-200"
                                            {{ !in_array('join_date', request('sort_fields', [])) ? 'disabled' : '' }}>
                                        <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'join_date') ? 'selected' : '' }}>Terlama → Terbaru</option>
                                        <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'join_date') ? 'selected' : '' }}>Terbaru → Terlama</option>
                                    </select>
                                </div>

                                <!-- Divisi -->
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-white transition duration-150 bg-white">
                                    <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                        <input type="checkbox" name="sort_fields[]" value="division" 
                                               {{ in_array('division', request('sort_fields', [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm font-medium text-gray-700">Divisi</span>
                                    </label>
                                    <select name="sort_directions[]" class="text-sm border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 transition duration-200"
                                            {{ !in_array('division', request('sort_fields', [])) ? 'disabled' : '' }}>
                                        <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'division') ? 'selected' : '' }}>A → Z</option>
                                        <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'division') ? 'selected' : '' }}>Z → A</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Info Text -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-auto">
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs text-blue-700">Pilih satu atau lebih field untuk sorting. Data akan diurutkan berdasarkan prioritas dari atas ke bawah.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4 mt-4 border-t border-gray-200">
                            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Terapkan Filter
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="flex-1 bg-gray-500 text-white px-4 py-3 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Reset All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

   {{-- ================================================== --}}
{{-- 3. TABLE SECTION --}}
{{-- ================================================== --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 mt-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        {{-- Table Header dengan Background Biru --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <h4 class="font-bold text-white text-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Daftar Pengguna ({{ $users->total() }})
                </h4>
                <span class="bg-blue-500/30 text-blue-100 px-3 py-1 rounded-full text-sm font-medium border border-blue-400/30">
                    {{ $users->count() }} ditampilkan
                </span>
            </div>
        </div>

        {{-- Table Content --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-blue-50/80 backdrop-blur-sm">
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Nama Lengkap
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                            Username
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                            Divisi
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                            Bergabung
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                            Masa Kerja
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200/60">
                    @forelse ($users as $user)
                        <tr class="hover:bg-blue-50/30 transition-all duration-200 group border-b border-gray-100 last:border-b-0">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    {{-- Avatar dengan Profile Photo --}}
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-sm font-bold mr-3 group-hover:scale-110 transition-transform shadow-sm overflow-hidden border-2 border-white">
                                        @if ($user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <span class="text-white">
                                                {{ substr($user->name, 0, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono text-gray-600 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 font-medium">
                                    {{ $user->username }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold 
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 border border-red-200 shadow-sm' : 
                                      ($user->role === 'hrd' ? 'bg-purple-100 text-purple-800 border border-purple-200 shadow-sm' : 
                                      ($user->role === 'ketua_divisi' ? 'bg-amber-100 text-amber-800 border border-amber-200 shadow-sm' : 'bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm')) }}">
                                    <span class="w-2 h-2 rounded-full 
                                        {{ $user->role === 'admin' ? 'bg-red-500' : 
                                          ($user->role === 'hrd' ? 'bg-purple-500' : 
                                          ($user->role === 'ketua_divisi' ? 'bg-amber-500' : 'bg-emerald-500')) }} mr-2">
                                    </span>
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($user->division)
                                    <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg border border-blue-200 text-xs font-medium shadow-sm">
                                        {{ $user->division->name }}
                                    </span>
                                @elseif($user->leadingDivision)
                                    <span class="bg-amber-50 text-amber-700 px-3 py-1.5 rounded-lg border border-amber-200 text-xs font-medium shadow-sm">
                                        {{ $user->leadingDivision->name }}
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-medium">
                                        Tidak ada divisi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600 font-medium">
                                    {{ $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('d/m/Y') : '-' }}
                                </div>
                                @if($user->join_date)
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($user->join_date)->diffForHumans() }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium {{ $user->months_of_work < 12 ? 'text-amber-600' : 'text-emerald-600' }}">
                                    {{ $user->employment_period }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $user->months_of_work }} bulan
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold 
                                    {{ $user->active_status ? 'bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm' : 'bg-red-100 text-red-800 border border-red-200 shadow-sm' }}">
                                    <span class="w-2 h-2 rounded-full {{ $user->active_status ? 'bg-emerald-500' : 'bg-red-500' }} mr-2"></span>
                                    {{ $user->active_status ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <!-- TOMBOL EDIT -->
                                    @if($user->role !== 'admin')
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 px-3 py-2 rounded-lg hover:bg-blue-50 transition duration-200 flex items-center gap-2 text-xs font-medium border border-blue-200 bg-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                    @elseif($user->id == auth()->id())
                                        <a href="{{ route('profile.edit') }}" 
                                           class="text-emerald-600 hover:text-emerald-900 px-3 py-2 rounded-lg hover:bg-emerald-50 transition duration-200 flex items-center gap-2 text-xs font-medium border border-emerald-200 bg-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Profil Saya
                                        </a>
                                    @else
                                        <span class="text-gray-400 cursor-not-allowed px-3 py-2 text-xs border border-gray-200 bg-gray-50 rounded-lg" title="Tidak boleh mengedit Admin lain">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Edit
                                        </span>
                                    @endif
                                    
                                    <!-- TOMBOL DELETE -->
                                    @if($user->id != auth()->id() && in_array($user->role, ['karyawan', 'ketua_divisi']))
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 px-3 py-2 rounded-lg hover:bg-red-50 transition duration-200 flex items-center gap-2 text-xs font-medium border border-red-200 bg-white shadow-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 cursor-not-allowed px-3 py-2 text-xs border border-gray-200 bg-gray-50 rounded-lg" title="Tidak boleh menghapus Admin/HRD/akun sendiri">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Hapus
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="text-center text-gray-500">
                                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada data pengguna</h3>
                                    <p class="mt-2 text-sm text-gray-500">Coba ubah filter atau tambah pengguna baru.</p>
                                    <a href="{{ route('admin.users.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Tambah Pengguna Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

    <!-- JavaScript untuk Multi-Sorting -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortCheckboxes = document.querySelectorAll('input[name="sort_fields[]"]');
        const resetSortBtn = document.querySelector('a[href="{{ route('admin.users.index') }}"]');

        // Enable/disable select based on checkbox state
        sortCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const parentDiv = this.closest('.flex.items-center.justify-between');
                const select = parentDiv.querySelector('select');
                select.disabled = !this.checked;
                
                // Reset select value when unchecked
                if (!this.checked) {
                    select.value = 'asc';
                }
            });
        });

        // Initialize disabled state on page load
        sortCheckboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                const parentDiv = checkbox.closest('.flex.items-center.justify-between');
                const select = parentDiv.querySelector('select');
                select.disabled = true;
            }
        });
    });
    </script>
</x-app-layout>