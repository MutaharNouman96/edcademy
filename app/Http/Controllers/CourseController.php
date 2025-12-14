<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function show($slug)
    {
        $course = Course::with([
            'category',
            'educator',
            'sections.lessons',
            'reviews',
        ])
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->where('slug', $slug)
        ->firstOrFail();

        // Instructor’s more courses
        $moreCourses = Course::where('user_id', $course->user_id)
            ->where('id', '!=', $course->id)
            ->published()
            ->take(4)
            ->get();

            $freeVideo = Lesson::where("course_id", $course->id)->where("free", true)->first();

        // Students enrolled count
        $studentsEnrolled = $course->coursePurchases()->count();

        return view('website.course', compact('course', 'moreCourses', 'studentsEnrolled', 'freeVideo'));
    }
}
