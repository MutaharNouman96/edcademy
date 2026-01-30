<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Course Details</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.manage.courses') }}">Courses</a></li>
                <li class="breadcrumb-item active">{{ $course->title }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.manage.courses') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Courses
        </a>
        @if($course->approval_status !== 'approved')
        <form method="POST" action="{{ route('admin.courses.approve', $course->id) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-lg me-1"></i>Approve Course
            </button>
        </form>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="bi bi-x-lg me-1"></i>Reject Course
        </button>
        @endif
        <form method="POST" action="{{ route('admin.courses.status', $course->id) }}" class="d-inline">
            @csrf
            @method('PATCH')
            <select name="status" onchange="this.form.submit()" class="form-select">
                <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ $course->status === 'published' ? 'selected' : '' }}>Published</option>
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
                    @if($course->thumbnail)
                    <img src="{{ $course->thumbnail_path }}" alt="thumbnail" class="rounded" style="width: 120px; height: 90px; object-fit: cover;">
                    @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 120px; height: 90px;">
                        <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                    </div>
                    @endif
                    <div class="flex-grow-1">
                        <h3 class="mb-2">{{ $course->title }}</h3>
                        <div class="d-flex gap-2 mb-2">
                            <span class="badge bg-secondary">{{ ucfirst($course->level ?? 'N/A') }}</span>
                            <span class="badge text-bg-{{ $course->status === 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($course->status) }}
                            </span>
                            <span class="badge text-bg-{{ $course->approval_status === 'approved' ? 'success' : ($course->approval_status === 'rejected' ? 'danger' : 'info') }}">
                                {{ ucfirst($course->approval_status ?? 'pending') }}
                            </span>
                            @if($course->price == 0)
                            <span class="badge bg-success">Free</span>
                            @endif
                        </div>
                        <p class="text-muted mb-0">{{ Str::limit(strip_tags($course->description), 200) }}</p>
                    </div>
                </div>

                <!-- Course Details -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <strong>Educator:</strong> {{ $course->educator ? $course->educator->full_name : 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Price:</strong>
                        @if($course->price > 0)
                            AED {{ number_format($course->price, 0) }}
                        @else
                            Free
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Subject:</strong> {{ $course->subject ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Language:</strong> {{ $course->language ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Duration:</strong> {{ $course->duration ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Created:</strong> {{ $course->created_at->format('M d, Y') }}
                    </div>
                </div>

                <!-- Sections and Lessons -->
                @if($course->sections && $course->sections->count() > 0)
                <div class="border-top pt-4">
                    <h5 class="mb-3">Course Content</h5>
                    <div class="accordion" id="courseSections">
                        @foreach($course->sections as $section)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#section{{ $section->id }}">
                                    {{ $section->title }}
                                    <span class="badge bg-primary ms-2">{{ $section->lessons ? $section->lessons->count() : 0 }} lessons</span>
                                </button>
                            </h2>
                            <div id="section{{ $section->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
                                <div class="accordion-body">
                                    @if($section->lessons && $section->lessons->count() > 0)
                                    <ul class="list-group list-group-flush">
                                        @foreach($section->lessons as $lesson)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $lesson->title ?? $lesson->name }}</strong>
                                                @if($lesson->description)
                                                <br><small class="text-muted">{{ Str::limit(strip_tags($lesson->description), 100) }}</small>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-1">
                                                <span class="badge bg-secondary">{{ ucfirst($lesson->type ?? 'N/A') }}</span>
                                                @if($lesson->free)
                                                <span class="badge bg-success">Free</span>
                                                @else
                                                <span class="badge bg-primary">Paid : {{ number_format($lesson->price, 0)}}</span>

                                                @endif
                                                
                                            </div>
                                       
                    
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <p class="text-muted mb-0">No lessons in this section.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="border-top pt-4">
                    <p class="text-muted mb-0">No sections added yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Course Stats -->
        <div class="kpi-card mb-4">
            <div class="p-3">
                <h6 class="mb-3">Course Statistics</h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="kpi-value">{{ $course->sections ? $course->sections->count() : 0 }}</div>
                            <div class="kpi-label">Sections</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="kpi-value">{{ $course->sections ? $course->sections->sum(function($section) { return $section->lessons ? $section->lessons->count() : 0; }) : 0 }}</div>
                            <div class="kpi-label">Lessons</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="kpi-card">
            <div class="p-3">
                <h6 class="mb-3">Additional Information</h6>
                <dl class="row">
                    <dt class="col-sm-5">Category:</dt>
                    <dd class="col-sm-7">{{ $course->category ? $course->category->name : 'N/A' }}</dd>

                    <dt class="col-sm-5">Difficulty:</dt>
                    <dd class="col-sm-7">{{ ucfirst($course->difficulty ?? 'N/A') }}</dd>

                    <dt class="col-sm-5">Type:</dt>
                    <dd class="col-sm-7">{{ ucfirst($course->type ?? 'N/A') }}</dd>

                    <dt class="col-sm-5">Approval Status:</dt>
                    <dd class="col-sm-7">{{ ucfirst($course->approval_status ?? 'pending') }}</dd>

                    @if($course->review_note)
                    <dt class="col-sm-5">Review Note:</dt>
                    <dd class="col-sm-7">{{ $course->review_note }}</dd>
                    @endif

                    @if($course->schedule_date)
                    <dt class="col-sm-5">Schedule Date:</dt>
                    <dd class="col-sm-7">{{ \Carbon\Carbon::parse($course->schedule_date)->format('M d, Y H:i') }}</dd>
                    @endif

                    @if($course->publish_date)
                    <dt class="col-sm-5">Publish Date:</dt>
                    <dd class="col-sm-7">{{ \Carbon\Carbon::parse($course->publish_date)->format('M d, Y H:i') }}</dd>
                    @endif
                </dl>
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

    .kpi-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--ink);
    }

    .kpi-label {
        color: var(--muted);
        font-weight: 600;
    }

    .accordion-button:not(.collapsed) {
        background-color: rgba(11, 60, 119, 0.1);
        color: var(--brand);
    }

    .list-group-item {
        border: 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .list-group-item:last-child {
        border-bottom: 0;
    }
</style>
@endpush

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.courses.reject', $course->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="review_note" class="form-label">Review Note <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="review_note" name="review_note" rows="4" required placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Course</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-admin-layout>