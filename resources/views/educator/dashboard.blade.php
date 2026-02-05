<x-educator-layout>
    @if (!auth()->user()->hasVerifiedEmail())
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Your email address is not verified.
            <form class="ms-auto" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    Resend Verification Email
                </button>
            </form>
        </div>
    @endif

    @if (auth()->user()->educatorProfile->status == 'pending')
        <div class="alert alert-info d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Your educator profile is <strong>pending approval</strong>. You will be notified once the review is
            complete.
        </div>
    @endif
    <section id="section-overview" class="mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h2 class="h4 mb-0">Overview</h2>
            <div class="input-group" style="max-width: 320px;">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input id="globalSearch" class="form-control" placeholder="Search courses, videos, students..." />
            </div>
        </div>

        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card kpi-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="kpi-icon"><i class="bi bi-journal-richtext"></i></span>
                        <span class="pill badge-soft">Courses</span>
                    </div>
                    <div class="mt-3">
                        <div class="h3 mb-0" id="kpiCourses">{{ $totalCourses }}</div>
                        <small class="text-muted">
                            {{ $draftCourses }} drafts • {{ $publishedCourses }} published
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card kpi-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="kpi-icon"><i class="bi bi-people"></i></span>
                        <span class="pill badge-soft">Students</span>
                    </div>
                    <div class="mt-3">
                        <div class="h3 mb-0" id="kpiStudents">{{ number_format($totalStudents) }}</div>
                        <small class="text-muted">+{{ $newStudentsThisWeek }} this week</small>

                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card kpi-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="kpi-icon"><i class="bi bi-bar-chart"></i></span>
                        <span class="pill badge-soft">last 30 days views</span>
                    </div>
                    <div class="mt-3">
                        <div class="h3 mb-0" id="kpiViews">{{ number_format($totalViews7Days) }}</div>
                        <small class="text-muted">Avg watch {{ $avgWatchFormatted }}</small>

                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card kpi-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="kpi-icon"><i class="bi bi-wallet2"></i></span>
                        <span class="pill badge-soft">Balances</span>
                    </div>
                    <div class="mt-3">
                        <div>
                            <div class="d-flex justify-content-between"><span class="text-muted">Escrow</span>
                                <strong id="escrowBalance">${{ number_format($escrowBalance, 2) }}</strong>

                            </div>
                            <div class="d-flex justify-content-between"><span class="text-muted">Earned</span>
                                <strong id="earnedTotal">${{ number_format($earnedTotal, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Charts Row -->
    <section class="mb-4">
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Video views (last 30 days)</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary" id="refreshViews"><i
                                    class="bi bi-arrow-clockwise"></i></button>
                        </div>
                    </div>
                    <canvas id="viewsChart" height="200" class="mt-3"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card p-3 h-100">
                    <h5 class="mb-0">Revenue breakdown</h5>
                    <canvas id="revenueChart" height="100" class="mt-3"></canvas>
                    <div class="mt-2 small text-muted">Course sales, live sessions, and resources (last 30 days)</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses -->
    <section id="section-courses" class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h3 class="h5 mb-0">My Courses</h3>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm" style="max-width: 260px;">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input id="courseSearch" class="form-control" placeholder="Search title or subject" />
                </div>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createCourseModal"><i
                        class="bi bi-plus-lg me-1"></i> New Course</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle" id="coursesTable">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Enrollments</th>
                        <th>Rating</th>
                        <th>Updated</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestCourses as $course)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img class="avatar avatar-sm" src="{{ asset($course->thumbnail) }}"
                                            alt="{{ $course->title }}" />
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <a
                                            href="{{ route('educator.courses.show', $course) }}">{{ $course->title }}</a>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $course->subject }}</td>
                            <td>{{ $course->status }}</td>
                            <td>${{ $course->price }}</td>
                            <td>{{ $course->enrollments_count }}</td>
                            <td>{{ $course->rating }}</td>
                            <td>{{ $course->updated_at->diffForHumans() }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Actions">
                                    <a href="{{ route('educator.courses.show', $course) }}"
                                        class="btn btn-sm btn-primary"><i class="bi bi-box-arrow-up-right"></i></a>
                                    <a href="{{ route('educator.courses.edit', $course) }}"
                                        class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <button wire:click="confirmDelete({{ $course->id }})"
                                        class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <!-- Videos -->
    <section id="section-videos" class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h3 class="h5 mb-0">Recent Videos</h3>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#uploadVideoModal"><i class="bi bi-cloud-upload me-1"></i> Upload</button>
        </div>
        <div class="table-responsive">
            <table class="table align-middle" id="videosTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Views</th>
                        <th>Watch Time</th>
                        <th>Likes</th>
                        <th>Comments</th>
                        <th>Published At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestVideos as $video)
                        <tr>
                            <td>{{ $video->title }}</td>
                            <td>{{ $video->course->title }}</td>
                            <td>{{ $video->lesson_video_views->count() }}</td>
                            <td>{{ format_seconds($video->lesson_video_views->avg('watch_time')) }}</td>
                            <td>{{ $video->lesson_video_views->where('liked', 1)->count() }}</td>
                            <td>{{ $video->lesson_video_comments->count() }}</td>
                            <td>{{ $video->published_at ? $video->published_at->diffForHumans() : '' }}</td>
                            <td>
                                <a href="{{ route('educator.courses.show', $video->course) }}"
                                    class="btn btn-sm btn-primary"><i class="bi bi-box-arrow-up-right"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- <!-- Earnings & Escrow split -->
    <div class="row g-3">
        <section id="section-earnings" class="col-lg-6">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h3 class="h6 mb-0">Earnings</h3>
                    <a href="{{ route('educator.earnings') }}" class="small">View all</a>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        August 2025 payout
                        <span class="fw-semibold">$1,980.00</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        July 2025 payout
                        <span class="fw-semibold">$1,642.75</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Refunds (30 days)
                        <span class="text-danger">-$64.00</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Net (30 days)
                        <span class="fw-semibold">$2,188.12</span>
                    </li>
                </ul>
            </div>
        </section>
        <section id="section-escrow" class="col-lg-6">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h3 class="h6 mb-0">Escrow</h3>
                    <span class="pill bg-warning-subtle text-warning-emphasis"><i class="bi bi-info-circle me-1"></i>
                        Released 7 days after delivery</span>
                </div>
                <div class="d-flex justify-content-between"><span>Pending course sales</span><strong>$412.00</strong>
                </div>
                <div class="d-flex justify-content-between"><span>Upcoming live sessions</span><strong>$330.00</strong>
                </div>
                <hr />
                <div class="d-flex justify-content-between"><span>Total in Escrow</span><strong>$742.00</strong></div>
            </div>
        </section>
    </div>

    <!-- Payouts / Reviews / Messages quick panels -->
    <div class="row g-3 mt-1">
        <section id="section-payouts" class="col-lg-4">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h3 class="h6 mb-0">Payouts</h3>
                    <a href="#" class="small">Manage</a>
                </div>
                <p class="small text-muted mb-2">Next payout on <strong>Sep 30</strong> to <em>Stripe •••• 4242</em>.
                </p>
                <button class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-gear me-1"></i>
                    Configure</button>
            </div>
        </section>
        <section id="section-reviews" class="col-lg-4">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h3 class="h6 mb-0">Recent Reviews</h3>
                    <a href="#" class="small">See all</a>
                </div>
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-person-circle fs-4 text-secondary"></i>
                    <div>
                        <div class="mb-1"><strong>Ali</strong> on <em>GCSE Physics: Forces</em></div>
                        <div class="text-warning mb-1">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-half"></i>
                        </div>
                        <p class="small mb-0">Clear explanations and helpful practice problems.</p>
                    </div>
                </div>
            </div>
        </section>
        <section id="section-messages" class="col-lg-4">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h3 class="h6 mb-0">Messages</h3>
                    <a href="#" class="small">Open inbox</a>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill text-bg-primary">3</span>
                    <span class="small">New student inquiries today</span>
                </div>
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-chat-dots me-1"></i> Go to
                    Messages</button>
            </div>
        </section>
    </div> --}}

    <!-- Resources -->
    <section id="section-resources" class="mt-4">
        <div class="card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="h6 mb-0">Creator Resources</h3>
                <a class="small" href="#">Browse all</a>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="fw-semibold mb-1">Course Template: STEM Unit</div>
                        <div class="small text-muted mb-3">Syllabus, pacing guide, rubric shells</div>
                        <button class="btn btn-sm btn-outline-primary">Use Template</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="fw-semibold mb-1">Assessment Bank Starter</div>
                        <div class="small text-muted mb-3">50 items with AI‑ready metadata</div>
                        <button class="btn btn-sm btn-outline-primary">Add to Course</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <div class="fw-semibold mb-1">Video Production Tips</div>
                        <div class="small text-muted mb-3">Lighting, audio, and pacing guidelines</div>
                        <button class="btn btn-sm btn-outline-primary">Read Guide</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Settings anchor (placeholder) -->
    {{-- <section id="section-settings" class="mt-4">
        <div class="card p-3">
            <h3 class="h6 mb-1">Settings</h3>
            <p class="small text-muted mb-0">Profile, availability, subjects, verification, notifications.</p>
        </div>
    </section> --}}

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            // Charts
            const viewsCtx = document.getElementById('viewsChart');

            let viewsChart;

            // Function to load chart data from API
            async function loadViewsChartData() {
                const response = await fetch('{{ url('/') }}/educator-panel/lesson-views/chart');
                const result = await response.json();

                const labels = result.labels.map((d, i) => `D-${30 - i}`);
                const viewsData = result.data;

                if (viewsChart) {
                    // Update existing chart
                    viewsChart.data.labels = labels;
                    viewsChart.data.datasets[0].data = viewsData;
                    viewsChart.update();
                } else {
                    // Create new chart
                    viewsChart = new Chart(viewsCtx, {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Views',
                                data: viewsData,
                                tension: .35,
                                borderColor: '#006b7d',
                                backgroundColor: '#006b7d90',
                                fill: true,
                                pointRadius: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }

            // Initial Load
            loadViewsChartData();

            // Refresh Button
            document.getElementById('refreshViews').addEventListener('click', () => {
                loadViewsChartData();
            });


            const revCtx = document.getElementById('revenueChart');
            new Chart(revCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Course sales', 'Live sessions', 'Resources'],
                    datasets: [{
                        data: [62, 25, 13],
                        backgroundColor: ['#006b7d', '#00a1b6', '#ff6b35']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Save draft (demo)
            document.getElementById('saveCourseBtn').addEventListener('click', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('createCourseModal'));
                modal.hide();
                const toastEl = document.createElement('div');
                toastEl.className =
                    'toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-3';
                toastEl.role = 'alert';
                toastEl.innerHTML =
                    `<div class="d-flex"><div class="toast-body"><i class='bi bi-check2-circle me-2'></i>Course saved as draft.</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
                document.body.appendChild(toastEl);
                const t = new bootstrap.Toast(toastEl, {
                    delay: 2500
                });
                t.show();
            });
        </script>
    @endpush
</x-educator-layout>
