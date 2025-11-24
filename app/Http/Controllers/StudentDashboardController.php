<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CoursePurchase;
use App\Models\VideoStat;
use App\Models\ProgressTracking;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\CourseSection;
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

    public function myCourses()
    {
        $user = Auth::user();
        $enrolledCoursesData = CoursePurchase::where('student_id', $user->id)->with('course')->get();

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

        return view('student.my_courses', compact('myCourses'));
    }

    public function newVideos()
    {
        $user = Auth::user();
        $enrolledCourseIds = CoursePurchase::where('student_id', $user->id)->pluck('course_id');
        $recentLessons = Lesson::whereIn('course_id', $enrolledCourseIds)
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->with('course')
            ->latest()
            ->get();

        $newVideosFeed = [];
        foreach ($recentLessons as $lesson) {
            $newVideosFeed[] = [
                'course' => $lesson->course->title,
                'lesson' => $lesson->title,
                'when' => Carbon::parse($lesson->created_at)->diffForHumans(),
                'duration' => $lesson->duration,
                'id' => $lesson->id,
            ];
        }

        return view('student.new_videos', compact('newVideosFeed'));
    }

    // public function analytics()
    // {
    //     $user = Auth::user();
    //     $totalCourses = CoursePurchase::where('student_id', $user->id)->count();
    //     $completedCourses = ProgressTracking::where('student_id', $user->id)
    //         ->distinct('course_id')
    //         ->count();
    //     $completionRate = ($totalCourses > 0) ? round(($completedCourses / $totalCourses) * 100, 2) : 0;

    //     $totalWatchTime = VideoStat::whereHas('lesson.course.coursePurchases', function ($query) use ($user) {
    //         $query->where('student_id', $user->id);
    //     })
    //     ->sum('average_watch_time');
    //     $hoursWatched = round($totalWatchTime / 3600, 1);

    //     $courseCompletionData = [];
    //     $enrolledCoursesData = CoursePurchase::where('student_id', $user->id)->with('course')->get();

    //     foreach ($enrolledCoursesData as $purchase) {
    //         $completedLessonsCount = ProgressTracking::where('student_id', $user->id)
    //             ->where('course_id', $purchase->course_id)
    //             ->distinct('lesson_id')
    //             ->count();
    //         $totalLessonsCount = $purchase->course->lessons->count();
    //         $completionPercentage = ($totalLessonsCount > 0) ? round(($completedLessonsCount / $totalLessonsCount) * 100, 2) : 0;

    //         $courseCompletionData[] = [
    //             'course_title' => $purchase->course->title,
    //             'completion_percentage' => $completionPercentage,
    //         ];
    //     }

    //     // Static data if no real data
    //     if (empty($courseCompletionData)) {
    //         $completionRate = 75;
    //         $hoursWatched = 120;
    //         $courseCompletionData = [
    //             ['course_title' => 'Static Course 1', 'completion_percentage' => 80],
    //             ['course_title' => 'Static Course 2', 'completion_percentage' => 60],
    //         ];
    //     }

    //     return view('student.analytics', compact('completionRate', 'hoursWatched', 'courseCompletionData'));
    // }

    public function analytics()
    {
        $user = Auth::user();
        $enrolledCoursesData = CoursePurchase::where('student_id', $user->id)->with('course')->get();
        $enrolledCourseIds = $enrolledCoursesData->pluck('course_id');

        $lessonIds = Lesson::whereIn('course_id', $enrolledCourseIds)->pluck('id');

        $totalCourses = $enrolledCoursesData->count();
        $completedCoursesCount = 0;
        $ongoingCoursesCount = 0;
        $totalWatchTime = 0;

        $courses = [];
        foreach ($enrolledCoursesData as $purchase) {
            $course = $purchase->course;
            $completedLessonsCount = ProgressTracking::where('student_id', $user->id)
                ->where('course_id', $course->id)
                ->distinct('lesson_id')
                ->count();
            $totalLessonsCount = $course->lessons->count();
            $progress = ($totalLessonsCount > 0) ? round(($completedLessonsCount / $totalLessonsCount), 2) : 0;

            $courseWatchTime = VideoStat::whereIn('lesson_id', $lessonIds)
            ->whereHas('lesson', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->sum('average_watch_time');
            $hoursWatched = round($courseWatchTime / 3600, 1);

            if ($progress >= 1) {
                $completedCoursesCount++;
            } elseif ($progress > 0) {
                $ongoingCoursesCount++;
            }
            $totalWatchTime += $hoursWatched;

            $courses[] = [
                'title' => $course->title,
                'subject' => $course->subject,
                'progress' => $progress,
                'hours' => $hoursWatched,
            ];
        }

        $watchTimeLabels = [];
        $watchTimeData = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $watchTimeLabels[] = $date->format('M d');
            $dailyWatchTime = VideoStat::whereIn('lesson_id', $lessonIds)
            ->whereDate('created_at', $date)
            ->sum('average_watch_time');
            $watchTimeData[] = round($dailyWatchTime / 60, 1);
        }

        // Static data if no real data
        if (empty($courses)) {
            $totalCourses = 4;
            $completedCoursesCount = 1;
            $ongoingCoursesCount = 2;
            $totalWatchTime = 16.5;
            $courses = [
                ['title' => "Calculus I", 'subject' => "Math", 'progress' => 0.85, 'hours' => 7],
                ['title' => "Physics Mechanics", 'subject' => "Physics", 'progress' => 0.4, 'hours' => 3],
                ['title' => "Essay Writing", 'subject' => "English", 'progress' => 1.0, 'hours' => 5],
                ['title' => "Organic Chemistry", 'subject' => "Chemistry", 'progress' => 0.2, 'hours' => 1.5],
            ];
            $watchTimeLabels = [];
            $watchTimeData = [];
            for ($i = 13; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $watchTimeLabels[] = $date->format('M d');
                $watchTimeData[] = rand(10, 50);
            }
        }

        return view('student.analytics', [
            'user' => $user,
            'totalCourses' => $totalCourses,
            'completedCoursesCount' => $completedCoursesCount,
            'ongoingCoursesCount' => $ongoingCoursesCount,
            'totalWatchTime' => $totalWatchTime,
            'courses' => $courses,
            'watchTimeLabels' => $watchTimeLabels,
            'watchTimeData' => $watchTimeData,
        ]);
    }
    public function certificates()
    {
        $user = Auth::user();
        // Fetch certificates from the database
        // For demonstration, using static data if no real data is found
        $certificates = []; // Replace with actual database query for certificates

        if (empty($certificates)) {
            $certificates = [
                ['title' => 'Static Web Development Certificate', 'issue_date' => 'October 26, 2024', 'url' => '#'],
                ['title' => 'Static Database Management Certificate', 'issue_date' => 'September 15, 2024', 'url' => '#'],
            ];
        }

        return view('student.certificates', compact('certificates'));
    }

    public function payments()
    {
        $user = Auth::user();
        $payments = Payment::where('student_id', $user->id)
            ->with('course')
            ->latest()
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

        // Static data if no real data
        if (empty($paymentData)) {
            $paymentData = [
                ['date' => '2024-11-01', 'course' => 'Static Course 1', 'method' => 'Credit Card', 'amount' => 49.99],
                ['date' => '2024-10-15', 'course' => 'Static Course 2', 'method' => 'PayPal', 'amount' => 29.99],
            ];
        }

        return view('student.payments', compact('paymentData'));
    }

    public function wishlist()
    {
        $user = Auth::user();
        // dummy-data
        $wishlistCourses = [];

        if (empty($wishlistCourses)) {
            $wishlistCourses = [
                ['id' => 3, 'title' => 'Static Course 3', 'subject' => 'Design', 'price' => 19.99, 'thumb' => 'https://fastly.picsum.photos/id/7/367/267.jpg?hmac=7scfIEZwG08cgYCiNifF6mEOaFpXAt2N-Q7oaA37ZQk'],
                ['id' => 4, 'title' => 'Static Course 4', 'subject' => 'Marketing', 'price' => 39.99, 'thumb' => 'https://fastly.picsum.photos/id/7/367/267.jpg?hmac=7scfIEZwG08cgYCiNifF6mEOaFpXAt2N-Q7oaA37ZQk'],
            ];
        }

        return view('student.wishlist', compact('wishlistCourses'));
    }

    public function courseDetails($course_id)
    {
       $data['course_name'] = Course::where('id', $course_id)->first();
       $data['course_chapters'] = CourseSection::where('course_id', $course_id)->get();
       $data['course_lessons'] = Lesson::where('course_id', $course_id)->get();

        return view('student.course_details', $data);
    }
}
