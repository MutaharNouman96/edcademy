<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    //

    public function index()
    {
        return view("website.index");
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

    public function cart(){
        return view("website.cart");
    }
}
