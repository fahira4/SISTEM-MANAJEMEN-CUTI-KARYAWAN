<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Izin Cuti - {{ $applicant->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', Arial, sans-serif;
            line-height: 1.4;
            color: #000;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px 40px;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        
        .company-address {
            font-size: 10px;
            color: #666;
            margin-bottom: 1px;
        }
        
        .company-contact {
            font-size: 9px;
            color: #888;
        }
        
        .divider {
            border-top: 1px solid #333;
            margin: 8px 0;
        }
        
        .document-title {
            text-align: center;
            margin: 15px 0 5px 0;
        }
        
        .title-text {
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 3px;
        }
        
        .document-number {
            font-size: 10px;
            color: #666;
        }
        
        .section {
            margin-bottom: 10px;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .section-content {
            margin-left: 15px;
            font-size: 12px;
        }
        
        .numbered-list {
            margin-left: 0;
            padding-left: 15px;
        }
        
        .numbered-list li {
           margin-bottom: 2px;
            line-height: 1.3;
        }
        
        .signature-table {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }
        
        .signature-cell {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 3px;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .compact {
            margin-bottom: 8px;
        }
        
        .paragraph {
            text-align: justify;
            margin-bottom: 8px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">PT. AMANAH JAYA</div>
        <div class="company-address">Jl. Tamalanrea</div>
        <div class="company-contact">Telp: (021) 1234567 | Email: hr@AMANAH.com | Website: www.AMANAHJAYA.com</div>
    </div>

    <div class="divider"></div>

    <div class="document-title">
        <div class="title-text">SURAT IZIN CUTI</div>
        <div class="document-number">No: CUTI/HRD/{{ now()->format('m/Y') }}/{{ str_pad($leave->id, 4, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="section">
        <p class="paragraph">Berdasarkan permohonan cuti yang diajukan dan setelah melalui proses verifikasi, dengan ini:</p>
    </div>

    <div class="section">
        <div class="section-title">PIHAK YANG MEMBERIKAN IZIN:</div>
        <div class="section-content">
            <ol class="numbered-list">
                <li>Nama: {{ $hrdApprover->name ?? 'Daffa' }}</li>
                <li>Jabatan: Human Resources Department</li>
                <li>Divisi: -</li>
            </ol>
        </div>
    </div>

    <div class="section">
        <div class="section-title">PIHAK YANG DIBERIKAN IZIN:</div>
        <div class="section-content">
            <ol class="numbered-list">
                <li>Nama: {{ $applicant->name }}</li>
                <li>Jabatan: {{ $applicant->role == 'ketua_divisi' ? 'Ketua Divisi' : 'Karyawan' }}</li>
                <li>Divisi: {{ $division->name ?? 'Belum ditentukan' }}</li>
            </ol>
        </div>
    </div>

    <div class="section">
        <p class="paragraph">
            {{ $applicant->name }} diberikan izin untuk melaksanakan 
            @if($leave->leave_type == 'tahunan')
                Cuti Tahunan
            @else
                Cuti Sakit
            @endif 
            selama {{ $leave->total_days }} hari kerja pada periode {{ $startDate }} hingga {{ $endDate }}. 
            Cuti ini diajukan dengan alasan: "{{ $leave->reason }}". Selama masa cuti, karyawan 
            berkewajiban untuk melakukan serah terima pekerjaan dan dapat dihubungi melalui 
            kontak darurat: {{ $leave->emergency_contact }}. Alamat selama cuti: {{ $leave->address_during_leave }}.
            @if($leave->leave_type == 'sakit' && $leave->attachment_path)
            Surat keterangan dokter telah dilampirkan sesuai ketentuan.
            @endif
        </p>
    </div>

    <div class="section">
        <div class="section-title">KETENTUAN:</div>
        <div class="section-content">
            <p class="paragraph">
                Karyawan diwajibkan untuk kembali bekerja pada tanggal 
                {{ Carbon\Carbon::parse($leave->end_date)->addDays(1)->locale('id_ID')->translatedFormat('d F Y') }} 
                sesuai jadwal normal. Selama masa cuti, hak dan kewajiban karyawan mengikuti ketentuan 
                yang berlaku di perusahaan.
            </p>
        </div>
    </div>

    @if($leave->leader_approver)
    <div class="section" style="background-color: #f9fafb; padding: 10px; border-radius: 4px;">
        <div class="section-title">PERSETUJUAN ATASAN LANGSUNG:</div>
        <div class="section-content">
            <p class="paragraph">
                Telah disetujui oleh {{ $leave->leader_approver->name }} 
                ({{ $leave->leader_approver->division->name ?? 'Ketua Divisi' }}) 
                pada {{ $leave->leader_approval_at->locale('id_ID')->translatedFormat('d F Y') }}
            </p>
        </div>
    </div>
    @endif

    <table class="signature-table">
        <tr>
            <td class="signature-cell">
                <p>Yang Diberikan Izin,</p>
                <div class="signature-line"></div>
                <p style="font-weight: bold; font-size: 12px; margin-top: 5px;">{{ $applicant->name }}</p>
                <p style="font-size: 10px; margin: 0;">{{ $division->name ?? 'Belum ditentukan' }}</p>
            </td>
            <td class="signature-cell">
                <p>Yang Memberikan Izin,</p>
                <div class="signature-line"></div>
                <p style="font-weight: bold; font-size: 12px; margin-top: 5px;">{{ $hrdApprover->name ?? 'HRD Manager' }}</p>
                <p style="font-size: 10px; margin: 0;">
                    {{ $leave->hrd_position ?? 'Human Resources Department' }}
                </p>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Surat ini berlaku sebagai bukti izin cuti resmi dari PT AMANAH JAYA</p>
        <p>Dicetak pada: {{ $currentDate }}, {{ Carbon\Carbon::now()->format('H:i') }} WIB</p>
    </div>

</body>
</html>