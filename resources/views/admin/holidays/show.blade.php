<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Hari Libur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Detail Hari Libur</h3>
                        <a href="{{ route('admin.holidays.index') }}" 
                           class="text-blue-600 hover:text-blue-900">
                            ← Kembali ke Daftar
                        </a>
                    </div>

                    <div class="space-y-6">
                        <!-- Informasi Hari Libur -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-lg text-gray-800 mb-4">{{ $holiday->name }}</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500">Tanggal:</span>
                                    <p class="font-medium">{{ $holiday->date->format('d F Y') }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm text-gray-500">Jenis:</span>
                                    <p class="font-medium">
                                        @php
                                            $typeLabels = [
                                                'national' => 'Libur Nasional',
                                                'company' => 'Libur Perusahaan', 
                                                'joint_leave' => 'Cuti Bersama'
                                            ];
                                        @endphp
                                        {{ $typeLabels[$holiday->type] ?? $holiday->type }}
                                    </p>
                                </div>
                                
                                <div>
                                    <span class="text-sm text-gray-500">Berulang Setiap Tahun:</span>
                                    <p class="font-medium">
                                        {{ $holiday->is_recurring ? 'Ya' : 'Tidak' }}
                                    </p>
                                </div>
                                
                                <div>
                                    <span class="text-sm text-gray-500">Dibuat Pada:</span>
                                    <p class="font-medium">{{ $holiday->created_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($holiday->description)
                                <div class="mt-4">
                                    <span class="text-sm text-gray-500">Deskripsi:</span>
                                    <p class="mt-1 text-gray-700">{{ $holiday->description }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.holidays.edit', $holiday) }}" 
                               class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('admin.holidays.destroy', $holiday) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus hari libur ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>