<?php

namespace App\Models;

use App\Models\SensitivityItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SensitivityProfiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function sensitivityValues()
    {
        return $this->hasMany(SensitivityItems::class , 'profile_id', 'id');
    }
}
