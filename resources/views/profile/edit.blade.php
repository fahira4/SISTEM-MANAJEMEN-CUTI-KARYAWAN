<x-app-layout>
    <div class="relative bg-blue-900 pb-24 pt-8 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="text-center flex-1">
                    <h1 class="text-3xl font-bold text-white mb-2">
                        Profile
                    </h1>
                    <p class="text-blue-200 text-lg">
                        Kelola informasi akun dan keamanan Anda
                    </p>
                </div>
                <div class="flex items-center space-x-2 text-blue-200">
                    <div class="h-2 w-2 rounded-full bg-green-400"></div>
                    <span class="font-medium">{{ Auth::user()->role == 'admin' ? 'Administrator' : ucfirst(Auth::user()->role) }}</span>
                </div>
            </div>
        </div>
    </div>

    @if (session('info'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg shadow-sm mb-8">
            {{ session('info') }}
        </div>
    </div>
    @endif

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 pb-12 relative z-10">
            
            @if(in_array(Auth::user()->role, ['karyawan', 'ketua_divisi']))
            <div class="bg-white rounded-xl shadow-lg border-t-4 border-orange-500 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-center mb-6"> 
                            <h3 class="text-xl font-semibold">Kuota Cuti Tahunan</h3>
                        </div>
                        
                        <div class="flex items-center justify-around space-x-6">
                            <div class="text-center flex-1">
                                <div class="text-2xl font-bold">{{ Auth::user()->annual_leave_quota ?? 12 }}</div>
                                <div class="text-gray-900 text-sm mt-1">Sisa Kuota</div> 
                            </div>
                            <div class="h-12 w-px bg-blue-400"></div>
                            <div class="text-center flex-1">
                                <div class="text-2xl font-bold">12</div>
                                <div class="text-gray-900 text-sm mt-1">Total Kuota</div> 
                            </div>
                            <div class="h-12 w-px bg-blue-400"></div>
                            <div class="text-center flex-1">
                                @php
                                    $usedQuota = 12 - (Auth::user()->annual_leave_quota ?? 12);
                                @endphp
                                <div class="text-2xl font-bold">{{ $usedQuota }}</div>
                                <div class="text-gray-900 text-sm mt-1">Terpakai</div> 
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-white/20 rounded-xl ml-4"> 
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-blue-400/30"> 
                    <div class="text-sm text-gray-900 text-center">
                        @if($usedQuota == 0)
                            <span class="font-medium">Anda belum menggunakan kuota cuti tahunan</span>
                        @elseif($usedQuota < 6)
                            <span class="font-medium">Masih banyak kuota tersisa untuk tahun ini</span>
                        @elseif($usedQuota < 10)
                            <span class="font-medium">Kuota cuti sudah setengah terpakai, gunakan dengan bijak</span>
                        @else
                            <span class="font-medium">Kuota cuti habis</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white rounded-xl shadow-lg border-t-2 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Profil</h3>
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-lg border-t-2 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Keamanan Akun</h3>
                            <div class="p-2 bg-green-50 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                        </div>
                        @include('profile.partials.update-password-form')
                    </div>

                    <div class="bg-white rounded-xl shadow-lg border-t-2 p-6 mt-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Hapus Akun</h3>
                            <div class="p-2 bg-red-50 rounded-lg">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                        </div>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border-t-2 p-6 mt-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Informasi Lainnya</h3>
                        <p class="text-sm text-gray-600 mt-1">Detail informasi karyawan</p>
                    </div>
                    <div class="p-2 bg-purple-50 rounded-lg text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="p-2 bg-blue-100 rounded-lg text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung Sejak</div>
                            <div class="text-sm font-bold text-gray-900">{{ $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('d M Y') : 'Belum diatur' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="p-2 bg-orange-100 rounded-lg text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Masa Kerja</div>
                            <div class="text-sm font-bold text-gray-900">{{ $user->employment_period }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>