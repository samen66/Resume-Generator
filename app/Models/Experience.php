<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $fillable = [
        'resume_id',
        'company',
        'position',
        'start_month',
        'start_year',
        'end_month',
        'end_year',
        'responsibilities',
    ];
}
