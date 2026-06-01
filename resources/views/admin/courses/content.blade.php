<x-admin-layout>
    @include('admin.courses.partials.alerts')

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">Course Content</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.manage.courses') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.courses.show', $course->id) }}">{{ Str::limit($course->title, 30) }}</a>
                    </li>
                    <li class="breadcrumb-item active">Content</li>
                </ol>
            </nav>
        </div>
        @include('admin.courses.partials.actions')
    </div>

    @include('admin.courses.partials.nav')

    @php
        $allLessons = $course->sections ? $course->sections->flatMap->lessons : collect();
        $totalLessons = $allLessons->count();
        $activeLessons = $allLessons->where('active', true)->count();
        $videoLessons = $allLessons->where('type', 'video')->count();
    @endphp

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="kpi-card p-3">
                <div class="kpi-label">Sections</div>
                <div class="kpi-value">{{ $course->sections?->count() ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card p-3">
                <div class="kpi-label">Lessons</div>
                <div class="kpi-value">{{ $totalLessons }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card p-3">
                <div class="kpi-label">Active Lessons</div>
                <div class="kpi-value text-success">{{ $activeLessons }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card p-3">
                <div class="kpi-label">Video Lessons</div>
                <div class="kpi-value">{{ $videoLessons }}</div>
            </div>
        </div>
    </div>

    <div class="kpi-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="section-title mb-0"><i class="bi bi-collection-play me-2"></i>Sections &amp; Lessons</h5>
            <span class="text-muted small">{{ $course->sections->count() }} sections · {{ $totalLessons }}
                lessons</span>
        </div>

        @if ($course->sections && $course->sections->count() > 0)
            <div class="accordion course-accordion" id="courseSections">
                @foreach ($course->sections as $section)
                    @php $sectionLessons = $section->lessons ?? collect(); @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#section{{ $section->id }}">
                                <span class="fw-semibold">{{ $section->title }}</span>
                                <span class="badge bg-primary ms-2">{{ $sectionLessons->count() }} lessons</span>
                            </button>
                        </h2>
                        <div id="section{{ $section->id }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                            data-bs-parent="#courseSections">
                            <div class="accordion-body p-0">
                                @if ($sectionLessons->count() > 0)
                                    <div class="lesson-list">
                                        @foreach ($sectionLessons as $lesson)
                                            @php
                                                $type = strtolower($lesson->type ?? '');
                                                $icon =
                                                    $type === 'video'
                                                        ? 'bi-play-circle-fill'
                                                        : ($type === 'worksheet'
                                                            ? 'bi-file-earmark-spreadsheet-fill'
                                                            : ($type === 'material'
                                                                ? 'bi-file-earmark-text-fill'
                                                                : 'bi-journal'));
                                            @endphp
                                            <div class="lesson-item">
                                                <div
                                                    class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                                    <div class="d-flex gap-3">
                                                        <div class="lesson-icon lesson-icon-{{ $type ?: 'default' }}">
                                                            <i class="bi {{ $icon }}"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">
                                                                {{ $lesson->title ?? $lesson->name }}</div>
                                                            @if ($lesson->description)
                                                                <small
                                                                    class="text-muted d-block">{{ Str::limit(strip_tags($lesson->description), 120) }}</small>
                                                            @endif
                                                            <div class="d-flex gap-2 flex-wrap mt-2">
                                                                <span
                                                                    class="badge bg-secondary text-capitalize">{{ $lesson->type ?? 'N/A' }}</span>
                                                                @if ($lesson->duration)
                                                                    <span class="badge bg-light text-dark border"><i
                                                                            class="bi bi-clock me-1"></i>{{ $lesson->duration }}
                                                                        min</span>
                                                                @endif
                                                                @if ($lesson->free)
                                                                    <span class="badge bg-success">Free</span>
                                                                @else
                                                                    <span class="badge bg-primary">$
                                                                        {{ number_format($lesson->price, 0) }}</span>
                                                                @endif
                                                                <span
                                                                    class="badge text-bg-{{ $lesson->status === 'Published' ? 'success' : 'warning' }}">{{ $lesson->status ?? 'Draft' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex flex-column align-items-end gap-2">
                                                        <a href="{{ route('admin.lessons.show', $lesson->id) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye me-1"></i>View
                                                        </a>
                                                        <form method="POST"
                                                            action="{{ route('admin.lessons.active', $lesson->id) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div
                                                                class="form-check form-switch m-0 d-flex align-items-center gap-2">
                                                                <input type="checkbox" class="form-check-input"
                                                                    role="switch" id="active{{ $lesson->id }}"
                                                                    onchange="this.form.submit()"
                                                                    {{ $lesson->active ? 'checked' : '' }}>
                                                                <label
                                                                    class="form-check-label small {{ $lesson->active ? 'text-success' : 'text-muted' }}"
                                                                    for="active{{ $lesson->id }}">
                                                                    {{ $lesson->active ? 'Active' : 'Inactive' }}
                                                                </label>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                @php
                                                    $hasVideo = $type === 'video' && $lesson->lesson_video_path;
                                                    $hasWorksheet = $lesson->worksheets
                                                        ? $lesson->worksheets_path
                                                        : null;
                                                    $hasMaterial = $lesson->materials ? $lesson->materials_path : null;
                                                @endphp
                                                @if ($hasVideo || $hasWorksheet || $hasMaterial || $lesson->video_link)
                                                    <div class="lesson-asset mt-3">
                                                        @if ($hasVideo)
                                                            @if ($lesson->uses_direct_video_player)
                                                                <video class="w-100 rounded asset-video" controls
                                                                    preload="none"
                                                                    src="{{ $lesson->lesson_video_path }}"></video>
                                                            @else
                                                                <div class="ratio ratio-16x9 rounded overflow-hidden">
                                                                    <iframe src="{{ $lesson->lesson_video_path }}"
                                                                        frameborder="0"
                                                                        allow="autoplay; fullscreen; picture-in-picture"
                                                                        allowfullscreen></iframe>
                                                                </div>
                                                            @endif
                                                        @elseif ($lesson->video_link)
                                                            <a href="{{ $lesson->video_link }}" target="_blank"
                                                                rel="noopener" class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-box-arrow-up-right me-1"></i>Open
                                                                external video
                                                            </a>
                                                        @endif

                                                        @if ($hasWorksheet)
                                                            <a href="{{ $hasWorksheet }}" target="_blank"
                                                                rel="noopener" class="btn btn-sm btn-outline-secondary">
                                                                <i class="bi bi-file-earmark-spreadsheet me-1"></i>View
                                                                worksheet
                                                            </a>
                                                        @endif
                                                        @if ($hasMaterial)
                                                            <a href="{{ $hasMaterial }}" target="_blank"
                                                                rel="noopener" class="btn btn-sm btn-outline-secondary">
                                                                <i class="bi bi-file-earmark-text me-1"></i>View
                                                                material
                                                            </a>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="text-muted small mt-2"><i
                                                            class="bi bi-exclamation-circle me-1"></i>No uploaded asset
                                                        for this lesson.</div>
                                                @endif

                                                @if ($lesson->notes)
                                                    <div class="lesson-notes mt-2">
                                                        <i class="bi bi-sticky me-1"></i>{{ $lesson->notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0 p-3">No lessons in this section.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                   
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    No sections added yet.
                </div>
            @endif
        </div>

        <div class="kpi-card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="section-title mb-0"><i class="bi bi-list-ul me-2"></i>All Lessons</h5>
                <span class="text-muted small">{{ $totalLessons }} records</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle modern-table data-table w-100">
                    <thead>
                        <tr>
                            <th>Lesson</th>
                            <th>Section</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Active</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allLessons as $lesson)
                            <tr>
                                <td class="fw-semibold">{{ $lesson->title ?? $lesson->name }}</td>
                                <td>{{ $lesson->courseSection?->title ?? '—' }}</td>
                                <td class="text-capitalize">{{ $lesson->type ?? 'N/A' }}</td>
                                <td>{{ $lesson->duration ? $lesson->duration . ' min' : '—' }}</td>
                                <td>
                                    @if ($lesson->free)
                                        Free
                                    @else
                                        $ {{ number_format($lesson->price, 0) }}
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge text-bg-{{ $lesson->status === 'Published' ? 'success' : 'warning' }}-subtle">
                                        {{ $lesson->status ?? 'Draft' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge text-bg-{{ $lesson->active ? 'success' : 'secondary' }}-subtle">
                                        {{ $lesson->active ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.lessons.show', $lesson->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No lessons found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('admin.courses.partials.reject-modal')

        @include('admin.courses.partials.datatables')

        @push('styles')
            @include('admin.courses.partials.styles')
            <style>
                .course-accordion .accordion-item {
                    border: 1px solid #e5e7eb;
                    border-radius: 0.75rem !important;
                    margin-bottom: 0.75rem;
                    overflow: hidden;
                }

                .course-accordion .accordion-button {
                    font-weight: 600;
                }

                .accordion-button:not(.collapsed) {
                    background-color: rgba(11, 60, 119, 0.08);
                    color: var(--brand);
                    box-shadow: none;
                }

                .accordion-button:focus {
                    box-shadow: none;
                }

                .lesson-list {
                    display: flex;
                    flex-direction: column;
                }

                .lesson-item {
                    padding: 1rem 1.25rem;
                    border-top: 1px solid #f0f1f4;
                }

                .lesson-item:first-child {
                    border-top: 0;
                }

                .lesson-icon {
                    width: 42px;
                    height: 42px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #fff;
                    font-size: 1.1rem;
                    flex-shrink: 0;
                    background: var(--brand);
                }

                .lesson-icon-video {
                    background: linear-gradient(135deg, #667eea, #764ba2);
                }

                .lesson-icon-worksheet {
                    background: linear-gradient(135deg, #4facfe, #00f2fe);
                }

                .lesson-icon-material {
                    background: linear-gradient(135deg, #fa709a, #fee140);
                }

                .lesson-icon-default {
                    background: var(--brand);
                }

                .lesson-asset {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.5rem;
                    align-items: center;
                }

                .asset-video {
                    max-width: 420px;
                    background: #000;
                }

                .lesson-notes {
                    background: var(--soft);
                    border-radius: 0.5rem;
                    padding: 0.5rem 0.75rem;
                    font-size: 0.85rem;
                    color: var(--muted);
                }
            </style>
        @endpush
    </x-admin-layout>
