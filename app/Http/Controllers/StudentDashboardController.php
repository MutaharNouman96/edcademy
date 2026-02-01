<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\CoursePurchase;
use App\Models\ProgressTracking;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\User;
use App\Models\LessonVideoComment;
use App\Models\LessonVideoView;
use App\Models\UserPurchasedItem;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Support both purchase systems:
        // 1) `course_purchases` table (CoursePurchase model)
        // 2) polymorphic `user_purchased_items` table (UserPurchasedItem model)
        $courseIdsFromCoursePurchases = CoursePurchase::query()
            ->where('student_id', $user->id)
            ->where('is_active', true)
            ->pluck('course_id');

        $courseIdsFromUserPurchasedItems = UserPurchasedItem::query()
            ->where('user_id', $user->id)
            ->where('active', true)
            ->where('purchasable_type', Course::class)
            ->pluck('purchasable_id');

        $courseIds = $courseIdsFromCoursePurchases
            ->merge($courseIdsFromUserPurchasedItems)
            ->unique()
            ->values();

        $enrolledCourses = $courseIds->count();

        // Watch time (last 30 days) in seconds
        $watchedTime = LessonVideoView::query()
            ->where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->sum('watch_time');

        $courses = Course::query()
            ->whereIn('id', $courseIds)
            ->withCount('lessons')
            ->get();

        $totalSpent = (float) $courses->sum('price');

        // Aggregate per-course totals
        $lessonCountsByCourse = Lesson::query()
            ->whereIn('course_id', $courseIds)
            ->selectRaw('course_id, COUNT(*) as total_lessons')
            ->groupBy('course_id')
            ->pluck('total_lessons', 'course_id');

        $completedLessonsByCourse = ProgressTracking::query()
            ->where('student_id', $user->id)
            ->whereIn('course_id', $courseIds)
            ->selectRaw('course_id, COUNT(DISTINCT lesson_id) as completed_lessons')
            ->groupBy('course_id')
            ->pluck('completed_lessons', 'course_id');

        $watchTimeSecondsByCourse = LessonVideoView::query()
            ->where('lesson_video_views.user_id', $user->id)
            ->join('lessons', 'lesson_video_views.lesson_id', '=', 'lessons.id')
            ->whereIn('lessons.course_id', $courseIds)
            ->selectRaw('lessons.course_id as course_id, SUM(lesson_video_views.watch_time) as watch_time_seconds')
            ->groupBy('lessons.course_id')
            ->pluck('watch_time_seconds', 'course_id');

        $lastViewedAtByCourse = LessonVideoView::query()
            ->where('lesson_video_views.user_id', $user->id)
            ->join('lessons', 'lesson_video_views.lesson_id', '=', 'lessons.id')
            ->whereIn('lessons.course_id', $courseIds)
            ->selectRaw('lessons.course_id as course_id, MAX(lesson_video_views.created_at) as last_viewed_at')
            ->groupBy('lessons.course_id')
            ->pluck('last_viewed_at', 'course_id');

        $newVideosCountByCourse = Lesson::query()
            ->whereIn('course_id', $courseIds)
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->selectRaw('course_id, COUNT(*) as new_videos')
            ->groupBy('course_id')
            ->pluck('new_videos', 'course_id');

        // Build course cards + chart data
        $myCourses = [];
        $courseCompletionData = [];
        $completionPercentages = [];

        foreach ($courses as $course) {
            $totalLessonsCount = (int) ($lessonCountsByCourse[$course->id] ?? $course->lessons_count ?? 0);
            $completedLessonsCount = (int) ($completedLessonsByCourse[$course->id] ?? 0);

            $progressFraction = $totalLessonsCount > 0
                ? round(min(1, max(0, $completedLessonsCount / $totalLessonsCount)), 2)
                : 0;

            $completionPercentage = $totalLessonsCount > 0
                ? round(($completedLessonsCount / $totalLessonsCount) * 100, 1)
                : 0;

            $completionPercentages[] = $completionPercentage;

            $watchSeconds = (int) ($watchTimeSecondsByCourse[$course->id] ?? 0);
            $hoursWatched = round($watchSeconds / 3600, 1);

            $lastViewedAt = $lastViewedAtByCourse[$course->id] ?? null;
            $lastViewed = $lastViewedAt ? Carbon::parse($lastViewedAt)->diffForHumans() : 'Never';

            $myCourses[] = [
                'id' => $course->id,
                'title' => $course->title,
                'subject' => $course->subject ?? 'General',
                'progress' => $progressFraction, // 0..1 for the progress bar
                'hours' => $hoursWatched,
                'last' => $lastViewed,
                'thumb' => $course->thumbnail_path,
                'newVideos' => (int) ($newVideosCountByCourse[$course->id] ?? 0),
            ];

            $courseCompletionData[] = [
                'course_title' => $course->title,
                'completion_percentage' => $completionPercentage, // 0..100 for the bar chart
            ];
        }

        // KPI: average completion across enrolled courses (0..100)
        $completionRate = count($completionPercentages) > 0
            ? (int) round(array_sum($completionPercentages) / count($completionPercentages))
            : 0;

        // Watch time line (last 14 days, minutes/day)
        $start = Carbon::now()->subDays(13)->startOfDay();
        $watchSecondsByDay = LessonVideoView::query()
            ->where('user_id', $user->id)
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as day, SUM(watch_time) as seconds')
            ->groupBy('day')
            ->pluck('seconds', 'day');

        $watchTimeLabels = [];
        $watchTimeData = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayKey = $date->toDateString();
            $watchTimeLabels[] = $date->format('M d');
            $watchTimeData[] = round(((int) ($watchSecondsByDay[$dayKey] ?? 0)) / 60, 1);
        }

        // New Videos Feed (last 5 lessons across enrolled courses)
        $newVideosFeed = Lesson::query()
            ->whereIn('course_id', $courseIds)
            ->with('course')
            ->latest()
            ->take(5)
            ->get()
            ->map(function (Lesson $lesson) {
                return [
                    'course' => optional($lesson->course)->title ?? 'Course',
                    'lesson' => $lesson->title,
                    'when' => Carbon::parse($lesson->created_at)->diffForHumans(),
                    'duration' => $lesson->duration,
                    'id' => $lesson->id,
                    'course_id' => $lesson->course_id,
                ];
            })
            ->values()
            ->all();

        // Payments table (courses)
        $purchasedItems = UserPurchasedItem::query()
            ->where('user_id', $user->id)
            ->where('purchasable_type', Course::class)
            ->with('purchasable')
            ->latest()
            ->get();

        $paymentData = [];
        foreach ($purchasedItems as $item) {
            $purchasable = $item->purchasable;

            $title = 'N/A';
            $price = null;

            if ($purchasable instanceof Course) {
                $title = $purchasable->title;
                $price = $purchasable->price;
            } elseif ($purchasable instanceof Lesson) {
                $title = $purchasable->title;
            }

            $paymentData[] = [
                'date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                'item_title' => $title,
                'type' => $purchasable ? class_basename($purchasable) : 'N/A',
                'amount' => $price,
            ];
        }

        // Fallback: if the user purchased via `course_purchases`, include those too
        $coursePurchasePayments = CoursePurchase::query()
            ->where('student_id', $user->id)
            ->where('is_active', true)
            ->join('courses', 'course_purchases.course_id', '=', 'courses.id')
            ->orderByDesc('course_purchases.created_at')
            ->limit(10)
            ->get([
                'course_purchases.created_at as purchased_at',
                'courses.title as course_title',
                'courses.price as course_price',
            ]);

        foreach ($coursePurchasePayments as $p) {
            $paymentData[] = [
                'date' => Carbon::parse($p->purchased_at)->format('Y-m-d'),
                'item_title' => $p->course_title,
                'type' => 'Course',
                'amount' => $p->course_price,
            ];
        }

        // keep newest first
        usort($paymentData, fn ($a, $b) => strcmp($b['date'], $a['date']));

        return view('student.dashboard', compact(
            'enrolledCourses',
            'watchedTime',
            'completionRate',
            'totalSpent',
            'courseCompletionData',
            'watchTimeLabels',
            'watchTimeData',
            'myCourses',
            'newVideosFeed',
            'paymentData'
        ));
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
        /** @var \App\Models\User $user */
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
        ->where('purchasable_type', \App\Models\Course::class)
        ->with('purchasable')
        ->latest()
        ->get();

        $paymentData = [];
        foreach ($purchasedItems as $item) {
            $purchasable = $item->purchasable;

            $title = 'N/A';
            $price = null; // or 0 if you prefer

            if ($purchasable instanceof \App\Models\Course) {
                $title = $purchasable->title;
                $price = $purchasable->price;
            } elseif ($purchasable instanceof \App\Models\Lesson) {
                $title = $purchasable->title;
            }

            $paymentData[] = [
                'date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                'item_title' => $title,
                'type' => class_basename($purchasable),
                'amount' => $price,
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
