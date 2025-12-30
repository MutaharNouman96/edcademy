<x-educator-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark-cyan"><i class="bi bi-credit-card-2-front me-2"></i>Payments</h4>

        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Summary -->
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="card text-center p-3 border-0 shadow-sm">
                    <div class="fw-semibold text-muted">Total Received</div>
                    <div class="fs-5 fw-bold text-dark-cyan">${{ number_format($summary['total_received'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center p-3 border-0 shadow-sm">
                    <div class="fw-semibold text-muted">Tax</div>
                    <div class="fs-5 fw-bold text-danger">${{ number_format($summary['total_tax'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center p-3 border-0 shadow-sm">
                    <div class="fw-semibold text-muted">Commission</div>
                    <div class="fs-5 fw-bold text-warning">${{ number_format($summary['total_commission'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center p-3 border-0 shadow-sm">
                    <div class="fw-semibold text-muted">Net Income</div>
                    <div class="fs-5 fw-bold text-success">${{ number_format($summary['total_net'], 2) }}</div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Gross</th>
                            <th>Commission</th>
                            <th>Tax</th>
                            <th>Net</th>
                            <th>Payment Status</th>
                            <th>Payout Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            @php
                                $item = $payment->orderItem?->item;
                            @endphp
                            <tr>
                                <td>{{ $payment->created_at->format('d M Y') }}</td>

                                <td>
                                    <strong>{{ $item->title ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">
                                        {{ class_basename($payment->orderItem?->model) }}
                                    </small>
                                </td>

                                <td>${{ number_format($payment->gross_amount, 2) }}</td>
                                <td>${{ number_format($payment->platform_commission, 2) }}</td>
                                <td>${{ number_format($payment->tax_amount, 2) }}</td>

                                <td class="fw-semibold text-success">
                                    ${{ number_format($payment->net_amount, 2) }}
                                </td>

                                <td>
                                    <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 'info' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-{{ $payment->payout_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payment->payout_status) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('educator.payments.show', $payment) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No payments found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-educator-layout>
