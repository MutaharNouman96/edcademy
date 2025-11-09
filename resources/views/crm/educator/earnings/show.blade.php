<x-educator-layout>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark-cyan"><i class="bi bi-wallet2 me-2"></i>Earning Details</h4>
            <a href="{{ route('educator.earnings.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div class="text-muted small">Gross Amount</div>
                            <div class="fw-bold">${{ number_format($earning->gross_amount, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div class="text-muted small">Platform Commission</div>
                            <div class="fw-bold text-danger">${{ number_format($earning->platform_commission, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border rounded p-3 mb-4 bg-light">
                    <div class="text-muted small">Net Amount (Your Earning)</div>
                    <div class="fw-bold text-success fs-5">${{ number_format($earning->net_amount, 2) }}</div>
                </div>

                <h6 class="fw-semibold mb-2">Source</h6>
                <p>
                    @if ($earning->source_type === 'session')
                        <strong>Session:</strong> {{ $earning->session->title ?? 'N/A' }}
                    @elseif($earning->source_type === 'course')
                        <strong>Course:</strong> {{ $earning->course->title ?? 'N/A' }}
                    @elseif($earning->source_type === 'resource')
                        <strong>Resource:</strong> {{ $earning->courseResource->title ?? 'N/A' }}
                    @else
                        -
                    @endif
                </p>

                <h6 class="fw-semibold mb-2">Payment Details</h6>
                @if ($earning->payment)
                    <p class="mb-1"><strong>Transaction ID:</strong> {{ $earning->payment->transaction_id }}</p>
                    <p class="mb-1"><strong>Status:</strong> {{ ucfirst($earning->payment->status) }}</p>
                    <p class="mb-0"><strong>Method:</strong> {{ $earning->payment->payment_method }}</p>
                @else
                    <p class="text-muted">No payment linked.</p>
                @endif

                @if ($earning->payout)
                    <hr>
                    <h6 class="fw-semibold mb-2">Payout</h6>
                    <p><strong>Payout ID:</strong> #{{ $earning->payout->id }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($earning->payout->status) }}</p>
                @endif

                @if ($earning->description)
                    <hr>
                    <h6 class="fw-semibold mb-2">Notes</h6>
                    <p>{{ $earning->description }}</p>
                @endif
            </div>
        </div>
    </div>

</x-educator-layout>
