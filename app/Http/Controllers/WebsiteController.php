<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseReview;
use App\Models\EducatorReview;
use App\Models\Order;
use App\Models\StudentTestimonial;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\EmailService;
use App\Services\BookingService;
use App\Services\ActivityNotificationService;
use App\Mail\SessionBookedMail;
use App\Models\Policy;
use App\Models\EducatorProfile;
use App\Models\EducatorSessionSchedule;
use App\Models\Lesson;
use Carbon\Carbon;

class WebsiteController extends Controller
{
    //

    public function index()
    {
        // Eager-load only admin-verified (active) lessons so listing counts/previews stay accurate.
        $trendingCourses = Course::active()->published()->orderByDesc("publish_date")->with(["educator.educatorProfile", "category", "reviews", "lessons" => fn ($query) => $query->active()])->limit(6)->get();

        $bestReviewedCourses = Course::active()->published()->BestReviewed()->limit(3)->get();

        $studentTestimonials = StudentTestimonial::where("shown", true)->inRandomOrder()->limit(4)->get();

        $featuredEducators =
            User::verifiedEducator()->with("educatorProfile")->limit(4)->get();

        $latestCourses = Course::active()->published()->orderByDesc("publish_date")->with(["educator.educatorProfile", "category", "reviews", "lessons" => fn ($query) => $query->active()])->limit(4)->get();

        $totalStudents = User::where("role", "student")->where("email_verified_at", "!=", null)->count();
        $availableCourses = Course::active()->published()->count();
        $totalEducators = User::verifiedEducator()->count();
        $averageRating = CourseReview::average("rating");

        $subjects = Subject::where("active", true)->get();





        return view("website.index", compact("trendingCourses", "bestReviewedCourses", "studentTestimonials", "featuredEducators", "latestCourses", "totalStudents", "availableCourses", "totalEducators", "averageRating", "subjects"));
    }

    public function educator_signup()
    {
        if (Auth::check() && Auth::user()->role == 'educator') {
            return redirect()->route("educator.dashboard");
        }
        return view("website.educator-signup");
    }

    public function courses()
    {
        // Eager-load only admin-verified (active) lessons so listing counts/previews stay accurate.
        $courses = Course::active()->published()->orderByDesc("publish_date")->with(["educator.educatorProfile", "category", "reviews", "lessons" => fn ($query) => $query->active()])->paginate(12);

        $courseCategories = CourseCategory::all();
        $chunks = $courseCategories->chunk(5);
        $firstFiveCategories = $chunks->first();          // first 5 categories
        $remainingCategories = $chunks->skip(1)->flatten(); // all remaining categories


        return view("website.courses", compact("courses", "courseCategories", "firstFiveCategories", "remainingCategories"));
    }

    public function course(Course $course)
    {
        // Only surface admin-verified (active) lessons.
        $course = $course->load(["educator", "category", "reviews", "lessons" => fn ($query) => $query->active()]);

        return view("website.course", compact("course"));
    }

    public function educators(Request $request)
    {
        $query = User::verifiedEducator()->with("EducatorProfile", "educatorReviews");

        // Search by Name or Keyword
        if ($request->has("search") && $request->search != "") {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("name", "like", "%" . $search . "%")
                    ->orWhereHas("educatorProfile", function ($q2) use ($search) {
                        $q2->where("bio", "like", "%" . $search . "%")
                           ->orWhere("skills", "like", "%" . $search . "%");
                    });
            });
        }

        // Primary Subject filter
        if ($request->has("subject") && $request->subject != "") {
            Log::info('Subject filter applied: ' . $request->subject);
            $query->whereHas("educatorProfile", function ($q) use ($request) {
                $q->where("primary_subject", $request->subject);
            });
        }

        // Teaching Levels filter (assuming 'teaching_levels' is a comma-separated string or JSON array in educatorProfile)
        if ($request->has("levels") && is_array($request->levels) && count($request->levels) > 0) {
            $query->whereHas("educatorProfile", function ($q) use ($request) {
                foreach ($request->levels as $level) {
                    $q->orWhere("teaching_levels", "like", "%{$level}%");
                }
            });
        }

        // Teaching Style filter
        if ($request->has("styles") && is_array($request->styles) && count($request->styles) > 0) {
            $query->whereHas("educatorProfile", function ($q) use ($request) {
                foreach ($request->styles as $style) {
                    $q->orWhere("teaching_style", "like", "%{$style}%");
                }
            });
        }

        // Maximum Hourly Rate filter
        if ($request->has("max_rate") && is_numeric($request->max_rate)) {
            $query->whereHas("educatorProfile", function ($q) use ($request) {
                $q->where("hourly_rate", "<=", $request->max_rate);
            });
        }

        // Additional Filters
        if ($request->has("additional_filters") && is_array($request->additional_filters)) {
            foreach ($request->additional_filters as $filter) {
                if ($filter == "certified") {
                    $query->whereHas("educatorProfile", function ($q) {
                        $q->where("is_certified", true);
                    });
                }
                if ($filter == "top_rated") {
                    $query->withAvg("educatorReviews", "rating")->having("educator_reviews_avg_rating", ">=", 4.5);
                }
            }
        }


        if ($request->has("sort_by")) {
            switch ($request->sort_by) {
                case "highest_rated":
                    $query->withAvg("educatorReviews", "rating")->orderByDesc("educator_reviews_avg_rating");
                    break;
                case "lowest_price":
                    $query->whereHas("educatorProfile")->orderBy("hourly_rate");
                    break;
                case "highest_price":
                    $query->whereHas("educatorProfile")->orderByDesc("hourly_rate");
                    break;
                case "most_students":
                    $query->leftJoin('courses', 'users.id', '=', 'courses.user_id')
                          ->leftJoin('course_purchases', 'courses.id', '=', 'course_purchases.course_id')
                          ->selectRaw('users.*, COUNT(DISTINCT course_purchases.student_id) as students_count')
                          ->groupBy('users.id')
                          ->orderByDesc('students_count');
                    break;
                case "most_experience":
                    $query->whereHas("educatorProfile")->orderByDesc("years_experience");
                    break;
                default:
                    break;
            }
        }

        $educators = $query->paginate(10);



        // dd($educators);

        return view("website.educators", compact("educators")); //asim
    }
    public function educator($id)
    {
        $educator = User::verifiedEducator()
            ->with([
                'educatorProfile',
                'sessionSchedules' => fn ($query) => $query->orderBy('day_of_week')->orderBy('start_time'),
            ])
            ->findOrFail($id);

        $educator_profile = $educator->educatorProfile;

        $educator_reviews = EducatorReview::with('student')
            ->where('educator_id', $id)
            ->latest()
            ->get();

        $averageRating = $educator_reviews->avg('rating') ?? 0;
        $educatorAverageRating = number_format($averageRating, 2);

        $studentCount = DB::table('course_purchases')
            ->join('courses', 'courses.id', '=', 'course_purchases.course_id')
            ->where('courses.user_id', $id)
            ->distinct()
            ->count('course_purchases.student_id');

        $courses = Course::query()
            ->where('user_id', $id)
            ->active()
            ->published()
            ->with(['category', 'reviews'])
            ->withCount([
                'lessons as published_lessons_count' => fn ($query) => $query->where('status', 'Published')->active(),
            ])
            ->withSum(
                ['lessons as total_lesson_duration' => fn ($query) => $query->where('status', 'Published')->active()],
                'duration'
            )
            ->orderByDesc('publish_date')
            ->get();

        $enrollmentCounts = DB::table('course_purchases')
            ->whereIn('course_id', $courses->pluck('id'))
            ->selectRaw('course_id, COUNT(DISTINCT student_id) as enrolled_students')
            ->groupBy('course_id')
            ->pluck('enrolled_students', 'course_id');

        $courses->each(function (Course $course) use ($enrollmentCounts) {
            $course->enrolled_students = (int) ($enrollmentCounts[$course->id] ?? 0);
        });

        $lessonQuery = fn ($query) => $query
            ->where('user_id', $id)
            ->active()
            ->published();

        $recent_videos = Lesson::query()
            ->active()
            ->whereHas('course', $lessonQuery)
            ->with(['course', 'courseSection'])
            ->latest('published_at')
            ->latest('id')
            ->limit(6)
            ->get();

        $popular_videos = Lesson::query()
            ->active()
            ->where(function ($query) {
                $query->where('popular', 1)->orWhere('popular', '1');
            })
            ->whereHas('course', $lessonQuery)
            ->with(['course', 'courseSection'])
            ->latest('published_at')
            ->limit(6)
            ->get();

        $totalLessons = Lesson::query()
            ->active()
            ->whereHas('course', $lessonQuery)
            ->count();

        $totalReviews = $educator_reviews->count();
        $starPercentages = [];

        for ($i = 1; $i <= 5; $i++) {
            $starCount = $educator_reviews->where('rating', $i)->count();
            $starPercentages[$i] = ($totalReviews > 0) ? round(($starCount / $totalReviews) * 100) : 0;
        }

        $teachingStyles = $this->parseTeachingStyles($educator_profile?->preferred_teaching_style);
        $teachingLevels = $this->decodeTeachingLevels($educator_profile?->teaching_levels);
        $primarySubjects = $this->parsePrimarySubjects($educator_profile?->primary_subject);
        $educatorBio = filled($educator->bio) ? $educator->bio : ($educator_profile?->bio ?? '');
        $introVideoUrl = EducatorProfile::resolveFileUrl($educator_profile?->intro_video_path);

        $bookingSubjects = collect($primarySubjects)
            ->merge($courses->pluck('subject'))
            ->filter()
            ->unique()
            ->values();

        $availabilitySummary = $this->formatAvailabilitySummary($educator->sessionSchedules);

        return view('website.educator', compact(
            'educator',
            'educator_profile',
            'educatorAverageRating',
            'educator_reviews',
            'studentCount',
            'courses',
            'recent_videos',
            'popular_videos',
            'starPercentages',
            'teachingStyles',
            'teachingLevels',
            'primarySubjects',
            'educatorBio',
            'introVideoUrl',
            'bookingSubjects',
            'availabilitySummary',
            'totalLessons',
        ));
    }

    private function parseTeachingStyles(?string $raw): array
    {
        if (blank($raw)) {
            return [];
        }

        $parts = preg_split('/\s*\/\s*|,\s*/', $raw);

        return array_values(array_filter(array_map('trim', $parts)));
    }

    private function parsePrimarySubjects(?string $raw): array
    {
        if (blank($raw)) {
            return [];
        }

        $parts = preg_split('/\s*,\s*/', $raw);

        return array_values(array_filter(array_map('trim', $parts)));
    }

    private function decodeTeachingLevels(mixed $raw): array
    {
        if (blank($raw)) {
            return [];
        }

        if (is_array($raw)) {
            return array_values(array_filter($raw));
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? array_values(array_filter($decoded)) : [];
    }

    private function formatAvailabilitySummary($schedules): string
    {
        if ($schedules->isEmpty()) {
            return 'Availability not set yet';
        }

        $dayNames = EducatorSessionSchedule::DAYS;
        $grouped = $schedules->groupBy('day_of_week');

        $segments = $grouped->map(function ($daySlots, $day) use ($dayNames) {
            $label = $dayNames[$day] ?? 'Day ' . $day;
            $times = $daySlots->map(function ($slot) {
                $start = Carbon::parse($slot->start_time)->format('g:ia');
                $end = Carbon::parse($slot->end_time)->format('g:ia');

                return "{$start}–{$end}";
            })->implode(', ');

            return "{$label}: {$times}";
        })->values();

        if ($segments->count() <= 2) {
            return $segments->implode(' · ');
        }

        $days = $grouped->keys()->sort()->map(fn ($day) => substr($dayNames[$day] ?? '', 0, 3))->implode('–');

        return 'Available ' . $days;
    }

    public function cart()
    {
        $myCart = Order::where('user_id', get_cart_identifier())->where('status', 'cart')->with('items')->first();

        return view("website.cart" , compact("myCart"));
    }


    public function educator_policy(){
        return view("website.educator-policy");
    }
    public function student_parent_policy(){
        return view("website.student-parent-policy");
    }
    public function refund_policy(){
        return view("website.refund-policy");
    }

    public function faqs(){
        return view("website.faq");
    }

    public function how_it_works(){
        return view("website.how_it_works");
    }

    public function reviews()
    {
        $courseReviews = CourseReview::with(['course', 'student'])
            ->latest()
            ->limit(30)
            ->get();

        $educatorReviews = EducatorReview::with(['educator', 'student'])
            ->latest()
            ->limit(30)
            ->get();

        $courseReviewsAvg = CourseReview::average('rating');
        $educatorReviewsAvg = EducatorReview::average('rating');

        return view('website.reviews', compact(
            'courseReviews',
            'educatorReviews',
            'courseReviewsAvg',
            'educatorReviewsAvg',
        ));
    }


    public function policy($slug){
        $policy = Policy::where('slug', $slug)->first();
        return view('website.policy', compact('policy'));
    }

    // -------------------------------------------------------------------------
    // Session booking: available slots (API). Slot computation lives in
    // App\Services\BookingService so it is shared with the booking controller.
    // -------------------------------------------------------------------------

    /**
     * API: GET /api/educator/{id}/available-slots?date=Y-m-d
     */
    public function getAvailableSlotsApi(Request $request, $educatorId, BookingService $bookings)
    {
        $request->validate(['date' => 'required|date|after_or_equal:today']);
        $educator = User::where('id', $educatorId)->where('role', 'educator')->first();
        if (!$educator) {
            return response()->json(['success' => false, 'message' => 'Educator not found.'], 404);
        }
        $slots = $bookings->getAvailableSlots((int) $educatorId, $request->date);
        return response()->json(['success' => true, 'slots' => $slots]);
    }

    /**
     * API: GET /api/educator/{id}/available-dates?month=Y-m
     * Returns dates in that month that have at least one available slot (for calendar UI).
     */
    public function getAvailableDatesApi(Request $request, $educatorId, BookingService $bookings)
    {
        $request->validate(['month' => 'required|date_format:Y-m']);
        $educator = User::where('id', $educatorId)->where('role', 'educator')->first();
        if (!$educator) {
            return response()->json(['success' => false, 'message' => 'Educator not found.'], 404);
        }
        $dates = $bookings->getAvailableDates((int) $educatorId, $request->month);
        return response()->json(['success' => true, 'dates' => $dates]);
    }
}
