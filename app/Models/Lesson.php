<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'couse_section_id',
        'course_id',
        'name',
        'type',
        'category',
        'video_link',
        'description',
        'duration',
        'is_preview',
        'order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }
    
}
