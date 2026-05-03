<x-educator-layout>

    @push('styles')
        <style>
            .course-create-page {
                background: #f6f8fb;
            }

            .course-create-page .page-header {
                background: #fff;
                border-bottom: 1px solid #e5e7eb;
            }

            .course-create-page .form-card {
                border: 0;
                border-radius: 0.85rem;
                box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
            }

            .course-create-page .form-card .card-header {
                background: #fff;
                border-bottom: 1px solid #f1f5f9;
                padding: 1rem 1.25rem;
            }

            .course-create-page .form-card .card-header h6 {
                font-weight: 700;
                color: #0f172a;
            }

            .course-create-page .form-card .card-header .section-icon {
                width: 36px;
                height: 36px;
                border-radius: 0.55rem;
                background: #eef2ff;
                color: #4f46e5;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-right: 0.65rem;
            }

            .course-create-page .form-label {
                font-weight: 600;
                color: #0f172a;
                font-size: 0.875rem;
            }

            .course-create-page .form-text {
                color: #64748b;
            }

            .course-create-page .required {
                color: #dc2626;
                margin-left: 2px;
            }

            .course-create-page .thumb-preview-wrap {
                border: 1px dashed #cbd5e1;
                border-radius: 0.6rem;
                background: #f8fafc;
                padding: 0.75rem;
                min-height: 132px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #94a3b8;
                font-size: 0.85rem;
            }

            .course-create-page .thumb-preview-wrap img {
                max-height: 160px;
                max-width: 100%;
                border-radius: 0.45rem;
                box-shadow: 0 2px 6px rgba(15, 23, 42, 0.1);
            }

            .course-create-page .action-bar {
                position: sticky;
                bottom: 0;
                background: #fff;
                border-top: 1px solid #e5e7eb;
                padding: 0.85rem 1rem;
                border-radius: 0 0 0.85rem 0.85rem;
                z-index: 5;
            }

            .course-create-page .form-check.toggle-card {
                border: 1px solid #e5e7eb;
                border-radius: 0.6rem;
                padding: 0.65rem 0.85rem 0.65rem 2.6rem;
                background: #fff;
            }

            .course-create-page .form-check.toggle-card.active {
                border-color: #4f46e5;
                background: #eef2ff;
            }
        </style>
    @endpush

    <div class="course-create-page py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">

                    <!-- Header -->
                    <div class="page-header rounded-3 px-4 py-3 mb-4 d-flex align-items-center justify-content-between">
                        <div>
                            <nav aria-label="breadcrumb" class="mb-1">
                                <ol class="breadcrumb mb-0 small">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('educator.dashboard') }}" class="text-decoration-none">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('educator.courses.crud.index') }}" class="text-decoration-none">Courses</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                                </ol>
                            </nav>
                            <h4 class="mb-0">
                                <i class="bi bi-mortarboard-fill text-primary me-2"></i> Create new course
                            </h4>
                            <p class="text-muted small mb-0">Fill in the basics — you can add sections, lessons and media after the course is created.</p>
                        </div>
                        <a href="{{ route('educator.courses.crud.index') }}" class="btn btn-outline-secondary btn-sm d-none d-md-inline-flex">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger d-flex">
                            <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                            <div>
                                <strong>Please fix the following:</strong>
                                <ul class="mb-0 mt-1 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('educator.courses.crud.store') }}" method="POST" id="create-form"
                        enctype="multipart/form-data" novalidate>
                        @csrf

                        <!-- Basic information -->
                        <div class="card form-card mb-4">
                            <div class="card-header d-flex align-items-center">
                                <span class="section-icon"><i class="bi bi-info-circle"></i></span>
                                <div>
                                    <h6 class="mb-0">Basic information</h6>
                                    <small class="text-muted">What is your course about?</small>
                                </div>
                            </div>
                            <div class="card-body">
                               

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="course_category_id">Category <span class="required">*</span></label>
                                        <select id="course_category_id" name="course_category_id"
                                            class="form-select @error('course_category_id') is-invalid @enderror" required>
                                            <option value="" disabled {{ old('course_category_id') ? '' : 'selected' }}>
                                                Select a category…
                                            </option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('course_category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('course_category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="subject">Subject <span class="required">*</span></label>
                                        <select id="subject" name="subject"
                                            class="form-select @error('subject') is-invalid @enderror"
                                            data-old-value="{{ old('subject') }}" required>
                                            <option value="" selected disabled>
                                                Select a category first…
                                            </option>
                                        </select>
                                        <div class="form-text" id="subject_help">Subjects load after a category is selected.</div>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                               
                            </div>
                        </div>

                        <!-- Course details -->
                        <div class="card form-card mb-4">
                            <div class="card-header d-flex align-items-center">
                                <span class="section-icon"><i class="bi bi-sliders2"></i></span>
                                <div>
                                    <h6 class="mb-0">Course details</h6>
                                    <small class="text-muted">Format, level and language.</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="type">Course type</label>
                                        <select id="type" name="type" class="form-select">
                                            @php($oldType = old('type', 'module'))
                                            <option value="module" {{ $oldType === 'module' ? 'selected' : '' }}>Module-based</option>
                                            <option value="video" {{ $oldType === 'video' ? 'selected' : '' }}>Video course</option>
                                            <option value="live" {{ $oldType === 'live' ? 'selected' : '' }}>Live course</option>
                                        </select>
                                        <div class="form-text">How students will primarily consume the content.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="difficulty">Difficulty</label>
                                        <select id="difficulty" name="difficulty" class="form-select">
                                            <option value="" {{ old('difficulty') ? '' : 'selected' }}>Select difficulty…</option>
                                            <option value="beginner" {{ old('difficulty') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                            <option value="intermediate" {{ old('difficulty') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                            <option value="advanced" {{ old('difficulty') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="level">Level</label>
                                        <input type="text" id="level" name="level"
                                            class="form-control"
                                            value="{{ old('level') }}"
                                            placeholder="e.g. Grade 10, A-Level, Year 1">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="language_id">Language</label>
                                        <select id="language" name="language"
                                            class="form-select @error('language') is-invalid @enderror">
                                           
                                            @foreach ($languages as $language)
                                                <option value="{{ $language->name }}"
                                                    {{ old('language') == $language->id ? 'selected' : '' }}>
                                                    {{ $language->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('language_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="duration">Duration</label>
                                        <input type="text" id="duration" name="duration"
                                            class="form-control"
                                            value="{{ old('duration') }}"
                                            placeholder="e.g. 8 weeks, 20 hours">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label" for="schedule_date">Schedule date & time</label>
                                        <input type="datetime-local" id="schedule_date" name="schedule_date"
                                            class="form-control"
                                           
                                            value="{{ old('schedule_date') }}">
                                        <div class="form-text">Optional. Used for live or cohort-based courses.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Media -->
                        <div class="card form-card mb-4">
                            <div class="card-header d-flex align-items-center">
                                <span class="section-icon"><i class="bi bi-image"></i></span>
                                <div>
                                    <h6 class="mb-0">Course thumbnail</h6>
                                    <small class="text-muted">A square or 16:9 image works best (max 2 MB).</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-start">
                                    <div class="col-md-7">
                                        <label class="form-label" for="thumbnail">Upload image</label>
                                        <input type="file" id="thumbnail" name="thumbnail"
                                            class="form-control @error('thumbnail') is-invalid @enderror"
                                            accept="image/*">
                                        @error('thumbnail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">JPG, PNG or WEBP. You can change this later.</div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Preview</label>
                                        <div id="thumb-preview-wrap" class="thumb-preview-wrap">
                                            <span id="thumb-preview-empty">No image selected</span>
                                            <img id="thumb-preview-img" src="" alt="Thumbnail preview" style="display:none">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="card form-card mb-4">
                            <div class="card-header d-flex align-items-center">
                                <span class="section-icon"><i class="bi bi-currency-dollar"></i></span>
                                <div>
                                    <h6 class="mb-0">Pricing</h6>
                                    <small class="text-muted">Set the course price or mark it as free.</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label" for="price">Price (USD)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" min="0" id="price" name="price"
                                                class="form-control @error('price') is-invalid @enderror"
                                                value="{{ old('price', 0) }}"
                                                placeholder="0.00">
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-text">Use 0 if you’ll mark this course as free.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check toggle-card {{ old('is_free') ? 'active' : '' }}">
                                            <input class="form-check-input" type="checkbox" id="is_free" name="is_free"
                                                value="1" {{ old('is_free') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_free">
                                                <i class="bi bi-unlock-fill text-success me-1"></i> This is a free course
                                            </label>
                                            <div class="form-text small mb-0">Free courses can still require login.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content release -->
                        <div class="card form-card mb-4">
                            <div class="card-header d-flex align-items-center">
                                <span class="section-icon"><i class="bi bi-droplet"></i></span>
                                <div>
                                    <h6 class="mb-0">Content release</h6>
                                    <small class="text-muted">Optional drip schedule for staged content.</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-6">
                                        <div class="form-check toggle-card {{ old('drip') ? 'active' : '' }}">
                                            <input class="form-check-input" type="checkbox" id="drip" name="drip"
                                                value="1" {{ old('drip') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="drip">
                                                <i class="bi bi-calendar2-event text-primary me-1"></i> Enable drip content
                                            </label>
                                            <div class="form-text small mb-0">Release lessons over time after enrollment.</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="drip_duration">Drip interval</label>
                                        <input type="text" id="drip_duration" name="drip_duration"
                                            class="form-control"
                                            value="{{ old('drip_duration') }}"
                                            placeholder="e.g. 1 lesson per 3 days">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card form-card mb-4">
                            <div class="card-header d-flex align-items-center">
                                <span class="section-icon"><i class="bi bi-info-circle"></i></span>
                                <div>
                                    <h6 class="mb-0">Course details</h6>
                                    <small class="text-muted">Format, level and language.</small>
                                </div>
                            </div>

                             <div class="card-body">
                                 <div class="mb-3">
                                    <label class="form-label" for="title">Course title <span class="required">*</span></label>
                                    <input type="text" id="title" name="title"
                                        class="form-control form-control-lg @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}"
                                        placeholder="e.g. Mastering Algebra for High School Students" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">A clear, specific title helps students find your course.</div>
                                </div>

                                 <div class="mt-3">
                                    <label class="form-label" for="description">Description <span class="required">*</span></label>
                                    <textarea id="description" name="description" rows="5"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Describe what students will learn, prerequisites, outcomes…" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">Tip: include who this course is for and what they’ll achieve.</small>
                                        <button type="button" id="generate-ai" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-stars me-1"></i> Generate with AI
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Publishing -->
                        <div class="card form-card mb-4">
                            <div class="card-header d-flex align-items-center">
                                <span class="section-icon"><i class="bi bi-send"></i></span>
                                <div>
                                    <h6 class="mb-0">Publishing</h6>
                                    <small class="text-muted">Choose when this course goes live.</small>
                                </div>
                            </div>

                           
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="publish_option">Publish option</label>
                                        <select id="publish_option" name="publish_option" class="form-select">
                                            @php($oldPub = old('publish_option', 'draft'))
                                            <option value="draft" {{ $oldPub === 'draft' ? 'selected' : '' }}>Save as draft</option>
                                            <option value="now" {{ $oldPub === 'now' ? 'selected' : '' }}>Publish now</option>
                                            <option value="schedule" {{ $oldPub === 'schedule' ? 'selected' : '' }}>Schedule for later</option>
                                        </select>
                                        <div class="form-text">Drafts are visible only to you and admins.</div>
                                    </div>
                                    <div class="col-md-6" id="publish_date_wrapper" style="display:{{ old('publish_option') === 'schedule' ? 'block' : 'none' }};">
                                        <label class="form-label" for="publish_date">Publish date</label>
                                        <input type="datetime-local" id="publish_date" name="publish_date"
                                            class="form-control"
                                            value="{{ old('publish_date') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="action-bar d-flex justify-content-between align-items-center">
                                <a href="{{ route('educator.courses.crud.index') }}" class="btn btn-link text-muted text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i> Cancel
                                </a>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-lg me-1"></i> Create course
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="alert alert-info d-flex">
                        <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                        <div>
                            After creating the course, you’ll be taken to the content editor where you can add sections, lessons and media.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const categorySelect = document.getElementById('course_category_id');
                const subjectSelect = document.getElementById('subject');
                const subjectHelp = document.getElementById('subject_help');
                const subjectsUrl = (categoryId) =>
                    @json(url('/api/categories')) + '/' + encodeURIComponent(categoryId) + '/subjects';

                function setSubjectsLoading() {
                    if (!subjectSelect) return;
                    subjectSelect.innerHTML = '<option value="" selected disabled>Loading subjects…</option>';
                    subjectSelect.disabled = true;
                    if (subjectHelp) subjectHelp.textContent = 'Loading subjects for the selected category…';
                }

                function setSubjectsEmptyForNoCategory() {
                    if (!subjectSelect) return;
                    subjectSelect.innerHTML = '<option value="" selected disabled>Select a category first…</option>';
                    subjectSelect.disabled = false;
                    if (subjectHelp) subjectHelp.textContent = 'Subjects load after a category is selected.';
                }

                function renderSubjects(subjects, preselectId) {
                    if (!subjectSelect) return;
                    subjectSelect.innerHTML = '';
                    if (!subjects || subjects.length === 0) {
                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.disabled = true;
                        opt.selected = true;
                        opt.textContent = 'No subjects in this category yet';
                        subjectSelect.appendChild(opt);
                        subjectSelect.disabled = true;
                        if (subjectHelp) subjectHelp.textContent = 'There are no subjects under this category.';
                        return;
                    }

                    const placeholder = document.createElement('option');
                    placeholder.value = '';
                    placeholder.disabled = true;
                    placeholder.textContent = 'Select a subject…';
                    if (!preselectId) placeholder.selected = true;
                    subjectSelect.appendChild(placeholder);

                    subjects.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.name;
                        opt.textContent = s.name;
                        if (preselectId && String(preselectId) === String(s.name)) {
                            opt.selected = true;
                        }
                        subjectSelect.appendChild(opt);
                    });
                    subjectSelect.disabled = false;
                    if (subjectHelp) subjectHelp.textContent = 'Choose the subject most relevant to this course.';
                }

                function loadSubjectsForCategory(categoryId, preselectId) {
                    if (!categoryId) {
                        setSubjectsEmptyForNoCategory();
                        return;
                    }
                    setSubjectsLoading();
                    fetch(subjectsUrl(categoryId), {
                            method: 'GET',
                            headers: { 'Accept': 'application/json' }
                        })
                        .then(r => {
                            if (!r.ok) throw new Error('HTTP ' + r.status);
                            return r.json();
                        })
                        .then(data => renderSubjects(data.subjects || [], preselectId))
                        .catch(err => {
                            console.error('Failed to load subjects:', err);
                            subjectSelect.innerHTML = '<option value="" selected disabled>Could not load subjects</option>';
                            subjectSelect.disabled = true;
                            if (subjectHelp) subjectHelp.textContent = 'Could not load subjects. Try changing the category again.';
                        });
                }

                if (categorySelect && subjectSelect) {
                    categorySelect.addEventListener('change', function () {
                        loadSubjectsForCategory(this.value, null);
                    });

                    const initialCategory = categorySelect.value;
                    const oldSubjectId = subjectSelect.dataset.oldValue || null;
                    if (initialCategory) {
                        loadSubjectsForCategory(initialCategory, oldSubjectId);
                    } else {
                        setSubjectsEmptyForNoCategory();
                    }
                }

                const publishOption = document.getElementById('publish_option');
                const publishDateWrapper = document.getElementById('publish_date_wrapper');
                if (publishOption && publishDateWrapper) {
                    publishOption.addEventListener('change', function () {
                        publishDateWrapper.style.display = this.value === 'schedule' ? 'block' : 'none';
                    });
                }

                const isFree = document.getElementById('is_free');
                const price = document.getElementById('price');
                const togglePriceState = () => {
                    if (!isFree || !price) return;
                    if (isFree.checked) {
                        price.value = 0;
                        price.setAttribute('disabled', 'disabled');
                    } else {
                        price.removeAttribute('disabled');
                    }
                };
                if (isFree) {
                    isFree.addEventListener('change', togglePriceState);
                    togglePriceState();
                }

                document.querySelectorAll('.form-check.toggle-card input[type="checkbox"]').forEach(cb => {
                    const card = cb.closest('.toggle-card');
                    if (!card) return;
                    cb.addEventListener('change', () => {
                        card.classList.toggle('active', cb.checked);
                    });
                });

                const thumbInput = document.getElementById('thumbnail');
                const thumbImg = document.getElementById('thumb-preview-img');
                const thumbEmpty = document.getElementById('thumb-preview-empty');
                if (thumbInput && thumbImg && thumbEmpty) {
                    thumbInput.addEventListener('change', function () {
                        const file = this.files && this.files[0];
                        if (!file) {
                            thumbImg.style.display = 'none';
                            thumbImg.src = '';
                            thumbEmpty.style.display = 'inline';
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            thumbImg.src = e.target.result;
                            thumbImg.style.display = 'inline';
                            thumbEmpty.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    });
                }

                const aiBtn = document.getElementById('generate-ai');
                if (aiBtn) {
                    aiBtn.addEventListener('click', function () {
                        aiBtn.disabled = true;
                        const originalLabel = aiBtn.innerHTML;
                        aiBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Generating…';

                        const form = document.querySelector('#create-form');
                        const formData = new FormData(form);
                        formData.append('_token', '{{ csrf_token() }}');

                        fetch('{{ route('educator.generate.course.content') }}', {
                                method: 'POST',
                                body: formData,
                                headers: { 'Accept': 'application/json' }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const titleInput = document.querySelector('input[name="title"]');
                                    const descInput = document.querySelector('textarea[name="description"]');
                                    if (titleInput && data.title) titleInput.value = data.title;
                                    if (descInput && data.description) descInput.value = data.description;
                                } else {
                                    alert('Error: ' + (data.message || 'Could not generate content.'));
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert('An error occurred while generating content.');
                            })
                            .finally(() => {
                                aiBtn.disabled = false;
                                aiBtn.innerHTML = originalLabel;
                            });
                    });
                }
            })();
        </script>
    @endpush

</x-educator-layout>
