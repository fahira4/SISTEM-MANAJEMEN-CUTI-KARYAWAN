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

                    {{-- Pesan Error --}}
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

                    {{-- Form Tambah Divisi --}}
                    <form method="POST" action="{{ route('admin.divisions.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Nama Divisi -->
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nama Divisi *</label>
                                <input id="name" 
                                       name="name" 
                                       type="text" 
                                       value="{{ old('name') }}"
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       required
                                       autofocus>
                                <p class="text-xs text-gray-500 mt-1">Nama divisi harus unik dan tidak boleh sama dengan divisi lain.</p>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi Divisi</label>
                                <textarea id="description" 
                                          name="description" 
                                          rows="3"
                                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Deskripsi opsional tentang divisi ini.</p>
                            </div>

                            <!-- Ketua Divisi -->
                            <div>
                                <label for="leader_id" class="block font-medium text-sm text-gray-700">Ketua Divisi *</label>
                                <select name="leader_id" id="leader_id" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required>
                                    <option value="">-- Pilih Ketua Divisi --</option>
                                    @foreach($availableLeaders as $leader)
                                        <option value="{{ $leader->id }}" {{ old('leader_id') == $leader->id ? 'selected' : '' }}>
                                            {{ $leader->name }} ({{ $leader->email }})
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Pesan jika tidak ada ketua divisi available --}}
                                @if($availableLeaders->count() == 0)
                                    <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                        <p class="text-yellow-700 text-sm">
                                            ⚠️ Tidak ada ketua divisi yang tersedia. 
                                            <a href="{{ route('admin.users.create') }}" class="underline font-medium">
                                                Buat user ketua divisi baru
                                            </a> 
                                            atau ubah role user yang sudah ada.
                                        </p>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-500 mt-1">
                                        Pilih ketua divisi dari daftar yang tersedia. Hanya ketua divisi yang belum memimpin divisi lain yang ditampilkan.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.divisions.index') }}" 
                               class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out mr-4">
                                Batal
                            </a>
                            
                            <button type="submit" 
                                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium">
                                Simpan Divisi Baru
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>