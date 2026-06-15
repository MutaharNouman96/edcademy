<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $educator->full_name }} — Payments &amp; Payouts</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.manage.educators') }}">Educators</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.educators.show', $educator->id) }}">{{ $educator->full_name }}</a></li>
                <li class="breadcrumb-item active">Payouts</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('admin.payouts.educator-release', $educator) }}"
            onsubmit="return confirm('Queue payout release for this educator now?');">
            @csrf
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-play me-1"></i>Release pending</button>
        </form>
        <a href="{{ route('admin.educators.show', $educator->id) }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
</div>

<div class="kpi-card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th class="text-end">Gross</th>
                    <th class="text-end">Net</th>
                    <th>Payment</th>
                    <th>Payout</th>
                    <th>Batch</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $index => $payment)
                    @php $net = \App\Http\Controllers\Educator\PayoutController::payableAmount($payment); @endphp
                    <tr>
                        <td>{{ $payouts->firstItem() + $index }}</td>
                        <td class="text-nowrap">{{ $payment->created_at->format('d M Y') }}</td>
                        <td>{{ $payment->student?->full_name ?? 'N/A' }}</td>
                        <td>{{ $payment->course?->title ?? '—' }}</td>
                        <td class="text-end">${{ number_format($payment->gross_amount, 2) }}</td>
                        <td class="text-end fw-semibold text-success">${{ number_format($net, 2) }}</td>
                        <td><span class="badge bg-{{ $payment->status === 'approved' ? 'success' : 'secondary' }}">{{ ucfirst($payment->status) }}</span></td>
                        <td>
                            @if($payment->is_payout_processed)
                                <span class="badge bg-success">Paid</span>
                            @elseif($payment->payout_batch_id)
                                <span class="badge bg-info">{{ ucfirst($payment->payout_status ?? 'processing') }}</span>
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
    @if($payouts->hasPages())
        <div class="p-3">{{ $payouts->appends(request()->query())->links() }}</div>
    @endif
</div>

@push('styles')
<style>.kpi-card { border:0; border-radius:1rem; background:#fff; box-shadow:0 10px 30px rgba(11,60,119,.08); }</style>
@endpush
</x-admin-layout>
