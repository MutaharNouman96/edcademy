<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function show($slug, $id)
    {
        $course = Course::with([
            'category',
            'educator',
            // Only surface admin-verified (active) lessons in the curriculum.
            'sections.lessons' => fn ($query) => $query->active(),
            'lessons' => fn ($query) => $query->active(),
            'reviews',
        ])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
           
            ->where('id', $id)
            ->firstOrFail();

            if($course->status == 'draft' && $course->user_id != (auth()->user()->id ?? '')){ 
                abort(404);
            }

        // Instructor’s more courses
        $moreCourses = Course::where('user_id', $course->user_id)
            ->where('id', '!=', $course->id)
            ->published()
            ->take(4)
            ->get();

        // Preview clip must also be an admin-verified (active) lesson.
        $freeVideo = Lesson::where("course_id", $course->id)->where("free", true)->active()->first();

        // Students enrolled count
        $studentsEnrolled = $course->coursePurchases()->count();

        return view('website.course', compact('course', 'moreCourses', 'studentsEnrolled', 'freeVideo'));
    }
}
