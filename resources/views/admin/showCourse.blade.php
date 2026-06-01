<x-admin-layout>
    @include('admin.courses.partials.alerts')

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Course Overview</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.manage.courses') }}">Courses</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($course->title, 40) }}</li>
                </ol>
            </nav>
        </div>
        @include('admin.courses.partials.actions')
    </div>

    @include('admin.courses.partials.nav')

    @php
        $allLessons = $course->sections ? $course->sections->flatMap->lessons : collect();
        $totalLessons = $allLessons->count();
        $activeLessons = $allLessons->where('active', true)->count();
    @endphp

    <div class="course-hero mb-4">
        <div class="d-flex align-items-start gap-3 flex-wrap">
            @if ($course->thumbnail)
                <img src="{{ $course->thumbnail_path }}" alt="thumbnail" class="rounded course-thumb">
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center course-thumb">
                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                </div>
            @endif
            <div class="flex-grow-1" style="min-width: 240px;">
                <h3 class="mb-2">{{ $course->title }}</h3>
                <div class="d-flex gap-2 mb-2 flex-wrap">
                    <span class="badge">{{ ucfirst($course->level ?? 'N/A') }}</span>
                    <span class="badge">{{ ucfirst($course->status) }}</span>
                    <span class="badge">{{ ucfirst($course->approval_status ?? 'pending') }}</span>
                    @if ($course->price == 0)
                        <span class="badge">Free</span>
                    @else
                        <span class="badge">$ {{ number_format($course->price, 0) }}</span>
                    @endif
                </div>
                <p class="mb-0 opacity-90">{{ Str::limit(strip_tags($course->description), 260) }}</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Unique Purchasers</div>
                        <div class="kpi-value">{{ number_format($stats['unique_purchasers']) }}</div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-people-fill"></i></span>
                </div>
                <small class="text-muted">{{ number_format($stats['active_enrollments']) }} active enrollments</small>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Total Revenue</div>
                        <div class="kpi-value">$ {{ number_format($stats['total_revenue'], 0) }}</div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-cash-coin"></i></span>
                </div>
                <small class="text-muted">{{ number_format($stats['completed_orders']) }} completed orders</small>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Educator Net</div>
                        <div class="kpi-value">$ {{ number_format($stats['educator_net'], 0) }}</div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-wallet2"></i></span>
                </div>
                <small class="text-muted">Platform fee: $ {{ number_format($stats['platform_commission'], 0) }}</small>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Average Rating</div>
                        <div class="kpi-value">{{ $stats['avg_rating'] ?? '—' }}</div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-star-half"></i></span>
                </div>
                <small class="text-muted">{{ number_format($stats['review_count']) }} reviews</small>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('admin.courses.purchases', $course->id) }}" class="summary-link-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">Purchases</div>
                        <div class="kpi-value">{{ number_format($stats['unique_purchasers']) }}</div>
                    </div>
                    <i class="bi bi-people fs-3 text-primary"></i>
                </div>
                <small class="text-muted">View enrolled students</small>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('admin.courses.revenue', $course->id) }}" class="summary-link-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">Revenue</div>
                        <div class="kpi-value">$ {{ number_format($stats['total_revenue'], 0) }}</div>
                    </div>
                    <i class="bi bi-cash-stack fs-3 text-success"></i>
                </div>
                <small class="text-muted">View sales transactions</small>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('admin.courses.reviews', $course->id) }}" class="summary-link-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">Reviews</div>
                        <div class="kpi-value">{{ number_format($stats['review_count']) }}</div>
                    </div>
                    <i class="bi bi-chat-square-quote fs-3 text-warning"></i>
                </div>
                <small class="text-muted">View student feedback</small>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('admin.courses.content', $course->id) }}" class="summary-link-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">Content</div>
                        <div class="kpi-value">{{ $totalLessons }}</div>
                    </div>
                    <i class="bi bi-collection-play fs-3 text-info"></i>
                </div>
                <small class="text-muted">{{ $activeLessons }} active lessons</small>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="kpi-card p-4">
                <h5 class="section-title mb-3"><i class="bi bi-info-circle me-2"></i>Course Details</h5>
                <div class="row g-3">
                    <div class="col-md-6 detail-row">
                        <i class="bi bi-person-badge"></i>
                        <span><strong>Educator:</strong>
                            @if ($course->educator)
                                <a href="{{ route('admin.educators.show', $course->educator->id) }}">{{ $course->educator->full_name }}</a>
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="col-md-6 detail-row">
                        <i class="bi bi-tag"></i>
                        <span><strong>Category:</strong> {{ $course->category?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6 detail-row">
                        <i class="bi bi-journal-text"></i>
                        <span><strong>Subject:</strong> {{ $course->subject ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6 detail-row">
                        <i class="bi bi-translate"></i>
                        <span><strong>Language:</strong> {{ $course->language ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6 detail-row">
                        <i class="bi bi-clock-history"></i>
                        <span><strong>Duration:</strong> {{ $course->duration ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6 detail-row">
                        <i class="bi bi-bar-chart-steps"></i>
                        <span><strong>Difficulty:</strong> {{ ucfirst($course->difficulty ?? 'N/A') }}</span>
                    </div>
                    <div class="col-md-6 detail-row">
                        <i class="bi bi-box"></i>
                        <span><strong>Type:</strong> {{ ucfirst($course->type ?? 'N/A') }}</span>
                    </div>
                    <div class="col-md-6 detail-row">
                        <i class="bi bi-calendar3"></i>
                        <span><strong>Created:</strong> {{ $course->created_at->format('M d, Y') }}</span>
                    </div>
                    @if ($course->publish_date)
                        <div class="col-md-6 detail-row">
                            <i class="bi bi-calendar-check"></i>
                            <span><strong>Published:</strong> {{ \Carbon\Carbon::parse($course->publish_date)->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                    @if ($course->schedule_date)
                        <div class="col-md-6 detail-row">
                            <i class="bi bi-calendar-event"></i>
                            <span><strong>Scheduled:</strong> {{ \Carbon\Carbon::parse($course->schedule_date)->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                </div>

                @if ($course->description)
                    <hr>
                    <h6 class="fw-bold mb-2">Description</h6>
                    <div class="text-muted">{!! nl2br(e(strip_tags($course->description))) !!}</div>
                @endif

                @if ($course->review_note)
                    <hr>
                    <div class="alert alert-danger mb-0">
                        <strong>Review note:</strong> {{ $course->review_note }}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="kpi-card p-3 mb-4">
                <h6 class="mb-3"><i class="bi bi-bar-chart me-2"></i>Content Snapshot</h6>
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="stat-box">
                            <div class="kpi-value">{{ $course->sections?->count() ?? 0 }}</div>
                            <div class="kpi-label">Sections</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <div class="kpi-value">{{ $totalLessons }}</div>
                            <div class="kpi-label">Lessons</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <div class="kpi-value text-success">{{ $activeLessons }}</div>
                            <div class="kpi-label">Active</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <div class="kpi-value">{{ number_format($stats['total_sales']) }}</div>
                            <div class="kpi-label">Units Sold</div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($course->features)
                <div class="kpi-card p-3">
                    <h6 class="mb-3"><i class="bi bi-stars me-2"></i>Course Features</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><i class="bi bi-{{ $course->features->drip_release ? 'check-circle text-success' : 'x-circle text-muted' }} me-2"></i>Drip release</li>
                        <li class="mb-2"><i class="bi bi-{{ $course->features->certificate ? 'check-circle text-success' : 'x-circle text-muted' }} me-2"></i>Certificate</li>
                        <li class="mb-2"><i class="bi bi-{{ $course->features->quizzes ? 'check-circle text-success' : 'x-circle text-muted' }} me-2"></i>Quizzes</li>
                        <li><i class="bi bi-{{ $course->features->downloads ? 'check-circle text-success' : 'x-circle text-muted' }} me-2"></i>Downloads</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    @include('admin.courses.partials.reject-modal')

    @push('styles')
        @include('admin.courses.partials.styles')
    @endpush
</x-admin-layout>
