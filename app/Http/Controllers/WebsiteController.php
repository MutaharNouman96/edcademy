<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseReview;
use App\Models\StudentTestimonial;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        $courses = Course::active()->published()->orderByDesc("publish_date")->with("educator", "category", "reviews", "lessons")->paginate(9);

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

    public function educators()
    {
        return view("website.educators");
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
        $myCart = Cart::where("user_id", Auth::check() ? Auth::id() : session()->getId())->get();

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
            'date' => 'required|date',
            'time' => 'required|string',
            'duration' => 'required|numeric',
            'subject' => 'required|string',
            'educator_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
        ]);

        // Here you would typically save the booking to a database.
        // For now, we'll just return a success response.
        // You'll need to create a 'Booking' model and migration for this.

                if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please log in to book a session.'], 401);
        }

        Booking::create([
            'student_id' => Auth::id() ?? 114,
            'educator_id' => $request->educator_id,
            'date' => $request->date,
            'time' => $request->time,
            'duration' => $request->duration,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'message' => 'Session booked successfully!']);
    }
}

