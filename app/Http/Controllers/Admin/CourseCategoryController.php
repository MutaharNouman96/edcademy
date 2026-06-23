<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseCategory::query()->with('parent')->withCount(['courses', 'subjects', 'children']);

        if ($request->filled('q')) {
            $search = trim((string) $request->q);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        $categories = $query->orderBy('name')->paginate(15)->appends($request->query());

        return view('admin.course-categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = CourseCategory::orderBy('name')->get();

        return view('admin.course-categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);

        CourseCategory::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.course-categories.index')->with('success', 'Course category created.');
    }

    public function edit(CourseCategory $courseCategory)
    {
        $parentCategories = CourseCategory::where('id', '!=', $courseCategory->id)
            ->orderBy('name')
            ->get();

        return view('admin.course-categories.edit', compact('courseCategory', 'parentCategories'));
    }

    public function update(Request $request, CourseCategory $courseCategory)
    {
        $validated = $this->validateCategory($request, $courseCategory->id);

        if (!empty($validated['parent_id']) && (int) $validated['parent_id'] === (int) $courseCategory->id) {
            return back()->withInput()->withErrors(['parent_id' => 'A category cannot be its own parent.']);
        }

        $courseCategory->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.course-categories.index')->with('success', 'Course category updated.');
    }

    public function destroy(CourseCategory $courseCategory)
    {
        if ($courseCategory->children()->exists()) {
            return back()->with('error', 'Cannot delete a category that has sub-categories.');
        }

        if ($courseCategory->courses()->exists()) {
            return back()->with('error', 'Cannot delete a category that has courses assigned.');
        }

        if ($courseCategory->subjects()->exists()) {
            return back()->with('error', 'Cannot delete a category that has subjects assigned.');
        }

        $courseCategory->delete();

        return back()->with('success', 'Course category deleted.');
    }

    private function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        $request->merge([
            'slug' => $request->filled('slug')
                ? Str::slug((string) $request->input('slug'))
                : Str::slug((string) $request->input('name')),
        ]);

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('course_categories', 'slug')->ignore($ignoreId),
            ],
            'parent_id' => ['nullable', 'integer', 'exists:course_categories,id'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
