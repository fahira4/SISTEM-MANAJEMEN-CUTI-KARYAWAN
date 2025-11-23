<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LeavePdfController extends Controller
{
    /**
     * Generate and download PDF surat cuti (Hanya untuk cuti yang disetujui HRD)
     */
    public function generateLeaveLetter(LeaveApplication $leaveApplication)
    {
        // Authorization: hanya pemohon, ketua divisi, HRD, atau admin yang bisa akses
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'hrd', 'ketua_divisi']) && 
            $leaveApplication->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi: hanya cuti yang sudah disetujui HRD yang bisa generate surat
        if (!$this->isApprovedByHrd($leaveApplication)) {
            abort(403, 'Surat izin cuti hanya tersedia untuk pengajuan yang sudah disetujui HRD.');
        }

        $data = $this->getPdfData($leaveApplication);

        // Gunakan view professional
        $pdf = PDF::loadView('pdf.surat-izin', $data);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        $filename = $this->generateFilename($leaveApplication);
        
        return $pdf->download($filename);
    }

    /**
     * Preview PDF surat cuti di browser (Hanya untuk cuti yang disetujui HRD)
     */
    public function generateLeaveLetterView(LeaveApplication $leaveApplication)
    {
        // Authorization
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'hrd', 'ketua_divisi']) && 
            $leaveApplication->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi: hanya cuti yang sudah disetujui HRD yang bisa preview surat
        if (!$this->isApprovedByHrd($leaveApplication)) {
            return redirect()->back()->with('error', 'Surat izin cuti hanya tersedia untuk pengajuan yang sudah disetujui HRD.');
        }

        $data = $this->getPdfData($leaveApplication);

        return view('pdf.surat-izin', $data);
    }

    /**
     * Cek apakah cuti sudah disetujui oleh HRD - PERBAIKI DI SINI
     */
    private function isApprovedByHrd(LeaveApplication $leaveApplication): bool
    {
        // PERBAIKAN: sesuaikan dengan nama kolom di model
        return $leaveApplication->status === 'approved_by_hrd' && 
               !is_null($leaveApplication->hrd_approver_id) && 
               !is_null($leaveApplication->hrd_approval_at);
    }

    /**
     * Get data untuk PDF - PERBAIKI DI SINI JUGA
     */
    private function getPdfData(LeaveApplication $leaveApplication)
    {
        $applicant = $leaveApplication->applicant;
        $division = $applicant->division;

        // PERBAIKAN: sesuaikan dengan nama kolom
        $hrdApprover = $leaveApplication->hrdApprover;
        $hrdApprovedAt = $leaveApplication->hrd_approval_at;

        return [
            'leave' => $leaveApplication,
            'applicant' => $applicant,
            'division' => $division,
            'hrdApprover' => $hrdApprover,
            'hrdApprovedAt' => $hrdApprovedAt,
            'currentDate' => Carbon::now()->locale('id_ID')->translatedFormat('d F Y'),
            'startDate' => $leaveApplication->start_date->locale('id_ID')->translatedFormat('d F Y'),
            'endDate' => $leaveApplication->end_date->locale('id_ID')->translatedFormat('d F Y'),
            'isApprovedByHrd' => $this->isApprovedByHrd($leaveApplication),
        ];
    }

    /**
     * Generate filename untuk PDF
     */
    private function generateFilename(LeaveApplication $leaveApplication)
    {
        $applicantName = str_replace(' ', '_', $leaveApplication->applicant->name);
        $leaveType = $leaveApplication->leave_type == 'tahunan' ? 'Tahunan' : 'Sakit';
        $date = $leaveApplication->start_date->format('Y-m-d');
        
        return "Surat_Izin_Cuti_{$leaveType}_{$applicantName}_{$date}.pdf";
    }

    /**
     * Cek ketersediaan surat izin cuti
     */
    public function checkAvailability(LeaveApplication $leaveApplication)
    {
        $user = auth()->user();
        
        // Authorization
        if (!in_array($user->role, ['admin', 'hrd', 'ketua_divisi']) && 
            $leaveApplication->user_id != $user->id) {
            return response()->json(['available' => false, 'message' => 'Unauthorized']);
        }

        $isAvailable = $this->isApprovedByHrd($leaveApplication);
        
        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Surat izin cuti tersedia' : 'Surat izin cuti hanya tersedia setelah disetujui HRD'
        ]);
    }
}