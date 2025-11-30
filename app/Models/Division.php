<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = [
    'name',
    'description',
    'leader_id',
    ];

    
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->hasMany(User::class, 'division_id');
    }

    public function scopeByLeader($query, $leaderId)
    {
        return $query->where('leader_id', $leaderId);
    }

    public function getRegularMemberCountAttribute()
    {
        return $this->regularMembers()->count();
    }
}


