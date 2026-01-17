<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class OpenAIController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function generateCourseContent(Request $request)
    {
        $formData = $request->all();

        // Resolve category name if id is provided
        if (!empty($formData['course_category_id'])) {
            $category = CourseCategory::find($formData['course_category_id']);
            if ($category) {
                $formData['course_category_name'] = $category->name;
            }
        }

        try {
            $generated = $this->openAIService->generateCourseTitleAndDescription($formData);

            return response()->json([
                'success' => true,
                'title' => $generated['title'],
                'description' => $generated['description']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}