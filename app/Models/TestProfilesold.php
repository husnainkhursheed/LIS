<?php

namespace App\Models;

use App\Models\Test;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestProfiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'specimen_type'
    ];

    public function tests()
    {
        return $this->hasMany(Test::class, 'test_profile_id');
    }
}
