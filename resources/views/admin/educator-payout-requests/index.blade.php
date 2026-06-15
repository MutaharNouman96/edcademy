<x-admin-layout>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Educator Payout Assistance</h4>
            <p class="text-muted mb-0 small">Help educators complete Stripe Connect and payout setup.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="kpi-value">{{ $pendingCount }}</div>
                <div class="kpi-label">Pending requests</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="kpi-value">{{ $inProgressCount }}</div>
                <div class="kpi-label">In progress</div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.educator-payout-requests.index') }}" class="filter-bar p-3 mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    @foreach (\App\Models\EducatorPayoutRequest::statusOptions() as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Search educator</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                    placeholder="Name or email">
            </div>
            <div class="col-md-4 text-md-end">
                <button class="btn btn-brand">Apply Filters</button>
            </div>
        </div>
    </form>

    <div class="kpi-card p-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Educator</th>
                        <th>Email</th>
                        <th>Payout status</th>
                        <th>Request status</th>
                        <th>Submitted</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $index => $requestItem)
                        @php
                            $educator = $requestItem->educator;
                        @endphp
                        <tr>
                            <td>{{ $requests->firstItem() + $index }}</td>
                            <td>{{ $educator?->full_name ?? '—' }}</td>
                            <td>{{ $educator?->email ?? '—' }}</td>
                            <td>
                                @if ($educator?->canReceivePayouts())
                                    <span class="badge text-bg-success">Ready</span>
                                @elseif ($educator?->stripe_connect_id)
                                    <span class="badge text-bg-warning">Incomplete</span>
                                @else
                                    <span class="badge text-bg-secondary">Not connected</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match ($requestItem->status) {
                                        'pending' => 'warning',
                                        'in_progress' => 'info',
                                        'resolved' => 'success',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $requestItem->status)) }}
                                </span>
                            </td>
                            <td>{{ $requestItem->created_at->format('M d, Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.educator-payout-requests.show', $requestItem) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No payout assistance requests yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($requests->hasPages())
            <div class="mt-3">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

</x-admin-layout>
