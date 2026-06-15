<x-educator-layout>
    @php
        $sym = setting('currency', 'USD') === 'USD' ? '$' : setting('currency', 'USD') . ' ';
        $statusColors = [
            'pending' => 'warning',
            'in_progress' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary',
        ];
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1 fw-bold text-dark-cyan"><i class="bi bi-send-check me-2"></i>Payout Requests</h2>
            <p class="text-muted mb-0 small">Track every payout request you have submitted to the admin team.</p>
        </div>
        <a href="{{ route('educator.payouts.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-bank me-1"></i> Back to Payouts
        </a>
    </div>

    @if ($openCount > 0)
        <div class="alert alert-info d-flex align-items-center gap-2">
            <i class="bi bi-hourglass-split"></i>
            You have <strong>{{ $openCount }}</strong> open request(s) awaiting admin review.
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="section-header">
            <h4 class="section-title mb-0"><i class="bi bi-inbox"></i> Submitted requests</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 data-table">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Submitted</th>
                        <th>Scope</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Resolved</th>
                        <th>Admin notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $req)
                        <tr>
                            <td><code>{{ $req->id }}</code></td>
                            <td class="text-nowrap">{{ $req->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if ($req->payment_id)
                                    <span class="badge bg-light text-dark border">Payment #{{ $req->payment_id }}</span>
                                    @if ($req->payment?->course)
                                        <div class="small text-muted mt-1">{{ Str::limit($req->payment->course->title, 40) }}</div>
                                    @endif
                                @else
                                    <span class="badge bg-primary">All pending earnings</span>
                                @endif
                                @if ($req->payout_batch_id)
                                    <div class="small text-muted mt-1">Batch #{{ $req->payout_batch_id }}</div>
                                @endif
                            </td>
                            <td style="max-width: 220px;">
                                <span class="d-inline-block text-truncate w-100" title="{{ $req->message }}">
                                    {{ $req->message ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $statusColors[$req->status] ?? 'secondary' }} status-badge">
                                    {{ \App\Models\EducatorPayoutRequest::statusOptions()[$req->status] ?? ucfirst($req->status) }}
                                </span>
                            </td>
                            <td class="text-nowrap small text-muted">
                                @if ($req->resolved_at)
                                    {{ $req->resolved_at->format('d M Y') }}
                                    @if ($req->resolver)
                                        <div>by {{ $req->resolver->first_name }}</div>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td style="max-width: 180px;">
                                @if ($req->admin_notes)
                                    <span class="small text-muted">{{ Str::limit($req->admin_notes, 80) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No payout requests yet.
                                <div class="mt-2">
                                    <a href="{{ route('educator.payouts.index') }}">Go to Payouts</a> to submit one.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($requests->hasPages())
            <div class="card-footer">{{ $requests->links() }}</div>
        @endif
    </div>
</x-educator-layout>
