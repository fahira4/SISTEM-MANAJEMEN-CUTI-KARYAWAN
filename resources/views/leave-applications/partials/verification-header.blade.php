<x-slot name="header">
    <div class="bg-white border-b border-gray-200 -mx-6 -mt-6 px-6 py-6 shadow-sm">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                {{-- Title Section --}}
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-indigo-50 rounded-xl">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Persetujuan Final Cuti</h1>
                        <p class="text-gray-500 mt-1">Tinjau dan berikan persetujuan final untuk pengajuan cuti</p>
                    </div>
                </div>

                {{-- User Info & Stats --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    {{-- Quick Stats --}}
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2 bg-indigo-50 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-indigo-700">{{ $pendingApplications->count() }} menunggu</span>
                        </div>
                    </div>
                    
                    {{-- User Profile --}}
                    <div class="flex items-center space-x-3 bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200">
                        <div class="flex items-center space-x-2">
                            <div class="h-2 w-2 rounded-full bg-green-500"></div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">HRD</p>
                            </div>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-slot>