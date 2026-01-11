<x-admin-layout>

<h4 class="mb-3">Payouts Management</h4>

<!-- Summary Cards -->
@if(isset($totalEarnings))
<div class="row g-4 mb-4">
    <div class="col-12 mb-3">
        <h5 class="section-title mb-3">
            <i class="bi bi-bar-chart-line me-2"></i>Earnings Overview
        </h5>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Total Earnings</div>
                    <div class="kpi-value">AED {{ number_format($totalEarnings, 2) }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-cash-coin"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Pending</div>
                    <div class="kpi-value">AED {{ number_format($pendingEarnings, 2) }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-clock"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Approved</div>
                    <div class="kpi-value">AED {{ number_format($approvedEarnings, 2) }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-check-circle"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label mb-1">Paid</div>
                    <div class="kpi-value">AED {{ number_format($paidEarnings, 2) }}</div>
                </div>
                <span class="kpi-icon"><i class="bi bi-check2-all"></i></span>
            </div>
        </div>
    </div>
</div>
@endif

<!-- View Tabs -->
<ul class="nav nav-tabs mb-4" id="payoutTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $currentView ?? 'earnings' == 'earnings' ? 'active' : '' }}"
                href="{{ route('admin.payouts.index', ['view' => 'earnings']) }}">
            <i class="bi bi-graph-up me-1"></i>Earnings
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $currentView ?? 'earnings' == 'payouts' ? 'active' : '' }}"
                href="{{ route('admin.payouts.index', ['view' => 'payouts']) }}">
            <i class="bi bi-wallet me-1"></i>Payouts
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $currentView ?? 'earnings' == 'educator_payouts' ? 'active' : '' }}"
                href="{{ route('admin.payouts.index', ['view' => 'educator_payouts']) }}">
            <i class="bi bi-credit-card me-1"></i>Educator Payouts
        </a>
    </li>
</ul>

<div class="tab-content" id="payoutTabsContent">

    <!-- Earnings Tab -->
    <div class="{{ $currentView ?? 'earnings' == 'earnings' ? '' : 'd-none' }}"
         id="earnings" role="tabpanel" aria-labelledby="earnings-tab">

        <div class="kpi-card">
            <div class="p-3 border-bottom">
                <div class="tab-section-header">
                    <h5 class="section-title">
                        <i class="bi bi-graph-up me-2"></i>Individual Earnings Management
                    </h5>
                    <p class="text-muted small">Track and manage individual earnings from courses, sessions, and resources</p>
                </div>
            </div>

            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Earnings Filters</h6>
                </div>
                <form method="GET" action="{{ route('admin.payouts.index') }}" class="filter-bar p-3 mb-0">
            <input type="hidden" name="view" value="earnings">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Source</label>
                    <select name="source_type" class="form-select">
                        <option value="">All Sources</option>
                        <option value="course" {{ request('source_type') == 'course' ? 'selected' : '' }}>Course</option>
                        <option value="session" {{ request('source_type') == 'session' ? 'selected' : '' }}>Session</option>
                        <option value="resource" {{ request('source_type') == 'resource' ? 'selected' : '' }}>Resource</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Educator</label>
                    <select name="educator_id" class="form-select">
                        <option value="">All Educators</option>
                        @if(isset($educators))
                        @foreach($educators as $educator)
                        <option value="{{ $educator->id }}" {{ request('educator_id') == $educator->id ? 'selected' : '' }}>
                            {{ $educator->full_name }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-2 text-md-end">
                    <button class="btn btn-brand me-2">Apply Filters</button>
                    <a href="{{ route('admin.payouts.index', ['view' => 'earnings']) }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </div>
                </form>
            </div>

            <div class="p-3">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all-earnings"></th>
                            <th>Educator</th>
                            <th>Source</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Earned Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($earnings ?? [] as $earning)
                            <tr>
                                <td><input type="checkbox" class="earning-checkbox" value="{{ $earning->id }}"></td>
                                <td>{{ $earning->educator ? $earning->educator->full_name : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($earning->source_type ?? 'N/A') }}</span>
                                </td>
                                <td>{{ Str::limit($earning->description, 50) }}</td>
                                <td>
                                    <strong>AED {{ number_format($earning->net_amount, 2) }}</strong>
                                    @if($earning->gross_amount != $earning->net_amount)
                                    <br><small class="text-muted">Gross: {{ number_format($earning->gross_amount, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge text-bg-{{
                                        $earning->status === 'paid' ? 'success' :
                                        ($earning->status === 'approved' ? 'info' :
                                        ($earning->status === 'pending' ? 'warning' : 'danger'))
                                    }}">
                                        {{ ucfirst($earning->status) }}
                                    </span>
                                </td>
                                <td>{{ $earning->earned_at ? $earning->earned_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.earnings.status', $earning->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <option value="pending" {{ $earning->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $earning->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="paid" {{ $earning->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="cancelled" {{ $earning->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No earnings found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Actions -->
                @if($earnings ?? []->count() > 0)
                <div class="mt-3 d-flex gap-2">
                    <form method="POST" action="{{ route('admin.earnings.bulk-update') }}" id="bulk-earnings-form">
                        @csrf
                        <input type="hidden" name="earning_ids" id="bulk-earning-ids">
                        <div class="input-group">
                            <select name="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="pending">Mark as Pending</option>
                                <option value="approved">Mark as Approved</option>
                                <option value="paid">Mark as Paid</option>
                                <option value="cancelled">Mark as Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-brand" id="bulk-update-btn" disabled>
                                Apply to Selected
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Pagination -->
                @if(isset($earnings) && $earnings->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $earnings->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payouts Tab -->
    <div class="mt-5 {{ $currentView ?? 'earnings' == 'payouts' ? '' : 'd-none' }}"
         id="payouts" role="tabpanel" aria-labelledby="payouts-tab">
        <div class="kpi-card">
            <div class="p-3 border-bottom">
                <div class="tab-section-header">
                    <h5 class="section-title">
                        <i class="bi bi-wallet me-2"></i>Payout Batch Management
                    </h5>
                    <p class="text-muted small">Manage payout batches and process payments to educators</p>
                </div>
            </div>

            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Payout Filters</h6>
                </div>
                <form method="GET" action="{{ route('admin.payouts.index') }}" class="filter-bar p-3 mb-0">
            <input type="hidden" name="view" value="payouts">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Educator</label>
                    <select name="educator_id" class="form-select">
                        <option value="">All Educators</option>
                        @if(isset($educators))
                        @foreach($educators as $educator)
                        <option value="{{ $educator->id }}" {{ request('educator_id') == $educator->id ? 'selected' : '' }}>
                            {{ $educator->full_name }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-2 text-md-end">
                    <button class="btn btn-brand me-2">Apply Filters</button>
                    <a href="{{ route('admin.payouts.index', ['view' => 'payouts']) }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </div>
                </form>
            </div>

            <div class="p-3">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Educator</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>Processed</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($payouts ?? [] as $payout)
                            <tr>
                                <td>{{ $payout->educator ? $payout->educator->full_name : 'N/A' }}</td>
                                <td><strong>AED {{ number_format($payout->amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge text-bg-{{
                                        $payout->status === 'completed' ? 'success' :
                                        ($payout->status === 'pending' ? 'warning' : 'danger')
                                    }}">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($payout->description, 50) }}</td>
                                <td>{{ $payout->created_at->format('M d, Y') }}</td>
                                <td>{{ $payout->processed_at ? $payout->processed_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @if($payout->status === 'pending')
                                    <a href="{{ route('admin.payouts.show', $payout) }}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-success" onclick="processPayout({{ $payout->id }})">
                                        <i class="bi bi-check"></i>
                                    </button>
                                    @else
                                    <a href="{{ route('admin.payouts.show', $payout) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No payouts found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($payouts) && $payouts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $payouts->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Educator Payouts Tab -->
    <div class="mt-5 {{ $currentView ?? 'earnings' == 'educator_payouts' ? '' : 'd-none' }}"
         id="educator-payouts" role="tabpanel" aria-labelledby="educator-payouts-tab">

        <div class="kpi-card">
            <div class="p-3 border-bottom">
                <div class="tab-section-header">
                    <h5 class="section-title">
                        <i class="bi bi-credit-card me-2"></i>Educator Payout Transactions
                    </h5>
                    <p class="text-muted small">View completed payout transactions and payment processing history</p>
                </div>
            </div>

            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Educator Payout Filters</h6>
                </div>
                <form method="GET" action="{{ route('admin.payouts.index') }}" class="filter-bar p-3 mb-0">
            <input type="hidden" name="view" value="educator_payouts">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Educator</label>
                    <select name="educator_id" class="form-select">
                        <option value="">All Educators</option>
                        @if(isset($educators))
                        @foreach($educators as $educator)
                        <option value="{{ $educator->id }}" {{ request('educator_id') == $educator->id ? 'selected' : '' }}>
                            {{ $educator->full_name }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Payment ID</label>
                    <input type="text" name="payment_id" value="{{ request('payment_id') }}" class="form-control" placeholder="Search payment ID">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-2 text-md-end">
                    <button class="btn btn-brand me-2">Apply Filters</button>
                    <a href="{{ route('admin.payouts.index', ['view' => 'educator_payouts']) }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </div>
                </form>
            </div>

            <div class="p-3">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Educator</th>
                            <th>Payment ID</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Processed</th>
                            <th>Acknowledged</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($educatorPayouts ?? [] as $payout)
                            <tr>
                                <td>{{ $payout->educator ? $payout->educator->full_name : 'N/A' }}</td>
                                <td><code>{{ $payout->payment_id }}</code></td>
                                <td><strong>AED {{ number_format($payout->amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge text-bg-{{
                                        $payout->status === 'completed' ? 'success' :
                                        ($payout->status === 'pending' ? 'warning' : 'danger')
                                    }}">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($payout->description, 50) }}</td>
                                <td>{{ $payout->processed_at ? $payout->processed_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @if($payout->acknowledged)
                                    <span class="badge bg-success"><i class="bi bi-check"></i> Yes</span>
                                    @else
                                    <span class="badge bg-warning"><i class="bi bi-clock"></i> No</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No educator payouts found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($educatorPayouts) && $educatorPayouts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $educatorPayouts->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Process Payout Modal -->
<div class="modal fade" id="processPayoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Payout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="processPayoutForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="completed">Mark as Completed</option>
                            <option value="failed">Mark as Failed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Processed By</label>
                        <input type="text" name="processed_by" class="form-control" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes about this payout"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Process Payout</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --brand: #0b3c77;
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

    .nav-tabs .nav-link {
        border: none;
        color: var(--muted);
    }

    .nav-tabs .nav-link.active {
        background: var(--brand);
        color: #fff;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .section-title {
        color: var(--ink);
        font-weight: 800;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .section-title i {
        color: var(--brand);
    }

    .tab-section-header {
        border-bottom: 2px solid var(--soft);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
function processPayout(payoutId) {
    const form = document.getElementById('processPayoutForm');
    form.action = `/admin/process/payout/${payoutId}`;
    new bootstrap.Modal(document.getElementById('processPayoutModal')).show();
}

// Bulk earnings selection
document.getElementById('select-all-earnings')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.earning-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBulkButton();
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('earning-checkbox')) {
        updateBulkButton();
    }
});

function updateBulkButton() {
    const selected = document.querySelectorAll('.earning-checkbox:checked');
    const bulkBtn = document.getElementById('bulk-update-btn');
    const idsInput = document.getElementById('bulk-earning-ids');

    bulkBtn.disabled = selected.length === 0;
    idsInput.value = Array.from(selected).map(cb => cb.value).join(',');
}
</script>
@endpush
</x-admin-layout>
