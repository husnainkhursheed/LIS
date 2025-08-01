<?php

namespace App\Models;

use App\Models\Test;
use App\Models\User;
use App\Models\Sample;
use App\Models\BiochemHaemoResults;
use Illuminate\Database\Eloquent\Model;
use App\Models\CytologyGynecologyResults;
use App\Models\UrinalysisMicrobiologyResults;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'test_id',
        'results',
        'notes',
        'is_completed',
        'is_signed',
        'signed_by',
    ];

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    // public function auditTrails()
    // {
    //     return $this->hasMany(AuditTrail::class);
    // }

    public function biochemHaemoResults()
    {
        return $this->hasMany(BiochemHaemoResults::class);
    }

    public function cytologyGynecologyResults()
    {
        return $this->hasMany(CytologyGynecologyResults::class);
    }

    public function urinalysisMicrobiologyResults()
    {
        return $this->hasMany(UrinalysisMicrobiologyResults::class);
    }
}
