<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mother extends Model
{
    /** @use HasFactory<\Database\Factories\MotherFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
}
