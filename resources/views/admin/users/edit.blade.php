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
                    <h2 class="text-2xl font-bold tracking-tight">Edit Pengguna</h2>
                    <p class="text-blue-200 text-lg mt-1">
                        {{ $user->name }}
                    </p>
                </div>
                
                <div class="hidden md:block">
                    <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                        <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                        <span class="text-blue-100 text-sm font-medium">Admin Panel</span>
                    </div>
                </div>
            </div>

            {{-- Security Check --}}
            @if($user->role === 'admin' && $user->id !== auth()->id())
                <div class="mb-6 bg-red-500/20 border border-red-500/30 text-white px-4 py-3 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <strong>Access Denied!</strong> Anda tidak boleh mengedit user Admin lain.
                </div>
            @endif

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

            {{-- Tampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-500/20 border border-red-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md text-sm shadow-sm animate-fade-in-down">
                    <strong class="font-medium">Whoops! Ada yang salah:</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- ================================================== --}}
    {{-- 2. FORM SECTION --}}
    {{-- ================================================== --}}
    @if($user->role === 'admin' && $user->id !== auth()->id())
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-12 relative z-10">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 text-center">
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                        ← Kembali ke Daftar Pengguna
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-12 relative z-10">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Form Header --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h4 class="font-bold text-gray-700 text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Form Edit Pengguna
                    </h4>
                </div>

                {{-- Form Content --}}
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Username -->
                            <div>
                                <label for="username" class="block font-medium text-sm text-gray-700 mb-2">Username *</label>
                                <input id="username" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200" 
                                       type="text" name="username" value="{{ old('username', $user->username) }}" required />
                                <p class="text-xs text-gray-500 mt-2">Huruf kecil, angka, titik, underscore.</p>
                                @error('username')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Lengkap -->
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700 mb-2">Nama Lengkap *</label>
                                <input id="name" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200" 
                                       type="text" name="name" value="{{ old('name', $user->name) }}" required />
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700 mb-2">Email *</label>
                                <input id="email" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200" 
                                       type="email" name="email" value="{{ old('email', $user->email) }}" required />
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Bergabung -->
                            <div>
                                <label for="join_date" class="block font-medium text-sm text-gray-700 mb-2">
                                    Tanggal Bergabung *
                                </label>
                                <input type="date" 
                                    name="join_date" 
                                    id="join_date"
                                    value="{{ old('join_date', $user->join_date ? $user->join_date->format('Y-m-d') : date('Y-m-d')) }}"
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200"
                                    required>
                                <p class="text-xs text-gray-500 mt-2">
                                    Masa kerja: 
                                    @if($user->join_date)
                                        <strong>{{ $user->months_of_work }} bulan</strong>
                                        @if($user->months_of_work < 12)
                                            <span class="text-red-600">(Belum eligible cuti tahunan)</span>
                                        @else
                                            <span class="text-green-600">(Sudah eligible cuti tahunan)</span>
                                        @endif
                                    @else
                                        <span class="text-red-600">Belum di-set</span>
                                    @endif
                                </p>
                            </div>

                            <!-- Role (Peran) -->
                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700 mb-2">Peran (Role) *</label>
                                @if($user->role === 'admin')
                                    {{-- Untuk admin, tampilkan readonly --}}
                                    <input type="text" value="Admin" class="w-full border-gray-300 bg-gray-100 rounded-lg shadow-sm" readonly>
                                    <input type="hidden" name="role" value="admin">
                                    <p class="text-xs text-red-500 mt-2">Role Admin tidak dapat diubah.</p>
                                @else
                                    {{-- Untuk user lain, tampilkan dropdown --}}
                                    <select id="role" name="role" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200" required>
                                        <option value="">Pilih Role</option>
                                        <option value="karyawan" {{ old('role', $user->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                        <option value="ketua_divisi" {{ old('role', $user->role) == 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi</option>
                                        <option value="hrd" {{ old('role', $user->role) == 'hrd' ? 'selected' : '' }} {{ \App\Models\User::where('role', 'hrd')->where('id', '!=', $user->id)->exists() ? 'disabled' : '' }}>
                                            HRD {{ \App\Models\User::where('role', 'hrd')->where('id', '!=', $user->id)->exists() ? '(Sudah Ada)' : '' }}
                                        </option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }} {{ \App\Models\User::where('role', 'admin')->exists() ? 'disabled' : '' }}>
                                            Admin {{ \App\Models\User::where('role', 'admin')->exists() ? '(Sudah Ada)' : '' }}
                                        </option>
                                    </select>
                                    @if(\App\Models\User::where('role', 'admin')->exists())
                                        <p class="text-xs text-red-500 mt-2">Admin sudah ada dalam sistem</p>
                                    @endif
                                    @if(\App\Models\User::where('role', 'hrd')->where('id', '!=', $user->id)->exists())
                                        <p class="text-xs text-red-500 mt-2">HRD sudah ada dalam sistem</p>
                                    @endif
                                @endif
                            </div>

                            <!-- Status Aktif -->
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <input id="active_status" name="active_status" type="checkbox" 
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                       value="1" {{ old('active_status', $user->active_status) ? 'checked' : '' }} />
                                <label for="active_status" class="ml-3 block font-medium text-sm text-gray-700">
                                    Status Aktif
                                </label>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                            <div>
                                <a href="{{ route('admin.users.index') }}" 
                                class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition duration-200 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    Kembali ke Daftar
                                </a>
                            </div>
                            
                            <div class="flex space-x-3">
                                {{-- Tombol Reset Form --}}
                                <button type="reset" 
                                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 ease-in-out">
                                    Reset Form
                                </button>
                                
                                {{-- Tombol Simpan --}}
                                <button type="submit" 
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>