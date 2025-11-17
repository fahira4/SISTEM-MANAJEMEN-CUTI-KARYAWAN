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

                    {{-- Formulir --}}
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div>
                            <label for="name">Nama Lengkap</label>
                            <input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="email">Email</label>
                            <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                        </div>

                        <div class="mt-4">
                            <label for="password">Password</label>
                            <input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        </div>

                        <div class="mt-4">
                            <label for="role">Peran (Role)</label>
                            <select id="role" name="role" class="block mt-1 w-full">
                                <option value="karyawan">Karyawan</option>
                                <option value="ketua_divisi">Ketua Divisi</option>
                                <option value="hrd">HRD</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="division_id">Divisi</label>
                            <select id="division_id" name="division_id" class="block mt-1 w-full">
                                <option value="">(Tidak ada divisi)</option>
                                {{-- Kita akan isi dropdown ini dari database --}}
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Pengguna
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>