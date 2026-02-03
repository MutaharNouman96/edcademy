<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Add Policy</h3>
            <div class="text-muted">Create a new policy page.</div>
        </div>
        <a href="{{ route('admin.policies.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.policies.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Policy Name</label>
                    <input id="policyName" class="form-control" name="name" value="{{ old('name') }}" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input id="policySlug" class="form-control" name="slug" value="{{ old('slug') }}"
                        placeholder="privacy-policy" />
                    <div class="form-text">Optional. If empty, it will be generated automatically.</div>
                </div>

                <div class="col-12">
                    <label class="form-label">Content</label>
                    <textarea id="policyContentEditor" class="form-control" name="content" rows="14"
                        required>{{ old('content') }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2-circle me-1"></i> Create
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.policies.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('policyContentEditor', {
                height: 420
            });

            // Auto-generate slug (doesn't overwrite if user typed manually)
            const nameEl = document.getElementById('policyName');
            const slugEl = document.getElementById('policySlug');
            let slugTouched = false;

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

