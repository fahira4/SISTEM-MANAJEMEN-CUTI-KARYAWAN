<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    /**
     * Otomatis konversi kolom tanggal.
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'leader_approval_at' => 'datetime',
        'hrd_approval_at' => 'datetime',
    ];

    /**
     * Relasi: Siapa Karyawan yang mengajukan cuti ini.
     */
    public function applicant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Siapa Ketua Divisi yang menyetujui.
     */
    public function leaderApprover()
    {
        return $this->belongsTo(User::class, 'leader_approver_id');
    }

    /**
     * Relasi: Siapa HRD yang menyetujui.
     */
    public function hrdApprover()
    {
        return $this->belongsTo(User::class, 'hrd_approver_id');
    }
}
