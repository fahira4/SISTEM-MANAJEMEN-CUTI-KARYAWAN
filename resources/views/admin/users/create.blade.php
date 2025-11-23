<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
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

                    {{-- Formulir --}}
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Username -->
                            <div>
                                <label for="username" class="block font-medium text-sm text-gray-700">Username *</label>
                                <input id="username" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                       type="text" name="username" value="{{ old('username') }}" required />
                                <p class="text-xs text-gray-500 mt-1">Huruf kecil, angka, titik, underscore. Contoh: budi.santoso</p>
                                @error('username')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Lengkap -->
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nama Lengkap *</label>
                                <input id="name" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                       type="text" name="name" value="{{ old('name') }}" required />
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email *</label>
                                <input id="email" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                       type="email" name="email" value="{{ old('email') }}" required />
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block font-medium text-sm text-gray-700">Password *</label>
                                <input id="password" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                       type="password" name="password" required />
                                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                            </div>

                            <!-- Role (Peran) -->
                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700">Peran (Role) *</label>
                                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Pilih Role</option>
                                    <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="ketua_divisi" {{ old('role') == 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi</option>
                                    <option value="hrd" {{ old('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>

                            <!-- Kuota Cuti Awal -->
                            <div>
                                <label for="annual_leave_quota" class="block font-medium text-sm text-gray-700">Kuota Cuti Awal (Hari) *</label>
                                <input id="annual_leave_quota" 
                                       class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                       type="number" 
                                       name="annual_leave_quota" 
                                       value="{{ old('annual_leave_quota', 12) }}" 
                                       min="0" 
                                       max="365" 
                                       required />
                                <p class="text-sm text-gray-500 mt-1">Default: 12 hari kerja per tahun</p>
                            </div>
                        </div>

                        {{-- Informasi Status Aktif --}}
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700">
                                User baru akan secara otomatis berstatus <strong>Aktif</strong> dan bisa langsung login.
                            </p>
                        </div>

                        {{-- Tombol --}}
                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('admin.users.index') }}" 
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                                Batal
                            </a>
                            
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium border border-blue-700">
                                Simpan Pengguna Baru
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>