<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Educator Profile</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.manage.educators') }}">Educators</a></li>
                <li class="breadcrumb-item active">{{ $educator->full_name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.manage.educators') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Educators
        </a>
        <form method="POST" action="{{ route('admin.educators.status', $educator->id) }}" class="d-inline">
            @csrf
            @method('PATCH')
            <select name="status" onchange="this.form.submit()" class="form-select">
                <option value="approved" {{ $educator->educatorProfile->status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="pending" {{ $educator->educatorProfile->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="rejected" {{ $educator->educatorProfile->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </form>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.educators.courses', $educator->id) }}" class="btn btn-outline-primary">Courses</a>
            <a href="{{ route('admin.educators.payouts', $educator->id) }}" class="btn btn-outline-primary">Payouts</a>
            <a href="{{ route('admin.educators.earnings', $educator->id) }}" class="btn btn-outline-primary">Earnings</a>
            <a href="{{ route('admin.educators.sessions', $educator->id) }}" class="btn btn-outline-primary">Sessions</a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="kpi-card">
            <div class="p-4">
                <div class="d-flex align-items-start gap-3 mb-4">
                    @if($educator->profile_picture_url)
                    <img src="{{ $educator->profile_picture_url }}" alt="avatar" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person text-muted" style="font-size: 2rem;"></i>
                    </div>
                    @endif
                    <div class="flex-grow-1">
                        <h3 class="mb-2">{{ $educator->full_name }}</h3>
                        <div class="d-flex gap-2 mb-2">
                            <span class="badge text-bg-{{ $educator->educatorProfile->status === 'approved' ? 'success' : ($educator->educatorProfile->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($educator->educatorProfile->status) }}
                            </span>
                            <span class="badge bg-primary">{{ $educator->courses ? $educator->courses->count() : 0 }} Courses</span>
                        </div>
                        <p class="text-muted mb-0">{{ $educator->email }}</p>
                    </div>
                </div>

                <!-- Educator Details -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <strong>First Name:</strong> {{ $educator->first_name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Last Name:</strong> {{ $educator->last_name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong> {{ $educator->email }}
                    </div>
                    <div class="col-md-6">
                        <strong>Phone:</strong> {{ $educator->phone ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Joined:</strong> {{ $educator->created_at->format('M d, Y') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Last Login:</strong> {{ $educator->last_login_at ? $educator->last_login_at->format('M d, Y H:i') : 'Never' }}
                    </div>
                </div>

                @if($educator->educatorProfile)
                <!-- Educator Profile Details -->
                <div class="border-top pt-4 mb-4">
                    <h5 class="mb-3">Profile Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Bio:</strong>
                            <p class="mt-1">{{ $educator->educatorProfile->bio ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Experience:</strong>
                            <p class="mt-1">{{ $educator->educatorProfile->experience ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Qualifications:</strong>
                            <p class="mt-1">{{ $educator->educatorProfile->qualifications ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Specializations:</strong>
                            <p class="mt-1">{{ $educator->educatorProfile->specializations ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Teaching Languages:</strong>
                            <p class="mt-1">{{ $educator->educatorProfile->teaching_languages ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Hourly Rate:</strong>
                            <p class="mt-1">{{ $educator->educatorProfile->hourly_rate ? 'AED ' . number_format($educator->educatorProfile->hourly_rate, 0) : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Courses -->
                @if($educator->courses && $educator->courses->count() > 0)
                <div class="border-top pt-4">
                    <h5 class="mb-3">Courses ({{ $educator->courses->count() }})</h5>
                    <div class="row g-3">
                        @foreach($educator->courses as $course)
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">{{ Str::limit($course->title, 40) }}</h6>
                                    <p class="card-text text-muted small">{{ Str::limit(strip_tags($course->description), 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-secondary">{{ ucfirst($course->level ?? 'N/A') }}</span>
                                        <span class="badge text-bg-{{ $course->status === 'published' ? 'success' : 'warning' }}">
                                            {{ ucfirst($course->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            @if($course->price > 0)
                                                AED {{ number_format($course->price, 0) }}
                                            @else
                                                Free
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="border-top pt-4">
                    <p class="text-muted mb-0">No courses created yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Educator Stats -->
        <div class="kpi-card mb-4">
            <div class="p-3">
                <h6 class="mb-3">Statistics</h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="kpi-value">{{ $educator->courses ? $educator->courses->count() : 0 }}</div>
                            <div class="kpi-label">Courses</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="kpi-value">{{ $educator->courses ? $educator->courses->where('status', 'published')->count() : 0 }}</div>
                            <div class="kpi-label">Published</div>
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
                    <dt class="col-sm-5">Status:</dt>
                    <dd class="col-sm-7">{{ ucfirst($educator->educatorProfile->status ?? 'N/A') }}</dd>

                    <dt class="col-sm-5">Verified:</dt>
                    <dd class="col-sm-7">{{ $educator->email_verified_at ? 'Yes' : 'No' }}</dd>

                    <dt class="col-sm-5">Active:</dt>
                    <dd class="col-sm-7">{{ $educator->is_active ? 'Yes' : 'No' }}</dd>
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
</x-admin-layout>