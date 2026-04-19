<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseSection;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\DocumentService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Services\EmailService;
use App\Services\ActivityNotificationService;
use App\Mail\CourseSubmittedMail;
use App\Mail\AdminNotificationMail;
use App\Models\User;

class CourseCrudController extends Controller
{
    public function index()
    {
        $courses = Course::with(['educator', 'category', 'sections.lessons'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('crm.educator.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = CourseCategory::all();
        return view('crm.educator.courses-test.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'course_category_id' => 'required|exists:course_categories,id',
            'subject' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'is_free' => 'boolean',
            'duration' => 'nullable|string',
            'difficulty' => 'nullable|in:beginner,intermediate,advanced',
            'type' => 'required|in:module,video,live',
            'schedule_date' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:2048',
            'publish_option' => 'required|in:now,schedule,draft',
            'publish_date' => 'nullable|date',
            // 'drip' => 'boolean',
            // 'drip_duration' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_free'] = $request->has('is_free');
        $validated['drip'] = $request->has('drip');

        // Handle status based on publish option
        if ($validated['publish_option'] === 'now') {
            $validated['status'] = 'published';
            $validated['publish_date'] = now();
        } elseif ($validated['publish_option'] === 'schedule') {
            $validated['status'] = 'scheduled';
        } else {
            $validated['status'] = 'draft';
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Store to public/storage/courses/thumbnails
            $file = $request->file('thumbnail');
            $fileName = time() . rand(1000, 9999) . '_' . $file->getClientOriginalName();
            $destinationFolder = public_path('storage/courses/thumbnails');
            // Ensure directory exists
            if (!File::exists($destinationFolder)) {
                File::makeDirectory($destinationFolder, 0755, true);
            }
            $file->move($destinationFolder, $fileName);
            $validated['thumbnail'] = "courses/thumbnails/{$fileName}";
        }

        $course = Course::create($validated);

        // Log activity
        ActivityNotificationService::logAndNotify(
            auth()->user(),
            'create_course',
            'Course',
            $course->id,
            $course->title,
            null,
            [
                'title' => $course->title,
                'subject' => $course->subject,
                'price' => $course->price,
                'status' => $course->status,
                'publish_option' => $course->publish_option
            ],
            "Created new course '{$course->title}'",
            [
                'subject' => $course->subject,
                'level' => $course->level,
                'price' => $course->price,
                'duration' => $course->duration
            ]
        );

        // Send course submitted email to educator
        try {
            EmailService::send(
                auth()->user()->email,
                new CourseSubmittedMail($course),
                'emails'
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send course submitted email: ' . $e->getMessage());
        }

        // Send notification to admin about new course submission
        // try {
        //     $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
        //     foreach ($adminEmails as $adminEmail) {
        //         EmailService::send(
        //             $adminEmail,
        //             new AdminNotificationMail(
        //                 'info',
        //                 [
        //                     'course_title' => $course->title,
        //                     'educator_name' => $course->user->full_name,
        //                     'educator_email' => $course->user->email,
        //                     'course_subject' => $course->subject,
        //                     'course_price' => '$' . number_format($course->price, 2),
        //                     'submission_date' => $course->created_at->format('M j, Y g:i A'),
        //                     'status' => 'Pending Review',
        //                 ],
        //                 'New Course Submitted for Review - Ed-Cademy',
        //                 'A new course has been submitted and requires review.'
        //             ),
        //             'emails'
        //         );
        //     }
        // } catch (\Exception $e) {
        //     \Log::error('Failed to send admin notification for course submission: ' . $e->getMessage());
        // }

        return redirect()->route('educator.courses.crud.show', $course)
            ->with('success', 'Course created successfully!');
    }

    public function show($course)
    {
        // $this->authorize('view', $course);
        $course = Course::findOrFail($course)->load('sections.lessons', 'educator', 'category');



        // dd($course);

        return view('crm.educator.courses-test.show', compact('course'));
    }

    public function edit($course)
    {
        // $this->authorize('update', $course);
        $action = request()->get('action');
    

        $categories = CourseCategory::all();

        $course = Course::findOrFail($course);
        $course->load('sections.lessons');


        return view('crm.educator.courses-test.edit', compact('course', 'categories', 'action'));
    }

    public function update(Request $request, $course_id)
    {
        // $this->authorize('update', $course);

        $course = Course::findOrFail($course_id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'course_category_id' => 'required|exists:course_categories,id',
            'subject' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'is_free' => 'boolean',
            'duration' => 'nullable|string',
            'difficulty' => 'nullable|in:beginner,intermediate,advanced',
            'type' => 'required|in:module,video,live',
            'schedule_date' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:2048',
            'publish_option' => 'required|in:now,schedule,draft',
            'publish_date' => 'nullable|date',
            'drip' => 'boolean',
            'drip_duration' => 'nullable|string',
        ]);

        $validated['is_free'] = $request->has('is_free');
        $validated['drip'] = $request->has('drip');

        // Handle status
        if ($validated['publish_option'] === 'now') {
            $validated['status'] = 'published';
        } elseif ($validated['publish_option'] === 'schedule') {
            $validated['status'] = 'scheduled';
        } else {
            $validated['status'] = 'draft';
        }

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                $thumbnailPath = public_path('storage/' . $course->thumbnail);
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
            }
            $file = $request->file('thumbnail');
            $fileName = time() . rand(1000, 9999) . '_' . $file->getClientOriginalName();
            $destinationFolder = public_path('storage/courses/thumbnails');
            if (!File::exists($destinationFolder)) {
                File::makeDirectory($destinationFolder, 0755, true);
            }
            $file->move($destinationFolder, $fileName);
            $validated['thumbnail'] = "courses/thumbnails/{$fileName}";
        }

        $course->update($validated);
       

        return redirect()->route('educator.courses.crud.show', ['courses_crud' => $course->id])
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        if ($course->thumbnail) {
            $thumbnailPath = public_path('storage/' . $course->thumbnail);
            if (file_exists($thumbnailPath)) {
                unlink($thumbnailPath);
            }
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    // Section Management
    public function storeSection(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        $course->sections()->create($validated);

        return back()->with('success', 'Section added successfully!');
    }

    public function updateSection(Request $request, CourseSection $section)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        $section->update($validated);

        return back()->with('success', 'Section updated successfully!');
    }

    public function destroySection(CourseSection $section)
    {
        $section->delete();
        return back()->with('success', 'Section deleted successfully!');
    }

    /**
     * JSON payload for the edit-lesson modal (session-auth fetch from the course edit page).
     */
    public function lessonEditPayload(Lesson $lesson)
    {
        $lesson->loadMissing('course');

        if ((int) $lesson->course->user_id !== (int) auth()->id()) {
            abort(403);
        }

        return response()->json([
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'duration' => $lesson->duration,
                'price' => $lesson->price,
                'status' => $lesson->status,
                'notes' => $lesson->notes,
                'type' => $lesson->type,
                'free' => (bool) $lesson->free,
                'preview' => (bool) $lesson->preview,
                'video_path' => $lesson->video_path,
                'video_temp_path' => $lesson->video_temp_path,
                'worksheets_path' => $lesson->worksheets ? $lesson->worksheets_path : null,
                'materials_path' => $lesson->materials ? $lesson->materials_path : null,
                'section_id' => $lesson->course_section_id,
                'destroy_url' => route('educator.courses.crud.lessons.destroy', $lesson),
            ],
        ]);
    }

    // Lesson Management
    public function storeLesson(Request $request, CourseSection $section)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,worksheet,material',
            'duration' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',

            'status' => 'required|in:Draft,Published',
            'preview' => 'boolean',
            'video_link' => 'nullable|url',
            'video_temp_path' => 'nullable|string|max:512',
            'worksheet_storage_path' => 'nullable|string|max:512',
            'material_storage_path' => 'nullable|string|max:512',
            'materials' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240',
            'worksheets' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'notes' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            if ($request->input('type') === 'video' && !$request->filled('video_temp_path')) {
                $validator->errors()->add('video_temp_path', 'Please upload a video file.');
            }
            if ($request->input('type') === 'worksheet' && !$request->filled('worksheet_storage_path') && !$request->hasFile('worksheets')) {
                $validator->errors()->add('worksheet_storage_path', 'Please upload a worksheet file.');
            }
            if ($request->input('type') === 'material' && !$request->filled('material_storage_path') && !$request->hasFile('materials')) {
                $validator->errors()->add('material_storage_path', 'Please upload a material file.');
            }
        });

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $validated = $validator->validated();

            $validated['course_id'] = $section->course_id;
            $validated['free'] = $request->has('free');
            $validated['preview'] = $request->has('preview');

            if (($validated['type'] ?? null) !== 'video') {
                unset($validated['video_temp_path']);
            }

            if (($validated['type'] ?? null) === 'video' && !empty($validated['video_temp_path'])) {
                $relative = $validated['video_temp_path'];
                if (!str_starts_with($relative, 'temp_upload/')) {
                    return response()->json(['video_temp_path' => ['Invalid video storage path.']], 422);
                }
                $abs = storage_path('app/' . $relative);
                if (!is_file($abs)) {
                    return response()->json(['video_temp_path' => ['Uploaded video not found. Upload again.']], 422);
                }
            }

            unset($validated['worksheet_storage_path'], $validated['material_storage_path']);

            if (($validated['type'] ?? null) === 'worksheet' && $request->filled('worksheet_storage_path')) {
                $rel = $request->input('worksheet_storage_path');
                if (!$this->publicLessonAssetPathIsValid($rel, 'lessons/worksheets')) {
                    return response()->json(['worksheet_storage_path' => ['Invalid or missing worksheet file.']], 422);
                }
                $validated['worksheets'] = $rel;
            } elseif ($request->hasFile('worksheets')) {
                $validated['worksheets'] = $this->storeLessonWorksheetFromUpload($request->file('worksheets'));
            }

            if (($validated['type'] ?? null) === 'material' && $request->filled('material_storage_path')) {
                $rel = $request->input('material_storage_path');
                if (!$this->publicLessonAssetPathIsValid($rel, 'lessons/materials')) {
                    return response()->json(['material_storage_path' => ['Invalid or missing material file.']], 422);
                }
                $validated['materials'] = $rel;
            } elseif ($request->hasFile('materials')) {
                $validated['materials'] = $this->storeLessonMaterialFromUpload($request->file('materials'));
            }

            if (($validated['type'] ?? null) === 'video') {
                $validated['video_path'] = null;
                $validated['worksheets'] = null;
                $validated['materials'] = null;
            }
            if (($validated['type'] ?? null) === 'worksheet') {
                $validated['materials'] = null;
            }
            if (($validated['type'] ?? null) === 'material') {
                $validated['worksheets'] = null;
            }

            $lesson = $section->lessons()->create($validated);

            return response()->json([
                'message' => 'Lesson added successfully',
                'lesson' => [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'type' => $lesson->type,
                    'duration' => $lesson->duration,
                    'free' => $lesson->free,
                    'preview' => $lesson->preview,
                    'status' => $lesson->status,
                    'section_id' => $section->id,
                    'video_temp_path' => $lesson->video_temp_path,
                    'video_path' => $lesson->video_path,
                    'destroy_url' => route('educator.courses.crud.lessons.destroy', $lesson),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // return back()->with('success', 'Lesson added successfully!');
    }

    public function updateLesson(Request $request, Lesson $lesson)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'nullable|string',
            'duration' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',

            'status' => 'required|in:Draft,Published',
            'preview' => 'boolean',
            'video_link' => 'nullable|url',
            'video_temp_path' => 'nullable|string|max:512',
            'materials' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240',
            'worksheets' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $validated = $validator->validated();

        $validated['free'] = $request->has('free');
        $validated['preview'] = $request->has('preview');

        try {
            if ($request->filled('video_temp_path')) {
                $relative = $request->input('video_temp_path');
                if (!str_starts_with($relative, 'temp_upload/')) {
                    return response()->json(['error' => ['video_temp_path' => ['Invalid video storage path.']]], 422);
                }
                $abs = storage_path('app/' . $relative);
                if (!is_file($abs)) {
                    return response()->json(['error' => ['video_temp_path' => ['Uploaded video not found. Upload again.']]], 422);
                }
                if ($lesson->video_temp_path && $lesson->video_temp_path !== $relative) {
                    Storage::disk('local')->delete($lesson->video_temp_path);
                }
                $validated['video_temp_path'] = $relative;
                $validated['video_path'] = null;
            } else {
                unset($validated['video_temp_path']);
            }

            // Handle file uploads
            if ($request->hasFile('materials')) {
                if ($lesson->materials) {
                    $materialsPath = public_path('storage/' . $lesson->materials);
                    if (file_exists($materialsPath)) {
                        unlink($materialsPath);
                    }
                }
                $file = $request->file('materials');
                $fileName = time() . rand(1000, 9999) . '_' . $file->getClientOriginalName();
                $destinationFolder = public_path('storage/lessons/materials');
                if (!File::exists($destinationFolder)) {
                    File::makeDirectory($destinationFolder, 0755, true);
                }
                $file->move($destinationFolder, $fileName);
                $validated['materials'] = "lessons/materials/{$fileName}";
            }

            if ($request->hasFile('worksheets')) {
                if ($lesson->worksheets) {
                    $worksheetsPath = public_path('storage/' . $lesson->worksheets);
                    if (file_exists($worksheetsPath)) {
                        unlink($worksheetsPath);
                    }
                }
                $file = $request->file('worksheets');
                $fileName = time() . rand(1000, 9999) . '_' . $file->getClientOriginalName();
                $destinationFolder = public_path('storage/lessons/worksheets');
                if (!File::exists($destinationFolder)) {
                    File::makeDirectory($destinationFolder, 0755, true);
                }
                $file->move($destinationFolder, $fileName);
                $validated['worksheets'] = "lessons/worksheets/{$fileName}";
            }

            $lesson->update($validated);

            return response()->json([
                'message' => 'Lesson updated successfully',
                'lesson' => [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'duration' => $lesson->duration,
                    'free' => $lesson->free,
                    'preview' => $lesson->preview,
                    'status' => $lesson->status,
                    'section_id' => $lesson->section_id,
                    'video_temp_path' => $lesson->video_temp_path,
                    'video_path' => $lesson->video_path,
                    'destroy_url' => route('educator.courses.crud.lessons.destroy', $lesson),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


        // return back()->with('success', 'Lesson updated successfully!');
    }

    public function destroyLesson(Lesson $lesson)
    {
        if ($lesson->video_temp_path) {
            Storage::disk('local')->delete($lesson->video_temp_path);
        }
        if ($lesson->materials) {
            $materialsPath = public_path('storage/' . $lesson->materials);
            if (file_exists($materialsPath)) {
                unlink($materialsPath);
            }
        }
        if ($lesson->worksheets) {
            $worksheetsPath = public_path('storage/' . $lesson->worksheets);
            if (file_exists($worksheetsPath)) {
                unlink($worksheetsPath);
            }
        }

        $lesson->delete();
        return back()->with('success', 'Lesson deleted successfully!');
    }

    private function publicLessonAssetPathIsValid(string $relative, string $expectedPrefix): bool
    {
        if (!str_starts_with($relative, $expectedPrefix . '/')) {
            return false;
        }
        if (!preg_match('#^' . preg_quote($expectedPrefix, '#') . '/[a-f0-9]{64}\.[a-z0-9]+$#i', $relative)) {
            return false;
        }

        return is_file(public_path('storage/' . $relative));
    }

    private function storeLessonWorksheetFromUpload($file): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: '');
        $randomName = bin2hex(random_bytes(32)) . '.' . $ext;
        $destinationFolder = public_path('storage/lessons/worksheets');
        if (!File::exists($destinationFolder)) {
            File::makeDirectory($destinationFolder, 0755, true);
        }
        $fullPath = $destinationFolder . '/' . $randomName;
        if ($ext === 'pdf') {
            $docService = new DocumentService();
            File::put($fullPath, $docService->generateWatermarkedPdf($file->getRealPath()));
        } else {
            $file->move($destinationFolder, $randomName);
        }

        return 'lessons/worksheets/' . $randomName;
    }

    private function storeLessonMaterialFromUpload($file): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: '');
        $randomName = bin2hex(random_bytes(32)) . '.' . $ext;
        $destinationFolder = public_path('storage/lessons/materials');
        if (!File::exists($destinationFolder)) {
            File::makeDirectory($destinationFolder, 0755, true);
        }
        $fullPath = $destinationFolder . '/' . $randomName;
        if ($ext === 'pdf') {
            $docService = new DocumentService();
            File::put($fullPath, $docService->generateWatermarkedPdf($file->getRealPath()));
        } else {
            $file->move($destinationFolder, $randomName);
        }

        return 'lessons/materials/' . $randomName;
    }
}
