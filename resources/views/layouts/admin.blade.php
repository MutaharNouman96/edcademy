<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Ed-Cademy • Analytics • Creator Analytics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .cke_notifications_area{
            display: none !important;
        }
        :root {
            --brand: #0b3c77;
            --light: #f8fafc;
        }

        body {
            background: var(--light);
        }

        .sidebar {
            min-height: 100vh;
            background: var(--brand);
            color: #fff;
        }

        .sidebar .nav-link {
            color: #d1d5db;
            border-radius: 0.5rem;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .main-content {
            padding: 2rem;
        }

        .card {
            border-radius: 1rem;
        }

        .section-title {
            font-weight: 700;
            color: var(--brand);
        }

        .kpi-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .kpi-value {
            font-size: 1.5rem;
            font-weight: 700;
        }
    </style>
    @stack('styles')
</head>

<body>
    <header class="header py-2">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none brand">
                        <i class="bi bi-mortarboard-fill me-2"></i>Ed‑Cademy
                    </a>
                    <span class="d-none d-md-inline text-muted">Admin Dashboard</span>
                </div>
                <div class="d-flex align-items-center gap-2">

                    <a class="btn btn-sm btn-outline-primary" href="{{ route('website.index') }}">
                        <i class="bi bi-globe me-1"></i> Go to website
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->first_name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                           
                            <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">App & Account Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="javascript:void(0)"
                                    onclick="document.getElementById('logout').submit()">Sign out</a></li>
                            <form action="{{ route('logout') }}" id="logout" method="post">@csrf</form>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-3 col-lg-2 p-3 sidebar">
                
                <nav class="nav flex-column gap-1">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-bar-chart-line me-2"></i>Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.visual-reports.*') ? 'active' : '' }}"
                        href="{{ route('admin.visual-reports.index') }}">
                        <i class="bi bi-graph-up-arrow me-2"></i>Visual Reports
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"
                        href="{{ route('admin.blogs.index') }}">
                        <i class="bi bi-journal-text me-2"></i>Blogs
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.policies.*') ? 'active' : '' }}"
                        href="{{ route('admin.policies.index') }}">
                        <i class="bi bi-file-earmark-text me-2"></i>Policies
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                        href="{{ route('admin.settings.index') }}">
                        <i class="bi bi-gear me-2"></i>Settings
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.inapp-users.*') ? 'active' : '' }}"
                        href="{{ route('admin.inapp-users.index') }}">
                        <i class="bi bi-person-plus me-2"></i>Internal Users
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.access-control.*') ? 'active' : '' }}"
                        href="{{ route('admin.access-control.index') }}">
                        <i class="bi bi-shield-lock me-2"></i>Roles & Permissions
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.manage.educators') ? 'active' : '' }}"
                        href="{{ route('admin.manage.educators') }}">
                        <i class="bi bi-person-workspace me-2"></i>Manage Educators
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.manage.students') ? 'active' : '' }}"
                        href="{{ route('admin.manage.students') }}">
                        <i class="bi bi-people-fill me-2"></i>Manage Students
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.manage.courses') ? 'active' : '' }}"
                        href="{{ route('admin.manage.courses') }}">
                        <i class="bi bi-book me-2"></i>Manage Courses
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.manage.lessons') ? 'active' : '' }}"
                        href="{{ route('admin.manage.lessons') }}">
                        <i class="bi bi-collection-play me-2"></i>Manage Lessons
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.payouts.index') ? 'active' : '' }}"
                        href="{{ route('admin.payouts.index') }}">
                        <i class="bi bi-cash-coin me-2"></i>Payouts
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.financial-reports.index') ? 'active' : '' }}"
                        href="{{ route('admin.financial-reports.index') }}">
                        <i class="bi bi-file-earmark-text me-2"></i>Financial Reports
                    </a>

                </nav>
            </aside>

            <!-- Main -->
            <main class="col-md-9 col-lg-10 main-content">

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($message = Session::get('danger'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>Please fix the errors below.
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{ $slot }}

            </main>
        </div>
    </div>

    <script>
        // Dashboard-only charts (guarded so other admin pages don't error)
        const earningsEl = document.getElementById('earningsChart');
        if (earningsEl) {
            new Chart(earningsEl, {
                type: 'bar',
                data: {
                    labels: ['Maryam B.', 'Omar H.', 'Sara M.', 'Ali R.', 'Fatima K.'],
                    datasets: [{
                        label: 'Earnings ($)',
                        data: [52000, 43500, 39200, 28000, 21500],
                        backgroundColor: [
                            '#0B3C77',
                            '#1d9bf0',
                            '#16a34a',
                            '#f59e0b',
                            '#94a3b8'
                        ]
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
            })
        }

        const contentEl = document.getElementById('contentChart');
        if (contentEl) {
            new Chart(contentEl, {
                type: 'bubble',
                data: {
                    datasets: [{
                            label: 'Maryam B.',
                            data: [{
                                x: 32000,
                                y: 4.6,
                                r: 15
                            }],
                            backgroundColor: '#0B3C77'
                        },
                        {
                            label: 'Omar H.',
                            data: [{
                                x: 28500,
                                y: 4.3,
                                r: 20
                            }],
                            backgroundColor: '#1d9bf0'
                        },
                        {
                            label: 'Sara M.',
                            data: [{
                                x: 25800,
                                y: 4.5,
                                r: 12
                            }],
                            backgroundColor: '#16a34a'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Views'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Avg Rating'
                            },
                            min: 0,
                            max: 5
                        }
                    }
                }
            })
        }

        const sentimentEl = document.getElementById('sentimentChart');
        if (sentimentEl) {
            new Chart(sentimentEl, {
                type: 'doughnut',
                data: {
                    labels: ['Positive', 'Neutral', 'Negative'],
                    datasets: [{
                        data: [68, 22, 10],
                        backgroundColor: ['#16a34a', '#f59e0b', '#dc2626']
                    }]
                },
                options: {
                    responsive: true
                }
            })
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
