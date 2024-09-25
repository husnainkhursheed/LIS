<?php

namespace App\Models;

use App\Models\Test;
use App\Models\Sample;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestProfile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cost'];


    // Many-to-Many relationship with Tests
    public function tests()
    {
        return $this->belongsToMany(Test::class, 'profile_tests');
    }

    // Many-to-Many relationship with Samples
    public function samples()
    {
        return $this->belongsToMany(Sample::class, 'sample_profiles');
    }

    public function departments()
    {
        return $this->hasMany(ProfileDepartment::class, 'test_profile_id');
    }
}
