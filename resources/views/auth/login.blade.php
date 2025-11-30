<x-guest-layout>
    <div class="min-h-screen w-full flex bg-white">
        
<div class="hidden md:flex md:w-1/2 bg-blue-900 relative flex-col justify-center items-center text-white overflow-hidden px-6 lg:px-10">
    
    <div class="absolute top-0 left-0 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 translate-y-1/2"></div>

    <div class="relative z-10 w-full max-w-2xl flex flex-col items-center">
        
        <div class="text-center mb-10 w-full">
            <h2 class="text-3xl lg:text-4xl font-bold tracking-tight mb-2">Cuti-Operation System (C-OPS)</h2>
            <div class="h-1.5 w-20 bg-blue-400 mx-auto rounded-full mb-3"></div>
            <p class="text-blue-200 text-base lg:text-lg font-light tracking-wide uppercase">PT AMANAH JAYA</p>
        </div>

    <div class="flex flex-col lg:flex-row items-center justify-center gap-6 w-full px-4">
            
            <div class="w-full lg:w-5/12 flex justify-center lg:justify-end">
                <div class="w-60 h-60 lg:w-80 lg:h-80 relative transition-all duration-500"> 
                    <img src="{{ asset('images/login-illustration.svg') }}" 
                         alt="Ilustrasi Cuti" 
                         class="absolute inset-0 w-full h-full object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500">
                </div>
            </div>

            <div class="w-full lg:w-7/12 space-y-5 pl-4">
                
                <div class="flex items-center space-x-4 group p-3 rounded-xl hover:bg-white/5 transition-all cursor-default">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:bg-blue-500 transition-colors">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg">Paperless</h3>
                        <p class="text-sm text-blue-200">Pengajuan cuti serba digital</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 group p-3 rounded-xl hover:bg-white/5 transition-all cursor-default">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:bg-blue-500 transition-colors">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg">Realtime</h3>
                        <p class="text-sm text-blue-200">Pantau status approval terkini</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 group p-3 rounded-xl hover:bg-white/5 transition-all cursor-default">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:bg-blue-500 transition-colors">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg">Cek Kuota</h3>
                        <p class="text-sm text-blue-200">Sisa cuti terhitung otomatis</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="absolute bottom-6 text-blue-300/60 text-xs tracking-wider">
        &copy; {{ date('Y') }} Human Resource Departmen
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
            </div>
        </div>
    </div>
</x-guest-layout>