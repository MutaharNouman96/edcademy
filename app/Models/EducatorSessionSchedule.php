<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducatorSessionSchedule extends Model
{
    use HasFactory;

    protected $table = 'educator_session_schedules';

    protected $fillable = [
        'educator_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public const DAYS = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday',
    ];

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? '';
    }
}
