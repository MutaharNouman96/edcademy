<x-educator-layout>
    @php
        $sym = setting('currency', 'USD') === 'USD' ? '$' : setting('currency', 'USD') . ' ';
        $net = \App\Http\Controllers\Educator\PayoutController::payableAmount($payment);
        $commission = (float) $payment->platform_commission;
        if ($commission <= 0) {
            $commission = round((float) $payment->gross_amount - $net, 2);
        }
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1 fw-bold text-dark-cyan"><i class="bi bi-receipt-cutoff me-2"></i>Payment #{{ $payment->id }}</h2>
            <p class="text-muted mb-0 small">{{ $payment->created_at->format('d M Y, H:i') }}</p>
        </div>
        <a href="{{ route('educator.payments.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to sales
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="section-header">
                    <h4 class="section-title mb-0"><i class="bi bi-currency-dollar"></i> Financial breakdown</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-3">
                            <div class="payout-stat-box text-center">
                                <div class="small text-muted">Gross</div>
                                <div class="fs-5 fw-bold text-dark-cyan">{{ $sym }}{{ number_format($payment->gross_amount, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="payout-stat-box text-center">
                                <div class="small text-muted">Commission</div>
                                <div class="fs-5 fw-bold text-warning">{{ $sym }}{{ number_format($commission, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="payout-stat-box text-center">
                                <div class="small text-muted">Tax</div>
                                <div class="fs-5 fw-bold">{{ $sym }}{{ number_format($payment->tax_amount ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="payout-stat-box text-center border-success">
                                <div class="small text-muted">Your net</div>
                                <div class="fs-5 fw-bold text-success">{{ $sym }}{{ number_format($net, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="section-header">
                    <h4 class="section-title mb-0"><i class="bi bi-info-circle"></i> Details</h4>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">Course</dt>
                        <dd class="col-sm-8">{{ $payment->course?->title ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted">Student</dt>
                        <dd class="col-sm-8">
                            {{ $payment->student?->full_name ?? $payment->student?->first_name ?? '—' }}
                            @if ($payment->student?->email)
                                <span class="text-muted small">({{ $payment->student->email }})</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted">Transaction ID</dt>
                        <dd class="col-sm-8"><code>{{ $payment->transaction_id ?? '—' }}</code></dd>

                        <dt class="col-sm-4 text-muted">Payment method</dt>
                        <dd class="col-sm-8">{{ $payment->payment_method ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted">Payment status</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $payment->status === 'approved' ? 'success' : 'secondary' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </dd>

                        @if ($payment->notes)
                            <dt class="col-sm-4 text-muted">Notes</dt>
                            <dd class="col-sm-8">{{ $payment->notes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="section-header">
                    <h4 class="section-title mb-0"><i class="bi bi-bank"></i> Payout status</h4>
                </div>
                <div class="card-body vstack gap-3">
                    @if ($payment->is_payout_processed)
                        <div class="alert alert-success mb-0 py-2">
                            <i class="bi bi-check-circle me-1"></i> Paid out to your account
                        </div>
                    @elseif ($payment->payout_batch_id)
                        <div class="alert alert-info mb-0 py-2">
                            <i class="bi bi-arrow-repeat me-1"></i> Included in batch #{{ $payment->payout_batch_id }}
                            ({{ ucfirst($payment->payout_status ?? 'processing') }})
                        </div>
                    @elseif ($payment->is_payout_requested)
                        <div class="alert alert-warning mb-0 py-2">
                            <i class="bi bi-send me-1"></i> Payout requested
                            @if ($payment->payout_requested_at)
                                on {{ $payment->payout_requested_at->format('d M Y') }}
                            @endif
                        </div>
                    @else
                        <div class="alert alert-secondary mb-0 py-2">
                            <i class="bi bi-hourglass me-1"></i> Awaiting next scheduled payout release
                        </div>
                    @endif

                    @if ($payment->payoutBatch)
                        <div class="small">
                            <strong>Batch #{{ $payment->payoutBatch->id }}</strong>
                            <div class="text-muted">
                                {{ $sym }}{{ number_format($payment->payoutBatch->total_net_amount, 2) }}
                                · {{ ucfirst($payment->payoutBatch->status) }}
                            </div>
                            @if ($payment->payoutBatch->processed_at)
                                <div class="text-muted">Processed {{ $payment->payoutBatch->processed_at->format('d M Y') }}</div>
                            @endif
                        </div>
                    @endif

                    @if (! $payment->is_payout_processed && ! $payment->is_payout_requested)
                        <a href="{{ route('educator.payouts.index') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-send me-1"></i> Request payout
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-educator-layout>
