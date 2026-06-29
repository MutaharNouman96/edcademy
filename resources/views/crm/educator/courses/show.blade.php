@php
    use App\Http\Controllers\Educator\CourseCrudController;
@endphp

<x-educator-layout>
    <div class="course-show-page">
        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item">
                            <a href="{{ route('educator.courses.crud.index') }}">My Courses</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $course->title }}</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-2">{{ $course->title }}</h1>
                <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">
                    <span><i class="bi bi-folder me-1"></i>{{ $course->category->name ?? 'Uncategorized' }}</span>
                    <span><i class="bi bi-book me-1"></i>{{ $course->subject }}</span>
                    <span><i class="bi bi-calendar3 me-1"></i>{{ $course->created_at->format('M d, Y') }}</span>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <span class="badge bg-{{ $course->status === 'published' ? 'success' : ($course->status === 'scheduled' ? 'info' : 'warning') }}">
                        {{ ucfirst($course->status) }}
                    </span>
                    @if ($course->difficulty)
                        <span class="badge bg-secondary">{{ ucfirst($course->difficulty) }}</span>
                    @endif
                    @if ($course->is_free)
                        <span class="badge bg-success">Free</span>
                    @else
                        <span class="badge bg-primary">${{ number_format($course->price ?? 0, 2) }}</span>
                    @endif
                    <span class="badge bg-dark text-capitalize">{{ $course->type }}</span>
                    <span class="badge bg-{{ $course->approval_status === 'approved' ? 'success' : ($course->approval_status === 'rejected' ? 'danger' : 'info') }}">
                        {{ ucfirst($course->approval_status ?? 'pending') }}
                    </span>
                </div>
            </div>
            <div class="d-flex gap-2">
                @can('update', $course)
                    <a href="{{ route('educator.courses.crud.edit', $course) }}?action=content" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Content
                    </a>
                    <a href="{{ route('educator.courses.crud.edit', $course) }}" class="btn btn-primary">
                        <i class="bi bi-gear me-1"></i> Edit Course
                    </a>
                @endcan
            </div>
        </div>

        <div class="row g-4">
            {{-- Main --}}
            <div class="col-lg-8">
                @if ($course->thumbnail)
                    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                        <img src="{{ str_starts_with($course->thumbnail, 'http') ? $course->thumbnail : asset($course->thumbnail) }}"
                            class="course-hero-img" alt="{{ $course->title }}">
                    </div>
                @endif

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>About this course</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-secondary">{{ $course->description }}</p>
                    </div>
                </div>

                {{-- Curriculum --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-nested me-2"></i>Course Curriculum</h5>
                        <span class="badge bg-secondary">{{ $course->sections_count }} modules · {{ $lessonCount }} lessons</span>
                    </div>
                    <div class="card-body p-0">
                        @forelse ($course->sections as $sectionIndex => $section)
                            <div class="curriculum-section {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="curriculum-section-header px-3 px-md-4 py-3">
                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                        <h6 class="mb-0 fw-semibold">
                                            <span class="text-muted me-2">Module {{ $sectionIndex + 1 }}</span>
                                            {{ $section->title }}
                                        </h6>
                                        <span class="badge bg-light text-dark">{{ $section->lessons->count() }} lessons</span>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle curriculum-table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:72px;">Thumb</th>
                                                <th style="width:48px;">#</th>
                                                <th>Lesson</th>
                                                <th>Type</th>
                                                <th>Duration</th>
                                                <th>Status</th>
                                                <th class="text-end">Content</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($section->lessons as $lessonIndex => $lesson)
                                                @php
                                                    $thumbnail = CourseCrudController::resolveLessonThumbnail($lesson, $course);
                                                    $previewAssets = CourseCrudController::resolveLessonPreviewableAssets($lesson);
                                                    $primaryPreview = $previewAssets[0] ?? null;
                                                    $typeIcon = match ($lesson->type) {
                                                        'video' => 'bi-camera-video-fill',
                                                        'worksheet' => 'bi-file-earmark-text-fill',
                                                        'material' => 'bi-file-earmark-pdf-fill',
                                                        default => 'bi-file-earmark',
                                                    };
                                                    $typeBadge = match ($lesson->type) {
                                                        'video' => 'primary',
                                                        'worksheet' => 'success',
                                                        'material' => 'warning',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td>
                                                        @if ($thumbnail)
                                                            @if ($primaryPreview)
                                                                <button type="button"
                                                                    class="btn p-0 border-0 btn-preview-material"
                                                                    data-title="{{ $lesson->title }} — {{ $primaryPreview['label'] }}"
                                                                    data-kind="{{ $primaryPreview['kind'] }}"
                                                                    data-url="{{ $primaryPreview['url'] }}"
                                                                    title="Preview {{ $primaryPreview['label'] }}">
                                                                    <img src="{{ $thumbnail }}" alt="{{ $lesson->title }}"
                                                                        class="lesson-thumb lesson-thumb--clickable">
                                                                </button>
                                                            @else
                                                                <img src="{{ $thumbnail }}" alt="{{ $lesson->title }}"
                                                                    class="lesson-thumb">
                                                            @endif
                                                        @else
                                                            <div class="lesson-thumb lesson-thumb--placeholder">
                                                                <i class="bi {{ $typeIcon }}"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted small">{{ $lessonIndex + 1 }}</td>
                                                    <td>
                                                        <div class="fw-semibold">{{ $lesson->title }}</div>
                                                        @if ($lesson->description)
                                                            <div class="small text-muted">{{ Str::limit($lesson->description, 80) }}</div>
                                                        @endif
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            @if ($lesson->free)
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Free</span>
                                                            @endif
                                                            @if ($lesson->preview)
                                                                <span class="badge bg-info-subtle text-info border border-info-subtle">Preview</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $typeBadge }}-subtle text-{{ $typeBadge }} border border-{{ $typeBadge }}-subtle text-capitalize">
                                                            <i class="bi {{ $typeIcon }} me-1"></i>{{ $lesson->type ?? 'lesson' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-nowrap small">
                                                        @if ($lesson->duration)
                                                            {{ $lesson->duration }} min
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $lesson->status === 'Published' ? 'success' : 'warning' }}">
                                                            {{ $lesson->status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        @if (count($previewAssets))
                                                            <div class="d-flex flex-wrap justify-content-end gap-1">
                                                                @foreach ($previewAssets as $asset)
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-primary btn-preview-material"
                                                                        data-title="{{ $lesson->title }} — {{ $asset['label'] }}"
                                                                        data-kind="{{ $asset['kind'] }}"
                                                                        data-url="{{ $asset['url'] }}">
                                                                        <i class="bi bi-eye me-1"></i>{{ $asset['label'] }}
                                                                    </button>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-muted small">No content</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        No lessons in this module yet.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5 px-3">
                                <i class="bi bi-inbox display-5 d-block mb-3"></i>
                                <p class="mb-3">No modules added yet.</p>
                                @can('update', $course)
                                    <a href="{{ route('educator.courses.crud.edit', $course) }}?action=content"
                                        class="btn btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i>Add Content
                                    </a>
                                @endcan
                            </div>
                        @endforelse
                    </div>
                </div>

                @if ($course->reviews->isNotEmpty())
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="bi bi-star-fill text-warning me-2"></i>Student Reviews</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($course->reviews->take(5) as $review)
                                <div class="pb-3 mb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <strong>{{ $review->user->name ?? 'Student' }}</strong>
                                            <div class="text-warning small">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i class="bi bi-star{{ $i < $review->rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                            @if ($review->comment)
                                                <p class="mb-0 mt-2 text-secondary">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                        <small class="text-muted text-nowrap">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Course Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="stat-row">
                            <span><i class="bi bi-collection me-2"></i>Modules</span>
                            <strong>{{ $course->sections_count }}</strong>
                        </div>
                        <div class="stat-row">
                            <span><i class="bi bi-play-circle me-2"></i>Lessons</span>
                            <strong>{{ $lessonCount }}</strong>
                        </div>
                        <div class="stat-row">
                            <span><i class="bi bi-people me-2"></i>Students</span>
                            <strong>{{ number_format($studentCount) }}</strong>
                        </div>
                        @if ($course->reviews->isNotEmpty())
                            <div class="stat-row border-0 pb-0 mb-0">
                                <span><i class="bi bi-star-fill text-warning me-2"></i>Rating</span>
                                <strong>{{ number_format($course->reviews->avg('rating'), 1) }} / 5</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Details</h6>
                    </div>
                    <div class="card-body">
                        @if ($course->level)
                            <div class="detail-item">
                                <small class="text-muted">Level</small>
                                <div class="fw-semibold">{{ $course->level }}</div>
                            </div>
                        @endif
                        @if ($course->language)
                            <div class="detail-item">
                                <small class="text-muted">Language</small>
                                <div class="fw-semibold">{{ $course->language }}</div>
                            </div>
                        @endif
                        @if ($course->duration)
                            <div class="detail-item">
                                <small class="text-muted">Duration</small>
                                <div class="fw-semibold">{{ $course->duration }}</div>
                            </div>
                        @endif
                        @if ($course->publish_date)
                            <div class="detail-item">
                                <small class="text-muted">Publish Date</small>
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($course->publish_date)->format('M d, Y') }}</div>
                            </div>
                        @endif
                        <div class="detail-item mb-0">
                            <small class="text-muted">Last Updated</small>
                            <div class="fw-semibold">{{ $course->updated_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0"><i class="bi bi-person-badge me-2"></i>Instructor</h6>
                    </div>
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="instructor-avatar">
                            {{ strtoupper(substr($course->educator->first_name ?? 'E', 0, 1)) }}
                        </div>
                        <div>
                            <strong>{{ $course->educator->full_name ?? $course->educator->name }}</strong>
                            <div class="small text-muted">{{ $course->educator->email }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content preview modal --}}
    <div class="modal fade" id="contentPreviewModal" tabindex="-1" aria-labelledby="contentPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contentPreviewModalLabel">Lesson Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="contentPreviewBody"></div>
                <div class="modal-footer">
                    <a href="#" id="contentPreviewOpenLink" class="btn btn-outline-primary" target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Open in new tab
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .course-hero-img {
                width: 100%;
                max-height: 280px;
                object-fit: cover;
            }

            .curriculum-section-header {
                background: #f8fafc;
            }

            .curriculum-table thead th {
                font-size: 0.82rem;
                text-transform: uppercase;
                letter-spacing: 0.03em;
                color: #64748b;
                white-space: nowrap;
            }

            .lesson-thumb {
                width: 56px;
                height: 42px;
                object-fit: cover;
                border-radius: 8px;
                border: 1px solid #e2e8f0;
                background: #f1f5f9;
            }

            .lesson-thumb--clickable {
                cursor: pointer;
                transition: transform 0.15s ease, box-shadow 0.15s ease;
            }

            .lesson-thumb--clickable:hover {
                transform: scale(1.05);
                box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
            }

            .lesson-thumb--placeholder {
                display: flex;
                align-items: center;
                justify-content: center;
                color: #64748b;
                font-size: 1.1rem;
            }

            .btn-preview-material {
                white-space: nowrap;
            }

            #contentPreviewBody .preview-pdf-wrap {
                background: #fff;
                min-height: 75vh;
            }

            #contentPreviewBody .preview-pdf-wrap object,
            #contentPreviewBody .preview-pdf-wrap iframe {
                width: 100%;
                height: 75vh;
                border: 0;
            }

            .stat-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-bottom: 0.85rem;
                margin-bottom: 0.85rem;
                border-bottom: 1px solid #eef2f7;
            }

            .detail-item {
                margin-bottom: 1rem;
            }

            .instructor-avatar {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background: #0d6efd;
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 1.1rem;
                flex-shrink: 0;
            }

            #contentPreviewBody {
                min-height: 200px;
                background: #f8fafc;
            }

            #contentPreviewBody .preview-video-wrap {
                background: #0f172a;
            }

            #contentPreviewBody video-player {
                display: block;
                width: 100%;
            }

            #contentPreviewBody .preview-iframe,
            #contentPreviewBody .preview-image {
                width: 100%;
            }

            #contentPreviewBody .preview-iframe {
                height: 75vh;
                border: 0;
            }

            #contentPreviewBody .preview-image {
                max-height: 75vh;
                object-fit: contain;
                display: block;
                margin: 0 auto;
            }
        </style>
    @endpush

    @push('scripts')
        <script type="module" src="https://cdn.jsdelivr.net/npm/@videojs/html/cdn/video-minimal.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('contentPreviewModal');
                const bodyEl = document.getElementById('contentPreviewBody');
                const titleEl = document.getElementById('contentPreviewModalLabel');
                const openLink = document.getElementById('contentPreviewOpenLink');
                let modalInstance = null;

                function escapeHtml(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                /**
                 * Open uploaded lesson content (video, material, worksheet) in the preview modal.
                 */
                window.previewLessonMaterial = function (title, kind, url) {
                    bodyEl.innerHTML = '';
                    titleEl.textContent = title || 'Lesson Content';
                    openLink.href = url || '#';
                    openLink.classList.toggle('d-none', !url);

                    const safeTitle = escapeHtml(title);
                    const safeUrl = escapeHtml(url);

                    if (kind === 'video' && url) {
                        bodyEl.style.background = '#0f172a';
                        bodyEl.innerHTML = `
                            <div class="preview-video-wrap p-2">
                                <video-player>
                                    <video-minimal-skin>
                                        <video data-src="${safeUrl}" playsinline preload="none"></video>
                                    </video-minimal-skin>
                                </video-player>
                            </div>`;

                        const video = bodyEl.querySelector('video[data-src]');
                        const loadVideo = function () {
                            if (!video || !video.dataset.src) return;
                            video.src = video.dataset.src;
                            video.removeAttribute('data-src');
                        };

                        video?.closest('video-player')?.addEventListener('pointerdown', loadVideo, { once: true, capture: true });
                        video?.addEventListener('play', loadVideo, { once: true });
                    } else if (kind === 'embed' && url) {
                        bodyEl.style.background = '#000';
                        bodyEl.innerHTML = `<iframe src="${safeUrl}" class="preview-iframe" allowfullscreen title="${safeTitle}"></iframe>`;
                    } else if (kind === 'pdf' && url) {
                        bodyEl.style.background = '#fff';
                        bodyEl.innerHTML = `
                            <div class="preview-pdf-wrap">
                                <object data="${safeUrl}" type="application/pdf">
                                    <iframe src="${safeUrl}" class="preview-iframe" title="${safeTitle}"></iframe>
                                </object>
                            </div>`;
                    } else if (kind === 'image' && url) {
                        bodyEl.style.background = '#f8fafc';
                        bodyEl.innerHTML = `<img src="${safeUrl}" alt="${safeTitle}" class="preview-image p-3">`;
                    } else if (url) {
                        bodyEl.style.background = '#f8fafc';
                        bodyEl.innerHTML = `
                            <div class="text-center py-5 px-3">
                                <i class="bi bi-file-earmark-arrow-down display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-1">Inline preview is not available for this file type.</p>
                                <p class="small text-muted mb-3">Use the button below to open or download the uploaded material.</p>
                                <a href="${safeUrl}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                                    Open Material
                                </a>
                            </div>`;
                    } else {
                        bodyEl.style.background = '#f8fafc';
                        bodyEl.innerHTML = `<p class="text-muted text-center py-5 mb-0">No preview available.</p>`;
                        openLink.classList.add('d-none');
                    }

                    modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modalInstance.show();
                };

                document.querySelectorAll('.btn-preview-material').forEach(function (button) {
                    button.addEventListener('click', function () {
                        previewLessonMaterial(
                            button.dataset.title,
                            button.dataset.kind,
                            button.dataset.url
                        );
                    });
                });

                modalEl.addEventListener('hidden.bs.modal', function () {
                    bodyEl.innerHTML = '';
                });
            });
        </script>
    @endpush
</x-educator-layout>
