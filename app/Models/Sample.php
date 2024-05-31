<?php

namespace App\Models;

use App\Models\Test;
use App\Models\Patient;
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
        'bill_to'
    ];

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'sample_tests');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }




}
