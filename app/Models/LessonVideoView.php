<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonVideoView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'watch_time',
        'completed',
        'liked',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'liked' => 'boolean',
        'watch_time' => 'integer',
    ];

    protected $appends = ['watch_time_formatted'];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }


    public function getWatchTimeFormattedAttribute(): string
    {
        $seconds = (int) $this->watch_time;

        if ($seconds <= 0) {
            return '0s';
        }

        $hours   = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs    = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02dh %02dm %02ds', $hours, $minutes, $secs);
        }

        if ($minutes > 0) {
            return sprintf('%02dm %02ds', $minutes, $secs);
        }

        return sprintf('%02ds', $secs);
    }
}
