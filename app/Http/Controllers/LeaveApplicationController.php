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
        // ✅ PERBAIKAN: Hapus query availableLeaders yang tidak diperlukan
        // Form pengajuan cuti tidak butuh data ketua divisi
        return view('leave-applications.create');
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

    /**
     * Menyimpan pengajuan cuti baru ke database.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validasi divisi untuk karyawan
        if ($user->role == 'karyawan' && !$user->division_id) {
            return back()->withInput()->withErrors([
                'division' => 'Anda belum memiliki divisi. Hubungi admin untuk ditambahkan ke divisi sebelum mengajukan cuti.'
            ]);
        }

            // ✅ TAMBAHKAN: Validasi masa kerja untuk cuti tahunan
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

        // 2. HITUNG TOTAL HARI KERJA
        $totalDays = $startDate->diffInWeekdays($endDate) + 1;

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
            'status' => 'pending',
        ]);

        // 7. KURANGI KUOTA CUTI TAHUNAN
        if ($leaveApplication->leave_type == 'tahunan') {
            $user->decrement('annual_leave_quota', $totalDays);
        }

        return redirect()->route('leave-applications.index')->with('success', 'Pengajuan cuti Anda telah berhasil dikirim.');
    }

    /**
     * Cek overlap cuti dengan cuti yang sudah disetujui
     */
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
        // MENGGUNAKAN SCOPE: lebih clean dan readable
        $pendingApplications = LeaveApplication::with(['applicant', 'applicant.division'])
            ->pending()
            ->latest()
            ->get();

    } elseif ($user->role == 'ketua_divisi') {
        if (!$user->division_id) {
            return view('leave-applications.verification-list', compact('pendingApplications'))
                   ->with('error', 'Anda tidak memiliki divisi. Hubungi admin.');
        }

        // MENGGUNAKAN SCOPE: lebih mudah dipahami
        $pendingApplications = LeaveApplication::with(['applicant', 'applicant.division'])
            ->forLeaderApproval($user->division_id)
            ->latest()
            ->get();

    } elseif ($user->role == 'hrd') {
        // MENGGUNAKAN SCOPE: menggantikan query complex
        $pendingApplications = LeaveApplication::with(['applicant', 'applicant.division', 'leaderApprover'])
            ->forHrdApproval()
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

        if ($user->role == 'ketua_divisi') {
            $applicant = $application->applicant;
            $isTeamMember = $applicant->division_id == $user->division_id && $applicant->id != $user->id;
            $isOrphanEmployee = $applicant->role == 'karyawan' && is_null($applicant->division_id);
            
            if (!$isTeamMember && !$isOrphanEmployee) {
                return redirect()->route('leave-verifications.index')
                               ->with('error', 'Anda tidak berhak menyetujui pengajuan ini.');
            }
        }

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
            'rejection_notes' => 'required|string',  
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
        if ($application->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak berhak membatalkan pengajuan ini.');
        }

        if ($application->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses (Approve/Reject) dan tidak bisa dibatalkan.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|min:10|max:500',
        ]);

        if ($application->leave_type === 'tahunan') {
            $application->applicant->increment('annual_leave_quota', $application->total_days);
        }

        $application->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return redirect()->route('leave-applications.index')->with('success', 'Pengajuan cuti berhasil dibatalkan dan kuota dikembalikan.');
    }

    /**
     * APPROVE/REJECT MULTIPLE PENGAJUAN SEKALIGUS (UNTUK HRD)
     */
        public function bulkApproveReject(Request $request)
    {
        $user = Auth::user();
        
        // Boleh diakses oleh HRD dan Ketua Divisi
        if (!in_array($user->role, ['hrd', 'ketua_divisi'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk aksi ini.');
        }

        \Log::info('Bulk action attempt', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'action' => $request->action,
            'leave_ids' => $request->leave_ids
        ]);

        // VALIDASI INPUT
        $request->validate([
            'action' => 'required|in:approve,reject',
            'leave_ids' => 'required|array',
            'leave_ids.*' => 'exists:leave_applications,id',
            'rejection_notes' => 'required_if:action,reject|min:10|max:500'
        ]);

        // QUERY BERDASARKAN ROLE
        if ($user->role == 'ketua_divisi') {
            // Untuk Ketua Divisi: hanya bisa proses anggota timnya yang status pending
            $leaveApplications = LeaveApplication::whereIn('id', $request->leave_ids)
                ->where('status', 'pending')
                ->whereHas('applicant', function($query) use ($user) {
                    $query->where('division_id', $user->division_id)
                        ->where('role', 'karyawan');
                })
                ->get();
                
        } else { // HRD
            // Untuk HRD: bisa proses yang sudah approved_by_leader ATAU dari ketua divisi
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

        \Log::info('Applications found for bulk action', [
            'user_role' => $user->role,
            'count' => $leaveApplications->count(),
            'applications' => $leaveApplications->pluck('id')
        ]);

        // JIKA TIDAK ADA DATA YANG BISA DIPROSES
        if ($leaveApplications->isEmpty()) {
            $errorMsg = $user->role == 'ketua_divisi' 
                ? 'Tidak ada pengajuan dari anggota tim yang dapat diproses.' 
                : 'Tidak ada pengajuan yang dapat diproses. Pastikan status pengajuan sudah "Approved by Leader" atau pengajuan dari Ketua Divisi.';
                
            return redirect()->route('leave-verifications.index')->with('error', $errorMsg);
        }

        $processedCount = 0;

        // PROSES SETIAP PENGAJUAN
        foreach ($leaveApplications as $application) {
            if ($request->action == 'approve') {
                if ($user->role == 'ketua_divisi') {
                    // Ketua Divisi approve -> status: approved_by_leader
                    $application->update([
                        'status' => 'approved_by_leader',
                        'leader_approver_id' => $user->id,
                        'leader_approval_at' => Carbon::now(),
                        'leader_rejection_notes' => null,
                    ]);
                } else { // HRD
                    // HRD approve -> status: approved_by_hrd (final)
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

                // KEMBALIKAN KUOTA JIKA CUTI TAHUNAN
                if ($application->leave_type == 'tahunan') {
                    $application->applicant->increment('annual_leave_quota', $application->total_days);
                }
                $processedCount++;
            }
        }

        // KIRIM FEEDBACK SUKSES
        $actionText = $request->action == 'approve' ? 'disetujui' : 'ditolak';
        $roleText = $user->role == 'ketua_divisi' ? ' (Verifikasi Pertama)' : ' (Persetujuan Final)';
        
        return redirect()->route('leave-verifications.index')
                    ->with('success', $processedCount . ' pengajuan cuti berhasil ' . $actionText . $roleText . '.');
    }

        private function checkEmploymentEligibility($user)
    {
        // Jika belum ada join_date, tidak eligible
        if (!$user->join_date) {
            return [
                'eligible' => false,
                'message' => 'Anda belum memiliki tanggal bergabung yang valid. Hubungi admin.'
            ];
        }

        $joinDate = Carbon::parse($user->join_date);
        $currentDate = Carbon::now();
        
        // Hitung selisih dalam bulan
        $monthsOfWork = $joinDate->diffInMonths($currentDate);
        
        // Jika masa kerja kurang dari 12 bulan
        if ($monthsOfWork < 12) {
            $remainingMonths = 12 - $monthsOfWork;
            return [
                'eligible' => false,
                'message' => "Karyawan dengan masa kerja < 1 tahun belum eligible cuti tahunan. Masa kerja Anda: {$monthsOfWork} bulan. Eligible dalam {$remainingMonths} bulan lagi."
            ];
        }

        return ['eligible' => true];
    }
}