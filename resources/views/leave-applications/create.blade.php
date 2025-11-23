<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Pengajuan Cuti Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Informasi Kuota --}}
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Sisa Kuota Cuti Tahunan Anda: 
                                    <span class="font-bold">{{ auth()->user()->annual_leave_quota }} hari</span>
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- Tampilkan Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
                            <strong class="font-medium">Whoops! Ada yang salah:</strong>
                            <ul class="mt-2 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Formulir --}}
                    <form method="POST" action="{{ route('leave-applications.store') }}" enctype="multipart/form-data" id="leaveForm">
                        @csrf

                        {{-- Field Tersembunyi untuk Tanggal Pengajuan Otomatis --}}
                        <input type="hidden" name="application_date" id="application_date" value="{{ date('Y-m-d') }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jenis Cuti -->
                            <div class="md:col-span-2">
                                <label for="leave_type" class="block font-medium text-sm text-gray-700">Jenis Cuti *</label>
                                <select id="leave_type" name="leave_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Pilih Jenis Cuti</option>
                                    <option value="tahunan" {{ old('leave_type') == 'tahunan' ? 'selected' : '' }}>
                                        Cuti Tahunan (Sisa: {{ auth()->user()->annual_leave_quota }} hari)
                                    </option>
                                    <option value="sakit" {{ old('leave_type') == 'sakit' ? 'selected' : '' }}>
                                        Cuti Sakit
                                    </option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1" id="leaveTypeHelp">
                                    Pilih jenis cuti sesuai kebutuhan
                                </p>
                            </div>

                            <!-- Informasi Tanggal Pengajuan -->
                            <div class="md:col-span-2 p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Tanggal Pengajuan: <strong>{{ date('d F Y') }}</strong></span>
                                </div>
                            </div>

                            <!-- Tanggal Mulai -->
                            <div>
                                <label for="start_date" class="block font-medium text-sm text-gray-700">Tanggal Mulai Cuti *</label>
                                <input id="start_date" 
                                       name="start_date" 
                                       type="date" 
                                       value="{{ old('start_date') }}"
                                       min="{{ date('Y-m-d', strtotime('+3 days')) }}"
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                       required />
                                <p class="text-xs text-gray-500 mt-1" id="startDateHelp">
                                    Minimal H+3 dari hari ini ({{ date('d/m/Y') }})
                                </p>
                            </div>

                            <!-- Tanggal Selesai -->
                            <div>
                                <label for="end_date" class="block font-medium text-sm text-gray-700">Tanggal Selesai Cuti *</label>
                                <input id="end_date" 
                                       name="end_date" 
                                       type="date" 
                                       value="{{ old('end_date') }}"
                                       min="{{ date('Y-m-d', strtotime('+4 days')) }}"
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                       required />
                                <p class="text-xs text-gray-500 mt-1" id="endDateHelp">
                                    Harus setelah tanggal mulai
                                </p>
                            </div>

                            <!-- Informasi Hari & Kuota -->
                            <div class="md:col-span-2 p-4 bg-gray-50 rounded-lg" id="dateInfo" style="display: none;">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Total Hari Kerja:</span>
                                        <span class="font-medium ml-2" id="totalDays">0 hari</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Sisa Kuota setelah cuti:</span>
                                        <span class="font-medium ml-2" id="remainingQuota">{{ auth()->user()->annual_leave_quota }} hari</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Alasan Cuti -->
                            <div class="md:col-span-2">
                                <label for="reason" class="block font-medium text-sm text-gray-700">Alasan Cuti *</label>
                                <textarea id="reason" 
                                          name="reason" 
                                          rows="3"
                                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                          placeholder="Jelaskan alasan cuti Anda secara detail..."
                                          required>{{ old('reason') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    Minimal 10 karakter. Jelaskan alasan cuti dengan jelas.
                                </p>
                            </div>

                            <!-- Alamat Selama Cuti -->
                            <div class="md:col-span-2">
                                <label for="address_during_leave" class="block font-medium text-sm text-gray-700">Alamat Selama Cuti *</label>
                                <input id="address_during_leave" 
                                       name="address_during_leave" 
                                       type="text" 
                                       value="{{ old('address_during_leave') }}"
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       placeholder="Contoh: Jl. Contoh No. 123, Jakarta"
                                       required />
                                <p class="text-xs text-gray-500 mt-1">
                                    Alamat lengkap tempat Anda selama cuti
                                </p>
                            </div>

                            <!-- Nomor Darurat -->
                            <div>
                                <label for="emergency_contact" class="block font-medium text-sm text-gray-700">Nomor Darurat *</label>
                                <input id="emergency_contact" 
                                       name="emergency_contact" 
                                       type="text" 
                                       value="{{ old('emergency_contact') }}"
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       placeholder="Contoh: 081234567890"
                                       required />
                                <p class="text-xs text-gray-500 mt-1">
                                    Nomor yang dapat dihubungi selama cuti
                                </p>
                            </div>

                            <!-- Lampiran Surat Dokter -->
                            <div id="attachmentField">
                                <label for="attachment_path" class="block font-medium text-sm text-gray-700">
                                    Lampiran Surat Dokter
                                    <span id="attachmentRequired" class="text-red-500">*</span>
                                </label>
                                <input id="attachment_path" 
                                       name="attachment_path" 
                                       type="file" 
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       accept=".pdf,.jpg,.jpeg,.png" />
                                <p class="text-xs text-gray-500 mt-1" id="attachmentHelp">
                                    Upload surat dokter (PDF/JPG/PNG, maks. 2MB) - <span class="font-medium" id="attachmentStatus">Opsional</span>
                                </p>
                            </div>
                        </div>

                        {{-- Informasi Penting --}}
                        <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <h4 class="text-sm font-medium text-yellow-800 mb-2">📋 Informasi Penting</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• <strong>Cuti Tahunan:</strong> Minimal H+3, mengurangi kuota cuti tahunan</li>
                                <li>• <strong>Cuti Sakit:</strong> Wajib lampirkan surat dokter, tidak mengurangi kuota</li>
                                <li>• Pastikan tidak ada cuti yang overlapping dengan periode yang sama</li>
                                <li>• Pengajuan akan diverifikasi oleh atasan dan HRD</li>
                                <li>• <strong>Tanggal Pengajuan:</strong> {{ date('d F Y') }}</li>
                            </ul>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('dashboard') }}" 
                               class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out mr-4">
                                Batal
                            </a>
                            
                            <button type="submit" 
                                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium"
                                    id="submitBtn">
                                📨 Ajukan Cuti
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk Real-time Validation & Calculation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const leaveTypeSelect = document.getElementById('leave_type');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const dateInfoDiv = document.getElementById('dateInfo');
            const totalDaysSpan = document.getElementById('totalDays');
            const remainingQuotaSpan = document.getElementById('remainingQuota');
            const attachmentRequired = document.getElementById('attachmentRequired');
            const attachmentHelp = document.getElementById('attachmentHelp');
            const attachmentStatus = document.getElementById('attachmentStatus');
            const submitBtn = document.getElementById('submitBtn');

            const userQuota = {{ auth()->user()->annual_leave_quota }};
            const today = new Date();
            const minStartDate = new Date(today);
            minStartDate.setDate(today.getDate() + 3); // H+3 untuk cuti tahunan

            // Format tanggal untuk display
            function formatDate(date) {
                return date.toLocaleDateString('id-ID', { 
                    day: '2-digit', 
                    month: '2-digit', 
                    year: 'numeric' 
                });
            }

            // Fungsi hitung hari kerja (Senin-Jumat)
            function calculateWorkingDays(start, end) {
                let count = 0;
                let current = new Date(start);
                const endDate = new Date(end);
                
                while (current <= endDate) {
                    const day = current.getDay();
                    if (day !== 0 && day !== 6) { // Bukan Minggu (0) dan Sabtu (6)
                        count++;
                    }
                    current.setDate(current.getDate() + 1);
                }
                return count;
            }

            // Fungsi update informasi tanggal
            function updateDateInfo() {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                const leaveType = leaveTypeSelect.value;

                if (startDate && endDate && new Date(startDate) <= new Date(endDate)) {
                    const totalDays = calculateWorkingDays(startDate, endDate);
                    totalDaysSpan.textContent = totalDays + ' hari';
                    
                    if (leaveType === 'tahunan') {
                        const remaining = userQuota - totalDays;
                        remainingQuotaSpan.textContent = remaining + ' hari';
                        remainingQuotaSpan.className = remaining < 0 ? 'font-medium text-red-600' : 'font-medium text-green-600';
                    } else {
                        remainingQuotaSpan.textContent = userQuota + ' hari';
                        remainingQuotaSpan.className = 'font-medium text-gray-600';
                    }
                    
                    dateInfoDiv.style.display = 'block';
                } else {
                    dateInfoDiv.style.display = 'none';
                }
            }

            // Fungsi toggle field lampiran
            function toggleAttachmentField() {
                const isSakit = leaveTypeSelect.value === 'sakit';
                
                if (isSakit) {
                    attachmentRequired.style.display = 'inline';
                    attachmentStatus.textContent = 'Wajib';
                    attachmentStatus.className = 'font-medium text-red-600';
                    document.getElementById('attachment_path').required = true;
                    
                    // Untuk cuti sakit, kurangi batasan tanggal minimal
                    startDateInput.min = "{{ date('Y-m-d') }}";
                    document.getElementById('startDateHelp').textContent = 'Dapat diajukan H-0 untuk cuti sakit';
                } else {
                    attachmentRequired.style.display = 'none';
                    attachmentStatus.textContent = 'Opsional';
                    attachmentStatus.className = 'font-medium text-gray-600';
                    document.getElementById('attachment_path').required = false;
                    
                    // Untuk cuti tahunan, tambah batasan H+3
                    startDateInput.min = "{{ date('Y-m-d', strtotime('+3 days')) }}";
                    document.getElementById('startDateHelp').textContent = 'Minimal H+3 dari hari ini ({{ date('d/m/Y') }})';
                }
            }

            // Event listeners
            leaveTypeSelect.addEventListener('change', function() {
                toggleAttachmentField();
                updateDateInfo();
                
                // Reset tanggal jika jenis cuti berubah
                if (this.value === 'sakit') {
                    startDateInput.min = "{{ date('Y-m-d') }}";
                } else {
                    startDateInput.min = "{{ date('Y-m-d', strtotime('+3 days')) }}";
                }
            });

            startDateInput.addEventListener('change', function() {
                // Update min end_date berdasarkan start_date
                if (this.value) {
                    const minEndDate = new Date(this.value);
                    minEndDate.setDate(minEndDate.getDate() + 1);
                    endDateInput.min = minEndDate.toISOString().split('T')[0];
                }
                updateDateInfo();
            });

            endDateInput.addEventListener('change', updateDateInfo);

            // Validasi form sebelum submit
            document.getElementById('leaveForm').addEventListener('submit', function(e) {
                const leaveType = leaveTypeSelect.value;
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                
                if (leaveType === 'tahunan') {
                    const totalDays = calculateWorkingDays(startDate, endDate);
                    if (totalDays > userQuota) {
                        e.preventDefault();
                        alert('❌ Kuota cuti tahunan tidak mencukupi! Sisa kuota: ' + userQuota + ' hari, butuh: ' + totalDays + ' hari.');
                        return false;
                    }
                    
                    // Validasi H-3 untuk cuti tahunan
                    const today = new Date();
                    const start = new Date(startDate);
                    const minDate = new Date(today);
                    minDate.setDate(today.getDate() + 3);
                    
                    if (start < minDate) {
                        e.preventDefault();
                        alert('❌ Cuti tahunan harus diajukan minimal H-3 (3 hari kerja) sebelum tanggal mulai.');
                        return false;
                    }
                }
                
                // Validasi untuk cuti sakit
                if (leaveType === 'sakit') {
                    const attachment = document.getElementById('attachment_path').files[0];
                    if (!attachment) {
                        e.preventDefault();
                        alert('❌ Untuk cuti sakit, wajib melampirkan surat dokter.');
                        return false;
                    }
                }
            });

            // Initial setup
            toggleAttachmentField();
            
            // Set min date untuk end_date berdasarkan start_date jika sudah ada value
            if (startDateInput.value) {
                const minEndDate = new Date(startDateInput.value);
                minEndDate.setDate(minEndDate.getDate() + 1);
                endDateInput.min = minEndDate.toISOString().split('T')[0];
            }
        });
    </script>
</x-app-layout>