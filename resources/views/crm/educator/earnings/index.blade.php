<x-educator-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark-cyan"><i class="bi bi-cash-coin me-2"></i>My Earnings</h4>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Pending</div>
                    <div class="fw-bold fs-4 text-warning">${{ number_format($totalPending, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Approved</div>
                    <div class="fw-bold fs-4 text-primary">${{ number_format($totalApproved, 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-3">
                    <div class="text-muted small">Paid</div>
                    <div class="fw-bold fs-4 text-success">${{ number_format($totalPaid, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Source</th>
                            <th>Gross</th>
                            <th>Commission</th>
                            <th>Net</th>
                            <th>Status</th>
                            <th>Earned At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($earnings as $earning)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($earning->source_type === 'session')
                                        Session: {{ $earning->session->title ?? 'N/A' }}
                                    @elseif($earning->source_type === 'course')
                                        Course: {{ $earning->course->title ?? 'N/A' }}
                                    @elseif($earning->source_type === 'resource')
                                        Resource: {{ $earning->courseResource->title ?? 'N/A' }}
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td>${{ number_format($earning->gross_amount, 2) }}</td>
                                <td>${{ number_format($earning->platform_commission, 2) }}</td>
                                <td class="fw-bold text-success">${{ number_format($earning->net_amount, 2) }}</td>
                                <td>
                                    <span
                                        class="badge 
                                    @if ($earning->status == 'paid') bg-success 
                                    @elseif($earning->status == 'approved') bg-info 
                                    @elseif($earning->status == 'pending') bg-warning 
                                    @else bg-secondary @endif">
                                        {{ ucfirst($earning->status) }}
                                    </span>
                                </td>
                                <td>{{ $earning->earned_at ? $earning->earned_at->format('d M Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('educator.earnings.show', $earning) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No earnings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $earnings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-educator-layout>
