<x-app-layout>
    {{-- HEADER --}}
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
                
                {{-- TOMBOL AJUKAN CUTI BARU YANG DIPERBAIKI --}}
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
        {{-- CONTAINER UTAMA --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-14 pb-12 relative z-20">
            
            {{-- CARD UTAMA --}}
            <div class="bg-white rounded-xl shadow-lg border-t-4 border-orange-500 p-6 mb-8">
                
                {{-- HEADER CARD - TANPA TOMBOL AJUKAN CUTI BARU --}}
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Riwayat Pengajuan Cuti</h3>
                        <p class="text-gray-600 mt-1">
                            Sisa kuota cuti tahunan: 
                            <span class="font-bold text-blue-600">{{ auth()->user()->annual_leave_quota }} hari</span>
                        </p>
                    </div>
                    {{-- TOMBOL AJUKAN CUTI BARU SUDAH DIPINDAH KE HEADER --}}
                </div>

                {{-- FILTER SECTION --}}
                <form method="GET" action="{{ route('leave-applications.index') }}" class="flex flex-col gap-4 pt-4 border-t border-gray-100">
                    
                    <div class="flex flex-wrap gap-3 items-center">
                        {{-- Filter Status --}}
                        <select name="status" 
                                class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 w-full sm:w-40">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved_by_leader" {{ request('status') == 'approved_by_leader' ? 'selected' : '' }}>Disetujui Atasan</option>
                            <option value="approved_by_hrd" {{ request('status') == 'approved_by_hrd' ? 'selected' : '' }}>Disetujui HRD</option>
                            <option value="rejected" {{ in_array(request('status'), ['rejected_by_leader', 'rejected_by_hrd']) ? 'selected' : '' }}>Ditolak</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>

                        {{-- Filter Jenis Cuti --}}
                        <select name="leave_type" 
                                class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 w-full sm:w-40">
                            <option value="">Semua Jenis</option>
                            <option value="tahunan" {{ request('leave_type') == 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                            <option value="sakit" {{ request('leave_type') == 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                        </select>

                        {{-- Filter Tahun --}}
                        <select name="year" 
                                class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 w-full sm:w-40">
                            <option value="">Semua Tahun</option>
                            @for($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>

                        {{-- Action Buttons --}}
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

                {{-- STATISTIK CEPAT - KOTAK KECIL --}}
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
                    {{-- Total --}}
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                        <div class="text-xs text-gray-600 font-medium mt-1">Total</div>
                    </div>

                    {{-- Menunggu --}}
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-yellow-700">{{ $stats['pending'] }}</div>
                        <div class="text-xs text-yellow-600 font-medium mt-1">Menunggu</div>
                    </div>

                    {{-- Disetujui Atasan --}}
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-blue-700">{{ $stats['approved_by_leader'] }}</div>
                        <div class="text-xs text-blue-600 font-medium mt-1">Disetujui Atasan</div>
                    </div>

                    {{-- Disetujui HRD --}}
                    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-green-700">{{ $stats['approved_by_hrd'] }}</div>
                        <div class="text-xs text-green-600 font-medium mt-1">Disetujui HRD</div>
                    </div>

                    {{-- Ditolak --}}
                    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-red-700">{{ $stats['rejected'] }}</div>
                        <div class="text-xs text-red-600 font-medium mt-1">Ditolak</div>
                    </div>

                    {{-- Selesai --}}
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-3 text-center hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="text-xl font-bold text-purple-700">{{ $stats['completed'] }}</div>
                        <div class="text-xs text-purple-600 font-medium mt-1">Selesai</div>
                    </div>
                </div>
            </div>

            {{-- TABEL RIWAYAT CUTI --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Jenis & Periode
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Durasi
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Timeline
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
                                            {{ $application->leave_type == 'tahunan' ? 'bg-blue-100' : 'bg-green-100' }} 
                                            flex items-center justify-center">
                                            @if($application->leave_type == 'tahunan')
                                                <span class="text-blue-600 text-sm">🏖️</span>
                                            @else
                                                <span class="text-green-600 text-sm">🏥</span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 capitalize">
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
                                    <div class="text-sm font-bold text-gray-900">{{ $application->total_days }} hari</div>
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
                                    
                                    <div class="space-y-2">
                                        {{-- Status Badge --}}
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $config['color'] }}">
                                            {{ $config['icon'] }}
                                            <span class="ml-1">{{ ucwords($text) }}</span>
                                        </span>
                                        
                                        {{-- Badge "Selesai" untuk cuti yang sudah berlalu --}}
                                        @if($application->status === 'approved_by_hrd' && $application->end_date->isPast())
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Selesai
                                            </div>
                                        @endif
                                        
                                        @if($application->leader_rejection_notes)
                                            <div class="text-xs text-red-600 mt-1 max-w-xs">
                                                Catatan: {{ Str::limit($application->leader_rejection_notes, 50) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @if($application->leader_approval_at)
                                            <div class="text-xs text-green-600 font-medium">
                                                ✅ Disetujui Atasan: {{ $application->leader_approval_at->format('d M Y') }}
                                            </div>
                                        @endif
                                        @if($application->hrd_approval_at)
                                            <div class="text-xs text-green-600 font-medium">
                                                ✅ Disetujui HRD: {{ $application->hrd_approval_at->format('d M Y') }}
                                            </div>
                                        @endif
                                        @if($application->status == 'pending')
                                            <div class="text-xs text-yellow-600 font-medium">
                                                ⏳ Menunggu persetujuan
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('leave-applications.show', $application) }}" 
                                        class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded text-xs font-medium transition duration-150 ease-in-out">
                                            Detail
                                        </a>

                                        {{-- TOMBOL PDF (Hanya untuk yang sudah disetujui HRD) --}}
                                        @if($application->status === 'approved_by_hrd' && $application->hrd_approval_at)
                                            <div class="flex space-x-1">
                                                <a href="{{ route('leave-applications.view-letter', $application) }}" 
                                                   target="_blank"
                                                   class="bg-blue-500 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded flex items-center transition duration-150 ease-in-out"
                                                   title="Lihat Surat Cuti">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Lihat
                                                </a>
                                                <a href="{{ route('leave-applications.download-letter', $application) }}" 
                                                   class="bg-green-500 hover:bg-green-700 text-white text-xs px-3 py-1 rounded flex items-center transition duration-150 ease-in-out"
                                                   title="Download Surat Cuti PDF">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    PDF
                                                </a>
                                            </div>
                                        @endif

                                        {{-- TOMBOL BATALKAN (Hanya untuk status pending) --}}
                                        @if ($application->status == 'pending' && $application->user_id == auth()->id())
                                            <form action="{{ route('leave-applications.cancel', $application->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin membatalkan?');">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded text-xs font-medium">
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
                                        @if(auth()->user()->role == 'karyawan' && !auth()->user()->division_id)
                                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                                <strong class="font-bold">Perhatian!</strong>
                                                <span class="block sm:inline">Anda belum terdaftar dalam Divisi manapun. Hubungi Admin untuk plotting divisi agar dapat mengajukan cuti.</span>
                                            </div>
                                            
                                            <button disabled class="inline-flex items-center px-6 py-3 bg-gray-400 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest cursor-not-allowed shadow-sm">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                Akun Belum Siap
                                            </button>
                                        @else
                                            <div class="max-w-md mx-auto">
                                                <div class="w-24 h-24 mx-auto mb-4 bg-blue-50 rounded-full flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                                    </svg>
                                                </div>
                                                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pengajuan Cuti</h3>
                                                <p class="text-gray-500 mb-6">Mulai ajukan cuti pertama Anda untuk melihat riwayat di sini.</p>
                                                <a href="{{ route('leave-applications.create') }}" 
                                                   class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    Ajukan Cuti Baru
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($leaveApplications->hasPages())
                <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    {{ $leaveApplications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>