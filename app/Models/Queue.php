<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Queue extends Model
{
    /** @use HasFactory<\Database\Factories\QueueFactory> */
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('health_post_via_relation', function (Builder $builder) {
            if (Auth::check() && Auth::user()->staff) {
                $builder->whereHas('child.mother');
            }
        });
    }

    protected $fillable = [
        'child_id',
        'queue_number',
        'date',
        'status',
        'type',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function child()
    {
        return $this->belongsTo(Children::class);
    }
}