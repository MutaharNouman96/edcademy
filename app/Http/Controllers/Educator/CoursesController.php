<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\CourseFeature;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Validator;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $courses = Course::where('user_id', auth()->id())->paginate(10);
        return view('crm.educator.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('crm.educator.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'free' => 'boolean',
            'release' => 'nullable|string|in:publish,schedule,draft',
            'schedule_date' => 'nullable|date',
            'tags' => 'nullable|string',
            'description' => 'required|string',
            'drip' => 'boolean',
            'thumbnail' => 'nullable|file|image|max:2048',
        ]);

        // Handle file upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Create new course
        $course = new Course();
        $course->user_id = auth()->id() ?? 1; // fallback for testing
        $course->title = $request->title;
        $course->subject = $request->subject;
        $course->level = $request->level ?? 'School';
        $course->language = $request->language ?? 'English';
        $course->price = $request->free ? 0 : $request->price ?? 0;
        $course->schedule_date = $request->schedule_date;
        $course->tags = $request->tags;
        $course->description = $request->description;
        $course->drip = $request->boolean('drip');
        $course->thumbnail = $thumbnailPath;

        $course->status = $request->status ?? 'draft';

        $course->save();

        return redirect()->route('educator.courses.edit', $course)->with('success', 'Course created successfully. Proceed for next steps');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $course = Course::findOrFail($id);
        return view('crm.educator.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function course_sections($course_id)
    {
        $course = Course::where('id', $course_id)
            ->where('user_id', auth()->id())
            ->first();
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }
        try {
            $sections = CourseSection::where('course_id', $course_id)->with("lessons")->get();
            return response()->json(
                [
                    'sections' => $sections,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function post_course_section(Request $request, $course_id)
    {
        $course = Course::find($course_id);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        // $validation = Validator::make($request->all(), [
        //     "course_id" => "required|integer",
        //     "title" => "required",
        //     "order" => "integer"
        // ]);

        // if ($validation->fails()) {
        //     return response()->json(['error' => $validation->errors()], 422);
        // }
        $courseSectionIndex = $course->sections()->count() + 1;

        $section = $course->sections()->create([
            'title' => $course->title . ' section ' . $courseSectionIndex,
            'order' => $courseSectionIndex,
        ]);

        return response()->json(
            [
                'section' => $section,
            ],
            200,
        );
    }

    public function delete_course_section(Request $request, $section_id)
    {
        $section = CourseSection::find($section_id);
        if (!$section) {
            return response()->json(['error' => 'Section not found'], 404);
        }
        CourseSection::where("id", $section_id)->delete();
        return response()->json(
            [
                'success' => true,
                'section' => $section,
                'message' => 'Section deleted successfully',
            ],
            200,
        );
    }
}
