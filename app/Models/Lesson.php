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
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function purchasers()
    {
        return $this->morphToMany(
            User::class,
            'purchasable',
            'user_purchased_items'
        )->withPivot('active')->withTimestamps();
    }

    public function scopepublished($query)
    {
        return $query->where('status', 'Published');
    }

    public function lesson_video_views()
    {
        return $this->hasMany(LessonVideoViews::class);
    }

    public function lesson_video_comments(){
        return $this->hasMany(LessonVideoComment::class);
    }

}
