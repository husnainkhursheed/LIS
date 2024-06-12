<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomDropdown extends Model
{
    use HasFactory;

    protected $fillable = [
        'dropdown_name',
        'value',
    ];
}
