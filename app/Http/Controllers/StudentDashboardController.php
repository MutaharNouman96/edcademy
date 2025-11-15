<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CoursePurchase;
use App\Models\VideoStat;
use App\Models\ProgressTracking;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledCourses = CoursePurchase::where('student_id', $user->id)->count();

        $watchedTime = VideoStat::whereHas('lesson.course.coursePurchases', function ($query) use ($user) {
            $query->where('student_id', $user->id);
        })
        ->where('created_at', '>=', Carbon::now()->subDays(30))
        ->sum('average_watch_time');

        $totalCourses = CoursePurchase::where('student_id', $user->id)->count();
        $completedCourses = ProgressTracking::where('student_id', $user->id)
            ->distinct('course_id')
            ->count();

        $completionRate = ($totalCourses > 0) ? round(($completedCourses / $totalCourses) * 100, 2) : 0;

        $totalSpent = CoursePurchase::where('student_id', $user->id)
            ->join('courses', 'course_purchases.course_id', '=', 'courses.id')
            ->sum('courses.price');

        $courseCompletionData = [];
        $enrolledCoursesData = CoursePurchase::where('student_id', $user->id)->with('course')->get();

        foreach ($enrolledCoursesData as $purchase) {
            $completedLessonsCount = ProgressTracking::where('student_id', $user->id)
                ->where('course_id', $purchase->course_id)
                ->distinct('lesson_id')
                ->count();

            $totalLessonsCount = $purchase->course->lessons->count();

            $completionPercentage = ($totalLessonsCount > 0) ? round(($completedLessonsCount / $totalLessonsCount) * 100, 2) : 0;

            $courseCompletionData[] = [
                'course_title' => $purchase->course->title,
                'completion_percentage' => $completionPercentage,
            ];
        }


        $watchTimeLabels = [];
        $watchTimeData = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $watchTimeLabels[] = $date->format('M d');
            $dailyWatchTime = VideoStat::whereHas('lesson.course.coursePurchases', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->whereDate('created_at', $date)
            ->sum('average_watch_time');
            $watchTimeData[] = round($dailyWatchTime / 60, 1);
        }


        $myCourses = [];
        foreach ($enrolledCoursesData as $purchase) {
            $course = $purchase->course;
            $completedLessonsCount = ProgressTracking::where('student_id', $user->id)
                ->where('course_id', $course->id)
                ->distinct('lesson_id')
                ->count();
            $totalLessonsCount = $course->lessons->count();
            $progress = ($totalLessonsCount > 0) ? round(($completedLessonsCount / $totalLessonsCount), 2) : 0;

            $lastViewedLesson = VideoStat::whereHas('lesson.course.coursePurchases', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->latest()
            ->first();

            $lastViewed = $lastViewedLesson ? Carbon::parse($lastViewedLesson->created_at)->diffForHumans() : 'Never';

            $totalWatchTime = VideoStat::whereHas('lesson.course.coursePurchases', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->sum('average_watch_time');
            $hoursWatched = round($totalWatchTime / 3600, 1);

            $newVideosCount = Lesson::where('course_id', $course->id)
                ->where('created_at', '>', Carbon::now()->subDays(7))
                ->count();

            $myCourses[] = [
                'id' => $course->id,
                'title' => $course->title,
                'subject' => $course->subject,
                'progress' => $progress,
                'hours' => $hoursWatched,
                'last' => $lastViewed,
                'thumb' => $course->thumbnail,
                'newVideos' => $newVideosCount,
            ];
        }

        // New Videos Feed
        $newVideosFeed = [];
        $enrolledCourseIds = $enrolledCoursesData->pluck('course_id');
        $recentLessons = Lesson::whereIn('course_id', $enrolledCourseIds)
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->with('course')
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentLessons as $lesson) {
            $newVideosFeed[] = [
                'course' => $lesson->course->title,
                'lesson' => $lesson->title,
                'when' => Carbon::parse($lesson->created_at)->diffForHumans(),
                'duration' => $lesson->duration,
                'id' => $lesson->id,
            ];
        }


        $payments = Payment::where('student_id', $user->id)
            ->with('course')
            ->latest()
            ->take(5)
            ->get();

        $paymentData = [];
        foreach ($payments as $payment) {
            $paymentData[] = [
                'date' => Carbon::parse($payment->created_at)->format('Y-m-d'),
                'course' => $payment->course->title ?? 'N/A',
                'method' => $payment->payment_method ?? 'N/A',
                'amount' => $payment->gross_amount ?? 0.00,
            ];
        }


        return view('student.dashboard', compact('enrolledCourses', 'watchedTime', 'completionRate', 'totalSpent', 'courseCompletionData', 'watchTimeLabels', 'watchTimeData', 'myCourses', 'newVideosFeed', 'paymentData'));
    }
}
