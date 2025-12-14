<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CourseReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_id',
        'rating',
        'comment'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }


    protected function rating(): Attribute{
        return Attribute::make(
            get: fn ($value) => round($value, 1),
            set: fn ($value) => round($value, 1)
        );
    }
}
