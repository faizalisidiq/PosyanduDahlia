<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnthropometryStandard extends Model
{
    /** @use HasFactory<\Database\Factories\AnthropometryStandardFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gender',
        'age_in_months',
        'l_value',
        'm_value',
        's_value',
        'sd_3_neg',
        'sd_2_neg',
        'sd_1_neg',
        'median',
        'sd_1_pos',
        'sd_2_pos',
        'sd_3_pos',
    ];
}
