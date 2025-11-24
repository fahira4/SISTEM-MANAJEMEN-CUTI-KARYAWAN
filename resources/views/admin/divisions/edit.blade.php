<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Divisi: ') }} {{ $division->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

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

                    {{-- Pesan Validasi Error --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            <strong class="font-medium">Error:</strong>
                            <ul class="mt-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Info Perubahan Ketua --}}
                    @if($division->leader_id != old('leader_id', $division->leader_id) && old('leader_id'))
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <p class="text-yellow-700 text-sm">
                                <strong>Perhatian:</strong> Mengganti ketua divisi akan mempengaruhi struktur organisasi.
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Form Edit Divisi --}}
                    <form method="POST" action="{{ route('admin.divisions.update', $division->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Nama Divisi -->
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nama Divisi *</label>
                                <input id="name" 
                                       name="name" 
                                       type="text" 
                                       value="{{ old('name', $division->name) }}"
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
                                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $division->description) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Deskripsi opsional tentang divisi ini.</p>
                            </div>

                            <!-- Ketua Divisi -->
                            <div>
                                <label for="leader_id" class="block font-medium text-sm text-gray-700">Ketua Divisi *</label>
                                <select id="leader_id" 
                                        name="leader_id" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required>
                                    <option value="">Pilih Ketua Divisi</option>
                                    @foreach($availableLeaders as $leader)
                                        <option value="{{ $leader->id }}" 
                                                {{ old('leader_id', $division->leader_id) == $leader->id ? 'selected' : '' }}>
                                            {{ $leader->name }} ({{ $leader->email }})
                                            @if($division->leader_id == $leader->id)
                                                - Saat ini
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($availableLeaders->count() > 0)
                                        Pilih user dengan role Ketua Divisi yang belum memimpin divisi lain.
                                    @else
                                        <span class="text-red-500">Tidak ada user dengan role Ketua Divisi yang tersedia.</span>
                                    @endif
                                </p>
                            </div>

                            <!-- Informasi Sistem (Read-only) -->
                            <div class="p-1 bg-white border border-gray-200 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Sistem</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Tanggal Dibentuk:</span>
                                        <span class="font-medium ml-2">{{ $division->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Jumlah Anggota:</span>
                                        <span class="font-medium ml-2">{{ $division->members->count() }} orang</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Diupdate Terakhir:</span>
                                        <span class="ml-2">{{ $division->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Informasi ini diisi otomatis oleh sistem.</p>
                            </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.divisions.index') }}" 
                            class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                ← Kembali ke Daftar
                            </a>
                            
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.divisions.members.show', $division->id) }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                        Kelola Anggota
                                </a>
                                
                                <button type="submit" 
                                        class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium">
                                        Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>