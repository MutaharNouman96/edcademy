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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/css/select2-addon.css') }}" rel="stylesheet" />

    <style>
        .cke_notifications_area {
            display: none !important;
        }

        :root {
            --brand: #0b3c77;
            --brand-dark: #07294f;
            --brand-light: #1d9bf0;
            --accent: #f59e0b;
            --sidebar-bg: #0a2748;
            --sidebar-bg-2: #0d335f;
            --sidebar-text: #b9c6da;
            --sidebar-muted: #6f86a8;
            --light: #f4f7fb;
            --radius: 14px;
            --shadow-sm: 0 2px 8px rgba(11, 60, 119, 0.07);
            --shadow-md: 0 8px 28px rgba(11, 60, 119, 0.1);
        }

        * {
            scrollbar-width: thin;
        }

        body {
            background: var(--light);
            color: #1f2a3a;
            -webkit-font-smoothing: antialiased;
        }

        /* ============ Brand ============ */
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--brand);
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--brand), var(--brand-light));
            color: #fff;
            font-size: 1.15rem;
            box-shadow: 0 4px 12px rgba(11, 60, 119, 0.35);
        }

        /* ============ Header ============ */
        .header {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: saturate(180%) blur(12px);
            -webkit-backdrop-filter: saturate(180%) blur(12px);
            border-bottom: 1px solid rgba(11, 60, 119, 0.08);
        }

        .header-subtitle {
            color: #7589a3;
            font-weight: 500;
            padding-left: 0.85rem;
            margin-left: 0.25rem;
            border-left: 1px solid rgba(11, 60, 119, 0.14);
        }

        .btn-icon-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            padding: 0;
            border-radius: 11px;
            border: 1px solid rgba(11, 60, 119, 0.14);
            background: #fff;
            color: var(--brand);
            font-size: 1.35rem;
            transition: all 0.2s ease;
        }

        .btn-icon-toggle:hover {
            background: #eef4fb;
            color: var(--brand-dark);
        }

        .btn-user {
            border: 1px solid rgba(11, 60, 119, 0.12);
            background: #fff;
            border-radius: 999px;
            padding: 0.3rem 0.85rem 0.3rem 0.35rem;
            font-weight: 600;
            color: #1f2a3a;
            box-shadow: var(--shadow-sm);
        }

        .btn-user:hover {
            border-color: var(--brand);
            color: var(--brand-dark);
        }

        .btn-user::after {
            margin-left: 0.15rem;
        }

        .user-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--brand), var(--brand-light));
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .dropdown-menu {
            border: 1px solid rgba(11, 60, 119, 0.08);
            border-radius: 14px;
            padding: 0.4rem;
            box-shadow: var(--shadow-md);
        }

        .dropdown-item {
            border-radius: 9px;
            padding: 0.55rem 0.75rem;
            font-weight: 500;
        }

        /* ============ Sidebar ============ */
        .sidebar {
            background: linear-gradient(180deg, var(--sidebar-bg), var(--sidebar-bg-2));
            color: var(--sidebar-text);
        }

        .sidebar .offcanvas-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 1.1rem;
        }

        .sidebar .offcanvas-header .brand {
            color: #fff;
        }

        .sidebar .offcanvas-header .btn-close {
            filter: invert(1) grayscale(1) brightness(2);
            opacity: 0.8;
        }

        .sidebar .offcanvas-body {
            padding: 1rem 0.85rem 1.25rem;
        }

        @media (min-width: 768px) {
            .sidebar {
                position: sticky;
                top: 0;
                height: 100dvh;
                overflow-y: auto;
                border-right: 0;
            }
        }

        .nav-section-label {
            display: block;
            padding: 0.35rem 0.85rem;
            margin-top: 1.1rem;
            margin-bottom: 0.35rem;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--sidebar-muted);
        }

        .nav-section-label:first-child {
            margin-top: 0.25rem;
        }

        .sidebar .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 0.85rem;
            margin-bottom: 0.15rem;
            color: var(--sidebar-text);
            border-radius: 11px;
            font-weight: 500;
            font-size: 0.94rem;
            transition: background 0.18s ease, color 0.18s ease, transform 0.12s ease;
        }

        .sidebar .nav-link i {
            flex: 0 0 auto;
            width: 22px;
            font-size: 1.1rem;
            text-align: center;
            color: var(--sidebar-muted);
            transition: color 0.18s ease;
        }

        .sidebar .nav-link span {
            line-height: 1.2;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
        }

        .sidebar .nav-link:hover i {
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--brand), var(--brand-light));
            color: #fff;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(11, 60, 119, 0.45);
        }

        .sidebar .nav-link.active i {
            color: #fff;
        }

        .sidebar .nav-link.active::before {
            content: "";
            position: absolute;
            left: -0.85rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            border-radius: 0 4px 4px 0;
            background: var(--accent);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.18);
            border-radius: 999px;
        }

        /* ============ Main content ============ */
        .main-content {
            min-height: calc(100dvh - 64px);
        }

        /* ============ Cards / KPI ============ */
        .card {
            border-radius: var(--radius);
            border: 1px solid rgba(11, 60, 119, 0.06);
            box-shadow: var(--shadow-sm);
        }

        .section-title {
            font-weight: 700;
            color: var(--brand);
        }

        .kpi-card {
            background: #fff;
            border: 0;
            border-radius: var(--radius);
            padding: 1rem;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .kpi-value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* ============ Buttons ============ */
        .btn {
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-primary {
            background: var(--brand);
            border-color: var(--brand);
            box-shadow: 0 4px 12px rgba(11, 60, 119, 0.25);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
        }

        .btn-outline-primary {
            color: var(--brand);
            border-color: var(--brand);
        }

        .btn-outline-primary:hover {
            background: var(--brand);
            border-color: var(--brand);
        }

        /* ============ Responsive tweaks ============ */
        @media (max-width: 767.98px) {
            .sidebar.offcanvas-md {
                width: 290px;
                max-width: 85vw;
            }

            .brand-text {
                font-size: 1.05rem;
            }

            .main-content {
                min-height: auto;
            }
        }

        @media (max-width: 575.98px) {
            .header .container-fluid {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <header class="header">
        <div class="container-fluid px-3 px-lg-4">
            <div class="d-flex align-items-center justify-content-between gap-2" style="min-height: 64px;">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <button class="btn btn-icon-toggle d-md-none" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Open menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none brand">
                        <span class="brand-logo"><i class="bi bi-mortarboard-fill"></i></span>
                        <span class="brand-text">Ed‑Cademy</span>
                    </a>
                    <span class="d-none d-lg-inline header-subtitle">Admin Dashboard</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a class="btn btn-sm btn-outline-primary d-none d-sm-inline-flex align-items-center"
                        href="{{ route('website.index') }}">
                        <i class="bi bi-globe me-1"></i> Go to website
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-user dropdown-toggle d-flex align-items-center gap-2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="user-avatar">{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}</span>
                            <span class="d-none d-md-inline">{{ Auth::user()->first_name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li class="d-sm-none">
                                <a class="dropdown-item" href="{{ route('website.index') }}">
                                    <i class="bi bi-globe me-2"></i>Go to website
                                </a>
                            </li>
                            <li class="d-sm-none">
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                                    <i class="bi bi-gear me-2"></i>App &amp; Account Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="javascript:void(0)"
                                    onclick="document.getElementById('logout').submit()">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sign out</a></li>
                            <form action="{{ route('logout') }}" id="logout" method="post">@csrf</form>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar: slide-in drawer below md, static column md and up -->
            <aside id="adminSidebar" class="sidebar offcanvas-md offcanvas-start col-12 col-md-3 col-lg-2 p-0"
                tabindex="-1" aria-labelledby="adminSidebarLabel">
                <div class="offcanvas-header d-md-none">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none brand">
                        <span class="brand-logo"><i class="bi bi-mortarboard-fill"></i></span>
                        <span class="brand-text">Ed‑Cademy</span>
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        data-bs-target="#adminSidebar" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body d-flex flex-column">
                    <nav class="nav flex-column">
                        <span class="nav-section-label">Overview</span>
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-bar-chart-line"></i> <span>Dashboard</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.visual-reports.*') ? 'active' : '' }}"
                            href="{{ route('admin.visual-reports.index') }}">
                            <i class="bi bi-graph-up-arrow"></i> <span>Visual Reports</span>
                        </a>

                        <span class="nav-section-label">Content</span>
                        <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"
                            href="{{ route('admin.blogs.index') }}">
                            <i class="bi bi-journal-text"></i> <span>Blogs</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.policies.*') ? 'active' : '' }}"
                            href="{{ route('admin.policies.index') }}">
                            <i class="bi bi-file-earmark-text"></i> <span>Policies</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.manage.courses') ? 'active' : '' }}"
                            href="{{ route('admin.manage.courses') }}">
                            <i class="bi bi-book"></i> <span>Manage Courses</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.manage.lessons') ? 'active' : '' }}"
                            href="{{ route('admin.manage.lessons') }}">
                            <i class="bi bi-collection-play"></i> <span>Manage Lessons</span>
                        </a>

                        <span class="nav-section-label">Users</span>
                        <a class="nav-link {{ request()->routeIs('admin.manage.educators') ? 'active' : '' }}"
                            href="{{ route('admin.manage.educators') }}">
                            <i class="bi bi-person-workspace"></i> <span>Manage Educators</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.manage.students') ? 'active' : '' }}"
                            href="{{ route('admin.manage.students') }}">
                            <i class="bi bi-people-fill"></i> <span>Manage Students</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.inapp-users.*') ? 'active' : '' }}"
                            href="{{ route('admin.inapp-users.index') }}">
                            <i class="bi bi-person-plus"></i> <span>Internal Users</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.access-control.*') ? 'active' : '' }}"
                            href="{{ route('admin.access-control.index') }}">
                            <i class="bi bi-shield-lock"></i> <span>Roles &amp; Permissions</span>
                        </a>

                        <span class="nav-section-label">Finance</span>
                        <a class="nav-link {{ request()->routeIs('admin.payouts.index') ? 'active' : '' }}"
                            href="{{ route('admin.payouts.index') }}">
                            <i class="bi bi-cash-coin"></i> <span>Payouts</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.financial-reports.index') ? 'active' : '' }}"
                            href="{{ route('admin.financial-reports.index') }}">
                            <i class="bi bi-file-earmark-bar-graph"></i> <span>Financial Reports</span>
                        </a>

                        <span class="nav-section-label">System</span>
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                            href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear"></i> <span>Settings</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Main -->
            <main class="col-12 col-md-9 col-lg-10 p-3 p-lg-4 main-content">

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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

    <script>
        (function () {
            var el = document.getElementById('adminSidebar');
            if (!el) return;
            var mq = window.matchMedia('(max-width: 767.98px)');
            el.querySelectorAll('.nav-link[href]').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (!mq.matches) return;
                    var inst = bootstrap.Offcanvas.getInstance(el);
                    if (inst) inst.hide();
                });
            });
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true, 
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
