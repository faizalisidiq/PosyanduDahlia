<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    /** @use HasFactory<\Database\Factories\QueueFactory> */
    use HasFactory;

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
