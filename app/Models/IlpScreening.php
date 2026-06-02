<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IlpScreening extends Model
{
    /** @use HasFactory<\Database\Factories\IlpScreeningFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subjectable_type',
        'subjectable_id',
        'staff_id',
        'checkup_date',
        'results',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'results' => 'array',
        'checkup_date' => 'date',
    ];

    /**
     * Get the subjectable that owns the IlpScreening
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subjectable()
    {
        return $this->morphTo();
    }

    /**
     * Get the staff that owns the IlpScreening
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the age at the time of screening.
     */
    public function getAgeAttribute()
    {
        if (! $this->subjectable || ! $this->checkup_date) {
            return null;
        }

        return $this->checkup_date->diff($this->subjectable->birth_date);
    }
}
