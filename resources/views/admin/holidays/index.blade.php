<x-app-layout>
    <div class="relative bg-blue-900 min-h-[40vh] overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-24">
           
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div class="text-white">
                    <h2 class="text-4xl font-bold tracking-tight">Manajemen Hari Libur & Cuti Bersama</h2>
                    <p class="text-blue-200 text-xl mt-2">
                        Kelola semua hari libur dan cuti bersama sistem C-OPS
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="hidden md:block">
                        <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 backdrop-blur-sm">
                            <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-blue-100 text-sm font-medium">Tahun: {{ $year }}</span>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.holidays.import-form') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-gray-900  font-bold rounded-lg shadow hover:bg-blue-50 transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                            </svg>
                            Import Google Calendar
                        </a>
                        
                        <a href="{{ route('admin.holidays.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-blue-900 font-bold rounded-lg shadow hover:bg-blue-50 transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Manual
                        </a>
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
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-8 relative z-10">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden border-b-4 border-b-orange-500">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                    
                    <div class="bg-gray-50 p-5 border border-gray-200 rounded-lg flex flex-col">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter Data Hari Libur
                        </h4>
                        <div class="grid grid-cols-1 gap-4 flex-1">
                           
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                                <select id="yearFilter" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                                    @foreach($availableYears as $availableYear)
                                        <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                            {{ $availableYear }}
                                        </option>
                                    @endforeach
                                    <option value="{{ date('Y') }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                        {{ date('Y') }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Libur</label>
                                <select id="typeFilter" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                                    <option value="">Semua Jenis</option>
                                    <option value="national">Libur Nasional</option>
                                    <option value="company">Libur Perusahaan</option>
                                    <option value="joint_leave">Cuti Bersama</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Berulang</label>
                                <select id="recurringFilter" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200">
                                    <option value="">Semua Status</option>
                                    <option value="1">Berulang Setiap Tahun</option>
                                    <option value="0">Tidak Berulang</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-5 border border-gray-200 rounded-lg flex flex-col">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Aksi Cepat
                        </h4>
                        
                        <div class="space-y-4 flex-1">
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs text-blue-700">Gunakan filter untuk menyaring data hari libur berdasarkan tahun, jenis, dan status berulang.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mt-4">
                                <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $holidays->count() }}</div>
                                    <div class="text-xs text-gray-500">Total Libur</div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-lg p-3 text-center">
                                    <div class="text-2xl font-bold text-purple-600">
                                        {{ $holidays->where('type', 'joint_leave')->count() }}
                                    </div>
                                    <div class="text-xs text-gray-500">Cuti Bersama</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4 mt-4 border-t border-gray-200">
                            <button type="button" id="applyFilter" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Terapkan Filter
                            </button>
                            <a href="{{ route('admin.holidays.index') }}" class="flex-1 bg-gray-500 text-white px-4 py-3 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 ease-in-out font-medium flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 mt-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
         
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h4 class="font-bold text-white text-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Daftar Hari Libur ({{ $holidays->count() }})
                    </h4>
                    <span class="bg-blue-500/30 text-blue-100 px-3 py-1 rounded-full text-sm font-medium border border-blue-400/30">
                        Tahun {{ $year }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-blue-50/80 backdrop-blur-sm">
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Nama Hari Libur
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                Tanggal
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                Jenis
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                Berulang
                            </th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-blue-800 uppercase tracking-wider border-b border-blue-200">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200/60">
                        @forelse ($holidays as $holiday)
                            <tr class="hover:bg-blue-50/30 transition-all duration-200 group border-b border-gray-100 last:border-b-0">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-sm font-bold mr-3 group-hover:scale-110 transition-transform shadow-sm overflow-hidden border-2 border-white text-white">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $holiday->name }}</div>
                                            @if($holiday->description)
                                                <div class="text-xs text-gray-500 mt-1">{{ $holiday->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $holiday->date->format('d F Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $holiday->date->format('l') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $typeColors = [
                                            'national' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                            'company' => 'bg-green-100 text-green-800 border border-green-200', 
                                            'joint_leave' => 'bg-purple-100 text-purple-800 border border-purple-200'
                                        ];
                                        $typeIcons = [
                                            'national' => '🇮🇩',
                                            'company' => '🏢',
                                            'joint_leave' => '👥'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $typeColors[$holiday->type] ?? 'bg-gray-100 text-gray-800' }} shadow-sm">
                                        <span class="mr-2">{{ $typeIcons[$holiday->type] ?? '📅' }}</span>
                                        {{ $holiday->type == 'national' ? 'Libur Nasional' : 
                                           ($holiday->type == 'company' ? 'Libur Perusahaan' : 'Cuti Bersama') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($holiday->is_recurring)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">
                                            <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>
                                            Setiap Tahun
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                            <span class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span>
                                            Tidak Berulang
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        
                                        <a href="{{ route('admin.holidays.show', $holiday) }}" 
                                           class="text-green-600 hover:text-green-900 px-3 py-2 rounded-lg hover:bg-green-50 transition duration-200 flex items-center gap-2 text-xs font-medium border border-green-200 bg-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                        
                                        <a href="{{ route('admin.holidays.edit', $holiday) }}" 
                                           class="text-blue-600 hover:text-blue-900 px-3 py-2 rounded-lg hover:bg-blue-50 transition duration-200 flex items-center gap-2 text-xs font-medium border border-blue-200 bg-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        
                                        <form action="{{ route('admin.holidays.destroy', $holiday) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 px-3 py-2 rounded-lg hover:bg-red-50 transition duration-200 flex items-center gap-2 text-xs font-medium border border-red-200 bg-white shadow-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus hari libur {{ $holiday->name }}?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-center text-gray-500">
                                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada data hari libur</h3>
                                        <p class="mt-2 text-sm text-gray-500">Coba ubah filter tahun atau tambah hari libur baru.</p>
                                        <div class="mt-4 flex justify-center gap-3">
                                            <a href="{{ route('admin.holidays.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Tambah Manual
                                            </a>
                                            <a href="{{ route('admin.holidays.import-form') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                                </svg>
                                                Import Google
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const yearFilter = document.getElementById('yearFilter');
            const typeFilter = document.getElementById('typeFilter');
            const recurringFilter = document.getElementById('recurringFilter');
            const applyFilterBtn = document.getElementById('applyFilter');

            function applyFilters() {
                const year = yearFilter.value;
                const type = typeFilter.value;
                const recurring = recurringFilter.value;
                
                let url = `{{ route('admin.holidays.index') }}?year=${year}`;
                
                if (type) {
                    url += `&type=${type}`;
                }
                
                if (recurring) {
                    url += `&recurring=${recurring}`;
                }
                
                window.location.href = url;
            }

            yearFilter.addEventListener('change', applyFilters);
            applyFilterBtn.addEventListener('click', applyFilters);

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('type')) {
                typeFilter.value = urlParams.get('type');
            }
            if (urlParams.get('recurring')) {
                recurringFilter.value = urlParams.get('recurring');
            }
        });
    </script>
</x-app-layout>