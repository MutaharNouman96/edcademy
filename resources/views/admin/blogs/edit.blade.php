<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Edit Blog</h3>
            <div class="text-muted">Update blog content and publishing status.</div>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data"
                class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-12">
                    <label class="form-label">Title</label>
                    <input class="form-control" name="title" value="{{ old('title', $blog->title) }}" required />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" required>
                        <option value="draft" @selected(old('status', $blog->status) === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $blog->status) === 'published')>Published</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Author</label>
                    <input class="form-control" name="author" value="{{ old('author', $blog->author) }}" />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tags (comma separated)</label>
                    <input class="form-control" name="tags" value="{{ old('tags', $blog->tags) }}" />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cover image</label>
                    <input type="file" class="form-control" name="image" accept="image/*" />
                    <div class="form-text">Uploading a new image will replace the old one.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Current image</label>
                    <div class="d-flex align-items-center gap-3">
                        @if ($blog->image_url)
                            <img src="{{ $blog->image_url }}" alt="Cover" style="width: 120px; height: 70px; object-fit: cover;"
                                class="rounded border" />
                        @else
                            <div class="text-muted">—</div>
                        @endif
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1"
                                id="remove_image" />
                            <label class="form-check-label" for="remove_image">Remove image</label>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Content</label>
                    <textarea id="contentEditor" class="form-control" name="content" rows="12" required>{{ old('content', $blog->content) }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-between gap-2">
                    <div class="text-muted small">
                        Slug: <code>{{ $blog->slug }}</code>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.blogs.index') }}">Cancel</a>
                    </div>
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

