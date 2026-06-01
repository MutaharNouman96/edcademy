<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseSection;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Services\DocumentService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Services\EmailService;
use App\Services\ActivityNotificationService;
use App\Mail\CourseSubmittedMail;
use App\Models\Subject;
use Illuminate\Support\Str;
use App\Models\Language;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CourseCrudController extends Controller
{
    public function index()
    {
        $courses = Course::with(['educator', 'category', 'sections.lessons'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('crm.educator.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = CourseCategory::all();
        $languages = Language::all();

        return view('crm.educator.courses.create', compact('categories', 'languages'));
    }

    /**
     * Return active subjects for a given category as JSON.
     * Consumed by the course create/edit forms via /api/categories/{category}/subjects.
     */
    public function subjectsByCategory(CourseCategory $category)
    {
        $subjects = Subject::query()
            ->where('category_id', $category->id)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
            'subjects' => $subjects,
        ]);
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
            'price' => 'nullable|numeric|min:0',
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
            $validated['thumbnail'] = url("public/storage/courses/thumbnails/{$fileName}");
        }

        $course = Course::create($validated);

        //update slug
        $course->slug = Str::slug($course->title) . '-' . $course->id;
        $course->save();



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
                'publish_option' => $course->publish_option,
            ],
            "Created new course '{$course->title}'",
            [
                'subject' => $course->subject,
                'level' => $course->level,
                'price' => $course->price,
                'duration' => $course->duration,
            ],
        );

        // Send course submitted email to educator
        try {
            EmailService::send(auth()->user()->email, new CourseSubmittedMail($course), 'emails');
        } catch (\Exception $e) {
            Log::error('Failed to send course submitted email: ' . $e->getMessage());
        }

        return redirect()->route('educator.courses.crud.show', $course)->with('success', 'Course created successfully! Add course content under course curriculum section.');
    }

    public function show($course)
    {
        // $this->authorize('view', $course);
        $course = Course::findOrFail($course)->load('sections.lessons', 'educator', 'category');

        // dd($course);

        return view('crm.educator.courses.show', compact('course'));
    }

    public function edit($course)
    {
        // $this->authorize('update', $course);
        $action = request()->get('action');

        $categories = CourseCategory::all();
        $languages = Language::all();

        $course = Course::findOrFail($course);
        $course->load('sections.lessons');

        return view('crm.educator.courses.edit', compact('course', 'categories', 'languages', 'action'));
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
            'price' => 'sometimes|numeric|min:0',
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
            $validated['thumbnail'] = url("public/storage/courses/thumbnails/{$fileName}");
        }
        $course->slug = Str::slug($validated['title']) . '-' . $course->id;

        $course->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Course updated successfully!');
    }

    public function destroy($course)
    {
        $course = Course::findOrFail($course);
        try {
            // $this->authorize('delete', $course);

            if ($course->thumbnail) {
                $thumbnailPath = public_path('storage/' . $course->thumbnail);
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
            }
            if ($course->sections) {
                foreach ($course->sections as $section) {
                    if ($section->lessons) {
                        foreach ($section->lessons as $lesson) {
                            // Remove the lesson video from S3 (videos live on S3 now).
                            $videoKey = $this->extractS3VideoKey($lesson->video_path);
                            if ($videoKey && Storage::disk('s3')->exists($videoKey)) {
                                Storage::disk('s3')->delete($videoKey);
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
                        }
                    }
                    $section->delete();
                }
            }
            //remove purchased items
            $course->purchasers()->detach();
            //remove reviews
            $course->reviews()->delete();
            //remove features
            $course->features()->delete();
            //remove sections
            $course->sections()->delete();
            //remove lessons
            $course->lessons()->delete();

            $course->delete();
            return response()->json(['success' => true, 'message' => 'Course deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
                'worksheets_path' => $lesson->worksheets ? $lesson->worksheets_path : null,
                'materials_path' => $lesson->materials ? $lesson->materials_path : null,
                'lesson_video_path' => $lesson->lesson_video_path,
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
            // S3 object key of the already-uploaded video (videos go straight to S3 on upload).
            'video_storage_path' => 'nullable|string|max:512',
            'worksheet_storage_path' => 'nullable|string',
            'material_storage_path' => 'nullable|string',
            'materials' => 'nullable|file|mimes:pdf,ppt,pptx,image/png, image/jpeg, image/gif, image/webp|max:50480',
            'notes' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            if ($request->input('type') === 'video' && !$request->filled('video_storage_path')) {
                $validator->errors()->add('video_storage_path', 'Please upload a video file.');
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
            $validated['price'] = $request->has('price') && $request->price > 0 ? $request->price : 0;
            $validated['preview'] = $request->has('preview');
            // Newly created lessons are inactive until an admin verifies them
            // (or the parent course is approved). See lessons.active.
            $validated['active'] = false;

            // The video is already on S3 (uploaded directly by the dropzone), so we
            // only resolve its public URL here — no temp file / move step anymore.
            $videoStoragePath = $request->input('video_storage_path');
            if (($validated['type'] ?? null) === 'video' && !empty($videoStoragePath)) {
                if (!str_starts_with($videoStoragePath, 'lessons/videos/')) {
                    return response()->json(['video_storage_path' => ['Invalid video storage path.']], 422);
                }
                if (!Storage::disk('s3')->exists($videoStoragePath)) {
                    return response()->json(['video_storage_path' => ['Uploaded video not found. Upload again.']], 422);
                }

                $validated['video_path'] = Storage::disk('s3')->url($videoStoragePath);
            }
            unset($validated['video_storage_path']);



            if ($request->has('worksheet_storage_path') && $request->input('worksheet_storage_path') !== null && $request->get('type') === 'worksheet') {
                $filePath = public_path('storage/' . $request->input('worksheet_storage_path'));
                if (!is_file($filePath)) {
                    return response()->json(['worksheet_storage_path' => ['Uploaded worksheet not found. Upload again.']], 422);
                }
                $validated['worksheets'] = $this->storeLessonWorksheetFromUpload($filePath);
            }

            if ($request->has('material_storage_path') && $request->input('material_storage_path') !== null && $request->get('type') === 'material') {
                $filePath = public_path('storage/' . $request->input('material_storage_path'));
                if (!is_file($filePath)) {
                    return response()->json(['material_storage_path' => ['Uploaded material not found. Upload again.']], 422);
                }
                $validated['materials'] = $this->storeLessonMaterialFromUpload($filePath);
            }


            // Keep only the media relevant to the chosen lesson type.
            if (($validated['type'] ?? null) === 'video') {
                $validated['worksheets'] = null;
                $validated['materials'] = null;
            }
            if (($validated['type'] ?? null) === 'worksheet') {
                $validated['materials'] = null;
            }
            if (($validated['type'] ?? null) === 'material') {
                $validated['worksheets'] = null;
            }

            unset($validated['worksheet_storage_path'], $validated['material_storage_path']);

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
                    'video_path' => $lesson->video_path,
                    'destroy_url' => route('educator.courses.crud.lessons.destroy', $lesson),
                ],
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
            // S3 object key of a freshly uploaded replacement video (optional on update).
            'video_storage_path' => 'nullable|string|max:512',
            'materials' => 'nullable|file|mimes:pdf,ppt,pptx,image/png, image/jpeg, image/gif, image/webp|max:102400',
            'worksheets' => 'nullable|file|mimes:pdf,doc,docx,image/png, image/jpeg, image/gif, image/webp|max:102400',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $validated = $validator->validated();

        $validated['free'] = $request->has('free');
        $validated['preview'] = $request->has('preview');

        try {
            // The replacement video (if any) is already on S3 — just point video_path at it.
            if ($request->filled('video_storage_path')) {
                $newKey = $request->input('video_storage_path');
                if (!str_starts_with($newKey, 'lessons/videos/')) {
                    return response()->json(['error' => ['video_storage_path' => ['Invalid video storage path.']]], 422);
                }
                if (!Storage::disk('s3')->exists($newKey)) {
                    return response()->json(['error' => ['video_storage_path' => ['Uploaded video not found. Upload again.']]], 422);
                }

                // Remove the previously stored S3 video (if different) to avoid orphans.
                $oldKey = $this->extractS3VideoKey($lesson->video_path);
                if ($oldKey && $oldKey !== $newKey && Storage::disk('s3')->exists($oldKey)) {
                    Storage::disk('s3')->delete($oldKey);
                }

                $validated['video_path'] = Storage::disk('s3')->url($newKey);
            }
            unset($validated['video_storage_path']);

            // Handle 'materials' file upload to S3 and remove old S3 file
            if ($request->hasFile('materials')) {
                // Remove old from S3 if present
                if ($lesson->materials && Storage::disk('s3')->exists($lesson->materials)) {
                    Storage::disk('s3')->delete($lesson->materials);
                }
                $file = $request->file('materials');
                $ext = strtolower($file->getClientOriginalExtension() ?: '');
                $fileName = 'lessons/materials/' . bin2hex(random_bytes(16)) . '.' . $ext;
                Storage::disk('s3')->put($fileName, file_get_contents($file->getRealPath()));
                $validated['materials'] = Storage::disk('s3')->url($fileName);
            }

            // Handle 'worksheets' file upload to S3 and remove old S3 file
            if ($request->hasFile('worksheets')) {
                // Remove old from S3 if present
                if ($lesson->worksheets && Storage::disk('s3')->exists($lesson->worksheets)) {
                    Storage::disk('s3')->delete($lesson->worksheets);
                }
                $file = $request->file('worksheets');
                $ext = strtolower($file->getClientOriginalExtension() ?: '');
                $fileName = 'lessons/worksheets/' . bin2hex(random_bytes(16)) . '.' . $ext;
                Storage::disk('s3')->put($fileName, file_get_contents($file->getRealPath()));
                $validated['worksheets'] = Storage::disk('s3')->url($fileName);
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
                    'video_path' => $lesson->video_path,
                    'destroy_url' => route('educator.courses.crud.lessons.destroy', $lesson),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // return back()->with('success', 'Lesson updated successfully!');
    }

    public function destroyLesson(Lesson $lesson)
    {
        // Remove the lesson video from S3 (videos live on S3 now).
        $videoKey = $this->extractS3VideoKey($lesson->video_path);
        if ($videoKey && Storage::disk('s3')->exists($videoKey)) {
            Storage::disk('s3')->delete($videoKey);
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

    private function storeLessonWorksheetFromUpload($filePath): string
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $randomName = bin2hex(random_bytes(32)) . '.' . $ext;
        $s3Path = 'lessons/worksheets/' . $randomName;

        $uploaded = Storage::disk('s3')->put($s3Path, File::get($filePath));

        if (!$uploaded || !Storage::disk('s3')->exists($s3Path)) {
            throw new \RuntimeException('Worksheet upload to storage failed. Please try again.');
        }

        //delete the file from the local storage
        unlink($filePath);

        return Storage::disk('s3')->url($s3Path);
    }

    private function storeLessonMaterialFromUpload($filePath): string
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $randomName = bin2hex(random_bytes(32)) . '.' . $ext;
        $s3Path = 'lessons/materials/' . $randomName;

        $uploaded = Storage::disk('s3')->put($s3Path, File::get($filePath));

        if (!$uploaded || !Storage::disk('s3')->exists($s3Path)) {
            throw new \RuntimeException('Material upload to storage failed. Please try again.');
        }

        //delete the file from the local storage
        unlink($filePath);

        return Storage::disk('s3')->url($s3Path);
    }

    /**
     * Resolve the S3 object key for a stored lesson video.
     *
     * video_path is persisted as a full S3 URL; we only ever manage keys under
     * "lessons/videos/". Returns null for external links or unrecognised values.
     */
    private function extractS3VideoKey(?string $videoPath): ?string
    {
        if (!$videoPath) {
            return null;
        }

        $path = parse_url($videoPath, PHP_URL_PATH);
        if (!$path) {
            return null;
        }

        $key = ltrim($path, '/');

        return str_starts_with($key, 'lessons/videos/') ? $key : null;
    }
}
