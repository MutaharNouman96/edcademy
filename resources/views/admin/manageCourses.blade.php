<x-admin-layout>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="section-title mb-1"><i class="bi bi-mortarboard me-2"></i>Manage Courses</h4>
        <p class="text-muted small mb-0">Review, approve and publish courses submitted by educators</p>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Total Courses</div>
                    <div class="kpi-value">{{ $stats['total'] ?? 0 }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-collection"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Published</div>
                    <div class="kpi-value">{{ $stats['published'] ?? 0 }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-check-circle"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Pending Approval</div>
                    <div class="kpi-value">{{ $stats['pending'] ?? 0 }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-hourglass-split"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Approved</div>
                    <div class="kpi-value">{{ $stats['approved'] ?? 0 }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-patch-check"></i></span>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<form method="GET" action="{{ route('admin.manage.courses') }}" class="filter-bar p-3 mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-12 col-lg-4">
            <label class="form-label fw-semibold">Search</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0"
                    placeholder="Search by course title, description or educator name">
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
            </select>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <label class="form-label fw-semibold">Level</label>
            <select name="level" class="form-select">
                <option value="">All Levels</option>
                <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
            </select>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <label class="form-label fw-semibold">Approval</label>
            <select name="approval_status" class="form-select">
                <option value="">All Approvals</option>
                <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="d-flex gap-2">
                <button class="btn btn-brand flex-fill"><i class="bi bi-funnel me-1"></i>Apply</button>
                <a href="{{ route('admin.manage.courses') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </div>
    </div>
</form>

<div class="kpi-card p-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>#</th>
                <th>Course</th>
                <th>Educator</th>
                <th>Level</th>
                <th>Price</th>
                <th>Status</th>
                <th>Approval</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
                @forelse($courses as $index => $course)
                    <tr>
                        <td>{{ ($courses->firstItem() ?? 1) + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($course->thumbnail)
                                    <img src="{{ $course->thumbnail_path }}" alt="thumbnail" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ Str::limit($course->title, 30) }}</strong>
                                    @if($course->description)
                                        <br><small class="text-muted">{{ Str::limit(strip_tags($course->description), 40) }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $course->educator ? $course->educator->full_name : 'N/A' }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($course->level ?? 'N/A') }}</span>
                        </td>
                        <td>
                            @if($course->price > 0)
                                $ {{ number_format($course->price, 0) }}
                            @else
                                Free
                            @endif
                        </td>
                        <td>
                            {{-- Inline editable status (moved out of the Actions menu into its own column) --}}
                            <form method="POST" action="{{ route('admin.courses.status', $course->id) }}">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                    class="form-select form-select-sm status-select status-{{ $course->status }}"
                                    style="width: 130px;">
                                    <option value="published" {{ $course->status === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    @if($course->status === 'scheduled')
                                        <option value="scheduled" selected>Scheduled</option>
                                    @endif
                                </select>
                            </form>
                        </td>
                        <td>
                            <span class="badge text-bg-{{ $course->approval_status === 'approved' ? 'success' : ($course->approval_status === 'rejected' ? 'danger' : 'info') }}">
                                {{ ucfirst($course->approval_status ?? 'pending') }}
                            </span>
                            @if($course->approval_status === 'rejected' && $course->review_note)
                                <i class="bi bi-info-circle text-muted ms-1" data-bs-toggle="tooltip"
                                   title="{{ $course->review_note }}"></i>
                            @endif
                        </td>
                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i> Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="{{ route('admin.courses.show', $course->id) }}" class="dropdown-item">
                                            <i class="bi bi-eye me-2"></i> View Course
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.courses.approve', $course->id) }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-success"
                                                {{ $course->approval_status === 'approved' ? 'disabled' : '' }}>
                                                <i class="bi bi-check2-circle me-2"></i> Approve Course
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item text-warning js-reject-course"
                                            data-action="{{ route('admin.courses.reject', $course->id) }}"
                                            data-title="{{ $course->title }}"
                                            data-note="{{ $course->review_note }}"
                                            data-bs-toggle="modal" data-bs-target="#rejectCourseModal">
                                            <i class="bi bi-x-circle me-2"></i> Reject Course
                                        </button>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.courses.delete', $course->id) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this course? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i> Delete Course
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No courses found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $courses->links() }}
        </div>
    @endif
</div>

<!-- Reject Course Modal -->
<div class="modal fade" id="rejectCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="rejectCourseForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-x-circle text-warning me-2"></i>Reject Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-2">You are rejecting: <strong id="rejectCourseTitle"></strong></p>
                    <label class="form-label fw-semibold">Reason / review note <span class="text-danger">*</span></label>
                    <textarea name="review_note" id="rejectCourseNote" class="form-control" rows="4" maxlength="1000"
                        placeholder="Explain what needs to be fixed before this course can be approved..." required></textarea>
                    <div class="form-text">This note is sent to the educator. Max 1000 characters.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white"><i class="bi bi-x-circle me-1"></i>Reject Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Populate the shared reject modal from the clicked course row.
            const rejectForm = document.getElementById('rejectCourseForm');
            const rejectTitle = document.getElementById('rejectCourseTitle');
            const rejectNote = document.getElementById('rejectCourseNote');

            document.querySelectorAll('.js-reject-course').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    rejectForm.setAttribute('action', this.dataset.action || '');
                    rejectTitle.textContent = this.dataset.title || '';
                    rejectNote.value = this.dataset.note || '';
                });
            });

            // Enable Bootstrap tooltips (rejection notes).
            if (window.bootstrap && bootstrap.Tooltip) {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                    new bootstrap.Tooltip(el);
                });
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        :root {
            --brand: #0b3c77;
            /* dark blue */
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

        .delta {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .delta.up {
            color: var(--good);
        }

        .delta.down {
            color: var(--bad);
        }

        .chip {
            border-radius: 9999px;
            padding: 0.25rem 0.6rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .chip.soft {
            background: rgba(11, 60, 119, 0.1);
            color: var(--brand);
        }

        .section-title {
            color: var(--ink);
            font-weight: 800;
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

        .progress.brand .progress-bar {
            background: var(--brand);
        }

        .dropdown-menu {
            min-width: 210px;
        }

        .section-title {
            color: var(--ink);
            font-weight: 800;
            font-size: 1.35rem;
        }

        .section-title i {
            color: var(--brand);
        }

        .input-group-text {
            border-right: 0;
        }

        /* Colour-code the inline status selector by its current value */
        .status-select {
            font-weight: 600;
            border-width: 1px;
        }

        .status-select.status-published {
            color: var(--good);
            border-color: rgba(22, 163, 74, 0.4);
        }

        .status-select.status-draft {
            color: var(--warn);
            border-color: rgba(217, 119, 6, 0.4);
        }

        .status-select.status-scheduled {
            color: var(--brand);
            border-color: rgba(11, 60, 119, 0.4);
        }
    </style>
@endpush
</x-admin-layout>
