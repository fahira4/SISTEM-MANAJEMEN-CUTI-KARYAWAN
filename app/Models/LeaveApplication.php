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
            // Validasi: start_date tidak boleh setelah end_date
            if ($model->start_date > $model->end_date) {
                throw new \Exception('Tanggal mulai tidak boleh setelah tanggal selesai.');
            }

            // Validasi: total_days harus positif
            if ($model->total_days <= 0) {
                throw new \Exception('Total hari cuti harus lebih dari 0.');
            }
        });
    }

    public function applicant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Siapa Ketua Divisi yang menyetujui.
     */
    public function leaderApprover()
    {
        return $this->belongsTo(User::class, 'leader_approver_id')->with('division');
    }

    /**
     * Relasi: Siapa HRD yang menyetujui.
     */
    public function hrdApprover()
    {
        return $this->belongsTo(User::class, 'hrd_approver_id');
    }

    /**
     * Cek apakah cuti sudah disetujui HRD - PERBAIKI DI SINI
     */
    public function getIsApprovedByHrdAttribute()
    {
        // PERBAIKAN: sesuaikan dengan nama kolom di database
        return $this->status === 'approved_by_hrd' && 
               !is_null($this->hrd_approver_id) && 
               !is_null($this->hrd_approval_at);
    }

    /**
     * Cek apakah surat izin cuti tersedia
     */
    public function getIsLeaveLetterAvailableAttribute()
    {
        return $this->is_approved_by_hrd;
    }

    /**
     * Scope untuk pengajuan dengan status pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk pengajuan yang sudah disetujui leader
     */
    public function scopeApprovedByLeader($query)
    {
        return $query->where('status', 'approved_by_leader');
    }

    /**
     * Scope untuk pengajuan yang sudah disetujui HRD
     */
    public function scopeApprovedByHrd($query)
    {
        return $query->where('status', 'approved_by_hrd');
    }

    /**
     * Scope untuk pengajuan yang ditolak
     */
    public function scopeRejected($query)
    {
        return $query->whereIn('status', ['rejected_by_leader', 'rejected_by_hrd']);
    }

    /**
     * Scope untuk pengajuan yang dibatalkan
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope UNTUK HRD: pengajuan yang menunggu approval final HRD
     * - Pengajuan biasa yang sudah disetujui leader
     * - Pengajuan dari ketua divisi (langsung ke HRD)
     * - Pengajuan dari karyawan tanpa divisi
     */
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

    /**
     * Scope UNTUK KETUA DIVISI: pengajuan dari anggota tim
     */
    public function scopeForLeaderApproval($query, $divisionId)
    {
        return $query->where('status', 'pending')
                    ->whereHas('applicant', function($applicantQuery) use ($divisionId) {
                        $applicantQuery->where('division_id', $divisionId)
                                      ->where('role', 'karyawan');
                    });
    }
    
}