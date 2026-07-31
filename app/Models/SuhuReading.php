<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuhuReading extends Model
{
    protected $fillable = ['patient_id', 'suhu', 'satuan', 'recorded_at'];
}