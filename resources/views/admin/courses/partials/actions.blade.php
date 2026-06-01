<div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('admin.manage.courses') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
    @if ($course->approval_status !== 'approved')
        <form method="POST" action="{{ route('admin.courses.approve', $course->id) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-lg me-1"></i>Approve
            </button>
        </form>
    @endif
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
        <i class="bi bi-x-lg me-1"></i>Reject
    </button>
    <form method="POST" action="{{ route('admin.courses.status', $course->id) }}" class="d-inline">
        @csrf
        @method('PATCH')
        <select name="status" onchange="this.form.submit()" class="form-select status-select status-{{ $course->status }}" style="min-width: 140px;">
            <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ $course->status === 'published' ? 'selected' : '' }}>Published</option>
            @if ($course->status === 'scheduled')
                <option value="scheduled" selected>Scheduled</option>
            @endif
        </select>
    </form>
</div>
