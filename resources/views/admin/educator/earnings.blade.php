<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $educator->full_name }} - Earnings</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.manage.educators') }}">Educators</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.educators.show', $educator->id) }}">{{ $educator->full_name }}</a></li>
                <li class="breadcrumb-item active">Earnings</li>
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
                    <th>Course</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($earnings as $index => $earning)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $earning->course ? $earning->course->title : 'N/A' }}</td>
                        <td>{{ ucfirst($earning->type ?? 'N/A') }}</td>
                        <td>AED {{ number_format($earning->net_amount, 2) }}</td>
                        <td>
                            <span class="badge text-bg-{{ $earning->status === 'paid' ? 'success' : ($earning->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($earning->status) }}
                            </span>
                        </td>
                        <td>{{ $earning->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No earnings found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($earnings->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $earnings->appends(request()->query())->links() }}
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