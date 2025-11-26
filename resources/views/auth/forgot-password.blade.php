<x-guest-layout>
    <div class="min-h-screen w-full flex bg-white">
        
        <div class="hidden md:flex md:w-1/2 bg-blue-900 relative flex-col justify-center items-center text-white overflow-hidden px-6 lg:px-10">
            
            <div class="absolute top-0 left-0 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 translate-y-1/2"></div>

            <div class="relative z-10 w-full max-w-lg flex flex-col items-center text-center">
                
                <h2 class="text-3xl lg:text-4xl font-bold tracking-tight mb-2">Pemulihan Akun</h2>
                <div class="h-1.5 w-20 bg-blue-400 mx-auto rounded-full mb-6"></div>
                
                <div class="w-64 h-64 relative mb-6">
                    <img src="{{ asset('images/forgot-pass.svg') }}" 
                         alt="Ilustrasi Security" 
                         class="w-full h-full object-contain drop-shadow-2xl">
                </div>

                <p class="text-blue-100 text-lg font-light leading-relaxed">
                    Jangan khawatir. Keamanan data karyawan adalah prioritas kami di PT AMANAH JAYA.
                </p>
            </div>

            <div class="absolute bottom-6 text-blue-300/60 text-xs tracking-wider">
                &copy; {{ date('Y') }} Human Resource Department
            </div>
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md space-y-8">
                
                <div class="md:hidden text-center mb-8">
                    <div class="mx-auto h-16 w-16 bg-blue-900 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
                </div>

                <div class="hidden md:block">
                    <h2 class="text-3xl font-bold text-gray-900">Lupa Kata Sandi?</h2>
                    <p class="mt-2 text-gray-600 text-sm">
                        Masukkan email yang terdaftar, kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <x-text-input id="email" class="block mt-1 w-full pl-10 py-3 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@unhas.ac.id" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center py-4 bg-blue-900 hover:bg-blue-800 focus:ring-blue-500 active:bg-blue-900 text-base font-bold transition duration-150 ease-in-out transform hover:-translate-y-0.5 shadow-lg">
                            {{ __('Kirim Tautan Reset') }}
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </x-primary-button>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-blue-700 hover:text-blue-900 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Halaman Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>