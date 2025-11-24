@extends('layouts.student')

@section('content')
    <main class="col-12 col-md-9 col-lg-12 p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h4 mb-0">My Courses</h2>
            <div class="input-group" style="max-width: 280px">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search"></i>
                </span>
                <input class="form-control" placeholder="Search courses..." />
            </div>
        </div>

        <div class="row g-3">
            @if (count($myCourses) > 0)
                @foreach ($myCourses as $course)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card course-card border-0 h-100">
                            <img src="{{ $course['thumb'] }}" class="card-img-top rounded-top" alt="{{ $course['title'] }}" />
                            <div class="card-body d-flex flex-column">
                                <h6 class="mb-1">{{ $course['title'] }}</h6>
                                <div class="small text-muted mb-2">
                                    {{ $course['subject'] }} • Last viewed {{ $course['last'] }}
                                </div>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-primary" style="width: {{ round($course['progress'] * 100) }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between small mb-3">
                                    <span>{{ round($course['progress'] * 100) }}% complete</span><span>{{ $course['hours'] }} h watched</span>
                                </div>
                                <div class="mt-auto d-flex gap-2">
                                    <a class="btn btn-sm btn-primary w-100" href="{{ route('student.course-details', $course['id']) }}"><i class="bi bi-play-fill me-1"></i> Resume</a>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>You are not enrolled in any courses yet.</p>
            @endif
        </div>
    </main>
@endsection
