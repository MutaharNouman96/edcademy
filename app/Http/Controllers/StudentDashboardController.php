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
use App\Models\User;
use App\Models\LessonVideoComment;
use App\Models\LessonVideoView;
use App\Models\UserPurchasedItem;
use App\Models\Wishlist;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledCourses = CoursePurchase::where('student_id', $user->id)->count();

        $watchedTime = LessonVideoView::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->sum('watch_time');

        $totalCourses = CoursePurchase::where('student_id', $user->id)->count();
        $completedCourses = ProgressTracking::where('student_id', $user->id)
            ->distinct('course_id')
            ->count();

        $completionRate = ($totalCourses > 0) ? round(($completedCourses / $totalCourses) * 100, 2) : 0;

        $totalSpent = CoursePurchase::where('student_id', $user->id)
            ->join('courses', 'course_purchases.course_id', '=', 'courses.id')
            ->sum('courses.price');

        $courseCompletionData = [];
        $enrolledCoursesData = CoursePurchase::where('student_id', $user->id)->with('course.lessons')->get();

        foreach ($enrolledCoursesData as $purchase) {
            $course = $purchase->course;
            $totalLessonsCount = $course->lessons->count();
            $totalLessonCompletionSum = 0;

            if ($totalLessonsCount > 0) {
                foreach ($course->lessons as $lesson) {
                    $lessonCompletion = LessonVideoView::where('user_id', $user->id)
                        ->where('lesson_id', $lesson->id)
                        ->value('completed');

                    // $totalLessonCompletionSum += $lessonCompletion ?? 0;
                }

                // $completionPercentage = round($totalLessonCompletionSum / $totalLessonsCount, 2);
            } else {
                $completionPercentage = 0;
            }

            $courseCompletionData[] = [
                'course_title' => $purchase->course->title,
                'completion_percentage' => $lessonCompletion,
            ];
        }

        // Debugging: Dump course completion data
        // dd($courseCompletionData);

        $watchTimeLabels = [];
        $watchTimeData = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $watchTimeLabels[] = $date->format('M d');
            $dailyWatchTimeQuery = LessonVideoView::where('user_id', $user->id)
                ->whereDate('created_at', $date);

            $dailyWatchTime = $dailyWatchTimeQuery->sum('watch_time');
            $watchTimeData[] = round($dailyWatchTime / 60, 1);
        }

        // Debugging: Check the data before passing to the view

        $myCourses = [];
        foreach ($enrolledCoursesData as $purchase) {
            $course = $purchase->course;
            $completedLessonsCount = ProgressTracking::where('student_id', $user->id)
                ->where('course_id', $course->id)
                ->distinct('lesson_id')
                ->count();
            $totalLessonsCount = $course->lessons->count();
            $progress = ($totalLessonsCount > 0) ? round(($completedLessonsCount / $totalLessonsCount), 2) : 0;

            $lastViewedLesson = LessonVideoView::where('user_id', $user->id)
                ->latest()
                ->first();

            $lastViewed = $lastViewedLesson ? Carbon::parse($lastViewedLesson->created_at)->diffForHumans() : 'Never';

            $totalWatchTime = LessonVideoView::where('user_id', $user->id)
                ->sum('watch_time');
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
        // dd($enrolledCourseIds);
        $recentLessons = Lesson::whereIn('course_id', $enrolledCourseIds)
            ->with('course')
            ->latest()
            ->take(5)
            ->get();

        // dd($recentLessons);

        foreach ($recentLessons as $lesson) {
            $newVideosFeed[] = [
                'course' => $lesson->course->title,
                'lesson' => $lesson->title,
                'when' => Carbon::parse($lesson->created_at)->diffForHumans(),
                'duration' => $lesson->duration,
                'id' => $lesson->id,
                'course_id' => $lesson->course->id,
            ];
        }


        $user = Auth::user();
        $purchasedItems = UserPurchasedItem::where('user_id', $user->id)
            ->with('purchasable')
            ->latest()
            ->take(5)->get();

        $paymentData = [];
        foreach ($purchasedItems as $item) {
            $purchasable = $item->purchasable;
            $title = 'N/A';
            if ($purchasable instanceof \App\Models\Course) {
                $title = $purchasable->title;
            } elseif ($purchasable instanceof \App\Models\Lesson) {
                $title = $purchasable->title;
            }

            $paymentData[] = [
                'date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                'item_title' => $title,
                'type' => class_basename($purchasable),
                'amount' => 'N/A',
            ];
        }

        return view('student.dashboard', compact('enrolledCourses', 'watchedTime', 'completionRate', 'totalSpent', 'courseCompletionData', 'watchTimeLabels', 'watchTimeData', 'myCourses', 'newVideosFeed', 'paymentData'));
    }

    // public function myCourses()
    // {
    //     $user = Auth::user();
    //     $enrolledCoursesData = CoursePurchase::where('student_id', $user->id)->with('course')->get();

    //     $myCourses = [];
    //     foreach ($enrolledCoursesData as $purchase) {
    //         $course = $purchase->course;
    //         $completedLessonsCount = ProgressTracking::where('student_id', $user->id)
    //             ->where('course_id', $course->id)
    //             ->distinct('lesson_id')
    //             ->count();
    //         $totalLessonsCount = $course->lessons->count();
    //         $progress = ($totalLessonsCount > 0) ? round(($completedLessonsCount / $totalLessonsCount), 2) : 0;

    //         $lastViewedLesson = VideoStat::whereHas('lesson.course.coursePurchases', function ($query) use ($user) {
    //             $query->where('student_id', $user->id);
    //         })
    //         ->latest()
    //         ->first();

    //         $lastViewed = $lastViewedLesson ? Carbon::parse($lastViewedLesson->created_at)->diffForHumans() : 'Never';

    //         $totalWatchTime = VideoStat::whereHas('lesson.course.coursePurchases', function ($query) use ($user) {
    //             $query->where('student_id', $user->id);
    //         })
    //         ->sum('average_watch_time');
    //         $hoursWatched = round($totalWatchTime / 3600, 1);

    //         $newVideosCount = Lesson::where('course_id', $course->id)
    //             ->where('created_at', '>', Carbon::now()->subDays(7))
    //             ->count();

    //         $myCourses[] = [
    //             'id' => $course->id,
    //             'title' => $course->title,
    //             'subject' => $course->subject,
    //             'progress' => $progress,
    //             'hours' => $hoursWatched,
    //             'last' => $lastViewed,
    //             'thumb' => $course->thumbnail,
    //             'newVideos' => $newVideosCount,
    //         ];
    //     }

    //     return view('student.my_courses', compact('myCourses'));
    // }


    public function myCourses()
    {
        $user = Auth::user();
        $purchases = $user->purchases()
            ->where('active', true)
            ->with('purchasable')
            ->get();
        $courses = $purchases
            ->where('purchasable_type', Course::class)
            ->pluck('purchasable')
            ->filter(); // Filter out any null courses

        $lessons = $purchases
            ->where('purchasable_type', Lesson::class)
            ->pluck('purchasable');

        $videoLessons = $lessons->where('type', 'video');
        $worksheetLessons = $lessons->where('type', 'worksheet');

        return view('student.my_courses', [
            'courses' => $courses,
            'videoLessons' => $videoLessons,
            'worksheetLessons' => $worksheetLessons,
            'lessons' => $lessons,
            'stats' => [
                'courses' => $courses->count(),
                'videos' => $videoLessons->count(),
                'worksheets' => $worksheetLessons->count(),
            ]
        ]);
    }

    public function newVideos()
    {
        $user = Auth::user();
        $enrolledCourseIds = CoursePurchase::where('student_id', $user->id)->pluck('course_id');
        $recentLessons = Lesson::whereIn('course_id', $enrolledCourseIds)
            ->with('course')
            ->latest()
            ->take(5)
            ->get();

        $newVideosFeed = [];
        foreach ($recentLessons as $lesson) {
            $newVideosFeed[] = [
                'course' => $lesson->course->title,
                'lesson' => $lesson->title,
                'when' => Carbon::parse($lesson->created_at)->diffForHumans(),
                'duration' => $lesson->duration,
                'id' => $lesson->id,
                'course_id' => $lesson->course->id,
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

            $courseWatchTime = LessonVideoView::where('user_id', $user->id)
                ->whereHas('lesson', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->sum('watch_time');
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
            $dailyWatchTime = LessonVideoView::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->sum('watch_time');
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
        $purchasedItems = UserPurchasedItem::where('user_id', $user->id)
            ->with('purchasable')
            ->latest()
            ->get();

        $paymentData = [];
        foreach ($purchasedItems as $item) {
            $purchasable = $item->purchasable;
            $title = 'N/A';
            if ($purchasable instanceof \App\Models\Course) {
                $title = $purchasable->title;
            } elseif ($purchasable instanceof \App\Models\Lesson) {
                $title = $purchasable->title;
            }

            $paymentData[] = [
                'date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                'item_title' => $title,
                'type' => class_basename($purchasable),
                'amount' => 'N/A',
            ];
        }
        return view('student.payments', compact('paymentData'));
    }

    public function wishlist()
    {
        $user = Auth::user();

        $wishlistCourses = Wishlist::where('user_id', $user->id)
            ->with('course')
            ->get()
            ->map(function ($wishlistItem) {
                $course = $wishlistItem->course;
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'subject' => $course->subject,
                    'price' => $course->price,
                    'thumb' => $course->thumbnail,
                ];
            });

        return view('student.wishlist', compact('wishlistCourses'));
    }

    public function removeWishlistCourse($course_id)
    {
        $user = Auth::user();

        Wishlist::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->delete();

        return redirect()->route('student.wishlist')->with('success', 'Course removed from wishlist.');
    }

    public function courseDetails($course_id, $lesson_id = null)
    {
         $user = auth()->user();
        $data['course'] = Course::where('id', $course_id)->firstOrFail();
        $data['educator'] = User::where('id', $data['course']->user_id)->first();
        $data['course_chapters'] = CourseSection::where('course_id', $course_id)->get();
        $data['course_lessons'] = Lesson::where('course_id', $course_id)->get();

        if ($lesson_id) {
            $data['currentLesson'] = Lesson::where('id', $lesson_id)->where('course_id', $course_id)->firstOrFail();
        } else {
            $data['currentLesson'] = Lesson::where('course_id', $course_id)->orderBy('id')->firstOrFail();
        }

        // Assign lesson_number for the current lesson
        $data['currentLesson']->lesson_number = $data['course_lessons']->where('course_section_id', $data['currentLesson']->course_section_id)->sortBy('id')->search($data['currentLesson']) + 1;

        $lesson = $data['currentLesson'];

        $hasAccess = UserPurchasedItem::where('user_id', $user->id)
            ->where('active', true)
            ->where(function ($q) use ($lesson) {
                $q->where(function ($q) use ($lesson) {
                    $q->where('purchasable_type', Lesson::class)
                        ->where('purchasable_id', $lesson->id);
                })
                    ->orWhere(function ($q) use ($lesson) {
                        $q->where('purchasable_type', Course::class)
                            ->where('purchasable_id', $lesson->course_id);
                    });
            })
            ->exists();

        abort_if(! $hasAccess, 403, 'You do not have access to this lesson. Please purchase the course or the lesson first.');
        $data['comments'] = LessonVideoComment::where('lesson_id', $data['currentLesson']->id)->with('user')->latest()->get();

        return view('student.course_details', $data);
    }

    public function storeLessonComment(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'comment' => 'required|string|max:255',
        ]);

        $comment = LessonVideoComment::create([
            'user_id' => Auth::id(),
            'lesson_id' => $request->lesson_id,
            'comment' => $request->comment,
        ]);

        $comment->load('user'); // Eager load the user relationship for the newly created comment

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user_name' => $comment->user->first_name . ' ' . $comment->user->last_name,
                'user_profile_picture' => $comment->user->profile_picture ?? 'https://placehold.co/40x40/E55A2B/white?text=U',
                'comment_text' => $comment->comment,
                'created_at_human' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }


    public function messages()
    {
        return view('student.messages');
    }




    public function lessonDetails(Course $course, Lesson $lesson)
    {
        $user = auth()->user();

        // Access check: lesson OR parent course purchased
        $hasAccess = UserPurchasedItem::where('user_id', $user->id)
            ->where('active', true)
            ->where(function ($q) use ($lesson) {
                $q->where(function ($q) use ($lesson) {
                    $q->where('purchasable_type', Lesson::class)
                        ->where('purchasable_id', $lesson->id);
                })
                    ->orWhere(function ($q) use ($lesson) {
                        $q->where('purchasable_type', Course::class)
                            ->where('purchasable_id', $lesson->course_id);
                    });
            })
            ->exists();

        abort_if(! $hasAccess, 403, 'You do not have access to this lesson. Please purchase the course or the lesson first.');

        return view('student.lesson_details', compact('lesson'));
    }
}
