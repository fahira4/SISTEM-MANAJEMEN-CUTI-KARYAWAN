<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\User;
use App\Models\Division;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $today = Carbon::today();

        $upcomingHolidays = Holiday::where('date', '>=', now())
                            ->orderBy('date', 'asc')
                            ->limit(3)
                            ->get();

        $employeesOnLeaveQuery = LeaveApplication::with(['applicant.division'])
            ->where('status', 'approved_by_hrd')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);

        if ($user->role == 'ketua_divisi') {
        $divisionId = $user->division_id;

        if ($divisionId) {
            $employeesOnLeaveQuery->whereHas('applicant', function($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            });
        }
    }

        $employeesOnLeave = $employeesOnLeaveQuery->get();

        $tomorrowHoliday = Holiday::where('date', now()->addDay()->format('Y-m-d'))->first();

        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $weeklyLeaves = LeaveApplication::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        
        $viewData = [
            'user' => $user,
            'upcomingHolidays' => $upcomingHolidays,
            'employeesOnLeave' => $employeesOnLeave,
            'tomorrowHoliday' => $tomorrowHoliday, 
            'weeklyLeaves' => $weeklyLeaves,       
        ];

        if ($user->role == 'karyawan') {
            $usedAnnualLeave = LeaveApplication::where('user_id', $user->id)
                                ->where('leave_type', 'tahunan')
                                ->where('status', 'approved_by_hrd')
                                ->sum('total_days');
            $quota = 12;
            $remainingQuota = $quota - $usedAnnualLeave;

            $currentMonthLeaves = LeaveApplication::where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $sickLeaveCount = LeaveApplication::where('user_id', $user->id)
                                ->where('leave_type', 'sakit')
                                ->count();

            $totalApplications = LeaveApplication::where('user_id', $user->id)->count();

            $viewData['remainingQuota'] = $remainingQuota;
            $viewData['currentMonthLeaves'] = $currentMonthLeaves;
            $viewData['sickLeaveCount'] = $sickLeaveCount;
            $viewData['totalApplications'] = $totalApplications;
            $viewData['usedAnnualLeave'] = $usedAnnualLeave;
        }

        if ($user->role == 'ketua_divisi') {
            $divisionId = $user->division_id;
            
            $totalDivisionLeaves = LeaveApplication::whereHas('applicant', function($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })->count();

            $pendingVerifications = LeaveApplication::whereHas('applicant', function($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })->where('status', 'pending')->count();

            $approvedDivisionLeaves = LeaveApplication::whereHas('applicant', function($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })->whereIn('status', ['approved_by_leader', 'approved_by_hrd'])->count();

            $rejectedDivisionLeaves = LeaveApplication::whereHas('applicant', function($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })->whereIn('status', ['rejected_by_leader', 'rejected_by_hrd'])->count();

            $divisionMembers = User::where('division_id', $divisionId)
                ->where('role', 'karyawan')
                ->withCount(['leaveApplications as active_leave_count' => function($query) {
                    $query->whereIn('status', ['approved_by_leader', 'approved_by_hrd'])
                          ->where('start_date', '<=', now())
                          ->where('end_date', '>=', now());
                }])
                ->get();

            $membersOnLeaveThisWeek = LeaveApplication::whereHas('applicant', function($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->whereIn('status', ['approved_by_leader', 'approved_by_hrd'])
            ->where(function($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('start_date', [$startOfWeek, $endOfWeek])
                      ->orWhereBetween('end_date', [$startOfWeek, $endOfWeek])
                      ->orWhere(function($q) use ($startOfWeek, $endOfWeek) {
                          $q->where('start_date', '<=', $startOfWeek)
                            ->where('end_date', '>=', $endOfWeek);
                      });
            })
            ->with('applicant')
            ->get();

            $viewData['totalDivisionLeaves'] = $totalDivisionLeaves;
            $viewData['pendingVerifications'] = $pendingVerifications;
            $viewData['approvedDivisionLeaves'] = $approvedDivisionLeaves;
            $viewData['rejectedDivisionLeaves'] = $rejectedDivisionLeaves;
            $viewData['divisionMembers'] = $divisionMembers;
            $viewData['membersOnLeaveThisWeek'] = $membersOnLeaveThisWeek;
        }

        if ($user->role == 'hrd') {
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();
            
            $viewData['monthlyLeavesCount'] = LeaveApplication::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $viewData['pendingFinalApprovalsCount'] = LeaveApplication::where('status', 'approved_by_leader')->count();
            $viewData['divisions'] = Division::with('leader')->withCount('members')->get();
           
            $viewData['totalApplications'] = LeaveApplication::count();
            $viewData['pendingCount'] = LeaveApplication::where('status', 'pending')->count();
            $viewData['approvedCount'] = LeaveApplication::where('status', 'approved_by_hrd')->count();
            $viewData['rejectedCount'] = LeaveApplication::whereIn('status', ['rejected_by_leader', 'rejected_by_hrd'])->count();
        }

        if ($user->role == 'admin') {
            $totalActiveEmployees = User::where('role', 'karyawan')->where('active_status', true)->count();
            $totalInactiveEmployees = User::where('role', 'karyawan')->where('active_status', false)->count();
            $totalUsers = $totalActiveEmployees + $totalInactiveEmployees;
            
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();
            $monthlyLeaves = LeaveApplication::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            
            $pendingApprovals = LeaveApplication::where('status', 'pending')->count();
            
            $oneYearAgo = now()->subYear();
            $newEmployees = User::where('role', 'karyawan')
                ->where('join_date', '>', $oneYearAgo)
                ->where('active_status', true)
                ->with('division')
                ->orderBy('join_date', 'desc')
                ->get();
            
            $totalDivisions = Division::count();

            $viewData['totalActiveEmployees'] = $totalActiveEmployees;
            $viewData['totalInactiveEmployees'] = $totalInactiveEmployees;
            $viewData['totalUsers'] = $totalUsers;
            $viewData['monthlyLeaves'] = $monthlyLeaves;
            $viewData['pendingApprovals'] = $pendingApprovals;
            $viewData['newEmployees'] = $newEmployees;
            $viewData['totalDivisions'] = $totalDivisions;
        }

        return view('dashboard', $viewData);
    }
}