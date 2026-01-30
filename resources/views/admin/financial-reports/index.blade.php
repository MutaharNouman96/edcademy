<x-admin-layout>

<h4 class="mb-3">Financial Reports</h4>

<!-- Date Range Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.financial-reports.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search me-1"></i>Generate Report
                </button>
                <a href="{{ route('admin.financial-reports.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Financial Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 mb-3">
        <h5 class="section-title mb-3">
            <i class="bi bi-bar-chart-line me-2"></i>Financial Overview ({{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }})
        </h5>
    </div>

    <!-- Earnings -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Total Earnings</div>
                    <div class="kpi-value">AED {{ number_format($totalEarnings, 2) }}</div>
                    <small class="text-muted">Net amount to educators</small>
                </div>
                <span class="kpi-icon"><i class="bi bi-cash-coin"></i></span>
            </div>
        </div>
    </div>

    <!-- Gross Revenue -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Gross Revenue</div>
                    <div class="kpi-value">AED {{ number_format($totalGross, 2) }}</div>
                    <small class="text-muted">Total revenue before commission</small>
                </div>
                <span class="kpi-icon"><i class="bi bi-graph-up"></i></span>
            </div>
        </div>
    </div>

    <!-- Platform Commission -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Platform Commission</div>
                    <div class="kpi-value">AED {{ number_format($totalPlatformCommission, 2) }}</div>
                    <small class="text-muted">Platform's share</small>
                </div>
                <span class="kpi-icon"><i class="bi bi-building"></i></span>
            </div>
        </div>
    </div>

    <!-- Net Revenue -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Net Revenue</div>
                    <div class="kpi-value">AED {{ number_format($netRevenue, 2) }}</div>
                    <small class="text-muted">Platform profit</small>
                </div>
                <span class="kpi-icon"><i class="bi bi-trophy"></i></span>
            </div>
        </div>
    </div>
</div>

<!-- Payouts Summary -->
<div class="row g-4 mb-4">
    <div class="col-12 mb-3">
        <h5 class="section-title mb-3">
            <i class="bi bi-cash me-2"></i>Payouts Overview
        </h5>
    </div>

    <!-- Total Payouts -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Total Payouts</div>
                    <div class="kpi-value">AED {{ number_format($totalPayouts, 2) }}</div>
                    <small class="text-muted">Amount paid to educators</small>
                </div>
                <span class="kpi-icon"><i class="bi bi-wallet2"></i></span>
            </div>
        </div>
    </div>

    <!-- Pending Payouts -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Pending Payouts</div>
                    <div class="kpi-value">AED {{ number_format($pendingPayouts, 2) }}</div>
                    <small class="text-muted">Awaiting processing</small>
                </div>
                <span class="kpi-icon"><i class="bi bi-clock"></i></span>
            </div>
        </div>
    </div>

    <!-- Processed Payouts -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Processed Payouts</div>
                    <div class="kpi-value">AED {{ number_format($processedPayouts, 2) }}</div>
                    <small class="text-muted">Successfully paid</small>
                </div>
                <span class="kpi-icon"><i class="bi bi-check-circle"></i></span>
            </div>
        </div>
    </div>
</div>

<!-- Additional Insights -->
<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Key Insights</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Commission Rate:</strong> {{ $totalGross > 0 ? number_format(($totalPlatformCommission / $totalGross) * 100, 2) : 0 }}%</p>
                        <p><strong>Payout Efficiency:</strong> {{ $totalEarnings > 0 ? number_format(($totalPayouts / $totalEarnings) * 100, 2) : 0 }}% of earnings paid out</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Pending vs Processed:</strong> {{ $totalPayouts > 0 ? number_format(($pendingPayouts / $totalPayouts) * 100, 2) : 0 }}% of payouts pending</p>
                        <p><strong>Platform Margin:</strong> {{ $totalGross > 0 ? number_format(($netRevenue / $totalGross) * 100, 2) : 0 }}% of gross revenue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-admin-layout>