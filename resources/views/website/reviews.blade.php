<x-guest-layout>
    <section class="py-5" style="background: linear-gradient(135deg, var(--primary-cyan), var(--dark-cyan)); color: #fff;">
        <div class="container text-center">
            <h1 class="fw-bold mb-2">Reviews</h1>
            <p class="mb-0 opacity-75">Real feedback from students across courses and educators.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small">Course reviews</div>
                                    <div class="fw-bold fs-4">{{ $courseReviews->count() }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted small">Average rating</div>
                                    <div class="fw-bold fs-4">{{ number_format($courseReviewsAvg ?? 0, 1) }}★</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small">Educator reviews</div>
                                    <div class="fw-bold fs-4">{{ $educatorReviews->count() }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted small">Average rating</div>
                                    <div class="fw-bold fs-4">{{ number_format($educatorReviewsAvg ?? 0, 1) }}★</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Course Reviews -->
                <div class="col-lg-6">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="fw-bold mb-0" style="color: var(--dark-cyan);">Course reviews</h2>
                    </div>

                    @forelse ($courseReviews as $review)
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $review->student->full_name ?? $review->student->name ?? 'Student' }}
                                        </div>
                                        @if ($review->course)
                                            <div class="text-muted small">
                                                on
                                                <a class="text-decoration-none"
                                                    href="{{ route('web.course.show', ['slug' => $review->course->slug, 'id' => $review->course->id]) }}">
                                                    {{ $review->course->title }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-end">
                                        <div class="small text-muted">{{ number_format($review->rating ?? 0, 1) }}</div>
                                        <div class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= round($review->rating ?? 0) ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                @if (!empty($review->comment))
                                    <p class="mb-0 mt-2 text-muted">{{ $review->comment }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light border">
                            No course reviews yet.
                        </div>
                    @endforelse
                </div>

                <!-- Educator Reviews -->
                <div class="col-lg-6">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="fw-bold mb-0" style="color: var(--dark-cyan);">Educator reviews</h2>
                    </div>

                    @forelse ($educatorReviews as $review)
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $review->student->full_name ?? $review->student->name ?? 'Student' }}
                                        </div>
                                        @if ($review->educator)
                                            <div class="text-muted small">
                                                for
                                                <a class="text-decoration-none"
                                                    href="{{ route('web.educator.show', $review->educator->id) }}">
                                                    {{ $review->educator->full_name ?? $review->educator->name ?? 'Educator' }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-end">
                                        <div class="small text-muted">{{ number_format($review->rating ?? 0, 1) }}</div>
                                        <div class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= round($review->rating ?? 0) ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                @if (!empty($review->comment))
                                    <p class="mb-0 mt-2 text-muted">{{ $review->comment }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light border">
                            No educator reviews yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>

