<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'type',
        'description',
        'is_recurring'
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean'
    ];

    public function scopeForYear($query, $year = null)
    {
        $year = $year ?? date('Y');
        return $query->whereYear('date', $year)
                    ->orWhere('is_recurring', true);
    }

    public function scopeJointLeave($query)
    {
        return $query->where('type', 'joint_leave');
    }

    public function scopeNational($query)
    {
        return $query->where('type', 'national');
    }

    public static function isHoliday($date)
    {
        $date = Carbon::parse($date);
        
        return static::where(function($query) use ($date) {
            $query->whereDate('date', $date)
                  ->orWhere(function($q) use ($date) {
                      $q->where('is_recurring', true)
                        ->whereDay('date', $date->day)
                        ->whereMonth('date', $date->month);
                  });
        })->exists();
    }

    public static function getHolidaysForYear($year = null)
    {
        $year = $year ?? date('Y');
        
        return static::forYear($year)->get()->map(function($holiday) use ($year) {
            if ($holiday->is_recurring) {
                $holiday->date = Carbon::create($year, $holiday->date->month, $holiday->date->day);
            }
            return $holiday;
        });
    }
}