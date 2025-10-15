<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'test_profile_id',
    ];
}
