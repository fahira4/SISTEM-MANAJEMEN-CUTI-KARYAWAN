<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment_path',
        'address_during_leave',
        'emergency_contact',
        'status',
        'leader_approver_id',
        'leader_approval_at',
        'leader_rejection_notes',
        'hrd_approver_id',
        'hrd_approval_at',
        'hrd_rejection_notes',
        'cancellation_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'leader_approval_at' => 'datetime',
        'hrd_approval_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];
    
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->start_date > $model->end_date) {
                throw new \Exception('Tanggal mulai tidak boleh setelah tanggal selesai.');
            }

            if ($model->total_days <= 0) {
                throw new \Exception('Total hari cuti harus lebih dari 0.');
            }
        });
    }

    public function applicant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leaderApprover()
    {
        return $this->belongsTo(User::class, 'leader_approver_id')->with('division');
    }

    
    public function hrdApprover()
    {
        return $this->belongsTo(User::class, 'hrd_approver_id');
    }

    public function getIsApprovedByHrdAttribute()
    {
        return $this->status === 'approved_by_hrd' && 
               !is_null($this->hrd_approver_id) && 
               !is_null($this->hrd_approval_at);
    }

    public function getIsLeaveLetterAvailableAttribute()
    {
        return $this->is_approved_by_hrd;
    }

    public function scopeForYear($query, $year)
    {
        return $query->whereYear('start_date', $year);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'approved_by_hrd')
                    ->where('end_date', '<', now());
    }

    public function scopeAllRejected($query)
    {
        return $query->whereIn('status', ['rejected_by_leader', 'rejected_by_hrd']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApprovedByLeader($query)
    {
        return $query->where('status', 'approved_by_leader');
    }

    public function scopeApprovedByHrd($query)
    {
        return $query->where('status', 'approved_by_hrd');
    }

    public function scopeRejected($query)
    {
        return $query->whereIn('status', ['rejected_by_leader', 'rejected_by_hrd']);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeForHrdApproval($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'approved_by_leader')
              ->orWhere(function($q2) {
                  $q2->where('status', 'pending')
                     ->whereHas('applicant', function($applicantQuery) {
                         $applicantQuery->where('role', 'ketua_divisi')
                                       ->orWhere(function($userQuery) {
                                           $userQuery->where('role', 'karyawan')
                                                     ->whereNull('division_id');
                                       });
                     });
              });
        });
    }

    public function scopeForLeaderApproval($query, $divisionId)
    {
        return $query->where('status', 'pending')
                    ->whereHas('applicant', function($applicantQuery) use ($divisionId) {
                        $applicantQuery->where('division_id', $divisionId)
                                      ->where('role', 'karyawan');
                    });
    }
    
}