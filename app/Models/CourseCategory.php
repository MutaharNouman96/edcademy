<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id', 'description'];

    public function children()
    {
        return $this->hasMany(CourseCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(CourseCategory::class, 'parent_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'course_category_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'category_id');
    }
}
