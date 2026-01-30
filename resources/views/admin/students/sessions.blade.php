<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $student->full_name }} - Session History</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.manage.students') }}">Students</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.students.show', $student->id) }}">{{ $student->full_name }}</a></li>
                <li class="breadcrumb-item active">Sessions</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-outline-secondary">
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
                    <th>Educator</th>
                    <th>Course</th>
                    <th>Session Date</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $index => $session)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $session->educator ? $session->educator->full_name : 'N/A' }}</td>
                        <td>{{ $session->course ? Str::limit($session->course->title, 30) : 'N/A' }}</td>
                        <td>{{ $session->session_date ? $session->session_date->format('M d, Y H:i') : 'N/A' }}</td>
                        <td>{{ $session->duration ?? 'N/A' }} minutes</td>
                        <td>
                            <span class="badge text-bg-{{ $session->status === 'completed' ? 'success' : ($session->status === 'scheduled' ? 'info' : 'warning') }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewSessionDetails({{ $session->id }})">
                                <i class="bi bi-eye"></i> View
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No sessions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sessions->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $sessions->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Session Details Modal -->
<div class="modal fade" id="sessionDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Session Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="sessionDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewSessionDetails(sessionId) {
    fetch(`{{ route('admin.students.session.details', ['studentId' => $student->id, 'sessionId' => ':sessionId']) }}`.replace(':sessionId', sessionId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('sessionDetailsContent').innerHTML = data.html;
                new bootstrap.Modal(document.getElementById('sessionDetailsModal')).show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
</script>
@endpush

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