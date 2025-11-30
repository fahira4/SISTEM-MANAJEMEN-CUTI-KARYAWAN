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
                    <h2 class="text-2xl font-bold tracking-tight">Tambah Divisi Baru</h2>
                    <p class="text-blue-200 text-lg mt-1">
                        Buat divisi baru untuk organisasi Anda
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

            @if ($errors->any())
                <div class="mb-6 bg-red-500/20 border border-red-500/30 text-white px-4 py-2 rounded-lg backdrop-blur-md text-sm shadow-sm animate-fade-in-down">
                    <strong class="font-medium">Whoops! Ada yang salah:</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-12 relative z-10">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
           
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h4 class="font-bold text-gray-700 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Form Tambah Divisi Baru
                </h4>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('admin.divisions.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-6">
                       
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 mb-2">Nama Divisi *</label>
                            <input id="name" 
                                   name="name" 
                                   type="text" 
                                   value="{{ old('name') }}"
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
                                      placeholder="Deskripsi singkat tentang divisi ini...">{{ old('description') }}</textarea>
                            <p class="text-xs text-gray-500 mt-2">Deskripsi opsional tentang divisi ini.</p>
                        </div>

                        <div>
                            <label for="leader_id" class="block font-medium text-sm text-gray-700 mb-2">Ketua Divisi *</label>
                            <select name="leader_id" id="leader_id" 
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm transition duration-200"
                                    required>
                                <option value="">-- Pilih Ketua Divisi --</option>
                                @foreach($availableLeaders as $leader)
                                    <option value="{{ $leader->id }}" {{ old('leader_id') == $leader->id ? 'selected' : '' }}>
                                        {{ $leader->name }} ({{ $leader->email }})
                                    </option>
                                @endforeach
                            </select>

                            @if($availableLeaders->count() == 0)
                                <div class="mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <div>
                                            <p class="text-yellow-700 text-sm font-medium">Tidak ada ketua divisi yang tersedia</p>
                                            <p class="text-yellow-600 text-sm mt-1">
                                                <a href="{{ route('admin.users.create') }}" class="underline font-medium hover:text-yellow-800">
                                                    Buat user ketua divisi baru
                                                </a> 
                                                atau ubah role user yang sudah ada.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-gray-500 mt-2">
                                    Pilih ketua divisi dari daftar yang tersedia. Hanya ketua divisi yang belum memimpin divisi lain yang ditampilkan.
                                </p>
                            @endif
                            @error('leader_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-700 font-medium">Informasi Sistem</p>
                                    <div class="mt-2 space-y-1 text-sm text-blue-600">
                                        <div class="flex justify-between">
                                            <span>Tanggal Dibentuk:</span>
                                            <span class="font-medium">{{ now()->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Status:</span>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">Akan dibuat</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-blue-500 mt-2">Tanggal dibentuk akan diisi otomatis oleh sistem.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.divisions.index') }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition duration-200">
                            Batal
                        </a>
                        
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium border border-blue-700 transition duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Divisi Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>