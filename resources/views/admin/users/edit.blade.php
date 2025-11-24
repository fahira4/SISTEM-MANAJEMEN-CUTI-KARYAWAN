<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    {{-- Security Check --}}
    @if($user->role === 'admin' && $user->id !== auth()->id())
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <strong>Access Denied!</strong> Anda tidak boleh mengedit user Admin lain.
                </div>
                <div class="flex justify-end mt-4">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Kembali</a>
                </div>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        {{-- Pesan Sukses/Error --}}
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Tampilkan Error Validasi --}}
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                <strong class="font-medium">Whoops! Ada yang salah:</strong>
                                <ul class="mt-1 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Formulir Edit --}}
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Username -->
                                <div>
                                    <label for="username" class="block font-medium text-sm text-gray-700">Username *</label>
                                    <input id="username" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                           type="text" name="username" value="{{ old('username', $user->username) }}" required />
                                    <p class="text-xs text-gray-500 mt-1">Huruf kecil, angka, titik, underscore.</p>
                                    @error('username')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="name" class="block font-medium text-sm text-gray-700">Nama Lengkap *</label>
                                    <input id="name" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                           type="text" name="name" value="{{ old('name', $user->name) }}" required />
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block font-medium text-sm text-gray-700">Email *</label>
                                    <input id="email" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                           type="email" name="email" value="{{ old('email', $user->email) }}" required />
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="join_date" class="block text-gray-700 text-sm font-bold mb-2">
                                        Tanggal Bergabung *
                                    </label>
                                    <input type="date" 
                                        name="join_date" 
                                        id="join_date"
                                        value="{{ old('join_date', $user->join_date ? $user->join_date->format('Y-m-d') : date('Y-m-d')) }}"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">
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
                                    <label for="role" class="block font-medium text-sm text-gray-700">Peran (Role) *</label>
                                    @if($user->role === 'admin')
                                        {{-- Untuk admin, tampilkan readonly --}}
                                        <input type="text" value="Admin" class="block mt-1 w-full border-gray-300 bg-gray-100 rounded-md shadow-sm" readonly>
                                        <input type="hidden" name="role" value="admin">
                                        <p class="text-xs text-red-500 mt-1">Role Admin tidak dapat diubah.</p>
                                    @else
                                        {{-- Untuk user lain, tampilkan dropdown --}}
                                        <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
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
                                            <p class="text-xs text-red-500 mt-1">Admin sudah ada dalam sistem</p>
                                        @endif
                                        @if(\App\Models\User::where('role', 'hrd')->where('id', '!=', $user->id)->exists())
                                            <p class="text-xs text-red-500 mt-1">HRD sudah ada dalam sistem</p>
                                        @endif
                                    @endif
                                </div>

                                <!-- Status Aktif -->
                                <div class="flex items-center">
                                    <input id="active_status" name="active_status" type="checkbox" 
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                           value="1" {{ old('active_status', $user->active_status) ? 'checked' : '' }} />
                                    <label for="active_status" class="ml-2 block font-medium text-sm text-gray-700">
                                        Status Aktif
                                    </label>
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200">
                                <div>
                                    <a href="{{ route('admin.users.index') }}" 
                                    class="px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition duration-150 ease-in-out">
                                        ← Kembali ke Daftar
                                    </a>
                                </div>
                                
                                <div class="flex space-x-3">
                                    {{-- Tombol Reset Form --}}
                                    <button type="reset" 
                                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                        Reset Form
                                    </button>
                                    
                                    {{-- Tombol Simpan --}}
                                    <button type="submit" 
                                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>