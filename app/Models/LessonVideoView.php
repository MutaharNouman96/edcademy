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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
