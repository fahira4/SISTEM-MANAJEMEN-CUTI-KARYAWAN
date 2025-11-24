<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Anggota Divisi: ') }} {{ $division->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Breadcrumb --}}
                    <div class="mb-6 flex items-center text-sm text-gray-500">
                        <a href="{{ route('admin.divisions.index') }}" class="hover:text-gray-700">Divisi</a>
                        <span class="mx-2">/</span>
                        <a href="{{ route('admin.divisions.edit', $division->id) }}" class="hover:text-gray-700">{{ $division->name }}</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-900 font-medium">Anggota</span>
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

                    {{-- Informasi Divisi --}}
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $division->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Ketua: <span class="font-medium">{{ $division->leader->name ?? '-' }}</span>
                                    | Deskripsi: <span class="font-medium">{{ $division->description ?? 'Tidak ada deskripsi' }}</span>
                                </p>
                            </div>
                            <a href="{{ route('admin.divisions.edit', $division->id) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    Edit Divisi
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Kolom 1: Daftar Anggota --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Anggota Divisi ({{ $division->members->count() }})</h3>
                            
                            @if($division->members->count() > 0)
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bergabung</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                            </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($division->members as $member)
                                                        <tr>
                                                            <td class="px-4 py-3 whitespace-nowrap">
                                                                <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                                                <div class="text-xs text-gray-500">{{ $member->role }}</div>
                                                            </td>
                                                            <td class="px-4 py-3 whitespace-nowrap">
                                                                <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                                            </td>
                                                            <td class="px-4 py-3 whitespace-nowrap">
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    {{ $member->active_status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                    {{ $member->active_status ? 'Aktif' : 'Non-Aktif' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 whitespace-nowrap">
                                                                <div class="text-sm text-gray-500">
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
                                                                            class="text-red-600 hover:text-red-900 text-sm"
                                                                            onclick="return confirm('Apakah Anda yakin ingin mengeluarkan {{ $member->name }} dari divisi ini?')">
                                                                        Keluarkan
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada anggota</h3>
                                    <p class="mt-1 text-sm text-gray-500">Tambahkan anggota pertama ke divisi ini.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Kolom 2: Tambah Anggota --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Anggota Baru</h3>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <form method="POST" action="{{ route('admin.divisions.members.add', $division->id) }}">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Karyawan</label>
                                        <select id="user_id" 
                                                name="user_id" 
                                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
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
                                                class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium">
                                                Tambahkan ke Divisi
                                        </button>
                                        <p class="text-xs text-gray-500 mt-2 text-center">
                                            Tersedia {{ $unassignedEmployees->count() }} karyawan tanpa divisi
                                        </p>
                                    @else
                                        <button type="button" 
                                                disabled
                                                class="w-full bg-gray-400 text-white py-2 px-4 rounded-md cursor-not-allowed font-medium">
                                                Tidak ada karyawan tersedia
                                        </button>
                                        <p class="text-xs text-red-500 mt-2 text-center">
                                            Semua karyawan sudah memiliki divisi
                                        </p>
                                    @endif
                                </form>
                            </div>

                            {{-- Informasi --}}
                            <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                                <h4 class="text-sm font-medium text-yellow-800 mb-2">📝 Informasi</h4>
                                <ul class="text-sm text-yellow-700 space-y-1">
                                    <li>• Hanya karyawan tanpa divisi yang bisa ditambahkan</li>
                                    <li>• Ketua divisi otomatis menjadi atasan dari anggota</li>
                                    <li>• Anggota yang dikeluarkan akan menjadi tanpa divisi</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Kembali --}}
                    <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.divisions.index') }}" 
                           class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            ← Kembali ke Daftar Divisi
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>