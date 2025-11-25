{{-- resources/views/admin/holidays/import.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Hari Libur dari Google Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('warning'))
                        <div class="mb-6 p-4 bg-yellow-100 text-yellow-700 rounded-lg border border-yellow-200">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <div class="mb-6 p-4 bg-green-50 rounded-lg">
                        <h3 class="font-semibold text-green-800">📅 Google Calendar Import</h3>
                        <p class="text-sm text-green-600 mt-2">
                            Sistem akan mengambil data hari libur resmi Indonesia dari Google Calendar.
                            <br>✅ Termasuk <strong>Libur Nasional</strong> dan <strong>Cuti Bersama</strong>
                            <br>✅ Data <strong>semua tahun</strong> tersedia
                            <br>✅ Selalu <strong>up-to-date</strong>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.holidays.import') }}">
                        @csrf

                        <div class="space-y-6">
                            <!-- Import Type -->
                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-2">
                                    Pilih Jenis Import *
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="import_type" value="all_years" checked 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">
                                            <strong>Import Semua Tahun</strong> (Rekomendasi)
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 ml-6">
                                        Import semua data hari libur yang tersedia dari Google Calendar
                                    </p>
                                    
                                    <label class="flex items-center">
                                        <input type="radio" name="import_type" value="specific_year"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">
                                            Import Tahun Tertentu
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- Tahun Specific (Conditional) -->
                            <div id="yearSelection" class="hidden">
                                <label for="year" class="block font-medium text-sm text-gray-700">
                                    Pilih Tahun *
                                </label>
                                <select name="year" id="year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Pilih Tahun</option>
                                    @foreach($availableYears as $availableYear)
                                        <option value="{{ $availableYear }}">{{ $availableYear }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">
                                    Tahun yang tersedia di Google Calendar
                                </p>
                            </div>

                            <!-- Informasi Data -->
                            <div class="p-4 bg-blue-50 rounded-lg">
                                <h4 class="font-semibold text-blue-800 text-sm">📊 Data yang akan diimport:</h4>
                                <ul class="text-xs text-blue-600 mt-2 space-y-1">
                                    <li>• Libur Nasional Indonesia</li>
                                    <li>• Cuti Bersama</li>
                                    <li>• Hari besar keagamaan</li>
                                    <li>• Data historis dan future dates</li>
                                </ul>
                            </div>

                            <!-- Tombol -->
                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('admin.holidays.index') }}" 
                                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                    🚀 Import dari Google Calendar
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const importTypeRadios = document.querySelectorAll('input[name="import_type"]');
            const yearSelection = document.getElementById('yearSelection');
            
            function toggleYearSelection() {
                const specificYearSelected = document.querySelector('input[name="import_type"]:checked').value === 'specific_year';
                yearSelection.classList.toggle('hidden', !specificYearSelected);
                
                if (specificYearSelected) {
                    document.getElementById('year').required = true;
                } else {
                    document.getElementById('year').required = false;
                }
            }
            
            importTypeRadios.forEach(radio => {
                radio.addEventListener('change', toggleYearSelection);
            });
            
            // Initial state
            toggleYearSelection();
        });
    </script>
</x-app-layout>