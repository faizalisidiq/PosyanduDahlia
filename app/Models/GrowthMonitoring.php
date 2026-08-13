<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthMonitoring extends Model
{
    /** @use HasFactory<\Database\Factories\GrowthMonitoringFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'child_id',
        'staff_id',
        'checkup_date',
        'weight',
        'height',
        'arm_circumference',
        'head_circumference',
        'z_score',
        'status',
        'next_checkup_date',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'checkup_date' => 'date',
        'next_checkup_date' => 'date',
    ];

    /**
     * Get the child that owns the growth monitoring.
     */
    public function child()
    {
        return $this->belongsTo(Children::class, 'child_id');
    }

    /**
     * Get the staff that owns the growth monitoring.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
