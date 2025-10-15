<?php

namespace App\Models;

use App\Models\User;
use App\Models\Sample;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SampleDepartmentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'department',
        'note',
        'is_signed',
        'signed_by',
        'signed_at',
        'is_completed',
        'completed_by',
        'completed_at',
    ];

    public function sample()
    {
        return $this->belongsTo(Sample::class, 'sample_id');
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

}
