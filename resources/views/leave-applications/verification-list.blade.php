<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Pengajuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Header & Informasi Role --}}
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                @if(auth()->user()->role == 'ketua_divisi')
                                    Verifikasi Cuti Anggota Tim
                                @elseif(auth()->user()->role == 'hrd')
                                    Persetujuan Final Cuti
                                @else
                                    Verifikasi Pengajuan Cuti
                                @endif
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                @if(auth()->user()->role == 'ketua_divisi')
                                    Verifikasi pertama untuk anggota divisi Anda
                                @elseif(auth()->user()->role == 'hrd')
                                    Persetujuan final untuk semua pengajuan cuti
                                @endif
                            </p>
                        </div>
                        
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600">{{ $pendingApplications->count() }}</div>
                            <div class="text-sm text-gray-600">Menunggu Verifikasi</div>
                        </div>
                    </div>

                    {{-- Filter Section --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Filter Pengajuan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Filter Jenis Cuti --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Jenis Cuti</label>
                                <select id="filterLeaveType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Semua Jenis</option>
                                    <option value="tahunan">Cuti Tahunan</option>
                                    <option value="sakit">Cuti Sakit</option>
                                </select>
                            </div>

                            {{-- Filter Durasi --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Durasi Cuti</label>
                                <select id="filterDuration" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Semua Durasi</option>
                                    <option value="1-3">1-3 Hari</option>
                                    <option value="4-7">4-7 Hari</option>
                                    <option value="8+">8+ Hari</option>
                                </select>
                            </div>

                            {{-- Quick Stats --}}
                            <div class="bg-white p-3 rounded border">
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div class="text-center">
                                        <div class="font-semibold text-blue-600">{{ $pendingApplications->where('leave_type', 'tahunan')->count() }}</div>
                                        <div class="text-gray-500">Tahunan</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="font-semibold text-green-600">{{ $pendingApplications->where('leave_type', 'sakit')->count() }}</div>
                                        <div class="text-gray-500">Sakit</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="font-semibold text-purple-600">{{ $pendingApplications->sum('total_days') }}</div>
                                        <div class="text-gray-500">Total Hari</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Daftar Verifikasi --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pemohon & Divisi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis & Periode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Durasi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Diajukan
                                    </th>
                                    <th class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="applicationsTable">
                                @forelse ($pendingApplications as $application)
                                    <tr class="application-row hover:bg-gray-50" 
                                        data-leave-type="{{ $application->leave_type }}"
                                        data-duration="{{ $application->total_days }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <span class="text-indigo-600 text-sm font-medium">
                                                        {{ substr($application->applicant->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $application->applicant->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $application->applicant->division->name ?? 'Tidak ada divisi' }}
                                                    </div>
                                                    <div class="text-xs text-gray-400">
                                                        {{ $application->applicant->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 capitalize">
                                                {{ $application->leave_type }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $application->start_date->format('d M Y') }} - {{ $application->end_date->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $application->total_days }} hari</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $application->start_date->format('d M') }} - {{ $application->end_date->format('d M') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => '⏳', 'text' => 'Menunggu'],
                                                    'approved_by_leader' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => '✅', 'text' => 'Disetujui Atasan'],
                                                ];
                                                
                                                $config = $statusConfig[$application->status] ?? ['color' => 'bg-gray-100 text-gray-800', 'icon' => '❓', 'text' => $application->status];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['color'] }}">
                                                {{ $config['icon'] }}
                                                <span class="ml-1">{{ $config['text'] }}</span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $application->created_at->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $application->created_at->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('leave-verifications.show', $application->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded text-xs font-medium transition duration-150 ease-in-out">
                                                    👁️ Lihat Detail
                                                </a>
                                                
                                                @if(auth()->user()->role == 'ketua_divisi' && $application->status == 'pending')
                                                    <form action="{{ route('leave-verifications.approve', $application->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded text-xs font-medium transition duration-150 ease-in-out"
                                                                onclick="return confirm('Setujui pengajuan cuti dari {{ $application->applicant->name }}?')">
                                                            ✅ Approve
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center">
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pengajuan cuti</h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    @if(auth()->user()->role == 'ketua_divisi')
                                                        Tidak ada pengajuan cuti dari anggota tim yang menunggu verifikasi.
                                                    @else
                                                        Tidak ada pengajuan cuti yang menunggu persetujuan final.
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk Filter --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterLeaveType = document.getElementById('filterLeaveType');
            const filterDuration = document.getElementById('filterDuration');
            const applicationsTable = document.getElementById('applicationsTable');
            const applicationRows = document.querySelectorAll('.application-row');

            function filterApplications() {
                const selectedType = filterLeaveType.value;
                const selectedDuration = filterDuration.value;

                applicationRows.forEach(row => {
                    const leaveType = row.getAttribute('data-leave-type');
                    const duration = parseInt(row.getAttribute('data-duration'));
                    
                    let typeMatch = !selectedType || leaveType === selectedType;
                    let durationMatch = !selectedDuration || (
                        (selectedDuration === '1-3' && duration >= 1 && duration <= 3) ||
                        (selectedDuration === '4-7' && duration >= 4 && duration <= 7) ||
                        (selectedDuration === '8+' && duration >= 8)
                    );

                    if (typeMatch && durationMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Cek jika semua row hidden
                const visibleRows = Array.from(applicationRows).filter(row => row.style.display !== 'none');
                if (visibleRows.length === 0 && applicationRows.length > 0) {
                    applicationsTable.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada hasil</h3>
                                    <p class="mt-1 text-sm text-gray-500">Tidak ada pengajuan yang sesuai dengan filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }
            }

            filterLeaveType.addEventListener('change', filterApplications);
            filterDuration.addEventListener('change', filterApplications);
        });
    </script>
</x-app-layout>