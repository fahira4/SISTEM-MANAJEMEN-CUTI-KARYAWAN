<x-app-layout>
    {{-- HEADER BIRU UNTUK SEMUA ROLE --}}
    @include('leave-applications.partials.verification-header')

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            
            {{-- CARD STATS & FILTER UNTUK SEMUA ROLE --}}
            @include('leave-applications.partials.verification-stats')

            {{-- BULK ACTION TOOLBAR --}}
            @include('leave-applications.partials.verification-bulk-toolbar')

            {{-- APPLICATION CARDS GRID --}}
            @if($pendingApplications->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mt-6" id="applications-grid">
                    @foreach($pendingApplications as $application)
                        @include('leave-applications.partials.verification-card', ['application' => $application])
                    @endforeach
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center mt-6">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 bg-blue-50 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Pengajuan</h3>
                        <p class="text-gray-500">
                            @if(Auth::user()->role == 'hrd')
                                Semua pengajuan cuti telah disetujui. Tidak ada yang menunggu persetujuan final saat ini.
                            @elseif(Auth::user()->role == 'ketua_divisi')
                                Tidak ada pengajuan cuti dari bawahan yang menunggu verifikasi.
                            @else
                                Tidak ada pengajuan cuti yang menunggu persetujuan.
                            @endif
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- MODALS --}}
    @include('leave-applications.partials.verification-modals')
    {{-- SCRIPTS --}}
    @include('leave-applications.partials.verification-scripts')
</x-app-layout>