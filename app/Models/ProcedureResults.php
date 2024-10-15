<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UrinalysisMicrobiologyResults;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcedureResults extends Model
{
    use HasFactory;

    protected $fillable = [
        'urinalysis_microbiology_result_id',
        'procedure',
        'specimen_note',
        // 'sensitivity_profiles',
        // 'sensitivity',
    ];

    public function urinalysisMicrobiologyResult()
    {
        return $this->belongsTo(UrinalysisMicrobiologyResults::class);
    }
}
