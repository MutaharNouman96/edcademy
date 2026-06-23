<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Course Categories</h3>
            <div class="text-muted">Manage categories used when educators create courses.</div>
        </div>
        <a href="{{ route('admin.course-categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Category
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Category name or slug" />
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button class="btn btn-outline-primary mt-4" type="submit">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.course-categories.index') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>Name</th>
                            <th style="width: 200px;">Slug</th>
                            <th>Parent</th>
                            <th style="width: 90px;">Courses</th>
                            <th style="width: 90px;">Subjects</th>
                            <th style="width: 200px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td class="fw-semibold">{{ $category->name }}</td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>{{ $category->parent?->name ?? '—' }}</td>
                                <td>{{ $category->courses_count }}</td>
                                <td>{{ $category->subjects_count }}</td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('admin.course-categories.edit', $category) }}">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form class="d-inline" method="POST"
                                        action="{{ route('admin.course-categories.destroy', $category) }}"
                                        onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-4 text-muted">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($categories->hasPages())
            <div class="card-footer">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
