<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToHealthPost;

class Mother extends Model
{
    /** @use HasFactory<\Database\Factories\MotherFactory> */
    use HasFactory, SoftDeletes, BelongsToHealthPost;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($mother) {
            $mother->pregnancyRecords()->delete();
            $mother->childbirthRecords()->delete();
            $mother->children()->delete();
        });

        static::restoring(function ($mother) {
            $mother->pregnancyRecords()->onlyTrashed()->restore();
            $mother->childbirthRecords()->onlyTrashed()->restore();
            $mother->children()->onlyTrashed()->restore();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'health_post_id',
        'name',
        'husband_name',
        'identity_number',
        'phone_number',
        'address',
        'social_security_number',
        'health_facility',
        'birth_place',
        'birth_date',
        'blood_type',
        'height',
        'weight',
        'temperature',
        'systolic_pressure', 'diastolic_pressure', 'pulse',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'datetime',
    ];

    /**
     * Get the children that belong to the mother.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Children::class);
    }

    /**
     * Get the pregnancy records that belong to the mother.
     */
    public function pregnancyRecords(): HasMany
    {
        return $this->hasMany(PregnancyRecord::class);
    }

    /**
     * Get the childbirth records that belong to the mother.
     */
    public function childbirthRecords(): HasMany
    {
        return $this->hasMany(ChildbirthRecord::class);
    }
}
