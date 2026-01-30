<x-admin-layout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Upcoming Payouts</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payouts.index') }}">Payouts</a></li>
                    <li class="breadcrumb-item active">Upcoming</li>
                </ol>
            </nav>
        </div>
        {{-- <div class="d-flex gap-2">
        <form method="POST" action="{{ route('admin.payouts.generate-upcoming') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>Generate Upcoming Payouts
            </button>
        </form>
        <a href="{{ route('admin.payouts.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Payouts
        </a>
    </div> --}}
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="kpi-value">{{ $totalUpcomingCount }}</div>
                        <div class="kpi-label">Upcoming Payouts</div>
                    </div>
                    <div class="kpi-icon">
                        <i class="bi bi-clock-history text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="kpi-value">AED {{ number_format($totalUpcomingAmount, 2) }}</div>
                        <div class="kpi-label">Total Amount</div>
                    </div>
                    <div class="kpi-icon">
                        <i class="bi bi-cash text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="kpi-value">
                            {{ $totalUpcomingCount > 0 ? number_format($totalUpcomingAmount / $totalUpcomingCount, 2) : '0.00' }}
                        </div>
                        <div class="kpi-label">Average Payout</div>
                    </div>
                    <div class="kpi-icon">
                        <i class="bi bi-graph-up text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.payouts.upcoming') }}" class="filter-bar p-3 mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Educator</label>
                <select name="educator_id" class="form-select">
                    <option value="">All Educators</option>
                    @foreach ($educators as $educator)
                        <option value="{{ $educator->id }}"
                            {{ request('educator_id') == $educator->id ? 'selected' : '' }}>
                            {{ $educator->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Min Amount</label>
                <input type="number" name="min_amount" value="{{ request('min_amount') }}" class="form-control"
                    placeholder="0.00" step="0.01">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Max Amount</label>
                <input type="number" name="max_amount" value="{{ request('max_amount') }}" class="form-control"
                    placeholder="0.00" step="0.01">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                    placeholder="Educator name">
            </div>
            <div class="col-md-2 text-md-end">
                <button class="btn btn-brand me-2">Apply Filters</button>
                <a href="{{ route('admin.payouts.upcoming') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </div>
    </form>

    <!-- Bulk Actions Form -->
    <form id="bulkActionForm" method="POST" action="{{ route('admin.payouts.release') }}">
        @csrf
        <div class="kpi-card p-3">
            <!-- Bulk Actions Bar -->
            <div class="d-flex justify-content-between align-items-center mb-3" id="bulkActionsBar"
                style="display: none;">
                <div>
                    <span id="selectedCount">0</span> payouts selected
                </div>
                <div class="d-flex gap-2">
                    <input type="text" name="processed_by" value="{{ auth()->user()->full_name ?? 'Admin' }}"
                        class="form-control form-control-sm" placeholder="Processed by" required style="width: 150px;">
                    <input type="text" name="notes" class="form-control form-control-sm"
                        placeholder="Notes (optional)" style="width: 200px;">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i>Release Selected
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Educator</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingPayouts as $payout)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input payout-checkbox"
                                        name="payout_ids[]" value="{{ $payout->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($payout->educator)
                                            <div>
                                                <div class="fw-semibold">{{ $payout->educator->full_name }}</div>
                                                <small class="text-muted">{{ $payout->educator->email }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">Unknown Educator</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-success">AED
                                        {{ number_format($payout->amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                        title="{{ $payout->description }}">
                                        {{ $payout->description ?? 'No description' }}
                                    </span>
                                </td>
                                <td>{{ $payout->created_at->format('M d, Y H:i') }}</td>


                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-info me-2"
                                        onclick="viewPayoutDetails({{ $payout->id }})">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <form method="POST"
                                        action="{{ route('admin.payouts.release-single', $payout) }}"
                                        class="d-inline">
                                        @csrf
                                        <input type="hidden" name="payout_ids[]" value="{{ $payout->id }}">
                                        <input type="hidden" name="processed_by"
                                            value="{{ auth()->user()->full_name ?? 'Admin' }}">
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Are you sure you want to release this payout?')">
                                            <i class="bi bi-check-circle me-1"></i>Release
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle fs-1 text-muted mb-2"></i>
                                    <div>No upcoming payouts found</div>
                                    <small>Generate payouts from approved earnings to see them here</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($upcomingPayouts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $upcomingPayouts->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </form>

    <!-- Payout Details Modal -->
    <div class="modal fade" id="payoutDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payout Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="payoutDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAllCheckbox = document.getElementById('selectAll');
                const payoutCheckboxes = document.querySelectorAll('.payout-checkbox');
                const bulkActionsBar = document.getElementById('bulkActionsBar');
                const selectedCount = document.getElementById('selectedCount');

                // Handle select all checkbox
                selectAllCheckbox.addEventListener('change', function() {
                    payoutCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkActions();
                });

                // Handle individual checkboxes
                payoutCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const checkedBoxes = document.querySelectorAll('.payout-checkbox:checked');
                        selectAllCheckbox.checked = checkedBoxes.length === payoutCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes
                            .length < payoutCheckboxes.length;
                        updateBulkActions();
                    });
                });

                function updateBulkActions() {
                    const checkedBoxes = document.querySelectorAll('.payout-checkbox:checked');
                    if (checkedBoxes.length > 0) {
                        bulkActionsBar.style.display = 'flex';
                        selectedCount.textContent = checkedBoxes.length;
                    } else {
                        bulkActionsBar.style.display = 'none';
                    }
                }

                // Clear selection function
                window.clearSelection = function() {
                    payoutCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    selectAllCheckbox.checked = false;
                    updateBulkActions();
                };
            });

            function viewPayoutDetails(payoutId) {
                fetch(`{{ url('/admin/payouts') }}/${payoutId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        let html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Payout Information</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Educator:</strong></td><td>${data.payout.educator ? data.payout.educator.full_name : 'N/A'}</td></tr>
                            <tr><td><strong>Amount:</strong></td><td>AED ${parseFloat(data.payout.amount).toFixed(2)}</td></tr>
                            <tr><td><strong>Status:</strong></td><td><span class="badge bg-warning">Pending</span></td></tr>
                            <tr><td><strong>Created:</strong></td><td>${new Date(data.payout.created_at).toLocaleString()}</td></tr>
                            <tr><td><strong>Description:</strong></td><td>${data.payout.description || 'N/A'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Earnings Breakdown (${data.total_earnings} items)</h6>
                        <div style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                        data.earnings.forEach(earning => {
                            const source = earning.course ? `Course: ${earning.course.title.substring(0, 30)}...` :
                                earning.session ? `Session: ${earning.session.title || 'Session'}` :
                                earning.source_type || 'Other';
                            html += `
                                    <tr>
                                        <td><small>${source}</small></td>
                                        <td>AED ${parseFloat(earning.net_amount).toFixed(2)}</td>
                                        <td><small>${new Date(earning.earned_at).toLocaleDateString()}</small></td>
                                    </tr>`;
                        });

                        html += `
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2 pt-2 border-top">
                            <strong>Total: AED ${parseFloat(data.total_amount).toFixed(2)}</strong>
                        </div>
                    </div>
                </div>`;

                        document.getElementById('payoutDetailsContent').innerHTML = html;
                        new bootstrap.Modal(document.getElementById('payoutDetailsModal')).show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to load payout details');
                    });
            }
        </script>
    @endpush

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

            .kpi-card {
                border: 0;
                border-radius: 1rem;
                background: #fff;
                box-shadow: var(--card-shadow);
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

            .kpi-icon {
                font-size: 2rem;
                opacity: 0.7;
            }

            .table thead th {
                color: var(--muted);
                font-weight: 700;
                border-bottom: 1px solid #e5e7eb;
            }

            .table>tbody>tr>td {
                vertical-align: middle;
            }

            .filter-bar {
                background: linear-gradient(135deg, var(--brand) 0%, var(--brand-600) 100%);
                border-radius: 1rem;
                color: white;
            }

            .filter-bar .form-label {
                color: white;
            }

            .filter-bar .form-control,
            .filter-bar .form-select {
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: white;
            }

            .filter-bar .form-control::placeholder {
                color: rgba(255, 255, 255, 0.7);
            }

            .filter-bar .btn-brand {
                background: white;
                color: var(--brand);
                border: none;
            }

            .filter-bar .btn-brand:hover {
                background: rgba(255, 255, 255, 0.9);
            }
        </style>
    @endpush
</x-admin-layout>
