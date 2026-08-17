<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Elderly extends Model
{
    /** @use HasFactory<\Database\Factories\ElderlyFactory> */
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($elderly) {
            $elderly->screenings()->delete();
        });

        static::restoring(function ($elderly) {
            $elderly->screenings()->onlyTrashed()->restore();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identity_number',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone_number',
        'social_security_number',
        'health_facility',
        'blood_type',
        'temperature',
        'systolic_pressure', 'diastolic_pressure', 'pulse',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Get the screenings for the elderly.
     */
    public function screenings()
    {
        return $this->morphMany(IlpScreening::class, 'subjectable');
    }
}
