<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Children extends Model
{
    /** @use HasFactory<\Database\Factories\ChildrenFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identity_number',
        'name',
        'mother_id',
        'gender',
        'birth_place',
        'birth_date',
        'birth_weight',
        'birth_height',
        'bpjs_facility',
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
     * Get the childbirth records for the children.
     */
    public function childbirthRecords(): BelongsTo
    {
        return $this->belongsTo(ChildbirthRecord::class);
    }

    /**
     * Get the mother that owns the children.
     */
    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    /**
     * Get the growth monitoring records for the children.
     */
    public function growthMonitorings(): HasMany
    {
        return $this->hasMany(GrowthMonitoring::class, 'child_id');
    }

    /**
     * Get the queues for the children.
     */
    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class, 'child_id');
    }
}
