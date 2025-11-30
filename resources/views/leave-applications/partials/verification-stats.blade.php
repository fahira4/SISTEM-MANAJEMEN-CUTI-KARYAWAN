<div class="relative -mt-16 mb-8 z-10">
    <div class="bg-white rounded-xl shadow-lg border-b-4 border-orange-500 mx-4 lg:mx-0">
        
<div class="p-8 border-b border-gray-100">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="flex items-center justify-center gap-12 lg:gap-32 w-full">
            <div class="text-center border-2 border-gray-200 rounded-xl p-6 bg-white shadow-sm min-w-[140px]">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Menunggu</p>
                <p class="text-4xl font-bold text-gray-900 mt-2">{{ $pendingApplications->count() }}</p>
                <p class="text-xs text-gray-500 mt-2">Pengajuan</p>
            </div>

            <div class="text-center border-2 border-blue-200 rounded-xl p-6 bg-blue-50 shadow-sm min-w-[140px]">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Cuti Tahunan</p>
                <p class="text-4xl font-bold text-blue-600 mt-2">
                    {{ $pendingApplications->where('leave_type', 'tahunan')->count() }}
                </p>
                <p class="text-xs text-gray-500 mt-2">Pengajuan</p>
            </div>

            <div class="text-center border-2 border-orange-200 rounded-xl p-6 bg-orange-50 shadow-sm min-w-[140px]">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Cuti Sakit</p>
                <p class="text-4xl font-bold text-orange-600 mt-2">
                    {{ $pendingApplications->where('leave_type', 'sakit')->count() }}
                </p>
                <p class="text-xs text-gray-500 mt-2">Pengajuan</p>
            </div>
        </div>

                @if($pendingApplications->count() > 0)
                <div class="flex items-center gap-3 bg-blue-50 rounded-lg px-5 py-4 border-2 border-blue-200 lg:ml-8">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-blue-800">
                            @if(Auth::user()->role == 'hrd')
                                Pilih multiple atau individual approval/reject
                            @else
                                Pilih multiple atau individual verifikasi
                            @endif
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('leave-verifications.index') }}">
                
                <div class="flex flex-wrap items-end gap-4">
                    
                    <div class="flex-1 min-w-[100px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari nama atau email..."
                                   class="pl-10 pr-4 py-2.5 border-2 border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="w-full sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Cuti</label>
                        <select name="leave_type" 
                                class="border-2 border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 px-3 w-full">
                            <option value="">Semua Jenis</option>
                            <option value="tahunan" {{ request('leave_type') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            <option value="sakit" {{ request('leave_type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        </select>
                    </div>

                    @if(Auth::user()->role == 'hrd')
                    <div class="w-full sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Divisi</label>
                        <select name="division" 
                                class="border-2 border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 px-3 w-full">
                            <option value="">Semua Divisi</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ request('division') == $division->id ? 'selected' : '' }}>
                                    {{ $division->name ?? 'Divisi Tidak Diketahui' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="w-full sm:w-auto">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode Pengajuan</label>
                        <div class="flex items-center gap-2">
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                   class="border-2 border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 px-3 w-40">
                            <span class="text-gray-400 text-sm">s/d</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                   class="border-2 border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 px-3 w-40">
                        </div>
                    </div>

                    <div class="w-full sm:w-auto flex gap-2">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition duration-200 whitespace-nowrap border-2 border-blue-700">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('leave-verifications.index') }}" 
                           class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-300 transition duration-200 whitespace-nowrap border-2 border-gray-300">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>