<?php

namespace App\Models;

use App\Models\Test;
use App\Models\TestProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfileFormula extends Model
{
    use HasFactory;

    protected $fillable = ['profile_id', 'calculated_test_id', 'formula', 'calculation_order'];

    protected $casts = ['formula' => 'array'];

    public function profile()
    {
        return $this->belongsTo(TestProfile::class, 'profile_id');
    }

    public function calculatedTest()
    {
        return $this->belongsTo(Test::class, 'calculated_test_id');
    }
}
