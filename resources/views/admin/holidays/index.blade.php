<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Hari Libur & Cuti Bersama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Di bagian header dengan tombol --}}
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-4">
                            <h3 class="text-lg font-semibold">Daftar Hari Libur</h3>
                            
                            {{-- Year Filter --}}
                            <select id="yearFilter" class="border-gray-300 rounded-md shadow-sm">
                                @foreach($availableYears as $availableYear)
                                    <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                        {{ $availableYear }}
                                    </option>
                                @endforeach
                                <option value="{{ date('Y') }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                    {{ date('Y') }}
                                </option>
                            </select>
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.holidays.import-form') }}" 
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                Import from Google Calendar
                            </a>
                            
                            <a href="{{ route('admin.holidays.create') }}" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Manual
                            </a>
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-3 px-4 text-left">Nama</th>
                                    <th class="py-3 px-4 text-left">Tanggal</th>
                                    <th class="py-3 px-4 text-left">Jenis</th>
                                    <th class="py-3 px-4 text-left">Berulang</th>
                                    <th class="py-3 px-4 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($holidays as $holiday)
                                    <tr class="border-b">
                                        <td class="py-3 px-4">
                                            <div class="font-medium">{{ $holiday->name }}</div>
                                            @if($holiday->description)
                                                <div class="text-sm text-gray-500">{{ $holiday->description }}</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            {{ $holiday->date->format('d F Y') }}
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $typeColors = [
                                                    'national' => 'bg-blue-100 text-blue-800',
                                                    'company' => 'bg-green-100 text-green-800',
                                                    'joint_leave' => 'bg-purple-100 text-purple-800'
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $typeColors[$holiday->type] ?? 'bg-gray-100' }}">
                                                {{ $holiday->type == 'national' ? 'Libur Nasional' : 
                                                   ($holiday->type == 'company' ? 'Libur Perusahaan' : 'Cuti Bersama') }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($holiday->is_recurring)
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Setiap Tahun</span>
                                            @else
                                                <span class="text-gray-500 text-sm">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                {{-- ✅ TAMBAH LINK SHOW --}}
                                                <a href="{{ route('admin.holidays.show', $holiday) }}" 
                                                class="text-green-600 hover:text-green-900" title="Lihat Detail">
                                                    👁️ Detail
                                                </a>
                                                <a href="{{ route('admin.holidays.edit', $holiday) }}" 
                                                class="text-blue-600 hover:text-blue-900">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.holidays.destroy', $holiday) }}" 
                                                    method="POST" 
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus hari libur ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-4 text-center text-gray-500">
                                            Tidak ada data hari libur untuk tahun {{ $year }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('yearFilter').addEventListener('change', function() {
            const year = this.value;
            window.location.href = `{{ route('admin.holidays.index') }}?year=${year}`;
        });
    </script>
</x-app-layout>