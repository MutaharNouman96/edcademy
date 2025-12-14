<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'educator_id',
        'date',
        'time',
        'duration',
        'subject',
        'message',
        'status',
    ];
}
