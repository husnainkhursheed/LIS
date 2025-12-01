<?php

namespace App\Models;

use App\Models\TestProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecimenType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'priority',
    ];

    public function tests()
    {
        return $this->hasMany(Test::class, 'specimen_type');
    }

    public function testProfiles()
    {
        return $this->hasMany(TestProfile::class, 'specimentype_id');
    }
}
