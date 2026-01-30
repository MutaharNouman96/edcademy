<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseSection;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\DocumentService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;


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
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course = Course::create($validated);

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

        $categories = CourseCategory::all();

        $course = Course::findOrFail($course);
        $course->load('sections.lessons');


        return view('crm.educator.courses-test.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        // $this->authorize('update', $course);

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
            $validated['publish_date'] = now();
        } elseif ($validated['publish_option'] === 'schedule') {
            $validated['status'] = 'scheduled';
        } else {
            $validated['status'] = 'draft';
        }

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course->update($validated);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
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
            'materials' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240',
            'worksheets' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'notes' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $validated = $validator->validated();

            $validated['course_id'] = $section->course_id;
            $validated['free'] = $request->has('free');
            $validated['preview'] = $request->has('preview');

            // Handle file uploads
            if ($request->hasFile('materials')) {
                // $validated['materials'] = $request->file('materials')->store('lessons/materials', 'public');
                $file = $request->file('materials');

                $fileName = time() . rand(1000, 9999) . '_' . $file->getClientOriginalName();
                $destinationFolder = public_path('storage/lessons/materials');
                // Ensure directory exists
                if (!File::exists($destinationFolder)) {
                    File::makeDirectory($destinationFolder, 0755, true);
                }
                $fullPath = $destinationFolder . '/' . $fileName;
                $docService  = new DocumentService();
                $watermarkedContent = $docService->generateWatermarkedPdf(
                    $file->getRealPath()
                );
                File::put($fullPath, $watermarkedContent);
                $destinationPath = public_path('storage/lessons/materials');
                $file->move($destinationPath, $fileName);
                $validated['worksheets'] = "storage/lessons/materials/" . $fileName;
            }

            if ($request->hasFile('worksheets')) {
                // $validated['worksheets'] = $request->file('worksheets')->store('lessons/worksheets', 'public');
                $file = $request->file('worksheets');
                $fileName = time() . rand(1000, 9999) . '_' . $file->getClientOriginalName();
                $destinationFolder = public_path('storage/lessons/worksheets');
                // Ensure directory exists
                if (!File::exists($destinationFolder)) {
                    File::makeDirectory($destinationFolder, 0755, true);
                }
                $fullPath = $destinationFolder . '/' . $fileName;
                $docService  = new DocumentService();
                $watermarkedContent = $docService->generateWatermarkedPdf(
                    $file->getRealPath()
                );
                File::put($fullPath, $watermarkedContent);
                $destinationPath = public_path('storage/lessons/worksheets');
                $file->move($destinationPath, $fileName);
                $validated['worksheets'] = "storage/lessons/worksheets/" . $fileName;
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

        try{
        // Handle file uploads
        if ($request->hasFile('materials')) {
            if ($lesson->materials) {
                Storage::disk('public')->delete($lesson->materials);
            }
            $validated['materials'] = $request->file('materials')->store('lessons/materials', 'public');
        }

        if ($request->hasFile('worksheets')) {
            if ($lesson->worksheets) {
                Storage::disk('public')->delete($lesson->worksheets);
            }
            $validated['worksheets'] = $request->file('worksheets')->store('lessons/worksheets', 'public');
        }
        $validated['free'] = $request->has('free');
        $validated['preview'] = $request->has('preview');

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
                'destroy_url' => route('educator.courses.crud.lessons.destroy', $lesson),
            ]
        ]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }


        // return back()->with('success', 'Lesson updated successfully!');
    }

    public function destroyLesson(Lesson $lesson)
    {
        if ($lesson->materials) {
            Storage::disk('public')->delete($lesson->materials);
        }
        if ($lesson->worksheets) {
            Storage::disk('public')->delete($lesson->worksheets);
        }

        $lesson->delete();
        return back()->with('success', 'Lesson deleted successfully!');
    }
}
