<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'division_id',
        'annual_leave_quota',
        'phone_number',         
        'address',              
        'profile_photo_path',
        'join_date',
        'active_status',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'join_date' => 'date',
            'active_status' => 'boolean',
            'status' => 'string',
        ];
    }
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

public function getEmploymentPeriodAttribute()
{
    if (!$this->join_date) {
        return '0 hari';
    }

    try {
        $joinDate = Carbon::parse($this->join_date)->startOfDay();
        $now = Carbon::now()->startOfDay();
        
        if ($joinDate->gt($now)) {
            return '0 hari';
        }
        
        $days = $joinDate->diffInDays($now);
        
        if ($days == 0) {
            return 'Hari ini';
        } elseif ($days == 1) {
            return '1 hari';
        } elseif ($days < 30) {
            return "{$days} hari";
        } elseif ($days < 365) {
            $months = floor($days / 30);
            return "{$months} bulan";
        } else {
            $years = floor($days / 365);
            $remainingMonths = floor(($days % 365) / 30);
            if ($remainingMonths > 0) {
                return "{$years} tahun {$remainingMonths} bulan";
            } else {
                return "{$years} tahun";
            }
        }
        
    } catch (\Exception $e) {
        \Log::error("Error calculating employment period: " . $e->getMessage());
        return '0 hari';
    }
}
    public function scopeByEmploymentPeriod($query, $period)
    {
        $now = now();
        
        return match($period) {
            'less_than_30_days' => $query->where('join_date', '>=', $now->subDays(30)),
            '30_90_days' => $query->whereBetween('join_date', [$now->subDays(90), $now->subDays(30)]),
            '90_180_days' => $query->whereBetween('join_date', [$now->subDays(180), $now->subDays(90)]),
            '180_365_days' => $query->whereBetween('join_date', [$now->subDays(365), $now->subDays(180)]),
            'more_than_1_year' => $query->where('join_date', '<=', $now->subDays(365)),
            default => $query
        };
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class, 'user_id');
    }

    public function leadingDivision()
    {
        return $this->hasOne(Division::class, 'leader_id', 'id');
    }   

    public function isDivisionLeader()
    {
        return !is_null($this->leadingDivision);
    }
    
    public function isLeadingDivision($divisionId)
    {
        return $this->leadingDivision && $this->leadingDivision->id == $divisionId;
    }

    public function isEligibleForAnnualLeave()
    {
        if (!$this->join_date) {
            return false;
        }

        $joinDate = Carbon::parse($this->join_date);
        $currentDate = Carbon::now();
        
        return $joinDate->diffInMonths($currentDate) >= 12;
    }

    public function getMonthsOfWorkAttribute()
    {
        if (!$this->join_date) {
            return 0;
        }

        $joinDate = Carbon::parse($this->join_date);
        $currentDate = Carbon::now();
        
        return $joinDate->diffInMonths($currentDate);
    }
}

?>