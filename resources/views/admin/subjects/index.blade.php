<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Subjects</h3>
            <div class="text-muted">Manage subjects linked to course categories.</div>
        </div>
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Subject
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Subject name or slug" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select select2" name="category_id">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="active">
                        <option value="">All</option>
                        <option value="1" @selected(request('active') === '1')>Active</option>
                        <option value="0" @selected(request('active') === '0')>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-outline-primary mt-4" type="submit">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.subjects.index') }}">Reset</a>
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
                            <th>Category</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 200px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr>
                                <td>{{ $subject->id }}</td>
                                <td class="fw-semibold">{{ $subject->name }}</td>
                                <td><code>{{ $subject->slug }}</code></td>
                                <td>{{ $subject->category?->name ?? '—' }}</td>
                                <td>
                                    @if ($subject->active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('admin.subjects.edit', $subject) }}">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form class="d-inline" method="POST"
                                        action="{{ route('admin.subjects.destroy', $subject) }}"
                                        onsubmit="return confirm('Delete this subject?');">
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
                                <td colspan="6" class="text-center p-4 text-muted">No subjects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($subjects->hasPages())
            <div class="card-footer">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
