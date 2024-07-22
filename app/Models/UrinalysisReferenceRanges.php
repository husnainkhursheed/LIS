<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrinalysisReferenceRanges extends Model
{
    use HasFactory;

    protected $fillable = [
        'analyte',
        'low',
        'high',
        'male_low',
        'male_high',
        'female_low',
        'female_high',
        'nomanualvalues_ref_range',
    ];
}
