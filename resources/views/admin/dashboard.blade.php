<x-admin-layout>

    <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-bar p-3 mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Time Range</label>
                <select class="form-select" name="time_range">
                    <option value="this_month" {{ request('time_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_7_days" {{ request('time_range') == 'last_7_days' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="last_30_days" {{ request('time_range') == 'last_30_days' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="quarter_to_date" {{ request('time_range') == 'quarter_to_date' ? 'selected' : '' }}>Quarter to date</option>
                    <option value="year_to_date" {{ request('time_range') == 'year_to_date' ? 'selected' : '' }}>Year to date</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Segment</label>
                <select class="form-select" name="segment">
                    <option value="all_users" {{ request('segment') == 'all_users' || !request('segment') ? 'selected' : '' }}>All Users</option>
                    <option value="students" {{ request('segment') == 'students' ? 'selected' : '' }}>Students</option>
                    <option value="educators" {{ request('segment') == 'educators' ? 'selected' : '' }}>Educators</option>
                    <option value="premium_educators" {{ request('segment') == 'premium_educators' ? 'selected' : '' }}>Premium Educators</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Region</label>
                <select class="form-select" name="region">
                    <option value="all" {{ request('region') == 'all' || !request('region') ? 'selected' : '' }}>All</option>
                    <option value="uae" {{ request('region') == 'uae' ? 'selected' : '' }}>UAE</option>
                    <option value="gcc" {{ request('region') == 'gcc' ? 'selected' : '' }}>GCC</option>
                    <option value="eu" {{ request('region') == 'eu' ? 'selected' : '' }}>EU</option>
                    <option value="us" {{ request('region') == 'us' ? 'selected' : '' }}>US</option>
                </select>
            </div>
            <div class="col-md-3 text-md-end">
                <button type="submit" class="btn btn-brand">
                    <i class="bi bi-funnel me-1"></i>Apply
                </button>
            </div>
        </div>
    </form>

    <!-- KPI Row -->
    <div class="row g-4">
        <!-- Total Users -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Total Users</div>
                        <div class="kpi-value">{{ number_format($totalUsers) }}</div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-people-fill"></i></span>
                </div>
                <div class="mt-3">
                    <div class="progress brand" style="height: 6px">
                        <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                    </div>
                    <small class="text-muted">New this period: {{ number_format($newUsersCurrentPeriod) }}</small>
                </div>
            </div>
        </div>

        <!-- Active Educators -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Active Educators</div>
                        <div class="kpi-value">{{ number_format($activeEducators) }}</div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-person-workspace"></i></span>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <span class="chip soft"><i class="bi bi-star-fill me-1"></i> Premium {{ $activeEducators > 0 ? round(($premiumEducators / $activeEducators) * 100) : 0 }}%</span>
                    <span class="chip soft"><i class="bi bi-play-btn-fill me-1"></i> Live {{ $liveEducators }}</span>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Revenue ($)</div>
                        <div class="kpi-value">$ {{ number_format($totalRevenue, 0) }}</div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-cash-coin"></i></span>
                </div>
                <div class="mt-3">
                    <div class="progress brand" style="height: 6px">
                        <div class="progress-bar" role="progressbar" style="width: 80%"></div>
                    </div>
                    <small class="text-muted">Revenue this period: $ {{ number_format($revenueCurrentPeriod, 0) }}</small>
                </div>
            </div>
        </div>

        <!-- Disputes & Flagged -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Disputes / Flagged</div>
                        <div class="kpi-value">{{ $totalDisputes }} / {{ $flaggedContent }}</div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-shield-exclamation"></i></span>
                </div>
                <div class="mt-3 d-flex flex-wrap gap-2">
                    <span class="chip soft"><i class="bi bi-chat-dots me-1"></i> Reviews {{ $totalDisputes }}</span>
                    <span class="chip soft"><i class="bi bi-file-earmark-text me-1"></i> Docs {{ $flaggedContent }}</span>
                    <span class="chip soft"><i class="bi bi-film me-1"></i> Videos 0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Row -->
    <div class="row g-4 mt-1">
        <!-- Recent Payments -->
        <div class="col-12 col-xl-6">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="section-title mb-0">
                        <i class="bi bi-credit-card me-2 text-success"></i>Recent
                        Payments
                    </h5>
                    <a href="#" class="btn btn-sm btn-brand"><i class="bi bi-arrow-right-short"></i> View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction</th>
                                <th>Student</th>
                                <th>Educator</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $payment['transaction_id'] }}</td>
                                <td>{{ $payment['student'] }}</td>
                                <td>{{ $payment['educator'] }}</td>
                                <td>{{ $payment['amount'] }}</td>
                                <td>
                                    @if(strtolower($payment['status']) === 'completed')
                                        <span class="badge text-bg-success">Completed</span>
                                    @elseif(strtolower($payment['status']) === 'pending')
                                        <span class="badge text-bg-warning">Pending</span>
                                    @elseif(strtolower($payment['status']) === 'failed')
                                        <span class="badge text-bg-danger">Failed</span>
                                    @else
                                        <span class="badge text-bg-secondary">{{ $payment['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No payments found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">Latest payment transactions</small>
            </div>
        </div>

        <!-- New Educators -->
        <div class="col-12 col-xl-6">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="section-title mb-0">
                        <i class="bi bi-person-plus me-2 text-info"></i>New
                        Educators
                    </h5>
                    <a href="#" class="btn btn-sm btn-brand"><i class="bi bi-arrow-right-short"></i> View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Educator</th>

                                <th>Courses</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($newEducatorsList as $index => $educator)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $educator['name'] }}
                                </br>
                                {{ $educator['email'] }}
                                </td>
                                    <td>{{ $educator['courses_count'] }} course</td>
                                <td>
                                    @if(strtolower($educator['status']) === 'approved')
                                        <span class="badge text-bg-success">Approved</span>
                                    @elseif(strtolower($educator['status']) === 'pending')
                                        <span class="badge text-bg-warning">Pending</span>
                                    @elseif(strtolower($educator['status']) === 'rejected')
                                        <span class="badge text-bg-danger">Rejected</span>
                                    @else
                                        <span class="badge text-bg-secondary">{{ $educator['status'] }}</span>
                                    @endif
                                </td>
                                <td>{{ $educator['joined'] }}</td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No new educators</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">Recently registered educators</small>
            </div>
        </div>
    </div>

    <!-- Footer note -->
    <div class="text-center text-muted small mt-4">
        Updated just now • Data is provisional and may change after
        reconciliation
    </div>

    @push('styles')
        <style>
            :root {
                --brand: #0b3c77;
                /* dark blue */
                --brand-700: #093362;
                --brand-600: #0c4b94;
                --ink: #0f172a;
                --muted: #6b7280;
                --card-shadow: 0 10px 30px rgba(11, 60, 119, 0.08);
                --soft: #f6f8fb;
                --good: #16a34a;
                --warn: #d97706;
                --bad: #dc2626;
            }

            body {
                background: var(--soft);
            }

            .navbar {
                background: var(--brand);
            }

            .navbar .navbar-brand,
            .navbar .nav-link,
            .navbar .form-control::placeholder {
                color: #fff;
            }

            .navbar .nav-link {
                opacity: 0.9;
            }

            .navbar .nav-link:hover {
                opacity: 1;
            }

            .brand-badge {
                background: #fff;
                color: var(--brand);
                font-weight: 700;
                padding: 0.25rem 0.5rem;
                border-radius: 0.5rem;
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
                font-size: 1.75rem;
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

            .chip {
                border-radius: 9999px;
                padding: 0.25rem 0.6rem;
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

            .table thead th {
                color: var(--muted);
                font-weight: 700;
                border-bottom: 1px solid #e5e7eb;
            }

            .table>tbody>tr>td {
                vertical-align: middle;
            }

            .btn-brand {
                background: var(--brand);
                border-color: var(--brand);
                color: #fff;
            }

            .btn-brand:hover {
                background: var(--brand-700);
                border-color: var(--brand-700);
            }

            .filter-bar {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 1rem;
                box-shadow: var(--card-shadow);
            }

            .progress.brand .progress-bar {
                background: var(--brand);
            }
        </style>
    @endpush
</x-admin-layout>
