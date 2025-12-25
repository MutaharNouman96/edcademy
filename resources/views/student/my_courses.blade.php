<x-student-layout>
    <main class="col-12 col-md-9 col-lg-12 p-4">
        <div class="container py-4">

            {{-- Stats --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4>{{ $stats['courses'] }}</h4>
                            <small class="text-muted">Courses Purchased</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4>{{ $stats['videos'] }}</h4>
                            <small class="text-muted">Video Lessons</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4>{{ $stats['worksheets'] }}</h4>
                            <small class="text-muted">Worksheets</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#courses">
                        My Courses
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lessons">
                        My Lessons
                    </button>
                </li>
            </ul>

            <div class="tab-content">

                {{-- COURSES TAB --}}
                <div class="tab-pane fade show active" id="courses">
                    <div class="row g-3">
                        @forelse($courses as $course)
                            <div class="col-sm-6 col-lg-4">
                                <div class="card course-card border-0 h-100">
                                    <img src="{{ $course->thumbnail_url }}" class="card-img-top rounded-top"
                                        alt="{{ $course->title }}">

                                    <div class="card-body d-flex flex-column">
                                        <h6 class="mb-1">{{ $course->title }}</h6>

                                        <div class="small text-muted mb-2">
                                            {{ $course->subject->name ?? 'General' }}
                                        </div>

                                        <div class="progress mb-2">
                                            <div class="progress-bar bg-primary"
                                                style="width: {{ round($course->progress * 100) }}%">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between small mb-3">
                                            <span>{{ round($course->progress * 100) }}% complete</span>
                                            <span>{{ $course->hours_watched }} h watched</span>
                                        </div>

                                        <div class="mt-auto">
                                            <a href="{{ route('student.course_details', $course->id) }}"
                                                class="btn btn-sm btn-primary w-100">
                                                <i class="bi bi-play-fill me-1"></i> Resume
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center">No courses purchased yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- LESSONS TAB --}}
                <div class="tab-pane fade" id="lessons">

                    <h5 class="mb-4">All Lessons</h5>

                    <div class="row g-4">
                        @forelse($lessons as $lesson)
                        
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body d-flex flex-column">
                                        {{-- Lesson type badge --}}
                                        <span
                                            class="badge bg-{{ $lesson->type === 'video' ? 'primary' : 'secondary' }} mb-2">
                                            {{ ucfirst($lesson->type) }}
                                        </span>

                                        {{-- Lesson title --}}
                                        <h6 class="card-title">{{ $lesson->title }}</h6>

                                        {{-- Course info --}}
                                        @if ($lesson->course)
                                            <p class="text-muted mb-2">
                                                Course: <strong>{{ $lesson->course->title }}</strong>
                                            </p>
                                        @endif

                                        {{-- Duration & Price --}}
                                        <p class="mb-2">
                                            @if ($lesson->duration)
                                                <span>⏱ {{ $lesson->duration }} min</span>
                                            @endif
                                            @if ($lesson->price && !$lesson->free)
                                                <span class="ms-2"> ${{ number_format($lesson->price, 2) }}</span>
                                            @elseif($lesson->free)
                                                <span class="ms-2 badge bg-success">Free</span>
                                            @endif
                                        </p>

                                        {{-- Action buttons --}}
                                        <div class="mt-auto">
                                            @if ($lesson->type === 'video')
                                                @php
                                                    $videoUrl =
                                                        $lesson->video_link ?? asset('storage/' . $lesson->video_path);
                                                @endphp
                                                <a href="{{ route('student.course_details', ['course_id' => $lesson->course->id, 'lesson_id'=> $lesson->id]) }}"
                                                    class="btn btn-sm btn-primary w-100 mb-1">
                                                    Watch Video
                                                </a>
                                            @elseif($lesson->type === 'worksheet')
                                                @php
                                                    $worksheetFile = isset($lesson->worksheets)
                                                        ? asset('storage/' . $lesson->worksheets)
                                                        : '#';
                                                @endphp
                                                <a href="{{ $worksheetFile }}" class="btn btn-sm btn-secondary w-100"
                                                    download>
                                                    Download Worksheet
                                                </a>
                                            @endif

                                            {{-- Preview note --}}
                                            @if ($lesson->preview)
                                                <small class="text-info mt-1 d-block">Preview available</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-light text-center">
                                    No lessons available.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </main>
</x-student-layout>
