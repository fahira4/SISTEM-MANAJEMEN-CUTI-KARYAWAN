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
                    <h2 class="text-4xl font-bold tracking-tight">Manajemen Divisi</h2>
                    <p class="text-blue-200 text-lg mt-1">
                        Kelola semua divisi dan struktur organisasi
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="hidden md:block">
                        <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                            <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-blue-100 text-sm font-medium">Total: {{ $divisions->total() }} Divisi</span>
                        </div>
                    </div>

                    <a href="{{ route('admin.divisions.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white text-blue-900 font-bold rounded-lg shadow hover:bg-blue-50 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Divisi Baru
                    </a>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm animate-fade-in-down">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-500/20 border border-red-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm animate-fade-in-down">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
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
            <form method="GET" action="{{ route('admin.divisions.index') }}" id="filterForm">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                        
                        {{-- Filter Card --}}
                        <div class="bg-gray-50 p-5 border border-gray-200 rounded-lg flex flex-col">
                            <h4 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Filter Data Divisi
                            </h4>
                            <div class="grid grid-cols-1 gap-4 flex-1">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Divisi</label>
                                    <input type="text" 
                                           name="name" 
                                           value="{{ request('name') }}"
                                           placeholder="Cari nama divisi..."
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ketua Divisi</label>
                                    <select name="leader_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                                        <option value="">Semua Ketua</option>
                                        @foreach($leaders as $leader)
                                            <option value="{{ $leader->id }}" {{ request('leader_id') == $leader->id ? 'selected' : '' }}>
                                                {{ $leader->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Anggota</label>
                                    <select name="member_count" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                                        <option value="">Semua Jumlah</option>
                                        <option value="0" {{ request('member_count') === '0' ? 'selected' : '' }}>0 anggota</option>
                                        <option value="1-5" {{ request('member_count') === '1-5' ? 'selected' : '' }}>1-5 anggota</option>
                                        <option value="6-10" {{ request('member_count') === '6-10' ? 'selected' : '' }}>6-10 anggota</option>
                                        <option value="11+" {{ request('member_count') === '11+' ? 'selected' : '' }}>11+ anggota</option>
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
                                Multi Sorting
                            </h4>
                            
                            <div class="space-y-4 flex-1">
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-white transition duration-150 bg-white">
                                        <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                            <input type="checkbox" name="sort_fields[]" value="name" 
                                                   {{ in_array('name', request('sort_fields', [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm font-medium text-gray-700">Nama Divisi</span>
                                        </label>
                                        <select name="sort_directions[]" class="text-sm border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 transition duration-200" 
                                                {{ !in_array('name', request('sort_fields', [])) ? 'disabled' : '' }}>
                                            <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'name') ? 'selected' : '' }}>A → Z</option>
                                            <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'name') ? 'selected' : '' }}>Z → A</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-white transition duration-150 bg-white">
                                        <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                            <input type="checkbox" name="sort_fields[]" value="members_count" 
                                                   {{ in_array('members_count', request('sort_fields', [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm font-medium text-gray-700">Jumlah Anggota</span>
                                        </label>
                                        <select name="sort_directions[]" class="text-sm border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 transition duration-200"
                                                {{ !in_array('members_count', request('sort_fields', [])) ? 'disabled' : '' }}>
                                            <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'members_count') ? 'selected' : '' }}>Sedikit → Banyak</option>
                                            <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'members_count') ? 'selected' : '' }}>Banyak → Sedikit</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-white transition duration-150 bg-white">
                                        <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                            <input type="checkbox" name="sort_fields[]" value="created_at" 
                                                   {{ in_array('created_at', request('sort_fields', [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm font-medium text-gray-700">Tanggal Dibuat</span>
                                        </label>
                                        <select name="sort_directions[]" class="text-sm border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 transition duration-200"
                                                {{ !in_array('created_at', request('sort_fields', [])) ? 'disabled' : '' }}>
                                            <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'created_at') ? 'selected' : '' }}>Terlama → Terbaru</option>
                                            <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'created_at') ? 'selected' : '' }}>Terbaru → Terlama</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-auto">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs text-blue-700">Pilih satu atau lebih field untuk sorting. Data akan diurutkan berdasarkan prioritas dari atas ke bawah.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3 pt-4 mt-4 border-t border-gray-200">
                                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    Terapkan Filter
                                </button>
                                <a href="{{ route('admin.divisions.index') }}" class="flex-1 bg-gray-500 text-white px-4 py-3 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Daftar Divisi ({{ $divisions->total() }})
                    </h4>
                    <span class="bg-blue-500/30 text-blue-100 px-3 py-1 rounded-full text-sm font-medium border border-blue-400/30">
                        {{ $divisions->count() }} ditampilkan
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Nama Divisi
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                Ketua Divisi
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                Jumlah Anggota
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                Dibuat
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                Deskripsi
                            </th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200/60">
                        @forelse ($divisions as $division)
                            <tr class="hover:bg-blue-50/30 transition-all duration-200 group border-b border-gray-100 last:border-b-0">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white flex items-center justify-center text-sm font-bold mr-3 group-hover:scale-110 transition-transform shadow-sm">
                                            {{ substr($division->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $division->name }}</div>
                                            <div class="text-xs text-gray-500 mt-1">ID: {{ $division->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($division->leader)
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-500 to-amber-600 text-white flex items-center justify-center text-xs font-bold mr-2">
                                                {{ substr($division->leader->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $division->leader->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $division->leader->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Belum ada ketua</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.divisions.members.show', $division->id) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition duration-150 border border-blue-200 shadow-sm">
                                        {{ $division->members_count }} anggota
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 font-medium">
                                        {{ $division->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $division->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 max-w-xs truncate" title="{{ $division->description }}">
                                        {{ $division->description ?? 'Tidak ada deskripsi' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.divisions.edit', $division->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 px-3 py-2 rounded-lg hover:bg-blue-50 transition duration-200 flex items-center gap-2 text-xs font-medium border border-blue-200 bg-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        <a href="{{ route('admin.divisions.members.show', $division->id) }}" 
                                           class="text-emerald-600 hover:text-emerald-900 px-3 py-2 rounded-lg hover:bg-emerald-50 transition duration-200 flex items-center gap-2 text-xs font-medium border border-emerald-200 bg-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                            </svg>
                                            Anggota
                                        </a>

                                        @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('admin.divisions.destroy', $division->id) }}" 
                                                method="POST" 
                                                class="inline-block"
                                                onsubmit="return confirmDoubleDelete('{{ addslashes($division->name) }}', {{ $division->members->count() }})">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 px-3 py-2 rounded-lg hover:bg-red-50 transition duration-200 flex items-center gap-2 text-xs font-medium border border-red-200 bg-white shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-center text-gray-500">
                                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada divisi</h3>
                                        <p class="mt-2 text-sm text-gray-500">
                                            @if(request()->anyFilled(['name', 'leader_id', 'member_count']))
                                                Coba ubah filter pencarian Anda.
                                            @else
                                                Mulai dengan membuat divisi pertama Anda.
                                            @endif
                                        </p>
                                        <a href="{{ route('admin.divisions.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Tambah Divisi Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($divisions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                    {{ $divisions->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript untuk Multi-Sorting -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortCheckboxes = document.querySelectorAll('input[name="sort_fields[]"]');
        
        // Initialize disabled state on page load
        sortCheckboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                const parentDiv = checkbox.closest('.flex.items-center.justify-between');
                const select = parentDiv.querySelector('select');
                if (select) {
                    select.disabled = true;
                }
            }
        });

        // Enable/disable select based on checkbox state
        sortCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const parentDiv = this.closest('.flex.items-center.justify-between');
                const select = parentDiv.querySelector('select');
                if (select) {
                    select.disabled = !this.checked;
                    
                    if (!this.checked) {
                        select.value = 'asc';
                    }
                }
            });
        });
    });

    // Double confirmation delete function
    function confirmDoubleDelete(divisionName, memberCount) {
        // First confirmation
        const firstConfirm = confirm(`HAPUS DIVISI: "${divisionName}"\n\n• ${memberCount} anggota akan dikeluarkan\n• Data divisi akan hilang permanen\n\nLanjutkan penghapusan?`);
        
        if (!firstConfirm) {
            return false;
        }
        
        // Second confirmation
        const secondConfirm = confirm(`KONFIRMASI AKHIR!\n\nYakin hapus divisi "${divisionName}" secara PERMANEN?\n\n• ${memberCount} anggota akan kehilangan divisi\n• Tindakan ini TIDAK DAPAT DIBATALKAN\n\nTekan OK untuk hapus, Cancel untuk batal.`);
        
        return secondConfirm;
    }
    </script>
</x-app-layout>