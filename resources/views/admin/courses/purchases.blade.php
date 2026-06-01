<x-admin-layout>
    @include('admin.courses.partials.alerts')

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Course Purchases</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.manage.courses') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.show', $course->id) }}">{{ Str::limit($course->title, 30) }}</a></li>
                    <li class="breadcrumb-item active">Purchases</li>
                </ol>
            </nav>
        </div>
        @include('admin.courses.partials.actions')
    </div>

    @include('admin.courses.partials.nav')

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="kpi-label">Unique Purchasers</div>
                <div class="kpi-value">{{ number_format($stats['unique_purchasers']) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="kpi-label">Active Enrollments</div>
                <div class="kpi-value">{{ number_format($stats['active_enrollments']) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card p-3">
                <div class="kpi-label">Course Price</div>
                <div class="kpi-value">
                    @if ($course->price > 0)
                        $ {{ number_format($course->price, 0) }}
                    @else
                        Free
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="kpi-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="section-title mb-0"><i class="bi bi-people me-2"></i>Enrolled Students</h5>
            <span class="text-muted small">{{ $purchases->count() }} records</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle modern-table data-table w-100">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Source</th>
                        <th>Enrollment Status</th>
                        <th>Payment Status</th>
                        <th>Amount</th>
                        <th>Enrolled At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td class="fw-semibold">{{ $purchase['name'] }}</td>
                            <td>{{ $purchase['email'] }}</td>
                            <td>{{ $purchase['source'] }}</td>
                            <td>
                                <span class="badge text-bg-{{ $purchase['status'] === 'Active' ? 'success' : 'secondary' }}-subtle text-{{ $purchase['status'] === 'Active' ? 'success' : 'secondary' }}-emphasis">
                                    {{ $purchase['status'] }}
                                </span>
                            </td>
                            <td>{{ $purchase['payment_status'] }}</td>
                            <td>
                                @if ($purchase['amount'] > 0)
                                    $ {{ number_format($purchase['amount'], 2) }}
                                @else
                                    Free
                                @endif
                            </td>
                            <td data-order="{{ $purchase['purchased_at']?->timestamp ?? 0 }}">
                                {{ $purchase['purchased_at']?->format('M d, Y H:i') ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No purchases found
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
