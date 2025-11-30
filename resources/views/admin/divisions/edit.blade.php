<x-app-layout>
    <div class="relative bg-blue-900 min-h-[40vh] overflow-hidden">
       
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
           
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div class="text-white">
                    <h2 class="text-2xl font-bold tracking-tight">Edit Divisi</h2>
                    <p class="text-blue-200 text-lg mt-1">
                        {{ $division->name }}
                    </p>
                </div>
                
                <div class="hidden md:block">
                    <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                        <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="text-blue-100 text-sm font-medium">Admin Panel</span>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm animate-fade-in-down">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-500/20 border border-red-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm animate-fade-in-down">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-500/20 border border-red-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md text-sm shadow-sm animate-fade-in-down">
                    <strong class="font-medium">Error:</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($division->leader_id != old('leader_id', $division->leader_id) && old('leader_id'))
                <div class="mb-6 bg-yellow-500/20 border border-yellow-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md flex items-center text-sm shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <strong>Perhatian:</strong> Mengganti ketua divisi akan mempengaruhi struktur organisasi.
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-12 relative z-10">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h4 class="font-bold text-gray-700 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Form Edit Divisi
                </h4>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('admin.divisions.update', $division->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 mb-2">Nama Divisi *</label>
                            <input id="name" 
                                   name="name" 
                                   type="text" 
                                   value="{{ old('name', $division->name) }}"
                                   class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200"
                                   required
                                   autofocus>
                            <p class="text-xs text-gray-500 mt-2">Nama divisi harus unik dan tidak boleh sama dengan divisi lain.</p>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block font-medium text-sm text-gray-700 mb-2">Deskripsi Divisi</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200"
                                      placeholder="Deskripsi singkat tentang divisi ini...">{{ old('description', $division->description) }}</textarea>
                            <p class="text-xs text-gray-500 mt-2">Deskripsi opsional tentang divisi ini.</p>
                        </div>

                        <div>
                            <label for="leader_id" class="block font-medium text-sm text-gray-700 mb-2">Ketua Divisi *</label>
                            <select id="leader_id" 
                                    name="leader_id" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200"
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
                            <p class="text-xs text-gray-500 mt-2">
                                @if($availableLeaders->count() > 0)
                                    Pilih user dengan role Ketua Divisi yang belum memimpin divisi lain.
                                    @if($division->leader)
                                        <br><span class="text-blue-600 font-medium">Ketua saat ini: {{ $division->leader->name }}</span>
                                    @endif
                                @else
                                    <span class="text-red-500">Tidak ada user dengan role Ketua Divisi yang tersedia.</span>
                                @endif
                            </p>
                            @error('leader_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="w-full">
                                    <p class="text-sm text-blue-700 font-medium mb-2">Informasi Sistem</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-600">
                                        <div class="flex justify-between">
                                            <span>Tanggal Dibentuk:</span>
                                            <span class="font-medium">{{ $division->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Jumlah Anggota:</span>
                                            <span class="font-medium">{{ $division->members->count() }} orang</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Diupdate Terakhir:</span>
                                            <span>{{ $division->updated_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-blue-500 mt-2">Informasi ini diisi otomatis oleh sistem.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.divisions.index') }}" 
                        class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition duration-200 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Daftar
                        </a>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.divisions.members.show', $division->id) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                Kelola Anggota
                            </a>
                            
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>