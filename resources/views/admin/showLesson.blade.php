<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Lesson Details</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.manage.lessons') }}">Lessons</a></li>
                <li class="breadcrumb-item active">{{ $lesson->title ?? $lesson->name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.manage.lessons') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Lessons
        </a>
        <form method="POST" action="{{ route('admin.lessons.status', $lesson->id) }}" class="d-inline">
            @csrf
            @method('PATCH')
            <select name="status" onchange="this.form.submit()" class="form-select">
                <option value="Draft" {{ $lesson->status === 'Draft' ? 'selected' : '' }}>Draft</option>
                <option value="Published" {{ $lesson->status === 'Published' ? 'selected' : '' }}>Published</option>
            </select>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="kpi-card">
            <div class="p-4">
                <div class="d-flex align-items-start gap-3 mb-4">
                    @if($lesson->thumbnail)
                    <img src="{{ asset($lesson->thumbnail) }}" alt="thumbnail" class="rounded" style="width: 120px; height: 90px; object-fit: cover;">
                    @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 120px; height: 90px;">
                        <i class="bi bi-play-circle text-muted" style="font-size: 2rem;"></i>
                    </div>
                    @endif
                    <div class="flex-grow-1">
                        <h3 class="mb-2">{{ $lesson->title ?? $lesson->name }}</h3>
                        <div class="d-flex gap-2 mb-2">
                            <span class="badge bg-secondary">{{ ucfirst($lesson->type ?? 'N/A') }}</span>
                            @if($lesson->preview)
                            <span class="badge bg-info">Preview Lesson</span>
                            @endif
                            @if($lesson->free)
                            <span class="badge bg-success">Free</span>
                            @else
                            <span class="badge bg-primary">AED {{ number_format($lesson->price, 2) }}</span>
                            @endif
                            <span class="badge text-bg-{{
                                $lesson->status === 'Published' ? 'success' :
                                ($lesson->status === 'Draft' ? 'warning' : 'secondary')
                            }}">
                                {{ $lesson->status ?? 'Draft' }}
                            </span>
                        </div>
                        @if($lesson->duration)
                        <p class="text-muted mb-0"><i class="bi bi-clock me-1"></i>{{ $lesson->duration }} minutes</p>
                        @endif
                    </div>
                </div>

                @if($lesson->description)
                <div class="mb-4">
                    <h5 class="section-title mb-3">Description</h5>
                    <div class="text-muted">
                        {!! nl2br(e($lesson->description)) !!}
                    </div>
                </div>
                @endif

                <!-- Video Content -->
                @if($lesson->video_path || $lesson->video_link)
                <div class="mb-4">
                    <h5 class="section-title mb-3">Video Content</h5>
                    @if($lesson->video_path)
                    <div class="video-container mb-3">
                        <video controls class="w-100 rounded" style="max-height: 400px;">
                            <source src="{{ asset($lesson->video_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    @endif
                    @if($lesson->video_link)
                    <div class="alert alert-info">
                        <i class="bi bi-link-45deg me-2"></i>
                        <strong>External Video Link:</strong>
                        <a href="{{ $lesson->video_link }}" target="_blank" class="alert-link">{{ $lesson->video_link }}</a>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Notes -->
                @if($lesson->notes)
                <div class="mb-4">
                    <h5 class="section-title mb-3">Notes</h5>
                    <div class="bg-light p-3 rounded text-muted">
                        {!! nl2br(e($lesson->notes)) !!}
                    </div>
                </div>
                @endif

                <!-- Popular Content -->
                @if($lesson->popular)
                <div class="mb-4">
                    <h5 class="section-title mb-3">Popular Content</h5>
                    <div class="text-muted">
                        {!! nl2br(e($lesson->popular)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Course & Section Info -->
        <div class="kpi-card mb-4">
            <div class="p-3 border-bottom">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Lesson Information</h6>
            </div>
            <div class="p-3">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Course</label>
                    <p class="mb-0">{{ $lesson->course ? $lesson->course->title : 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Section</label>
                    <p class="mb-0">{{ $lesson->courseSection ? $lesson->courseSection->title : 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Educator</label>
                    <p class="mb-0">{{ $lesson->course && $lesson->course->educator ? $lesson->course->educator->full_name : 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Created</label>
                    <p class="mb-0">{{ $lesson->created_at ? $lesson->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                </div>
                @if($lesson->published_at)
                <div class="mb-3">
                    <label class="form-label fw-semibold">Published</label>
                    <p class="mb-0">{{ $lesson->published_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
                <div class="mb-0">
                    <label class="form-label fw-semibold">Order</label>
                    <p class="mb-0">{{ $lesson->order ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Materials -->
        @if($lesson->materials || $lesson->worksheets || $lesson->resources || $lesson->assignments)
        <div class="kpi-card mb-4">
            <div class="p-3 border-bottom">
                <h6 class="mb-0"><i class="bi bi-file-earmark me-2"></i>Materials & Resources</h6>
            </div>
            <div class="p-3">
                @if($lesson->materials)
                <div class="mb-3">
                    <label class="form-label fw-semibold">Materials</label>
                    <div class="text-break small">
                        <i class="bi bi-file-earmark-text me-1"></i>
                        <a href="{{ asset($lesson->materials) }}" target="_blank" class="text-decoration-none">{{ basename($lesson->materials) }}</a>
                    </div>
                </div>
                @endif

                @if($lesson->worksheets)
                <div class="mb-3">
                    <label class="form-label fw-semibold">Worksheets</label>
                    <div class="text-break small">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i>
                        <a href="{{ asset($lesson->worksheets) }}" target="_blank" class="text-decoration-none">{{ basename($lesson->worksheets) }}</a>
                    </div>
                </div>
                @endif

                @if($lesson->resources)
                <div class="mb-3">
                    <label class="form-label fw-semibold">Resources</label>
                    <div class="text-break small">
                        <i class="bi bi-file-earmark-zip me-1"></i>
                        <a href="{{ asset($lesson->resources) }}" target="_blank" class="text-decoration-none">{{ basename($lesson->resources) }}</a>
                    </div>
                </div>
                @endif

                @if($lesson->assignments)
                <div class="mb-3">
                    <label class="form-label fw-semibold">Assignments</label>
                    <div class="text-break small">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        <a href="{{ asset($lesson->assignments) }}" target="_blank" class="text-decoration-none">{{ basename($lesson->assignments) }}</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="kpi-card">
            <div class="p-3 border-bottom">
                <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Quick Actions</h6>
            </div>
            <div class="p-3">
                <div class="d-grid gap-2">
                    <form method="POST" action="{{ route('admin.lessons.status', $lesson->id) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $lesson->status === 'Published' ? 'Draft' : 'Published' }}">
                        <button type="submit" class="btn btn-outline-{{ $lesson->status === 'Published' ? 'warning' : 'success' }} w-100">
                            <i class="bi bi-{{ $lesson->status === 'Published' ? 'eye-slash' : 'eye' }} me-1"></i>
                            {{ $lesson->status === 'Published' ? 'Unpublish' : 'Publish' }} Lesson
                        </button>
                    </form>
                </div>
            </div>
        </div>
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

    .kpi-card {
        border: 0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: var(--card-shadow);
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

    .video-container video {
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 0;
    }

    .breadcrumb-item a {
        color: var(--brand);
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: var(--muted);
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
</style>
@endpush

</x-admin-layout>
