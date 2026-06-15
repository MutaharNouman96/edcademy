<x-admin-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Add New Educator</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.manage.educators') }}">Educators</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.manage.educators') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Educators
    </a>
</div>

<form method="POST" action="{{ route('admin.educators.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- Left Column: Account & Profile -->
        <div class="col-lg-8">
            <!-- Account Details -->
            <div class="kpi-card mb-4">
                <div class="p-4">
                    <h5 class="mb-3"><i class="bi bi-person me-2"></i>Account Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" required>
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" required>
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">A random password will be generated and emailed to this address.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Details -->
            <div class="kpi-card mb-4">
                <div class="p-4">
                    <h5 class="mb-3"><i class="bi bi-mortarboard me-2"></i>Professional Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Primary Subject <span class="text-danger">*</span></label>
                            <input type="text" name="primary_subject" value="{{ old('primary_subject') }}" class="form-control @error('primary_subject') is-invalid @enderror" required>
                            @error('primary_subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Educator Type</label>
                            <select name="educator_type" class="form-select @error('educator_type') is-invalid @enderror">
                                <option value="" {{ old('educator_type') === null || old('educator_type') === '' ? 'selected' : '' }}>Not set</option>
                                <option value="teacher" {{ old('educator_type') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="tutor" {{ old('educator_type') === 'tutor' ? 'selected' : '' }}>Tutor</option>
                            </select>
                            @error('educator_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hourly Rate ($) <span class="text-danger">*</span></label>
                            <input type="number" name="hourly_rate" value="{{ old('hourly_rate') }}" class="form-control @error('hourly_rate') is-invalid @enderror" min="5" step="0.01" required>
                            @error('hourly_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Teaching Levels <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['Elementary', 'Middle School', 'High School', 'Undergraduate', 'Graduate', 'Professional'] as $level)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="teaching_levels[]" value="{{ $level }}" id="level_{{ Str::slug($level) }}"
                                        {{ is_array(old('teaching_levels')) && in_array($level, old('teaching_levels')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="level_{{ Str::slug($level) }}">{{ $level }}</label>
                                </div>
                                @endforeach
                            </div>
                            @error('teaching_levels') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Preferred Teaching Style</label>
                            <input type="text" name="preferred_teaching_style" value="{{ old('preferred_teaching_style') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Certifications</label>
                            <input type="text" name="certifications" value="{{ old('certifications') }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents & Media -->
            @php
                $documentRows = [
                    [
                        'key' => 'cv',
                        'title' => 'CV / Resume',
                        'filename' => null,
                        'url' => null,
                        'input_name' => 'cv',
                        'accept' => '.pdf,.jpeg,.jpg,.png',
                    ],
                    [
                        'key' => 'degree_proof',
                        'title' => 'Degree Proof',
                        'filename' => null,
                        'url' => null,
                        'input_name' => 'degree_proof',
                        'accept' => '.pdf,.jpeg,.jpg,.png',
                    ],
                    [
                        'key' => 'intro_video',
                        'title' => 'Intro Video',
                        'filename' => null,
                        'url' => null,
                        'input_name' => 'intro_video',
                        'accept' => 'video/mp4,video/quicktime',
                    ],
                ];
            @endphp
            <div class="kpi-card mb-4">
                <div class="p-4">
                    <h5 class="mb-3"><i class="bi bi-file-earmark-arrow-up me-2"></i>Documents & Media</h5>
                    <p class="text-muted small mb-3">Use <strong>Update</strong> to choose a replacement file, then save the form.</p>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle doc-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 45%;">Document</th>
                                    <th style="width: 25%;">Status</th>
                                    <th class="text-end" style="width: 30%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentRows as $row)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $row['title'] }}</div>
                                            @if($row['filename'])
                                                <div class="text-muted small text-truncate" style="max-width: 280px;" title="{{ $row['filename'] }}">{{ $row['filename'] }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($row['filename'])
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Uploaded</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Not uploaded</span>
                                            @endif
                                            <div class="text-primary small mt-1 doc-pending-label" id="pending_{{ $row['key'] }}"></div>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-2">
                                                @if($row['url'])
                                                    <a href="{{ $row['url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye me-1"></i>View
                                                    </a>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                                        <i class="bi bi-eye me-1"></i>View
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-dark" data-doc-upload="upload_{{ $row['key'] }}">
                                                    <i class="bi bi-arrow-repeat me-1"></i>Update
                                                </button>
                                            </div>
                                            <input type="file"
                                                id="upload_{{ $row['key'] }}"
                                                name="{{ $row['input_name'] }}"
                                                class="d-none doc-file-input"
                                                accept="{{ $row['accept'] }}"
                                                data-label="pending_{{ $row['key'] }}">
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                        <div class="fw-semibold">Add Additional Document</div>
                                        <div class="text-muted small">Certificates, references, or other files</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-muted border">New upload</span>
                                        <div class="text-primary small mt-1 doc-pending-label" id="pending_additional_new"></div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                                <i class="bi bi-eye me-1"></i>View
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-dark" data-doc-upload="upload_additional_new">
                                                <i class="bi bi-upload me-1"></i>Update
                                            </button>
                                        </div>
                                        <input type="file"
                                            id="upload_additional_new"
                                            name="additional_documents[]"
                                            class="d-none doc-file-input"
                                            accept=".pdf,.jpeg,.jpg,.png"
                                            multiple
                                            data-label="pending_additional_new">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @error('cv') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    @error('degree_proof') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    @error('intro_video') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    @error('additional_documents') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    @error('additional_documents.*') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Right Column: Status & Submit -->
        <div class="col-lg-4">
            <div class="kpi-card mb-4">
                <div class="p-4">
                    <h5 class="mb-3"><i class="bi bi-gear me-2"></i>Account Status</h5>
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="kpi-card mb-4">
                <div class="p-4">
                    <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>What happens next?</h6>
                    <ul class="list-unstyled text-muted small mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i> A user account with role <strong>educator</strong> is created.</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i> A random password is generated.</li>
                        <li class="mb-2"><i class="bi bi-envelope text-primary me-1"></i> Login credentials are emailed to the educator.</li>
                        <li><i class="bi bi-check-circle text-success me-1"></i> Uploaded documents are attached to the profile.</li>
                    </ul>
                </div>
            </div>

            <button type="submit" class="btn btn-brand w-100 py-2">
                <i class="bi bi-person-plus me-2"></i>Create Educator Account
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
    .doc-table th {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        color: var(--muted);
    }
    .doc-table td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('[data-doc-upload]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.docUpload);
            if (input) {
                input.click();
            }
        });
    });

    document.querySelectorAll('.doc-file-input').forEach(function (input) {
        input.addEventListener('change', function () {
            var label = document.getElementById(input.dataset.label);
            if (!label || !input.files.length) {
                return;
            }

            if (input.files.length > 1) {
                label.textContent = input.files.length + ' files selected (save to upload)';
            } else {
                label.textContent = input.files[0].name + ' (save to upload)';
            }
        });
    });
</script>
@endpush
</x-admin-layout>
