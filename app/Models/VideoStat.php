<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'lesson_id',
        'views',
        'likes',
        'average_watch_time',
        'completion_rate',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
