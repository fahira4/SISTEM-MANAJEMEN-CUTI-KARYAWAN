<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Header Konten & Tombol Tambah -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Semua Pengguna</h3>
                        
                        <a href="{{ route('admin.users.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                           style="background-color: #1f2937;"> 
                            + Tambah Pengguna Baru
                        </a>
                    </div>

                    <!-- Form Wrapper -->
                    <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
                        <!-- KONSEP C: Side-by-Side Layout dengan equal height -->
                        <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                            
                            <!-- Filter Card -->
                            <div class="bg-white p-5 border border-gray-200 rounded-lg shadow-sm flex flex-col">
                                <h4 class="font-medium text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    Filter Data Pengguna
                                </h4>
                                <div class="grid grid-cols-1 gap-4 flex-1">
                                    <!-- Filter Role -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                        <select name="role" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Semua Role</option>
                                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="karyawan" {{ request('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                            <option value="ketua_divisi" {{ request('role') == 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi</option>
                                            <option value="hrd" {{ request('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                        </select>
                                    </div>

                                    <!-- Filter Status Aktif -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select name="active_status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Semua Status</option>
                                            <option value="1" {{ request('active_status') == '1' ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ request('active_status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>

                                    <!-- Filter Divisi -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                                        <select name="division_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Masa Kerja</label>
                                        <select name="employment_period" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

                            <!-- Sort Card - MODERN VERSION -->
                            <div class="bg-white p-5 border border-gray-200 rounded-lg shadow-sm flex flex-col">
                                <h4 class="font-medium text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                                    </svg>
                                    Urutkan Data
                                </h4>
                                
                                <div class="space-y-4 flex-1">
                                    <!-- Sorting Options Grid -->
                                    <div class="grid grid-cols-1 gap-3">
                                        <!-- Nama -->
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                            <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                                <input type="checkbox" name="sort_fields[]" value="name" 
                                                       {{ in_array('name', request('sort_fields', [])) ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="text-sm font-medium text-gray-700">Nama</span>
                                            </label>
                                            <select name="sort_directions[]" class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" 
                                                    {{ !in_array('name', request('sort_fields', [])) ? 'disabled' : '' }}>
                                                <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'name') ? 'selected' : '' }}>A → Z</option>
                                                <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'name') ? 'selected' : '' }}>Z → A</option>
                                            </select>
                                        </div>

                                        <!-- Tanggal Bergabung -->
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                            <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                                <input type="checkbox" name="sort_fields[]" value="join_date" 
                                                       {{ in_array('join_date', request('sort_fields', [])) ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="text-sm font-medium text-gray-700">Tanggal Bergabung</span>
                                            </label>
                                            <select name="sort_directions[]" class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                                    {{ !in_array('join_date', request('sort_fields', [])) ? 'disabled' : '' }}>
                                                <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'join_date') ? 'selected' : '' }}>Terlama → Terbaru</option>
                                                <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'join_date') ? 'selected' : '' }}>Terbaru → Terlama</option>
                                            </select>
                                        </div>

                                        <!-- Divisi -->
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                            <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                                <input type="checkbox" name="sort_fields[]" value="division" 
                                                       {{ in_array('division', request('sort_fields', [])) ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="text-sm font-medium text-gray-700">Divisi</span>
                                            </label>
                                            <select name="sort_directions[]" class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
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
                                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium">
                                        Terapkan Filter & Sorting
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium text-center">
                                        Reset All
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Pesan Sukses/Error -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <!-- Tabel -->
                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Lengkap
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Username
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Divisi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bergabung
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Masa Kerja
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-mono text-gray-900">{{ $user->username }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 
                                                  ($user->role === 'hrd' ? 'bg-purple-100 text-purple-800' : 
                                                  ($user->role === 'ketua_divisi' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->division->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->employment_period }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $user->active_status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->active_status ? 'Aktif' : 'Non-Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <!-- TOMBOL EDIT -->
                                                @if($user->role !== 'admin')
                                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                @elseif($user->id == auth()->id())
                                                    <a href="{{ route('profile.edit') }}" class="text-green-600 hover:text-green-900 mr-3 font-medium">
                                                        ✏️ Edit Profil Saya
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 cursor-not-allowed mr-3" title="Tidak boleh mengedit Admin lain">Edit 🔒</span>
                                                @endif
                                                
                                                <!-- TOMBOL DELETE - SESUAI REQUIREMENTS: HANYA KARYAWAN & KETUA DIVISI -->
                                                @if($user->id != auth()->id() && in_array($user->role, ['karyawan', 'ketua_divisi']))
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?')">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400 cursor-not-allowed" title="Tidak boleh menghapus Admin/HRD/akun sendiri">Hapus</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-8 text-center">
                                            <div class="text-center text-gray-500">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data pengguna</h3>
                                                <p class="mt-1 text-sm text-gray-500">Coba ubah filter atau tambah pengguna baru.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="mt-6">
                            {{ $users->withQueryString()->links() }}
                        </div>
                    @endif

                </div>
            </div>
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