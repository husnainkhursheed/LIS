<?php

namespace App\Models;

use App\Models\TestReport;
use App\Models\ProcedureResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UrinalysisMicrobiologyResults extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_report_id',
        's_gravity',
        'ph',
        'bilirubin',
        'blood',
        'leucocytes',
        'glucose',
        'nitrite',
        'ketones',
        'urobilinogen',
        'proteins',
        'colour',
        'appearance',
        'epith_cells',
        'bacteria',
        'white_cells',
        'yeast',
        'red_cells',
        'trichomonas',
        'casts',
        'crystals',
        // 'specimen',
        // 'procedure',
        // 'specimen_note',
        'sensitivity_profiles',
        'sensitivity',
        'review',
    ];

    public function testReport()
    {
        return $this->belongsTo(TestReport::class);
    }

    public function procedureResults()
    {
        return $this->hasMany(ProcedureResults::class , 'urinalysis_microbiology_result_id');
    }
}
