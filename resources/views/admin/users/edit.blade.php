<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Formulir Edit --}}
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT') {{-- Kirim sebagai request PUT --}}

                        <div>
                            <label for="name">Nama Lengkap</label>
                            <input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="email">Email</label>
                            <input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                        </div>

                        <div class="mt-4">
                            <label for="password">Password</label>
                            <input id="password" class="block mt-1 w-full" type="password" name="password" />
                            <small class="text-gray-500">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>

                        <div class="mt-4">
                            <label for="role">Peran (Role)</label>
                            <select id="role" name="role" class="block mt-1 w-full">
                                {{-- Hapus 'admin' sesuai aturan kita --}}
                                <option value="karyawan" @selected(old('role', $user->role) == 'karyawan')>Karyawan</option>
                                <option value="ketua_divisi" @selected(old('role', $user->role) == 'ketua_divisi')>Ketua Divisi</option>
                                <option value="hrd" @selected(old('role', $user->role) == 'hrd')>HRD</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="division_id">Divisi</label>
                            <select id="division_id" name="division_id" class="block mt-1 w-full">
                                <option value="">(Tidak ada divisi)</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}" @selected(old('division_id', $user->division_id) == $division->id)>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>