<?php

namespace App\Models;

use App\Models\Test;
use App\Models\Sample;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SampleTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'test_id',
    ];

    // Relationships
    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }


}
