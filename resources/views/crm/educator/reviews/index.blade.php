<x-educator-layout>
    <div class="container py-4">
        <h3 class="mb-4"><i class="bi bi-star-half me-2"></i>Reviews</h3>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="reviewTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="course-tab" data-bs-toggle="tab" data-bs-target="#courseReviews"
                    type="button" role="tab">
                    Course Reviews ({{ $courseReviews->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="educator-tab" data-bs-toggle="tab" data-bs-target="#educatorReviews"
                    type="button" role="tab">
                    Educator Reviews ({{ $educatorReviews->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content border border-top-0 rounded-bottom bg-white p-4" id="reviewTabsContent">
            <!-- Course Reviews -->
            <div class="tab-pane fade show active" id="courseReviews" role="tabpanel">
                @forelse ($courseReviews as $review)
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-1 text-primary">
                                    {{ $review->course->title ?? 'Deleted Course' }}
                                </h5>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $review->comment ?: 'No comment' }}</p>
                            <div class="text-warning mb-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">By {{ $review->student->full_name ?? 'Unknown' }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mt-3">No course reviews yet.</p>
                @endforelse
            </div>

            <!-- Educator Reviews -->
            <div class="tab-pane fade" id="educatorReviews" role="tabpanel">
                @forelse ($educatorReviews as $review)
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-1 text-primary">
                                    {{ $review->educator->name ?? 'Deleted Educator' }}
                                </h5>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $review->comment ?: 'No comment' }}</p>
                            <div class="text-warning mb-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">By {{ $review->student->name ?? 'Unknown' }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mt-3">No educator reviews yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-educator-layout>
