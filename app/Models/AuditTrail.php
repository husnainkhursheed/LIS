<?php

namespace App\Models;

use App\Models\User;
use App\Models\TestReport;
use App\Models\BiochemHaemoResults;
use Illuminate\Database\Eloquent\Model;
use App\Models\CytologyGynecologyResults;
use App\Models\UrinalysisMicrobiologyResults;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_report_id',
        'user_id',
        'changed_at',
        'changes',
        'field_name',
        'from_value',
        'to_value'
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    // ⚠️ Conditional relations
    public function biochemResult()
    {
        return $this->belongsTo(
            BiochemHaemoResults::class,
            'test_report_id',
            'id'
        );
    }

    public function cytologyResult()
    {
        return $this->belongsTo(
            CytologyGynecologyResults::class,
            'test_report_id',
            'id'
        );
    }

    public function urinalysisResult()
    {
        return $this->belongsTo(
            UrinalysisMicrobiologyResults::class,
            'test_report_id',
            'id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
