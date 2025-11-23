<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LeavePdfController extends Controller
{
    /**
     * Generate and download PDF surat cuti
     */
    public function generateLeaveLetter(LeaveApplication $leaveApplication)
    {
        // Authorization: hanya pemohon, ketua divisi, HRD, atau admin yang bisa akses
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'hrd', 'ketua_divisi']) && 
            $leaveApplication->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
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
     * Preview PDF surat cuti di browser
     */
    public function generateLeaveLetterView(LeaveApplication $leaveApplication)
    {
        // Authorization
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'hrd', 'ketua_divisi']) && 
            $leaveApplication->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $this->getPdfData($leaveApplication);

        return view('pdf.surat-izin', $data);
    }

    /**
     * Generate PDF untuk cuti yang masih pending (draft)
     */
    public function generateDraftLeaveLetter(LeaveApplication $leaveApplication)
    {
        // Authorization
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'hrd', 'ketua_divisi']) && 
            $leaveApplication->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $this->getPdfData($leaveApplication);

        $pdf = PDF::loadView('pdf.surat-izin', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = "Draft_Surat_Cuti_{$leaveApplication->applicant->name}_{$leaveApplication->start_date->format('Y-m-d')}.pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Get data untuk PDF
     */
    private function getPdfData(LeaveApplication $leaveApplication)
    {
        $applicant = $leaveApplication->applicant;
        $division = $applicant->division;

        return [
            'leave' => $leaveApplication,
            'applicant' => $applicant,
            'division' => $division,
            'currentDate' => Carbon::now()->locale('id_ID')->translatedFormat('d F Y'),
            'startDate' => $leaveApplication->start_date->locale('id_ID')->translatedFormat('d F Y'),
            'endDate' => $leaveApplication->end_date->locale('id_ID')->translatedFormat('d F Y'),
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
        
        return "Surat_Cuti_{$leaveType}_{$applicantName}_{$date}.pdf";
    }
}