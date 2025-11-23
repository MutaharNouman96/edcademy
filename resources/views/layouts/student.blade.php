<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ed‑Cademy — Student Dashboard</title>
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/student_dashboard.js'])
</head>
<body>
  <!-- Top Header -->
  <header class="header py-2">
    <div class="container-fluid px-4">
      <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
          <a href="#" class="text-decoration-none brand">
            <i class="bi bi-mortarboard-fill me-2"></i>Ed‑Cademy
          </a>
          <span class="text-muted d-none d-md-inline">Student Dashboard</span>
        </div>
        <div class="d-flex align-items-center gap-2">
          <a class="btn btn-sm btn-outline-primary" href="#"><i class="bi bi-search me-1"></i> Browse</a>
          <a class="btn btn-sm btn-outline-primary" href="#"><i class="bi bi-chat-dots me-1"></i> Messages</a>
          <div class="dropdown">
            <button class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#">Account Settings</a></li>
              <li><a class="dropdown-item" href="#">Billing</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        Sign out
                    </a>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <aside class="col-12 col-md-3 col-lg-2 sidebar p-3">
        <nav class="nav flex-column gap-1">
          <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Overview</a>
          <a class="nav-link {{ request()->routeIs('student.my-courses') ? 'active' : '' }}" href="{{ route('student.my-courses') }}"><i class="bi bi-journal-richtext me-2"></i> My Courses</a>
          <a class="nav-link {{ request()->routeIs('student.new-videos') ? 'active' : '' }}" href="{{ route('student.new-videos') }}"><i class="bi bi-camera-video me-2"></i> New Videos</a>
          <a class="nav-link {{ request()->routeIs('student.analytics') ? 'active' : '' }}" href="{{ route('student.analytics') }}"><i class="bi bi-graph-up-arrow me-2"></i> Analytics</a>
          <a class="nav-link {{ request()->routeIs('student.certificates') ? 'active' : '' }}" href="{{ route('student.certificates') }}"><i class="bi bi-award me-2"></i> Certificates</a>
          <a class="nav-link {{ request()->routeIs('student.payments') ? 'active' : '' }}" href="{{ route('student.payments') }}"><i class="bi bi-wallet2 me-2"></i> Payments</a>
          <a class="nav-link {{ request()->routeIs('student.wishlist') ? 'active' : '' }}" href="{{ route('student.wishlist') }}"><i class="bi bi-heart me-2"></i> Wishlist</a>
          <a class="nav-link" href="#section-messages"><i class="bi bi-chat-dots me-2"></i> Messages</a>
          <a class="nav-link {{ request()->routeIs('student.profile.edit') ? 'active' : '' }}" href="{{ route('student.profile.edit') }}"><i class="bi bi-gear me-2"></i> Settings</a>
        </nav>
        <hr/>
        <div class="p-3 rounded" style="background: var(--primary-50);">
          <div class="d-flex align-items-start gap-2">
            <i class="bi bi-lightning-charge-fill text-warning fs-5"></i>
            <div>
              <strong>Keep the streak!</strong>
              <p class="mb-2 small">You're on a 4‑day study streak. Watch 20 min today to extend it.</p>
              <button class="btn btn-sm btn-primary w-100"><i class="bi bi-play-fill me-1"></i> Continue learning</button>
            </div>
          </div>
        </div>
      </aside>

      <!-- Main -->
      <main class="col-12 col-md-9 col-lg-10 p-4">
        @yield('content')
      </main>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>
