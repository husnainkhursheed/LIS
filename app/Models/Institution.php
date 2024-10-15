<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_number',
        'street_name',
        'address_line_2',
        'area',
        'email',
        'is_active',
    ];
}
