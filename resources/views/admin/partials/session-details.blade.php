<div class="row">
    <div class="col-md-6">
        <h6 class="mb-3">Session Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Educator:</strong></td>
                <td>{{ $session->educator ? $session->educator->full_name : 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Course:</strong></td>
                <td>{{ $session->course ? $session->course->title : 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Session Date:</strong></td>
                <td>{{ $session->session_date ? $session->session_date->format('M d, Y H:i') : 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Duration:</strong></td>
                <td>{{ $session->duration ?? 'N/A' }} minutes</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <span class="badge text-bg-{{ $session->status === 'completed' ? 'success' : ($session->status === 'scheduled' ? 'info' : 'warning') }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="mb-3">Additional Details</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Created:</strong></td>
                <td>{{ $session->created_at->format('M d, Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Last Updated:</strong></td>
                <td>{{ $session->updated_at->format('M d, Y H:i') }}</td>
            </tr>
            @if($session->notes)
            <tr>
                <td><strong>Notes:</strong></td>
                <td>{{ $session->notes }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>

@if($session->students && $session->students->count() > 0)
<div class="mt-4">
    <h6 class="mb-3">Attendees</h6>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($session->students as $student)
                <tr>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->pivot->created_at ? $student->pivot->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif