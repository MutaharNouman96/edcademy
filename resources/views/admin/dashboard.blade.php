<x-admin-layout>
  
    <div class="filter-bar p-3 mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Time Range</label>
                <select class="form-select">
                    <option selected>This Month</option>
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Quarter to date</option>
                    <option>Year to date</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Segment</label>
                <select class="form-select">
                    <option selected>All Users</option>
                    <option>Students</option>
                    <option>Educators</option>
                    <option>Premium Educators</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Region</label>
                <select class="form-select">
                    <option selected>All</option>
                    <option>UAE</option>
                    <option>GCC</option>
                    <option>EU</option>
                    <option>US</option>
                </select>
            </div>
            <div class="col-md-3 text-md-end">
                <button class="btn btn-brand">
                    <i class="bi bi-funnel me-1"></i>Apply
                </button>
            </div>
        </div>
    </div>

    <!-- KPI Row -->
    <div class="row g-4">
        <!-- Total Users -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Total Users</div>
                        <div class="kpi-value">128,940</div>
                        <div class="delta up mt-1">
                            <i class="bi bi-arrow-up-right"></i> +3.4% vs last month
                        </div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-people-fill"></i></span>
                </div>
                <div class="mt-3">
                    <div class="progress brand" style="height: 6px">
                        <div class="progress-bar" role="progressbar" style="width: 68%"></div>
                    </div>
                    <small class="text-muted">New this month: 6,120</small>
                </div>
            </div>
        </div>

        <!-- Active Educators -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Active Educators</div>
                        <div class="kpi-value">2,147</div>
                        <div class="delta up mt-1">
                            <i class="bi bi-arrow-up-right"></i> +1.9% WoW
                        </div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-person-workspace"></i></span>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <span class="chip soft"><i class="bi bi-star-fill me-1"></i> Premium 38%</span>
                    <span class="chip soft"><i class="bi bi-play-btn-fill me-1"></i> Live 412</span>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Revenue (AED)</div>
                        <div class="kpi-value">AED 1,284,560</div>
                        <div class="delta up mt-1">
                            <i class="bi bi-arrow-up-right"></i> +12.6% MoM
                        </div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-cash-coin"></i></span>
                </div>
                <div class="mt-3">
                    <div class="progress brand" style="height: 6px">
                        <div class="progress-bar" role="progressbar" style="width: 74%"></div>
                    </div>
                    <small class="text-muted">Payouts pending: AED 218,400</small>
                </div>
            </div>
        </div>

        <!-- Disputes & Flagged -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label mb-1">Disputes / Flagged</div>
                        <div class="kpi-value">37 / 112</div>
                        <div class="delta down mt-1">
                            <i class="bi bi-arrow-down-right"></i> −8.0% disputes
                        </div>
                    </div>
                    <span class="kpi-icon"><i class="bi bi-shield-exclamation"></i></span>
                </div>
                <div class="mt-3 d-flex flex-wrap gap-2">
                    <span class="chip soft"><i class="bi bi-chat-dots me-1"></i> Reviews 46</span>
                    <span class="chip soft"><i class="bi bi-file-earmark-text me-1"></i> Docs 28</span>
                    <span class="chip soft"><i class="bi bi-film me-1"></i> Videos 38</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Row -->
    <div class="row g-4 mt-1">
        <!-- Disputes Table -->
        <div class="col-12 col-xl-6">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="section-title mb-0">
                        <i class="bi bi-clipboard2-pulse me-2 text-primary"></i>Open
                        Disputes
                    </h5>
                    <a href="#" class="btn btn-sm btn-brand"><i class="bi bi-arrow-right-short"></i> View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ticket</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Age</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>#D-10492</td>
                                <td>Fatima K.</td>
                                <td>Refund</td>
                                <td>3d</td>
                                <td><span class="badge text-bg-warning">Pending</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>#D-10488</td>
                                <td>Ahmed S.</td>
                                <td>Quality</td>
                                <td>1d</td>
                                <td>
                                    <span class="badge text-bg-primary">In Review</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>#D-10473</td>
                                <td>Sara M.</td>
                                <td>Access</td>
                                <td>6h</td>
                                <td><span class="badge text-bg-success">Resolved</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>#D-10461</td>
                                <td>John P.</td>
                                <td>Other</td>
                                <td>5d</td>
                                <td><span class="badge text-bg-danger">Escalated</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">SLA: 24h first reply • 72h resolution target</small>
            </div>
        </div>

        <!-- Flagged Content -->
        <div class="col-12 col-xl-6">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="section-title mb-0">
                        <i class="bi bi-flag-fill me-2 text-danger"></i>Flagged Content
                    </h5>
                    <a href="#" class="btn btn-sm btn-outline-secondary"><i
                            class="bi bi-shield-check me-1"></i> Moderation Queue</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Owner</th>
                                <th>Reason</th>
                                <th>Signals</th>
                                <th>Queue</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>112</td>
                                <td>Worksheet: Algebra I</td>
                                <td>R. Malik</td>
                                <td>Possible duplicate</td>
                                <td>
                                    <span class="badge text-bg-info">AI-Match 86%</span>
                                </td>
                                <td>
                                    <span class="badge text-bg-primary">Originality</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>109</td>
                                <td>Video: Cell Division</td>
                                <td>L. Pereira</td>
                                <td>TOS language</td>
                                <td><span class="badge text-bg-warning">Toxicity</span></td>
                                <td><span class="badge text-bg-secondary">Safety</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>103</td>
                                <td>Quiz Bank: WWII</td>
                                <td>A. Rahman</td>
                                <td>Copyright claim</td>
                                <td><span class="badge text-bg-danger">Takedown</span></td>
                                <td><span class="badge text-bg-dark">Legal</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Queue load</small>
                        <small class="fw-semibold">62%</small>
                    </div>
                    <div class="progress brand" style="height: 6px">
                        <div class="progress-bar" style="width: 62%"></div>
                    </div>
                </div>
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
