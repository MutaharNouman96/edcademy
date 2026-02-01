<x-admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="section-title mb-0">Visual Reports</h3>
            <div class="text-muted">Graphical analytics with a custom date range.</div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Start date</label>
                    <input type="date" class="form-control" name="start_date"
                        value="{{ request('start_date', $start->toDateString()) }}" />
                </div>
                <div class="col-md-4">
                    <label class="form-label">End date</label>
                    <input type="date" class="form-control" name="end_date"
                        value="{{ request('end_date', $end->toDateString()) }}" />
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary mt-4" type="submit">
                        <i class="bi bi-funnel me-1"></i> Apply
                    </button>
                    <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.visual-reports.index') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="text-muted">Courses added</div>
                <div class="kpi-value">{{ number_format($kpis['courses_added']) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="text-muted">Courses sold</div>
                <div class="kpi-value">{{ number_format($kpis['courses_sold']) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="text-muted">Revenue</div>
                <div class="kpi-value">AED {{ number_format($kpis['revenue'], 0) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="text-muted">Watch time</div>
                <div class="kpi-value">{{ number_format($kpis['watch_time_minutes'], 0) }} min</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Courses added over time</div>
                    <canvas id="coursesAddedChart" height="140"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Courses sold vs Revenue earned</div>
                    <canvas id="salesRevenueChart" height="140"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-semibold mb-2">New educators vs New students</div>
                    <canvas id="signupChart" height="140"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="fw-semibold mb-2">New videos vs Watch time (minutes)</div>
                    <canvas id="videosWatchChart" height="140"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const labels = @json($labels);

                // 1) Courses added
                const coursesAdded = @json($coursesAddedSeries);
                new Chart(document.getElementById('coursesAddedChart'), {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Courses added',
                            data: coursesAdded,
                            borderColor: '#0B3C77',
                            backgroundColor: 'rgba(11, 60, 119, 0.12)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 2,
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
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });

                // 2) Courses sold vs Revenue
                const coursesSold = @json($coursesSoldSeries);
                const revenue = @json($revenueSeries);
                new Chart(document.getElementById('salesRevenueChart'), {
                    data: {
                        labels,
                        datasets: [{
                                type: 'bar',
                                label: 'Courses sold',
                                data: coursesSold,
                                yAxisID: 'y',
                                backgroundColor: 'rgba(29, 155, 240, 0.35)',
                                borderColor: '#1d9bf0',
                                borderWidth: 1,
                            },
                            {
                                type: 'line',
                                label: 'Revenue (AED)',
                                data: revenue,
                                yAxisID: 'y1',
                                borderColor: '#16a34a',
                                backgroundColor: 'rgba(22, 163, 74, 0.12)',
                                tension: 0.3,
                                pointRadius: 2,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                position: 'left',
                                ticks: {
                                    precision: 0
                                },
                                title: {
                                    display: true,
                                    text: 'Courses sold'
                                }
                            },
                            y1: {
                                beginAtZero: true,
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false
                                },
                                title: {
                                    display: true,
                                    text: 'Revenue (AED)'
                                }
                            }
                        }
                    }
                });

                // 3) New educators vs students
                const newEducators = @json($newEducatorsSeries);
                const newStudents = @json($newStudentsSeries);
                new Chart(document.getElementById('signupChart'), {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                                label: 'New educators',
                                data: newEducators,
                                borderColor: '#0B3C77',
                                tension: 0.3,
                                pointRadius: 2,
                            },
                            {
                                label: 'New students',
                                data: newStudents,
                                borderColor: '#f59e0b',
                                tension: 0.3,
                                pointRadius: 2,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });

                // 4) New videos vs watch time
                const newVideos = @json($newVideosSeries);
                const watchTimeMinutes = @json($watchTimeMinutesSeries);
                new Chart(document.getElementById('videosWatchChart'), {
                    data: {
                        labels,
                        datasets: [{
                                type: 'bar',
                                label: 'New videos',
                                data: newVideos,
                                yAxisID: 'y',
                                backgroundColor: 'rgba(11, 60, 119, 0.20)',
                                borderColor: '#0B3C77',
                                borderWidth: 1,
                            },
                            {
                                type: 'line',
                                label: 'Watch time (min)',
                                data: watchTimeMinutes,
                                yAxisID: 'y1',
                                borderColor: '#dc2626',
                                tension: 0.3,
                                pointRadius: 2,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                position: 'left',
                                ticks: {
                                    precision: 0
                                },
                                title: {
                                    display: true,
                                    text: 'New videos'
                                }
                            },
                            y1: {
                                beginAtZero: true,
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false
                                },
                                title: {
                                    display: true,
                                    text: 'Watch time (min)'
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-admin-layout>

