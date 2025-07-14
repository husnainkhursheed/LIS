<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensitivityResults extends Model
{
    use HasFactory;

     protected $fillable = [
        'sample_id',
        'sensitivity_profiles',
        'sensitivity',
        'review',
    ];

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }
}

