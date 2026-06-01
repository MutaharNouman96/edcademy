<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ed‑Cademy — Student Dashboard</title>
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/student_dashboard.js') }}" defer></script>

    <style>
        :root {
            --s-primary: #6f42c1;
            --s-primary-dark: #4b2a87;
            --s-primary-50: #f3e8ff;
            --s-grad: linear-gradient(135deg, #7c3aed 0%, #6f42c1 55%, #4b2a87 100%);
            --s-ink: #1f2937;
            --s-muted: #6b7280;
            --s-bg: #f5f6fb;
            --s-border: rgba(17, 24, 39, .08);
            --s-radius: 16px;
            --s-header-h: 64px;
        }

        body {
            background:
                radial-gradient(1200px 600px at 100% -10%, rgba(124, 58, 237, .06), transparent 60%),
                var(--s-bg);
            font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            color: var(--s-ink);
        }

        .min-w-0 { min-width: 0; }

        /* ============ Header ============ */
        .header {
            position: sticky;
            top: 0;
            z-index: 1030;
            height: var(--s-header-h);
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, .82);
            -webkit-backdrop-filter: saturate(180%) blur(14px);
            backdrop-filter: saturate(180%) blur(14px);
            border-bottom: 1px solid var(--s-border);
        }

        .brand {
            color: var(--s-primary-dark);
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: -.02em;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
        }

        .brand .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--s-grad);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: .55rem;
            box-shadow: 0 6px 16px rgba(111, 66, 193, .35);
            font-size: 1.05rem;
        }

        .brand-text {
            background: var(--s-grad);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-divider {
            width: 1px;
            height: 26px;
            background: var(--s-border);
        }

        .header-subtitle {
            font-size: .9rem;
            font-weight: 500;
            color: var(--s-muted);
        }

        /* Header action buttons */
        .hdr-btn {
            --bs-btn-padding-y: .45rem;
            --bs-btn-padding-x: .7rem;
            border-radius: 12px;
            border: 1px solid var(--s-border);
            background: #fff;
            color: var(--s-ink);
            font-weight: 600;
            font-size: .875rem;
            display: inline-flex;
            align-items: center;
            transition: all .18s ease;
        }

        .hdr-btn:hover {
            background: var(--s-primary-50);
            color: var(--s-primary-dark);
            border-color: rgba(111, 66, 193, .25);
            transform: translateY(-1px);
        }

        .hdr-btn .bi { font-size: 1.05rem; }

        .hdr-cart-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            font-size: .62rem;
            font-weight: 700;
            padding: .2rem .4rem;
        }

        .user-chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border-radius: 999px;
            border: 1px solid var(--s-border);
            background: #fff;
            padding: .3rem .35rem;
            font-weight: 600;
            font-size: .875rem;
            color: var(--s-ink);
            transition: all .18s ease;
        }

        .user-chip:hover { background: var(--s-primary-50); }

        .user-chip .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--s-grad);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .dropdown-menu {
            border: 1px solid var(--s-border);
            border-radius: 14px;
            box-shadow: 0 18px 48px rgba(17, 24, 39, .14);
            padding: .4rem;
        }

        .dropdown-menu .dropdown-item {
            border-radius: 10px;
            padding: .55rem .75rem;
            font-weight: 500;
            font-size: .9rem;
        }

        .dropdown-menu .dropdown-item:hover {
            background: var(--s-primary-50);
            color: var(--s-primary-dark);
        }

        /* ============ Layout shell ============ */
        .shell {
            display: flex;
            align-items: flex-start;
            gap: 0;
        }

        /* ============ Sidebar ============ */
        .sidebar {
            position: sticky;
            top: var(--s-header-h);
            height: calc(100dvh - var(--s-header-h));
            width: 264px;
            flex: 0 0 264px;
            overflow-y: auto;
            background: #fff;
            border-right: 1px solid var(--s-border);
            padding: 1rem .85rem 1.25rem;
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(17, 24, 39, .12); border-radius: 99px; }

        .sidebar-label {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #9ca3af;
            padding: .25rem .85rem;
            margin-top: .5rem;
        }

        .sidebar .nav-link {
            position: relative;
            color: #4b5563;
            border-radius: 12px;
            padding: .65rem .85rem;
            margin-bottom: .15rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: .92rem;
            transition: all .16s ease;
        }

        .sidebar .nav-link .nav-ico {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: .65rem;
            background: #f3f4f6;
            color: #6b7280;
            font-size: 1rem;
            transition: all .16s ease;
            flex: 0 0 auto;
        }

        .sidebar .nav-link:hover {
            background: rgba(111, 66, 193, .07);
            color: var(--s-primary-dark);
        }

        .sidebar .nav-link:hover .nav-ico {
            background: #fff;
            color: var(--s-primary);
        }

        .sidebar .nav-link.active {
            background: var(--s-primary-50);
            color: var(--s-primary-dark);
            font-weight: 700;
        }

        .sidebar .nav-link.active .nav-ico {
            background: var(--s-grad);
            color: #fff;
            box-shadow: 0 6px 14px rgba(111, 66, 193, .32);
        }

        .sidebar .nav-link.active::before {
            content: "";
            position: absolute;
            left: -.85rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 22px;
            border-radius: 0 4px 4px 0;
            background: var(--s-grad);
        }

        .sidebar .nav-badge {
            margin-left: auto;
            font-size: .68rem;
            font-weight: 700;
        }

        .sidebar-sep {
            border: 0;
            border-top: 1px solid var(--s-border);
            margin: .65rem .5rem;
        }

        /* Sidebar footer user card */
        .sidebar-user {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .65rem;
            border-radius: 14px;
            background: linear-gradient(135deg, #faf7ff, #f3e8ff);
            border: 1px solid rgba(111, 66, 193, .15);
        }

        .sidebar-user .avatar {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: var(--s-grad);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            text-transform: uppercase;
            flex: 0 0 auto;
        }

        .sidebar-user .u-name {
            font-weight: 700;
            font-size: .85rem;
            line-height: 1.1;
            color: var(--s-ink);
        }

        .sidebar-user .u-role {
            font-size: .72rem;
            color: var(--s-muted);
        }

        .sidebar-brand-mobile { display: none; }

        /* ============ Main ============ */
        .main {
            flex: 1 1 auto;
            min-width: 0;
            padding: 1.5rem;
        }

        @media (min-width: 1200px) {
            .main { padding: 1.75rem 2rem; }
        }

        /* ============ Backdrop ============ */
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, .5);
            -webkit-backdrop-filter: blur(3px);
            backdrop-filter: blur(3px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity .3s ease, visibility .3s ease;
        }

        .sidebar-backdrop.show { opacity: 1; visibility: visible; }

        /* ============ Mobile drawer ============ */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                height: 100dvh;
                width: 286px;
                max-width: 86vw;
                flex: none;
                z-index: 1050;
                border-right: 1px solid var(--s-border);
                box-shadow: 0 0 60px rgba(0, 0, 0, .22);
                border-radius: 0 20px 20px 0;
                transform: translateX(-100%);
                transition: transform .32s cubic-bezier(.4, 0, .2, 1);
            }

            .sidebar.show { transform: translateX(0); }
            .sidebar-brand-mobile { display: flex; }
            body.sidebar-open { overflow: hidden; }
            .main { padding: 1.15rem; }
        }

        @media (max-width: 575.98px) {
            .main { padding: 1rem .85rem; }
            .brand { font-size: 1.05rem; }
        }

        /* ============ Shared component polish ============ */
        .btn-primary { background: var(--s-primary); border-color: var(--s-primary); }
        .btn-primary:hover { background: var(--s-primary-dark); border-color: var(--s-primary-dark); }
        .btn-outline-primary { color: var(--s-primary); border-color: var(--s-primary); }
        .btn-outline-primary:hover { background: var(--s-primary); border-color: var(--s-primary); }
        .kpi-card { border: 0; border-radius: var(--s-radius); box-shadow: 0 6px 24px rgba(0, 0, 0, .06); }
        .kpi-icon { width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; background: var(--s-primary-50); color: var(--s-primary); }
        .card { border-radius: var(--s-radius); }
        .progress { height: 8px; }
        .course-card { transition: transform .2s ease, box-shadow .2s ease; }
        .course-card:hover { transform: translateY(-3px); box-shadow: 0 14px 28px rgba(0, 0, 0, .08); }
        .pill { padding: .15rem .5rem; border-radius: 9999px; font-size: .75rem; }
    </style>

    @stack('styles')
</head>

<body>
    @php
        $initials = collect(explode(' ', trim(Auth::user()->full_name)))
            ->filter()
            ->map(fn($p) => mb_substr($p, 0, 1))
            ->take(2)
            ->implode('');
    @endphp

    <!-- Top Header -->
    <header class="header">
        <div class="container-fluid px-3 px-md-4">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                    <button class="btn btn-light border d-lg-none sidebar-toggle" type="button" id="sidebarToggle"
                        aria-label="Open menu" aria-controls="studentSidebar" aria-expanded="false"
                        style="border-radius:12px;">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <a href="{{ route('student.dashboard') }}" class="text-decoration-none brand text-truncate">
                        <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
                        <span class="brand-text">Ed‑Cademy</span>
                    </a>
                    <span class="header-divider d-none d-lg-inline-block"></span>
                    <span class="header-subtitle d-none d-lg-inline">Student Dashboard</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if (cartTotalItems() > 0)
                        <a class="btn hdr-btn position-relative" href="{{ route('web.cart') }}" aria-label="Cart">
                            <i class="bi bi-cart"></i>
                            <span class="d-none d-sm-inline ms-1">Cart</span>
                            <span class="badge rounded-pill bg-danger hdr-cart-badge">
                                {{ cartTotalItems() }}
                                <span class="visually-hidden">cart items</span>
                            </span>
                        </a>
                    @endif
                    <a class="btn hdr-btn" href="{{ route('website.index') }}" aria-label="Website">
                        <i class="bi bi-globe"></i>
                        <span class="d-none d-sm-inline ms-1">Website</span>
                    </a>
                    <a class="btn hdr-btn" href="{{ route('chat.index') }}" aria-label="Messages">
                        <i class="bi bi-chat-dots"></i>
                        <span class="d-none d-sm-inline ms-1">Messages</span>
                    </a>
                    <div class="dropdown">
                        <button class="btn user-chip dropdown-toggle" data-bs-toggle="dropdown"
                            aria-label="Account menu">
                            <span class="avatar">{{ $initials }}</span>
                            <span class="d-none d-md-inline">{{ Auth::user()->full_name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('student.profile.edit') }}"><i
                                        class="bi bi-gear me-2"></i>Account Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>Sign out
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Backdrop for mobile slide-in sidebar -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="shell">
        <!-- Sidebar -->
        <aside class="sidebar" id="studentSidebar">
            <div class="d-flex d-md-none align-items-center justify-content-between mb-3 sidebar-brand-mobile">
                <a href="{{ route('student.dashboard') }}" class="text-decoration-none brand">
                    <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
                    <span class="brand-text">Ed‑Cademy</span>
                </a>
                <button class="btn btn-light border sidebar-close" type="button" id="sidebarClose"
                    aria-label="Close menu" style="border-radius:12px;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <nav class="nav flex-column">
                <span class="sidebar-label">Main</span>
                <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
                    href="{{ route('student.dashboard') }}">
                    <span class="nav-ico"><i class="bi bi-speedometer2"></i></span> Overview
                </a>

                <span class="sidebar-label">Learning</span>
                <a class="nav-link {{ request()->routeIs('student.my-courses') ? 'active' : '' }}"
                    href="{{ route('student.my-courses') }}">
                    <span class="nav-ico"><i class="bi bi-journal-richtext"></i></span> My Courses
                </a>
                <a class="nav-link {{ request()->routeIs('student.new-videos') ? 'active' : '' }}"
                    href="{{ route('student.new-videos') }}">
                    <span class="nav-ico"><i class="bi bi-camera-video"></i></span> New Videos
                </a>
                <a class="nav-link {{ request()->routeIs('web.courses') ? 'active' : '' }}"
                    href="{{ route('web.courses') }}">
                    <span class="nav-ico"><i class="bi bi-search"></i></span> Browse Courses
                </a>

                <span class="sidebar-label">Account</span>
                <a class="nav-link {{ request()->routeIs('student.analytics') ? 'active' : '' }}"
                    href="{{ route('student.analytics') }}">
                    <span class="nav-ico"><i class="bi bi-graph-up-arrow"></i></span> Analytics
                </a>
                <a class="nav-link {{ request()->routeIs('student.payments') ? 'active' : '' }}"
                    href="{{ route('student.payments') }}">
                    <span class="nav-ico"><i class="bi bi-wallet2"></i></span> Payments
                </a>
                <a class="nav-link {{ request()->routeIs('student.wishlist') ? 'active' : '' }}"
                    href="{{ route('student.wishlist') }}">
                    <span class="nav-ico"><i class="bi bi-heart"></i></span> Wishlist
                </a>
                <a class="nav-link" href="{{ route('chat.index') }}">
                    <span class="nav-ico"><i class="bi bi-chat-dots"></i></span> Messages
                </a>

                @if (cartTotalItems() > 0)
                    <hr class="sidebar-sep" />
                    <a class="nav-link active" href="{{ route('web.cart') }}">
                        <span class="nav-ico"><i class="bi bi-cart"></i></span> Cart
                        <span class="badge rounded-pill bg-danger nav-badge">
                            {{ cartTotalItems() }}
                            <span class="visually-hidden">cart items</span>
                        </span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-user">
                <span class="avatar">{{ $initials }}</span>
                <div class="min-w-0">
                    <div class="u-name text-truncate">{{ Auth::user()->full_name }}</div>
                    <div class="u-role">Student</div>
                </div>
                <a href="{{ route('student.profile.edit') }}" class="ms-auto text-decoration-none"
                    style="color:var(--s-primary-dark);" aria-label="Account settings">
                    <i class="bi bi-gear"></i>
                </a>
            </div>
        </aside>

        <!-- Main -->
        <main class="main">
            {{ $slot }}
        </main>
    </div>

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        (function() {
            const sidebar = document.getElementById('studentSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarClose');

            if (!sidebar || !backdrop) return;

            const MOBILE_BP = 992;

            const open = function() {
                sidebar.classList.add('show');
                backdrop.classList.add('show');
                document.body.classList.add('sidebar-open');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
            };

            const close = function() {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
                document.body.classList.remove('sidebar-open');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
            };

            if (toggleBtn) toggleBtn.addEventListener('click', open);
            if (closeBtn) closeBtn.addEventListener('click', close);
            backdrop.addEventListener('click', close);

            sidebar.querySelectorAll('.nav-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < MOBILE_BP) close();
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') close();
            });
            window.addEventListener('resize', function() {
                if (window.innerWidth >= MOBILE_BP) close();
            });
        })();
    </script>

    @stack('scripts')

</body>

</html>
