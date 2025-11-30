<x-app-layout>
    <div class="relative bg-blue-900 min-h-[40vh] overflow-hidden">
       
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
           
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div class="text-white mt-8">
                    <h2 class="text-4xl font-bold tracking-tight">Tambah Pengguna Baru</h2>
                    <p class="text-blue-200 text-lg mt-2">
                        Buat akun pengguna baru untuk sistem C-OPS
                    </p>
                </div>
                
                <div class="hidden md:block">
                    <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                        <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                        <span class="text-blue-100 text-sm font-medium">Admin Panel</span>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm animate-fade-in-down">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-12 relative z-10">
        <div class="bg-white rounded-xl shadow-sm border-b-4 border-blue-600 overflow-hidden">
            
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h4 class="font-bold text-gray-700 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                    Form Tambah Pengguna Baru
                </h4>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                       
                        <div>
                            <label for="username" class="block font-medium text-sm text-gray-700 mb-2">Username *</label>
                            <input id="username" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200" 
                                   type="text" name="username" value="{{ old('username') }}" required />
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 mb-2">Nama Lengkap *</label>
                            <input id="name" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200" 
                                   type="text" name="name" value="{{ old('name') }}" required />
                        </div>

                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700 mb-2">Email *</label>
                            <input id="email" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200" 
                                   type="email" name="email" value="{{ old('email') }}" required />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block font-medium text-sm text-gray-700 mb-2">Password *</label>
                            <input id="password" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200" 
                                   type="password" name="password" required />
                            <p class="text-xs text-gray-500 mt-2">Minimal 8 karakter</p>
                        </div>

                        <div>
                            <label for="join_date" class="block font-medium text-sm text-gray-700 mb-2">
                                Tanggal Bergabung *
                            </label>
                            <input type="date" 
                                name="join_date" 
                                id="join_date"
                                value="{{ old('join_date', date('Y-m-d')) }}"
                                class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200"
                                required>
                            <p class="text-xs text-gray-500 mt-2">
                                Tanggal ketika karyawan resmi bergabung. Untuk menentukan eligibility cuti tahunan.
                            </p>
                        </div>

                        <div>
                            <label for="role" class="block font-medium text-sm text-gray-700 mb-2">Peran (Role) *</label>
                            <select id="role" name="role" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200" required>
                                <option value="">Pilih Role</option>
                                <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                <option value="ketua_divisi" {{ old('role') == 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi</option>
                                <option value="hrd" {{ old('role') == 'hrd' ? 'selected' : '' }} {{ \App\Models\User::where('role', 'hrd')->exists() ? 'disabled' : '' }}>
                                    HRD {{ \App\Models\User::where('role', 'hrd')->exists() ? '(Sudah Ada)' : '' }}
                                </option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }} {{ \App\Models\User::where('role', 'admin')->exists() ? 'disabled' : '' }}>
                                    Admin {{ \App\Models\User::where('role', 'admin')->exists() ? '(Sudah Ada)' : '' }}
                                </option>
                            </select>
                            @if(\App\Models\User::where('role', 'admin')->exists())
                                <p class="text-xs text-red-500 mt-2">Admin sudah ada dalam sistem</p>
                            @endif
                            @if(\App\Models\User::where('role', 'hrd')->exists())
                                <p class="text-xs text-red-500 mt-2">HRD sudah ada dalam sistem</p>
                            @endif
                        </div>

                        <div>
                            <label for="annual_leave_quota" class="block font-medium text-sm text-gray-700 mb-2">Kuota Cuti Awal (Hari)</label>
                            <input id="annual_leave_quota" 
                                class="w-full border-gray-300 bg-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200" 
                                type="number" 
                                name="annual_leave_quota" 
                                value="12" 
                                readonly 
                                required />
                            <p class="text-sm text-gray-500 mt-2">Default: 12 hari kerja per tahun (read-only)</p>
                            <input type="hidden" name="annual_leave_quota" value="12">
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p class="text-sm text-blue-700 font-medium">Informasi Status</p>
                                <p class="text-sm text-blue-600 mt-1">User baru akan secara otomatis berstatus <strong>Aktif</strong> dan bisa langsung login.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition duration-200">
                            Batal
                        </a>
                        
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium border border-blue-700 transition duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Pengguna Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>