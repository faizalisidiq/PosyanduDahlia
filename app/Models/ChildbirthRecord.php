<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildbirthRecord extends Model
{
    /** @use HasFactory<\Database\Factories\ChildbirthRecordFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mother_id',
        'children_id',
        'staff_id',
        'child_order',
        'delivery_method',
        'delivery_date',
        'delivery_location',
        'baby_condition',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'delivery_date' => 'date',
    ];

    /**
     * Get the mother that owns the childbirth record.
     */
    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    public function children(): BelongsTo
    {
        return $this->belongsTo(Children::class);
    }

    /**
     * Get the staff that owns the childbirth record.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
