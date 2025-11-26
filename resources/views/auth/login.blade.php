<x-guest-layout>
    <div class="min-h-screen w-full flex bg-white">
        
        <div class="hidden md:flex md:w-1/2 bg-blue-900 relative flex-col justify-center items-center text-white overflow-hidden">
            
            <div class="absolute inset-0 opacity-10" 
                 style="background-image: radial-gradient(#ffffff 2px, transparent 2px); background-size: 40px 40px;">
            </div>
            
            <div class="absolute top-0 left-0 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 translate-y-1/2"></div>

            <div class="relative z-10 p-12 text-center max-w-lg">
                <div class="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-white/10 backdrop-blur-sm border border-white/20 shadow-2xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                
                <h2 class="text-4xl font-bold mb-4 tracking-tight">Sistem Manajemen Cuti</h2>
                <p class="text-blue-200 text-lg font-light mb-8">Universitas Hasanuddin</p>

                <div class="space-y-4 text-left bg-white/5 p-6 rounded-2xl border border-white/10">
                    <div class="flex items-center space-x-3">
                        <div class="p-1 bg-blue-500 rounded-full"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                        <span class="text-blue-50">Pengajuan Cuti Digital (Paperless)</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="p-1 bg-blue-500 rounded-full"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                        <span class="text-blue-50">Approval Berjenjang & Realtime</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="p-1 bg-blue-500 rounded-full"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                        <span class="text-blue-50">Cek Sisa Kuota Kapan Saja</span>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-6 text-blue-300/60 text-xs">
                &copy; {{ date('Y') }} Human Resource Department
            </div>
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white overflow-y-auto">
            <div class="w-full max-w-md space-y-8">
                
                <div class="md:hidden text-center mb-8">
                    <div class="mx-auto h-16 w-16 bg-blue-900 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Leave Management</h2>
                    <p class="text-gray-500">Universitas Hasanuddin</p>
                </div>

                <div class="hidden md:block mb-10">
                    <h2 class="text-3xl font-bold text-gray-900">Selamat Datang</h2>
                    <p class="mt-2 text-gray-600">Silakan masuk menggunakan akun karyawan Anda.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email atau Username')" class="text-gray-700 font-semibold" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <x-text-input id="email" class="block mt-1 w-full pl-10 py-3 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan email anda" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <x-text-input id="password" class="block mt-1 w-full pl-10 py-3 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-900 shadow-sm focus:ring-blue-500" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Ingat Saya') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-700 hover:text-blue-900 font-medium" href="{{ route('password.request') }}">
                                {{ __('Lupa Password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center py-4 bg-blue-900 hover:bg-blue-800 focus:ring-blue-500 active:bg-blue-900 text-base font-bold transition duration-150 ease-in-out transform hover:-translate-y-0.5 shadow-lg">
                            {{ __('MASUK') }}
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </x-primary-button>
                    </div>
                </form>

                <div class="mt-6 text-center text-sm text-gray-400">
                    Sistem Informasi Semester 3 - Project Final Web
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>