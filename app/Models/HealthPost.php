<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthPost extends Model
{
    /** @use HasFactory<\Database\Factories\HealthPostFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'phone',
    ];

    /**
     * Get the staffs for the health post.
     */
    public function staffs(): HasMany
    {
        return $this->hasMany(Staff::class);
    }
}
