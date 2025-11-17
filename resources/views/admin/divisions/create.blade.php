<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Divisi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Formulir --}}
                    <form method="POST" action="{{ route('admin.divisions.store') }}">
                        @csrf

                        <div>
                            <label for="name">Nama Divisi</label>
                            <input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="description">Deskripsi</label>
                            <textarea id="description" name="description" class="block mt-1 w-full"></textarea>
                        </div>
                        
                        <div class="mt-4">
                            <label for="leader_id">Ketua Divisi</label>
                            <select id="leader_id" name="leader_id" class="block mt-1 w-full">
                                <option value="">(Pilih Ketua Divisi)</option>
                                @foreach ($leaders as $leader)
                                    <option value="{{ $leader->id }}">{{ $leader->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Divisi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>