<nav x-data="{ open: false }" class="bg-blue-900 border-b border-blue-800 sticky top-0 z-50 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white backdrop-blur-sm border border-white/20 group-hover:bg-white/20 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-extrabold text-2xl tracking-tight text-white leading-none">C-OPS</span>
                            <span class="text-[10px] uppercase font-bold text-blue-200 tracking-widest">Cuti Operations System</span>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-12 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                        class="text-sm font-bold {{ request()->routeIs('dashboard') ? 'text-white border-white' : 'text-blue-200 border-transparent hover:text-white hover:border-blue-400' }} transition-all">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->role == 'ketua_divisi' || Auth::user()->role == 'hrd')
                    <x-nav-link :href="route('leave-verifications.index')" :active="request()->routeIs('leave-verifications.*')"
                        class="text-sm font-bold {{ request()->routeIs('leave-verifications.*') ? 'text-white border-white' : 'text-blue-200 border-transparent hover:text-white hover:border-blue-400' }} transition-all">
                        {{ __('Verifikasi Cuti') }}
                    </x-nav-link>
                    @endif

                    <x-nav-link :href="route('leave-applications.index')" :active="request()->routeIs('leave-applications.index')"
                        class="text-sm font-bold {{ request()->routeIs('leave-applications.index') ? 'text-white border-white' : 'text-blue-200 border-transparent hover:text-white hover:border-blue-400' }} transition-all">
                        {{ __('Riwayat Saya') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 bg-blue-800 text-blue-100 text-[10px] font-bold uppercase rounded-full tracking-wider border border-blue-700">
                        {{ str_replace('_', ' ', Auth::user()->role) }}
                    </span>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-3 transition duration-150 ease-in-out group cursor-pointer focus:outline-none">
                                <div class="text-right hidden md:block">
                                    <div class="text-sm font-bold text-white group-hover:text-blue-200 transition">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-blue-300">{{ Auth::user()->email }}</div>
                                </div>
                                
                                {{-- FOTO PROFIL / INISIAL --}}
                                <div class="w-10 h-10 rounded-full bg-white/10 border-2 border-white/20 flex items-center justify-center overflow-hidden">
                                    @if (Auth::user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-white font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    @endif
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-100 mb-2">
                                <p class="text-xs text-gray-400 uppercase font-bold">Manage Account</p>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')" class="hover:bg-blue-50 hover:text-blue-600 transition">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="text-red-600 hover:bg-red-50 hover:text-red-700 transition"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-blue-200 hover:text-white hover:bg-blue-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-blue-800 border-t border-blue-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('leave-applications.index')" :active="request()->routeIs('leave-applications.index')" class="text-white">
                {{ __('Riwayat Cuti') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>