<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $educator->full_name }} - Courses</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.manage.educators') }}">Educators</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.educators.show', $educator->id) }}">{{ $educator->full_name }}</a></li>
                <li class="breadcrumb-item active">Courses</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.educators.show', $educator->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Profile
        </a>
    </div>
</div>

<div class="kpi-card p-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Course</th>
                    <th>Category</th>
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
                        <td>{{ $course->category ? $course->category->name : 'N/A' }}</td>
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
                        <td>
                            <span class="badge text-bg-{{ $course->approval_status === 'approved' ? 'success' : ($course->approval_status === 'rejected' ? 'danger' : 'info') }}">
                                {{ ucfirst($course->approval_status ?? 'pending') }}
                            </span>
                        </td>
                        <td>{{ $course->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No courses found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

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

    .table thead th {
        color: var(--muted);
        font-weight: 700;
        border-bottom: 1px solid #e5e7eb;
    }

    .table>tbody>tr>td {
        vertical-align: middle;
    }
</style>
@endpush
</x-admin-layout>