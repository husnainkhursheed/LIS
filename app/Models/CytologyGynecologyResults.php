<?php

namespace App\Models;

use App\Models\TestReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CytologyGynecologyResults extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_report_id',
        'history',
        'last_period',
        'contraceptive',
        'previous_pap',
        'result',
        'cervix_examination',
        'specimen_adequacy',
        'diagnostic_interpretation',
        'recommend',
    ];

    public function testReport()
    {
        return $this->belongsTo(TestReport::class);
    }
}
