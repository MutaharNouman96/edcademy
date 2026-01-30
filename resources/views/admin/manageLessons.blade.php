<x-admin-layout>

<h4 class="mb-3">Lesson Management</h4>

<!-- Summary Cards -->
@if(isset($lessons))
<div class="row g-4 mb-4">
    <div class="col-12 mb-3">
        <h5 class="section-title mb-3">
            <i class="bi bi-book me-2"></i>Lesson Overview
        </h5>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Total Lessons</div>
                    <div class="kpi-value">{{ $lessons->total() }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-collection-play"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Published</div>
                    <div class="kpi-value">{{ $lessons->where('status', 'Published')->count() }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-check-circle"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Draft</div>
                    <div class="kpi-value">{{ $lessons->where('status', 'Draft')->count() }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-pencil"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Free Lessons</div>
                    <div class="kpi-value">{{ $lessons->where('free', 1)->count() }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-gift"></i></span>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Filters -->
<div class="kpi-card">
    <div class="p-3 border-bottom">
        <div class="tab-section-header">
            <h5 class="section-title">
                <i class="bi bi-book me-2"></i>Lesson Management
            </h5>
            <p class="text-muted small">Review and manage lesson content, materials, and publishing status</p>
        </div>
    </div>

    <div class="p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Lesson Filters</h6>
        </div>
        <form method="GET" action="{{ route('admin.manage.lessons') }}" class="filter-bar p-3 mb-0">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Published" {{ request('status') == 'Published' ? 'selected' : '' }}>Published</option>
                        <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="text" {{ request('type') == 'text' ? 'selected' : '' }}>Text</option>
                        <option value="quiz" {{ request('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="assignment" {{ request('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Course</label>
                    <select name="course_id" class="form-select">
                        <option value="">All Courses</option>
                        @if(isset($courses))
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ Str::limit($course->title, 30) }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Access</label>
                    <select name="free" class="form-select">
                        <option value="">All</option>
                        <option value="1" {{ request('free') == '1' ? 'selected' : '' }}>Free</option>
                        <option value="0" {{ request('free') == '0' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Lesson title, course...">
                </div>
                <div class="col-md-2 text-md-end">
                    <div class="d-flex gap-2 justify-content-end">
                        <button class="btn btn-brand">Apply</button>
                        <a href="{{ route('admin.manage.lessons') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="p-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Lesson</th>
                    <th>Course</th>
                    <th>Educator</th>
                    <th>Type</th>
                    <th>Access</th>
                    <th>Status</th>
                    <th>Duration</th>
                    <th>Materials</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($lessons ?? [] as $lesson)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($lesson->thumbnail)
                                <img src="{{ asset($lesson->thumbnail) }}" alt="thumbnail" class="rounded me-2" style="width: 40px; height: 30px; object-fit: cover;">
                                @else
                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 30px;">
                                    <i class="bi bi-play-circle text-muted"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $lesson->title ?? $lesson->name }}</div>
                                    @if($lesson->preview)
                                    <small class="badge bg-info">Preview</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $lesson->course ? Str::limit($lesson->course->title, 25) : 'N/A' }}</div>
                            <small class="text-muted">{{ $lesson->courseSection ? $lesson->courseSection->title : 'N/A' }}</small>
                        </td>
                        <td>{{ $lesson->course && $lesson->course->educator ? $lesson->course->educator->full_name : 'N/A' }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($lesson->type ?? 'N/A') }}</span>
                        </td>
                        <td>
                            @if($lesson->free)
                            <span class="badge bg-success">Free</span>
                            @else
                            <span class="badge bg-primary">AED {{ number_format($lesson->price, 2) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge text-bg-{{
                                $lesson->status === 'Published' ? 'success' :
                                ($lesson->status === 'Draft' ? 'warning' : 'secondary')
                            }}">
                                {{ $lesson->status ?? 'Draft' }}
                            </span>
                        </td>
                        <td>
                            @if($lesson->duration)
                            {{ $lesson->duration }} min
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $materialsCount = 0;
                                if ($lesson->materials) $materialsCount++;
                                if ($lesson->worksheets) $materialsCount++;
                                if ($lesson->resources) $materialsCount++;
                                if ($lesson->assignments) $materialsCount++;
                            @endphp
                            @if($materialsCount > 0)
                            <span class="badge bg-info">{{ $materialsCount }} file{{ $materialsCount > 1 ? 's' : '' }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $lesson->created_at ? $lesson->created_at->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.lessons.show', $lesson) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.lessons.status', $lesson->id) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm" style="width: 100px;">
                                        <option value="Draft" {{ $lesson->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="Published" {{ $lesson->status === 'Published' ? 'selected' : '' }}>Publish</option>
                                    </select>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">No lessons found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($lessons) && $lessons->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $lessons->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    :root {
        --brand: #0b3c77;
        --brand-700: #093362;
        --brand-600: #0c4b94;
        --ink: #0f172a;
        --muted: #6b7280;
        --card-shadow: 0 10px 30px rgba(11, 60, 119, 0.08);
        --soft: #f6f8fb;
        --good: #16a34a;
        --warn: #d97706;
        --bad: #dc2626;
    }

    body {
        background: var(--soft);
    }

    .navbar {
        background: var(--brand);
    }

    .navbar .navbar-brand,
    .navbar .nav-link,
    .navbar .form-control::placeholder {
        color: #fff;
    }

    .navbar .nav-link {
        opacity: 0.9;
    }

    .navbar .nav-link:hover {
        opacity: 1;
    }

    .brand-badge {
        background: #fff;
        color: var(--brand);
        font-weight: 700;
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
    }

    .kpi-card {
        border: 0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: var(--card-shadow);
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: var(--brand);
        box-shadow: 0 6px 16px rgba(11, 60, 119, 0.25);
    }

    .kpi-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--ink);
    }

    .kpi-label {
        color: var(--muted);
        font-weight: 600;
    }

    .table thead th {
        color: var(--muted);
        font-weight: 700;
        border-bottom: 1px solid #e5e7eb;
    }

    .table>tbody>tr>td {
        vertical-align: middle;
    }

    .btn-brand {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }

    .btn-brand:hover {
        background: var(--brand-700);
        border-color: var(--brand-700);
    }

    .filter-bar {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
    }

    .nav-tabs .nav-link {
        border: none;
        color: var(--muted);
    }

    .nav-tabs .nav-link.active {
        background: var(--brand);
        color: #fff;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .section-title {
        color: var(--ink);
        font-weight: 800;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .section-title i {
        color: var(--brand);
    }

    .tab-section-header {
        border-bottom: 2px solid var(--soft);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

</x-admin-layout>
