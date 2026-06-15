<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="mb-1">Payout Batch #{{ $batch->id }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.payouts.index', ['view' => 'batches']) }}">Payout batches</a></li>
                <li class="breadcrumb-item active">Batch #{{ $batch->id }}</li>
            </ol>
        </nav>
    </div>
    <span class="badge fs-6 bg-{{ $batch->status === 'completed' ? 'success' : ($batch->status === 'failed' ? 'danger' : 'info') }}">
        {{ ucfirst($batch->status) }}
    </span>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="kpi-card p-3 mb-4">
            <h5 class="mb-3">Batch summary</h5>
            <dl class="row mb-0 small">
                <dt class="col-5 text-muted">Educator</dt>
                <dd class="col-7">{{ $batch->educator?->full_name ?? '—' }}</dd>

                <dt class="col-5 text-muted">Gross total</dt>
                <dd class="col-7">${{ number_format($batch->total_amount, 2) }}</dd>

                <dt class="col-5 text-muted">Commission</dt>
                <dd class="col-7">${{ number_format($batch->total_commission, 2) }}</dd>

                <dt class="col-5 text-muted">Net paid</dt>
                <dd class="col-7 fw-bold text-success">${{ number_format($batch->total_net_amount, 2) }} {{ $batch->currency }}</dd>

                <dt class="col-5 text-muted">Period</dt>
                <dd class="col-7">
                    @if($batch->start_date && $batch->end_date)
                        {{ $batch->start_date->format('d M Y') }} – {{ $batch->end_date->format('d M Y') }}
                    @else — @endif
                </dd>

                <dt class="col-5 text-muted">Processed by</dt>
                <dd class="col-7"><code>{{ $batch->processed_by ?? '—' }}</code></dd>

                <dt class="col-5 text-muted">Processed at</dt>
                <dd class="col-7">{{ $batch->processed_at?->format('d M Y H:i') ?? '—' }}</dd>

                <dt class="col-5 text-muted">Description</dt>
                <dd class="col-7">{{ $batch->description ?? '—' }}</dd>

                @if($batch->notes)
                    <dt class="col-5 text-muted">Notes</dt>
                    <dd class="col-7">{{ $batch->notes }}</dd>
                @endif
            </dl>
        </div>

        <div class="kpi-card p-3">
            <h5 class="mb-3">Job / processor response</h5>
            @if(!empty($stripeResponse))
                <pre class="bg-light p-3 rounded small mb-0" style="max-height:280px;overflow:auto;">{{ json_encode($stripeResponse, JSON_PRETTY_PRINT) }}</pre>
            @else
                <p class="text-muted mb-0">No processor response recorded.</p>
            @endif
        </div>
    </div>

    <div class="col-lg-7">
        <div class="kpi-card p-3">
            <h5 class="mb-3">Included payments ({{ $payments->count() }})</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Course</th>
                            <th>Student</th>
                            <th class="text-end">Net</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            @php $net = \App\Http\Controllers\Educator\PayoutController::payableAmount($payment); @endphp
                            <tr>
                                <td>#{{ $payment->id }}</td>
                                <td class="text-nowrap">{{ $payment->created_at->format('d M Y') }}</td>
                                <td>{{ Str::limit($payment->course?->title ?? '—', 28) }}</td>
                                <td class="small">{{ $payment->student?->full_name ?? '—' }}</td>
                                <td class="text-end">${{ number_format($net, 2) }}</td>
                                <td><span class="badge bg-{{ $payment->is_payout_processed ? 'success' : 'warning' }}">{{ $payment->is_payout_processed ? 'Paid' : ucfirst($payment->payout_status ?? 'pending') }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="fw-semibold">Total</td>
                            <td class="text-end fw-bold">${{ number_format($payments->sum(fn($p) => \App\Http\Controllers\Educator\PayoutController::payableAmount($p)), 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .kpi-card { border: 0; border-radius: 1rem; background: #fff; box-shadow: 0 10px 30px rgba(11,60,119,.08); }
</style>
@endpush
</x-admin-layout>
