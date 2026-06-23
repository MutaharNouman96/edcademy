<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Edit Subject</h3>
            <div class="text-muted">Update subject details.</div>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.subjects.update', $subject) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input id="subjectName" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name', $subject->name) }}" required />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input id="subjectSlug" class="form-control @error('slug') is-invalid @enderror" name="slug"
                        value="{{ old('slug', $subject->slug) }}" />
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select class="form-select select2 @error('category_id') is-invalid @enderror" name="category_id">
                        <option value="">None</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $subject->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="active" name="active"
                            value="1" @checked(old('active', $subject->active))>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2-circle me-1"></i> Save changes
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.subjects.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const nameEl = document.getElementById('subjectName');
            const slugEl = document.getElementById('subjectSlug');
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
