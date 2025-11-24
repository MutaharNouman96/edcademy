<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseCategory;

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
}
