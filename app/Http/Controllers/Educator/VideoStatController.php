<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LessonVideoViews;
use Illuminate\Http\Request;

class VideoStatController extends Controller
{
    //
    public function index(Request $request)
    {
        $courseId  = $request->course_id ? $request->course_id : null;

        $videoViews = LessonVideoViews::when($courseId, function ($query) use ($request) {
            $query->whereHas('lesson', function ($lessonQuery) use ($request) {
                $lessonQuery->where('course_id', $request->course_id);
            });
        })->get();
        $totalViews = LessonVideoViews::when($courseId, function ($query) use ($request) {
            $query->whereHas('lesson', function ($lessonQuery) use ($request) {
                $lessonQuery->where('course_id', $request->course_id);
            });
        })->count();
        $averageWatchTime = LessonVideoViews::when($courseId, function ($query) use ($request) {
            $query->whereHas('lesson', function ($lessonQuery) use ($request) {
                $lessonQuery->where('course_id', $request->course_id);
            });
        })->avg('watch_time');

        $averageWatchTime = (int)($averageWatchTime / 60);

        $completionRate = $totalViews == 0 ? 0 : LessonVideoViews::where('completed', 1)->count() / $totalViews * 100;

        $myCourses = Course::where('user_id', auth()->user()->id)->get();


        return view('crm.educator.video_stats.index', compact('videoViews', 'totalViews', 'averageWatchTime', 'completionRate', 'myCourses'));
    }
}
