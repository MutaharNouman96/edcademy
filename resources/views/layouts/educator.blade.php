    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport"
            content="width=device-width, initial-scale=1.0, viewport-fit=cover, shrink-to-fit=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Ed‑Cademy — Educator Dashboard</title>
        <!-- Bootstrap & Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
        <link rel="stylesheet" href="{{ asset('assets/css/educator-style.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('assets/css/select2-addon.css') }}" />

        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.bootstrap5.css">
       




        {{-- @vite(['resources/js/app.js']) --}}
        <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>


        @stack('styles')
    </head>

    <body>
        <!-- Top Header -->
        <header class="header">
            <div class="container-fluid px-3 px-lg-4">
                <div class="d-flex align-items-center justify-content-between gap-2" style="min-height: 64px;">
                    <div class="d-flex align-items-center gap-2 gap-md-3">
                        <button class="btn btn-icon-toggle d-md-none" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#educatorSidebar"
                            aria-controls="educatorSidebar" aria-label="Open menu">
                            <i class="bi bi-list"></i>
                        </button>
                        <a href="{{ route('educator.dashboard') }}" class="text-decoration-none brand">
                            <span class="brand-logo"><i class="bi bi-mortarboard-fill"></i></span>
                            <span class="brand-text">Ed‑Cademy</span>
                        </a>
                        <span class="d-none d-lg-inline header-subtitle">Educator Dashboard</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a class="btn btn-sm btn-primary d-none d-sm-inline-flex align-items-center"
                            href="{{ route('educator.courses.crud.create') }}">
                            <i class="bi bi-plus-lg me-1"></i> New Course
                        </a>
                        <a class="btn btn-sm btn-outline-primary d-none d-lg-inline-flex align-items-center"
                            href="{{ route('website.index') }}">
                            <i class="bi bi-globe me-1"></i> Website
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-user dropdown-toggle d-flex align-items-center gap-2"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="user-avatar">{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}</span>
                                <span class="d-none d-md-inline">{{ Auth::user()->first_name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li class="d-sm-none">
                                    <a class="dropdown-item" href="{{ route('educator.courses.crud.create') }}">
                                        <i class="bi bi-plus-lg me-2"></i>New Course
                                    </a>
                                </li>
                                <li class="d-lg-none">
                                    <a class="dropdown-item" href="{{ route('website.index') }}">
                                        <i class="bi bi-globe me-2"></i>Go to website
                                    </a>
                                </li>
                                <li class="d-sm-none">
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('educator.settings') }}">
                                        <i class="bi bi-person me-2"></i>View Public Profile
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Account Settings</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-arrow-left-right me-2"></i>Switch to Student View</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="javascript:void(0)"
                                        onclick="document.getElementById('logout').submit()"><i class="bi bi-box-arrow-right me-2"></i>Sign out</a></li>
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
                <aside id="educatorSidebar"
                    class="sidebar offcanvas-md offcanvas-start col-12 col-md-3 col-lg-2 p-0"
                    tabindex="-1" aria-labelledby="educatorSidebarLabel">
                    <div class="offcanvas-header d-md-none">
                        <a href="{{ route('educator.dashboard') }}" class="text-decoration-none brand">
                            <span class="brand-logo"><i class="bi bi-mortarboard-fill"></i></span>
                            <span class="brand-text">Ed‑Cademy</span>
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            data-bs-target="#educatorSidebar" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body d-flex flex-column">
                        <nav class="nav flex-column">
                            <span class="nav-section-label">Main</span>
                            <a class="nav-link @if (request()->is('educator-panel/dashboard')) active @endif"
                                href="{{ route('educator.dashboard') }}"><i class="bi bi-speedometer2"></i>
                                <span>Overview</span></a>
                            <a class="nav-link @if (request()->is('educator-panel/courses', 'educator/courses-crud/*')) active @endif"
                                href="{{ route('educator.courses.crud.index') }}"><i
                                    class="bi bi-journal-code"></i> <span>My Courses</span></a>
                            <a class="nav-link @if (request()->is('educator-panel/video-stats')) active @endif"
                                href="{{ route('educator.video-stats.index') }}"><i
                                    class="bi bi-camera-video"></i>
                                <span>Videos &amp; Stats</span></a>
                            <a class="nav-link @if (request()->is('educator-panel/sessions*')) active @endif"
                                href="{{ route('educator.sessions.index') }}"><i class="bi bi-calendar3"></i>
                                <span>Sessions/Bookings</span></a>
                            <a class="nav-link @if (request()->is('educator-panel/session-schedule*')) active @endif"
                                href="{{ route('educator.session-schedule.index') }}"><i
                                    class="bi bi-calendar2-range"></i>
                                <span>Session availability</span></a>

                            <span class="nav-section-label">Finance</span>
                            <a class="nav-link @if (request()->is('educator-panel/payouts')) active @endif"
                                href="{{ route('educator.payouts.index') }}"><i class="bi bi-bank"></i>
                                <span>Payouts</span></a>
                            <a class="nav-link @if (request()->is('educator-panel/payments*')) active @endif"
                                href="{{ route('educator.payments.index') }}"><i class="bi bi-cash-coin"></i>
                                <span>Earned Payments</span></a>

                            <span class="nav-section-label">Engagement</span>
                            <a class="nav-link @if (request()->is('educator-panel/reviews*')) active @endif"
                                href="{{ route('educator.reviews.index') }}"><i class="bi bi-star-half"></i>
                                <span>Reviews</span></a>
                            <a class="nav-link" href="{{ route('chat.index') }}"><i class="bi bi-chat-dots"></i>
                                <span>Messages</span></a>
                            <a class="nav-link" href="{{ route('educator.resources.index') }}"><i
                                    class="bi bi-folder2-open"></i>
                                <span>Resources</span></a>
                            <a class="nav-link" href="{{ route('educator.schedule.index') }}"><i
                                    class="bi bi-calendar-event"></i>
                                <span>Schedule</span></a>
                            <a class="nav-link" href="{{ route('educator.settings') }}"><i
                                    class="bi bi-gear"></i>
                                <span>Settings</span></a>
                        </nav>
                        @unless (Auth::user()->canReceivePayouts())
                            <div class="payout-card mt-auto">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <div>
                                        <strong>Finish payouts setup</strong>
                                        <p class="mb-2 small">Connect Stripe/PayPal to receive earnings.</p>
                                        <a href="{{ route('stripe.connect') }}" class="btn btn-sm btn-primary w-100"><i
                                                class="bi bi-link-45deg me-1"></i>
                                            Connect Payouts</a>
                                    </div>
                                </div>
                            </div>
                        @endunless
                    </div>
                </aside>

                <!-- Main -->
                <main class="col-12 col-md-9 col-lg-10 p-3 p-lg-4 main-content">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('message'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    {{ $slot }}

                </main>
            </div>
        </div>

        <!-- Create Course Modal -->
        <div class="modal fade" id="createCourseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-plus-square-dotted me-2"></i>Create New Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createCourseForm" class="row g-3">
                            <div class="col-12 col-md-8">
                                <label class="form-label">Course Title</label>
                                <input required class="form-control"
                                    placeholder="e.g., Calculus I — Limits & Derivatives" />
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label">Subject</label>
                                <input class="form-control" placeholder="Math, Physics, English" />
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label">Level</label>
                                <select class="form-select">
                                    <option>School</option>
                                    <option>High School</option>
                                    <option>University</option>
                                    <option>Professional</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label">Price (USD)</label>
                                <input type="number" min="0" step="0.01" class="form-control"
                                    placeholder="49.00" />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="4" placeholder="What students will learn, prerequisites, outcomes..."></textarea>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Course Type</label>
                                <select class="form-select">
                                    <option>Online Module (multi‑lesson)</option>
                                    <option>Video pack</option>
                                    <option>Worksheet/Resources</option>
                                    <option>Live Cohort (with sessions)</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Release</label>
                                <select class="form-select">
                                    <option>Publish immediately</option>
                                    <option>Schedule publish</option>
                                    <option>Draft only</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Thumbnail</label>
                                <input type="file" class="form-control" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Tags</label>
                                <input class="form-control" placeholder="e.g., algebra, exam prep, STEM" />
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="drip" />
                                    <label class="form-check-label" for="drip">Enable drip release by
                                        lesson</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" id="saveCourseBtn"><i class="bi bi-save me-1"></i> Save
                            draft</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Scripts -->

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
        <script>
            (function () {
                var el = document.getElementById('educatorSidebar');
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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.select2').each(function() {
                    var $el = $(this);
                    var opts = {
                        width: '100%',
                        theme: 'default',
                    };
                    if ($el.data('placeholder')) {
                        opts.placeholder = $el.data('placeholder');
                    }
                    if ($el.prop('multiple')) {
                        opts.closeOnSelect = false;
                    }
                    $el.select2(opts);
                });
            });
        </script>

        <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.3.6/js/dataTables.bootstrap5.js"></script>
        <script>
            $(document).ready(function() {
                $('.data-table').DataTable({
                    "paging": true,
                    "info": true,
                    "searching": true,
                    pageLength: 10
                });
            });
        </script>

        @stack('scripts')



    </body>

    </html>
