<?php

namespace App\Models;

use App\Models\Test;
use App\Models\User;
use App\Models\Sample;
use Illuminate\Database\Eloquent\Model;
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
}
