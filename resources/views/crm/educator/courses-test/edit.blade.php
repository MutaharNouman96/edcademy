<x-educator-layout>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" />
        <style>
            #addLessonVideoZone.dropzone,
            #editLessonVideoZone.dropzone {
                min-height: 140px;
                border: 2px dashed #cbd5e1;
                background: #f8fafc;
            }

            #addLessonVideoZone .dz-message,
            #editLessonVideoZone .dz-message {
                font-weight: 500;
                color: #64748b;
            }

            #addLessonVideoPreview,
            #editLessonVideoPreview {
                max-height: 280px;
                background: #0f172a;
            }

            #addLessonWorksheetPreviewFrame,
            #addLessonMaterialPreviewFrame {
                min-height: 240px;
                background: #f1f5f9;
            }

            #addLessonWorksheetZone.dropzone,
            #addLessonMaterialZone.dropzone {
                min-height: 100px;
                border: 2px dashed #cbd5e1;
                background: #f8fafc;
            }
        </style>
    @endpush

    <div class=" py-4">
        @if (isset($action) && $action == 'content')
            <div class="alert alert-info">

                <i class="bi bi-info-circle"></i>
                <strong>Course Content Management</strong><br>
                Manage your course structure: add, edit, or organize sections and lessons below.


            </div>
        @endif
        <div class="row">
            <div class="col-lg-6">
                <!-- Course Details Form -->
                <!-- Sidebar -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Course</h4>
                    </div>

                    <div class="card-body">
                        <h6 class="mb-0">Course Status</h6>

                        <p><strong>Status:</strong> <span
                                class="badge bg-{{ $course->status == 'published' ? 'success' : 'warning' }}">{{ ucfirst($course->status) }}</span>
                        </p>
                        <div class="mb-2">
                            Approval Status : <span
                                class="badge bg-{{ $course->approval_status == 'approved' ? 'success' : ($course->approval_status == 'rejected' ? 'danger' : 'info') }}">{{ ucfirst($course->approval_status) }}</span>
                        </div>
                        <p><strong>Sections:</strong> {{ $course->sections->count() }}</p>
                        <p><strong>Total Lessons:</strong> {{ $course->lessons->count() }}</p>
                        <p><strong>Created:</strong> {{ $course->created_at->format('M d, Y') }}</p>
                        @if ($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="img-fluid rounded mt-2"
                                alt="Thumbnail">
                        @endif
                    </div>
                    <hr>

                    <div class="card-body">
                        <form action="{{ route('educator.courses.crud.update', $course->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <h5 class="border-bottom pb-2 mb-3">Basic Information</h5>
                            <div class="mb-3">
                                <label for="title" class="form-label">Course Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $course->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" required>{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="course_category_id" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('course_category_id') is-invalid @enderror"
                                        id="course_category_id" name="course_category_id" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('course_category_id', $course->course_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Subject <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                        id="subject" name="subject" value="{{ old('subject', $course->subject) }}"
                                        required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="level" class="form-label">Level</label>
                                    <input type="text" class="form-control" id="level" name="level"
                                        value="{{ old('level', $course->level) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="language" class="form-label">Language</label>
                                    <input type="text" class="form-control" id="language" name="language"
                                        value="{{ old('language', $course->language) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="difficulty" class="form-label">Difficulty</label>
                                    <select class="form-select" id="difficulty" name="difficulty">
                                        <option value="">Select</option>
                                        <option value="beginner"
                                            {{ old('difficulty', $course->difficulty) == 'beginner' ? 'selected' : '' }}>
                                            Beginner</option>
                                        <option value="intermediate"
                                            {{ old('difficulty', $course->difficulty) == 'intermediate' ? 'selected' : '' }}>
                                            Intermediate</option>
                                        <option value="advanced"
                                            {{ old('difficulty', $course->difficulty) == 'advanced' ? 'selected' : '' }}>
                                            Advanced</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Pricing</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price ($)</label>
                                    <input type="number" step="0.01" class="form-control" id="price"
                                        name="price" value="{{ old('price', $course->price) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_free"
                                            name="is_free" {{ old('is_free', $course->is_free) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_free">Free Course</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Course Type -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Course Details</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="type" class="form-label">Course Type</label>
                                    <select class="form-select" id="type" name="type">
                                        <option value="module"
                                            {{ old('type', $course->type) == 'module' ? 'selected' : '' }}>Module-based
                                        </option>
                                        <option value="video"
                                            {{ old('type', $course->type) == 'video' ? 'selected' : '' }}>Video Course
                                        </option>
                                        <option value="live"
                                            {{ old('type', $course->type) == 'live' ? 'selected' : '' }}>Live Course
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="duration" class="form-label">Duration</label>
                                    <input type="text" class="form-control" id="duration" name="duration"
                                        value="{{ old('duration', $course->duration) }}" placeholder="e.g., 8 weeks">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="schedule_date" class="form-label">Schedule Date</label>
                                    <input type="datetime-local" class="form-control" id="schedule_date"
                                        name="schedule_date"
                                        value="{{ old('schedule_date', $course->schedule_date ? date('Y-m-d\TH:i', strtotime($course->schedule_date)) : '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="thumbnail" class="form-label">Thumbnail</label>
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail"
                                        accept="image/*">
                                    @if ($course->thumbnail)
                                        <small class="text-muted">Current: {{ basename($course->thumbnail) }}</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Drip Content -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Content Release</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="drip"
                                            name="drip" {{ old('drip', $course->drip) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="drip">Enable Drip Content</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="drip_duration" class="form-label">Drip Duration</label>
                                    <input type="text" class="form-control" id="drip_duration"
                                        name="drip_duration"
                                        value="{{ old('drip_duration', $course->drip_duration) }}">
                                </div>
                            </div>

                            <!-- Publishing -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4">Publishing</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="publish_option" class="form-label">Publish Option</label>
                                    <select class="form-select" id="publish_option" name="publish_option">
                                        <option value="draft"
                                            {{ old('publish_option', $course->publish_option) == 'draft' ? 'selected' : '' }}>
                                            Draft</option>
                                        <option value="now"
                                            {{ old('publish_option', $course->publish_option) == 'now' ? 'selected' : '' }}>
                                            Publish Now</option>
                                        <option value="schedule"
                                            {{ old('publish_option', $course->publish_option) == 'schedule' ? 'selected' : '' }}>
                                            Schedule</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="publish_date_wrapper">
                                    <label for="publish_date" class="form-label">Publish Date</label>
                                    <input type="datetime-local" class="form-control" id="publish_date"
                                        name="publish_date"
                                        value="{{ old('publish_date', $course->publish_date ? date('Y-m-d\TH:i', strtotime($course->publish_date)) : '') }}">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <a href="{{ route('educator.courses.crud.show', $course) }}"
                                    class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update
                                    Course</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Sections & Lessons Management -->
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-nested"></i> Course Content</h5>
                        <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal"
                            data-bs-target="#addSectionModal">
                            <i class="bi bi-plus"></i> Add Section
                        </button>
                    </div>
                    <div class="card-body">
                        @forelse($course->sections as $section)
                            <div class="section-item mb-4 border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">
                                        <i class="bi bi-folder"></i> {{ $section->title }}
                                        <span class="badge bg-secondary">Order: {{ $section->order }}</span>
                                    </h6>
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="editSection({{ $section->id }}, '{{ $section->title }}', {{ $section->order }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success"
                                            onclick="addLesson({{ $section->id }})">
                                            <i class="bi bi-plus"></i> Lesson
                                        </button>
                                        <form action="{{ route('educator.courses.crud.sections.destroy', $section) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Delete this section and all its lessons?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Lessons in this section -->
                                <div class="ms-4" id="section-{{ $section->id }}-lessons">
                                    @forelse($section->lessons as $lesson)
                                        <div class="lesson-item border-start border-3 border-info ps-3 py-2 mb-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong>{{ $lesson->title }}</strong>
                                                    <div class="small text-muted">
                                                        @if ($lesson->duration)
                                                            <i class="bi bi-clock"></i> {{ $lesson->duration }} min
                                                        @endif
                                                        @if ($lesson->free)
                                                            <span class="badge bg-success">Free</span>
                                                        @endif
                                                        @if ($lesson->preview)
                                                            <span class="badge bg-info">Preview</span>
                                                        @endif
                                                        <span
                                                            class="badge bg-{{ $lesson->status == 'Published' ? 'success' : 'warning' }}">
                                                            {{ $lesson->status }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editLesson({{ $lesson->id }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form
                                                        action="{{ route('educator.courses.crud.lessons.destroy', $lesson) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Delete this lesson?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted small mb-0">No lessons yet</p>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center">No sections yet. Click "Add Section" to get started.</p>
                        @endforelse
                    </div>
                </div>
            </div>



        </div>
    </div>

    <!-- Add Section Modal -->
    <div class="modal fade" id="addSectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('educator.courses.crud.sections.store', $course) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Section Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Order</label>
                            <input type="number" class="form-control" name="order"
                                value="{{ $course->sections->count() + 1 }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Section Modal -->
    <div class="modal fade" id="editSectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editSectionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Section Title</label>
                            <input type="text" class="form-control" name="title" id="edit_section_title"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Order</label>
                            <input type="number" class="form-control" name="order" id="edit_section_order"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Lesson Modal -->

    <div class="modal fade" id="addLessonModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addLessonForm" method="POST" enctype="multipart/form-data">
                    <div id="lessonFormErrors" class="alert alert-danger d-none"></div>

                    @csrf

                    <div class="modal-header bg-light">
                        <h5 class="modal-title">
                            <i class="bi bi-play-btn-fill text-primary me-2"></i>
                            Add Lesson
                        </h5>
                        <button type="button" class="btn-close" id="addLessonModalCloseBtn"
                            data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Lesson Title -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-type me-1"></i>
                                Lesson Title
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="title"
                                placeholder="Enter lesson title" required>
                        </div>

                        <!-- Duration, Price, Status -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-clock me-1"></i> Duration (min)
                                </label>
                                <input type="number" class="form-control" name="duration" min="1"
                                    placeholder="10">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-currency-dollar me-1"></i> Price
                                </label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    id="lesson_price" name="price" placeholder="0.00">


                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="lesson_free" name="free">
                                    <label class="form-check-label fw-semibold" for="lesson_free">
                                        <i class="bi bi-unlock-fill text-success me-1"></i> Free Lesson
                                    </label>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-eye me-1"></i> Status
                                </label>
                                <select class="form-select" name="status" required>
                                    <option value="Draft">Draft</option>
                                    <option value="Published">Published</option>
                                </select>
                            </div>
                        </div>

                        <div class="py-2">
                            <input type="hidden" name="type" id="lesson_type" value="video">
                            <ul class="nav nav-tabs" id="uploadTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="video-tab" onclick="setLessonType('video')"
                                        data-bs-toggle="tab" data-bs-target="#videoUpload" type="button"
                                        role="tab">
                                        <i class="bi bi-camera-video me-1"></i> Video
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="worksheet-tab" onclick="setLessonType('worksheet')"
                                        data-bs-toggle="tab" data-bs-target="#worksheetUpload" type="button"
                                        role="tab">
                                        <i class="bi bi-file-earmark-text me-1"></i> Worksheet
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="material-tab" onclick="setLessonType('material')"
                                        data-bs-toggle="tab" data-bs-target="#materialUpload" type="button"
                                        role="tab">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Material
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content p-3 bg-light">
                                <div class="tab-pane fade show active" id="videoUpload" role="tabpanel"
                                    aria-labelledby="video-tab">
                                    <input type="hidden" name="video_temp_path" id="video_temp_path"
                                        value="">
                                    <div id="addLessonVideoZone" class="dropzone"></div>
                                    <div id="videoUploadLoader" class="small text-primary mt-2 d-none">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                        Uploading video…
                                    </div>
                                    <div id="videoTempPathDisplay" class="form-text small mt-2"></div>
                                    <div id="addLessonVideoPreviewWrap" class="mt-3 d-none">
                                        <label class="form-label small fw-semibold mb-1">Preview</label>
                                        <video id="addLessonVideoPreview" class="w-100 rounded border" controls
                                            playsinline preload="metadata"></video>
                                        <div class="form-text small mt-1">Plays from your file before upload finishes.
                                        </div>
                                    </div>
                                    <div class="form-text">
                                        MP4 up to 2 GB.
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="worksheetUpload" role="tabpanel"
                                    aria-labelledby="worksheet-tab">
                                    <input type="hidden" name="worksheet_storage_path" id="worksheet_storage_path"
                                        value="">
                                    <div id="addLessonWorksheetZone" class="dropzone"></div>
                                    <div id="worksheetUploadLoader" class="small text-primary mt-2 d-none">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                        Uploading…
                                    </div>
                                    <div id="worksheetPathDisplay" class="form-text small mt-2"></div>
                                    <div id="addLessonWorksheetPreviewWrap" class="mt-3 d-none">
                                        <label class="form-label small fw-semibold mb-1">Preview</label>
                                        <iframe id="addLessonWorksheetPreviewFrame" class="w-100 rounded border"
                                            title="Worksheet preview"></iframe>
                                        <div id="addLessonWorksheetNonPdfNote" class="form-text small mt-1 d-none">
                                            Word files can’t be previewed here; they are saved when upload completes.
                                        </div>
                                    </div>
                                    <div class="form-text">
                                        PDF/Word up to 10 MB. Files are saved under <code>public/storage</code> with a
                                        random name.
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="materialUpload" role="tabpanel"
                                    aria-labelledby="material-tab">
                                    <input type="hidden" name="material_storage_path" id="material_storage_path"
                                        value="">
                                    <div id="addLessonMaterialZone" class="dropzone"></div>
                                    <div id="materialUploadLoader" class="small text-primary mt-2 d-none">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                        Uploading…
                                    </div>
                                    <div id="materialPathDisplay" class="form-text small mt-2"></div>
                                    <div id="addLessonMaterialPreviewWrap" class="mt-3 d-none">
                                        <label class="form-label small fw-semibold mb-1">Preview</label>
                                        <iframe id="addLessonMaterialPreviewFrame" class="w-100 rounded border"
                                            title="Material preview"></iframe>
                                        <div id="addLessonMaterialNonPdfNote" class="form-text small mt-1 d-none">
                                            PowerPoint files can’t be previewed in the browser; open the link after
                                            upload.
                                        </div>
                                    </div>
                                    <div class="form-text">
                                        PDF/PPT up to 10 MB. Files are saved under <code>public/storage</code> with a
                                        random name.
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Notes -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-journal-text me-1"></i> Lesson Notes
                            </label>
                            <textarea class="form-control" name="notes" rows="3"
                                placeholder="Key points, chapters, instructions for students..."></textarea>
                        </div>

                        <!-- Toggles -->
                        <div class="row">


                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="lesson_preview"
                                        name="preview" value="1">
                                    <label class="form-check-label fw-semibold" for="lesson_preview">
                                        <i class="bi bi-eye-fill text-primary me-1"></i> Allow Preview
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" id="addLessonModalCancelBtn"
                            data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="lessonSubmitBtn">
                            <i class="bi bi-check-circle me-1"></i> Save Lesson
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="modal fade" id="editLessonModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content">

                <form id="editLessonForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div id="editLessonErrors" class="alert alert-danger d-none"></div>

                    <input type="hidden" id="edit_lesson_id">

                    <!-- Header -->
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil-square text-primary me-2"></i>
                            Edit Lesson
                        </h5>
                        <button type="button" class="btn-close" id="editLessonModalCloseBtn"
                            data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">

                        <!-- Lesson Title -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-type me-1"></i> Lesson Title
                            </label>
                            <input type="text" class="form-control" name="title" id="edit_title" required>
                        </div>

                        <!-- Duration / Price / Status -->
                        <div class="row g-3 mb-3">

                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-clock me-1"></i> Duration (min)
                                </label>
                                <input type="number" class="form-control" name="duration" id="edit_duration">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-currency-dollar me-1"></i> Price
                                </label>
                                <input type="number" class="form-control" name="price" id="edit_price">

                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" id="edit_free" name="free">
                                    <label class="form-check-label fw-semibold" for="edit_free">
                                        <i class="bi bi-unlock-fill text-success me-1"></i> Free Lesson
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-eye me-1"></i> Status
                                </label>
                                <select class="form-select" name="status" id="edit_status" required>
                                    <option value="Draft">Draft</option>
                                    <option value="Published">Published</option>
                                </select>
                            </div>
                        </div>

                        <!-- Content Type -->
                        <input type="hidden" name="type" id="edit_type">

                        <!-- Upload Tabs -->


                        <div class=" p-3 bg-light rounded" id="lessonTypeFileUpload">


                        </div>

                        <!-- Notes -->
                        <div class="mt-3">
                            <label class="form-label">
                                <i class="bi bi-journal-text me-1"></i> Lesson Notes
                            </label>
                            <textarea class="form-control" name="notes" id="edit_notes" rows="3"></textarea>
                        </div>

                        <!-- Preview -->
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="edit_preview" name="preview">
                            <label class="form-check-label fw-semibold" for="edit_preview">
                                <i class="bi bi-eye-fill text-primary me-1"></i> Allow Preview
                            </label>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" id="editLessonModalCancelBtn"
                            data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="editLessonSubmitBtn">
                            <i class="bi bi-save me-1"></i> Update Lesson
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <script>
            Dropzone.autoDiscover = false;

            const tempVideoUploadUrl = @json(route('educator.courses.crud.temp-video.upload'));
            const lessonAssetUploadUrl = (kind) =>
                @json(url('educator-panel/course-crud/upload-lesson-asset')) + '/' + kind;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                document.querySelector('input[name="_token"]')?.value;

            let addLessonVideoDropzone = null;
            let addLessonWorksheetDropzone = null;
            let addLessonMaterialDropzone = null;
            let editLessonVideoDropzone = null;
            let editLessonVideoUploading = false;
            let addLessonVideoObjectUrl = null;
            let editLessonVideoObjectUrl = null;
            let addLessonWorksheetBlobUrl = null;
            let addLessonMaterialBlobUrl = null;

            const addLessonUploadLock = {
                video: false,
                worksheet: false,
                material: false
            };

            function isAddLessonModalUploading() {
                return addLessonUploadLock.video || addLessonUploadLock.worksheet || addLessonUploadLock.material;
            }

            function setAddLessonUploadPart(which, on) {
                addLessonUploadLock[which] = !!on;
                refreshAddLessonModalUploadUi();
            }

            function refreshAddLessonModalUploadUi() {
                const locked = isAddLessonModalUploading();
                ['addLessonModalCancelBtn', 'addLessonModalCloseBtn', 'lessonSubmitBtn'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.disabled = locked;
                });
                document.querySelectorAll('#uploadTab .nav-link').forEach(el => {
                    el.classList.toggle('disabled', locked);
                    el.style.pointerEvents = locked ? 'none' : '';
                });
                const v = document.getElementById('videoUploadLoader');
                const w = document.getElementById('worksheetUploadLoader');
                const m = document.getElementById('materialUploadLoader');
                if (v) v.classList.toggle('d-none', !addLessonUploadLock.video);
                if (w) w.classList.toggle('d-none', !addLessonUploadLock.worksheet);
                if (m) m.classList.toggle('d-none', !addLessonUploadLock.material);
            }

            function clearAddVideoPreview() {
                if (addLessonVideoObjectUrl) {
                    URL.revokeObjectURL(addLessonVideoObjectUrl);
                    addLessonVideoObjectUrl = null;
                }
                const wrap = document.getElementById('addLessonVideoPreviewWrap');
                const vid = document.getElementById('addLessonVideoPreview');
                if (vid) {
                    vid.pause();
                    vid.removeAttribute('src');
                    vid.load();
                }
                if (wrap) wrap.classList.add('d-none');
            }

            function clearEditVideoPreview() {
                if (editLessonVideoObjectUrl) {
                    URL.revokeObjectURL(editLessonVideoObjectUrl);
                    editLessonVideoObjectUrl = null;
                }
                const wrap = document.getElementById('editLessonVideoPreviewWrap');
                const vid = document.getElementById('editLessonVideoPreview');
                if (vid) {
                    vid.pause();
                    vid.removeAttribute('src');
                    vid.load();
                }
                if (wrap) wrap.classList.add('d-none');
            }

            function showVideoPreviewFromDropzoneFile(file, which) {
                if (!file || !file.type || !file.type.startsWith('video/')) {
                    return;
                }
                const isAdd = which === 'add';
                if (isAdd) {
                    clearAddVideoPreview();
                } else {
                    clearEditVideoPreview();
                }
                const url = URL.createObjectURL(file);
                if (isAdd) {
                    addLessonVideoObjectUrl = url;
                } else {
                    editLessonVideoObjectUrl = url;
                }
                const wrap = document.getElementById(isAdd ? 'addLessonVideoPreviewWrap' :
                    'editLessonVideoPreviewWrap');
                const vid = document.getElementById(isAdd ? 'addLessonVideoPreview' : 'editLessonVideoPreview');
                if (!vid || !wrap) {
                    URL.revokeObjectURL(url);
                    if (isAdd) {
                        addLessonVideoObjectUrl = null;
                    } else {
                        editLessonVideoObjectUrl = null;
                    }
                    return;
                }
                vid.src = url;
                vid.load();
                wrap.classList.remove('d-none');
            }

            function clearAddWorksheetPreview() {
                if (addLessonWorksheetBlobUrl) {
                    URL.revokeObjectURL(addLessonWorksheetBlobUrl);
                    addLessonWorksheetBlobUrl = null;
                }
                const frame = document.getElementById('addLessonWorksheetPreviewFrame');
                const wrap = document.getElementById('addLessonWorksheetPreviewWrap');
                const note = document.getElementById('addLessonWorksheetNonPdfNote');
                if (frame) {
                    frame.removeAttribute('src');
                }
                if (wrap) wrap.classList.add('d-none');
                if (note) note.classList.add('d-none');
            }

            function clearAddMaterialPreview() {
                if (addLessonMaterialBlobUrl) {
                    URL.revokeObjectURL(addLessonMaterialBlobUrl);
                    addLessonMaterialBlobUrl = null;
                }
                const frame = document.getElementById('addLessonMaterialPreviewFrame');
                const wrap = document.getElementById('addLessonMaterialPreviewWrap');
                const note = document.getElementById('addLessonMaterialNonPdfNote');
                if (frame) {
                    frame.removeAttribute('src');
                }
                if (wrap) wrap.classList.add('d-none');
                if (note) note.classList.add('d-none');
            }

            function showWorksheetLocalPreview(file) {
                clearAddWorksheetPreview();
                const wrap = document.getElementById('addLessonWorksheetPreviewWrap');
                const frame = document.getElementById('addLessonWorksheetPreviewFrame');
                const note = document.getElementById('addLessonWorksheetNonPdfNote');
                if (!file || !wrap || !frame) return;
                wrap.classList.remove('d-none');
                if (file.type === 'application/pdf') {
                    if (note) note.classList.add('d-none');
                    const url = URL.createObjectURL(file);
                    addLessonWorksheetBlobUrl = url;
                    frame.src = url;
                } else {
                    if (note) note.classList.remove('d-none');
                    frame.removeAttribute('src');
                }
            }

            function showMaterialLocalPreview(file) {
                clearAddMaterialPreview();
                const wrap = document.getElementById('addLessonMaterialPreviewWrap');
                const frame = document.getElementById('addLessonMaterialPreviewFrame');
                const note = document.getElementById('addLessonMaterialNonPdfNote');
                if (!file || !wrap || !frame) return;
                wrap.classList.remove('d-none');
                if (file.type === 'application/pdf') {
                    if (note) note.classList.add('d-none');
                    const url = URL.createObjectURL(file);
                    addLessonMaterialBlobUrl = url;
                    frame.src = url;
                } else {
                    if (note) note.classList.remove('d-none');
                    frame.removeAttribute('src');
                }
            }

            function setEditLessonModalVideoUploading(on) {
                editLessonVideoUploading = !!on;
                ['editLessonModalCancelBtn', 'editLessonModalCloseBtn', 'editLessonSubmitBtn'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.disabled = !!on;
                });
                const loader = document.getElementById('editVideoUploadLoader');
                if (loader) loader.classList.toggle('d-none', !on);
            }

            function destroyAddLessonAllDropzones() {
                clearAddVideoPreview();
                clearAddWorksheetPreview();
                clearAddMaterialPreview();
                if (addLessonVideoDropzone) {
                    try {
                        addLessonVideoDropzone.destroy();
                    } catch (e) {}
                    addLessonVideoDropzone = null;
                }
                if (addLessonWorksheetDropzone) {
                    try {
                        addLessonWorksheetDropzone.destroy();
                    } catch (e) {}
                    addLessonWorksheetDropzone = null;
                }
                if (addLessonMaterialDropzone) {
                    try {
                        addLessonMaterialDropzone.destroy();
                    } catch (e) {}
                    addLessonMaterialDropzone = null;
                }
                const vh = document.getElementById('video_temp_path');
                const wh = document.getElementById('worksheet_storage_path');
                const mh = document.getElementById('material_storage_path');
                if (vh) vh.value = '';
                if (wh) wh.value = '';
                if (mh) mh.value = '';
                const vd = document.getElementById('videoTempPathDisplay');
                const wd = document.getElementById('worksheetPathDisplay');
                const md = document.getElementById('materialPathDisplay');
                if (vd) vd.textContent = '';
                if (wd) wd.textContent = '';
                if (md) md.textContent = '';
            }

            function initAddLessonAllDropzones() {
                if (typeof Dropzone === 'undefined') return;
                destroyAddLessonAllDropzones();

                const videoEl = document.getElementById('addLessonVideoZone');
                if (videoEl) {
                    addLessonVideoDropzone = new Dropzone(videoEl, {
                    url: tempVideoUploadUrl,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    paramName: 'file',
                    maxFiles: 1,
                    maxFilesize: 2048,
                    acceptedFiles: 'video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/webm,video/x-matroska',
                    chunking: true,
                    forceChunking: true,
                    chunkSize: 2000000,
                    addRemoveLinks: true,
                    dictDefaultMessage: 'Drop video here or click to upload (up to 2 GB)',
                    init: function() {
                        this.on('addedfile', file => {
                            showVideoPreviewFromDropzoneFile(file, 'add');
                        });
                        this.on('removedfile', () => clearAddVideoPreview());
                        this.on('maxfilesexceeded', file => {
                            this.removeAllFiles();
                            this.addFile(file);
                        });
                        this.on('sending', () => setAddLessonUploadPart('video', true));
                        this.on('uploadprogress', () => setAddLessonUploadPart('video', true));
                        this.on('success', (file, res) => {
                            setAddLessonUploadPart('video', false);
                            if (res && res.path) {
                                const h = document.getElementById('video_temp_path');
                                const d = document.getElementById('videoTempPathDisplay');
                                if (h) h.value = res.path;
                                if (d) {
                                    d.innerHTML =
                                        'Video is uploaded. It will be reviewed by us before publishing to the platform.';

                                }
                            }
                        });
                        this.on('error', (file, err, xhr) => {
                            setAddLessonUploadPart('video', false);
                            let msg = 'Upload failed';
                            if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (err && err.message) {
                                msg = err.message;
                            } else if (typeof err === 'string') {
                                msg = err;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Upload error',
                                text: msg
                            });
                        });
                        this.on('canceled', () => setAddLessonUploadPart('video', false));
                    }
                });
                }

                const wsEl = document.getElementById('addLessonWorksheetZone');
                if (wsEl) {
                    addLessonWorksheetDropzone = new Dropzone(wsEl, {
                        url: lessonAssetUploadUrl('worksheets'),
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        paramName: 'file',
                        maxFiles: 1,
                        maxFilesize: 50,
                        acceptedFiles: '.pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        addRemoveLinks: true,
                        dictDefaultMessage: 'Drop worksheet (PDF or Word) or click',
                        init: function() {
                            this.on('addedfile', file => showWorksheetLocalPreview(file));
                            this.on('removedfile', () => {
                                clearAddWorksheetPreview();
                                const h = document.getElementById('worksheet_storage_path');
                                const d = document.getElementById('worksheetPathDisplay');
                                if (h) h.value = '';
                                if (d) d.textContent = '';
                            });
                            this.on('maxfilesexceeded', file => {
                                this.removeAllFiles();
                                this.addFile(file);
                            });
                            this.on('sending', () => setAddLessonUploadPart('worksheet', true));
                            this.on('uploadprogress', () => setAddLessonUploadPart('worksheet', true));
                            this.on('success', (file, res) => {
                                setAddLessonUploadPart('worksheet', false);
                                if (res && res.path) {
                                    const h = document.getElementById('worksheet_storage_path');
                                    const d = document.getElementById('worksheetPathDisplay');
                                    if (h) h.value = res.path;
                                    if (d) {
                                        d.innerHTML = 'Saved: <code class="text-break">' + res.path +
                                            '</code>';
                                    }
                                    clearAddWorksheetPreview();
                                    const wrap = document.getElementById('addLessonWorksheetPreviewWrap');
                                    const frame = document.getElementById('addLessonWorksheetPreviewFrame');
                                    const note = document.getElementById('addLessonWorksheetNonPdfNote');
                                    if (res.url && /\.pdf($|\?)/i.test(res.url)) {
                                        if (note) note.classList.add('d-none');
                                        if (frame) frame.src = res.url;
                                        if (wrap) wrap.classList.remove('d-none');
                                    } else if (wrap) {
                                        if (note) note.classList.remove('d-none');
                                        if (frame) frame.removeAttribute('src');
                                        wrap.classList.remove('d-none');
                                    }
                                }
                            });
                            this.on('error', (file, err, xhr) => {
                                setAddLessonUploadPart('worksheet', false);
                                let msg = 'Upload failed';
                                if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Upload error',
                                    text: msg
                                });
                            });
                            this.on('canceled', () => setAddLessonUploadPart('worksheet', false));
                        }
                    });
                }

                const matEl = document.getElementById('addLessonMaterialZone');
                if (matEl) {
                    addLessonMaterialDropzone = new Dropzone(matEl, {
                        url: lessonAssetUploadUrl('materials'),
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        paramName: 'file',
                        maxFiles: 1,
                        maxFilesize: 50,
                        acceptedFiles: '.pdf,.ppt,.pptx,application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        addRemoveLinks: true,
                        dictDefaultMessage: 'Drop material (PDF or PowerPoint) or click',
                        init: function() {
                            this.on('addedfile', file => showMaterialLocalPreview(file));
                            this.on('removedfile', () => {
                                clearAddMaterialPreview();
                                const h = document.getElementById('material_storage_path');
                                const d = document.getElementById('materialPathDisplay');
                                if (h) h.value = '';
                                if (d) d.textContent = '';
                            });
                            this.on('maxfilesexceeded', file => {
                                this.removeAllFiles();
                                this.addFile(file);
                            });
                            this.on('sending', () => setAddLessonUploadPart('material', true));
                            this.on('uploadprogress', () => setAddLessonUploadPart('material', true));
                            this.on('success', (file, res) => {
                                setAddLessonUploadPart('material', false);
                                if (res && res.path) {
                                    const h = document.getElementById('material_storage_path');
                                    const d = document.getElementById('materialPathDisplay');
                                    if (h) h.value = res.path;
                                    if (d) {
                                        d.innerHTML = 'Saved: <code class="text-break">' + res.path +
                                            '</code>';
                                    }
                                    clearAddMaterialPreview();
                                    const wrap = document.getElementById('addLessonMaterialPreviewWrap');
                                    const frame = document.getElementById('addLessonMaterialPreviewFrame');
                                    const note = document.getElementById('addLessonMaterialNonPdfNote');
                                    if (res.url && /\.pdf($|\?)/i.test(res.url)) {
                                        if (note) note.classList.add('d-none');
                                        if (frame) frame.src = res.url;
                                        if (wrap) wrap.classList.remove('d-none');
                                    } else if (wrap) {
                                        if (note) note.classList.remove('d-none');
                                        if (frame) frame.removeAttribute('src');
                                        wrap.classList.remove('d-none');
                                    }
                                }
                            });
                            this.on('error', (file, err, xhr) => {
                                setAddLessonUploadPart('material', false);
                                let msg = 'Upload failed';
                                if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Upload error',
                                    text: msg
                                });
                            });
                            this.on('canceled', () => setAddLessonUploadPart('material', false));
                        }
                    });
                }
            }

            function destroyEditLessonDropzone() {
                clearEditVideoPreview();
                if (editLessonVideoDropzone) {
                    try {
                        editLessonVideoDropzone.destroy();
                    } catch (e) {}
                    editLessonVideoDropzone = null;
                }
            }

            function initEditLessonDropzone(lesson) {
                const el = document.getElementById('editLessonVideoZone');
                if (!el || typeof Dropzone === 'undefined') return;
                destroyEditLessonDropzone();
                const hidden = document.getElementById('edit_video_temp_path');
                if (hidden && lesson && lesson.video_temp_path) {
                    hidden.value = lesson.video_temp_path;
                }
                const disp = document.getElementById('editVideoTempPathDisplay');
                if (disp && lesson && lesson.video_temp_path) {
                    disp.innerHTML = 'Current file (relative): <code class="text-break">' + lesson
                        .video_temp_path + '</code>';
                }
                editLessonVideoDropzone = new Dropzone(el, {
                    url: tempVideoUploadUrl,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    paramName: 'file',
                    maxFiles: 1,
                    maxFilesize: 2048,
                    acceptedFiles: 'video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/webm,video/x-matroska',
                    chunking: true,
                    forceChunking: true,
                    chunkSize: 2000000,
                    addRemoveLinks: true,
                    dictDefaultMessage: 'Drop a new video here or click to replace',
                    init: function() {
                        this.on('addedfile', file => {
                            showVideoPreviewFromDropzoneFile(file, 'edit');
                        });
                        this.on('removedfile', () => clearEditVideoPreview());
                        this.on('maxfilesexceeded', file => {
                            this.removeAllFiles();
                            this.addFile(file);
                        });
                        this.on('sending', () => setEditLessonModalVideoUploading(true));
                        this.on('uploadprogress', () => setEditLessonModalVideoUploading(true));
                        this.on('success', (file, res) => {
                            setEditLessonModalVideoUploading(false);
                            if (res && res.path) {
                                const h = document.getElementById('edit_video_temp_path');
                                const d = document.getElementById('editVideoTempPathDisplay');
                                if (h) h.value = res.path;
                                if (d) {
                                    d.innerHTML =
                                        'Video is uploaded. It will be reviewed by us before publishing to the platform.';
                                }
                            }
                        });
                        this.on('error', (file, err, xhr) => {
                            setEditLessonModalVideoUploading(false);
                            let msg = 'Upload failed';
                            if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Upload error',
                                text: msg
                            });
                        });
                        this.on('canceled', () => setEditLessonModalVideoUploading(false));
                    }
                });
            }

            document.getElementById('addLessonModal').addEventListener('hide.bs.modal', function(e) {
                if (isAddLessonModalUploading()) {
                    e.preventDefault();
                }
            });
            document.getElementById('editLessonModal').addEventListener('hide.bs.modal', function(e) {
                if (editLessonVideoUploading) {
                    e.preventDefault();
                }
            });

            document.getElementById('addLessonModal').addEventListener('shown.bs.modal', function() {
                initAddLessonAllDropzones();
            });
            document.getElementById('addLessonModal').addEventListener('hidden.bs.modal', function() {
                destroyAddLessonAllDropzones();
                addLessonUploadLock.video = false;
                addLessonUploadLock.worksheet = false;
                addLessonUploadLock.material = false;
                refreshAddLessonModalUploadUi();
            });
            document.getElementById('editLessonModal').addEventListener('hidden.bs.modal', function() {
                destroyEditLessonDropzone();
                setEditLessonModalVideoUploading(false);
            });

            function setLessonType(type) {
                document.getElementById('lesson_type').value = type;
                if (type !== 'video') {
                    const h = document.getElementById('video_temp_path');
                    if (h) h.value = '';
                    if (addLessonVideoDropzone) {
                        addLessonVideoDropzone.removeAllFiles(true);
                    }
                    clearAddVideoPreview();
                    const d = document.getElementById('videoTempPathDisplay');
                    if (d) d.textContent = '';
                }
                if (type !== 'worksheet') {
                    const wh = document.getElementById('worksheet_storage_path');
                    if (wh) wh.value = '';
                    if (addLessonWorksheetDropzone) {
                        addLessonWorksheetDropzone.removeAllFiles(true);
                    }
                    clearAddWorksheetPreview();
                    const wd = document.getElementById('worksheetPathDisplay');
                    if (wd) wd.textContent = '';
                }
                if (type !== 'material') {
                    const mh = document.getElementById('material_storage_path');
                    if (mh) mh.value = '';
                    if (addLessonMaterialDropzone) {
                        addLessonMaterialDropzone.removeAllFiles(true);
                    }
                    clearAddMaterialPreview();
                    const md = document.getElementById('materialPathDisplay');
                    if (md) md.textContent = '';
                }
            }
        </script>
        <script>
            const freeCheckbox = document.getElementById('lesson_free');
            const priceInput = document.getElementById('lesson_price');

            freeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    priceInput.value = 0;
                    priceInput.setAttribute('disabled', true);
                } else {
                    priceInput.removeAttribute('disabled');
                }
            });
        </script>
        <script>
            function editSection(id, title, order) {
                document.getElementById('edit_section_title').value = title;
                document.getElementById('edit_section_order').value = order;
                document.getElementById('editSectionForm').action = `{{ url('educator-panel') }}/course-crud/sections/${id}`;
                new bootstrap.Modal(document.getElementById('editSectionModal')).show();
            }



            // Publish option handler
            document.getElementById('publish_option').addEventListener('change', function() {
                const wrapper = document.getElementById('publish_date_wrapper');
                wrapper.style.display = this.value === 'schedule' ? 'block' : 'none';
            });

            // Initialize
            if (document.getElementById('publish_option').value === 'schedule') {
                document.getElementById('publish_date_wrapper').style.display = 'block';
            }





            //course-management.js script
        </script>


        <script>
            // Course Management JavaScript

            // Auto-update price when free checkbox is toggled
            document.addEventListener('DOMContentLoaded', function() {
                const isFreeCheckbox = document.getElementById('is_free');
                const priceInput = document.getElementById('price');

                if (isFreeCheckbox && priceInput) {
                    isFreeCheckbox.addEventListener('change', function() {
                        if (this.checked) {
                            priceInput.value = 0;
                            priceInput.setAttribute('readonly', true);
                        } else {
                            priceInput.removeAttribute('readonly');
                        }
                    });
                }

                // Handle publish option changes
                const publishOption = document.getElementById('publish_option');
                const publishDateWrapper = document.getElementById('publish_date_wrapper');

                if (publishOption && publishDateWrapper) {
                    publishOption.addEventListener('change', function() {
                        if (this.value === 'schedule') {
                            publishDateWrapper.style.display = 'block';
                            document.getElementById('publish_date').setAttribute('required', true);
                        } else {
                            publishDateWrapper.style.display = 'none';
                            document.getElementById('publish_date').removeAttribute('required');
                        }
                    });

                    // Initialize on page load
                    if (publishOption.value === 'schedule') {
                        publishDateWrapper.style.display = 'block';
                    }
                }

                // Handle drip content toggle
                const dripCheckbox = document.getElementById('drip');
                const dripDurationInput = document.getElementById('drip_duration');

                if (dripCheckbox && dripDurationInput) {
                    dripCheckbox.addEventListener('change', function() {
                        if (this.checked) {
                            dripDurationInput.parentElement.style.display = 'block';
                        } else {
                            dripDurationInput.parentElement.style.display = 'none';
                        }
                    });

                    // Initialize
                    if (!dripCheckbox.checked) {
                        dripDurationInput.parentElement.style.display = 'none';
                    }
                }
            });

            // Section Management Functions
            function editSection(id, title, order) {
                document.getElementById('edit_section_title').value = title;
                document.getElementById('edit_section_order').value = order;
                document.getElementById('editSectionForm').action = `{{ url('educator-panel') }}/course-crud/sections/${id}`;

                const modal = new bootstrap.Modal(document.getElementById('editSectionModal'));
                modal.show();
            }

            function deleteSection(id) {
                if (confirm(
                        'Are you sure you want to delete this section? All lessons in this section will also be deleted.')) {
                    document.getElementById(`delete-section-form-${id}`).submit();
                }
            }

            // Lesson Management Functions
            const lessonEditPayloadUrl = (lessonId) =>
                `{{ url('educator-panel/course-crud/lessons') }}/${lessonId}/edit-payload`;

            function addLesson(sectionId) {
                document.getElementById('addLessonForm').action =
                    `{{ url('educator-panel') }}/course-crud/sections/${sectionId}/lessons`;
                document.getElementById('addLessonForm').reset();

                const modal = new bootstrap.Modal(document.getElementById('addLessonModal'));
                modal.show();
            }

            function editLesson(lessonId) {
                destroyEditLessonDropzone();

                const modalEl = document.getElementById('editLessonModal');
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

                fetch(lessonEditPayloadUrl(lessonId), {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(async (response) => {
                        if (!response.ok) {
                            const err = await response.json().catch(() => ({}));
                            throw new Error(err.message || 'Could not load lesson');
                        }
                        return response.json();
                    })
                    .then((data) => {
                        populateEditLessonModal(data.lesson);
                        modal.show();
                    })
                    .catch((e) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: e.message || 'Failed to load lesson details.'
                        });
                    });
            }

            function populateEditLessonModal(lesson) {
                const form = document.getElementById('editLessonForm');

                form.action = `{{ url('educator-panel') }}/course-crud/lessons/${lesson.id}`;
                document.getElementById('edit_lesson_id').value = lesson.id;

                document.getElementById('edit_title').value = lesson.title;
                document.getElementById('edit_duration').value = lesson.duration ?? '';
                document.getElementById('edit_price').value = lesson.price ?? 0;
                document.getElementById('edit_status').value = lesson.status;
                document.getElementById('edit_notes').value = lesson.notes ?? '';

                document.getElementById('edit_type').value = lesson.type;

                document.getElementById('edit_free').checked = !!lesson.free;
                document.getElementById('edit_preview').checked = !!lesson.preview;

                const editLessonFileType = document.getElementById('lessonTypeFileUpload');

                switch (lesson.type) {
                    case 'video': {
                        let vimeoBlock = '';
                        if (lesson.video_path && String(lesson.video_path).includes('vimeo.com')) {
                            vimeoBlock =
                                `<div class="alert alert-success py-2 small mb-2">On Vimeo: <a href="${lesson.video_path}" target="_blank" rel="noopener">Open player link</a></div>`;
                        }
                        let tempBlock = '';
                        if (lesson.video_temp_path) {
                            tempBlock =
                                `<div class="alert alert-warning py-2 small mb-2">Pending admin approval for Vimeo. Temp: <code class="small">${lesson.video_temp_path}</code></div>`;
                        }
                        editLessonFileType.innerHTML = vimeoBlock + tempBlock +
                            `<p class="small text-muted mb-2">Upload a replacement file below. Vimeo is updated only after an admin approves.</p>
                            <input type="hidden" name="video_temp_path" id="edit_video_temp_path" value="">
                            <div id="editLessonVideoZone" class="dropzone"></div>
                            <div id="editVideoUploadLoader" class="small text-primary mt-2 d-none">
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span> Uploading video…
                            </div>
                            <div id="editVideoTempPathDisplay" class="form-text small mt-2"></div>
                            <div id="editLessonVideoPreviewWrap" class="mt-3 d-none">
                                <label class="form-label small fw-semibold mb-1">Preview</label>
                                <video id="editLessonVideoPreview" class="w-100 rounded border" controls
                                    playsinline preload="metadata"></video>
                                <div class="form-text small mt-1">Plays from your file before upload finishes.</div>
                            </div>`;
                        setTimeout(() => initEditLessonDropzone(lesson), 50);
                        break;
                    }
                    case 'worksheet':
                        editLessonFileType.innerHTML = `<div class=" " id="editWorksheet">
                            <input type="file" class="form-control" name="worksheets" accept=".pdf,.doc,.docx">
                        </div>`;
                        if (lesson.worksheets_path) {
                            editLessonFileType.innerHTML +=
                                `<a href="${lesson.worksheets_path}" target="_blank" rel="noopener" class="btn btn-primary btn-sm mt-2">Preview uploaded worksheet</a>`;
                        }
                        break;
                    case 'material':
                        editLessonFileType.innerHTML = `<div class=" " id="editMaterial">
                            <input type="file" class="form-control" name="materials" accept=".pdf,.ppt,.pptx">
                        </div>`;
                        if (lesson.materials_path) {
                            editLessonFileType.innerHTML +=
                                `<a href="${lesson.materials_path}" target="_blank" rel="noopener" class="btn btn-primary btn-sm mt-2">Preview uploaded material</a>`;
                        }
                        break;


                    default:
                        editLessonFileType.innerHTML = '';

                }

                toggleEditPrice();
            }

            function toggleEditPrice() {
                const price = document.getElementById('edit_price');
                const free = document.getElementById('edit_free');

                if (free.checked) {
                    price.value = 0;
                    price.disabled = true;
                } else {
                    price.disabled = false;
                }
            }

            document.getElementById('edit_free').addEventListener('change', toggleEditPrice);



            function deleteLesson(id) {
                if (confirm('Are you sure you want to delete this lesson?')) {
                    document.getElementById(`delete-lesson-form-${id}`).submit();
                }
            }

            // Image preview for thumbnail upload
            const thumbnailInput = document.getElementById('thumbnail');
            if (thumbnailInput) {
                thumbnailInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            // Create or update preview image
                            let preview = document.getElementById('thumbnail-preview');
                            if (!preview) {
                                preview = document.createElement('img');
                                preview.id = 'thumbnail-preview';
                                preview.className = 'img-thumbnail mt-2';
                                preview.style.maxHeight = '200px';
                                thumbnailInput.parentElement.appendChild(preview);
                            }
                            preview.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Form validation before submit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('is-invalid');
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        alert('Please fill in all required fields');
                    }
                });
            });

            // Auto-save draft functionality (optional)
            let autoSaveTimer;

            function enableAutoSave(formId) {
                const form = document.getElementById(formId);
                if (!form) return;

                const inputs = form.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        clearTimeout(autoSaveTimer);
                        autoSaveTimer = setTimeout(() => {
                            saveDraft(form);
                        }, 3000); // Auto-save after 3 seconds of inactivity
                    });
                });
            }

            function saveDraft(form) {
                const formData = new FormData(form);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                // Save to localStorage
                localStorage.setItem('course_draft', JSON.stringify(data));

                // Show notification
                showNotification('Draft saved', 'success');
            }

            function loadDraft() {
                const draft = localStorage.getItem('course_draft');
                if (draft && confirm('Load previously saved draft?')) {
                    const data = JSON.parse(draft);
                    Object.keys(data).forEach(key => {
                        const field = document.querySelector(`[name="${key}"]`);
                        if (field) {
                            if (field.type === 'checkbox') {
                                field.checked = data[key] === 'on';
                            } else {
                                field.value = data[key];
                            }
                        }
                    });
                }
            }

            function showNotification(message, type = 'info') {
                // Create toast notification
                const toast = document.createElement('div');
                toast.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3`;
                toast.style.zIndex = '9999';
                toast.textContent = message;
                toast.style.minWidth = '250px';

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        </script>


        <script>
            document.getElementById('addLessonForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const submitBtn = document.getElementById('lessonSubmitBtn');
                const errorBox = document.getElementById('lessonFormErrors');

                // Reset UI
                errorBox.classList.add('d-none');
                errorBox.innerHTML = '';
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Saving...`;

                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async response => {
                        if (!response.ok) {
                            const data = await response.json();
                            throw data;
                        }
                        return response.json();
                    })
                    .then(data => {
                        //   Success
                        form.reset();

                        // Close modal
                        const modalEl = document.getElementById('addLessonModal'); // <-- modal ID
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();

                        // Optional: refresh lessons list / Livewire emit / reload section
                        // location.reload();

                        appendLessonCard(data.lesson);

                        showLessonSuccess('Lesson added successfully!');
                    })
                    .catch(error => {
                        //   Validation errors (422)
                        if (error.errors) {
                            let messages = '<ul class="mb-0">';
                            Object.values(error.errors).forEach(errArr => {
                                errArr.forEach(msg => {
                                    messages += `<li>${msg}</li>`;
                                });
                            });
                            messages += '</ul>';

                            errorBox.innerHTML = messages;
                            errorBox.classList.remove('d-none');
                        } else {
                            errorBox.innerHTML = 'Something went wrong. Please try again.';
                            errorBox.classList.remove('d-none');
                        }
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = `<i class="bi bi-check-circle me-1"></i> Save Lesson`;
                    });
            });

            document.getElementById('editLessonForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const submitBtn = document.getElementById('editLessonSubmitBtn');
                const errorBox = document.getElementById('editLessonErrors');

                errorBox.classList.add('d-none');
                errorBox.innerHTML = '';
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    `<span class="spinner-border spinner-border-sm me-1"></span> Updating...`;

                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(async response => {
                        if (!response.ok) {
                            const data = await response.json();
                            throw data;
                        }
                        return response.json();
                    })
                    .then(data => {
                        const modalEl = document.getElementById('editLessonModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                        showLessonSuccess(data.message || 'Lesson updated successfully!');
                    })
                    .catch(error => {
                        const errs = error.errors || error.error;
                        if (errs) {
                            let messages = '<ul class="mb-0">';
                            Object.values(errs).forEach(errArr => {
                                const arr = Array.isArray(errArr) ? errArr : [errArr];
                                arr.forEach(msg => {
                                    messages += `<li>${msg}</li>`;
                                });
                            });
                            messages += '</ul>';
                            errorBox.innerHTML = messages;
                            errorBox.classList.remove('d-none');
                        } else {
                            errorBox.innerHTML = error.message || 'Something went wrong. Please try again.';
                            errorBox.classList.remove('d-none');
                        }
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = `<i class="bi bi-save me-1"></i> Update Lesson`;
                    });
            });


            function showLessonSuccess(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: message
                });
            }



            function appendLessonCard(lesson) {
                const container = document.getElementById(`section-${lesson.section_id}-lessons`);
                if (!container) return;

                let badges = '';

                if (lesson.free) {
                    badges += `<span class="badge bg-success ms-1">Free</span>`;
                }
                if (lesson.preview) {
                    badges += `<span class="badge bg-info ms-1">Preview</span>`;
                }

                const statusBadge =
                    lesson.status === 'published' ?
                    'success' :
                    'warning';

                const html = `
                        <div class="lesson-item border-start border-3 border-info ps-3 py-2 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>${lesson.title}</strong>
                                    <div class="small text-muted">
                                        ${lesson.duration ? `<i class="bi bi-clock"></i> ${lesson.duration} min` : ''}
                                        ${badges}
                                        <span class="badge bg-${statusBadge}">${lesson.status}</span>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="editLesson(${lesson.id})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="${lesson.destroy_url}" method="POST" class="d-inline">
                                        <input type="hidden" name="_token" value="${document.querySelector('input[name=_token]').value}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this lesson?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `;

                container.insertAdjacentHTML('beforeend', html);
            }
        </script>
    @endpush
</x-educator-layout>
