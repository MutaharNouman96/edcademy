<x-educator-layout>
    <div class="container py-4">
        <!-- Course Header -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="mb-2">{{ $course->title }}</h1>
                        <p class="text-muted mb-2">
                            <i class="bi bi-person-circle"></i> {{ $course->educator->first_name }}
                            <span class="mx-2">|</span>
                            <i class="bi bi-folder"></i> {{ $course->category->name ?? 'N/A' }}
                            <span class="mx-2">|</span>
                            <i class="bi bi-calendar"></i> {{ $course->created_at->format('M d, Y') }}
                        </p>
                        <div>
                            <span
                                class="badge bg-{{ $course->status == 'published' ? 'success' : ($course->status == 'scheduled' ? 'info' : 'warning') }}">
                                {{ ucfirst($course->status) }}
                            </span>
                            @if ($course->difficulty)
                                <span class="badge bg-secondary">{{ ucfirst($course->difficulty) }}</span>
                            @endif
                            @if ($course->is_free)
                                <span class="badge bg-success">Free</span>
                            @else
                                <span class="badge bg-primary">${{ number_format($course->price, 2) }}</span>
                            @endif
                            <span class="badge bg-dark">{{ ucfirst($course->type) }}</span>
                        </div>
                    </div>
                    <div>
                        @can('update', $course)
                            <a href="{{ route('educator.courses.crud.edit', $course) }}" class="btn btn-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Course Thumbnail -->
                @if ($course->thumbnail)
                    <div class="card shadow-sm mb-4">
                        <img src="{{ Storage::url($course->thumbnail) }}" class="card-img-top"
                            alt="{{ $course->title }}">
                    </div>
                @endif

                <!-- Course Description -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Course Description</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $course->description }}</p>
                    </div>
                </div>

                <!-- Course Details -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Course Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Subject:</strong> {{ $course->subject }}
                            </div>
                            @if ($course->level)
                                <div class="col-md-6 mb-3">
                                    <strong>Level:</strong> {{ $course->level }}
                                </div>
                            @endif
                            @if ($course->language)
                                <div class="col-md-6 mb-3">
                                    <strong>Language:</strong> {{ $course->language }}
                                </div>
                            @endif
                            @if ($course->duration)
                                <div class="col-md-6 mb-3">
                                    <strong>Duration:</strong> {{ $course->duration }}
                                </div>
                            @endif
                            @if ($course->schedule_date)
                                <div class="col-md-6 mb-3">
                                    <strong>Scheduled:</strong>
                                    {{ \Carbon\Carbon::parse($course->schedule_date)->format('M d, Y g:i A') }}
                                </div>
                            @endif
                            @if ($course->drip)
                                <div class="col-md-12 mb-3">
                                    <strong>Drip Content:</strong> <span class="badge bg-info">Enabled</span>
                                    @if ($course->drip_duration)
                                        <span class="text-muted">({{ $course->drip_duration }})</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Course Curriculum -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-book"></i> Course Curriculum</h5>
                    </div>
                    <div class="card-body p-0">
                        @forelse($course->sections as $index => $section)
                            <div class="section-container border-bottom">
                                <div class="section-header bg-light p-3" data-bs-toggle="collapse"
                                    data-bs-target="#section-{{ $section->id }}" style="cursor: pointer;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="bi bi-chevron-down"></i>
                                            Section {{ $index + 1 }}: {{ $section->title }}
                                        </h6>
                                        <span class="badge bg-secondary">{{ $section->lessons->count() }}
                                            Lessons</span>
                                    </div>
                                </div>
                                <div class="collapse show" id="section-{{ $section->id }}">
                                    <div class="lessons-list">
                                        @forelse($section->lessons as $lessonIndex => $lesson)
                                            <div
                                                class="lesson-item p-3 border-bottom {{ $lessonIndex % 2 == 0 ? 'bg-white' : 'bg-light' }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="bi bi-play-circle me-2"></i>
                                                            <strong>{{ $lesson->title }}</strong>
                                                            @if ($lesson->free)
                                                                <span class="badge bg-success ms-2">Free</span>
                                                            @endif
                                                            @if ($lesson->preview)
                                                                <span class="badge bg-info ms-2">Preview</span>
                                                            @endif
                                                        </div>
                                                        <div class="small text-muted">
                                                            @if ($lesson->duration)
                                                                <i class="bi bi-clock"></i> {{ $lesson->duration }} min
                                                            @endif
                                                            @if ($lesson->video_link)
                                                                <span class="mx-2">|</span>
                                                                <i class="bi bi-camera-video"></i> Video
                                                            @endif
                                                            @if ($lesson->materials)
                                                                <span class="mx-2">|</span>
                                                                <i class="bi bi-file-earmark-pdf"></i> Materials
                                                            @endif
                                                            @if ($lesson->worksheets)
                                                                <span class="mx-2">|</span>
                                                                <i class="bi bi-file-earmark-text"></i> Worksheets
                                                            @endif
                                                        </div>
                                                        @if ($lesson->notes)
                                                            <p class="small text-muted mb-0 mt-2">
                                                                {{ Str::limit($lesson->notes, 100) }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="ms-3">
                                                        <span
                                                            class="badge bg-{{ $lesson->status == 'Published' ? 'success' : 'warning' }}">
                                                            {{ $lesson->status }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Lesson Resources (if lesson is free or preview) -->
                                                @if ($lesson->free || $lesson->preview)
                                                    <div class="mt-2 pt-2 border-top">
                                                        @if ($lesson->video_link)
                                                            <a href="{{ $lesson->video_link }}" target="_blank"
                                                                class="btn btn-sm btn-outline-primary me-2">
                                                                <i class="bi bi-play-circle"></i> Watch
                                                            </a>
                                                        @endif
                                                        @if ($lesson->materials)
                                                            <a href="{{ Storage::url($lesson->materials) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-secondary me-2">
                                                                <i class="bi bi-download"></i> Materials
                                                            </a>
                                                        @endif
                                                        @if ($lesson->worksheets)
                                                            <a href="{{ Storage::url($lesson->worksheets) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-secondary">
                                                                <i class="bi bi-download"></i> Worksheets
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="p-3 text-muted text-center">No lessons in this section</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                <p>No curriculum added yet</p>
                                @can('update', $course)
                                    <a href="{{ route('educator.courses.crud.edit', $course) }}"
                                        class="btn btn-primary">Add
                                        Content</a>
                                @endcan
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Reviews Section -->
                @if ($course->reviews->count() > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-star-fill text-warning"></i> Student Reviews</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($course->reviews->take(5) as $review)
                                <div class="review-item mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $review->user->name }}</strong>
                                            <div class="text-warning">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i
                                                        class="bi bi-star{{ $i < $review->rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                            @if ($review->comment)
                                                <p class="mb-0 mt-2">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-graph-up"></i> Course Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span><i class="bi bi-collection"></i> Sections</span>
                            <strong>{{ $course->sections->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span><i class="bi bi-play-circle"></i> Total Lessons</span>
                            <strong>{{ $course->lessons->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span><i class="bi bi-people"></i> Students</span>
                            <strong>{{ $course->coursePurchases->count() }}</strong>
                        </div>
                        @if ($course->reviews->count() > 0)
                            <div class="d-flex justify-content-between">
                                <span><i class="bi bi-star-fill text-warning"></i> Rating</span>
                                <strong>{{ number_format($course->reviews->avg('rating'), 1) }} / 5.0
                                    ({{ $course->reviews->count() }})</strong>
                            </div>
                        @endif
                    </div>
                </div>



                <!-- Additional Info -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Additional Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">Course Type</small>
                            <div><strong>{{ ucfirst($course->type) }}</strong></div>
                        </div>
                        @if ($course->status == 'published')
                            <div class="mb-2">
                                <small class="text-muted">Publish Now</small>

                            </div>
                        @endif
                        <div class="mb-2">
                            Approval Status : <span
                                class="badge bg-{{ $course->approval_status == 'approved' ? 'success' : ($course->approval_status == 'rejected' ? 'danger' : 'info') }}">{{ ucfirst($course->approval_status) }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Last Updated</small>
                            <div><strong>{{ $course->updated_at->format('M d, Y') }}</strong></div>
                        </div>
                    </div>
                </div>

                <!-- Instructor Card -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-person-badge"></i> Instructor</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; font-size: 1.5rem;">
                                {{ substr($course->educator->name, 0, 1) }}
                            </div>
                            <div class="ms-3">
                                <strong>{{ $course->educator->name }}</strong>
                                <div class="small text-muted">{{ $course->educator->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .section-header:hover {
                background-color: #e9ecef !important;
            }

            .lesson-item {
                transition: background-color 0.2s;
            }

            .lesson-item:hover {
                background-color: #f8f9fa !important;
            }
        </style>
    @endpush
</x-educator-layout>
