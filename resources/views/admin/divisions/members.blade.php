<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Angg') }}ota: {{ $division->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Bagian 1: Formulir Tambah Anggota --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Tambah Anggota Baru</h3>

                    {{-- Tampilkan pesan error atau sukses --}}
                    @if (session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 text-red-600">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.divisions.members.add', $division->id) }}">
                        @csrf
                        <div class="flex items-center space-x-4">
                            <div class="flex-grow">
                                <label for="user_id" class="sr-only">Pilih Karyawan</label>
                                <select id="user_id" name="user_id" class="block mt-1 w-full">
                                    <option value="">Pilih Karyawan (Tanpa Divisi)</option>
                                    @forelse ($unassignedEmployees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->email }})</option>
                                    @empty
                                        <option value="" disabled>Semua karyawan sudah punya divisi</option>
                                    @endforelse
                                </select>
                            </div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Tambahkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bagian 2: Tabel Anggota Saat Ini --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Anggota Saat Ini ({{ $division->members->count() }})</h3>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($division->members as $member)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->role }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{-- Nanti kita bisa tambahkan tombol "Keluarkan dari Divisi" di sini --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        Belum ada anggota di divisi ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>