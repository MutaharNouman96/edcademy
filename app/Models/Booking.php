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
        'meeting_link',
        'platform',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    public function getScheduledAtAttribute()
    {
        return $this->date->setTimeFromTimeString($this->time);
    }
}
