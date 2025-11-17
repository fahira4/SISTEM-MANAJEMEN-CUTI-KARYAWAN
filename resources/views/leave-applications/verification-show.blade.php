<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Verifikasi Cuti: ') }} {{ $application->applicant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            Silakan periksa input Anda.
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="font-semibold">Pemohon</h3>
                            <p>{{ $application->applicant->name }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold">Divisi</h3>
                            <p>{{ $application->applicant->division->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold">Jenis Cuti</h3>
                            <p>{{ $application->leave_type }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold">Tanggal</h3>
                            <p>{{ $application->start_date->format('d M Y') }} - {{ $application->end_date->format('d M Y') }} ({{ $application->total_days }} hari)</p>
                        </div>
                        <div class="col-span-2">
                            <h3 class="font-semibold">Alasan</h3>
                            <p>{{ $application->reason }}</p>
                        </div>
                        <div class="col-span-2">
                            <h3 class="font-semibold">Alamat Selama Cuti</h3>
                            <p>{{ $application->address_during_leave }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold">Kontak Darurat</h3>
                            <p>{{ $application->emergency_contact }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold">Lampiran (Surat Sakit)</h3>
                            @if ($application->attachment_path)
                                <a href="{{ asset('storage/' . $application->attachment_path) }}" target="_blank" class="text-blue-500 hover:underline">
                                    Lihat Lampiran
                                </a>
                            @else
                                <p>-</p>
                            @endif
                        </div>
                    </div>

                    <hr class="my-6">

                    <div class="flex items-center space-x-4">

                        {{-- Tombol Approve --}}
                        <form action="{{ route('leave-verifications.approve', $application->id) }}" method="POST">
                            @csrf
                            <button type.="submit" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Approve (Setujui)
                            </button>
                        </form>

                        {{-- Tombol Reject --}}
                        <form action="{{ route('leave-verifications.reject', $application->id) }}" method="POST">
                            @csrf
                            <div class="flex items-center space-x-2">
                                <label for="rejection_notes" class="sr-only">Alasan Penolakan</label>
                                <input type="text" name="rejection_notes" id="rejection_notes" 
                                       class="block w-full" 
                                       placeholder="Alasan Penolakan (Wajib diisi)" 
                                       required>
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Reject (Tolak)
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>