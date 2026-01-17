<x-admin-layout>

<h4 class="mb-3">Manage Courses</h4>

<form method="GET" action="{{ route('admin.manage.courses') }}" class="filter-bar p-3 mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Level</label>
            <select name="level" class="form-select">
                <option value="">All Levels</option>
                <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Search</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Course title, description, or educator name">
        </div>
        <div class="col-md-2 text-md-end">
            <button class="btn btn-brand">Apply Filters</button>
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
                <th>Created</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
                @forelse($courses as $index => $course)
                    <tr>
                        <td>{{ $index + 1 }}</td>
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
                                AED {{ number_format($course->price, 0) }}
                            @else
                                Free
                            @endif
                        </td>
                        <td>
                            <span class="badge text-bg-{{ $course->status === 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </td>
                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form method="POST" action="{{ route('admin.courses.status', $course->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm mb-2">
                                                <option value="published" {{ $course->status === 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                            </select>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.courses.delete', $course->id) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this course?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-1"></i> Delete Course
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No courses found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $courses->appends(request()->query())->links() }}
        </div>
    @endif
</div>

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
            min-width: 200px;
        }
    </style>
@endpush
</x-admin-layout>
