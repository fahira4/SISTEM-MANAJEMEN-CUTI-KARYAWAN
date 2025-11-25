<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveApplicationController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $query = LeaveApplication::where('user_id', $userId)->latest();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'rejected') {
                $query->whereIn('status', ['rejected_by_leader', 'rejected_by_hrd']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by leave type
        if ($request->has('leave_type') && $request->leave_type != '') {
            $query->where('leave_type', $request->leave_type);
        }

        // Filter by year
        if ($request->has('year') && $request->year != '') {
            $query->whereYear('created_at', $request->year);
        }

        $leaveApplications = $query->paginate(10);

        return view('leave-applications.index', compact('leaveApplications'));
    }

    public function create()
    {
        // Ambil data libur dan format paksa ke YYYY-MM-DD
        // Menggunakan map() untuk memastikan formatnya string bersih, bukan objek Carbon
        $holidays = \App\Models\Holiday::all()->map(function ($holiday) {
            return \Carbon\Carbon::parse($holiday->date)->format('Y-m-d');
        })->toArray();
        
        // Kirim ke view
        return view('leave-applications.create', compact('holidays'));
    }

    public function show(LeaveApplication $leaveApplication)
    {
        $user = Auth::user();
        
        // Authorization: hanya pemohon, admin, HRD, atau ketua divisi yang related yang bisa lihat
        if ($user->role != 'admin' && 
            $user->role != 'hrd' && 
            $leaveApplication->user_id != $user->id &&
            !($user->role == 'ketua_divisi' && $leaveApplication->applicant->division_id == $user->division_id)) {
            abort(403, 'Unauthorized action.');
        }

        // Load relationships
        $leaveApplication->load(['applicant', 'applicant.division', 'leaderApprover', 'hrdApprover']);
        
        return view('leave-applications.show', compact('leaveApplication'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        \Log::info('Leave Application Store - User:', [
            'id' => $user->id,
            'role' => $user->role,
            'division_id' => $user->division_id,
            'leave_type' => $request->leave_type
        ]);
        
        // Validasi divisi HANYA untuk karyawan
        if ($user->role == 'karyawan' && !$user->division_id) {
            return back()->withInput()->withErrors([
                'division' => 'Anda belum memiliki divisi. Hubungi admin.'
            ]);
        }

        // Validasi masa kerja untuk cuti tahunan
        if ($request->leave_type == 'tahunan') {
            $employmentEligibility = $this->checkEmploymentEligibility($user);
            if (!$employmentEligibility['eligible']) {
                return back()->withInput()->withErrors([
                    'leave_type' => $employmentEligibility['message']
                ]);
            }
        }

        // 1. VALIDASI DATA
        $request->validate([
            'leave_type' => 'required|in:tahunan,sakit',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
            'address_during_leave' => 'required|string|min:5',
            'emergency_contact' => 'required|string|min:9',
            'attachment_path' => 'required_if:leave_type,sakit|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // ✅ PERBAIKAN 1: Menggunakan fungsi calculateWorkingDays (agar hari libur tidak terhitung)
        $totalDays = $this->calculateWorkingDays($startDate, $endDate);
        
        // ✅ PERBAIKAN: Cek jika total hari 0 (Semuanya hari libur/weekend)
        if ($totalDays <= 0) {
            return back()->withInput()->withErrors([
                'start_date' => 'Periode yang dipilih seluruhnya adalah hari libur atau akhir pekan. Anda tidak perlu mengajukan cuti.'
            ]);
        }

        // 3. VALIDASI LOGIKA CUTI TAHUNAN
        if ($request->leave_type == 'tahunan') {
            if ($user->annual_leave_quota < $totalDays) {
                return back()->withInput()->withErrors([
                    'total_days' => 'Sisa kuota cuti tahunan Anda tidak mencukupi (Sisa: ' . $user->annual_leave_quota . ' hari).'
                ]);
            }

            if ($startDate->isBefore(Carbon::today()->addDays(3))) {
                return back()->withInput()->withErrors([
                    'start_date' => 'Pengajuan Cuti Tahunan harus minimal H-3 (3 hari) sebelum tanggal mulai cuti.'
                ]);
            }
        }

        // 4. VALIDASI OVERLAP CUTI
        $hasOverlap = $this->checkLeaveOverlap($user->id, $startDate, $endDate);
        if ($hasOverlap) {
            return back()->withInput()->withErrors([
                'start_date' => 'Anda sudah memiliki cuti yang disetujui pada periode tersebut. Silakan pilih tanggal lain.'
            ]);
        }
        
        // 5. PROSES UPLOAD FILE
        $attachmentPath = null;
        if ($request->hasFile('attachment_path')) {
            $attachmentPath = $request->file('attachment_path')->store('attachments', 'public');
        }

        $initialStatus = 'pending';
        $leaderApproverId = null;
        $leaderApprovalAt = null;

        if ($user->role == 'ketua_divisi') {
            $initialStatus = 'approved_by_leader'; 
            $leaderApproverId = $user->id; 
            $leaderApprovalAt = Carbon::now();
        }

        // 6. SIMPAN KE DATABASE
        $leaveApplication = LeaveApplication::create([
            'user_id' => $user->id,
            'leave_type' => $request->leave_type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'address_during_leave' => $request->address_during_leave,
            'emergency_contact' => $request->emergency_contact,
            'attachment_path' => $attachmentPath,
            'status' => $initialStatus,
            'leader_approver_id' => $leaderApproverId,
            'leader_approval_at' => $leaderApprovalAt,
        ]);

        // 7. KURANGI KUOTA CUTI TAHUNAN
        if ($leaveApplication->leave_type == 'tahunan') {
            $user->decrement('annual_leave_quota', $totalDays);
        }

        return redirect()->route('leave-applications.index')->with('success', 'Pengajuan cuti Anda telah berhasil dikirim.');
    }

    private function checkLeaveOverlap($userId, $startDate, $endDate)
    {
        return LeaveApplication::where('user_id', $userId)
            ->where(function($query) {
                $query->where('status', 'pending')
                      ->orWhere('status', 'approved_by_leader')
                      ->orWhere('status', 'approved_by_hrd');
            })
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      })
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '>=', $startDate)
                            ->where('end_date', '<=', $endDate);
                      });
            })
            ->exists();
    }

    public function showVerificationList()
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'ketua_divisi', 'hrd'])) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }
        
        $pendingApplications = collect();

        if ($user->role == 'admin') {
            $pendingApplications = LeaveApplication::with(['applicant', 'applicant.division'])
                ->pending()
                ->latest()
                ->get();

        } elseif ($user->role == 'ketua_divisi') {
            
            // ✅ PERBAIKAN CORE LOGIC: Mencari ID Divisi dengan lebih pintar
            // Cek 1: Apakah user ini terdaftar sebagai leader di tabel divisions?
            $leadingDivision = $user->leadingDivision; 
            
            // Jika dia memimpin divisi, ambil ID dari tabel divisions. 
            // Jika tidak (null), fallback ke kolom division_id di tabel users.
            $divisionIdToVerify = $leadingDivision ? $leadingDivision->id : $user->division_id;

            $pendingApplications = LeaveApplication::with(['applicant', 'applicant.division'])
                ->where('status', 'pending')
                ->whereHas('applicant', function($query) use ($divisionIdToVerify) {
                    // Filter karyawan berdasarkan ID Divisi yang sudah kita pastikan
                    $query->where('division_id', $divisionIdToVerify)
                          ->where('role', 'karyawan');
                })
                ->latest()
                ->get();

        } elseif ($user->role == 'hrd') {
            $pendingApplications = LeaveApplication::with(['applicant', 'applicant.division', 'leaderApprover'])
                ->where(function($query) {
                    $query->where('status', 'approved_by_leader')
                          ->orWhere(function($subQuery) {
                              $subQuery->where('status', 'pending')
                                       ->whereHas('applicant', function($userQuery) {
                                           $userQuery->where('role', 'ketua_divisi');
                                       });
                          });
                })
                ->latest()
                ->get();
        }

        return view('leave-applications.verification-list', compact('pendingApplications'));
    }

    public function showVerificationDetail(LeaveApplication $application)
    {
        return view('leave-applications.verification-show', compact('application'));
    }

    public function approveLeave(Request $request, LeaveApplication $application)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'ketua_divisi', 'hrd'])) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses.');
        }

        // Logic approval tetap sama
        if ($user->role == 'ketua_divisi') {
            $applicant = $application->applicant;
            
            // ✅ Update Logic: Menggunakan division_id yang lebih robust (sama seperti list)
            $leadingDivision = $user->leadingDivision;
            $myDivisionId = $leadingDivision ? $leadingDivision->id : $user->division_id;

            $isTeamMember = $applicant->division_id == $myDivisionId && $applicant->id != $user->id;
            $isOrphanEmployee = $applicant->role == 'karyawan' && is_null($applicant->division_id);
            
            if (!$isTeamMember && !$isOrphanEmployee) {
                return redirect()->route('leave-verifications.index')
                               ->with('error', 'Anda tidak berhak menyetujui pengajuan ini.');
            }
        }

        // Eksekusi Update Status
        if ($user->role == 'admin') {
            $application->update([
                'status' => 'approved_by_hrd',
                'hrd_approver_id' => $user->id,
                'hrd_approval_at' => Carbon::now(),
            ]);
        } elseif ($user->role == 'ketua_divisi') {
            $application->update([
                'status' => 'approved_by_leader',
                'leader_approver_id' => $user->id,
                'leader_approval_at' => Carbon::now(),
            ]);
        } elseif ($user->role == 'hrd') {
            $application->update([
                'status' => 'approved_by_hrd',
                'hrd_approver_id' => $user->id,
                'hrd_approval_at' => Carbon::now(),
            ]);
        }

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
        // ✅ TAMBAHKAN CUSTOM MESSAGE UNTUK MIN:10
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

    public function cancelLeave(Request $request, LeaveApplication $application)
    {
        $user = Auth::user();

        if ($application->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak berhak membatalkan pengajuan ini.');
        }

        $canCancel = false;

        if ($user->role == 'karyawan') {
            if ($application->status === 'pending') {
                $canCancel = true;
            } else {
                return redirect()->back()->with('error', 'Pengajuan sudah disetujui atasan, tidak bisa dibatalkan.');
            }
        } 
        elseif ($user->role == 'ketua_divisi') {
            if (in_array($application->status, ['pending', 'approved_by_leader'])) {
                $canCancel = true;
            } else {
                return redirect()->back()->with('error', 'Pengajuan sudah diproses HRD, tidak bisa dibatalkan.');
            }
        }

        if ($canCancel) {
            $cancellationReason = $request->cancellation_reason ?? 'Dibatalkan oleh pemohon (Tanpa alasan)';

            if ($application->leave_type === 'tahunan') {
                $application->applicant->increment('annual_leave_quota', $application->total_days);
            }

            $application->update([
                'status' => 'cancelled',
                'cancellation_reason' => $cancellationReason,
            ]);

            return redirect()->route('leave-applications.index')->with('success', 'Pengajuan cuti berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Status pengajuan tidak mengizinkan pembatalan.');
    }

    public function bulkApproveReject(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['hrd', 'ketua_divisi'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk aksi ini.');
        }

        $request->validate([
        'action' => 'required|in:approve,reject',
        'leave_ids' => 'required|array',
        'leave_ids.*' => 'exists:leave_applications,id',
        'rejection_notes' => 'required_if:action,reject|min:10|max:500'
    ], [
        'rejection_notes.min' => 'Alasan penolakan harus minimal 10 karakter.',
        'rejection_notes.required_if' => 'Alasan penolakan wajib diisi ketika menolak pengajuan.',
        'rejection_notes.max' => 'Alasan penolakan maksimal 500 karakter.'
    ]);

        if ($user->role == 'ketua_divisi') {
            // ✅ Perbaikan Logic Bulk: Menggunakan division_id yang robust
            $leadingDivision = $user->leadingDivision;
            $divisionIdToVerify = $leadingDivision ? $leadingDivision->id : $user->division_id;

            $leaveApplications = LeaveApplication::whereIn('id', $request->leave_ids)
                ->where('status', 'pending')
                ->whereHas('applicant', function($query) use ($divisionIdToVerify) {
                    $query->where('division_id', $divisionIdToVerify)
                          ->where('role', 'karyawan');
                })
                ->get();
                
        } else { // HRD
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
            $errorMsg = $user->role == 'ketua_divisi' 
                ? 'Tidak ada pengajuan dari anggota tim yang dapat diproses.' 
                : 'Tidak ada pengajuan yang dapat diproses.';
            return redirect()->route('leave-verifications.index')->with('error', $errorMsg);
        }

        $processedCount = 0;

        foreach ($leaveApplications as $application) {
            if ($request->action == 'approve') {
                if ($user->role == 'ketua_divisi') {
                    $application->update([
                        'status' => 'approved_by_leader',
                        'leader_approver_id' => $user->id,
                        'leader_approval_at' => Carbon::now(),
                        'leader_rejection_notes' => null,
                    ]);
                } else { // HRD
                    $application->update([
                        'status' => 'approved_by_hrd',
                        'hrd_approver_id' => $user->id,
                        'hrd_approval_at' => Carbon::now(),
                        'hrd_rejection_notes' => null,
                    ]);
                }
                $processedCount++;

            } else { // REJECT
                if ($user->role == 'ketua_divisi') {
                    $application->update([
                        'status' => 'rejected_by_leader',
                        'leader_rejection_notes' => $request->rejection_notes,
                        'leader_approver_id' => $user->id,
                        'leader_approval_at' => Carbon::now(),
                    ]);
                } else { // HRD
                    $application->update([
                        'status' => 'rejected_by_hrd',
                        'hrd_rejection_notes' => $request->rejection_notes,
                        'hrd_approver_id' => $user->id,
                        'hrd_approval_at' => Carbon::now(),
                    ]);
                }

                if ($application->leave_type == 'tahunan') {
                    $application->applicant->increment('annual_leave_quota', $application->total_days);
                }
                $processedCount++;
            }
        }

        $actionText = $request->action == 'approve' ? 'disetujui' : 'ditolak';
        $roleText = $user->role == 'ketua_divisi' ? ' (Verifikasi Pertama)' : ' (Persetujuan Final)';
        
        return redirect()->route('leave-verifications.index')
                    ->with('success', $processedCount . ' pengajuan cuti berhasil ' . $actionText . $roleText . '.');
    }

    private function checkEmploymentEligibility($user)
    {
        if (!$user->join_date) {
            return ['eligible' => false, 'message' => 'Anda belum memiliki tanggal bergabung yang valid.'];
        }

        $joinDate = Carbon::parse($user->join_date);
        $currentDate = Carbon::now();
        $monthsOfWork = $joinDate->diffInMonths($currentDate);
        
        if ($monthsOfWork < 12) {
            $remainingMonths = 12 - $monthsOfWork;
            return [
                'eligible' => false,
                'message' => "Masa kerja Anda {$monthsOfWork} bulan (kurang dari 1 tahun)."
            ];
        }

        return ['eligible' => true];
    }

    private function calculateWorkingDays($startDate, $endDate)
    {
        $totalDays = 0;
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current <= $end) {
            // Cek jika bukan weekend dan bukan hari libur
            if ($current->dayOfWeek !== Carbon::SATURDAY && 
                $current->dayOfWeek !== Carbon::SUNDAY &&
                !\App\Models\Holiday::isHoliday($current)) {
                $totalDays++;
            }
            $current->addDay();
        }

        return $totalDays;
    }
}