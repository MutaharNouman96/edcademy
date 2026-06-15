<x-educator-layout>
    @php
        $sym = $currency === 'USD' ? '$' : $currency . ' ';
        $statusColors = [
            'pending' => 'warning',
            'processing' => 'info',
            'paid' => 'success',
            'failed' => 'danger',
            'completed' => 'success',
            'cancelled' => 'secondary',
        ];
    @endphp

    {{-- Page header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1 fw-bold text-dark-cyan"><i class="bi bi-bank me-2"></i>Payouts</h2>
            <p class="text-muted mb-0 small">
                Track earnings, scheduled releases, and request payouts.
                <span class="badge badge-soft ms-1">{{ $scheduleLabel }}</span>
            </p>
        </div>
        @if ($nextPayoutDate)
            <div class="payout-next-release text-end">
                <div class="small text-muted">Next automatic release</div>
                <div class="fw-semibold text-dark-cyan">
                    <i class="bi bi-calendar-event me-1"></i>{{ $nextPayoutDate->format('d M Y, H:i') }}
                </div>
            </div>
        @endif
    </div>

    @include('educator.partials.payout-setup-alert', ['openPayoutRequest' => $openPayoutRequest ?? null])

    {{-- KPI cards --}}
    <section class="mb-4">
        <div class="row g-3">
            <div class="col-sm-6 col-xl-3">
                <div class="card kpi-card p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="kpi-icon"><i class="bi bi-hourglass-split"></i></span>
                        <span class="pill badge-soft">Pending</span>
                    </div>
                    <div class="mt-3">
                        <div class="h3 mb-0">{{ $sym }}{{ number_format($pendingBalance, 2) }}</div>
                        <small class="text-muted">{{ $pendingPayments->count() }} payment(s) awaiting release</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card kpi-card p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="kpi-icon"><i class="bi bi-arrow-repeat"></i></span>
                        <span class="pill badge-soft">In batch</span>
                    </div>
                    <div class="mt-3">
                        <div class="h3 mb-0">{{ $sym }}{{ number_format($processingBalance, 2) }}</div>
                        <small class="text-muted">{{ $processingPayments->count() }} payment(s) being processed</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card kpi-card p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="kpi-icon"><i class="bi bi-calendar-check"></i></span>
                        <span class="pill badge-soft">This month</span>
                    </div>
                    <div class="mt-3">
                        <div class="h3 mb-0">{{ $sym }}{{ number_format($paidThisMonth, 2) }}</div>
                        <small class="text-muted">{{ $paidThisMonthCount }} batch(es) paid out</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card kpi-card p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="kpi-icon"><i class="bi bi-graph-up-arrow"></i></span>
                        <span class="pill badge-soft">Lifetime</span>
                    </div>
                    <div class="mt-3">
                        <div class="h3 mb-0">{{ $sym }}{{ number_format($lifetimePaid, 2) }}</div>
                        <small class="text-muted">{{ $sym }}{{ number_format($totalEarned, 2) }} total earned</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row g-4">
        {{-- Left column: pending + processing tables --}}
        <div class="col-12 col-xl-8">
            {{-- Pending payments --}}
            <div class="card shadow-sm mb-4">
                <div class="section-header">
                    <h4 class="section-title"><i class="bi bi-wallet2"></i> Pending Earnings</h4>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Source</th>
                                    <th>Student</th>
                                    <th class="text-end">Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendingPayments as $payment)
                                    @php
                                        $amount = \App\Http\Controllers\Educator\PayoutController::payableAmount($payment);
                                    @endphp
                                    <tr>
                                        <td class="text-nowrap">{{ $payment->created_at->format('d M Y') }}</td>
                                        <td>
                                            <strong>{{ $payment->course?->title ?? 'Course #' . $payment->course_id }}</strong>
                                            <div class="small text-muted">Payment #{{ $payment->id }}</div>
                                        </td>
                                        <td>{{ $payment->student?->full_name ?? $payment->student?->first_name ?? '—' }}</td>
                                        <td class="text-end fw-semibold text-success">{{ $sym }}{{ number_format($amount, 2) }}</td>
                                        <td>
                                            @if ($payment->is_payout_requested)
                                                <span class="badge bg-info status-badge">Requested</span>
                                            @else
                                                <span class="badge bg-warning text-dark status-badge">Awaiting release</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                            No pending earnings right now.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($pendingPayments->isNotEmpty())
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="fw-semibold">Total pending</td>
                                        <td class="text-end fw-bold text-success">{{ $sym }}{{ number_format($pendingBalance, 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            {{-- Processing in batch --}}
            @if ($processingPayments->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="section-header">
                        <h4 class="section-title"><i class="bi bi-arrow-repeat"></i> Currently Processing</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Source</th>
                                    <th class="text-end">Amount</th>
                                    <th>Batch</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($processingPayments as $payment)
                                    @php $amount = \App\Http\Controllers\Educator\PayoutController::payableAmount($payment); @endphp
                                    <tr>
                                        <td>{{ $payment->created_at->format('d M Y') }}</td>
                                        <td>{{ $payment->course?->title ?? 'Payment #' . $payment->id }}</td>
                                        <td class="text-end">{{ $sym }}{{ number_format($amount, 2) }}</td>
                                        <td>
                                            @if ($payment->payoutBatch)
                                                <span class="badge bg-secondary">Batch #{{ $payment->payout_batch_id }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $statusColors[$payment->payout_status] ?? 'info' }} status-badge">
                                                {{ ucfirst($payment->payout_status ?? 'processing') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Payout batch history --}}
            <div class="card shadow-sm">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h4 class="section-title mb-0"><i class="bi bi-clock-history"></i> Payout History</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Processed</th>
                                <th>Batch</th>
                                <th>Period</th>
                                <th class="text-end">Net paid</th>
                                <th>Payments</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payoutBatches as $batch)
                                <tr>
                                    <td class="text-nowrap">
                                        {{ $batch->processed_at?->format('d M Y') ?? '—' }}
                                    </td>
                                    <td><code class="small">#{{ $batch->id }}</code></td>
                                    <td class="small text-muted">
                                        @if ($batch->start_date && $batch->end_date)
                                            {{ $batch->start_date->format('d M') }} – {{ $batch->end_date->format('d M Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold text-success">
                                        {{ $sym }}{{ number_format($batch->total_net_amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ count(array_filter(explode(',', $batch->payment_ids ?? ''))) }} payment(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusColors[$batch->status] ?? 'secondary' }} status-badge">
                                            {{ ucfirst($batch->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No payout batches yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($payoutBatches->hasPages())
                    <div class="card-footer">{{ $payoutBatches->links() }}</div>
                @endif
            </div>
        </div>

        {{-- Right column: request form + stripe + recent requests --}}
        <div class="col-12 col-xl-4">
            {{-- Payout request form --}}
            <div class="card shadow-sm mb-4 payout-request-card">
                <div class="section-header">
                    <h4 class="section-title"><i class="bi bi-send"></i> Request a Payout</h4>
                </div>
                <div class="card-body">
                    @if ($openPayoutRequest)
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            You have an open request
                            <strong>#{{ $openPayoutRequest->id }}</strong>
                            ({{ ucfirst(str_replace('_', ' ', $openPayoutRequest->status)) }}).
                            <a href="{{ route('educator.payout-requests.index') }}" class="alert-link">View all requests</a>
                        </div>
                    @elseif (! $canReceivePayouts)
                        <p class="text-muted small mb-3">
                            Connect Stripe before you can request a payout. Use the alert above to set up payouts or ask admin for help.
                        </p>
                        <a href="{{ route('stripe.connect') }}" class="btn btn-primary w-100">
                            <i class="bi bi-link-45deg me-1"></i> Connect Stripe
                        </a>
                    @elseif ($pendingPayments->isEmpty())
                        <p class="text-muted small mb-0">
                            <i class="bi bi-check-circle text-success me-1"></i>
                            No pending earnings to request. New sales will appear here after checkout.
                        </p>
                    @else
                        <p class="text-muted small">
                            Submit a manual payout request if you need funds released before the next scheduled batch
                            ({{ $nextPayoutDate?->format('d M Y') ?? 'see schedule' }}).
                            Available: <strong class="text-success">{{ $sym }}{{ number_format($pendingBalance, 2) }}</strong>
                        </p>

                        <form method="POST" action="{{ route('educator.payout-requests.store') }}" class="vstack gap-3">
                            @csrf
                            <input type="hidden" name="type" value="payout">

                            <div>
                                <label for="payment_id" class="form-label fw-semibold">Scope</label>
                                <select name="payment_id" id="payment_id" class="form-select">
                                    <option value="">All pending earnings ({{ $sym }}{{ number_format($pendingBalance, 2) }})</option>
                                    @foreach ($pendingPayments as $payment)
                                        @php $amt = \App\Http\Controllers\Educator\PayoutController::payableAmount($payment); @endphp
                                        <option value="{{ $payment->id }}" @selected(old('payment_id') == $payment->id)>
                                            {{ $payment->course?->title ?? 'Payment #' . $payment->id }}
                                            — {{ $sym }}{{ number_format($amt, 2) }}
                                            ({{ $payment->created_at->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="payout_message" class="form-label fw-semibold">Message <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea name="message" id="payout_message" class="form-control @error('message') is-invalid @enderror"
                                    rows="3" maxlength="2000" placeholder="e.g. Please process my pending earnings this week.">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send me-1"></i> Submit payout request
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Stripe status --}}
            <div class="card shadow-sm mb-4">
                <div class="section-header">
                    <h4 class="section-title"><i class="bi bi-stripe"></i> Payout account</h4>
                </div>
                <div class="card-body">
                    @if ($canReceivePayouts)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="payout-status-dot bg-success"></span>
                            <strong class="text-success">Stripe connected</strong>
                        </div>
                        <p class="text-muted small mb-3">Payouts are sent to your connected Stripe account on the scheduled release dates.</p>
                        <a href="{{ route('educator.settings') }}#tab-connections" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-gear me-1"></i> Manage connection
                        </a>
                    @else
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="payout-status-dot bg-warning"></span>
                            <strong class="text-warning">Not connected</strong>
                        </div>
                        <p class="text-muted small mb-3">Complete Stripe Connect to receive automatic payouts.</p>
                        <a href="{{ route('stripe.connect') }}" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-link-45deg me-1"></i> Connect Stripe
                        </a>
                    @endif
                </div>
            </div>

            {{-- Recent requests snippet --}}
            <div class="card shadow-sm">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <h4 class="section-title mb-0"><i class="bi bi-list-check"></i> Recent requests</h4>
                    <a href="{{ route('educator.payout-requests.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($recentRequests as $req)
                        <li class="list-group-item d-flex justify-content-between align-items-start gap-2">
                            <div class="min-w-0">
                                <div class="fw-semibold small">Request #{{ $req->id }}</div>
                                <div class="text-muted small text-truncate">{{ Str::limit($req->message, 60) }}</div>
                                <div class="text-muted small">{{ $req->created_at->format('d M Y, H:i') }}</div>
                            </div>
                            <span class="badge bg-{{ $statusColors[$req->status] ?? 'secondary' }} status-badge flex-shrink-0">
                                {{ \App\Models\EducatorPayoutRequest::statusOptions()[$req->status] ?? ucfirst($req->status) }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center small py-4">No requests submitted yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-educator-layout>
