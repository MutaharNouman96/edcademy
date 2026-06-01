<x-admin-layout>
    @include('admin.courses.partials.alerts')

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Course Revenue</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.manage.courses') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.show', $course->id) }}">{{ Str::limit($course->title, 30) }}</a></li>
                    <li class="breadcrumb-item active">Revenue</li>
                </ol>
            </nav>
        </div>
        @include('admin.courses.partials.actions')
    </div>

    @include('admin.courses.partials.nav')

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="kpi-card p-3">
                <div class="kpi-label">Gross Revenue</div>
                <div class="kpi-value">$ {{ number_format($stats['total_revenue'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card p-3">
                <div class="kpi-label">Platform Commission</div>
                <div class="kpi-value">$ {{ number_format($stats['platform_commission'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card p-3">
                <div class="kpi-label">Educator Net</div>
                <div class="kpi-value">$ {{ number_format($stats['educator_net'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card p-3">
                <div class="kpi-label">Completed Orders</div>
                <div class="kpi-value">{{ number_format($stats['completed_orders']) }}</div>
            </div>
        </div>
    </div>

    <div class="kpi-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="section-title mb-0"><i class="bi bi-cash-stack me-2"></i>Sales Transactions</h5>
            <span class="text-muted small">{{ $transactions->count() }} records</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle modern-table data-table w-100">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Transaction ID</th>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Tax</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Method</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>#{{ $transaction['order_id'] }}</td>
                            <td><span class="mono">{{ $transaction['transaction_id'] }}</span></td>
                            <td class="fw-semibold">{{ $transaction['student'] }}</td>
                            <td>{{ $transaction['student_email'] }}</td>
                            <td>{{ $transaction['quantity'] }}</td>
                            <td>$ {{ number_format($transaction['price'], 2) }}</td>
                            <td>$ {{ number_format($transaction['tax'], 2) }}</td>
                            <td class="fw-semibold">$ {{ number_format($transaction['total'], 2) }}</td>
                            <td>
                                <span class="badge text-bg-{{ in_array(strtolower($transaction['status']), ['completed', 'paid']) ? 'success' : 'warning' }}-subtle text-{{ in_array(strtolower($transaction['status']), ['completed', 'paid']) ? 'success' : 'warning' }}-emphasis">
                                    {{ $transaction['status'] }}
                                </span>
                            </td>
                            <td>{{ $transaction['payment_method'] }}</td>
                            <td data-order="{{ $transaction['purchased_at']?->timestamp ?? 0 }}">
                                {{ $transaction['purchased_at']?->format('M d, Y H:i') ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No transactions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('admin.courses.partials.reject-modal')

    @include('admin.courses.partials.datatables')

    @push('styles')
        @include('admin.courses.partials.styles')
    @endpush
</x-admin-layout>
