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
use App\Services\ActivityNotificationService;
use App\Mail\SessionBookedMail;
use App\Models\Policy;
use App\Models\EducatorProfile;
use App\Models\EducatorSessionSchedule;
use Carbon\Carbon;

class WebsiteController extends Controller
{
    //

    public function index()
    {
        $trendingCourses = Course::active()->published()->orderByDesc("publish_date")->with("educator", "category", "reviews", "lessons")->limit(6)->get();

        $bestReviewedCourses = Course::active()->published()->BestReviewed()->limit(3)->get();

        $studentTestimonials = StudentTestimonial::where("shown", true)->inRandomOrder()->limit(4)->get();

        $featuredEducators =
            User::verifiedEducator()->with("educatorProfile")->limit(4)->get();

        $latestCourses = Course::active()->published()->orderByDesc("publish_date")->with("educator", "category", "reviews", "lessons")->limit(4)->get();

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
        $courses = Course::active()->published()->orderByDesc("publish_date")->with("educator", "category", "reviews", "lessons")->paginate(12);

        $courseCategories = CourseCategory::all();
        $chunks = $courseCategories->chunk(5);
        $firstFiveCategories = $chunks->first();          // first 5 categories
        $remainingCategories = $chunks->skip(1)->flatten(); // all remaining categories


        return view("website.courses", compact("courses", "courseCategories", "firstFiveCategories", "remainingCategories"));
    }

    public function course(Course $course)
    {
        $course = $course->load("educator", "category", "reviews", "lessons");

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
        $educator = User::where("role", "educator")->where("id", $id)->firstOrFail();
        $educator_profile = DB::table('educator_profiles')->where('user_id', $id)->first();
        $educator_reviews = DB::table('educator_reviews')->where('educator_id', $id)->get();
        $averageRating = DB::table('educator_reviews')->where('educator_id', $id)->avg('rating');


        $educatorAverageRating = number_format($averageRating, 2);
        $studentCount = DB::table('course_purchases')
        ->join('courses', 'courses.id', 'course_purchases.course_id')
        ->where('courses.user_id', $id)
        ->distinct('course_purchases.student_id')
        ->count();

        $recent_videos = DB::table('lessons')
        ->join('courses', 'courses.id', 'lessons.course_id')
        ->where('courses.user_id', $id)
        ->select('courses.*', 'lessons.*', 'courses.title as course_title', 'lessons.title as lesson_title')
        ->skip(0)->take(6)->get();

        $popular_videos = DB::table('lessons')
        ->join('courses', 'courses.id', 'lessons.course_id')
        ->select('courses.*', 'lessons.*', 'courses.title as course_title', 'lessons.title as lesson_title')
        ->where('courses.user_id', $id)
        ->where('lessons.popular', 1)
        ->skip(0)->take(6)->get();

        $courses = DB::table('courses')->where('user_id', $id)->get();

        $totalReviews = $educator_reviews->count();
        $starPercentages = [];

        for ($i = 1; $i <= 5; $i++) {
            $starCount = $educator_reviews->where('rating', $i)->count();
            $starPercentages[$i] = ($totalReviews > 0) ? round(($starCount / $totalReviews) * 100) : 0;
        }

        // dd("educator", $educator, '</br>' , "educator_profile", $educator_profile, '</br>' , "avgRating", $educatorAverageRating, '</br>' , "review_count", $educator_reviews_count, '</br>', "student_count", $studentCount);
        return view("website.educator", compact("educator", "educator_profile", "educatorAverageRating", "educator_reviews", "studentCount", "courses", "recent_videos", "popular_videos", "starPercentages"));
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

    public function bookSession(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'duration' => 'required|numeric|in:1,1.5,2,3',
            'subject' => 'required|string|max:255',
            'educator_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please log in to book a session.'], 401);
        }

        $educatorId = (int) $request->educator_id;
        $date = $request->date;
        $time = preg_replace('/^(\d{1,2}):(\d{2}).*$/', '${1}:${2}', $request->time);
        if (strlen($time) === 4) {
            $time = '0' . $time;
        }

        if (!$this->isSlotAvailable($educatorId, $date, $time)) {
            return response()->json(['success' => false, 'message' => 'This time slot is no longer available. Please choose another.'], 422);
        }

        $booking = Booking::create([
            'student_id' => Auth::id() ?? 114,
            'educator_id' => $request->educator_id,
            'date' => $request->date,
            'time' => $request->time,
            'duration' => $request->duration,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // Log activity
        $student = $booking->student;
        $educator = $booking->educator;

        if ($student) {
            ActivityNotificationService::logAndNotify(
                $student,
                'book_session',
                'Booking',
                $booking->id,
                "Session with {$educator->full_name}",
                null,
                [
                    'educator_id' => $booking->educator_id,
                    'date' => $booking->date,
                    'time' => $booking->time,
                    'duration' => $booking->duration,
                    'subject' => $booking->subject,
                    'status' => $booking->status
                ],
                "Booked session with {$educator->full_name} for {$booking->date} at {$booking->time}",
                ['session_details' => $request->all()]
            );
        }

        // Send session booked emails
        try {
            // Email to student
            if ($student) {
                EmailService::send(
                    $student->email,
                    new SessionBookedMail($booking, false), // false = for student
                    'emails'
                );
            }

            // Email to educator
            if ($educator) {
                EmailService::send(
                    $educator->email,
                    new SessionBookedMail($booking, true), // true = for educator
                    'emails'
                );
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send session booked emails: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Session booked successfully!']);
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
    // Session booking: available slots (API + internal)
    // -------------------------------------------------------------------------

    /**
     * Get available time slots for an educator on a given date.
     * Respects educator_session_schedules and max_sessions_per_day; excludes existing bookings.
     *
     * @param int $educatorId
     * @param string $date Y-m-d
     * @param int $slotIntervalMinutes
     * @return array List of time strings ['09:00', '09:30', ...]
     */
    public function getAvailableSlots(int $educatorId, string $date, int $slotIntervalMinutes = 30): array
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->isoWeekday(); // 1=Mon .. 7=Sun

        $schedules = EducatorSessionSchedule::where('educator_id', $educatorId)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        if ($schedules->isEmpty()) {
            return [];
        }

        $profile = EducatorProfile::where('user_id', $educatorId)->first();
        $maxPerDay = $profile && $profile->max_sessions_per_day ? (int) $profile->max_sessions_per_day : 6;

        $bookings = Booking::where('educator_id', $educatorId)
            ->whereDate('date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->get();

        $existingCount = $bookings->count();
        if ($existingCount >= $maxPerDay) {
            return [];
        }
        $slotsAllowed = $maxPerDay - $existingCount;

        $slotInterval = $slotIntervalMinutes;
        $minDurationHours = 1;
        $candidates = [];

        foreach ($schedules as $schedule) {
            $start = $this->timeStringToMinutes($schedule->start_time);
            $end = $this->timeStringToMinutes($schedule->end_time);
            $minEnd = $start + (int)($minDurationHours * 60);
            if ($minEnd > $end) {
                continue;
            }
            for ($t = $start; $t + (int)($minDurationHours * 60) <= $end; $t += $slotInterval) {
                $timeStr = $this->minutesToTimeString($t);
                $candidates[] = $timeStr;
            }
        }
        $candidates = array_unique($candidates);
        sort($candidates);

        $available = [];
        foreach ($candidates as $slotStart) {
            if (count($available) >= $slotsAllowed) {
                break;
            }
            $slotStartMin = $this->timeStringToMinutes($slotStart);
            $slotEndMin = $slotStartMin + (int)($minDurationHours * 60);

            $overlaps = false;
            foreach ($bookings as $b) {
                $bStart = $this->timeStringToMinutes($b->time);
                $bEnd = $bStart + (float)$b->duration * 60;
                if ($slotStartMin < $bEnd && $slotEndMin > $bStart) {
                    $overlaps = true;
                    break;
                }
            }
            if (!$overlaps) {
                $available[] = $slotStart;
            }
        }

        return array_values($available);
    }

    private function timeStringToMinutes($time): int
    {
        if ($time instanceof \Carbon\Carbon) {
            return (int)$time->format('G') * 60 + (int)$time->format('i');
        }
        $parts = explode(':', substr($time, 0, 5));
        return ((int)($parts[0] ?? 0)) * 60 + ((int)($parts[1] ?? 0));
    }

    private function minutesToTimeString(int $minutes): string
    {
        $h = (int)floor($minutes / 60);
        $m = $minutes % 60;
        return sprintf('%02d:%02d', $h, $m);
    }

    /**
     * API: GET /api/educator/{id}/available-slots?date=Y-m-d
     */
    public function getAvailableSlotsApi(Request $request, $educatorId)
    {
        $request->validate(['date' => 'required|date|after_or_equal:today']);
        $educator = User::where('id', $educatorId)->where('role', 'educator')->first();
        if (!$educator) {
            return response()->json(['success' => false, 'message' => 'Educator not found.'], 404);
        }
        $slots = $this->getAvailableSlots((int) $educatorId, $request->date);
        return response()->json(['success' => true, 'slots' => $slots]);
    }

    /**
     * API: GET /api/educator/{id}/available-dates?month=Y-m
     * Returns dates in that month that have at least one available slot (for calendar UI).
     */
    public function getAvailableDatesApi(Request $request, $educatorId)
    {
        $request->validate(['month' => 'required|date_format:Y-m']);
        $educator = User::where('id', $educatorId)->where('role', 'educator')->first();
        if (!$educator) {
            return response()->json(['success' => false, 'message' => 'Educator not found.'], 404);
        }
        $start = Carbon::parse($request->month . '-01');
        $end = $start->copy()->endOfMonth();
        $today = Carbon::today();
        $dates = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($d->lt($today)) {
                continue;
            }
            $slots = $this->getAvailableSlots((int) $educatorId, $d->format('Y-m-d'));
            if (count($slots) > 0) {
                $dates[] = $d->format('Y-m-d');
            }
        }
        return response()->json(['success' => true, 'dates' => $dates]);
    }

    /**
     * Validate that date+time is an available slot (used in bookSession).
     */
    private function isSlotAvailable(int $educatorId, string $date, string $time): bool
    {
        $slots = $this->getAvailableSlots($educatorId, $date);
        $timeNorm = substr($time, 0, 5);
        if (strlen($timeNorm) === 4) {
            $timeNorm = '0' . $timeNorm;
        }
        return in_array($timeNorm, $slots, true);
    }
}
