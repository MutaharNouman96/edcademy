<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EducatorController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = User::verifiedEducator()->with("EducatorProfile", "educatorReviews");

        // Search by Name or Keyword
        if ($request->has("search") && $request->search != "") {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("first_name", "like", "%" . $search . "%")
                    ->orWhere("last_name", "like", "%" . $search . "%")
                    ->orWhereHas("educatorProfile", function ($q2) use ($search) {
                        $q2->where("bio", "like", "%" . $search . "%")
                           ->orWhere("primary_subject", "like", "%" . $search . "%")
                           ->orWhere("certifications", "like", "%" . $search . "%");
                    });
            });
        }

        // Primary Subject filter
        if ($request->has("subject") && $request->subject != "") {
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
                    // Simplified version to avoid complex joins that might cause issues
                    $query->orderByDesc("created_at"); // Order by newest educators instead
                    break;
                case "most_experience":
                    // years_experience field not available in database yet
                    $query->orderByDesc("created_at"); // Order by newest educators instead
                    break;
                default:
                    break;
            }
        }

        $educators = $query->paginate(10);

        // Transform the data for JSON response
        $transformedEducators = $educators->getCollection()->map(function ($educator) {
            return [
                'id' => $educator->id,
                'name' => trim($educator->first_name . ' ' . $educator->last_name),
                'is_online' => $educator->is_online ?? false,
                'educator_profile' => $educator->educatorProfile ? [
                    'is_featured' => $educator->educatorProfile->featured ?? false,
                    'main_subject' => $educator->educatorProfile->primary_subject ?? 'N/A',
                    'teaching_style' => $educator->educatorProfile->preferred_teaching_style ?? null,
                    'years_experience' => '0', // Not available in database yet
                    'teaching_levels' => $educator->educatorProfile->teaching_levels ?
                        (json_decode($educator->educatorProfile->teaching_levels, true) ?? []) : [],
                    'bio' => $educator->educatorProfile->bio ?? 'No bio available.',
                    'certifications' => $educator->educatorProfile->certifications ?? null,
                    'hourly_rate' => $educator->educatorProfile->hourly_rate ?? '0',
                ] : null,
                'avg_rating' => $educator->educatorReviews->avg('rating') ?? 0,
                'educator_reviews_count' => $educator->educatorReviews->count() ?? 0,
                'students_count' => $educator->students_count ?? 0,
            ];
        });

        return response()->json([
            'data' => $transformedEducators,
            'total' => $educators->total(),
            'current_page' => $educators->currentPage(),
            'last_page' => $educators->lastPage(),
            'per_page' => $educators->perPage(),
            'links' => $educators->links()->elements ?? [],
            'prev_page_url' => $educators->previousPageUrl(),
            'next_page_url' => $educators->nextPageUrl(),
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching educators',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
