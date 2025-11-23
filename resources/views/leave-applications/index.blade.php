<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Pengajuan Cuti Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Header & Tombol --}}
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Riwayat Pengajuan Cuti</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Sisa kuota cuti tahunan: 
                                <span class="font-bold text-blue-600">{{ auth()->user()->annual_leave_quota }} hari</span>
                            </p>
                        </div>
                        
                        <a href="{{ route('leave-applications.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            + Ajukan Cuti Baru
                        </a>
                    </div>

                    {{-- Filter Section --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Filter Riwayat</h4>
                        <form method="GET" action="{{ route('leave-applications.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            {{-- Filter Status --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Status</label>
                                <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="approved_by_leader" {{ request('status') == 'approved_by_leader' ? 'selected' : '' }}>Disetujui Atasan</option>
                                    <option value="approved_by_hrd" {{ request('status') == 'approved_by_hrd' ? 'selected' : '' }}>Disetujui HRD</option>
                                    <option value="rejected" {{ in_array(request('status'), ['rejected_by_leader', 'rejected_by_hrd']) ? 'selected' : '' }}>Ditolak</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            {{-- Filter Jenis Cuti --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Jenis Cuti</label>
                                <select name="leave_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Semua Jenis</option>
                                    <option value="tahunan" {{ request('leave_type') == 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                                    <option value="sakit" {{ request('leave_type') == 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                                </select>
                            </div>

                            {{-- Filter Tahun --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Tahun</label>
                                <select name="year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Semua Tahun</option>
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Tombol Filter --}}
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Terapkan Filter
                                </button>
                                <a href="{{ route('leave-applications.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 text-sm">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Statistik Cepat --}}
                    @php
                        $stats = [
                            'total' => $leaveApplications->count(),
                            'pending' => $leaveApplications->where('status', 'pending')->count(),
                            'approved' => $leaveApplications->whereIn('status', ['approved_by_leader', 'approved_by_hrd'])->count(),
                            'rejected' => $leaveApplications->whereIn('status', ['rejected_by_leader', 'rejected_by_hrd'])->count(),
                        ];
                    @endphp

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                            <div class="text-sm text-gray-600">Total Pengajuan</div>
                        </div>
                        <div class="bg-white border border-yellow-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                            <div class="text-sm text-gray-600">Menunggu</div>
                        </div>
                        <div class="bg-white border border-green-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                            <div class="text-sm text-gray-600">Disetujui</div>
                        </div>
                        <div class="bg-white border border-red-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                            <div class="text-sm text-gray-600">Ditolak</div>
                        </div>
                    </div>

                    {{-- Tabel Riwayat Cuti --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis & Periode
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Durasi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Timeline
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($leaveApplications as $application)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full 
                                                    {{ $application->leave_type == 'tahunan' ? 'bg-blue-100' : 'bg-green-100' }} 
                                                    flex items-center justify-center">
                                                    @if($application->leave_type == 'tahunan')
                                                        <span class="text-blue-600 text-sm">🏖️</span>
                                                    @else
                                                        <span class="text-green-600 text-sm">🏥</span>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 capitalize">
                                                        {{ $application->leave_type }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $application->start_date->format('d M Y') }} - {{ $application->end_date->format('d M Y') }}
                                                    </div>
                                                    <div class="text-xs text-gray-400">
                                                        Diajukan: {{ $application->created_at->format('d M Y H:i') }}
                                                    </div>
                                                </div>
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
                                                    'pending' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => '⏳'],
                                                    'approved_by_leader' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => '✅'],
                                                    'approved_by_hrd' => ['color' => 'bg-green-100 text-green-800', 'icon' => '🎉'],
                                                    'rejected_by_leader' => ['color' => 'bg-red-100 text-red-800', 'icon' => '❌'],
                                                    'rejected_by_hrd' => ['color' => 'bg-red-100 text-red-800', 'icon' => '❌'],
                                                    'cancelled' => ['color' => 'bg-gray-100 text-gray-800', 'icon' => '🚫'],
                                                ];
                                                
                                                $config = $statusConfig[$application->status] ?? ['color' => 'bg-gray-100 text-gray-800', 'icon' => '❓'];
                                                $text = str_replace(['_', 'by'], [' ', 'oleh'], $application->status);
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $config['color'] }}">
                                                {{ $config['icon'] }}
                                                <span class="ml-1">{{ ucwords($text) }}</span>
                                            </span>
                                            
                                            @if($application->leader_rejection_notes)
                                                <div class="text-xs text-red-600 mt-1 max-w-xs">
                                                    Catatan: {{ Str::limit($application->leader_rejection_notes, 50) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                @if($application->leader_approval_at)
                                                    <div class="text-xs text-green-600">
                                                        ✅ Disetujui Atasan: {{ $application->leader_approval_at->format('d M Y') }}
                                                    </div>
                                                @endif
                                                @if($application->hrd_approval_at)
                                                    <div class="text-xs text-green-600">
                                                        ✅ Disetujui HRD: {{ $application->hrd_approval_at->format('d M Y') }}
                                                    </div>
                                                @endif
                                                @if($application->status == 'pending')
                                                    <div class="text-xs text-yellow-600">
                                                        ⏳ Menunggu persetujuan
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                {{-- TOMBOL PDF --}}
                                                @if(in_array($application->status, ['approved_by_hrd', 'approved_by_leader']))
                                                    <div class="flex space-x-1">
                                                        <a href="{{ route('leave-applications.preview-pdf', $application) }}" 
                                                           target="_blank"
                                                           class="bg-blue-500 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded flex items-center transition duration-150 ease-in-out"
                                                           title="Preview Surat Cuti">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                            Preview
                                                        </a>
                                                        <a href="{{ route('leave-applications.download-pdf', $application) }}" 
                                                           class="bg-green-500 hover:bg-green-700 text-white text-xs px-3 py-1 rounded flex items-center transition duration-150 ease-in-out"
                                                           title="Download Surat Cuti PDF">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                            PDF
                                                        </a>
                                                    </div>
                                                @else
                                                    {{-- TOMBOL DRAFT PDF untuk yang belum approved --}}
                                                    <a href="{{ route('leave-applications.download-draft', $application) }}" 
                                                       class="bg-gray-500 hover:bg-gray-700 text-white text-xs px-3 py-1 rounded flex items-center transition duration-150 ease-in-out"
                                                       title="Download Draft Surat Cuti">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Draft
                                                    </a>
                                                @endif

                                                {{-- TOMBOL BATALKAN untuk status pending --}}
                                                @if ($application->status == 'pending')
                                                    <form action="{{ route('leave-applications.cancel', $application->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded text-xs font-medium transition duration-150 ease-in-out"
                                                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pengajuan cuti ini? Kuota akan dikembalikan.')">
                                                            Batalkan
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                {{-- TOMBOL LAMPIRAN --}}
                                                @if($application->attachment_path)
                                                    <a href="{{ asset('storage/' . $application->attachment_path) }}" 
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded text-xs font-medium transition duration-150 ease-in-out">
                                                        Lampiran
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center">
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada riwayat cuti</h3>
                                                <p class="mt-1 text-sm text-gray-500">Mulai dengan mengajukan cuti pertama Anda.</p>
                                                <div class="mt-4">
                                                    <a href="{{ route('leave-applications.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                                        + Ajukan Cuti Baru
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($leaveApplications->hasPages())
                        <div class="mt-4">
                            {{ $leaveApplications->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>