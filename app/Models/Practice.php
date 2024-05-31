<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Practice extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = [
        'v_name',
        'address',
        'town',
        'zip',
        'country',
        'telephone',
        'email',
        'is_active',
        'inserted_by',
        'updated_by',
    ];

}
