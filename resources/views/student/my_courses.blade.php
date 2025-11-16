@extends('layouts.student')

@section('content')
    <div class="container">
        <h1>My Courses</h1>
        @if (count($myCourses) > 0)
            <div class="row">
                @foreach ($myCourses as $course)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ $course['thumb'] }}" class="card-img-top" alt="{{ $course['title'] }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $course['title'] }}</h5>
                                <p class="card-text">Subject: {{ $course['subject'] }}</p>
                                <p class="card-text">Progress: {{ round($course['progress'] * 100) }}%</p>
                                <p class="card-text">Hours Watched: {{ $course['hours'] }}</p>
                                <p class="card-text">Last Viewed: {{ $course['last'] }}</p>
                                @if ($course['newVideos'] > 0)
                                    <p class="card-text text-success">{{ $course['newVideos'] }} new videos!</p>
                                @endif
                                <a href="#" class="btn btn-primary">Continue Learning</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>You are not enrolled in any courses yet.</p>
        @endif
    </div>
@endsection
