<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Edit Policy</h3>
            <div class="text-muted">Update policy content and slug.</div>
        </div>
        <a href="{{ route('admin.policies.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.policies.update', $policy) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Policy Name</label>
                    <input class="form-control" name="name" value="{{ old('name', $policy->name) }}" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input class="form-control" name="slug" value="{{ old('slug', $policy->slug) }}" />
                    <div class="form-text">Optional. If empty, it will be generated automatically.</div>
                </div>

                <div class="col-12">
                    <label class="form-label">Content</label>
                    <textarea id="policyContentEditor" class="form-control" name="content" rows="14"
                        required>{{ old('content', $policy->content) }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
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
        </script>
    @endpush
</x-admin-layout>

