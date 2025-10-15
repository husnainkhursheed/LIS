<?php

namespace App\Models;

use App\Models\User;
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
    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
