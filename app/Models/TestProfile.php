<?php

namespace App\Models;

use App\Models\Test;
use App\Models\Sample;
use App\Models\ProfileFormula;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestProfile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cost' , 'specimentype_id'];

    public function subProfiles()
    {
        return $this->belongsToMany(TestProfile::class, 'profile_profiles', 'parent_profile_id', 'child_profile_id');
    }

    public function parentProfiles()
    {
        return $this->belongsToMany(TestProfile::class, 'profile_profiles', 'child_profile_id', 'parent_profile_id');
    }

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

    public function specimen_type()
    {
        return $this->belongsTo(SpecimenType::class, 'specimentype_id');
    }

    public function profileFormulas()
    {
        return $this->hasMany(ProfileFormula::class, 'profile_id');
    }
}
