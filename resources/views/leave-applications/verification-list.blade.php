<x-app-layout>
    {{-- HEADER --}}
    @include('leave-applications.partials.verification-header')

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- STATS & NOTIFICATIONS --}}
            @include('leave-applications.partials.verification-stats')

            {{-- BULK ACTION TOOLBAR --}}
            @include('leave-applications.partials.verification-bulk-toolbar')

            {{-- CARD GRID --}}
            @if($pendingApplications->isEmpty())
                {{-- Empty State --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 bg-blue-50 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Pengajuan</h3>
                        <p class="text-gray-500">Semua pengajuan cuti telah diverifikasi. Tidak ada yang menunggu persetujuan saat ini.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($pendingApplications as $application)
                        @include('leave-applications.partials.verification-card', ['application' => $application])
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    {{-- MODALS --}}
    @include('leave-applications.partials.verification-modals')

    {{-- SCRIPTS --}}
    @include('leave-applications.partials.verification-scripts')
</x-app-layout> 