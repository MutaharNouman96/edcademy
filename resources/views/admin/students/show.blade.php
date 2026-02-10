<x-admin-layout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Student Profile</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.manage.students') }}">Students</a></li>
                    <li class="breadcrumb-item active">{{ $student->full_name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.manage.students') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Students
            </a>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-info" onclick="sendMessage({{ $student->id }})">
                    <i class="bi bi-envelope me-1"></i>Send Message
                </button>
                <button type="button" class="btn btn-outline-warning" onclick="resetPassword({{ $student->id }})">
                    <i class="bi bi-key me-1"></i>Reset Password
                </button>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-gear me-1"></i>Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="viewActivityLogs({{ $student->id }})">
                                <i class="bi bi-activity me-2"></i>View Activity Logs
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#"
                                onclick="deleteStudent({{ $student->id }})">
                                <i class="bi bi-trash me-2"></i>Delete Student
                            </a></li>
                    </ul>
                </div>
            </div>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.students.courses', $student->id) }}"
                    class="btn btn-outline-primary">Courses</a>
                <a href="{{ route('admin.students.payments', $student->id) }}"
                    class="btn btn-outline-primary">Payments</a>
                <a href="{{ route('admin.students.sessions', $student->id) }}"
                    class="btn btn-outline-primary">Sessions</a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="kpi-card">
                <div class="p-4">
                    <div class="d-flex align-items-start gap-3 mb-4">
                        @if ($student->profile_picture_url)
                            <img src="{{ $student->profile_picture_url }}" alt="avatar" class="rounded-circle"
                                style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-person text-muted" style="font-size: 2rem;"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h3 class="mb-2">{{ $student->full_name }}</h3>
                            <div class="d-flex gap-2 mb-2">
                                <span
                                    class="badge bg-primary">{{ $student->purchasedCourses ? $student->purchasedCourses->count() : 0 }}
                                    Courses Purchased</span>
                            </div>
                            <p class="text-muted mb-0">{{ $student->email }}</p>
                        </div>
                    </div>

                    <!-- Student Details -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <strong>First Name:</strong> {{ $student->first_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Last Name:</strong> {{ $student->last_name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $student->email }}
                        </div>
                        <div class="col-md-6">
                            <strong>Phone:</strong> {{ $student->phone ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Joined:</strong> {{ $student->created_at->format('M d, Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Last Login:</strong>
                            {{ $student->last_login_at ? $student->last_login_at->format('M d, Y H:i') : 'Never' }}
                        </div>
                    </div>

                    @if ($student->studentProfile)
                        <!-- Student Profile Details -->
                        <div class="border-top pt-4 mb-4">
                            <h5 class="mb-3">Profile Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <strong>Bio:</strong>
                                    <p class="mt-1">{{ $student->studentProfile->bio ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Education:</strong>
                                    <p class="mt-1">{{ $student->studentProfile->education ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Interests:</strong>
                                    <p class="mt-1">{{ $student->studentProfile->interests ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Location:</strong>
                                    <p class="mt-1">{{ $student->studentProfile->location ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Purchased Courses -->
                    @if ($student->purchasedCourses && $student->purchasedCourses->count() > 0)
                        <div class="border-top pt-4">
                            <h5 class="mb-3">Purchased Courses ({{ $student->purchasedCourses->count() }})</h5>
                            <div class="row g-3">
                                @foreach ($student->purchasedCourses->take(6) as $course)
                               
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ Str::limit($course->purchasable->title, 40) }}</h6>
                                                <p class="card-text text-muted small">
                                                    {{ Str::limit(strip_tags($course->purchasable->description), 80) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span
                                                        class="badge bg-secondary">{{ ucfirst($course->purchasable->level ?? 'N/A') }}</span>
                                                    <small
                                                        class="text-muted">{{ $course->purchasable->educator ? $course->purchasable->educator->full_name : 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if ($student->purchasedCourses->count() > 6)
                                    <div class="col-12">
                                        <a href="{{ route('admin.students.courses', $student->id) }}"
                                            class="btn btn-outline-primary">View All Courses</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="border-top pt-4">
                            <p class="text-muted mb-0">No courses purchased yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Student Stats -->
            <div class="kpi-card mb-4">
                <div class="p-3">
                    <h6 class="mb-3">Statistics</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="kpi-value">
                                    {{ $student->purchasedCourses ? $student->purchasedCourses->count() : 0 }}</div>
                                <div class="kpi-label">Courses</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="kpi-value">
                                    {{ App\Models\Payment::where('student_id', $student->id)->sum('gross_amount') }}
                                </div>
                                <div class="kpi-label">Total Spent</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="kpi-card">
                <div class="p-3">
                    <h6 class="mb-3">Additional Information</h6>
                    <dl class="row">
                        <dt class="col-sm-5">Verified:</dt>
                        <dd class="col-sm-7">{{ $student->email_verified_at ? 'Yes' : 'No' }}</dd>

                        <dt class="col-sm-5">Active:</dt>
                        <dd class="col-sm-7">{{ $student->is_active ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
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

            .kpi-value {
                font-size: 1.75rem;
                font-weight: 800;
                color: var(--ink);
            }

            .kpi-label {
                color: var(--muted);
                font-weight: 600;
            }

            .card {
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
            }

            .card:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Send message to student
            function sendMessage(studentId) {
                // You can implement a modal to send message or redirect to chat
                window.location.href = `{{ url('/admin/chat/open') }}/${studentId}`;
            }

            // Reset student password
            function resetPassword(studentId) {
                if (confirm(
                        'Are you sure you want to reset this student\'s password? A new password will be generated and emailed to them.'
                        )) {
                    fetch(`{{ route('admin.students.show', ':studentId') }}`.replace(':studentId', studentId) +
                            '/reset-password', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Password reset email sent successfully!');
                            } else {
                                alert('Failed to reset password. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                }
            }

            // View activity logs
            function viewActivityLogs(studentId) {
                // Open activity logs in a new tab or modal
                window.open(`{{ route('admin.students.show', ':studentId') }}`.replace(':studentId', studentId) +
                    '/activity-logs', '_blank');
            }

            // Delete student
            function deleteStudent(studentId) {
                if (confirm(
                        'Are you sure you want to delete this student? This action cannot be undone and will remove all associated data.'
                        )) {
                    if (confirm('This will permanently delete the student account. Are you absolutely sure?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ route('admin.students.show', ':studentId') }}`.replace(':studentId', studentId);

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        form.appendChild(csrfToken);

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            }
        </script>
    @endpush
</x-admin-layout>
