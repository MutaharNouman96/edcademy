<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Services\GenAIService;
use Illuminate\Http\Request;

class GenAIController extends Controller
{
    protected $genAIService;

    public function __construct(GenAIService $genAIService)
    {
        $this->genAIService = $genAIService;
    }

    public function generateCourseContent(Request $request)
    {
        $courseId = $request->input('course_id');
        $course = null;

        if ($courseId) {
            $course = Course::where('id', $courseId)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if ($course->aiGenerationsRemaining() <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have used all ' . Course::AI_GENERATION_LIMIT . ' AI generations for this course.',
                    'remaining' => 0,
                ], 429);
            }
        } else {
            $sessionCount = (int) session('course_ai_generation_count', 0);

            if ($sessionCount >= Course::AI_GENERATION_LIMIT) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have used all ' . Course::AI_GENERATION_LIMIT . ' AI generations for this course.',
                    'remaining' => 0,
                ], 429);
            }
        }

        $formData = $request->all();

        if (!empty($formData['course_category_id'])) {
            $category = CourseCategory::find($formData['course_category_id']);
            if ($category) {
                $formData['course_category_name'] = $category->name;
            }
        }

        try {
            $generated = $this->genAIService->generateCourseTitleAndDescription($formData);

            if ($course) {
                $course->increment('ai_generation_count');
                $remaining = $course->fresh()->aiGenerationsRemaining();
            } else {
                $newCount = (int) session('course_ai_generation_count', 0) + 1;
                session(['course_ai_generation_count' => $newCount]);
                $remaining = max(0, Course::AI_GENERATION_LIMIT - $newCount);
            }

            return response()->json([
                'success' => true,
                'title' => $generated['title'],
                'description' => $generated['description'],
                'remaining' => $remaining,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
