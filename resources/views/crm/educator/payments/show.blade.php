<x-educator-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark-cyan">
                <i class="bi bi-receipt-cutoff me-2"></i>Payment Details
            </h4>
            <a href="{{ route('educator.payments.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Transaction Summary</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted small">Transaction ID</div>
                            <div class="fw-bold">{{ $payment->transaction_id ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light d-flex gap-3">
                            <div class="text-muted small">

                                <div> Payment Status </div>

                                <span
                                    class="badge 
                            @if ($payment->status == 'completed') bg-success 
                            @elseif($payment->status == 'pending') bg-warning 
                            @elseif($payment->status == 'refunded') bg-secondary 
                            @else bg-danger @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>


                            </div>


                            <div class="text-muted small">

                                <div> Payout Status </div>

                                <span
                                    class="badge 
                            @if ($payment->payout_status == 'completed') bg-success 
                            @elseif($payment->payout_status == 'pending') bg-warning 
                            @elseif($payment->payout_status == 'refunded') bg-secondary 
                            @else bg-danger @endif">
                                    {{ ucfirst($payment->payout_status) }}
                                </span>


                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <div class="text-muted small">Payment Method</div>
                            <div class="fw-bold">{{ $payment->order->payment_method ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-semibold mb-3">Financial Breakdown</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <div class="text-muted small">Gross Amount</div>
                            <div class="fw-bold fs-5 text-dark-cyan">${{ number_format($payment->gross_amount, 2) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <div class="text-muted small">Tax</div>
                            <div class="fw-bold text-danger">${{ number_format($payment->tax_amount, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <div class="text-muted small">Commission</div>
                            <div class="fw-bold text-warning">${{ number_format($payment->platform_commission, 2) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <div class="text-muted small">Net Amount</div>
                            <div class="fw-bold text-success fs-5">${{ number_format($payment->net_amount, 2) }}</div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-semibold mb-3">Parties Involved</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <div class="text-muted small">Educator</div>
                            <div class="fw-bold">{{ $payment->educator->full_name ?? 'N/A' }}</div>
                            <div class="small text-muted">{{ $payment->educator->email ?? '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <div class="text-muted small">Student</div>
                            <div class="fw-bold">{{ $payment->order->user->full_name ?? 'N/A' }}</div>
                            <div class="small text-muted">{{ $payment->order->user->email ?? '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <div class="text-muted small">Date</div>
                            <div class="fw-bold">{{ $payment->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-semibold mb-3">Linked Items</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div class="text-muted small">Course</div>
                            <div class="fw-bold">{{ $payment->orderItem->item_details->title ?? 'N/A' }}</div>
                        </div>
                    </div>



                </div>

                @if ($payment->notes)
                    <hr class="my-4">
                    <h5 class="fw-semibold mb-2">Notes</h5>
                    <p class="text-muted">{{ $payment->notes }}</p>
                @endif
            </div>
        </div>
    </div>
</x-educator-layout>
