<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Add Blog</h3>
            <div class="text-muted">Create a new blog post.</div>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf

                <div class="col-12">
                    <label class="form-label">Title</label>
                    <input class="form-control" name="title" value="{{ old('title') }}" required />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" required>
                        <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                        <option value="published" @selected(old('status') === 'published')>Published</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Author</label>
                    <input class="form-control" name="author" value="{{ old('author', $defaultAuthor) }}" />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tags (comma separated)</label>
                    <input class="form-control" name="tags" value="{{ old('tags') }}" placeholder="education, tips, parenting" />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cover image (optional)</label>
                    <input type="file" class="form-control" name="image" accept="image/*" />
                    <div class="form-text">Max 2MB.</div>
                </div>

                <div class="col-12">
                    <label class="form-label">Content</label>
                    <textarea id="contentEditor" class="form-control" name="content" rows="12" required>{{ old('content') }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2-circle me-1"></i> Create
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.blogs.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('contentEditor', {
                height: 420
            });
        </script>
    @endpush
</x-admin-layout>

