<x-app-layout>
    <div class="relative bg-blue-900 pb-24 pt-8 overflow-hidden">
        
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
           
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="text-white">
                    <h2 class="text-4xl font-bold tracking-tight">Edit Pengguna</h2>
                    <p class="text-blue-100 text-sm mt-1 flex items-center gap-2">
                        <a href="{{ route('admin.users.index') }}" class="hover:text-white hover:underline transition">Manajemen User</a>
                        <span>/</span>
                        <span class="font-semibold text-white">{{ $user->name }}</span>
                    </p>
                </div>
                
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white hover:bg-white hover:text-blue-900 transition-all duration-200 backdrop-blur-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-white px-4 py-3 rounded-lg backdrop-blur-md flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-500/20 border border-red-500/30 text-white px-4 py-3 rounded-lg backdrop-blur-md shadow-sm">
                    <div class="flex items-center mb-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <strong class="font-medium">Periksa kembali input Anda:</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm opacity-90 ml-7">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-12 relative z-10">
        
        @if($user->role === 'admin' && $user->id !== auth()->id())
            <div class="bg-white rounded-xl shadow-lg border-b-4 border-red-500 p-8 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Akses Ditolak</h3>
                <p class="text-gray-600 mb-6">Anda tidak memiliki izin untuk mengedit data sesama Administrator.</p>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                    Kembali ke Daftar
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg border-b-4 border-blue-600 overflow-hidden">
                
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Form Data Pengguna</h3>
                        <p class="text-sm text-gray-500">Perbarui informasi detail akun dan hak akses.</p>
                    </div>
                    
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $user->active_status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $user->active_status ? 'User Aktif' : 'Non-Aktif' }}
                    </span>
                </div>

                <div class="p-6 md:p-8">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                     
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <div class="space-y-6">
                                <h4 class="text-sm font-bold text-blue-600 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Informasi Akun</h4>
                                
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition" required>
                                    </div>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition" required>
                                    </div>
                                </div>

                                <div>
                                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                        </div>
                                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition" required>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Gunakan huruf kecil dan angka tanpa spasi.</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <h4 class="text-sm font-bold text-blue-600 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Data Kepegawaian</h4>

                                <div>
                                    <label for="join_date" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Bergabung <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <input type="date" name="join_date" id="join_date" value="{{ old('join_date', optional($user->join_date)->format('Y-m-d')) }}" class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition" required>
                                    </div>
                                   
                                    <div class="mt-2 text-xs flex items-center gap-2">
                                        <span class="text-gray-500">Masa Kerja:</span>
                                        @if($user->join_date)
                                            <span class="font-bold text-gray-700">{{ $user->months_of_work }} Bulan</span>
                                            @if($user->months_of_work >= 12)
                                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-[10px] font-bold">Eligible Cuti</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-[10px] font-bold">Belum Eligible</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Peran (Role) <span class="text-red-500">*</span></label>
                                    @if($user->role === 'admin')
                                        <div class="relative">
                                            <input type="text" value="Administrator" class="w-full bg-gray-100 text-gray-500 border-gray-300 rounded-lg cursor-not-allowed" readonly>
                                            <input type="hidden" name="role" value="admin">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            </div>
                                        </div>
                                        <p class="text-xs text-red-500 mt-1">Role Admin tidak dapat diubah di sini.</p>
                                    @else
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </div>
                                            <select id="role" name="role" class="pl-10 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition" required>
                                                <option value="karyawan" {{ old('role', $user->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                                <option value="ketua_divisi" {{ old('role', $user->role) == 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi</option>
                                                <option value="hrd" {{ old('role', $user->role) == 'hrd' ? 'selected' : '' }} {{ \App\Models\User::where('role', 'hrd')->where('id', '!=', $user->id)->exists() ? 'disabled' : '' }}>
                                                    HRD {{ \App\Models\User::where('role', 'hrd')->where('id', '!=', $user->id)->exists() ? '(Sudah Ada)' : '' }}
                                                </option>
                                                <option value="admin" disabled class="bg-gray-100 text-gray-400">Admin (Gunakan menu lain)</option>
                                            </select>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Akun</label>
                                    <div class="flex items-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="active_status" value="1" class="sr-only peer" {{ old('active_status', $user->active_status) ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            <span class="ml-3 text-sm font-medium text-gray-700">Akun Aktif</span>
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Non-aktifkan akun jika karyawan sudah tidak bekerja.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition shadow-sm">
                                Batal
                            </a>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>