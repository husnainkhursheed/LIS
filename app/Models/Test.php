<?php

namespace App\Models;

use App\Models\Sample;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department',
        'specimen_type',
        'cost',
        'reference_range',
    ];

    public function samples()
    {
        return $this->belongsToMany(Sample::class, 'sample_tests');
    }
}
