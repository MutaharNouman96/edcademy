<x-admin-layout>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Payout Request #{{ $payoutRequest->id }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.payouts.index', ['view' => 'requests']) }}">Payout requests</a></li>
                    <li class="breadcrumb-item active">Request #{{ $payoutRequest->id }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.payouts.index', ['view' => 'requests']) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="kpi-card p-3 h-100">
                <h5 class="mb-3">Educator</h5>
                @php $educator = $payoutRequest->educator; @endphp
                <dl class="row mb-0">
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8">{{ $educator?->full_name ?? '—' }}</dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">{{ $educator?->email ?? '—' }}</dd>
                    <dt class="col-sm-4">Stripe</dt>
                    <dd class="col-sm-8">
                        @if ($educator?->canReceivePayouts())
                            <span class="badge text-bg-success">Ready for payout</span>
                        @elseif ($educator?->stripe_connect_id)
                            <span class="badge text-bg-warning">Setup incomplete</span>
                        @else
                            <span class="badge text-bg-secondary">Not connected</span>
                        @endif
                    </dd>
                    <dt class="col-sm-4">Profile</dt>
                    <dd class="col-sm-8">
                        @if ($educator)
                            <a href="{{ route('admin.educators.show', $educator->id) }}" class="btn btn-sm btn-outline-primary">View profile</a>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="kpi-card p-3 mb-4">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <h5 class="mb-0">Request details</h5>
                    <span class="badge bg-{{ match($payoutRequest->status) {
                        'pending' => 'warning', 'in_progress' => 'info', 'resolved' => 'success', default => 'secondary'
                    } }}">
                        {{ \App\Models\EducatorPayoutRequest::statusOptions()[$payoutRequest->status] ?? $payoutRequest->status }}
                    </span>
                </div>
                <p class="text-muted small mb-2">Submitted {{ $payoutRequest->created_at->format('M d, Y H:i') }}</p>
                <div class="border rounded p-3 bg-light mb-3">{{ $payoutRequest->message ?: 'No message.' }}</div>

                <dl class="row small mb-0">
                    <dt class="col-sm-3">Scope</dt>
                    <dd class="col-sm-9">
                        @if ($payoutRequest->payment_id)
                            Single payment #{{ $payoutRequest->payment_id }}
                            @if($payoutRequest->payment?->course)
                                — {{ $payoutRequest->payment->course->title }}
                            @endif
                        @else
                            All pending earnings for this educator
                        @endif
                    </dd>
                    @if($payoutRequest->payout_batch_id)
                        <dt class="col-sm-3">Linked batch</dt>
                        <dd class="col-sm-9">
                            <a href="{{ route('admin.payout-batches.show', $payoutRequest->payout_batch_id) }}">Batch #{{ $payoutRequest->payout_batch_id }}</a>
                        </dd>
                    @endif
                </dl>
            </div>

            @if($pendingPayments->isNotEmpty())
                <div class="kpi-card p-3 mb-4">
                    <h5 class="mb-3">Eligible pending payments</h5>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>ID</th><th>Course</th><th class="text-end">Net</th></tr></thead>
                            <tbody>
                                @foreach($pendingPayments as $p)
                                    @php $net = \App\Http\Controllers\Educator\PayoutController::payableAmount($p); @endphp
                                    <tr @if($payoutRequest->payment_id && $p->id == $payoutRequest->payment_id) class="table-info" @endif>
                                        <td>#{{ $p->id }}</td>
                                        <td>{{ Str::limit($p->course?->title ?? '—', 40) }}</td>
                                        <td class="text-end">${{ number_format($net, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @php
                $isPayoutRelease = $payoutRequest->payment_id || $pendingPayments->isNotEmpty();
            @endphp

            {{-- Approve & queue release job (payout release requests only) --}}
            @if($payoutRequest->isOpen() && $isPayoutRelease)
                <div class="kpi-card p-3 mb-4 border border-success">
                    <h5 class="text-success mb-2"><i class="bi bi-check-circle me-1"></i> Approve &amp; release payout</h5>
                    <p class="small text-muted mb-3">
                        Approving queues <code>ReleaseEducatorPayoutJob</code> to run in
                        <strong>{{ $approvalDelay }} minute(s)</strong> (config: <code>payout.approval_delay_minutes</code>).
                        Educator and admin receive emails when processing completes.
                    </p>
                    <form method="POST" action="{{ route('admin.educator-payout-requests.approve', $payoutRequest) }}"
                        onsubmit="return confirm('Approve this request and schedule payout release in {{ $approvalDelay }} minute(s)?');">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Admin notes (optional)</label>
                            <textarea name="admin_notes" class="form-control" rows="2" placeholder="Visible to educator in approval email">{{ old('admin_notes', $payoutRequest->admin_notes) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success"
                            @disabled($payoutRequest->payment_id && !$pendingPayments->contains('id', $payoutRequest->payment_id))>
                            <i class="bi bi-send-check me-1"></i> Approve &amp; schedule release
                        </button>
                    </form>
                </div>
            @endif

            <div class="kpi-card p-3">
                <h5 class="mb-3">Update status manually</h5>
                <form method="POST" action="{{ route('admin.educator-payout-requests.update', $payoutRequest) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach (\App\Models\EducatorPayoutRequest::statusOptions() as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $payoutRequest->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Admin notes</label>
                        <textarea name="admin_notes" class="form-control" rows="3">{{ old('admin_notes', $payoutRequest->admin_notes) }}</textarea>
                    </div>
                    @if ($payoutRequest->resolved_at)
                        <p class="text-muted small">Resolved by {{ $payoutRequest->resolver?->full_name ?? 'Admin' }} on {{ $payoutRequest->resolved_at->format('M d, Y H:i') }}.</p>
                    @endif
                    <button type="submit" class="btn btn-brand"><i class="bi bi-save me-1"></i> Save</button>
                </form>
            </div>
        </div>
    </div>

@push('styles')
<style>.kpi-card { border:0; border-radius:1rem; background:#fff; box-shadow:0 10px 30px rgba(11,60,119,.08); } .btn-brand { background:#0b3c77; color:#fff; }</style>
@endpush
</x-admin-layout>
