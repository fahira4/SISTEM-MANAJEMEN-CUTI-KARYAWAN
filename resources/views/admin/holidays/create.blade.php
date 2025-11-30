<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Hari Libur Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('admin.holidays.store') }}">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">
                                    Nama Hari Libur *
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       value="{{ old('name') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date" class="block font-medium text-sm text-gray-700">
                                    Tanggal *
                                </label>
                                <input type="date" 
                                       name="date" 
                                       id="date"
                                       value="{{ old('date') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                       required>
                                @error('date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type" class="block font-medium text-sm text-gray-700">
                                    Jenis Hari Libur *
                                </label>
                                <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="national" {{ old('type') == 'national' ? 'selected' : '' }}>Libur Nasional</option>
                                    <option value="company" {{ old('type') == 'company' ? 'selected' : '' }}>Libur Perusahaan</option>
                                    <option value="joint_leave" {{ old('type') == 'joint_leave' ? 'selected' : '' }}>Cuti Bersama</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block font-medium text-sm text-gray-700">
                                    Deskripsi
                                </label>
                                <textarea name="description" 
                                          id="description"
                                          rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_recurring" 
                                           value="1"
                                           {{ old('is_recurring') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Berulang setiap tahun</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">
                                    Centang jika hari libur ini terjadi setiap tahun pada tanggal yang sama
                                </p>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('admin.holidays.index') }}" 
                                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Simpan Hari Libur
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>