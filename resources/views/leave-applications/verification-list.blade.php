<x-app-layout>
    {{-- HEADER --}}
    @include('leave-applications.partials.verification-header')

    <div class="py-8 bg-gray-50 min-h-screen">
        {{-- CONTAINER UTAMA (Yang Mengatur Posisi Mengambang) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-64 pb-12 relative z-10">
            
            {{-- SATU KONTAINER BESAR UNTUK STATS DAN FILTER --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 border-l-4 border-l-blue-600 p-6 mb-8">
                
                {{-- 1. STATISTIK AREA (Card Style) --}}
                <div class="flex items-start justify-between space-x-4 pb-4 mb-4 border-b border-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">Menunggu Verifikasi</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $pendingApplications->count() }} Pengajuan</h3>
                        </div>
                    </div>
                </div>

                {{-- 2. FILTER CONTROLS --}}
                <form method="GET" action="{{ route('leave-verifications.index') }}" class="flex flex-col gap-4">
                    
                    <div class="flex flex-wrap gap-3 items-center">
                        {{-- Search Input --}}
                        <div class="relative w-full sm:w-64">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari nama/email..."
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Leave Type Filter --}}
                        <select name="leave_type" 
                                class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 w-full sm:w-40">
                            <option value="">Semua Jenis</option>
                            <option value="tahunan" {{ request('leave_type') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            <option value="sakit" {{ request('leave_type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        </select>

                        {{-- Division Filter --}}
                        @if(in_array(Auth::user()->role, ['hrd', 'admin']))
                        <select name="division" 
                                class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 w-full sm:w-40">
                            <option value="">Semua Divisi</option>
                            {{-- ... divisions loop ... --}}
                        </select>
                        @endif
                        
                        {{-- Date Range --}}
                        <div class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-lg border flex-1 min-w-[300px]">
                            <span class="text-sm font-medium text-gray-700 whitespace-nowrap">Periode:</span>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                   class="border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500 py-1 px-2 w-32">
                            <span class="text-gray-400 text-sm">s/d</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                   class="border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500 py-1 px-2 w-32">
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition duration-200 whitespace-nowrap">
                                Terapkan
                            </button>
                            <a href="{{ route('leave-verifications.index') }}" 
                               class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-600 transition duration-200 whitespace-nowrap flex items-center justify-center">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            
            {{-- CARD GRID (Dibawah Filter) --}}
            @if($pendingApplications->isEmpty())
                {{-- Empty State --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center mt-6">
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
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mt-6">
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