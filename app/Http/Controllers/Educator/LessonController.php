<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Services\VimeoService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class LessonController extends Controller
{
    /**
     * Store a newly created lesson.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_section_id' => 'required|exists:course_sections,id',
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'type' => 'required',
            'duration' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'free' => 'boolean',
            'status' => 'in:Draft,Published',
            'preview' => 'boolean',
            'notes' => 'nullable|string',
            'video_link' => 'nullable|url',
            'materials.*' => 'file|mimes:pdf,ppt,pptx|max:51200',
            'worksheets.*' => 'file|mimes:pdf,doc,docx|max:51200',
            'video_path' => 'nullable|file|mimes:mp4,mov,avi|max:2048000',
            'resources' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }
        //if course is of auth user
        if (!Course::where("id", $request->course_id)->where("user_id", Auth::id())->exists()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        $data = $request->all();

        try {
            // Handle video upload
            if ($request->hasFile('video_path')) {
                // $data['video_path'] = $request->file('video_path')->store('lessons/videos', 'public');
                $vimeoService = new VimeoService();
                $uploadResponse = $vimeoService->upload($request->file('video_path')->getPathname(), $request->title, $request->notes ?? "");
                $data['video_path'] = $uploadResponse['link'];
            }

            // Handle materials upload
            if ($request->hasFile('materials')) {
                $materials = [];
                foreach ($request->file('materials') as $file) {
                    $materials[] = $file->store('lessons/materials', 'public');
                }
                $data['materials'] = json_encode($materials);
            }

            // Handle worksheets upload
            if ($request->hasFile('worksheets')) {
                $worksheets = [];
                foreach ($request->file('worksheets') as $file) {
                    $worksheets[] = $file->store('lessons/worksheets', 'public');
                }
                $data['worksheets'] = json_encode($worksheets);
            }

            // Encode resources if present
            if ($request->filled('resources')) {
                $data['resources'] = json_encode($request->input('resources'));
            }

            $lesson = Lesson::insert($data);

            return response()->json([
                'message' => 'Lesson created successfully.',
                'lesson' => $lesson
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a specific lesson.
     */
    public function update(Request $request, Lesson $lesson)
    {

        $successMessage = "";

        try {
            $data = $request->validate([
                'title' => 'sometimes|string|max:255',
                'type' => 'required',
                'duration' => 'nullable|integer|min:1',
                'price' => 'nullable|numeric|min:0',
                'free' => 'boolean',
                'status' => 'in:Draft,Published',
                'preview' => 'boolean',
                'notes' => 'nullable|string',
                'video_link' => 'nullable|url',
                'materials.*' => 'file|mimes:pdf,ppt,pptx|max:51200',
                'worksheets.*' => 'file|mimes:pdf,doc,docx|max:51200',
                'video_path' => 'nullable|file|mimes:mp4,mov,avi|max:2048000',
                'resources' => 'array',
            ]);

            // Handle updated uploads

            if ($request->hasFile('materials')) {
                if ($lesson->materials) {
                    foreach (json_decode($lesson->materials, true) as $path) {
                        Storage::disk('public')->delete($path);
                    }
                }
                $materials = [];
                foreach ($request->file('materials') as $file) {
                    $materials[] = $file->store('lessons/materials', 'public');
                }
                $data['materials'] = json_encode($materials);
            }

            if ($request->hasFile('worksheets')) {
                if ($lesson->worksheets) {
                    foreach (json_decode($lesson->worksheets, true) as $path) {
                        Storage::disk('public')->delete($path);
                    }
                }
                $worksheets = [];
                foreach ($request->file('worksheets') as $file) {
                    $worksheets[] = $file->store('lessons/worksheets', 'public');
                }
                $data['worksheets'] = json_encode($worksheets);
            }

            if ($request->filled('resources')) {
                $data['resources'] = json_encode($request->input('resources'));
            }

            $lesson->update($data);
            $successMessage = "Lesson updated successfully.";



            //update the video after updating the lesson
            try {
                $videoLink = "";
                if ($request->hasFile('video_path')) {
                    // if ($lesson->video_path) Storage::disk('public')->delete($lesson->video_path);
                    // $data['video_path'] = $request->file('video_path')->store('lessons/videos', 'public');

                    // Upload to Vimeo
                    $vimeoService = new VimeoService();
                    $uploadResponse = $vimeoService->upload($request->file('video_path')->getPathname(), $request->title, $request->notes);
                    $videoLink = $uploadResponse['link'];
                }
                if (!empty($videoLink)) {
                    $lesson->video_path = $videoLink;
                    $lesson->save();
                }
            } catch (\Exception $e) {
                \Log::error('Vimeo upload error during lesson update: ' . $e->getMessage());
                $successMessage .= " However, there was an issue uploading the video. You can try again or contact the administrator.";
            }


            return response()->json([
                'message' => $successMessage,
                'lesson' => $lesson
            ]);
        } catch (\Exception $e) {
            // 🧩 Catch all unexpected errors
            \Log::error('Lesson update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while updating the lesson.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove a specific lesson.
     */
    public function destroy(Lesson $lesson)
    {
        if ($lesson->video_path) {
            Storage::disk('public')->delete($lesson->video_path);
        }

        if ($lesson->materials) {
            foreach (json_decode($lesson->materials, true) as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        if ($lesson->worksheets) {
            foreach (json_decode($lesson->worksheets, true) as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $lesson->delete();

        return response()->json(['message' => 'Lesson deleted successfully.']);
    }
}
