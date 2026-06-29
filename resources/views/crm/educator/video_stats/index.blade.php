<x-educator-layout>

    <div class="card p-4 mb-4">
        <form id="video-stat-filter-form" method="GET" action="{{ route('educator.video-stats.index') }}"
            class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <h5 class="mb-0"><i class="bi bi-bar-chart-line me-2"></i>Video Statistics</h5>
            <div class="d-flex gap-2">
                <select name="course_id" class="form-select form-select-sm" style="width:auto;"
                    onchange="document.getElementById('video-stat-filter-form').submit()">
                    <option value="">All Courses</option>
                    @foreach ($myCourses as $course)
                        <option value="{{ $course->id }}" @selected($courseId === $course->id)>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                <select class="form-select form-select-sm" name="date_range" style="width:auto;"
                    onchange="document.getElementById('video-stat-filter-form').submit()">
                    <option value="">All Time</option>
                    <option value="last_7_days" @selected($dateRange === 'last_7_days')>Last 7 Days</option>
                    <option value="last_30_days" @selected($dateRange === 'last_30_days')>Last 30 Days</option>
                </select>
            </div>
        </form>

        <!-- Stats Summary -->
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="fw-semibold text-secondary small">Total Views</div>
                    <div class="fs-4 fw-bold text-primary">{{ number_format($totalViews) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="fw-semibold text-secondary small">Average Watch Time</div>
                    <div class="fs-4 fw-bold text-primary">{{ $averageWatchTime }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="fw-semibold text-secondary small">Completion Rate</div>
                    <div class="fs-4 fw-bold text-success">{{ $completionRate }}%</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="fw-semibold text-secondary small">Top Performing Video</div>
                    <div class="fs-6 fw-bold text-dark">{{ $topPerformingVideo }}</div>
                </div>
            </div>
        </div>

        <hr class="my-4" />

        <!-- Views Over Time -->
        <div class="mb-4">
            <h6 class="fw-semibold mb-2">Views Over Time</h6>
            <div style="height:300px;">
                <canvas id="viewsOverTimeChart"></canvas>
            </div>
        </div>

        <!-- Table: Per-Video Breakdown -->
        <h6 class="fw-semibold mb-2">Per-Video Breakdown</h6>
        <div class="table-responsive">
            <table id="videoBreakdownTable" class="table align-middle data-table w-100">
                <thead class="table-light">
                    <tr>
                        <th>Lesson Title</th>
                        <th>Course</th>
                        <th>Views</th>
                        <th>Avg Watch Time</th>
                        <th>Completion</th>
                        <th>Likes</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($videoBreakdown as $row)
                        @php $lesson = $row['lesson']; @endphp
                        <tr>
                            <td>
                                @if ($lesson->lesson_video_path)
                                    <a href="{{ $lesson->lesson_video_path }}" target="_blank" rel="noopener noreferrer"
                                        class="fw-semibold text-decoration-none">
                                        {{ $lesson->title }}
                                        <i class="bi bi-play-circle ms-1"></i>
                                    </a>
                                @else
                                    <a href="{{ route('educator.courses.crud.show', $lesson->course_id) }}?action=content"
                                        class="fw-semibold text-decoration-none">
                                        {{ $lesson->title }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if ($lesson->course)
                                    <a href="{{ route('educator.courses.crud.show', $lesson->course_id) }}"
                                        class="text-decoration-none">
                                        {{ $lesson->course->title }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td data-order="{{ $row['views'] }}">{{ number_format($row['views']) }}</td>
                            <td data-order="{{ $row['avg_watch_time_seconds'] }}">{{ $row['avg_watch_time'] }}</td>
                            <td data-order="{{ $row['completion_rate'] }}">{{ $row['completion_rate'] }}%</td>
                            <td data-order="{{ $row['likes'] }}">{{ number_format($row['likes']) }}</td>
                            <td data-order="{{ $row['comments'] }}">{{ number_format($row['comments']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('viewsOverTimeChart');
                if (!ctx) {
                    return;
                }

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Views',
                            data: @json($chartData),
                            tension: 0.35,
                            borderColor: '#006b7d',
                            backgroundColor: 'rgba(0, 107, 125, 0.15)',
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                },
                            },
                        },
                    },
                });
            });
        </script>
    @endpush

</x-educator-layout>
