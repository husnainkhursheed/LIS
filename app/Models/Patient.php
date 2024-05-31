<?php

namespace App\Models;

use App\Models\Sample;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'surname',
        'contact_number',
        'dob',
        'sex',
    ];

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }
}
