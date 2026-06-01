<x-admin-layout>
    @include('admin.courses.partials.alerts')

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Course Reviews</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.manage.courses') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.show', $course->id) }}">{{ Str::limit($course->title, 30) }}</a></li>
                    <li class="breadcrumb-item active">Reviews</li>
                </ol>
            </nav>
        </div>
        @include('admin.courses.partials.actions')
    </div>

    @include('admin.courses.partials.nav')

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="kpi-label">Total Reviews</div>
                <div class="kpi-value">{{ number_format($stats['review_count']) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="kpi-label">Average Rating</div>
                <div class="kpi-value">{{ $stats['avg_rating'] ?? '—' }} <small class="text-muted fs-6">/ 5</small></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="kpi-label">Low Ratings (≤ 2)</div>
                <div class="kpi-value">{{ $reviews->where('rating', '<=', 2)->count() }}</div>
            </div>
        </div>
    </div>

    <div class="kpi-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="section-title mb-0"><i class="bi bi-star-half me-2"></i>Student Reviews</h5>
            <span class="text-muted small">{{ $reviews->count() }} records</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle modern-table data-table w-100">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="fw-semibold">{{ $review->student?->full_name ?? 'Unknown' }}</td>
                            <td>{{ $review->student?->email ?? 'N/A' }}</td>
                            <td data-order="{{ $review->rating }}">
                                <span class="badge text-bg-{{ $review->rating >= 4 ? 'success' : ($review->rating >= 3 ? 'warning' : 'danger') }}-subtle">
                                    {{ $review->rating }} / 5
                                </span>
                            </td>
                            <td>{{ $review->comment ? Str::limit($review->comment, 120) : '—' }}</td>
                            <td data-order="{{ $review->created_at?->timestamp ?? 0 }}">
                                {{ $review->created_at?->format('M d, Y H:i') ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No reviews found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('admin.courses.partials.reject-modal')

    @include('admin.courses.partials.datatables')

    @push('styles')
        @include('admin.courses.partials.styles')
    @endpush
</x-admin-layout>
