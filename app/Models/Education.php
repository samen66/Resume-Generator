<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $fillable = [
        'resume_id',
        'institution',
        'specialty',
        'degree',
        'start_year',
        'end_year',
    ];
}
