    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Ed‑Cademy — Educator Dashboard</title>
        <!-- Bootstrap & Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
        <link rel="stylesheet" href="{{ asset('assets/css/educator-style.css') }}">

        {{-- @vite(['resources/js/app.js']) --}}
        <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>


        @stack('styles')
    </head>

    <body>
        <!-- Top Header -->
        <header class="header py-2">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <a href="edcademy-landing.html" class="text-decoration-none brand">
                            <i class="bi bi-mortarboard-fill me-2"></i>Ed‑Cademy
                        </a>
                        <span class="d-none d-md-inline text-muted">Educator Dashboard</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('educator.courses.create') }}">
                            <i class="bi bi-plus-lg me-1"></i> New Course
                        </a>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#uploadVideoModal">
                            <i class="bi bi-cloud-upload me-1"></i> Upload Video
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->first_name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('educator.profile') }}">View Public
                                        Profile</a></li>
                                <li><a class="dropdown-item" href="#">Account Settings</a></li>
                                <li><a class="dropdown-item" href="#">Switch to Student View</a></li>
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
                <aside class="col-12 col-md-3 col-lg-2 sidebar p-3">
                    <nav class="nav flex-column gap-1">
                        <a class="nav-link @if (request()->is('educator/dashboard')) active @endif"
                            href="{{ route('educator.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>
                            Overview</a>
                        <a class="nav-link @if (request()->is('educator/courses', 'educator/courses/*')) active @endif"
                            href="{{ route('educator.courses.index') }}"><i class="bi bi-journal-code me-2"></i> My
                            Courses</a>
                        <a class="nav-link @if (request()->is('educator/video-stats')) active @endif"
                            href="{{ route('educator.video-stats.index') }}"><i class="bi bi-camera-video me-2"></i>
                            Videos &
                            Stats
                        </a>
                        <a class="nav-link @if (request()->is('educator/sessions')) active @endif"
                            href="{{ route('educator.sessions.index') }}"><i class="bi bi-calendar3 me-2"></i>
                            Sessions/Bookings</a>
                        <a class="nav-link" href="#section-earnings"><i class="bi bi-cash-coin me-2"></i> Earnings</a>
                        <a class="nav-link" href="#section-escrow"><i class="bi bi-shield-check me-2"></i> Escrow</a>
                        <a class="nav-link @if (request()->is('educator/payouts')) active @endif"
                            href="{{ route('educator.payouts.index') }}"><i class="bi bi-bank me-2"></i> Payouts</a>
                        <a class="nav-link" href="#section-reviews"><i class="bi bi-star-half me-2"></i> Reviews</a>
                        <a class="nav-link" href="#section-messages"><i class="bi bi-chat-dots me-2"></i> Messages</a>
                        <a class="nav-link" href="#section-resources"><i class="bi bi-folder2-open me-2"></i>
                            Resources</a>
                        <a class="nav-link" href="{{ route('educator.schedule.index') }}"><i class="bi bi-calendar-event me-2"></i>
                            Schedule</a>
                        <a class="nav-link" href="#section-settings"><i class="bi bi-gear me-2"></i> Settings</a>
                    </nav>
                    <hr />
                    <div class="p-3 rounded" style="background: var(--light-cyan);">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                            <div>
                                <strong>Finish payouts setup</strong>
                                <p class="mb-2 small">Connect Stripe/PayPal to receive earnings.</p>
                                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-link-45deg me-1"></i>
                                    Connect
                                    Payouts</button>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Main -->
                <main class="col-12 col-md-9 col-lg-10 p-4">
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

        <!-- Upload Video Modal -->
        <div class="modal fade" id="uploadVideoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-cloud-upload me-2"></i>Upload Video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="uploadVideoForm" class="row g-3">
                            <div class="col-12 col-md-8">
                                <label class="form-label">Video Title</label>
                                <input required class="form-control" placeholder="e.g., Chain Rule Explained" />
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Course</label>
                                <select class="form-select" id="videoCourseSelect"></select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Upload File</label>
                                <input type="file" class="form-control" />
                                <div class="form-text">MP4/MOV, up to 2 GB. Auto‑captions enabled.</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Visibility</label>
                                <select class="form-select">
                                    <option>Published</option>
                                    <option>Unlisted</option>
                                    <option>Draft</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Release date</label>
                                <input type="date" class="form-control" />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3" placeholder="Add chapter markers, resources, links..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary"><i class="bi bi-upload me-1"></i> Upload</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @stack('scripts')



    </body>

    </html>
