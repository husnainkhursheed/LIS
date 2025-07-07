<?php

namespace App\Models;

use App\Models\Sample;
use Illuminate\Database\Eloquent\Model;
use App\Models\UrinalysisMicrobiologyResults;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcedureResults extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'procedure',
        'specimen_note',
        // 'sensitivity_profiles',
        // 'sensitivity',
    ];

    public function sampleResult()
    {
        return $this->belongsTo(Sample::class);
    }
}
