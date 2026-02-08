<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $educator->full_name }} - Payouts</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.manage.educators') }}">Educators</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.educators.show', $educator->id) }}">{{ $educator->full_name }}</a></li>
                <li class="breadcrumb-item active">Payouts</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.educators.show', $educator->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Profile
        </a>
    </div>
</div>

<div class="kpi-card p-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Transaction ID</th>
                    <th>Student</th>
                    <th>Amount</th>
                    <th>Net Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $index => $payout)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $payout->transaction_id ?? 'N/A' }}</td>
                        <td>{{ $payout->student ? $payout->student->full_name : 'N/A' }}</td>
                        <td>$ {{ number_format($payout->gross_amount, 2) }}</td>
                        <td>$ {{ number_format($payout->net_amount, 2) }}</td>
                        <td>
                            <span class="badge text-bg-{{ $payout->status === 'completed' ? 'success' : ($payout->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($payout->status) }}
                            </span>
                        </td>
                        <td>{{ $payout->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No payouts found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payouts->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $payouts->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    :root {
        --brand: #0b3c77;
        --brand-700: #093362;
        --brand-600: #0c4b94;
        --ink: #0f172a;
        --muted: #6b7280;
        --card-shadow: 0 10px 30px rgba(11, 60, 119, 0.08);
        --soft: #f6f8fb;
        --good: #16a34a;
        --warn: #d97706;
        --bad: #dc2626;
    }

    body {
        background: var(--soft);
    }

    .kpi-card {
        border: 0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: var(--card-shadow);
    }

    .table thead th {
        color: var(--muted);
        font-weight: 700;
        border-bottom: 1px solid #e5e7eb;
    }

    .table>tbody>tr>td {
        vertical-align: middle;
    }
</style>
@endpush
</x-admin-layout>