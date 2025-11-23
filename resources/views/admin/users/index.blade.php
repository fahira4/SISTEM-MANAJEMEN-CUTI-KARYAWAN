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
                    
                    {{-- Header Konten & Tombol Tambah --}}
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Semua Pengguna</h3>
                        
                        <a href="{{ route('admin.users.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                           style="background-color: #1f2937;"> 
                            + Tambah Pengguna Baru
                        </a>
                    </div>

                    {{-- Filter Section --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Filter Pengguna</h4>
                        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            {{-- Filter Role --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Role</label>
                                <select name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="karyawan" {{ request('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="ketua_divisi" {{ request('role') == 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi</option>
                                    <option value="hrd" {{ request('role') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                </select>
                            </div>

                            {{-- Filter Status Aktif --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Status</label>
                                <select name="active_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('active_status') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ request('active_status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>

                            {{-- Filter Divisi --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Divisi</label>
                                <select name="division_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Semua Divisi</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tombol Filter --}}
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Terapkan Filter
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

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

                    {{-- Tabel --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Username
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Lengkap
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
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-mono text-gray-900">{{ $user->username }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
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
                                            {{ $user->join_date ? $user->join_date->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $user->active_status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->active_status ? 'Aktif' : 'Non-Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    {{-- Edit Button dengan Protection --}}
    @if($user->role !== 'admin')
        {{-- ✅ BISA EDIT: User bukan admin --}}
        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
    @elseif($user->id == auth()->id())
        {{-- ✅ ADMIN EDIT DIRI SENDIRI: Redirect ke profile page --}}
        <a href="{{ route('profile.edit') }}" class="text-green-600 hover:text-green-900 mr-3 font-medium">
            ✏️ Edit Profil Saya
        </a>
    @else
        {{-- ❌ TIDAK BISA EDIT: Admin lain --}}
        <span class="text-gray-400 cursor-not-allowed mr-3" title="Tidak boleh mengedit Admin lain">Edit 🔒</span>
    @endif
    
    {{-- Delete Button dengan Protection --}}
    @if($user->id != auth()->id() && !in_array($user->role, ['admin', 'hrd']))
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?')">
                Hapus
            </button>
        </form>
    @else
        <span class="text-gray-400 cursor-not-allowed" title="Tidak boleh menghapus Admin/HRD">Hapus</span>
    @endif
</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Belum ada data pengguna.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($users->hasPages())
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>