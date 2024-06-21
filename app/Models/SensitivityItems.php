<?php

namespace App\Models;

use App\Models\SensitivityProfiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SensitivityItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'antibiotic',
        // 'measure',
        // 'designation',
        // 'sensitivity',
        // 'resistant',
        // 'intermediate',
    ];

    public function profile()
    {
        return $this->belongsTo(SensitivityProfiles::class);
    }
}
