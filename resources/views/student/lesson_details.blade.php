<x-student-layout>
    <div class="container py-4">

        {{-- Breadcrumb --}}
        <nav class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item">
                    <a href="{{ route('student.my-courses') }}">My Library</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('student.course_details', $lesson->course_id) }}">
                        {{ $lesson->course->title }}
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ $lesson->title }}</li>
            </ol>
        </nav>

        <div class="row g-4">

            {{-- MAIN CONTENT --}}
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm">

                    <div class="card-body">
                        <h4 class="mb-3">{{ $lesson->title }}</h4>

                        {{-- VIDEO --}}
                        @if ($lesson->type === 'video')
                            <div class="ratio ratio-16x9 rounded overflow-hidden bg-dark">
                                <video controls class="w-100 h-100" controlsList="nodownload"
                                    poster="{{ $lesson->thumbnail_url ?? '' }}">
                                    <source src="{{ $lesson->video_url }}" type="video/mp4">
                                    Your browser does not support HTML5 video.
                                </video>
                            </div>
                        @endif

                        {{-- WORKSHEET / PDF --}}
                        @if ($lesson->type === 'worksheet')
                            <div class="ratio ratio-4x3 border rounded overflow-hidden">
                                <iframe src="{{ asset('storage/' . $lesson->file_path) }}#toolbar=0" class="w-100 h-100"
                                    frameborder="0">
                                </iframe>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Description / Notes --}}
                @if ($lesson->description)
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-body">
                            <h6>Lesson Notes</h6>
                            <p class="text-muted mb-0">
                                {!! nl2br(e($lesson->description)) !!}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">Lesson Info</h6>
                        <ul class="list-unstyled small mb-0">
                            <li><strong>Type:</strong> {{ ucfirst($lesson->type) }}</li>
                            <li><strong>Duration:</strong> {{ $lesson->duration }} min</li>
                            <li><strong>Course:</strong> {{ $lesson->course->title }}</li>
                        </ul>

                        {{-- Optional Download --}}
                        @if ($lesson->type === 'worksheet')
                            <a href="{{ asset('storage/' . $lesson->file_path) }}"
                                class="btn btn-sm btn-outline-primary w-100 mt-3" download>
                                <i class="bi bi-download me-1"></i> Download PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-student-layout>
