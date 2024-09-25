<?php

namespace App\Models;

use App\Models\Test;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\TestReport;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_number',
        'access_number',
        'collected_date',
        'received_date',
        'received_time',
        'patient_id',
        'institution_id',
        'doctor_id',
        'bill_to',
        'is_completed',
        'is_signed',
        'signed_by',
        'signed_at',
        'completed_by',
        'completed_at',
        'notes',
    ];

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'sample_tests');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function testReports()
    {
        return $this->hasMany(TestReport::class);
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function validateBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

        // Many-to-Many relationship with TestProfile
    public function testProfiles()
    {
        return $this->belongsToMany(TestProfile::class, 'sample_profiles');
    }



}
