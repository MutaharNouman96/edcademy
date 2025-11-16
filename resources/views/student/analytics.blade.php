@extends('layouts.student')

@section('content')
    <div class="container">
        <h1>Analytics</h1>
        <p>This page will display your course analytics.</p>

        @if (count($courseCompletionData) > 0)
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Overall Completion Rate</h5>
                            <p class="card-text">{{ round($completionRate) }}%</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Hours Watched</h5>
                            <p class="card-text">{{ $hoursWatched }} hours</p>
                        </div>
                    </div>
                </div>
            </div>

            <h3>Course Progress</h3>
            <div class="row">
                @foreach ($courseCompletionData as $courseProgress)
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $courseProgress['course_title'] }}</h5>
                                <p class="card-text">Completion: {{ round($courseProgress['completion_percentage']) }}%</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Completion Rate</h5>
                            <p class="card-text">{{ $completionRate }}%</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Hours Watched</h5>
                            <p class="card-text">{{ $hoursWatched }} hours</p>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Course Progress</h3>
            <p>No course progress data available. Displaying static data for design preview:</p>
            <div class="row">
                @foreach ($courseCompletionData as $courseProgress)
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $courseProgress['course_title'] }}</h5>
                                <p class="card-text">Completion: {{ round($courseProgress['completion_percentage']) }}%</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection
