<x-educator-layout>
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
                        <div class="h3 mb-0" id="kpiCourses">8</div>
                        <small class="text-muted">2 drafts • 6 published</small>
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
                        <div class="h3 mb-0" id="kpiStudents">1,246</div>
                        <small class="text-muted">+38 this week</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card kpi-card p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="kpi-icon"><i class="bi bi-bar-chart"></i></span>
                        <span class="pill badge-soft">7‑day views</span>
                    </div>
                    <div class="mt-3">
                        <div class="h3 mb-0" id="kpiViews">18,420</div>
                        <small class="text-muted">Avg watch 7m 12s</small>
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
                            <div class="d-flex justify-content-between"><span class="text-muted">Escrow</span> <strong
                                    id="escrowBalance">$742.00</strong></div>
                            <div class="d-flex justify-content-between"><span class="text-muted">Earned</span> <strong
                                    id="earnedTotal">$12,904.50</strong></div>
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
                        <h5 class="mb-0">Video views (last 14 days)</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary" id="refreshViews"><i
                                    class="bi bi-arrow-clockwise"></i></button>
                        </div>
                    </div>
                    <canvas id="viewsChart" height="500" class="mt-3"></canvas>
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
                    <!-- Rows injected by JS -->
                </tbody>
            </table>
        </div>
    </section>

    <!-- Videos -->
    <section id="section-videos" class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h3 class="h5 mb-0">Recent Videos</h3>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadVideoModal"><i
                    class="bi bi-cloud-upload me-1"></i> Upload</button>
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
                        <th>Published</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>

    <!-- Earnings & Escrow split -->
    <div class="row g-3">
        <section id="section-earnings" class="col-lg-6">
            <div class="card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h3 class="h6 mb-0">Earnings</h3>
                    <a href="#" class="small">View all</a>
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
    </div>

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
    <section id="section-settings" class="mt-4">
        <div class="card p-3">
            <h3 class="h6 mb-1">Settings</h3>
            <p class="small text-muted mb-0">Profile, availability, subjects, verification, notifications.</p>
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            // Sample data (replace with API responses later)
            const courses = [{
                    id: 1,
                    title: 'Calculus I — Limits & Derivatives',
                    subject: 'Math',
                    status: 'Published',
                    price: 49,
                    enroll: 412,
                    rating: 4.8,
                    updated: '2025-09-12'
                },
                {
                    id: 2,
                    title: 'Physics: Mechanics (GCSE)',
                    subject: 'Physics',
                    status: 'Published',
                    price: 39,
                    enroll: 288,
                    rating: 4.7,
                    updated: '2025-09-10'
                },
                {
                    id: 3,
                    title: 'IELTS Speaking Mastery',
                    subject: 'English',
                    status: 'Draft',
                    price: 29,
                    enroll: 0,
                    rating: null,
                    updated: '2025-09-15'
                },
                {
                    id: 4,
                    title: 'Organic Chemistry Basics',
                    subject: 'Chemistry',
                    status: 'Published',
                    price: 35,
                    enroll: 156,
                    rating: 4.5,
                    updated: '2025-09-05'
                },
                {
                    id: 5,
                    title: 'Algebra II — Functions',
                    subject: 'Math',
                    status: 'Published',
                    price: 25,
                    enroll: 501,
                    rating: 4.6,
                    updated: '2025-09-08'
                },
                {
                    id: 6,
                    title: 'Essay Writing Bootcamp',
                    subject: 'English',
                    status: 'Published',
                    price: 19,
                    enroll: 321,
                    rating: 4.4,
                    updated: '2025-09-01'
                },
                {
                    id: 7,
                    title: 'SAT Math Crash Course',
                    subject: 'Math',
                    status: 'Draft',
                    price: 59,
                    enroll: 0,
                    rating: null,
                    updated: '2025-09-13'
                },
                {
                    id: 8,
                    title: 'AP Physics 1: Waves',
                    subject: 'Physics',
                    status: 'Published',
                    price: 45,
                    enroll: 118,
                    rating: 4.6,
                    updated: '2025-09-03'
                },
            ];

            const videos = [{
                    title: 'Chain Rule Explained',
                    course: 'Calculus I — Limits & Derivatives',
                    views: 4200,
                    watch: '7h 15m',
                    likes: 298,
                    comments: 32,
                    published: '2025-09-11'
                },
                {
                    title: 'Free‑Body Diagrams',
                    course: 'Physics: Mechanics (GCSE)',
                    views: 2850,
                    watch: '5h 02m',
                    likes: 188,
                    comments: 14,
                    published: '2025-09-10'
                },
                {
                    title: 'Quadratic Functions',
                    course: 'Algebra II — Functions',
                    views: 5100,
                    watch: '8h 41m',
                    likes: 332,
                    comments: 29,
                    published: '2025-09-07'
                },
                {
                    title: 'Essay Openings that Hook',
                    course: 'Essay Writing Bootcamp',
                    views: 1630,
                    watch: '2h 40m',
                    likes: 120,
                    comments: 6,
                    published: '2025-09-05'
                },
            ];

            // Populate Courses table
            const tbody = document.querySelector('#coursesTable tbody');

            function renderCourses(list) {
                tbody.innerHTML = list.map(c => `
        <tr>
          <td>
            <div class="d-flex align-items-start gap-2">
              <img src="https://via.placeholder.com/64x40/ffffff/006b7d?text=${encodeURIComponent(c.subject[0] || 'C')}" class="rounded border" width="64" height="40" alt="" />
              <div>
                <div class="fw-semibold">${c.title}</div>
                <div class="small text-muted">ID #${c.id}</div>
              </div>
            </div>
          </td>
          <td>${c.subject}</td>
          <td>${c.status === 'Published' ? '<span class="badge text-bg-success">Published</span>' : '<span class="badge text-bg-secondary">Draft</span>'}</td>
          <td>$${c.price.toFixed(2)}</td>
          <td>${c.enroll}</td>
          <td>${c.rating ? `<span class="text-warning"><i class="bi bi-star-fill"></i></span> ${c.rating.toFixed(1)}` : '-'}</td>
          <td>${c.updated}</td>
          <td class="text-end">
            <div class="btn-group btn-group-sm">
              <a href="#" class="btn btn-outline-primary"><i class="bi bi-bar-chart"></i></a>
              <a href="#" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
              <a href="#" class="btn btn-outline-primary"><i class="bi bi-box-arrow-up-right"></i></a>
            </div>
          </td>
        </tr>`).join('');
            }
            renderCourses(courses);

            // Populate Videos table
            const vbody = document.querySelector('#videosTable tbody');

            function renderVideos(list) {
                vbody.innerHTML = list.map(v => `
        <tr>
          <td>${v.title}</td>
          <td>${v.course}</td>
          <td>${v.views.toLocaleString()}</td>
          <td>${v.watch}</td>
          <td>${v.likes}</td>
          <td>${v.comments}</td>
          <td>${v.published}</td>
        </tr>`).join('');
            }
            renderVideos(videos);

            // Fill video course select
            const videoCourseSelect = document.getElementById('videoCourseSelect');
            courses.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.title;
                videoCourseSelect.appendChild(opt);
            });

            // Simple search/filter
            document.getElementById('courseSearch').addEventListener('input', (e) => {
                const q = e.target.value.toLowerCase();
                renderCourses(courses.filter(c => c.title.toLowerCase().includes(q) || c.subject.toLowerCase().includes(
                    q)));
            });

            // Global search (demo: just highlights Courses section)
            document.getElementById('globalSearch').addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    document.getElementById('section-courses').scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });

            // Charts
            const viewsCtx = document.getElementById('viewsChart');
            const labels = Array.from({
                length: 14
            }, (_, i) => `D-${14-i}`);
            let viewsData = labels.map(() => Math.floor(800 + Math.random() * 1200));
            const viewsChart = new Chart(viewsCtx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Views',
                        data: viewsData,
                        tension: .35,
                        borderColor: '#006b7d',
                        backgroundColor: 'rgba(0,107,125,.12)',
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
            document.getElementById('refreshViews').addEventListener('click', () => {
                viewsChart.data.datasets[0].data = labels.map(() => Math.floor(800 + Math.random() * 1200));
                viewsChart.update();
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
