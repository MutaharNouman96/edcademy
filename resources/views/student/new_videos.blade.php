<x-student-layout>
    <div class="container">
        <h1>New Videos</h1>
        @if (count($newVideosFeed) > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Lesson</th>
                                <th scope="col">Course</th>
                                <th scope="col">Added</th>
                                <th scope="col">Duration</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($newVideosFeed as $video)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $video['lesson'] }}</div>
                                    </td>
                                    <td>{{ $video['course'] }}</td>
                                    <td>{{ $video['when'] }}</td>
                                    <td>{{ $video['duration'] }} minutes</td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-primary" href="{{ route('student.course_details', ['course_id' => $video['course_id']]) }}"><i class="bi bi-play-fill me-1"></i> Watch</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @else
            <p>No new videos have been added to your enrolled courses recently.</p>
        @endif
    </div>
</x-student-layout>
