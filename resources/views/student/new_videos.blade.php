@extends('layouts.student')

@section('content')
    <div class="container">
        <h1>New Videos</h1>
        @if (count($newVideosFeed) > 0)
            <div class="row">
                @foreach ($newVideosFeed as $video)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $video['lesson'] }}</h5>
                                <p class="card-text">Course: {{ $video['course'] }}</p>
                                <p class="card-text">Added: {{ $video['when'] }}</p>
                                <p class="card-text">Duration: {{ $video['duration'] }}</p>
                                <a href="#" class="btn btn-primary">Watch Video</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>No new videos have been added to your enrolled courses recently.</p>
        @endif
    </div>
@endsection
