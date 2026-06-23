<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query()->with('category');

        if ($request->filled('q')) {
            $search = trim((string) $request->q);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('active')) {
            $query->where('active', $request->active === '1');
        }

        $subjects = $query->orderBy('name')->paginate(15)->appends($request->query());
        $categories = CourseCategory::orderBy('name')->get();

        return view('admin.subjects.index', compact('subjects', 'categories'));
    }

    public function create()
    {
        $categories = CourseCategory::orderBy('name')->get();

        return view('admin.subjects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateSubject($request);

        Subject::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'category_id' => $validated['category_id'] ?? null,
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject created.');
    }

    public function edit(Subject $subject)
    {
        $categories = CourseCategory::orderBy('name')->get();

        return view('admin.subjects.edit', compact('subject', 'categories'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $this->validateSubject($request, $subject->id);

        $subject->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'category_id' => $validated['category_id'] ?? null,
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return back()->with('success', 'Subject deleted.');
    }

    private function validateSubject(Request $request, ?int $ignoreId = null): array
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
                Rule::unique('subjects', 'slug')->ignore($ignoreId),
            ],
            'category_id' => ['nullable', 'integer', 'exists:course_categories,id'],
        ]);
    }
}
