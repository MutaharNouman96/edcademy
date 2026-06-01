<x-student-layout>

    @push('styles')
    <style>
        /* ─── Dashboard-scoped tokens ───────────────────── */
        :root {
            --d-primary:      #6f42c1;
            --d-primary-dark: #4b2a87;
            --d-primary-50:   #f3e8ff;
            --d-grad: linear-gradient(135deg,#7c3aed 0%,#6f42c1 55%,#4b2a87 100%);
            --d-green:  #10b981;
            --d-amber:  #f59e0b;
            --d-blue:   #3b82f6;
            --d-red:    #ef4444;
        }

        /* ─── Alerts ─────────────────────────────────────── */
        .d-alert {
            border-radius: 14px;
            border-left: 4px solid transparent;
            font-size: .9rem;
        }
        .d-alert-warning { border-color: var(--d-amber); background: #fffbeb; color: #92400e; }
        .d-alert-success { border-color: var(--d-green); background: #ecfdf5; color: #065f46; }
        .d-alert-info    { border-color: var(--d-blue);  background: #eff6ff; color: #1e40af; }

        /* ─── Welcome Hero ────────────────────────────────── */
        .hero {
            background: var(--d-grad);
            border-radius: 20px;
            padding: 1.6rem 1.75rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-title { font-size: clamp(1.25rem, 3vw, 1.7rem); font-weight: 800; margin-bottom: .25rem; }
        .hero-sub   { font-size: .9rem; opacity: .82; }
        .hero-search {
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 12px;
            color: #fff;
            padding: .55rem 1rem;
            max-width: 340px;
            width: 100%;
            backdrop-filter: blur(6px);
            transition: background .2s;
        }
        .hero-search::placeholder { color: rgba(255,255,255,.65); }
        .hero-search:focus {
            outline: none;
            background: rgba(255,255,255,.25);
            border-color: rgba(255,255,255,.55);
        }

        /* ─── KPI Cards ───────────────────────────────────── */
        .kpi { border: 0; border-radius: 18px; overflow: hidden; transition: transform .2s, box-shadow .2s; }
        .kpi:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(0,0,0,.1); }
        .kpi-body { padding: 1.2rem 1.35rem 1.1rem; }
        .kpi-ico {
            width: 46px; height: 46px; border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.25rem; flex: 0 0 auto;
        }
        .kpi-ico-purple { background: var(--d-primary-50); color: var(--d-primary); }
        .kpi-ico-green  { background: #d1fae5; color: var(--d-green); }
        .kpi-ico-amber  { background: #fef3c7; color: var(--d-amber); }
        .kpi-ico-blue   { background: #dbeafe; color: var(--d-blue);  }
        .kpi-value { font-size: 1.8rem; font-weight: 800; line-height: 1; letter-spacing: -.03em; }
        .kpi-label { font-size: .8rem; color: #6b7280; font-weight: 500; }
        .kpi-tag {
            font-size: .72rem; font-weight: 700; border-radius: 999px;
            padding: .2rem .6rem;
        }

        /* ─── Section headings ────────────────────────────── */
        .section-head { margin-bottom: 1rem; }
        .section-title { font-size: 1.05rem; font-weight: 700; }
        .section-link { font-size: .82rem; font-weight: 600; color: var(--d-primary); text-decoration: none; }
        .section-link:hover { color: var(--d-primary-dark); }

        /* ─── Chart cards ─────────────────────────────────── */
        .chart-card { border: 0; border-radius: 18px; box-shadow: 0 4px 20px rgba(0,0,0,.05); height: 100%; }
        .chart-card .card-body { padding: 1.25rem; }
        .chart-card-title { font-size: .95rem; font-weight: 700; }

        /* ─── Video feed ──────────────────────────────────── */
        .video-item {
            display: flex; align-items: center; gap: .9rem;
            padding: .75rem .9rem;
            border-radius: 14px;
            transition: background .15s;
        }
        .video-item:hover { background: #f9fafb; }
        .video-thumb {
            width: 60px; height: 38px; border-radius: 10px; flex: 0 0 auto;
            background: var(--d-primary-50);
            display: flex; align-items: center; justify-content: center;
            color: var(--d-primary); font-size: 1.1rem;
            overflow: hidden;
        }
        .video-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .video-title { font-weight: 600; font-size: .9rem; line-height: 1.3; }
        .video-meta  { font-size: .78rem; color: #6b7280; }
        .video-dur   { font-size: .78rem; font-weight: 600; color: #6b7280; white-space: nowrap; }
        .btn-watch {
            border-radius: 10px; font-size: .8rem; font-weight: 700;
            padding: .35rem .75rem; white-space: nowrap;
            background: var(--d-grad); color: #fff; border: 0;
            transition: opacity .15s;
        }
        .btn-watch:hover { opacity: .88; color: #fff; }

        /* ─── Course grid (JS-rendered) ───────────────────── */
        .c-card {
            border: 0; border-radius: 18px; overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,.06);
            transition: transform .2s, box-shadow .2s;
            height: 100%;
        }
        .c-card:hover { transform: translateY(-4px); box-shadow: 0 16px 36px rgba(0,0,0,.1); }
        .c-thumb {
            width: 100%; aspect-ratio: 16/9; object-fit: cover;
            background: var(--d-primary-50);
        }
        .c-thumb-placeholder {
            width: 100%; aspect-ratio: 16/9;
            background: var(--d-grad);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.7); font-size: 2.5rem;
        }
        .c-body { padding: 1rem 1.1rem 1.1rem; }
        .c-subject {
            display: inline-block; font-size: .7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .06em;
            color: var(--d-primary); background: var(--d-primary-50);
            border-radius: 999px; padding: .2rem .6rem; margin-bottom: .5rem;
        }
        .c-title { font-size: .95rem; font-weight: 700; line-height: 1.35; margin-bottom: .35rem; }
        .c-meta  { font-size: .78rem; color: #9ca3af; }
        .c-prog-bar {
            height: 6px; border-radius: 999px; background: #f3f4f6; overflow: hidden;
            margin: .75rem 0 .3rem;
        }
        .c-prog-fill {
            height: 100%; border-radius: 999px;
            background: var(--d-grad);
            transition: width .6s ease;
        }
        .c-prog-text { font-size: .78rem; color: #6b7280; }
        .c-prog-pct  { font-weight: 700; color: var(--d-primary); }
        .btn-resume {
            border-radius: 11px; font-size: .85rem; font-weight: 700;
            background: var(--d-grad); color: #fff; border: 0;
            padding: .5rem 0; width: 100%;
            transition: opacity .15s;
        }
        .btn-resume:hover { opacity: .88; color: #fff; }
        .c-new-badge {
            position: absolute; top: .6rem; right: .6rem;
            background: #f59e0b; color: #fff;
            border-radius: 999px; font-size: .7rem; font-weight: 700;
            padding: .2rem .55rem; box-shadow: 0 2px 8px rgba(245,158,11,.4);
        }

        /* ─── Payments table ──────────────────────────────── */
        .pay-table { border-radius: 12px; overflow: hidden; }
        .pay-table th { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #9ca3af; border: 0; background: #f9fafb; padding: .7rem 1rem; }
        .pay-table td { border: 0; border-bottom: 1px solid #f3f4f6; padding: .8rem 1rem; vertical-align: middle; font-size: .9rem; }
        .pay-table tbody tr:last-child td { border-bottom: 0; }
        .pay-table tbody tr:hover td { background: #faf9ff; }
        .pay-type-badge {
            font-size: .72rem; font-weight: 700; border-radius: 999px;
            padding: .25rem .65rem; display: inline-block;
        }
        .pay-type-course  { background: #dbeafe; color: #1d4ed8; }
        .pay-type-lesson  { background: #fce7f3; color: #be185d; }
        .pay-type-default { background: #f3f4f6; color: #6b7280; }
        .pay-amount { font-weight: 700; font-size: .92rem; }

        /* ─── Messages CTA ────────────────────────────────── */
        .msg-cta {
            background: linear-gradient(135deg,#eff6ff,#dbeafe);
            border: 1px solid #bfdbfe;
            border-radius: 18px; padding: 1.25rem 1.5rem;
            display: flex; align-items: center; gap: 1.1rem;
        }
        .msg-cta-ico {
            width: 52px; height: 52px; border-radius: 15px; flex: 0 0 auto;
            background: #3b82f6; color: #fff;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
        }
        .msg-cta-title { font-weight: 700; margin-bottom: .15rem; }
        .msg-cta-sub   { font-size: .85rem; color: #6b7280; }

        /* ─── Empty state ─────────────────────────────────── */
        .empty-state {
            text-align: center; padding: 2.5rem 1rem;
            border-radius: 18px; background: #f9fafb;
            border: 2px dashed #e5e7eb;
        }
        .empty-state-ico { font-size: 2.8rem; color: #d1d5db; margin-bottom: .75rem; }
        .empty-state-text { color: #6b7280; font-size: .9rem; }

        /* ─── Responsive tweaks ───────────────────────────── */
        @media (max-width: 575.98px) {
            .hero { padding: 1.2rem; }
            .hero-title { font-size: 1.2rem; }
            .kpi-value { font-size: 1.5rem; }
        }
    </style>
    @endpush

    {{-- ── Email verification alert ────────────────────────────── --}}
    @if (!session('status') && Auth::user() && !Auth::user()->hasVerifiedEmail())
        <div class="d-alert d-alert-warning d-flex align-items-start gap-2 mb-3" role="alert">
            <i class="bi bi-envelope-exclamation-fill mt-1"></i>
            <div>
                Your email address is not verified. Please check your inbox, or
                <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-warning-emphasis fw-semibold">
                        resend verification email
                    </button>.
                </form>
            </div>
        </div>
    @endif

    @if (session('status') === 'verification-link-sent')
        <div class="d-alert d-alert-success d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            A new verification link has been sent to your email address.
        </div>
    @endif

    @if (cartTotalItems() > 0)
        <div class="d-alert d-alert-info d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-cart-fill fs-5"></i>
            <div>
                You have <strong>{{ cartTotalItems() }}</strong> item{{ cartTotalItems() > 1 ? 's' : '' }} in your cart.
                <a href="{{ route('web.cart') }}" class="fw-semibold ms-1 text-primary">View cart &amp; checkout &rarr;</a>
            </div>
        </div>
    @endif

    {{-- ── Welcome Hero ─────────────────────────────────────────── --}}
    <div class="hero mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                @php
                    $hour = now()->hour;
                    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                @endphp
                <div class="hero-title">{{ $greeting }}, {{ Auth::user()->first_name ?? Auth::user()->full_name }}! 👋</div>
                <div class="hero-sub">{{ now()->format('l, F j, Y') }} &mdash; Keep up the great work!</div>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <input id="globalSearch"
                    class="hero-search d-none d-md-block"
                    placeholder="&#128269;  Search courses &amp; lessons…"
                    autocomplete="off" />
                <a href="{{ route('web.courses') }}" class="btn btn-light fw-bold" style="border-radius:12px;">
                    <i class="bi bi-compass me-1"></i> Browse
                </a>
            </div>
        </div>
    </div>

    {{-- ── KPI Cards ────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card kpi">
                <div class="kpi-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="kpi-ico kpi-ico-purple"><i class="bi bi-journal-richtext"></i></span>
                        <span class="kpi-tag" style="background:var(--d-primary-50);color:var(--d-primary-dark)">Enrolled</span>
                    </div>
                    <div class="kpi-value" id="kpiEnrolled">{{ $enrolledCourses }}</div>
                    <div class="kpi-label mt-1">Active courses</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card kpi">
                <div class="kpi-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="kpi-ico kpi-ico-green"><i class="bi bi-clock-history"></i></span>
                        <span class="kpi-tag" style="background:#d1fae5;color:#065f46">30 days</span>
                    </div>
                    <div class="kpi-value" id="kpiHours">{{ round($watchedTime / 3600, 1) }}<span style="font-size:1rem;font-weight:600"> h</span></div>
                    <div class="kpi-label mt-1">Watch time</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card kpi">
                <div class="kpi-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="kpi-ico kpi-ico-amber"><i class="bi bi-trophy"></i></span>
                        <span class="kpi-tag" style="background:#fef3c7;color:#92400e">Avg</span>
                    </div>
                    <div class="kpi-value" id="kpiCompletion">{{ $completionRate }}<span style="font-size:1rem;font-weight:600">%</span></div>
                    <div class="kpi-label mt-1">Completion rate</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card kpi">
                <div class="kpi-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="kpi-ico kpi-ico-blue"><i class="bi bi-wallet2"></i></span>
                        <span class="kpi-tag" style="background:#dbeafe;color:#1d4ed8">Total</span>
                    </div>
                    <div class="kpi-value" id="kpiSpend"><span style="font-size:1rem;font-weight:600">$</span>{{ number_format($totalSpent, 2) }}</div>
                    <div class="kpi-label mt-1">Spent to date</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Charts Row ───────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card chart-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <div class="chart-card-title">Course completion</div>
                        <div class="d-flex gap-2">
                            <select id="progressSort" class="form-select form-select-sm" style="width:auto;border-radius:10px;">
                                <option value="desc">Highest first</option>
                                <option value="asc">Lowest first</option>
                            </select>
                            <button class="btn btn-sm btn-outline-secondary" id="refreshProgress" style="border-radius:10px;" title="Refresh">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </div>
                    @if(count($courseCompletionData) > 0)
                        <canvas id="completionBar" height="130"></canvas>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-ico"><i class="bi bi-bar-chart-line"></i></div>
                            <div class="fw-semibold mb-1">No course data yet</div>
                            <div class="empty-state-text">Enrol in a course and your progress will appear here.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card chart-card">
                <div class="card-body">
                    <div class="chart-card-title mb-1">Watch time</div>
                    <div style="font-size:.78rem;color:#9ca3af" class="mb-3">Minutes per day &mdash; last 14 days</div>
                    <canvas id="watchLine" height="160"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── New Videos ───────────────────────────────────────────── --}}
    <section class="mb-4">
        <div class="d-flex align-items-center justify-content-between section-head">
            <span class="section-title">New videos from your courses</span>
            <a class="section-link" href="{{ route('student.new-videos') }}">View all &rarr;</a>
        </div>
        <div class="card" style="border:0;border-radius:18px;box-shadow:0 4px 20px rgba(0,0,0,.05);">
            <div class="card-body p-2" id="newVideosList">
                <div class="text-center text-muted py-4" style="font-size:.9rem;">
                    <i class="bi bi-hourglass-split me-1"></i> Loading…
                </div>
            </div>
        </div>
    </section>

    {{-- ── My Courses ───────────────────────────────────────────── --}}
    <section class="mb-4">
        <div class="d-flex align-items-center justify-content-between section-head">
            <span class="section-title">My Courses</span>
            <div class="d-flex align-items-center gap-2">
                <div class="input-group input-group-sm" style="max-width:240px;">
                    <span class="input-group-text bg-white" style="border-radius:12px 0 0 12px;border-right:0;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input id="courseSearch" class="form-control" placeholder="Filter courses…"
                        style="border-radius:0 12px 12px 0;border-left:0;" />
                </div>
                <a class="section-link" href="{{ route('student.my-courses') }}">View all &rarr;</a>
            </div>
        </div>
        <div class="row g-3" id="coursesGrid"></div>
    </section>

    {{-- ── Payments ─────────────────────────────────────────────── --}}
    <section class="mb-4">
        <div class="d-flex align-items-center justify-content-between section-head">
            <span class="section-title">Recent Payments</span>
            <a class="section-link" href="{{ route('student.payments') }}">View all &rarr;</a>
        </div>
        <div class="card" style="border:0;border-radius:18px;box-shadow:0 4px 20px rgba(0,0,0,.05);overflow:hidden;">
            @if(count($paymentData) > 0)
                <div class="table-responsive">
                    <table class="table pay-table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Item</th>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (array_slice($paymentData, 0, 6) as $payment)
                                <tr>
                                    <td class="text-nowrap" style="color:#6b7280;font-size:.85rem;">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ \Carbon\Carbon::parse($payment['date'])->format('M d, Y') }}
                                    </td>
                                    <td class="fw-semibold">{{ $payment['item_title'] }}</td>
                                    <td>
                                        @php
                                            $typeClass = match(strtolower($payment['type'] ?? '')) {
                                                'course'  => 'pay-type-course',
                                                'lesson'  => 'pay-type-lesson',
                                                default   => 'pay-type-default',
                                            };
                                        @endphp
                                        <span class="pay-type-badge {{ $typeClass }}">{{ $payment['type'] }}</span>
                                    </td>
                                    <td class="text-end pay-amount">
                                        @if($payment['amount'] !== null)
                                            ${{ number_format((float)$payment['amount'], 2) }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state m-3">
                    <div class="empty-state-ico"><i class="bi bi-receipt"></i></div>
                    <div class="fw-semibold mb-1">No payments yet</div>
                    <div class="empty-state-text">Your purchase history will appear here.</div>
                </div>
            @endif
        </div>
    </section>

    {{-- ── Messages CTA ─────────────────────────────────────────── --}}
    <section class="mb-4">
        <div class="msg-cta">
            <div class="msg-cta-ico"><i class="bi bi-chat-dots-fill"></i></div>
            <div class="flex-grow-1 min-w-0">
                <div class="msg-cta-title">Messages &amp; Support</div>
                <div class="msg-cta-sub">Chat directly with your educators about coursework, feedback, and scheduling.</div>
            </div>
            <a href="{{ route('chat.index') }}" class="btn btn-primary fw-bold text-nowrap flex-shrink-0" style="border-radius:12px;">
                <i class="bi bi-chat me-1"></i> Open inbox
            </a>
        </div>
    </section>

    @push('scripts')
    <script>
        window.courseCompletionData = @json($courseCompletionData);
        window.watchTimeLabels      = @json($watchTimeLabels);
        window.watchTimeData        = @json($watchTimeData);
        window.myCourses            = @json($myCourses);
        window.newVideos            = @json($newVideosFeed);
        window.payments             = @json($paymentData);
    </script>
    <div id="dashboard-data"
         data-course-details-url="{{ route('student.course_details', ['course_id' => '_COURSE_ID_']) }}">
    </div>
    @endpush

</x-student-layout>
