<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Edit Course Category</h3>
            <div class="text-muted">Update category details.</div>
        </div>
        <a href="{{ route('admin.course-categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.course-categories.update', $courseCategory) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input id="categoryName" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name', $courseCategory->name) }}" required />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input id="categorySlug" class="form-control @error('slug') is-invalid @enderror" name="slug"
                        value="{{ old('slug', $courseCategory->slug) }}" />
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Parent category</label>
                    <select class="form-select select2 @error('parent_id') is-invalid @enderror" name="parent_id">
                        <option value="">None (top-level)</option>
                        @foreach ($parentCategories as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id', $courseCategory->parent_id) == $parent->id)>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                        rows="4">{{ old('description', $courseCategory->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2-circle me-1"></i> Save changes
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.course-categories.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const nameEl = document.getElementById('categoryName');
            const slugEl = document.getElementById('categorySlug');
            let slugTouched = slugEl.value.trim().length > 0;

            slugEl.addEventListener('input', () => {
                slugTouched = slugEl.value.trim().length > 0;
            });

            nameEl.addEventListener('input', () => {
                if (slugTouched) return;
                slugEl.value = (nameEl.value || '')
                    .toLowerCase()
                    .trim()
                    .replace(/['"]/g, '')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            });
        </script>
    @endpush
</x-admin-layout>
