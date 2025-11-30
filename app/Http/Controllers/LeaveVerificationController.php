<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LeaveVerificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
    
        if (!in_array($user->role, ['ketua_divisi', 'hrd'])) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }
        
        $query = LeaveApplication::with([
            'applicant', 
            'applicant.division'
        ]);

        if ($user->role == 'ketua_divisi') {
            $leadingDivision = $user->leadingDivision;
            $divisionIdToVerify = $leadingDivision ? $leadingDivision->id : $user->division_id;

            $query->where('status', 'pending')
                  ->whereHas('applicant', function($query) use ($divisionIdToVerify) {
                      $query->where('division_id', $divisionIdToVerify)
                            ->where('role', 'karyawan');
                  });

        } elseif ($user->role == 'hrd') {
            $query->where(function($query) {
                $query->where('status', 'approved_by_leader')
                      ->orWhere(function($subQuery) {
                          $subQuery->where('status', 'pending')
                                   ->whereHas('applicant', function($userQuery) {
                                       $userQuery->where('role', 'ketua_divisi');
                                   });
                      });
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicant', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($user->role == 'hrd' && $request->filled('division')) {
            $query->whereHas('applicant', function($q) use ($request) {
                $q->where('division_id', $request->division);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $pendingApplications = $query->latest()->get();
        $divisions = Division::all();

        return view('leave-verifications.index', compact('pendingApplications', 'divisions'));
    }

    public function showVerificationDetail(LeaveApplication $application)
    {
        return view('leave-applications.verification-show', compact('application'));
    }

    public function approveLeave(Request $request, LeaveApplication $application)
    {
        $user = Auth::user();
        
        \Log::info('Approve Leave Request Data:', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'application_id' => $application->id,
            'approval_note_from_request' => $request->input('approval_note'),
            'all_request_data' => $request->all()
        ]);

        if (!in_array($user->role, ['admin', 'ketua_divisi', 'hrd'])) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses.');
        }

        if ($user->role == 'ketua_divisi') {
            $applicant = $application->applicant;
            $leadingDivision = $user->leadingDivision;
            $myDivisionId = $leadingDivision ? $leadingDivision->id : $user->division_id;

            $isTeamMember = $applicant->division_id == $myDivisionId && $applicant->id != $user->id;
            $isOrphanEmployee = $applicant->role == 'karyawan' && is_null($applicant->division_id);
            
            if (!$isTeamMember && !$isOrphanEmployee) {
                return redirect()->route('leave-verifications.index')
                               ->with('error', 'Anda tidak berhak menyetujui pengajuan ini.');
            }
        }

        $approvalNote = $request->input('approval_note', 'Disetujui tanpa catatan');
        
        \Log::info('Before Update - Approval Note:', ['note' => $approvalNote]);

        if ($user->role == 'admin') {
            $application->update([
                'status' => 'approved_by_hrd',
                'hrd_approver_id' => $user->id,
                'hrd_approval_at' => Carbon::now(),
                'hrd_approval_note' => $approvalNote,
            ]);
        } elseif ($user->role == 'ketua_divisi') {
            $application->update([
                'status' => 'approved_by_leader',
                'leader_approver_id' => $user->id,
                'leader_approval_at' => Carbon::now(),
                'leader_approval_note' => $approvalNote,
            ]);
        } elseif ($user->role == 'hrd') {
            $application->update([
                'status' => 'approved_by_hrd',
                'hrd_approver_id' => $user->id,
                'hrd_approval_at' => Carbon::now(),
                'hrd_approval_note' => $approvalNote,
            ]);
        }

        \Log::info('After Update - Application Data:', [
            'leader_approval_note' => $application->fresh()->leader_approval_note,
            'hrd_approval_note' => $application->fresh()->hrd_approval_note
        ]);

        return redirect()->route('leave-verifications.index')->with('success', 'Cuti berhasil disetujui.');
    }

    public function rejectLeave(Request $request, LeaveApplication $application)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'ketua_divisi', 'hrd'])) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses.');
        }

        $request->validate([
        'rejection_notes' => 'required|string|min:10|max:500',  
        ], [
            'rejection_notes.min' => 'Alasan penolakan harus minimal 10 karakter.',
            'rejection_notes.required' => 'Alasan penolakan wajib diisi.',
            'rejection_notes.max' => 'Alasan penolakan maksimal 500 karakter.'
        ]);

        if ($user->role == 'admin') {
            $application->update([
                'status' => 'rejected_by_hrd',
                'hrd_rejection_notes' => $request->rejection_notes,
                'hrd_approver_id' => $user->id,
            ]);
        } elseif ($user->role == 'ketua_divisi') {
            $application->update([
                'status' => 'rejected_by_leader',
                'leader_rejection_notes' => $request->rejection_notes,
                'leader_approver_id' => $user->id,
            ]);
        } elseif ($user->role == 'hrd') {
            $application->update([
                'status' => 'rejected_by_hrd',
                'hrd_rejection_notes' => $request->rejection_notes,
                'hrd_approver_id' => $user->id,
            ]);
        }

        if ($application->leave_type == 'tahunan') {
            $application->applicant->increment('annual_leave_quota', $application->total_days);
        }

        return redirect()->route('leave-verifications.index')->with('success', 'Cuti telah ditolak.');
    }

    public function bulkApproveReject(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            \Log::info('Bulk Action Request:', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'action' => $request->action,
                'leave_ids' => $request->leave_ids,
                'selected_count' => count($request->leave_ids ?? [])
            ]);

            if (!in_array($user->role, ['hrd', 'ketua_divisi'])) {
                throw new \Exception('Anda tidak memiliki hak akses untuk aksi ini.');
            }

            $request->validate([
                'action' => 'required|in:approve,reject',
                'leave_ids' => 'required|array|max:50',
                'leave_ids.*' => 'exists:leave_applications,id',
                'rejection_notes' => 'required_if:action,reject|min:10|max:500',
                'approval_note' => 'nullable|string|max:500'
            ], [
                'rejection_notes.min' => 'Alasan penolakan harus minimal 10 karakter.',
                'rejection_notes.required_if' => 'Alasan penolakan wajib diisi ketika menolak pengajuan.',
                'rejection_notes.max' => 'Alasan penolakan maksimal 500 karakter.',
                'leave_ids.max' => 'Maksimal 50 pengajuan dapat diproses sekaligus.',
                'leave_ids.required' => 'Pilih setidaknya satu pengajuan untuk diproses.'
            ]);

            if ($user->role == 'ketua_divisi') {
                $leadingDivision = $user->leadingDivision;
                $divisionIdToVerify = $leadingDivision ? $leadingDivision->id : $user->division_id;

                $leaveApplications = LeaveApplication::whereIn('id', $request->leave_ids)
                    ->where('status', 'pending')
                    ->whereHas('applicant', function($query) use ($divisionIdToVerify) {
                        $query->where('division_id', $divisionIdToVerify)
                              ->where('role', 'karyawan');
                    })
                    ->get();
                    
            } else { 
                $leaveApplications = LeaveApplication::whereIn('id', $request->leave_ids)
                    ->where(function($query) {
                        $query->where('status', 'approved_by_leader')
                            ->orWhere(function($q) {
                                $q->where('status', 'pending')
                                    ->whereHas('applicant', function($applicantQuery) {
                                        $applicantQuery->where('role', 'ketua_divisi');
                                    });
                            });
                    })
                    ->get();
            }

            if ($leaveApplications->isEmpty()) {
                throw new \Exception(
                    $user->role == 'ketua_divisi' 
                    ? 'Tidak ada pengajuan dari anggota tim yang dapat diproses.' 
                    : 'Tidak ada pengajuan yang dapat diproses.'
                );
            }

            $processedCount = 0;
            $errors = [];

            foreach ($leaveApplications as $application) {
                try {
                    if ($request->action == 'approve') {
                        if ($user->role == 'ketua_divisi') {
                            $application->update([
                                'status' => 'approved_by_leader',
                                'leader_approver_id' => $user->id,
                                'leader_approval_at' => Carbon::now(),
                                'leader_approval_note' => $request->approval_note ?? 'Disetujui tanpa catatan',
                                'leader_rejection_notes' => null,
                            ]);
                        } else {
                            $application->update([
                                'status' => 'approved_by_hrd',
                                'hrd_approver_id' => $user->id,
                                'hrd_approval_at' => Carbon::now(),
                                'hrd_approval_note' => $request->approval_note ?? 'Disetujui tanpa catatan',
                                'hrd_rejection_notes' => null,
                            ]);
                        }
                        $processedCount++;

                    } else { 
                        if ($user->role == 'ketua_divisi') {
                            $application->update([
                                'status' => 'rejected_by_leader',
                                'leader_rejection_notes' => $request->rejection_notes,
                                'leader_approver_id' => $user->id,
                                'leader_approval_at' => Carbon::now(),
                                'leader_approval_note' => null,
                            ]);
                        } else { 
                            $application->update([
                                'status' => 'rejected_by_hrd',
                                'hrd_rejection_notes' => $request->rejection_notes,
                                'hrd_approver_id' => $user->id,
                                'hrd_approval_at' => Carbon::now(),
                                'hrd_approval_note' => null,
                            ]);
                        }

                        if ($application->leave_type == 'tahunan') {
                            $application->applicant->increment('annual_leave_quota', $application->total_days);
                        }
                        $processedCount++;
                    }

                } catch (\Exception $e) {
                    $errors[] = "Gagal memproses pengajuan #{$application->id}: " . $e->getMessage();
                    Log::error("Bulk action failed for application #{$application->id}: " . $e->getMessage());
                }
            }

            if ($processedCount === 0 && !empty($errors)) {
                throw new \Exception("Semua pengajuan gagal diproses: " . implode(', ', $errors));
            }

            DB::commit();

            $actionText = $request->action == 'approve' ? 'disetujui' : 'ditolak';
            $roleText = $user->role == 'ketua_divisi' ? ' (Verifikasi Pertama)' : ' (Persetujuan Final)';
            
            $message = $processedCount . ' pengajuan cuti berhasil ' . $actionText . $roleText . '.';
            
            if (!empty($errors)) {
                $message .= ' Namun, ' . count($errors) . ' pengajuan gagal diproses.';
            }

            return redirect()->route('leave-verifications.index')
                        ->with('success', $message)
                        ->with('errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Bulk action failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return redirect()->route('leave-verifications.index')
                        ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}