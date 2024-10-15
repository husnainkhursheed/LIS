<?php

namespace App\Models;

use App\Models\User;
use App\Models\TestReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiochemHaemoResults extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_report_id',
        'reference',
        'note',
        'description',
        'test_results',
        'flag',
        'reference_range',
        'test_notes',
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
