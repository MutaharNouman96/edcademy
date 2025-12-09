<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseReview;
use App\Models\StudentTestimonial;
use App\Models\Subject;
use App\Models\User;

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
        return view("website.educator", compact("educator"));
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
}
