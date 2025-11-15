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

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Gross</th>
                            <th>Tax</th>
                            <th>Commission</th>
                            <th>Net</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>${{ number_format($payment->gross_amount, 2) }}</td>
                                <td>${{ number_format($payment->tax_amount, 2) }}</td>
                                <td>${{ number_format($payment->platform_commission, 2) }}</td>
                                <td class="fw-bold text-success">${{ number_format($payment->net_amount, 2) }}</td>
                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                <td>
                                    <span
                                        class="badge 
                                    @if ($payment->status == 'completed') bg-success 
                                    @elseif($payment->status == 'pending') bg-warning 
                                    @elseif($payment->status == 'refunded') bg-secondary 
                                    @else bg-danger @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('d M Y') }}</td>
                             
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No payments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-educator-layout>