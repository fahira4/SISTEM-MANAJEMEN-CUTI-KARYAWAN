<x-app-layout>
    <div class="relative bg-blue-900 pb-24 pt-8 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="text-left">
                    <h1 class="text-3xl font-bold text-white mb-2">
                        Form Pengajuan Cuti Baru
                    </h1>
                    <p class="text-blue-200 text-lg">
                        Isi formulir di bawah ini untuk mengajukan cuti baru. Pastikan semua data yang Anda masukkan sudah benar.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-14 pb-12 relative z-20">
            
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                
                @if(auth()->user()->role == 'karyawan' && !auth()->user()->division_id)
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                        <div class="flex items-start">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-lg"> Akun Belum Siap!</p>
                                <p class="text-sm font-medium mt-1">
                                    Anda belum terdaftar dalam <strong>Divisi</strong> manapun.
                                </p>
                                <p class="text-sm mt-2">
                                    Sistem tidak dapat memproses pengajuan karena tidak ada atasan (Ketua Divisi) yang terhubung. 
                                    Silakan hubungi <strong>Admin</strong> untuk penempatan divisi.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(Auth::user()->role == 'karyawan' && !Auth::user()->isEligibleForAnnualLeave())
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                        <p class="font-semibold"> Perhatian!</p>
                        <p>Anda <strong>belum eligible</strong> untuk cuti tahunan. 
                        Masa kerja Anda: <strong>{{ floor(Auth::user()->months_of_work) }} bulan</strong>. 
                        Syarat cuti tahunan: minimal <strong>12 bulan</strong> masa kerja.</p>
                        <p class="mt-2 text-sm"> Anda masih dapat mengajukan <strong>Cuti Sakit</strong> dengan melampirkan surat dokter.</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('leave-applications.store') }}" enctype="multipart/form-data" id="leaveForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="leave_type" class="block font-medium text-sm text-gray-700">Jenis Cuti *</label>
                            <select id="leave_type" name="leave_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Pilih Jenis Cuti</option>
                                <option value="tahunan" {{ old('leave_type') == 'tahunan' ? 'selected' : '' }}>
                                    Cuti Tahunan (Sisa: {{ auth()->user()->annual_leave_quota }} hari)
                                    @if(Auth::user()->role == 'karyawan' && !Auth::user()->isEligibleForAnnualLeave())
                                        -  Tidak Eligible
                                    @endif
                                </option>
                                <option value="sakit" {{ old('leave_type') == 'sakit' ? 'selected' : '' }}>
                                    Cuti Sakit
                                </option>
                            </select>
                            @if(Auth::user()->role == 'karyawan' && !Auth::user()->isEligibleForAnnualLeave())
                                <p class="text-xs text-red-600 mt-1">
                                     Anda belum eligible cuti tahunan (masa kerja: {{ floor(Auth::user()->months_of_work) }} bulan)
                                </p>
                            @else
                                <p class="text-xs text-gray-500 mt-1">
                                    Pilih jenis cuti sesuai kebutuhan
                                </p>
                            @endif
                        </div>

                        <div class="md:col-span-2 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Tanggal Pengajuan: <strong>{{ date('d F Y') }}</strong></span>
                            </div>
                        </div>

                        <div>
                            <label for="start_date" class="block font-medium text-sm text-gray-700">Tanggal Mulai Cuti *</label>
                            <input id="start_date" 
                                   name="start_date" 
                                   type="date" 
                                   value="{{ old('start_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+3 days')) }}"
                                   class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                   required />

                                   @error('start_date')
                                        <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                                    @enderror
                                    
                            <p class="text-xs text-gray-500 mt-1" id="startDateHelp">
                                Minimal H+3 dari hari ini ({{ date('d/m/Y') }})
                            </p>
                        </div>

                        <div>
                            <label for="end_date" class="block font-medium text-sm text-gray-700">Tanggal Selesai Cuti *</label>
                            <input id="end_date" 
                                   name="end_date" 
                                   type="date" 
                                   value="{{ old('end_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+4 days')) }}"
                                   class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                   required />
                        </div>

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

                        <div class="md:col-span-2">
                            <label for="reason" class="block font-medium text-sm text-gray-700">Alasan Cuti *</label>
                            <textarea id="reason" 
                                      name="reason" 
                                      rows="3"
                                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                      placeholder="Jelaskan alasan cuti Anda secara detail..."
                                      required
                                      minlength="10">{{ old('reason') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                Minimal 10 karakter. Jelaskan alasan cuti dengan jelas.
                            </p>
                            
                            <div id="reasonError" class="hidden text-red-600 text-sm mt-1">⚠️ Alasan cuti minimal 10 karakter</div>
                           
                            @error('reason')
                                <p class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</p>
                            @enderror
                        </div>

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

                    <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}" 
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out mr-4">
                            Batal
                        </a>
                        
                        @if(Auth::user()->role == 'karyawan' && !Auth::user()->isEligibleForAnnualLeave())
                           
                            <button type="button" 
                                    class="px-6 py-2 bg-gray-400 text-gray-600 rounded-md cursor-not-allowed font-medium"
                                    disabled
                                    id="disabledSubmitBtn">
                                🔒 Tidak Dapat Ajukan Cuti Tahunan
                            </button>

                            <button type="submit" 
                                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium hidden"
                                    id="sakitSubmitBtn">
                                📨 Ajukan Cuti Sakit
                            </button>
                        @else
                            <button type="submit" 
                                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium"
                                    id="submitBtn">
                                📨 Ajukan Cuti
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

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
        
        const disabledSubmitBtn = document.getElementById('disabledSubmitBtn');
        const sakitSubmitBtn = document.getElementById('sakitSubmitBtn');
        const submitBtn = document.getElementById('submitBtn');
        const leaveForm = document.getElementById('leaveForm');

        const reasonTextarea = document.getElementById('reason');
        const reasonError = document.getElementById('reasonError');

        function validateReason() {
            const reason = reasonTextarea.value.trim();
            if (reason.length > 0 && reason.length < 10) {
                reasonError.classList.remove('hidden');
                reasonTextarea.classList.add('border-red-300');
                reasonTextarea.classList.remove('border-gray-300');
                return false;
            } else {
                reasonError.classList.add('hidden');
                reasonTextarea.classList.remove('border-red-300');
                reasonTextarea.classList.add('border-gray-300');
                return true;
            }
        }

        reasonTextarea.addEventListener('input', validateReason);
        reasonTextarea.addEventListener('blur', validateReason);

        const hasDivision = {{ auth()->user()->division_id ? 'true' : 'false' }};
        const userRole = "{{ auth()->user()->role }}";
        const isEligible = {{ auth()->user()->isEligibleForAnnualLeave() ? 'true' : 'false' }};
        const userQuota = {{ auth()->user()->annual_leave_quota ?? 0 }};

        function updateSubmitButton() {
            if (userRole === 'karyawan' && !hasDivision) {
                hideAllButtons();
                return;
            }

            const leaveType = leaveTypeSelect.value;
            
            if (userRole === 'karyawan' && !isEligible) {
                if (leaveType === 'tahunan') {
                    showButton('disabled'); 
                } else if (leaveType === 'sakit') {
                    showButton('sakit');
                } else {
                    hideAllButtons();
                }
            }
            else {
                if (leaveType) {
                    showButton('normal');
                } else {
                    hideAllButtons();
                }
            }
        }

        function hideAllButtons() {
            if(disabledSubmitBtn) disabledSubmitBtn.classList.add('hidden');
            if(sakitSubmitBtn) sakitSubmitBtn.classList.add('hidden');
            if(submitBtn) submitBtn.classList.add('hidden');
        }

        function showButton(type) {
            hideAllButtons();
            if (type === 'disabled' && disabledSubmitBtn) disabledSubmitBtn.classList.remove('hidden');
            if (type === 'sakit' && sakitSubmitBtn) sakitSubmitBtn.classList.remove('hidden');
            if (type === 'normal' && submitBtn) submitBtn.classList.remove('hidden');
        }

        const holidays = @json($holidays ?? []);
        console.log("Daftar Hari Libur dari Database:", holidays);

        function calculateWorkingDays(start, end) {
        let count = 0;
        let current = new Date(start + 'T00:00:00');
        const endDate = new Date(end + 'T00:00:00');
        
        while (current <= endDate) {
            const day = current.getDay();
            
            const year = current.getFullYear();
            const month = String(current.getMonth() + 1).padStart(2, '0'); 
            const dateVal = String(current.getDate()).padStart(2, '0');    
            const dateString = `${year}-${month}-${dateVal}`;

            const isHoliday = holidays.includes(dateString);

            if (isHoliday) {
                console.log(`Skip Tanggal Merah: ${dateString}`);
            }

            if (day !== 0 && day !== 6 && !isHoliday) { 
                count++;
            }
            
            current.setDate(current.getDate() + 1);
        }
        return count;
    }

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

        function toggleAttachmentField() {
            const isSakit = leaveTypeSelect.value === 'sakit';
            if (isSakit) {
                attachmentRequired.style.display = 'inline';
                attachmentStatus.textContent = 'Wajib';
                attachmentStatus.className = 'font-medium text-red-600';
                document.getElementById('attachment_path').required = true;
                
                startDateInput.min = "{{ date('Y-m-d') }}";
                document.getElementById('startDateHelp').textContent = 'Dapat diajukan mulai hari ini';
            } else {
                attachmentRequired.style.display = 'none';
                attachmentStatus.textContent = 'Opsional';
                attachmentStatus.className = 'font-medium text-gray-600';
                document.getElementById('attachment_path').required = false;
                
                startDateInput.min = "{{ date('Y-m-d', strtotime('+3 days')) }}";
                document.getElementById('startDateHelp').textContent = 'Minimal H+3 dari hari ini';
            }
        }

        leaveTypeSelect.addEventListener('change', function() {
            toggleAttachmentField();
            updateDateInfo();
            updateSubmitButton(); 
            startDateInput.value = '';
            endDateInput.value = '';
            dateInfoDiv.style.display = 'none';
        });

        startDateInput.addEventListener('change', function() {
            if (this.value) {
                const minEndDate = new Date(this.value);
                endDateInput.min = this.value; 
            }
            updateDateInfo();
        });

        endDateInput.addEventListener('change', updateDateInfo);

        if (leaveForm) {
            leaveForm.addEventListener('submit', function(e) {
                if (!validateReason()) {
                    e.preventDefault();
                    alert('❌ Alasan cuti harus minimal 10 karakter!');
                    reasonTextarea.focus();
                    return false;
                }

                const leaveType = leaveTypeSelect.value;
                
                if (leaveType === 'tahunan' && userRole === 'karyawan' && !isEligible) {
                    e.preventDefault();
                    alert('⛔ Anda belum berhak mengambil Cuti Tahunan karena masa kerja kurang dari 1 tahun.\nSilakan gunakan Cuti Sakit jika diperlukan.');
                    return false;
                }

                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                if (leaveType === 'tahunan') {
                    const totalDays = calculateWorkingDays(startDate, endDate);
                    if (totalDays > userQuota) {
                        e.preventDefault();
                        alert('❌ Kuota cuti tahunan tidak mencukupi!');
                        return false;
                    }
                }
            });
        }

        updateSubmitButton();
        toggleAttachmentField();
        validateReason();
    });
</script>

</x-app-layout>