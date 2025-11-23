<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    {{-- Security Check --}}
@if($user->role === 'admin' && $user->id !== auth()->id())
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
        <strong>Access Denied!</strong> Anda tidak boleh mengedit user Admin.
    </div>
    <div class="flex justify-end mt-4">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Kembali</a>
    </div>
@else
    {{-- FORM EDIT DISINI --}}
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')
        
        {{-- Sisanya form yang sudah ada --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- ... form fields ... -->
        </div>
        
        {{-- Tombol Simpan --}}
        <div class="flex items-center justify-end mt-6">
            {{-- ... tombol ... --}}
        </div>
    </form>
@endif

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
                                <p class="text-xs text-gray-500 mt-1">Huruf kecil, angka, titik, underscore. Contoh: budi.santoso</p>
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

                            <!-- Password -->
                            <div>
                                <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                                <input id="password" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                       type="password" name="password" />
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password. Minimal 8 karakter.</p>
                            </div>

                            <!-- Tanggal Bergabung -->
                            <div>
                                <label for="join_date" class="block font-medium text-sm text-gray-700">Tanggal Bergabung *</label>
                                <input id="join_date" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                       type="date" name="join_date" value="{{ old('join_date', $user->join_date ? $user->join_date->format('Y-m-d') : '') }}" required />
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

                            <!-- Role (Peran) -->
                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700">Peran (Role) *</label>
                                
                                @if($user->role === 'admin')
                                    {{-- Untuk admin, tampilkan readonly --}}
                                    <input type="text" value="Admin" class="block mt-1 w-full border-gray-300 bg-gray-100 rounded-md shadow-sm" readonly>
                                    <input type="hidden" name="role" value="admin">
                                    <p class="text-xs text-red-500 mt-1">Role Admin tidak dapat diubah.</p>
                                @else
                                    {{-- Untuk user lain, tampilkan dropdown tanpa admin --}}
                                    <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Pilih Role</option>
                                        <option value="karyawan" {{ old('role', $user->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                        <option value="ketua_divisi" {{ old('role', $user->role) == 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi</option>
                                        <option value="hrd" {{ old('role', $user->role) == 'hrd' ? 'selected' : '' }}>HRD</option>
                                    </select>
                                @endif
                            </div>

                            <!-- Kuota Cuti -->
                            <div>
                                <label for="annual_leave_quota" class="block font-medium text-sm text-gray-700">Kuota Cuti Tahunan (Hari)</label>
                                <input id="annual_leave_quota" 
                                       class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                       type="number" 
                                       name="annual_leave_quota" 
                                       value="{{ old('annual_leave_quota', $user->annual_leave_quota) }}" 
                                       min="0" 
                                       max="365" 
                                       required />
                                <p class="text-sm text-gray-500 mt-1">Kuota cuti tahunan dalam hari kerja.</p>
                            </div>

                            <!-- Divisi -->
                            <div id="division-field">
                                <label for="division_id" class="block font-medium text-sm text-gray-700">Divisi</label>
                                <select id="division_id" name="division_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">(Belum ditempatkan)</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-sm text-gray-500 mt-1" id="division-note">
                                    * Hanya untuk Karyawan dan Ketua Divisi
                                </p>
                            </div>

                            <!-- Informasi Tambahan (Read-only) -->
                            <div class="md:col-span-2 p-4 bg-gray-50 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Sistem</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">ID Pengguna:</span>
                                        <span class="font-mono">{{ $user->id }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Dibuat:</span>
                                        <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Diupdate:</span>
                                        <span>{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Email Verified:</span>
                                        <span class="{{ $user->email_verified_at ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $user->email_verified_at ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                </div>
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

    {{-- JavaScript untuk Conditional Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const divisionField = document.getElementById('division-field');
            const divisionSelect = document.getElementById('division_id');
            const divisionNote = document.getElementById('division-note');

            function toggleDivisionField() {
                const selectedRole = roleSelect.value;
                
                if (selectedRole === 'admin' || selectedRole === 'hrd') {
                    divisionField.style.opacity = '0.6';
                    divisionSelect.disabled = true;
                    divisionSelect.value = '';
                    divisionNote.textContent = '* Admin dan HRD tidak membutuhkan divisi';
                    divisionNote.className = 'text-sm text-red-500 mt-1';
                } else if (selectedRole === 'ketua_divisi' || selectedRole === 'karyawan') {
                    divisionField.style.opacity = '1';
                    divisionSelect.disabled = false;
                    divisionNote.textContent = '* Wajib memiliki divisi';
                    divisionNote.className = 'text-sm text-red-500 mt-1';
                } else {
                    divisionField.style.opacity = '1';
                    divisionSelect.disabled = false;
                    divisionNote.textContent = '* Hanya untuk Karyawan dan Ketua Divisi';
                    divisionNote.className = 'text-sm text-gray-500 mt-1';
                }
            }

            // Initial state
            toggleDivisionField();
            
            // On change
            roleSelect.addEventListener('change', toggleDivisionField);

            // Reset form handler
            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                setTimeout(toggleDivisionField, 100); // Reset after form reset
            });
        });
    </script>
</x-app-layout>