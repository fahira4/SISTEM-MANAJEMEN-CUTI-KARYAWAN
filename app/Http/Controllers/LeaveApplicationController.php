<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Division;
use App\Models\Holiday;


class LeaveApplicationController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    
    if ($user->role == 'hrd') {
        $query = LeaveApplication::with(['applicant', 'applicant.division'])->latest();
    } elseif ($user->role == 'ketua_divisi') {
        $query = LeaveApplication::with(['applicant', 'applicant.division'])
            ->whereHas('applicant', function($query) use ($user) {
                $query->where('division_id', $user->division_id);
            })->latest();
    } else {
        $query = LeaveApplication::where('user_id', $user->id)->latest();
    }

    if ($request->has('status') && $request->status != '') {
        if ($request->status == 'rejected') {
            $query->whereIn('status', ['rejected_by_leader', 'rejected_by_hrd']);
        } elseif ($request->status == 'completed') {
            $query->where('status', 'approved_by_hrd')
                  ->where('end_date', '<', Carbon::now());
        } else {
            $query->where('status', $request->status);
        }
    }

    if ($request->has('leave_type') && $request->leave_type != '') {
        $query->where('leave_type', $request->leave_type);
    }

    if ($request->has('year') && $request->year != '') {
        $query->whereYear('start_date', $request->year);
    }

    if (in_array($user->role, ['hrd', 'ketua_divisi'])) {
        if ($request->has('division') && $request->division != '' && $user->role == 'hrd') {
            $query->whereHas('applicant', function($q) use ($request) {
                $q->where('division_id', $request->division);
            });
        }
        
        if ($request->has('employee') && $request->employee != '') {
            $query->whereHas('applicant', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->employee . '%');
            });
        }
    }

    if ($request->has('sort') && $request->has('direction')) {
        $allowedSorts = ['start_date', 'end_date', 'total_days', 'created_at', 'status'];
        $allowedDirections = ['asc', 'desc'];
        
        if (in_array($request->sort, $allowedSorts) && in_array($request->direction, $allowedDirections)) {
            $query->orderBy($request->sort, $request->direction);
        }
    } else {
        $query->orderBy('created_at', 'desc');
    }

    $leaveApplications = $query->paginate(10)
        ->appends($request->except('page'));

    if ($user->role == 'hrd') {
        $years = LeaveApplication::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $divisions = Division::all();
    } elseif ($user->role == 'ketua_divisi') {
        $years = LeaveApplication::whereHas('applicant', function($query) use ($user) {
                $query->where('division_id', $user->division_id);
            })
            ->selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $divisions = collect(); 
    } else {
        $years = LeaveApplication::where('user_id', $user->id)
            ->selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $divisions = collect();
    }

    return view('leave-applications.index', compact('leaveApplications', 'divisions', 'years'));
}

    public function create()
    {
        $holidays = \App\Models\Holiday::all()->map(function ($holiday) {
            return \Carbon\Carbon::parse($holiday->date)->format('Y-m-d');
        })->toArray();
        
        return view('leave-applications.create', compact('holidays'));
    }

    public function show(LeaveApplication $leaveApplication)
    {
        $user = Auth::user();
        
        if ($user->role != 'admin' && 
            $user->role != 'hrd' && 
            $leaveApplication->user_id != $user->id &&
            !($user->role == 'ketua_divisi' && $leaveApplication->applicant->division_id == $user->division_id)) {
            abort(403, 'Unauthorized action.');
        }

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
        
        if ($user->role == 'karyawan' && !$user->division_id) {
            return back()->withInput()->withErrors([
                'division' => 'Anda belum memiliki divisi. Hubungi admin.'
            ]);
        }

        if ($request->leave_type == 'tahunan') {
            $employmentEligibility = $this->checkEmploymentEligibility($user);
            if (!$employmentEligibility['eligible']) {
                return back()->withInput()->withErrors([
                    'leave_type' => $employmentEligibility['message']
                ]);
            }
        }

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

        $totalDays = $this->calculateWorkingDays($startDate, $endDate);
        
        if ($totalDays <= 0) {
            return back()->withInput()->withErrors([
                'start_date' => 'Periode yang dipilih seluruhnya adalah hari libur atau akhir pekan. Anda tidak perlu mengajukan cuti.'
            ]);
        }

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

        $hasOverlap = $this->checkLeaveOverlap($user->id, $startDate, $endDate);
        if ($hasOverlap) {
            return back()->withInput()->withErrors([
                'start_date' => 'Anda sudah memiliki cuti yang disetujui pada periode tersebut. Silakan pilih tanggal lain.'
            ]);
        }
        
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

        if ($leaveApplication->leave_type == 'tahunan') {
            $user->decrement('annual_leave_quota', $totalDays);
        }

        return redirect()->route('leave-applications.index')->with('success', 'Pengajuan cuti Anda telah berhasil dikirim.');
    }

    public function destroy(LeaveApplication $leaveApplication)
    {
        $user = Auth::user();
        
        if ($leaveApplication->user_id != $user->id) {
            abort(403);
        }

        $canDelete = $leaveApplication->status === 'approved_by_hrd' 
            && $leaveApplication->end_date->isPast();

        if (!$canDelete) {
            return back()->with('error', 'Hanya riwayat cuti yang sudah selesai yang dapat diarsipkan.');
        }

        $leaveApplication->delete(); 

        return redirect()->route('leave-applications.index')
                        ->with('success', 'Riwayat cuti berhasil diarsipkan.');
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
            if ($current->dayOfWeek !== Carbon::SATURDAY && 
                $current->dayOfWeek !== Carbon::SUNDAY &&
                !\App\Models\Holiday::isHoliday($current)) {
                $totalDays++;
            }
            $current->addDay();
        }

        return $totalDays;
    }

    public function allLeaves(Request $request)
    {
        if (Auth::user()->role != 'hrd') {
            abort(403, 'Unauthorized action.');
        }

        $query = LeaveApplication::with(['applicant', 'applicant.division', 'leaderApprover', 'hrdApprover'])
                    ->latest();

        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'rejected') {
                $query->whereIn('status', ['rejected_by_leader', 'rejected_by_hrd']);
            } elseif ($request->status == 'completed') {
                $query->where('status', 'approved_by_hrd')
                    ->where('end_date', '<', Carbon::now());
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->has('leave_type') && $request->leave_type != '') {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->has('year') && $request->year != '') {
            $query->whereYear('start_date', $request->year);
        }

        if ($request->has('division') && $request->division != '') {
            $query->whereHas('applicant', function($q) use ($request) {
                $q->where('division_id', $request->division);
            });
        }

        if ($request->has('employee') && $request->employee != '') {
            $query->whereHas('applicant', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->employee . '%');
            });
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('start_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('start_date', '<=', $request->date_to);
        }

        $leaveApplications = $query->paginate(15);
        $divisions = Division::all();

        $totalApplications = LeaveApplication::count();
        $pendingCount = LeaveApplication::where('status', 'pending')->count();
        $approvedCount = LeaveApplication::where('status', 'approved_by_hrd')->count();
        $rejectedCount = LeaveApplication::whereIn('status', ['rejected_by_leader', 'rejected_by_hrd'])->count();

        return view('leave-applications.hrd.all-leaves', compact(
            'leaveApplications', 
            'divisions',
            'totalApplications',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    public function leaveReports(Request $request)
    {
        if (Auth::user()->role != 'hrd') {
            abort(403, 'Unauthorized action.');
        }

        return view('hrd.leave-reports');
    }

        public function divisionLeaves(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role == 'ketua_divisi') {
            $divisionId = $user->division_id;
            
            if (!$divisionId) {
                abort(403, 'Anda belum ditugaskan ke divisi manapun.');
            }

            $query = LeaveApplication::with(['applicant', 'applicant.division'])
                ->whereHas('applicant', function($query) use ($divisionId) {
                    $query->where('division_id', $divisionId);
                });

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('leave_type')) {
                $query->where('leave_type', $request->leave_type);
            }

            if ($request->filled('year')) {
                $query->whereYear('start_date', $request->year);
            }

            if ($request->filled('month')) {
                $query->whereMonth('start_date', $request->month);
            }

            if ($request->filled('employee')) {
                $query->whereHas('applicant', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->employee . '%');
                });
            }

            if ($request->filled('date_from')) {
                $query->where('start_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('end_date', '<=', $request->date_to);
            }

            $leaveApplications = $query->orderBy('created_at', 'desc')->paginate(10);

            $totalApplications = LeaveApplication::whereHas('applicant', function($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })->count();

            $pendingCount = LeaveApplication::whereHas('applicant', function($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })->where('status', 'pending')->count();

            $approvedCount = LeaveApplication::whereHas('applicant', function($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })->whereIn('status', ['approved_by_leader', 'approved_by_hrd'])->count();

            $rejectedCount = LeaveApplication::whereHas('applicant', function($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })->whereIn('status', ['rejected_by_leader', 'rejected_by_hrd'])->count();

            $years = LeaveApplication::whereHas('applicant', function($query) use ($divisionId) {
                    $query->where('division_id', $divisionId);
                })
                ->selectRaw('YEAR(start_date) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            $division = Division::find($divisionId);

            return view('division.leaves', [
                'leaveApplications' => $leaveApplications,
                'totalApplications' => $totalApplications,
                'pendingCount' => $pendingCount,
                'approvedCount' => $approvedCount,
                'rejectedCount' => $rejectedCount,
                'years' => $years,
                'division' => $division,
            ]);
            
        } else if ($user->role == 'hrd') {
            $query = LeaveApplication::with(['applicant', 'applicant.division']);
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('leave_type')) {
                $query->where('leave_type', $request->leave_type);
            }

            if ($request->filled('division')) {
                $query->whereHas('applicant', function($q) use ($request) {
                    $q->where('division_id', $request->division);
                });
            }

            if ($request->filled('year')) {
                $query->whereYear('start_date', $request->year);
            }

            if ($request->filled('month')) {
                $query->whereMonth('start_date', $request->month);
            }

            if ($request->filled('employee')) {
                $query->whereHas('applicant', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->employee . '%');
                });
            }

            if ($request->filled('date_from')) {
                $query->where('start_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('end_date', '<=', $request->date_to);
            }

            $leaveApplications = $query->orderBy('created_at', 'desc')->paginate(10);

            $totalApplications = LeaveApplication::count();
            $pendingCount = LeaveApplication::where('status', 'pending')->count();
            $approvedCount = LeaveApplication::where('status', 'approved_by_hrd')->count();
            $rejectedCount = LeaveApplication::whereIn('status', ['rejected_by_leader', 'rejected_by_hrd'])->count();
            
            $divisions = Division::all();
            $years = LeaveApplication::selectRaw('YEAR(start_date) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            return view('leave-applications.hrd.all-leaves', [
                'leaveApplications' => $leaveApplications,
                'totalApplications' => $totalApplications,
                'pendingCount' => $pendingCount,
                'approvedCount' => $approvedCount,
                'rejectedCount' => $rejectedCount,
                'divisions' => $divisions,
                'years' => $years,
            ]);
        }
        
        abort(403, 'Akses ditolak.');
    }
}