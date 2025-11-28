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

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
            {{-- Header Text --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div class="text-white">
                    <h2 class="text-2xl font-bold tracking-tight">Kelola Anggota Divisi</h2>
                    <p class="text-blue-200 text-lg mt-1">
                        {{ $division->name }}
                    </p>
                </div>
                
                <div class="hidden md:block">
                    <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                        <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <span class="text-blue-100 text-sm font-medium">{{ $division->members->count() }} Anggota</span>
                    </div>
                </div>
            </div>

            {{-- Breadcrumb --}}
            <div class="flex items-center text-sm text-blue-200 mb-6">
                <a href="{{ route('admin.divisions.index') }}" class="hover:text-white transition duration-200">Divisi</a>
                <span class="mx-2">/</span>
                <a href="{{ route('admin.divisions.edit', $division->id) }}" class="hover:text-white transition duration-200">{{ $division->name }}</a>
                <span class="mx-2">/</span>
                <span class="text-white font-medium">Anggota</span>
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
    {{-- 2. CONTENT SECTION --}}
    {{-- ================================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-12 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Kolom 1: Daftar Anggota --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-white text-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            Daftar Anggota ({{ $division->members->count() }})
                        </h4>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-6">
                    @if($division->members->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-blue-50/80 backdrop-blur-sm">
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                            Nama
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                            Email
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                            Status
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                            Bergabung
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200/60">
                                    @foreach($division->members as $member)
                                        <tr class="hover:bg-blue-50/30 transition-all duration-200 group">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center text-xs font-bold mr-3">
                                                        {{ substr($member->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900">{{ $member->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $member->role }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm text-gray-600">{{ $member->email }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold 
                                                    {{ $member->active_status ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $member->active_status ? 'bg-emerald-500' : 'bg-red-500' }} mr-1"></span>
                                                    {{ $member->active_status ? 'Aktif' : 'Non-Aktif' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm text-gray-600">
                                                    {{ $member->join_date ? \Carbon\Carbon::parse($member->join_date)->format('d/m/Y') : '-' }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('admin.divisions.members.remove', ['division' => $division->id, 'user' => $member->id]) }}" 
                                                    method="POST" 
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 px-3 py-1 rounded-lg hover:bg-red-50 transition duration-200 flex items-center gap-1 text-xs font-medium border border-red-200 bg-white shadow-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin mengeluarkan {{ $member->name }} dari divisi ini?')">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Keluarkan
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada anggota</h3>
                            <p class="text-gray-500 text-sm">Tambahkan anggota pertama ke divisi ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kolom 2: Tambah Anggota & Informasi --}}
            <div class="space-y-6">
                {{-- Card Tambah Anggota --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
                        <h4 class="font-bold text-white text-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Anggota Baru
                        </h4>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.divisions.members.add', $division->id) }}">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="user_id" class="block font-medium text-sm text-gray-700 mb-2">Pilih Karyawan</label>
                                <select id="user_id" 
                                        name="user_id" 
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200"
                                        required>
                                    <option value="">Pilih karyawan...</option>
                                    @foreach($unassignedEmployees as $employee)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->name }} ({{ $employee->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if($unassignedEmployees->count() > 0)
                                <button type="submit" 
                                        class="w-full bg-emerald-600 text-white py-3 px-4 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambahkan ke Divisi
                                </button>
                                <p class="text-xs text-gray-500 mt-2 text-center">
                                    Tersedia {{ $unassignedEmployees->count() }} karyawan tanpa divisi
                                </p>
                            @else
                                <button type="button" 
                                        disabled
                                        class="w-full bg-gray-400 text-white py-3 px-4 rounded-lg cursor-not-allowed font-medium">
                                    Tidak ada karyawan tersedia
                                </button>
                                <p class="text-xs text-red-500 mt-2 text-center">
                                    Semua karyawan sudah memiliki divisi
                                </p>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- Card Informasi Divisi --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                        <h4 class="font-bold text-white text-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Informasi Divisi
                        </h4>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <h5 class="font-semibold text-gray-900 text-lg">{{ $division->name }}</h5>
                                <p class="text-sm text-gray-600 mt-1">
                                    <span class="font-medium">Ketua:</span> {{ $division->leader->name ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Deskripsi:</span> {{ $division->description ?? 'Tidak ada deskripsi' }}
                                </p>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <a href="{{ route('admin.divisions.edit', $division->id) }}" 
                                   class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit Divisi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Informasi Sistem --}}
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-amber-800 mb-2">📝 Informasi Sistem</h4>
                            <ul class="text-sm text-amber-700 space-y-1">
                                <li>• Hanya karyawan tanpa divisi yang bisa ditambahkan</li>
                                <li>• Ketua divisi otomatis menjadi atasan dari anggota</li>
                                <li>• Anggota yang dikeluarkan akan menjadi tanpa divisi</li>
                                <li>• Perubahan divisi mempengaruhi alur persetujuan cuti</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Kembali --}}
        <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.divisions.index') }}" 
               class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Divisi
            </a>
        </div>
    </div>
</x-app-layout>