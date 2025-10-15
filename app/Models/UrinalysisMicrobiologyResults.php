<?php

namespace App\Models;

use App\Models\User;
use App\Models\TestReport;
use App\Models\ProcedureResults;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UrinalysisMicrobiologyResults extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_report_id',
        'description',
        'test_results',
        'flag',
        'reference_range',
        'test_notes',
        'sensitivity_profiles',
        'sensitivity',
        'review',
        'is_completed',
        'completed_by',
        'completed_at',
        'is_signed',
        'signed_by',
        'signed_at',
    ];

    public function testReport()
    {
        return $this->belongsTo(TestReport::class);
    }

    public function procedureResults()
    {
        return $this->hasMany(ProcedureResults::class , 'urinalysis_microbiology_result_id');
    }
    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
