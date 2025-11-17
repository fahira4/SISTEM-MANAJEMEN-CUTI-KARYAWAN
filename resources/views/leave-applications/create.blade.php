<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Pengajuan Cuti Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tampilkan Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulir --}}
                    {{-- 'enctype' diperlukan untuk upload file --}}
                    <form method="POST" action="{{ route('leave-applications.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="leave_type">Jenis Cuti</label>
                            <select id="leave_type" name="leave_type" class="block mt-1 w-full" required>
                                <option value="tahunan">Cuti Tahunan (Sisa: {{ auth()->user()->annual_leave_quota }} hari)</option>
                                <option value="sakit">Cuti Sakit</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="start_date">Tanggal Mulai Cuti</label>
                            <input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" required />
                        </div>

                        <div class="mt-4">
                            <label for="end_date">Tanggal Selesai Cuti</label>
                            <input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" required />
                        </div>

                        <div class="mt-4">
                            <label for="reason">Alasan Cuti</label>
                            <textarea id="reason" name="reason" class="block mt-1 w-full" required>{{ old('reason') }}</textarea>
                        </div>

                        <div class="mt-4">
                            <label for="address_during_leave">Alamat Selama Cuti</label>
                            <input id="address_during_leave" class="block mt-1 w-full" type="text" name="address_during_leave" :value="old('address_during_leave')" required />
                        </div>

                        <div class="mt-4">
                            <label for="emergency_contact">Nomor Darurat</label>
                            <input id="emergency_contact" class="block mt-1 w-full" type="text" name="emergency_contact" :value="old('emergency_contact')" required />
                        </div>

                        <div class="mt-4">
                            <label for="attachment_path">Lampiran Surat Dokter (Wajib jika Cuti Sakit)</label>
                            <input id="attachment_path" class="block mt-1 w-full" type="file" name="attachment_path" />
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ajukan Cuti
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>