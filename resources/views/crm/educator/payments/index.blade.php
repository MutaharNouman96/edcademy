<x-educator-layout>
    @php
        $sym = setting('currency', 'USD') === 'USD' ? '$' : setting('currency', 'USD') . ' ';
        $payoutColors = ['pending' => 'warning', 'processing' => 'info', 'paid' => 'success', 'failed' => 'danger'];
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1 fw-bold text-dark-cyan"><i class="bi bi-cash-coin me-2"></i>My Sales</h2>
            <p class="text-muted mb-0 small">Every payment received from students for your courses.</p>
        </div>
        <a href="{{ route('educator.payouts.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-bank me-1"></i> View Payouts
        </a>
    </div>

    {{-- Summary KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="kpi-icon"><i class="bi bi-receipt"></i></span>
                    <span class="pill badge-soft">Gross</span>
                </div>
                <div class="mt-3">
                    <div class="h4 mb-0">{{ $sym }}{{ number_format($summary['total_received'], 2) }}</div>
                    <small class="text-muted">Total received</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="kpi-icon"><i class="bi bi-percent"></i></span>
                    <span class="pill badge-soft">Fees</span>
                </div>
                <div class="mt-3">
                    <div class="h4 mb-0">{{ $sym }}{{ number_format($summary['total_commission'], 2) }}</div>
                    <small class="text-muted">Platform commission</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="kpi-icon"><i class="bi bi-hourglass"></i></span>
                    <span class="pill badge-soft">Pending</span>
                </div>
                <div class="mt-3">
                    <div class="h4 mb-0 text-warning">{{ $sym }}{{ number_format($summary['pending_payout'], 2) }}</div>
                    <small class="text-muted">Awaiting payout</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card kpi-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="kpi-icon"><i class="bi bi-check-circle"></i></span>
                    <span class="pill badge-soft">Paid out</span>
                </div>
                <div class="mt-3">
                    <div class="h4 mb-0 text-success">{{ $sym }}{{ number_format($summary['paid_out'], 2) }}</div>
                    <small class="text-muted">Net {{ $sym }}{{ number_format($summary['total_net'], 2) }} earned</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="section-header">
            <h4 class="section-title mb-0"><i class="bi bi-table"></i> Payment history</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 data-table">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Course / item</th>
                        <th>Student</th>
                        <th class="text-end">Gross</th>
                        <th class="text-end">Net</th>
                        <th>Payment</th>
                        <th>Payout</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        @php
                            $net = \App\Http\Controllers\Educator\PayoutController::payableAmount($payment);
                            $commission = (float) $payment->platform_commission;
                            if ($commission <= 0) {
                                $commission = round((float) $payment->gross_amount - $net, 2);
                            }
                        @endphp
                        <tr>
                            <td class="text-nowrap">{{ $payment->created_at->format('d M Y') }}</td>
                            <td>
                                <strong>{{ $payment->course?->title ?? 'Item #' . $payment->course_id }}</strong>
                                <div class="small text-muted">#{{ $payment->id }}</div>
                            </td>
                            <td>{{ $payment->student?->full_name ?? $payment->student?->first_name ?? '—' }}</td>
                            <td class="text-end">{{ $sym }}{{ number_format($payment->gross_amount, 2) }}</td>
                            <td class="text-end fw-semibold text-success">{{ $sym }}{{ number_format($net, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $payment->status === 'approved' ? 'success' : 'secondary' }} status-badge">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                @if ($payment->is_payout_processed)
                                    <span class="badge bg-success status-badge">Paid</span>
                                @elseif ($payment->payout_batch_id)
                                    <span class="badge bg-{{ $payoutColors[$payment->payout_status] ?? 'info' }} status-badge">
                                        {{ ucfirst($payment->payout_status ?? 'processing') }}
                                    </span>
                                @elseif ($payment->is_payout_requested)
                                    <span class="badge bg-info status-badge">Requested</span>
                                @else
                                    <span class="badge bg-warning text-dark status-badge">Pending</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('educator.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No sales recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($payments->hasPages())
            <div class="card-footer">{{ $payments->links() }}</div>
        @endif
    </div>
</x-educator-layout>
