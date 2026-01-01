<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Course::active()->published()->with("educator", "category", "reviews", "lessons");

            // Search by Title, Description, or Subject
            if ($request->has("search") && $request->search != "") {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where("title", "like", "%" . $search . "%")
                        ->orWhere("description", "like", "%" . $search . "%")
                        ->orWhere("subject", "like", "%" . $search . "%");
                });
            }


            // Difficulty filter
            if ($request->has("difficulty") && $request->difficulty != "") {
                $query->where("difficulty", $request->difficulty);
            }

            // Price filter
            if ($request->has("price_type") && $request->price_type != "") {
                if ($request->price_type === "free") {
                    $query->where(function ($q) {
                        $q->where("price", 0)->orWhere("is_free", true);
                    });
                } elseif ($request->price_type === "paid") {
                    $query->where("price", ">", 0)->where("is_free", false);
                }
            }

            // Type filter
            if ($request->has("type") && $request->type != "") {
                $query->where("type", $request->type);
            }

            // Additional Filters
            if ($request->has("additional_filters") && is_array($request->additional_filters)) {
                foreach ($request->additional_filters as $filter) {
                    if ($filter == "featured") {
                        // Add featured logic if exists
                        $query->where("featured", true);
                    }
                    if ($filter == "top_rated") {
                        $query->withAvg("reviews", "rating")->having("reviews_avg_rating", ">=", 4.5);
                    }
                }
            }

            if ($request->has("sort_by")) {
                switch ($request->sort_by) {
                    case "highest_rated":
                        $query->withAvg("reviews", "rating")->orderByDesc("reviews_avg_rating");
                        break;
                    case "lowest_price":
                        $query->orderBy("price");
                        break;
                    case "highest_price":
                        $query->orderByDesc("price");
                        break;
                    case "newest":
                        $query->orderByDesc("publish_date");
                        break;
                    case "most_popular":
                        $query->withCount("reviews")->orderByDesc("reviews_count");
                        break;
                    default:
                        $query->orderByDesc("publish_date");
                        break;
                }
            } else {
                $query->orderByDesc("publish_date");
            }

            $courses = $query->paginate(9);

            // Transform the data for JSON response
            $transformedCourses = $courses->getCollection()->map(function ($course) {
                return [
                    'id' => $course->id,
                    'slug' => $course->slug,
                    'title' => $course->title,
                    'description' => $course->description,
                    'subject' => $course->subject,
                    'difficulty' => $course->difficulty,
                    'type' => $course->type,
                    'duration' => $course->duration,
                    'price' => $course->price,
                    'is_free' => $course->is_free ?? false,
                    'thumbnail' => $course->thumbnail,
                    'lessons_count' => $course->lessons->count(),
                    'educator' => $course->educator ? [
                        'id' => $course->educator->id,
                        'name' => trim($course->educator->first_name . ' ' . $course->educator->last_name),
                    ] : null,
                    'category' => $course->category ? [
                        'id' => $course->category->id,
                        'name' => $course->category->name,
                    ] : null,
                    'avg_rating' => $course->reviews->avg('rating') ?? 0,
                    'reviews_count' => $course->reviews->count() ?? 0,
                ];
            });

            return response()->json([
                'data' => $transformedCourses,
                'total' => $courses->total(),
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'per_page' => $courses->perPage(),
                'links' => $courses->links()->elements ?? [],
                'prev_page_url' => $courses->previousPageUrl(),
                'next_page_url' => $courses->nextPageUrl(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching courses',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
