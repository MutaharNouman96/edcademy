<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CourseReview;
use App\Models\EducatorReview;

class ReviewController extends Controller
{
    //

    public function index()
    {
        $courseReviews = CourseReview::whereHas('course', function ($q) {
            $q->where('user_id', auth()->user()->id);
        })
            ->with(['course', 'student'])
            ->latest()
            ->get();

        $educatorReviews = EducatorReview::with(['educator', 'student'])
            ->where('educator_id', auth()->user()->id)
            ->latest()->get();

        return view('crm.educator.reviews.index', compact('courseReviews', 'educatorReviews'));
    }
}
