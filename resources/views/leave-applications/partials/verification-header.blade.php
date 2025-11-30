<div class="relative bg-blue-900 pb-24 pt-8 overflow-hidden">
    
    <div class="absolute inset-0 opacity-10">
        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
        </svg>
    </div>
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-blue-500 blur-3xl opacity-20"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end gap-6">
            
            <div class="flex items-center gap-5">
                <div class="p-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl shadow-2xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-extrabold text-white tracking-tight">
                        @if(Auth::user()->role == 'hrd')
                            Persetujuan Final Cuti
                        @elseif(Auth::user()->role == 'ketua_divisi')
                            Verifikasi Pengajuan Cuti
                        @else
                            Persetujuan Cuti
                        @endif
                    </h1>
                    <p class="text-blue-200 mt-2 text-lg font-medium">
                        @if(Auth::user()->role == 'hrd')
                            Tinjau dan berikan persetujuan final untuk pengajuan cuti
                        @elseif(Auth::user()->role == 'ketua_divisi')
                            Verifikasi pengajuan cuti dari bawahan Anda
                        @else
                            Kelola persetujuan pengajuan cuti
                        @endif
                    </p>
                </div>
            </div>

            <div class="mb-2">
                <div class="flex items-center px-6 py-3 bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            @if(isset($pendingApplications) && $pendingApplications->count() > 0)
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold text-blue-300 tracking-widest">
                                @if(Auth::user()->role == 'hrd')
                                    Menunggu Final
                                @elseif(Auth::user()->role == 'ketua_divisi')
                                    Menunggu Verifikasi
                                @else
                                    Menunggu Persetujuan
                                @endif
                            </p>
                            <p class="text-2xl font-bold text-white leading-none mt-1">
                                {{ isset($pendingApplications) ? $pendingApplications->count() : 0 }} 
                                <span class="text-sm font-medium text-blue-200">Pengajuan</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>