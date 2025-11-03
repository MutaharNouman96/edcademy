<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFeature extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'drip_release', 'certificate', 'quizzes', 'downloads'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
