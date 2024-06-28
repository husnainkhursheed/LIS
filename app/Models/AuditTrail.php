<?php

namespace App\Models;

use App\Models\User;
use App\Models\TestReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_report_id',
        'user_id',
        'changed_at',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function testReport()
    {
        return $this->belongsTo(TestReport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
