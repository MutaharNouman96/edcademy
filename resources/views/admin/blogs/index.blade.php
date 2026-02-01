<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Blogs</h3>
            <div class="text-muted">Create and manage website blog posts.</div>
        </div>
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Blog
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-md-5">
                    <label class="form-label">Search</label>
                    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Title, author, tags" />
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-outline-primary mt-4" type="submit">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.blogs.index') }}">Reset</a>
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
                            <th style="width: 90px;">ID</th>
                            <th>Title</th>
                            <th style="width: 170px;">Status</th>
                            <th style="width: 220px;">Author</th>
                            <th style="width: 190px;">Created</th>
                            <th style="width: 220px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blogs as $blog)
                            <tr>
                                <td>{{ $blog->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $blog->title }}</div>
                                    <div class="text-muted small">
                                        <span class="me-2">Slug: {{ $blog->slug ?: '—' }}</span>
                                        @if ($blog->tags)
                                            <span>Tags: {{ $blog->tags }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($blog->status === 'published')
                                        <span class="badge bg-success-subtle text-success border">Published</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $blog->author }}</td>
                                <td>{{ optional($blog->created_at)->format('M d, Y') }}</td>
                                <td class="text-end">
                                    @if ($blog->slug)
                                        <a class="btn btn-sm btn-outline-secondary" target="_blank"
                                            href="{{ route('blogs.show', ['blog' => $blog->slug]) }}">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> View
                                        </a>
                                    @endif
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.blogs.edit', $blog) }}">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form class="d-inline" method="POST" action="{{ route('admin.blogs.destroy', $blog) }}"
                                        onsubmit="return confirm('Delete this blog post?');">
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
                                <td colspan="6" class="text-center p-4 text-muted">No blogs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($blogs->hasPages())
            <div class="card-footer">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

