{{-- STATS & FILTER --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        {{-- Statistik --}}
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

    {{-- Filter Controls - HORIZONTAL LAYOUT --}}
    <div class="mt-4 flex flex-col gap-4">
        <form method="GET" action="{{ route('leave-verifications.index') }}" class="flex flex-col lg:flex-row gap-4 items-start lg:items-center">
            
            {{-- Filter Group 1: Search & Basic Filters --}}
            <div class="flex flex-wrap gap-3 items-center flex-1">
                {{-- Search Input --}}
                <div class="relative w-70">
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
                        onchange="this.form.submit()"
                        class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 w-48">
                    <option value="">Semua Jenis</option>
                    <option value="tahunan" {{ request('leave_type') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    <option value="sakit" {{ request('leave_type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                </select>

                {{-- Division Filter (hanya untuk HRD/Admin) --}}
                @if(in_array(Auth::user()->role, ['hrd', 'admin']))
                <select name="division" 
                        onchange="this.form.submit()"
                        class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 w-48">
                    <option value="">Semua Divisi</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" {{ request('division') == $division->id ? 'selected' : '' }}>
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
                @endif
            </div>

            {{-- Filter Group 2: Date & Actions --}}
            <div class="flex flex-wrap gap-3 items-center">
                {{-- Date Range --}}
                <div class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-lg border">
                    <span class="text-sm font-medium text-gray-700 whitespace-nowrap">Periode:</span>
                    <div class="flex items-center gap-2">
                        <input type="date" 
                               name="date_from" 
                               value="{{ request('date_from') }}"
                               class="border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500 py-1 px-2 w-32">
                        <span class="text-gray-400 text-sm">s/d</span>
                        <input type="date" 
                               name="date_to" 
                               value="{{ request('date_to') }}"
                               class="border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500 py-1 px-2 w-32">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition duration-200 whitespace-nowrap">
                        Terapkan
                    </button>
                    <a href="{{ route('leave-verifications.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-600 transition duration-200 whitespace-nowrap">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Active Filters Badges --}}
        @if(request()->anyFilled(['search', 'status', 'leave_type', 'division', 'date_from', 'date_to']))
        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-200">
            <span class="text-sm text-gray-600 font-medium">Filter aktif:</span>
            @if(request('search'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    🔍 "{{ request('search') }}"
                    <a href="{{ route('leave-verifications.index', array_merge(request()->except('search'), ['search' => ''])) }}" 
                       class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                </span>
            @endif
            @if(request('status'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    📊 {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                    <a href="{{ route('leave-verifications.index', array_merge(request()->except('status'), ['status' => ''])) }}" 
                       class="ml-1 text-green-600 hover:text-green-800">×</a>
                </span>
            @endif
            @if(request('leave_type'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    🏷️ {{ ucfirst(request('leave_type')) }}
                    <a href="{{ route('leave-verifications.index', array_merge(request()->except('leave_type'), ['leave_type' => ''])) }}" 
                       class="ml-1 text-purple-600 hover:text-purple-800">×</a>
                </span>
            @endif
            @if(request('division') && $divisions->find(request('division')))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                    👥 {{ $divisions->find(request('division'))->name }}
                    <a href="{{ route('leave-verifications.index', array_merge(request()->except('division'), ['division' => ''])) }}" 
                       class="ml-1 text-orange-600 hover:text-orange-800">×</a>
                </span>
            @endif
            @if(request('date_from') || request('date_to'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    📅 
                    {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d M') : '...' }}
                    - 
                    {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d M Y') : '...' }}
                    <a href="{{ route('leave-verifications.index', array_merge(request()->except(['date_from', 'date_to']), ['date_from' => '', 'date_to' => ''])) }}" 
                       class="ml-1 text-indigo-600 hover:text-indigo-800">×</a>
                </span>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- NOTIFICATIONS --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" 
         class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
        class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm backdrop-blur-sm bg-white/80">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif

{{-- Enhanced Error Display --}}
@if(session('errors'))
    <div class="bg-orange-50 border-l-4 border-orange-500 text-orange-700 p-4 rounded-lg shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <span class="font-medium">Beberapa pengajuan gagal diproses:</span>
                    <ul class="mt-1 text-sm list-disc list-inside">
                        @foreach(session('errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button onclick="this.parentElement.parentElement.style.display='none'" 
                    class="text-orange-500 hover:text-orange-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif