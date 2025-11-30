<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Carbon\Carbon;

class CheckLeaveStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->syncUserStatus();

        return $next($request);
    }

    private function syncUserStatus()
    {
        try {
            $today = Carbon::today();
            User::where('active_status', true)
                ->whereHas('leaveApplications', function ($query) use ($today) {
                    $query->where('status', 'approved_by_hrd')
                          ->whereDate('start_date', '<=', $today)
                          ->whereDate('end_date', '>=', $today); 
                })
                ->update(['active_status' => false]);

            User::where('active_status', false)
                ->whereDoesntHave('leaveApplications', function ($query) use ($today) {
                    $query->where('status', 'approved_by_hrd')
                          ->whereDate('start_date', '<=', $today)
                          ->whereDate('end_date', '>=', $today);
                })
                ->update(['active_status' => true]);
                
        } catch (\Exception $e) {
            \Log::error('Gagal update status cuti otomatis: ' . $e->getMessage());
        }
    }
}