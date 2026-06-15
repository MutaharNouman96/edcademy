<x-admin-layout>
@php
    $sym = '$';
    $currentView = $currentView ?? 'payments';
    $statusColors = [
        'pending' => 'warning', 'processing' => 'info', 'completed' => 'success',
        'failed' => 'danger', 'paid' => 'success', 'cancelled' => 'secondary',
        'in_progress' => 'info', 'resolved' => 'success', 'closed' => 'secondary',
    ];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <h4 class="mb-1">Payouts Management</h4>
        <p class="text-muted small mb-0">
            Earnings from payments · Released batches · Educator payout requests
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <span class="badge bg-light text-dark border">
            <i class="bi bi-clock me-1"></i>Schedule: {{ $schedule['label'] }}
        </span>
        <span class="badge bg-light text-dark border">
            Approval delay: {{ $schedule['approval_delay_minutes'] }} min
        </span>
        <form method="POST" action="{{ route('admin.payouts.run-release') }}" class="d-inline"
            onsubmit="return confirm('Queue a payout release job for all eligible educators now?');">
            @csrf
            <button type="submit" class="btn btn-sm btn-brand">
                <i class="bi bi-play-circle me-1"></i>Run scheduled release
            </button>
        </form>
    </div>
</div>

{{-- KPI summary (from Payment model, not legacy Earning) --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-2">
        <div class="kpi-card p-3 text-center">
            <div class="kpi-label">Total gross</div>
            <div class="kpi-value fs-5">{{ $sym }}{{ number_format($summary['total_gross'], 2) }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="kpi-card p-3 text-center">
            <div class="kpi-label">Net earned</div>
            <div class="kpi-value fs-5">{{ $sym }}{{ number_format($summary['total_net'], 2) }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="kpi-card p-3 text-center">
            <div class="kpi-label">Pending payout</div>
            <div class="kpi-value fs-5 text-warning">{{ $sym }}{{ number_format($summary['pending_payout'], 2) }}</div>
            <small class="text-muted">{{ $summary['pending_count'] }} payment(s)</small>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="kpi-card p-3 text-center">
            <div class="kpi-label">In processing</div>
            <div class="kpi-value fs-5 text-info">{{ $sym }}{{ number_format($summary['processing'], 2) }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="kpi-card p-3 text-center">
            <div class="kpi-label">Paid out</div>
            <div class="kpi-value fs-5 text-success">{{ $sym }}{{ number_format($summary['paid_out'], 2) }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="kpi-card p-3 text-center">
            <div class="kpi-label">Batches</div>
            <div class="kpi-value fs-5">{{ $summary['batch_completed'] }} <small class="text-muted">/ {{ $summary['batch_failed'] }} failed</small></div>
        </div>
    </div>
</div>

{{-- Tabs --}}
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $currentView === 'payments' ? 'active' : '' }}"
           href="{{ route('admin.payouts.index', ['view' => 'payments']) }}">
            <i class="bi bi-receipt me-1"></i>Payments ({{ $summary['payment_count'] }})
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $currentView === 'batches' ? 'active' : '' }}"
           href="{{ route('admin.payouts.index', ['view' => 'batches']) }}">
            <i class="bi bi-collection me-1"></i>Released batches
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $currentView === 'requests' ? 'active' : '' }}"
           href="{{ route('admin.payouts.index', ['view' => 'requests']) }}">
            <i class="bi bi-send-check me-1"></i>Payout requests
            @if(($pendingRequestCount ?? 0) > 0)
                <span class="badge bg-warning text-dark">{{ $pendingRequestCount }}</span>
            @endif
        </a>
    </li>
</ul>

{{-- TAB: Payments --}}
@if($currentView === 'payments')
<div class="kpi-card">
    <div class="p-3 border-bottom">
        <h5 class="section-title mb-1"><i class="bi bi-receipt me-2"></i>Educator payments</h5>
        <p class="text-muted small mb-0">All sales recorded in the payments table — earnings are calculated from gross minus commission.</p>
    </div>
    <div class="p-3 border-bottom">
        <form method="GET" class="row g-2 align-items-end">
            <input type="hidden" name="view" value="payments">
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Payout status</label>
                <select name="payout_status" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach(['pending','processing','paid','failed'] as $s)
                        <option value="{{ $s }}" @selected(request('payout_status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Educator</label>
                <select name="educator_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($educators as $ed)
                        <option value="{{ $ed->id }}" @selected(request('educator_id') == $ed->id)>{{ $ed->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="ID, name, course">
            </div>
            <div class="col-md-2">
                <button class="btn btn-brand btn-sm w-100">Filter</button>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Educator</th>
                    <th>Course</th>
                    <th>Student</th>
                    <th class="text-end">Gross</th>
                    <th class="text-end">Net</th>
                    <th>Payout</th>
                    <th>Batch</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments ?? [] as $payment)
                    @php $net = \App\Http\Controllers\Educator\PayoutController::payableAmount($payment); @endphp
                    <tr>
                        <td><code>#{{ $payment->id }}</code></td>
                        <td class="text-nowrap small">{{ $payment->created_at->format('d M Y') }}</td>
                        <td>{{ $payment->educator?->full_name ?? '—' }}</td>
                        <td>{{ Str::limit($payment->course?->title ?? '—', 30) }}</td>
                        <td class="small">{{ $payment->student?->full_name ?? '—' }}</td>
                        <td class="text-end">{{ $sym }}{{ number_format($payment->gross_amount, 2) }}</td>
                        <td class="text-end fw-semibold text-success">{{ $sym }}{{ number_format($net, 2) }}</td>
                        <td>
                            @if($payment->is_payout_processed)
                                <span class="badge bg-success">Paid</span>
                            @elseif($payment->payout_batch_id)
                                <span class="badge bg-{{ $statusColors[$payment->payout_status] ?? 'info' }}">{{ ucfirst($payment->payout_status ?? 'processing') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->payout_batch_id)
                                <a href="{{ route('admin.payout-batches.show', $payment->payout_batch_id) }}">#{{ $payment->payout_batch_id }}</a>
                            @else — @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No payments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($payments) && $payments->hasPages())
        <div class="p-3">{{ $payments->links() }}</div>
    @endif
</div>
@endif

{{-- TAB: Batches --}}
@if($currentView === 'batches')
<div class="kpi-card">
    <div class="p-3 border-bottom">
        <h5 class="section-title mb-1"><i class="bi bi-collection me-2"></i>Payout batches</h5>
        <p class="text-muted small mb-0">Each batch groups payments released via <code>ReleaseEducatorPayoutJob</code> (scheduled or admin-approved).</p>
    </div>
    <div class="p-3 border-bottom">
        <form method="GET" class="row g-2 align-items-end">
            <input type="hidden" name="view" value="batches">
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All status</option>
                    @foreach(['pending','processing','completed','failed','cancelled'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="educator_id" class="form-select form-select-sm">
                    <option value="">All educators</option>
                    @foreach($educators as $ed)
                        <option value="{{ $ed->id }}" @selected(request('educator_id') == $ed->id)>{{ $ed->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm"></div>
            <div class="col-md-2"><input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm"></div>
            <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Filter</button></div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Batch</th>
                    <th>Educator</th>
                    <th>Period</th>
                    <th class="text-end">Net amount</th>
                    <th>Payments</th>
                    <th>Processed by</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches ?? [] as $batch)
                    <tr>
                        <td><code>#{{ $batch->id }}</code></td>
                        <td>{{ $batch->educator?->full_name ?? '—' }}</td>
                        <td class="small text-muted">
                            @if($batch->start_date && $batch->end_date)
                                {{ $batch->start_date->format('d M') }} – {{ $batch->end_date->format('d M Y') }}
                            @else — @endif
                        </td>
                        <td class="text-end fw-semibold">{{ $sym }}{{ number_format($batch->total_net_amount, 2) }}</td>
                        <td>{{ count(array_filter(explode(',', $batch->payment_ids ?? ''))) }}</td>
                        <td class="small">{{ $batch->processed_by ?? '—' }}</td>
                        <td><span class="badge bg-{{ $statusColors[$batch->status] ?? 'secondary' }}">{{ ucfirst($batch->status) }}</span></td>
                        <td>
                            <a href="{{ route('admin.payout-batches.show', $batch) }}" class="btn btn-sm btn-outline-primary">Details</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No payout batches yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($batches) && $batches->hasPages())
        <div class="p-3">{{ $batches->links() }}</div>
    @endif
</div>
@endif

{{-- TAB: Requests --}}
@if($currentView === 'requests')
<div class="row g-3 mb-3">
    <div class="col-md-4"><div class="kpi-card p-3"><div class="kpi-value">{{ $pendingRequestCount ?? 0 }}</div><div class="kpi-label">Pending</div></div></div>
    <div class="col-md-4"><div class="kpi-card p-3"><div class="kpi-value">{{ $inProgressRequestCount ?? 0 }}</div><div class="kpi-label">In progress (job queued)</div></div></div>
    <div class="col-md-4"><div class="kpi-card p-3 small text-muted">Approving a request queues <code>ReleaseEducatorPayoutJob</code> after {{ $schedule['approval_delay_minutes'] }} min.</div></div>
</div>
<div class="kpi-card">
    <div class="p-3 border-bottom">
        <form method="GET" class="row g-2 align-items-end">
            <input type="hidden" name="view" value="requests">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All status</option>
                    @foreach(\App\Models\EducatorPayoutRequest::statusOptions() as $val => $label)
                        <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="educator_id" class="form-select form-select-sm">
                    <option value="">All educators</option>
                    @foreach($educators as $ed)
                        <option value="{{ $ed->id }}" @selected(request('educator_id') == $ed->id)>{{ $ed->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search educator"></div>
            <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Filter</button></div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Educator</th>
                    <th>Scope</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Batch</th>
                    <th>Submitted</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($payoutRequests ?? [] as $req)
                    <tr>
                        <td>{{ $req->id }}</td>
                        <td>
                            {{ $req->educator?->full_name ?? '—' }}
                            @if($req->educator && !$req->educator->canReceivePayouts())
                                <span class="badge bg-warning text-dark">Stripe incomplete</span>
                            @endif
                        </td>
                        <td>
                            @if($req->payment_id)
                                Payment #{{ $req->payment_id }}
                            @else
                                All pending
                            @endif
                        </td>
                        <td class="small" style="max-width:200px">{{ Str::limit($req->message, 60) }}</td>
                        <td><span class="badge bg-{{ $statusColors[$req->status] ?? 'secondary' }}">{{ \App\Models\EducatorPayoutRequest::statusOptions()[$req->status] ?? $req->status }}</span></td>
                        <td>
                            @if($req->payout_batch_id)
                                <a href="{{ route('admin.payout-batches.show', $req->payout_batch_id) }}">#{{ $req->payout_batch_id }}</a>
                            @else — @endif
                        </td>
                        <td class="small text-nowrap">{{ $req->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.educator-payout-requests.show', $req) }}" class="btn btn-sm btn-outline-primary">Review</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No payout requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($payoutRequests) && $payoutRequests->hasPages())
        <div class="p-3">{{ $payoutRequests->links() }}</div>
    @endif
</div>
@endif

@push('styles')
<style>
    :root { --brand: #0b3c77; --card-shadow: 0 10px 30px rgba(11, 60, 119, 0.08); }
    .kpi-card { border: 0; border-radius: 1rem; background: #fff; box-shadow: var(--card-shadow); }
    .kpi-value { font-weight: 800; color: #0f172a; }
    .kpi-label { color: #6b7280; font-weight: 600; font-size: 0.85rem; }
    .btn-brand { background: var(--brand); border-color: var(--brand); color: #fff; }
    .btn-brand:hover { background: #093362; color: #fff; }
    .section-title { font-weight: 700; margin: 0; }
    .nav-tabs .nav-link.active { background: var(--brand); color: #fff; border-radius: 0.5rem 0.5rem 0 0; }
</style>
@endpush
</x-admin-layout>
