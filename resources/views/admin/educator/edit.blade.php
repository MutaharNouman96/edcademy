<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Edit Educator</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.manage.educators') }}">Educators</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.educators.show', $educator->id) }}">{{ $educator->full_name }}</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.educators.show', $educator->id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Profile
    </a>
</div>

@php $profile = $educator->educatorProfile; @endphp

<form method="POST" action="{{ route('admin.educators.update', $educator->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Account Details -->
            <div class="kpi-card mb-4">
                <div class="p-4">
                    <h5 class="mb-3"><i class="bi bi-person me-2"></i>Account Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name', $educator->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" required>
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name', $educator->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required>
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $educator->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Details -->
            <div class="kpi-card mb-4">
                <div class="p-4">
                    <h5 class="mb-3"><i class="bi bi-mortarboard me-2"></i>Professional Details</h5>
                    @php
                        $teachingLevels = [];
                        if ($profile && $profile->teaching_levels) {
                            $decoded = json_decode($profile->teaching_levels, true);
                            $teachingLevels = is_array($decoded) ? $decoded : [];
                        }
                    @endphp
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Primary Subject <span class="text-danger">*</span></label>
                            <input type="text" name="primary_subject" value="{{ old('primary_subject', $profile->primary_subject ?? '') }}" class="form-control @error('primary_subject') is-invalid @enderror" required>
                            @error('primary_subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hourly Rate ($) <span class="text-danger">*</span></label>
                            <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate ?? '') }}" class="form-control @error('hourly_rate') is-invalid @enderror" min="5" step="0.01" required>
                            @error('hourly_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Teaching Levels <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['Elementary', 'Middle School', 'High School', 'Undergraduate', 'Graduate', 'Professional'] as $level)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="teaching_levels[]" value="{{ $level }}" id="level_{{ Str::slug($level) }}"
                                        {{ in_array($level, old('teaching_levels', $teachingLevels)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="level_{{ Str::slug($level) }}">{{ $level }}</label>
                                </div>
                                @endforeach
                            </div>
                            @error('teaching_levels') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Preferred Teaching Style</label>
                            <input type="text" name="preferred_teaching_style" value="{{ old('preferred_teaching_style', $profile->preferred_teaching_style ?? '') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Certifications</label>
                            <input type="text" name="certifications" value="{{ old('certifications', $profile->certifications ?? '') }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Uploads -->
            <div class="kpi-card mb-4">
                <div class="p-4">
                    <h5 class="mb-3"><i class="bi bi-file-earmark-arrow-up me-2"></i>Documents & Media</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">CV / Resume</label>
                            @if($profile && $profile->cv_path)
                                <div class="mb-2">
                                    <a href="{{ asset($profile->cv_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View Current
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="cv" class="form-control @error('cv') is-invalid @enderror" accept=".pdf,.jpeg,.jpg,.png">
                            @error('cv') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Upload new to replace. PDF, JPEG, PNG (max 6MB)</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Degree Proof</label>
                            @if($profile && $profile->degree_proof_path)
                                <div class="mb-2">
                                    <a href="{{ asset($profile->degree_proof_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View Current
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="degree_proof" class="form-control @error('degree_proof') is-invalid @enderror" accept=".pdf,.jpeg,.jpg,.png">
                            @error('degree_proof') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Upload new to replace. PDF, JPEG, PNG (max 6MB)</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Intro Video</label>
                            @if($profile && $profile->intro_video_path)
                                <div class="mb-2">
                                    <a href="{{ asset($profile->intro_video_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-play-circle me-1"></i>View Current
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="intro_video" class="form-control @error('intro_video') is-invalid @enderror" accept="video/mp4,video/quicktime">
                            @error('intro_video') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Upload new to replace. MP4 or MOV (max 50MB)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <div class="kpi-card mb-4">
                <div class="p-4">
                    <h5 class="mb-3"><i class="bi bi-gear me-2"></i>Account Status</h5>
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="pending" {{ old('status', $profile->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $profile->status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $profile->status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="kpi-card mb-4">
                <div class="p-4">
                    <h6 class="mb-2">Account Info</h6>
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">Joined:</dt>
                        <dd class="col-7">{{ $educator->created_at->format('M d, Y') }}</dd>
                        <dt class="col-5 text-muted">Verified:</dt>
                        <dd class="col-7">{{ $educator->email_verified_at ? 'Yes' : 'No' }}</dd>
                    </dl>
                </div>
            </div>

            <button type="submit" class="btn btn-brand w-100 py-2">
                <i class="bi bi-check-lg me-2"></i>Save Changes
            </button>
        </div>
    </div>
</form>

@push('styles')
<style>
    :root {
        --brand: #0b3c77;
        --brand-700: #093362;
        --ink: #0f172a;
        --muted: #6b7280;
        --card-shadow: 0 10px 30px rgba(11, 60, 119, 0.08);
        --soft: #f6f8fb;
    }
    body { background: var(--soft); }
    .kpi-card {
        border: 0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: var(--card-shadow);
    }
    .btn-brand {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }
    .btn-brand:hover {
        background: var(--brand-700);
        border-color: var(--brand-700);
        color: #fff;
    }
</style>
@endpush
</x-admin-layout>
