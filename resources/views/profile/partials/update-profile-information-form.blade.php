<section>
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <label class="text-sm font-medium text-gray-700">Foto Profil</label>
                <div class="text-xs text-gray-500">Max. 2MB</div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="shrink-0 relative">
                    @if($user->profile_photo_path)
                        <img class="h-20 w-20 object-cover rounded-2xl border-2 border-white shadow-md" 
                             src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                             alt="Foto Profil">
                    @else
                        <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold shadow-md">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    <label class="block cursor-pointer">
                        <span class="sr-only">Pilih foto profil</span>
                        <input type="file" 
                               id="profile_photo" 
                               name="profile_photo" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors"
                               accept="image/*"
                        />
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF (Maks. 2MB)</p>
                    <x-input-error class="mt-1" :messages="$errors->get('profile_photo')" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
           
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-sm font-medium text-gray-700 mb-2" />
                <x-text-input id="name" name="name" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700 mb-2" />
                <x-text-input id="email" name="email" type="email" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-1" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-amber-600">
                            {{ __('Email Anda belum terverifikasi.') }}
                            <button form="send-verification" class="underline text-amber-700 hover:text-amber-800 font-medium">
                                {{ __('Kirim ulang verifikasi') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 text-sm text-green-600 font-medium">
                                {{ __('Tautan verifikasi baru telah dikirim!') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="phone_number" :value="__('Nomor Telepon')" class="text-sm font-medium text-gray-700 mb-2" />
                <x-text-input id="phone_number" name="phone_number" type="text" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            :value="old('phone_number', $user->phone_number)" autocomplete="tel" placeholder="08xxxxxxxxxx" />
                <x-input-error class="mt-1" :messages="$errors->get('phone_number')" />
            </div>

            <div>
                <x-input-label for="role" :value="__('Role')" class="text-sm font-medium text-gray-700 mb-2" />
                <div class="relative">
                    <x-text-input id="role" name="role" type="text" class="block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500 cursor-not-allowed" 
                                :value="ucfirst($user->role)" disabled readonly />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Role tidak dapat diubah</p>
            </div>
        </div>

        <div>
            <x-input-label for="address" :value="__('Alamat Lengkap')" class="text-sm font-medium text-gray-700 mb-2" />
            <textarea id="address" name="address" rows="3" 
                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                      placeholder="Tulis alamat lengkap Anda...">{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-1" :messages="$errors->get('address')" />
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <div class="flex items-center space-x-3">
                <x-primary-button class="rounded-lg px-6 py-2.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Simpan Perubahan') }}
                </x-primary-button>

                @if (session('status') === 'profile-updated')
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                         class="flex items-center text-sm text-green-600 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ __('Data berhasil disimpan!') }}
                    </div>
                @endif
            </div>
            
            <button type="button" onclick="resetForm()" class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                Reset Form
            </button>
        </div>
    </form>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <script>
        function resetForm() {
            document.getElementById('name').value = '{{ $user->name }}';
            document.getElementById('email').value = '{{ $user->email }}';
            document.getElementById('phone_number').value = '{{ $user->phone_number }}';
            document.getElementById('address').value = '{{ $user->address }}';
            document.getElementById('profile_photo').value = '';
        }
    </script>
</section>