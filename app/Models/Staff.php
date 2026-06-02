<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    /** @use HasFactory<\Database\Factories\StaffFactory> */
    use HasFactory;

    protected $table = 'staff';

    const AVATAR_PATH = 'staff';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'health_post_id',
        'user_id',
        'address',
        'phone',
        'avatar',
        'role',
        'status',
    ];

    /**
     * Get the user that owns the staff.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the health post that owns the staff.
     */
    public function healthPost(): BelongsTo
    {
        return $this->belongsTo(HealthPost::class);
    }

    /**
     * Get the pregnancy records for the staff.
     */
    public function pregnancyRecords(): HasMany
    {
        return $this->hasMany(PregnancyRecord::class);
    }

    /**
     * Get the growth monitoring records for the staff.
     */
    public function growthMonitorings(): HasMany
    {
        return $this->hasMany(GrowthMonitoring::class);
    }

    /**
     * Get the ilp screenings for the staff.
     */
    public function ilpScreenings(): HasMany
    {
        return $this->hasMany(IlpScreening::class);
    }
}
