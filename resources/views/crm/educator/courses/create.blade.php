<x-educator-layout>
    <div>
        <!-- Header -->
        <header class="header py-2">
            <div class="container-fluid px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <a href="edcademy-landing.html" class="text-decoration-none brand"><i
                            class="bi bi-mortarboard-fill me-2"></i>Ed‑Cademy</a>
                    <span class="text-muted d-none d-md-inline">Create Course</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-primary" id="btnExport">
                        <i class="bi bi-download me-1"></i> Export JSON
                    </button>
                    <label class="btn btn-sm btn-outline-primary mb-0" for="importJson"><i
                            class="bi bi-upload me-1"></i>
                        Import</label>
                    <input type="file" id="importJson" accept="application/json" hidden />
                    <a class="btn btn-sm btn-outline-primary" href="edcademy-educator-dashboard.html"><i
                            class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                </div>
            </div>
        </header>

        <div class="container-fluid py-3">
            <div class="row g-3">
                <!-- Builder (left) -->
                <div class="col-lg-8">
                    <!-- Course Details -->
                    <div class="card p-3 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h5 class="mb-0">Course Details</h5>
                            <span class="pill" style="background: var(--light-cyan); color: var(--dark-cyan)"><i
                                    class="bi bi-info-circle me-1"></i> Complete to enable
                                Publish</span>
                        </div>
                        <form action="{{ route('educator.courses.store') }}" method="POST"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf

                            <div class="col-12 col-md-8">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="e.g., Calculus I — Limits & Derivatives" required />
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-md-4">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" value="{{ old('subject') }}"
                                    class="form-control @error('subject') is-invalid @enderror"
                                    placeholder="Math, Physics" required />
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-md-4">
                                <label class="form-label">Level</label>
                                <select name="level" class="form-select @error('level') is-invalid @enderror">
                                    <option value="School" {{ old('level') == 'School' ? 'selected' : '' }}>School
                                    </option>
                                    <option value="High School" {{ old('level') == 'High School' ? 'selected' : '' }}>
                                        High School</option>
                                    <option value="University" {{ old('level') == 'University' ? 'selected' : '' }}>
                                        University</option>
                                    <option value="Professional" {{ old('level') == 'Professional' ? 'selected' : '' }}>
                                        Professional</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-md-4">
                                <label class="form-label">Language</label>
                                <select name="language" class="form-select @error('language') is-invalid @enderror">
                                    <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>
                                        English</option>
                                    <option value="Arabic" {{ old('language') == 'Arabic' ? 'selected' : '' }}>Arabic
                                    </option>
                                    <option value="Urdu" {{ old('language') == 'Urdu' ? 'selected' : '' }}>Urdu
                                    </option>
                                    <option value="French" {{ old('language') == 'French' ? 'selected' : '' }}>French
                                    </option>
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-md-4">
                                <label class="form-label">Price (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="price" value="{{ old('price') }}" min="0"
                                        step="0.01" class="form-control @error('price') is-invalid @enderror"
                                        placeholder="49.00" />
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <div class="form-check mt-2 d-none">
                                    <input class="form-check-input" type="checkbox" name="free" id="freeCourse"
                                        value="1" {{ old('free') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="freeCourse">Free course</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                        Publish
                                        immediately</option>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>
                                        Schedule publish</option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft only
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6" id="scheduleWrap"
                                style="{{ old('status') == 'schedule' ? '' : 'display: none' }}">
                                <label class="form-label">Publish date</label>
                                <input type="date" name="schedule_date" value="{{ old('schedule_date') }}"
                                    class="form-control @error('schedule_date') is-invalid @enderror" />
                                @error('schedule_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Thumbnail</label>
                                <input type="file" name="thumbnail"
                                    class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*" />
                                @error('thumbnail')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Tags (comma-separated)</label>
                                <input type="text" name="tags" value="{{ old('tags') }}"
                                    class="form-control @error('tags') is-invalid @enderror"
                                    placeholder="algebra, exam prep, STEM" />
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                                    placeholder="What students will learn, prerequisites, outcomes..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-none">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="drip" id="drip"
                                        value="1" {{ old('drip') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="drip">Enable drip status by
                                        lesson</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save Course</button>
                            </div>
                        </form>

                    </div>
                    <div
                        @if (!isset($course)) style="opacity: 0.5; pointer-events: none;"
                        title="Save Course and add Lesson Details for each of the course sections." @endif>
                        <!-- Lesson Builder -->
                        <div class="card p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h5 class="mb-0">Lessons & Content</h5>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" id="btnAddSection">
                                        <i class="bi bi-layout-split me-1"></i> Add Section
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" id="btnExpandAll">
                                        <i class="bi bi-arrows-expand me-1"></i> Expand
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" id="btnCollapseAll">
                                        <i class="bi bi-arrows-collapse me-1"></i> Collapse
                                    </button>
                                </div>
                            </div>

                            <div class="accordion" id="sectionsAccordion"></div>

                            <div class="mt-3 small text-muted">
                                <i class="bi bi-shield-check me-1"></i>Video uploads are
                                fingerprinted for IP protection. Captions are auto‑generated.
                            </div>
                        </div>

                        <!-- Actions (bottom) -->
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <button class="btn btn-outline-primary" id="btnSaveDraft">
                                <i class="bi bi-save me-1"></i> Save Draft
                            </button>
                            <button class="btn btn-primary" id="btnPublish" disabled>
                                <i class="bi bi-rocket-takeoff me-1"></i> Publish Course
                            </button>
                            <button class="btn btn-light ms-auto" id="btnPreview">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Preview Listing
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary (right) -->
                <div class="col-lg-4">
                    <div class="sticky-col">
                        <div class="card p-3 mb-3">
                            <h6 class="mb-2">Course Summary</h6>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div id="thumbPreview" class="rounded border"
                                    style="
                    width: 72px;
                    height: 48px;
                    background: #fff
                      url('')
                      center/cover no-repeat;
                  ">
                                </div>
                                <div>
                                    <div class="fw-semibold" id="sumTitle">Untitled course</div>
                                    <div class="small text-muted" id="sumSubject">—</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="summary-key">Price</span>

                                <strong id="sumPrice">$0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="summary-key">Lessons</span><strong id="sumLessons">0</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="summary-key">Total duration</span><strong id="sumDuration">0m</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="summary-key">Release</span><strong id="sumRelease">Draft</strong>
                            </div>
                        </div>

                        <div class="card p-3 mb-3">
                            <h6 class="mb-2">Checklist</h6>
                            <div class="form-check small">
                                <input class="form-check-input" type="checkbox" id="chkTitle" disabled />
                                <label class="form-check-label" for="chkTitle">Title & Subject</label>
                            </div>
                            <div class="form-check small">
                                <input class="form-check-input" type="checkbox" id="chkDesc" disabled />
                                <label class="form-check-label" for="chkDesc">Description</label>
                            </div>
                            <div class="form-check small">
                                <input class="form-check-input" type="checkbox" id="chkThumb" disabled />
                                <label class="form-check-label" for="chkThumb">Thumbnail</label>
                            </div>
                            <div class="form-check small">
                                <input class="form-check-input" type="checkbox" id="chkLesson" disabled />
                                <label class="form-check-label" for="chkLesson">≥ 1 published lesson</label>
                            </div>
                            <hr />
                            <div class="d-flex justify-content-between">
                                <span class="summary-key">Ready to publish</span><strong id="sumReady"
                                    class="text-danger">No</strong>
                            </div>
                        </div>

                        <div class="card p-3">
                            <h6 class="mb-2">Tips</h6>
                            <ul class="small mb-0">
                                <li>Keep lessons 6–12 minutes for better retention.</li>
                                <li>Use "+ Free preview" on 1 lesson.</li>
                                <li>Add tags so students can discover your course.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Lesson Modal -->
        <div class="modal fade" id="lessonModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-film me-2"></i>Lesson</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="lessonForm" class="row g-3">
                            <input type="hidden" id="lfSectionId" />
                            <input type="hidden" id="lfLessonId" />

                            <!-- Title -->
                            <div class="col-12">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input id="lfTitle" class="form-control"
                                    placeholder="e.g., Limits — Concept & Notation" required />
                            </div>

                            <!-- Tabs for Content Type -->
                            <div class="col-12">
                                <ul class="nav nav-tabs" id="lessonTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="video-tab" data-bs-toggle="tab"
                                            data-bs-target="#videoContent" type="button" role="tab">
                                            <i class="bi bi-camera-video me-1"></i> Video Content
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="materials-tab" data-bs-toggle="tab"
                                            data-bs-target="#materialsContent" type="button" role="tab">
                                            <i class="bi bi-file-earmark-ppt me-1"></i> Learning
                                            Materials
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="worksheets-tab" data-bs-toggle="tab"
                                            data-bs-target="#worksheetsContent" type="button" role="tab">
                                            <i class="bi bi-file-earmark-text me-1"></i> Worksheets
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content border border-top-0 p-3 rounded-bottom bg-light">
                                    <!-- Video Content -->
                                    <div class="tab-pane fade show active" id="videoContent" role="tabpanel"
                                        aria-labelledby="video-tab">
                                        <label class="form-label">Video</label>
                                        <input id="lfVideo" type="file" class="form-control mb-2"
                                            accept="video/*" />
                                        <input id="lfVideoLink" type="url" class="form-control mb-2"
                                            placeholder="Or paste a video link (YouTube, Vimeo)" />
                                        <div class="form-text">
                                            MP4/MOV up to 2 GB, or provide an external link.
                                        </div>
                                        <div id="videoPreview" class="border rounded mt-2 p-3 text-center bg-white">
                                            <i class="bi bi-camera-video fs-1 text-muted"></i>
                                            <p class="mb-0 small text-muted">No video selected</p>
                                        </div>
                                    </div>

                                    <!-- Learning Materials -->
                                    <div class="tab-pane fade" id="materialsContent" role="tabpanel"
                                        aria-labelledby="materials-tab">
                                        <label class="form-label">Upload Learning Materials (PDF, PPT)</label>
                                        <input id="lfMaterials" type="file" class="form-control"
                                            accept=".pdf,.ppt,.pptx" multiple />
                                        <div id="lfMatList" class="row g-2 mt-2">
                                            <div class="col-12 text-center text-muted small">
                                                <i class="bi bi-file-earmark-ppt fs-4"></i><br />No
                                                materials uploaded
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Worksheets -->
                                    <div class="tab-pane fade" id="worksheetsContent" role="tabpanel"
                                        aria-labelledby="worksheets-tab">
                                        <label class="form-label">Upload Worksheets (PDF, Word)</label>
                                        <input id="lfWorksheets" type="file" class="form-control"
                                            accept=".pdf,.doc,.docx" multiple />
                                        <div id="lfWsList" class="row g-2 mt-2">
                                            <div class="col-12 text-center text-muted small">
                                                <i class="bi bi-file-earmark-text fs-4"></i><br />No
                                                worksheets uploaded
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Duration, Price, Status -->
                            <div class="col-6 col-md-4">
                                <label class="form-label">Duration (min) <span class="text-danger">*</span></label>
                                <input id="lfDuration" type="number" min="1" step="1"
                                    class="form-control" placeholder="8" required />
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label">Price (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input id="price" type="number" min="0" step="0.01"
                                        class="form-control" placeholder="49.00" />
                                </div>
                                <div class="form-check mt-2 d-none">
                                    <input class="form-check-input" type="checkbox" id="freeCourse" />
                                    <label class="form-check-label" for="freeCourse">Free course</label>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label">Status</label>
                                <select id="lfStatus" class="form-select">
                                    <option value="Draft">Draft</option>
                                    <option value="Published">Published</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="lfPreview" />
                                    <label class="form-check-label" for="lfPreview">Free preview</label>
                                </div>
                            </div>

                            <!-- Resources -->
                            <div class="col-12">
                                <label class="form-label">Additional Resources (links)</label>
                                <div class="input-group">
                                    <input id="lfResourceUrl" class="form-control"
                                        placeholder="Paste a link (extra PDF, sheet, site)" />
                                    <button class="btn btn-outline-primary" type="button" id="btnAddRes">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                <div id="lfResList" class="small mt-2 text-muted">
                                    No links added
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-12">
                                <label class="form-label">Notes (optional)</label>
                                <textarea id="lfNotes" class="form-control" rows="2" placeholder="Key points, chapters, timecodes..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button class="btn btn-primary" id="btnSaveLesson">
                            <i class="bi bi-check2-circle me-1"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast container -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
            <div id="toast" class="toast text-bg-success border-0" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="toastBody">Saved</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $("#price").on("input", function() {

                let value = parseFloat(this.value) || 0;

                if (value < 0) {
                    value = 0;
                    this.value = 0;
                }

                $("#sumPrice").html("$" + value.toFixed(2));
            });
        </script>
    @endpush
</x-educator-layout>
