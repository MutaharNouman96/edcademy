<x-admin-layout>
    <section class="dashboard-hero mb-4">
        <div class="hero-main">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <span class="eyebrow"><i class="bi bi-speedometer2 me-1"></i>Admin analytics</span>
                    <h1 class="hero-title mb-2">Dashboard Overview</h1>
                    <p class="hero-subtitle mb-0">Live platform performance for <strong>{{ $timeRangeLabel }}</strong>.</p>
                </div>
                <div class="d-flex flex-wrap gap-2 align-content-start">
                    <a href="{{ route('admin.manage.students') }}" class="btn btn-light btn-sm hero-action">
                        <i class="bi bi-people me-1"></i>Students
                    </a>
                    <a href="{{ route('admin.manage.educators') }}" class="btn btn-light btn-sm hero-action">
                        <i class="bi bi-person-workspace me-1"></i>Educators
                    </a>
                    <a href="{{ route('admin.manage.courses') }}" class="btn btn-light btn-sm hero-action">
                        <i class="bi bi-book me-1"></i>Courses
                    </a>
                    <a href="{{ route('admin.manage.lessons') }}" class="btn btn-light btn-sm hero-action">
                        <i class="bi bi-collection-play me-1"></i>Lessons
                    </a>
                </div>
            </div>
        </div>
    </section>

    <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-bar p-3 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-6 col-lg-3">
                <label class="form-label fw-semibold mb-1">Time range</label>
                <select class="form-select" name="time_range">
                    <option value="this_month" {{ request('time_range') == 'this_month' ? 'selected' : '' }}>This month</option>
                    <option value="last_7_days" {{ request('time_range') == 'last_7_days' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="last_30_days" {{ request('time_range') == 'last_30_days' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="quarter_to_date" {{ request('time_range') == 'quarter_to_date' ? 'selected' : '' }}>Quarter to date</option>
                    <option value="year_to_date" {{ request('time_range') == 'year_to_date' ? 'selected' : '' }}>Year to date</option>
                </select>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <label class="form-label fw-semibold mb-1">Segment</label>
                <select class="form-select" name="segment">
                    <option value="all_users" {{ request('segment') == 'all_users' || !request('segment') ? 'selected' : '' }}>All users</option>
                    <option value="students" {{ request('segment') == 'students' ? 'selected' : '' }}>Students</option>
                    <option value="educators" {{ request('segment') == 'educators' ? 'selected' : '' }}>Educators</option>
                    <option value="premium_educators" {{ request('segment') == 'premium_educators' ? 'selected' : '' }}>Premium educators</option>
                </select>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <label class="form-label fw-semibold mb-1">Region</label>
                <select class="form-select" name="region">
                    <option value="all" {{ request('region') == 'all' || !request('region') ? 'selected' : '' }}>All regions</option>
                    <option value="uae" {{ request('region') == 'uae' ? 'selected' : '' }}>UAE</option>
                    <option value="gcc" {{ request('region') == 'gcc' ? 'selected' : '' }}>GCC</option>
                    <option value="eu" {{ request('region') == 'eu' ? 'selected' : '' }}>EU</option>
                    <option value="us" {{ request('region') == 'us' ? 'selected' : '' }}>US</option>
                </select>
            </div>
            <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap justify-content-lg-end gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                <button type="submit" class="btn btn-brand">
                    <i class="bi bi-funnel me-1"></i>Apply filters
                </button>
            </div>
        </div>
    </form>

    <div class="row g-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Total Users</div>
                        <div class="kpi-value">{{ number_format($totalUsers) }}</div>
                        <div class="delta {{ $usersGrowthMeta['class'] }} mt-1">
                            <i class="bi {{ $usersGrowthMeta['icon'] }} me-1"></i>{{ $usersGrowthMeta['label'] }}
                        </div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-people-fill"></i></span>
                </div>
                <div class="mt-3">
                    <div class="progress brand" style="height: 7px">
                        <div class="progress-bar" role="progressbar" style="width: {{ $newUsersRatio }}%"></div>
                    </div>
                    <small class="text-muted">New this period: {{ number_format($newUsersCurrentPeriod) }}</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Active Educators</div>
                        <div class="kpi-value">{{ number_format($activeEducators) }}</div>
                        <div class="delta {{ $educatorsGrowthMeta['class'] }} mt-1">
                            <i class="bi {{ $educatorsGrowthMeta['icon'] }} me-1"></i>{{ $educatorsGrowthMeta['label'] }}
                        </div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-person-workspace"></i></span>
                </div>
                <div class="mt-3 d-flex flex-wrap gap-2">
                    <span class="chip soft"><i class="bi bi-star-fill me-1"></i>Premium {{ $activeEducators > 0 ? round(($premiumEducators / $activeEducators) * 100) : 0 }}%</span>
                    <span class="chip soft"><i class="bi bi-camera-video-fill me-1"></i>Live {{ $liveEducators }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Revenue</div>
                        <div class="kpi-value">$ {{ number_format($totalRevenue, 0) }}</div>
                        <div class="delta {{ $revenueGrowthMeta['class'] }} mt-1">
                            <i class="bi {{ $revenueGrowthMeta['icon'] }} me-1"></i>{{ $revenueGrowthMeta['label'] }}
                        </div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-cash-coin"></i></span>
                </div>
                <div class="mt-3">
                    <div class="progress brand" style="height: 7px">
                        <div class="progress-bar" role="progressbar" style="width: {{ $revenuePeriodRatio }}%"></div>
                    </div>
                    <small class="text-muted">In period: $ {{ number_format($revenueCurrentPeriod, 0) }}</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Trust & Safety</div>
                        <div class="kpi-value">{{ $totalDisputes + $flaggedContent }}</div>
                        <small class="text-muted">Disputes + low-rated reviews</small>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-shield-exclamation"></i></span>
                </div>
                <div class="mt-3 d-flex flex-wrap gap-2">
                    <span class="chip soft"><i class="bi bi-chat-dots me-1"></i>Disputes {{ $totalDisputes }}</span>
                    <span class="chip soft"><i class="bi bi-flag me-1"></i>Flagged {{ $flaggedContent }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-12 col-lg-4">
            <div class="kpi-card p-3 h-100">
                <h5 class="section-title mb-3"><i class="bi bi-lightning-charge text-warning me-2"></i>Operations Snapshot</h5>
                <div class="mini-metric mb-3">
                    <small class="text-muted d-block mb-1">Pending payouts</small>
                    <div class="mini-value">$ {{ number_format($pendingPayouts, 0) }}</div>
                    <a href="{{ route('admin.payouts.index') }}" class="mini-link">Open payouts <i class="bi bi-arrow-right-short"></i></a>
                </div>
                <div class="mini-metric mb-3">
                    <small class="text-muted d-block mb-1">Recent disputes</small>
                    <div class="mini-value">{{ number_format($totalDisputes) }}</div>
                    <a href="{{ route('admin.financial-reports.index') }}" class="mini-link">Open financial reports <i class="bi bi-arrow-right-short"></i></a>
                </div>
                <div class="mini-metric">
                    <small class="text-muted d-block mb-1">Low-rated content flags</small>
                    <div class="mini-value">{{ number_format($flaggedContent) }}</div>
                    <a href="{{ route('admin.visual-reports.index') }}" class="mini-link">Open visual reports <i class="bi bi-arrow-right-short"></i></a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="section-title mb-0">
                        <i class="bi bi-credit-card me-2 text-success"></i>Recent Payments
                    </h5>
                    <a href="{{ route('admin.payouts.index') }}" class="btn btn-sm btn-brand">
                        <i class="bi bi-arrow-right-short"></i>View All
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle modern-table">
                        <thead>
                            <tr>
                                <th>Transaction</th>
                                <th>Student</th>
                                <th>Educator</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                                <tr>
                                    <td><span class="mono">{{ $payment['transaction_id'] }}</span></td>
                                    <td>{{ $payment['student'] }}</td>
                                    <td>{{ $payment['educator'] }}</td>
                                    <td class="fw-semibold">{{ $payment['amount'] }}</td>
                                    <td class="text-muted">{{ $payment['date'] }}</td>
                                    <td>
                                        @if(strtolower($payment['status']) === 'completed')
                                            <span class="badge text-bg-success-subtle text-success-emphasis">Completed</span>
                                        @elseif(strtolower($payment['status']) === 'pending')
                                            <span class="badge text-bg-warning-subtle text-warning-emphasis">Pending</span>
                                        @elseif(strtolower($payment['status']) === 'failed')
                                            <span class="badge text-bg-danger-subtle text-danger-emphasis">Failed</span>
                                        @else
                                            <span class="badge text-bg-secondary-subtle text-secondary-emphasis">{{ $payment['status'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No payments found for selected filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="section-title mb-0">
                        <i class="bi bi-person-plus me-2 text-info"></i>Recently Joined Educators
                    </h5>
                    <a href="{{ route('admin.manage.educators') }}" class="btn btn-sm btn-brand">
                        <i class="bi bi-arrow-right-short"></i>View All
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle modern-table">
                        <thead>
                            <tr>
                                <th>Educator</th>
                                <th>Email</th>
                                <th>Courses</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($newEducatorsList as $educator)
                                <tr>
                                    <td class="fw-semibold">{{ $educator['name'] }}</td>
                                    <td class="text-muted">{{ $educator['email'] }}</td>
                                    <td>{{ $educator['courses_count'] }} {{ $educator['courses_count'] === 1 ? 'course' : 'courses' }}</td>
                                    <td>
                                        @if(strtolower($educator['status']) === 'approved')
                                            <span class="badge text-bg-success-subtle text-success-emphasis">Approved</span>
                                        @elseif(strtolower($educator['status']) === 'pending')
                                            <span class="badge text-bg-warning-subtle text-warning-emphasis">Pending</span>
                                        @elseif(strtolower($educator['status']) === 'rejected')
                                            <span class="badge text-bg-danger-subtle text-danger-emphasis">Rejected</span>
                                        @else
                                            <span class="badge text-bg-secondary-subtle text-secondary-emphasis">{{ $educator['status'] }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $educator['joined'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No new educators found for selected filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center text-muted small mt-4">
        Updated just now • Analytics are provisional and may change after reconciliation.
    </div>

    @push('styles')
        <style>
            :root {
                --brand: #0b3c77;
                --brand-700: #093362;
                --ink: #0f172a;
                --muted: #6b7280;
                --card-shadow: 0 10px 30px rgba(11, 60, 119, 0.08);
                --good: #16a34a;
                --warn: #d97706;
                --bad: #dc2626;
                --flat: #6b7280;
            }

            .dashboard-hero .hero-main {
                border-radius: 18px;
                padding: 1.25rem 1.25rem;
                background: linear-gradient(130deg, rgba(11, 60, 119, 0.96), rgba(30, 108, 190, 0.94));
                color: #fff;
                box-shadow: 0 18px 38px rgba(11, 60, 119, 0.22);
            }

            .eyebrow {
                font-size: 0.78rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                opacity: 0.85;
            }

            .hero-title {
                font-size: clamp(1.4rem, 2.6vw, 2rem);
                font-weight: 800;
            }

            .hero-subtitle {
                opacity: 0.9;
            }

            .hero-action {
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .kpi-card {
                border: 0;
                border-radius: 1rem;
                background: #fff;
                box-shadow: var(--card-shadow);
            }

            .kpi-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                background: var(--brand);
                box-shadow: 0 6px 16px rgba(11, 60, 119, 0.25);
            }

            .kpi-value {
                font-size: clamp(1.45rem, 2.2vw, 1.8rem);
                font-weight: 800;
                color: var(--ink);
            }

            .kpi-label {
                color: var(--muted);
                font-weight: 600;
            }

            .delta {
                font-size: 0.85rem;
                font-weight: 600;
            }

            .delta.up {
                color: var(--good);
            }

            .delta.down {
                color: var(--bad);
            }

            .delta.flat {
                color: var(--flat);
            }

            .chip {
                border-radius: 9999px;
                padding: 0.28rem 0.65rem;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .chip.soft {
                background: rgba(11, 60, 119, 0.1);
                color: var(--brand);
            }

            .section-title {
                color: var(--ink);
                font-weight: 800;
            }

            .filter-bar {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 1rem;
                box-shadow: var(--card-shadow);
            }

            .progress.brand {
                background: #e7edf6;
            }

            .progress.brand .progress-bar {
                background: linear-gradient(90deg, var(--brand), #1d9bf0);
            }

            .btn-brand {
                background: var(--brand);
                border-color: var(--brand);
                color: #fff;
            }

            .btn-brand:hover {
                background: var(--brand-700);
                border-color: var(--brand-700);
                color: #fff;
            }

            .modern-table thead th {
                color: var(--muted);
                font-weight: 700;
                border-bottom: 1px solid #e5e7eb;
                font-size: 0.84rem;
                text-transform: uppercase;
                letter-spacing: 0.03em;
            }

            .modern-table > tbody > tr > td {
                vertical-align: middle;
                border-color: #eff3f8;
            }

            .mono {
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
                font-size: 0.84rem;
            }

            .mini-metric {
                border: 1px solid #e8edf5;
                border-radius: 12px;
                padding: 0.8rem 0.85rem;
                background: #fbfcff;
            }

            .mini-value {
                font-size: 1.3rem;
                line-height: 1.1;
                font-weight: 800;
                color: var(--ink);
                margin-bottom: 0.1rem;
            }

            .mini-link {
                font-size: 0.86rem;
                color: var(--brand);
                text-decoration: none;
                font-weight: 600;
            }

            .mini-link:hover {
                color: var(--brand-700);
            }

            @media (max-width: 991.98px) {
                .dashboard-hero .hero-main {
                    padding: 1rem;
                }
            }
        </style>
    @endpush
</x-admin-layout>
