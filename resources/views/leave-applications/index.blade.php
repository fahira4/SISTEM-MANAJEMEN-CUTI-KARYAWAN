<x-app-layout>
    <div class="relative bg-blue-900 pb-24 pt-8 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="text-left">
                    <h1 class="text-3xl font-bold text-white mb-2">
                        Riwayat Pengajuan Cuti Saya
                    </h1>
                    <p class="text-blue-200 text-lg">
                        Kelola dan pantau semua pengajuan cuti Anda di satu tempat
                    </p>
                </div>
                
                @if(auth()->user()->role == 'karyawan' && !auth()->user()->division_id)
                    <button disabled class="inline-flex items-center px-6 py-3 bg-gray-400 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest cursor-not-allowed shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Akun Belum Siap
                    </button>
                @else
                    <a href="{{ route('leave-applications.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-blue-900 border border-transparent rounded-lg font-semibold text-sm uppercase tracking-widest hover:bg-blue-50 hover:text-blue-800 active:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Ajukan Cuti Baru
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-14 pb-12 relative z-20">
            
            <div class="bg-white rounded-xl shadow-lg border-b-4 border-orange-500 p-6 mb-8">
            
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">
                            Riwayat Pengajuan Cuti Saya
                        </h3>
                        <p class="text-gray-600 mt-1">
                            Sisa kuota cuti tahunan: 
                            <span class="font-bold text-blue-600">{{ auth()->user()->annual_leave_quota ?? 0 }} hari</span>
                        </p>
                    </div>
                </div>

                <form method="GET" action="{{ route('leave-applications.index') }}" class="flex flex-col gap-4 pt-4 border-b border-gray-100">
                    <div class="flex flex-wrap gap-3 items-center">
                        <select name="status" 
                                class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 w-full sm:w-40">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved_by_leader" {{ request('status') == 'approved_by_leader' ? 'selected' : '' }}>Disetujui Atasan</option>
                            <option value="approved_by_hrd" {{ request('status') == 'approved_by_hrd' ? 'selected' : '' }}>Disetujui HRD</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>

                        <select name="leave_type" 
                                class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 w-full sm:w-40">
                            <option value="">Semua Jenis</option>
                            <option value="tahunan" {{ request('leave_type') == 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                            <option value="sakit" {{ request('leave_type') == 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                        </select>

                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition duration-200 whitespace-nowrap">
                                Terapkan Filter
                            </button>
                            <a href="{{ route('leave-applications.index') }}" 
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-600 transition duration-200 whitespace-nowrap flex items-center justify-center">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                @php
                    $stats = [
                        'total' => $leaveApplications->count(),
                        'pending' => $leaveApplications->where('status', 'pending')->count(),
                        'approved_by_leader' => $leaveApplications->where('status', 'approved_by_leader')->count(),
                        'approved_by_hrd' => $leaveApplications->where('status', 'approved_by_hrd')->count(),
                        'rejected' => $leaveApplications->whereIn('status', ['rejected_by_leader', 'rejected_by_hrd'])->count(),
                        'completed' => $leaveApplications->where('status', 'approved_by_hrd')
                                        ->filter(function($app) {
                                            return $app->end_date->isPast();
                                        })->count(),
                    ];
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mt-6 pt-6 border-t border-gray-100">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                        <div class="text-xs text-gray-600 font-medium mt-1">Total</div>
                    </div>
            
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-yellow-700">{{ $stats['pending'] }}</div>
                        <div class="text-xs text-yellow-600 font-medium mt-1">Menunggu</div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-blue-700">{{ $stats['approved_by_leader'] }}</div>
                        <div class="text-xs text-blue-600 font-medium mt-1">Disetujui Atasan</div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-green-700">{{ $stats['approved_by_hrd'] }}</div>
                        <div class="text-xs text-green-600 font-medium mt-1">Disetujui HRD</div>
                    </div>

                    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-red-700">{{ $stats['rejected'] }}</div>
                        <div class="text-xs text-red-600 font-medium mt-1">Ditolak</div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-purple-700">{{ $stats['completed'] }}</div>
                        <div class="text-xs text-purple-600 font-medium mt-1">Selesai</div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Daftar Pengajuan Cuti</h3>
                <a href="{{ route('dashboard') }}" 
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">
                                    <div class="flex items-center space-x-1 cursor-pointer group" 
                                         onclick="sortTable('start_date')">
                                        <span>Jenis & Periode</span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">
                                    <div class="flex items-center space-x-1 cursor-pointer group" 
                                         onclick="sortTable('total_days')">
                                        <span>Durasi</span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">
                                    <div class="flex items-center space-x-1 cursor-pointer group" 
                                         onclick="sortTable('status')">
                                        <span>Status</span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">
                                    <div class="flex items-center space-x-1 cursor-pointer group" 
                                         onclick="sortTable('created_at')">
                                        <span>Timeline</span>
                                    </div>
                                </th>

                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($leaveApplications as $application)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full 
                                                {{ $application->leave_type == 'tahunan' ? 'bg-blue-100 border border-blue-200' : 'bg-green-100 border border-green-200' }} 
                                                flex items-center justify-center">
                                                @if($application->leave_type == 'tahunan')
                                                    <span class="text-blue-600 text-sm">🏖️</span>
                                                @else
                                                    <span class="text-green-600 text-sm">🏥</span>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900 capitalize">
                                                    {{ $application->leave_type ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($application->start_date)->format('d M Y') }} - 
                                                    {{ \Carbon\Carbon::parse($application->end_date)->format('d M Y') }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    Diajukan: {{ $application->created_at->format('d M Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $application->total_days ?? 0 }} hari</div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($application->start_date)->format('d M') }} - 
                                            {{ \Carbon\Carbon::parse($application->end_date)->format('d M') }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['color' => 'bg-yellow-100 text-yellow-800 border border-yellow-200', 'icon' => '⏳'],
                                                'approved_by_leader' => ['color' => 'bg-blue-100 text-blue-800 border border-blue-200', 'icon' => '✅'],
                                                'approved_by_hrd' => ['color' => 'bg-green-100 text-green-800 border border-green-200', 'icon' => '🎉'],
                                                'rejected_by_leader' => ['color' => 'bg-red-100 text-red-800 border border-red-200', 'icon' => '❌'],
                                                'rejected_by_hrd' => ['color' => 'bg-red-100 text-red-800 border border-red-200', 'icon' => '❌'],
                                                'cancelled' => ['color' => 'bg-gray-100 text-gray-800 border border-gray-200', 'icon' => '🚫'],
                                            ];
                                            
                                            $config = $statusConfig[$application->status] ?? ['color' => 'bg-gray-100 text-gray-800 border border-gray-200', 'icon' => '❓'];
                                            $text = str_replace(['_', 'by'], [' ', 'oleh'], $application->status);
                                        @endphp
                                        
                                        <div class="space-y-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $config['color'] }}">
                                                {{ $config['icon'] }}
                                                <span class="ml-1">{{ ucwords($text) }}</span>
                                            </span>
                                            
                                            @if($application->status === 'approved_by_hrd' && $application->end_date->isPast())
                                                <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Selesai
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 space-y-1">
                                            @if($application->leader_approval_at)
                                                <div class="text-xs text-green-600">
                                                    ✅ Atasan: {{ \Carbon\Carbon::parse($application->leader_approval_at)->format('d M Y') }}
                                                </div>
                                            @endif
                                            @if($application->hrd_approval_at)
                                                <div class="text-xs text-green-600">
                                                    ✅ HRD: {{ \Carbon\Carbon::parse($application->hrd_approval_at)->format('d M Y') }}
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
                                            <a href="{{ route('leave-applications.show', $application) }}" 
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Detail
                                            </a>

                                            @if($application->status === 'approved_by_hrd' && $application->hrd_approval_at)
                                                <a href="{{ route('leave-applications.download-letter', $application) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200"
                                                   title="Download Surat Cuti">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    PDF
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-center">
                                            <div class="max-w-md mx-auto">
                                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                                    </svg>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Data</h3>
                                                <p class="text-gray-500 text-sm">Tidak ada pengajuan cuti yang sesuai dengan filter yang dipilih.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($leaveApplications->hasPages())
                <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    {{ $leaveApplications->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
    function sortTable(column) {
        const url = new URL(window.location.href);
        const currentSort = url.searchParams.get('sort');
        const currentDirection = url.searchParams.get('direction');
        
        let newDirection = 'asc';
        
        if (currentSort === column) {
            newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
        }
        
        url.searchParams.set('sort', column);
        url.searchParams.set('direction', newDirection);
        
        const baseUrl = '{{ route("leave-applications.index") }}';
        window.location.href = baseUrl + '?' + url.searchParams.toString();
    }
    </script>
</x-app-layout>