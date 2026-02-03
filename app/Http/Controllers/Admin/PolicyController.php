<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $query = Policy::query();
        $query->withTrashed();
        if ($request->filled('q')) {
            $search = trim((string) $request->q);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        $policies = $query->orderBy('deleted_at', 'desc')->paginate(15)->appends($request->query());

        return view('admin.policies.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.policies.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'slug' => $request->filled('slug') ? Str::slug((string) $request->input('slug')) : null,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:policies,slug'],
            'content' => ['required', 'string'],
        ]);

        $policy = new Policy();
        $policy->name = $validated['name'];
        $policy->slug = $validated['slug'] ?? null;
        $policy->content = $validated['content'];
        $policy->save();

        return redirect()->route('admin.policies.index')->with('success', 'Policy created.');
    }

    public function edit(Policy $policy)
    {
        return view('admin.policies.edit', compact('policy'));
    }

    public function update(Request $request, Policy $policy)
    {
        $request->merge([
            'slug' => $request->filled('slug') ? Str::slug((string) $request->input('slug')) : null,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('policies', 'slug')->ignore($policy->id),
            ],
            'content' => ['required', 'string'],
        ]);

        $policy->name = $validated['name'];
        $policy->slug = $validated['slug'] ?? null;
        $policy->content = $validated['content'];
        $policy->save();

        return redirect()->route('admin.policies.index')->with('success', 'Policy updated.');
    }

    public function destroy(Policy $policy)
    {
        $policy->delete();

        return back()->with('success', 'Policy deleted.');
    }

    public function restore($id)
    {
        try {
            $policy = Policy::withTrashed()->findOrFail($id);
            if (!$policy->deleted_at) {
                return back()->with('error', 'Policy is not deleted.');
            }
            $policy->deleted_at = null;
            $policy->save();
            return redirect()->route('admin.policies.index')->with('success', 'Policy restored.');
        } catch (\Exception $e) {
            return redirect()->route('admin.policies.index')->with('error', 'Failed to restore policy.');
        }
    }
}
