<x-educator-layout>
    <div>
        <!-- Header -->
        <header class=" py-2">
            <div class="container-fluid px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <a href="edcademy-landing.html" class="text-decoration-none brand"><i
                            class="bi bi-mortarboard-fill me-2"></i>Ed‑Cademy</a>
                    <span class="text-muted d-none d-md-inline">Update Course</span>
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
                        <form action="{{ route('educator.courses.update', $course->id) }}" method="POST"
                            enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')

                            <div class="col-12 col-md-8">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title"
                                    value="{{ old('title', $course->title) }}"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="e.g., Calculus I — Limits & Derivatives" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-md-4">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" value="{{ old('subject', $course->subject) }}"
                                    class="form-control @error('subject') is-invalid @enderror"
                                    placeholder="Math, Physics" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-md-4">
                                <label class="form-label">Level</label>
                                <select name="level" class="form-select @error('level') is-invalid @enderror">
                                    <option value="School"
                                        {{ old('level', $course->level) == 'School' ? 'selected' : '' }}>School</option>
                                    <option value="High School"
                                        {{ old('level', $course->level) == 'High School' ? 'selected' : '' }}>High
                                        School</option>
                                    <option value="University"
                                        {{ old('level', $course->level) == 'University' ? 'selected' : '' }}>University
                                    </option>
                                    <option value="Professional"
                                        {{ old('level', $course->level) == 'Professional' ? 'selected' : '' }}>
                                        Professional</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6 col-md-4">
                                <label class="form-label">Language</label>
                                <select name="language" class="form-select @error('language') is-invalid @enderror">
                                    <option value="English"
                                        {{ old('language', $course->language) == 'English' ? 'selected' : '' }}>English
                                    </option>
                                    <option value="Arabic"
                                        {{ old('language', $course->language) == 'Arabic' ? 'selected' : '' }}>Arabic
                                    </option>
                                    <option value="Urdu"
                                        {{ old('language', $course->language) == 'Urdu' ? 'selected' : '' }}>Urdu
                                    </option>
                                    <option value="French"
                                        {{ old('language', $course->language) == 'French' ? 'selected' : '' }}>French
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
                                    <input type="number" name="price" value="{{ old('price', $course->price) }}"
                                        min="0" step="0.01"
                                        class="form-control @error('price') is-invalid @enderror" placeholder="49.00">
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="free" id="freeCourse"
                                        value="1" {{ old('free', $course->free) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="freeCourse">Free course</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Release</label>
                                <select name="release" class="form-select @error('release') is-invalid @enderror">
                                    <option value="publish"
                                        {{ old('release', $course->release) == 'publish' ? 'selected' : '' }}>Publish
                                        immediately</option>
                                    <option value="schedule"
                                        {{ old('release', $course->release) == 'schedule' ? 'selected' : '' }}>Schedule
                                        publish</option>
                                    <option value="draft"
                                        {{ old('release', $course->release) == 'draft' ? 'selected' : '' }}>Draft only
                                    </option>
                                </select>
                                @error('release')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6" id="scheduleWrap"
                                style="{{ old('release', $course->release) == 'schedule' ? '' : 'display: none' }}">
                                <label class="form-label">Publish date</label>
                                <input type="date" name="schedule_date"
                                    value="{{ old('schedule_date', $course->schedule_date) }}"
                                    class="form-control @error('schedule_date') is-invalid @enderror">
                                @error('schedule_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Thumbnail</label>
                                <input type="file" name="thumbnail"
                                    class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                                @error('thumbnail')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                @if ($course->thumbnail)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumbnail"
                                            class="img-thumbnail" width="120">
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Tags (comma-separated)</label>
                                <input type="text" name="tags" value="{{ old('tags', $course->tags) }}"
                                    class="form-control @error('tags') is-invalid @enderror"
                                    placeholder="algebra, exam prep, STEM">
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                                    placeholder="What students will learn, prerequisites, outcomes..." required>{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="drip" id="drip"
                                        value="1" {{ old('drip', $course->drip) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="drip">Enable drip release by
                                        lesson</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update Course</button>
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
                            <button class="btn btn-primary" id="btnPublish">
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
                                <span class="summary-key">Price</span><strong id="sumPrice">$0.00</strong>
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
                                <li>Use "+ Free preview" on 1–2 lessons.</li>
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
                        <h5 class="modal-title"><i class="bi bi-film me-2"></i>Add Lesson</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="lessonForm" class="row g-3">
                            <input type="hidden" id="sectionId" />
                            <input type="hidden" id="lessonType" value="video" />
                            <input type="hidden" id="courseId" value="{{ $course->id }}" />

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
                                        <button onclick="setLessonType('video')" class="nav-link active"
                                            id="video-tab" data-bs-toggle="tab" data-bs-target="#videoContent"
                                            type="button" role="tab">
                                            <i class="bi bi-camera-video me-1"></i> Video Content
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button onclick="setLessonType('material')" class="nav-link"
                                            id="materials-tab" data-bs-toggle="tab"
                                            data-bs-target="#materialsContent" type="button" role="tab">
                                            <i class="bi bi-file-earmark-ppt me-1"></i> Learning
                                            Materials
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button onclick="setLessonType('worksheet')" class="nav-link"
                                            id="worksheets-tab" data-bs-toggle="tab"
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
                                <div class="form-check mt-2">
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
                        <button class="btn btn-primary" onclick="saveLesson()" id="btnSaveLesson">
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
            jQuery(function($) {
                // safe to use $ here
                fetchSections();

            });
            // ------- State Model -------
            const course = {
                id: Date.now(),
                title: '',
                subject: '',
                level: '',
                language: '',
                price: 0,
                free: false,
                release: 'draft',
                scheduleDate: null,
                thumbnailName: '',
                tags: [],
                description: '',
                drip: false,
                sections: [{
                    id: crypto.randomUUID(),
                    title: 'Section Title',
                    lessons: [{
                        id: crypto.randomUUID(),
                        title: 'Example Title',
                        duration: 4,
                        status: 'Draft',
                        preview: true,
                        videoFileName: '',
                        resources: [],
                        notes: ''
                    }]
                }]
            }



            // ------- Helpers -------
            const $ = (sel, root = document) => root.querySelector(sel)
            const $$ = (sel, root = document) =>
                Array.from(root.querySelectorAll(sel))
            const toast = new bootstrap.Toast($('#toast'), {
                delay: 2200
            })

            function showToast(msg, ok = true) {
                const el = $('#toast')
                $('#toastBody').textContent = msg
                el.classList.toggle('text-bg-success', ok)
                el.classList.toggle('text-bg-danger', !ok)
                toast.show()
            }

            function totalLessons() {
                return course.sections.reduce((a, s) => a + s.lessons.length, 0)
            }

            function totalDuration() {
                return course.sections.reduce(
                    (a, s) =>
                    a + s.lessons.reduce((b, l) => b + Number(l.duration || 0), 0),
                    0
                )
            }

            // ------- Lesson Modal Logic -------
            const lessonModal = new bootstrap.Modal($('#lessonModal'))

            function openLessonModal(sectionId, lessonId) {
                const sec = course.sections.find(s => s.id === sectionId)
                let lsn = sec && sec.lessons.find(l => l.id === lessonId)
                $('#lfSectionId').value = sectionId
                $('#lfLessonId').value = lessonId || ''
                $('#lfTitle').value = lsn ? lsn.title : ''
                $('#lfDuration').value = lsn ? lsn.duration : ''
                $('#lfStatus').value = lsn ? lsn.status : 'Draft'
                $('#lfPreview').checked = lsn ? !!lsn.preview : false
                $('#lfNotes').value = lsn ? lsn.notes || '' : ''
                $('#lfVideo').value = ''
                $('#lfResourceUrl').value = ''
                const resList = $('#lfResList')
                resList.innerHTML = '';
                (lsn ? lsn.resources : []).forEach((r, i) => {
                    const chip = document.createElement('span')
                    chip.className = 'badge text-bg-light border me-1 mb-1'
                    chip.textContent = r
                    resList.appendChild(chip)
                })
                lessonModal.show()
            }

            $('#btnAddRes').addEventListener('click', () => {
                const url = $('#lfResourceUrl').value.trim()
                if (!url) return
                const resList = $('#lfResList')
                const chip = document.createElement('span')
                chip.className = 'badge text-bg-light border me-1 mb-1'
                chip.textContent = url
                resList.appendChild(chip)
                $('#lfResourceUrl').value = ''
            })

            // $('#btnSaveLesson').addEventListener('click', () => {
            //     // gather
            //     const sectionId = $('#lfSectionId').value
            //     const lessonId = $('#lfLessonId').value || null
            //     const sec = course.sections.find(s => s.id === sectionId)
            //     if (!sec) return
            //     const l = {
            //         id: lessonId || crypto.randomUUID(),
            //         title: $('#lfTitle').value.trim(),
            //         duration: Number($('#lfDuration').value || 0),
            //         status: $('#lfStatus').value,
            //         preview: $('#lfPreview').checked,
            //         videoFileName: $('#lfVideo').files[0] ?
            //             $('#lfVideo').files[0].name : lessonId ?
            //             sec.lessons.find(x => x.id === lessonId).videoFileName || '' : '',
            //         resources: $$('#lfResList .badge').map(b => b.textContent),
            //         notes: $('#lfNotes').value.trim()
            //     }
            //     if (!l.title || !l.duration) {
            //         showToast('Please add title and duration', false)
            //         return
            //     }
            //     if (lessonId) {
            //         const idx = sec.lessons.findIndex(x => x.id === lessonId)
            //         sec.lessons[idx] = l
            //     } else {
            //         sec.lessons.push(l)
            //     }
            //     lessonModal.hide()
            //     renderSections()
            //     updateSummary()
            //     showToast('Lesson saved')
            // })

            // ------- Section controls -------
            $('#btnAddSection').addEventListener('click', () => {
                //ajax call to save course section
                fetch("{{ url('/educator/courses/section/' . $course->id) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Authorization": "{{ env('AUTH_KEY') }}"
                        },
                        body: JSON.stringify({}),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.json();
                    })
                    .then(response => {
                        console.log("Section created:", response);

                        document.querySelector("#sectionsAccordion").insertAdjacentHTML(
                            "beforeend",
                            renderSectionItem(response.section)

                        );
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Something went wrong while creating the section.");
                    });

            });


            $('#btnExpandAll').addEventListener('click', () => {
                $$('.accordion-collapse').forEach(
                    el => new bootstrap.Collapse(el, {
                        show: true
                    })
                )
            });


            $('#btnCollapseAll').addEventListener('click', () => {
                $$('.accordion-collapse').forEach(el =>
                    new bootstrap.Collapse(el, {
                        toggle: false
                    }).hide()
                )
            });

            // ------- Summary, Checklist, Validation -------
            function updateSummary() {
                $('#sumTitle').textContent = course.title || 'Untitled course'
                $('#sumSubject').textContent = course.subject || '—'
                $('#sumPrice').textContent = course.free ?
                    'Free' :
                    `$${(course.price || 0).toFixed(2)}`
                $('#sumLessons').textContent = totalLessons()
                $('#sumDuration').textContent = `${totalDuration()}m`
                $('#sumRelease').textContent =
                    course.release === 'publish' ?
                    'Publish now' :
                    course.release === 'schedule' ?
                    `Schedule ${course.scheduleDate || ''}` :
                    'Draft'

                $('#chkTitle').checked = !!(course.title && course.subject)
                $('#chkDesc').checked = !!(
                    course.description && course.description.trim().length >= 10
                )
                $('#chkThumb').checked = !!course.thumbnailName
                $('#chkLesson').checked = course.sections.some(s =>
                    s.lessons.some(l => l.status === 'Published')
                )

                const ready =
                    $('#chkTitle').checked &&
                    $('#chkDesc').checked &&
                    $('#chkLesson').checked
                $('#sumReady').textContent = ready ? 'Yes' : 'No'
                $('#sumReady').classList.toggle('text-success', ready)
                $('#sumReady').classList.toggle('text-danger', !ready)
                $('#btnPublish').disabled = !ready
            }

            function fetchSections() {
                fetch('{{ url('/educator/course/get/sections/' . $course->id) }}', {
                        method: "GET",

                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token() }}',
                            "Authorization": "{{ env('AUTH_KEY') }}"
                        }

                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        console.log("Section fetched:", response);
                        return response.json();
                    })
                    .then(response => {
                        response.sections.forEach((sectionItem) => {

                            document.querySelector("#sectionsAccordion").insertAdjacentHTML(
                                "beforeend",
                                renderSectionItem(sectionItem)
                            );
                            var lessons = sectionItem.lessons;
                            lessons.forEach((lessonItem, index) => {
                                document.querySelector("#section-lessons-" + sectionItem.id)
                                    .insertAdjacentHTML(
                                        "beforeend",
                                        renderLessonItem(lessonItem, index + 1)
                                    );
                            });
                        });
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Something went wrong while creating the section.");
                    });
            }

            // ------- Actions -------
            $('#btnSaveDraft').addEventListener('click', () => {
                localStorage.setItem('edc-course-draft', JSON.stringify(course))
                showToast('Draft saved locally')
            })
            $('#btnPublish').addEventListener('click', () => {
                if ($('#btnPublish').disabled) return
                // pretend POST to API
                console.log('PUBLISH_PAYLOAD', JSON.stringify(course, null, 2))
                showToast('Course published!')
            })
            $('#btnPreview').addEventListener('click', () => {
                alert('Preview would open the public listing in a new tab.')
            })

            // Export / Import
            $('#btnExport').addEventListener('click', () => {
                const blob = new Blob([JSON.stringify(course, null, 2)], {
                    type: 'application/json'
                })
                const a = document.createElement('a')
                a.href = URL.createObjectURL(blob)
                a.download = (course.title || 'course') + '.json'
                a.click()
                URL.revokeObjectURL(a.href)
            })
            $('#importJson').addEventListener('change', e => {
                const f = e.target.files[0]
                if (!f) return
                const r = new FileReader()
                r.onload = () => {
                    try {
                        const obj = JSON.parse(r.result)
                        Object.assign(course, obj)
                        renderSections()
                        updateSummary()
                        showToast('Imported')
                    } catch (err) {
                        showToast('Invalid JSON', false)
                    }
                }
                r.readAsText(f)
            })

            // ------- Init -------
            // bindForm()
            // renderSections()
            // updateSummary()

            // Video file preview
            document
                .getElementById('lfVideo')
                .addEventListener('change', function(e) {
                    const file = e.target.files[0]
                    const preview = document.getElementById('videoPreview')
                    if (file) {
                        const url = URL.createObjectURL(file)
                        preview.innerHTML =
                            `<video controls class="w-100 rounded" style="max-height:200px"><source src="${url}" type="${file.type}"></video>`
                    } else {
                        preview.innerHTML =
                            `<i class="bi bi-camera-video fs-1 text-muted"></i><p class="mb-0 small text-muted">No video selected</p>`
                    }
                })

            // Video link preview
            document
                .getElementById('lfVideoLink')
                .addEventListener('input', function(e) {
                    const link = e.target.value.trim()
                    const preview = document.getElementById('videoPreview')
                    if (link) {
                        preview.innerHTML =
                            `<iframe class="w-100 rounded" style="max-height:200px" src="${link}" allowfullscreen></iframe>`
                    }
                })

            // Learning materials preview
            document
                .getElementById('lfMaterials')
                .addEventListener('change', function(e) {
                    const list = document.getElementById('lfMatList')
                    list.innerHTML = '';
                    [...e.target.files].forEach(f => {
                        list.innerHTML +=
                            `<div class="col-auto"><span class="badge bg-secondary">${f.name}</span></div>`
                    })
                    if (!e.target.files.length) {
                        list.innerHTML =
                            `<div class="col-12 text-center text-muted small"><i class="bi bi-file-earmark-ppt fs-4"></i><br>No materials uploaded</div>`
                    }
                })

            // Worksheets preview
            document
                .getElementById('lfWorksheets')
                .addEventListener('change', function(e) {
                    const list = document.getElementById('lfWsList')
                    list.innerHTML = '';
                    [...e.target.files].forEach(f => {
                        list.innerHTML +=
                            `<div class="col-auto"><span class="badge bg-secondary">${f.name}</span></div>`
                    })
                    if (!e.target.files.length) {
                        list.innerHTML =
                            `<div class="col-12 text-center text-muted small"><i class="bi bi-file-earmark-text fs-4"></i><br>No worksheets uploaded</div>`
                    }
                })

            // Resource links
            document.getElementById('btnAddRes').addEventListener('click', () => {
                const url = document.getElementById('lfResourceUrl').value.trim()
                if (!url) return
                const resList = document.getElementById('lfResList')
                if (resList.textContent.includes('No links')) resList.innerHTML = ''
                resList.innerHTML += `<span class="badge bg-info text-dark me-1">${url}</span>`
                document.getElementById('lfResourceUrl').value = ''
            });



            function renderSectionItem(response) {
                return `
            <div class="accordion-item" id="section-${response.id}">
                <h2 class="accordion-header" id="heading-${response.id}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-${response.id}" aria-expanded="true"
                        aria-controls="collapse-${response.id}">
                        ${response.title ?? "Untitled Section"}
                    </button>
                </h2>

                <div id="collapse-${response.id}" class="accordion-collapse collapse show"
                    aria-labelledby="heading-${response.id}" data-bs-parent="#sectionsAccordion">
                    <div class="accordion-body">

                        <ul class="list-group list-draggable mb-3" id="list-sec-${response.id}">
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <i class="bi bi-grip-vertical lesson-handle"></i>
                                    <div>
                                        <div class="fw-semibold">${response.title ?? "New Section"}</div>
                                        <div class="small text-muted">Draft</div>
                                    </div>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-light" data-act="edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-light" data-act="up"><i class="bi bi-arrow-up"></i></button>
                                    <button class="btn btn-light" data-act="down"><i class="bi bi-arrow-down"></i></button>
                                    <button class="btn btn-light" data-act="delete" onclick="deleteSection('${response.id}')"><i class="bi bi-trash"></i></button>
                                </div>
                            </li>
                        </ul>
                        <div class="p-2 bg-light">
                        <div class='font-italic mb-1'>Lessons in this section</div>
                        <ul class="list-group" id="section-lessons-${response.id}">
                        </ul>

                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary"
                            onclick="setSectionId('${response.id}')"
                                data-act="add-lesson"
                                data-bs-toggle="modal"
                                data-bs-target="#lessonModal">
                                <i class="bi bi-plus-lg me-1"></i> Add Lesson Content
                            </button>
                        </div>
                        </div>

                    </div>
                </div>
            </div>
            `;
            }

            function deleteSection(id) {
                if (confirm("Are you sure you want to delete this section?")) {
                    fetch(`{{ route('educator.courses.section.delete', ':id') }}`.replace(':id', id), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Authorization': '{{ env('AUTH_KEY') }}'
                        }
                    }).then(r => r.json()).then(r => {
                        console.log(r);
                        if (r.success) {
                            document.querySelector(`#section-${id}`).remove()
                        }
                    })
                }
            }


            function renderLessonItem(response, index) {
                return `  <li class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <i class="bi bi-grip-vertical lesson-handle"></i>
                                    <div>
                                        <div class="fw-semibold">#${index} ${response.title ?? "New Section"}</div>
                                        <div class="small text-muted">${response.type}</div>
                                    </div>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-light" data-act="edit"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-light" data-act="up"><i class="bi bi-arrow-up"></i></button>
                                    <button class="btn btn-light" data-act="down"><i class="bi bi-arrow-down"></i></button>
                                    <button class="btn btn-light" data-act="delete" onclick="deleteSection('${response.id}')"><i class="bi bi-trash"></i></button>
                                </div>
                            </li>`;
            }



            async function saveLesson() {
                // e.preventDefault();

                const form = document.getElementById('lessonForm');
                const formData = new FormData();

                const lessonType = document.getElementById('lessonType').value;

                // Collect basic fields
                formData.append('course_section_id', document.getElementById('sectionId').value);
                formData.append('course_id', document.getElementById('courseId').value);
                formData.append('type', document.getElementById('lessonType').value);
                formData.append('title', document.getElementById('lfTitle').value);
                formData.append('duration', document.getElementById('lfDuration').value);
                formData.append('price', document.getElementById('price').value || 0);
                formData.append('free', document.getElementById('freeCourse').checked ? 1 : 0);
                formData.append('status', document.getElementById('lfStatus').value);
                formData.append('preview', document.getElementById('lfPreview').checked ? 1 : 0);
                formData.append('notes', document.getElementById('lfNotes').value);

                // Video (upload or link)
                if (lessonType == 'video') {
                    const videoFile = document.getElementById('lfVideo').files[0];
                    const videoLink = document.getElementById('lfVideoLink').value.trim();
                    if (videoFile) {
                        formData.append('video_path', videoFile);
                    } else if (videoLink) {
                        formData.append('video_link', videoLink);
                    }
                }

                if (lessonType == 'material') {
                    const videoFile = document.g
                    // Materials
                    const materials = document.getElementById('lfMaterials').files;
                    for (let i = 0; i < materials.length; i++) {
                        formData.append('materials[]', materials[i]);
                    }
                }

                if (lessonType == 'worksheet') {
                    const videoFile = document.g
                    // Worksheets
                    const worksheets = document.getElementById('lfWorksheets').files;
                    for (let i = 0; i < worksheets.length; i++) {
                        formData.append('worksheets[]', worksheets[i]);
                    }
                }

                // Resources (extra links)
                const resources = Array.from(document.querySelectorAll('#lfResList .badge'))
                    .map(el => el.textContent.trim())
                    .filter(Boolean);
                if (resources.length > 0) {
                    resources.forEach((r, i) => formData.append(`resources[${i}]`, r));
                }

                // Send AJAX POST
                try {
                    const response = await fetch('{{ url('/educator/lessons/store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': '{{ env('AUTH_KEY') }}'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Lesson created successfully',
                            text: 'The lesson has been created successfully.'
                        });
                        console.log(data);
                        form.reset();
                        document.getElementById('lfResList').innerHTML = 'No links added';
                        //close lessonModal IN bootstrap js
                        lessonModal.hide();
                       

                    } else {
                        console.error('Validation errors:', data);
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Errors',
                            text: Object.entries(data.errors).map(([key, value]) => `${key}: ${value}`).join('\n')
                        });
                    }
                } catch (err) {
                    console.error('Error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong while creating the lesson.'
                    });
                }
            }



            function setSectionId(id) {
                document.getElementById("sectionId").value = id;
            }

            function setLessonType(type) {
                document.getElementById("lessonType").value = type;
            }
        </script>
    @endpush
</x-educator-layout>
