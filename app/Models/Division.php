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

    /**
    * Mendapatkan user yang merupakan ketua divisi ini.
    */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Mendapatkan semua user (karyawan) yang ada di divisi ini.
     */
    public function members()
    {
        return $this->hasMany(User::class, 'division_id');
    }
}


