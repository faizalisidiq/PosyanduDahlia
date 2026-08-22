<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PregnancyRecord extends Model
{
    /** @use HasFactory<\Database\Factories\PregnancyRecordFactory> */
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('health_post_via_relation', function (Builder $builder) {
            if (Auth::check() && Auth::user()->staff) {
                $builder->whereHas('mother');
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mother_id',
        'staff_id',
        'visit_date',
        'pregnancy_order',
        'gestational_age',
        'weight',
        'arm_circumference',
        'blood_pressure',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'visit_date' => 'date',
    ];

    /**
     * Get the mother that owns the pregnancy record.
     */
    public function mother()
    {
        return $this->belongsTo(Mother::class);
    }

    /**
     * Get the staff that owns the pregnancy record.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}