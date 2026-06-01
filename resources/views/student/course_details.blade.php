<x-student-layout>

    @php
        $allLessonsSorted = $course_lessons->sortBy('id')->values();
        $totalLessons = $allLessonsSorted->count();
        $currentIndex = $currentLesson
            ? $allLessonsSorted->search(fn($l) => $l->id === $currentLesson->id)
            : false;
        $currentIndex = $currentIndex === false ? -1 : $currentIndex;
        $progressPct = $totalLessons > 0 && $currentIndex >= 0
            ? (int) round((($currentIndex + 1) / $totalLessons) * 100)
            : 0;
        $upcomingLessons = $currentIndex >= 0
            ? $allLessonsSorted->slice($currentIndex + 1)->take(3)
            : collect();

        $lessonIcon = fn($type) => match ($type) {
            'video' => 'bi-play-circle',
            'worksheet' => 'bi-file-earmark-text',
            'material' => 'bi-folder',
            default => 'bi-file-earmark',
        };
    @endphp

    {{-- ── Lesson top bar ─────────────────────────────────────────── --}}
    <div class="cd-topbar mb-3">
        <a href="{{ route('student.my-courses') }}" class="cd-back" aria-label="Back to my courses">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="min-w-0 flex-grow-1">
            <div class="cd-course-title text-truncate">{{ $course->title }}</div>
            <div class="cd-course-meta text-truncate">
                <i class="bi bi-person-video3 me-1"></i>{{ trim(($educator->first_name ?? '') . ' ' . ($educator->last_name ?? '')) ?: 'Educator' }}
                <span class="mx-1">&bull;</span>{{ $totalLessons }} lessons
            </div>
        </div>
        <button class="btn cd-content-toggle d-xl-none" type="button" id="cdContentToggle"
            aria-label="Open course content">
            <i class="bi bi-list-ul"></i>
            <span class="d-none d-sm-inline ms-1">Content</span>
        </button>
    </div>

    <div class="cd-layout">
        {{-- ── Main column ────────────────────────────────────────── --}}
        <div class="cd-main">
            @if ($currentLesson)
                {{-- Player (sticks to top on mobile while scrolling) --}}
                @php $isStickyMedia = $currentLesson->type === 'video' && $currentLesson->lesson_video_path; @endphp
                <div class="cd-player-wrap mb-3 {{ $isStickyMedia ? 'cd-player-sticky' : '' }}">
                    <div class="cd-media position-relative">
                        @if ($currentLesson->type === 'video' && $currentLesson->lesson_video_path)
                            @if ($currentLesson->uses_direct_video_player)
                                <div class="lesson-video-player">
                                    <video-player>
                                        <video-minimal-skin>
                                            <video src="{{ $currentLesson->lesson_video_path }}" playsinline
                                                preload="metadata"></video>
                                        </video-minimal-skin>
                                    </video-player>
                                </div>
                            @else
                                <div class="cd-ratio">
                                    <iframe src="{{ $currentLesson->lesson_video_path }}" frameborder="0"
                                        allowfullscreen class="cd-ratio-item"></iframe>
                                </div>
                            @endif
                        @elseif ($currentLesson->type === 'worksheet' && $currentLesson->worksheets_path)
                            <div class="p-3 p-md-4">
                                @include('student.partials.lesson_asset_viewer', [
                                    'url' => $currentLesson->worksheets_path,
                                    'downloadLabel' => 'Download Worksheet',
                                ])
                            </div>
                        @elseif ($currentLesson->type === 'material' && $currentLesson->materials_path)
                            <div class="p-3 p-md-4">
                                @include('student.partials.lesson_asset_viewer', [
                                    'url' => $currentLesson->materials_path,
                                    'downloadLabel' => 'Download Material',
                                ])
                            </div>
                        @else
                            <div class="cd-ratio bg-dark d-flex align-items-center justify-content-center">
                                <div class="text-center text-white-50">
                                    <i class="bi bi-camera-video-off fs-1 d-block mb-2"></i>
                                    No preview available for this lesson type.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Lesson info --}}
                <div class="card cd-card mb-3">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="cd-type-badge cd-type-{{ $currentLesson->type }}">
                                <i class="bi {{ $lessonIcon($currentLesson->type) }} me-1"></i>{{ ucfirst($currentLesson->type) }}
                            </span>
                            <span class="cd-pos-badge">
                                Lesson {{ $currentLesson->lesson_number }} of
                                {{ $course_lessons->where('course_section_id', $currentLesson->course_section_id)->count() }}
                            </span>
                            @if ($currentLesson->duration)
                                <span class="cd-dur-badge"><i class="bi bi-clock me-1"></i>{{ gmdate('i:s', $currentLesson->duration) }}</span>
                            @endif
                        </div>

                        <h1 class="cd-lesson-title">{{ $currentLesson->title }}</h1>

                        <div class="d-flex align-items-center gap-2 cd-educator">
                            <img src="{{ $educator->profile_picture ? asset($educator->profile_picture) : 'https://placehold.co/40x40/6f42c1/white?text=E' }}"
                                class="rounded-circle" alt="Educator" width="38" height="38">
                            <div class="min-w-0">
                                <div class="fw-semibold text-truncate" style="font-size:.9rem;">
                                    {{ trim(($educator->first_name ?? '') . ' ' . ($educator->last_name ?? '')) ?: 'Unknown Educator' }}
                                </div>
                                <div class="text-muted" style="font-size:.78rem;">
                                    Uploaded {{ $course->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>

                        @if ($currentLesson->notes)
                            <hr class="cd-divider">
                            <h2 class="cd-section-h">Notes</h2>
                            <p class="cd-notes mb-0">{{ $currentLesson->notes }}</p>
                        @endif
                    </div>
                </div>

                {{-- Up Next (mobile/tablet — under the player) --}}
                @if ($upcomingLessons->count() > 0)
                    <div class="card cd-card mb-3 d-xl-none">
                        <div class="card-body p-3">
                            <h2 class="cd-section-h mb-3">Up Next</h2>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($upcomingLessons as $lesson)
                                    <a href="{{ route('student.course_details', ['course_id' => $course->id, 'lesson_id' => $lesson->id]) }}"
                                        class="cd-upnext-item">
                                        <span class="cd-upnext-ico"><i class="bi {{ $lessonIcon($lesson->type) }}"></i></span>
                                        <span class="flex-grow-1 min-w-0">
                                            <span class="cd-upnext-title text-truncate d-block">{{ $lesson->title }}</span>
                                            <span class="cd-upnext-meta">{{ gmdate('i:s', $lesson->duration) }}</span>
                                        </span>
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Comments --}}
                <div class="card cd-card">
                    <div class="card-body p-3 p-md-4">
                        <form id="comment-form" action="{{ route('student.lesson_comment.store') }}">
                            @csrf
                            <input type="hidden" name="lesson_id" value="{{ $currentLesson->id }}">
                            <label class="cd-section-h mb-2 d-block">Add a comment</label>
                            <div class="mb-2">
                                <textarea class="form-control cd-textarea" name="comment" rows="3"
                                    placeholder="Share your thoughts or ask a question…"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary cd-btn">
                                <i class="bi bi-send me-1"></i>Post Comment
                            </button>
                        </form>

                        <hr class="cd-divider">

                        <h2 class="cd-section-h mb-3">
                            Comments <span class="cd-count">({{ $comments->count() }})</span>
                        </h2>
                        <div id="comments-list" class="d-flex flex-column gap-3">
                            @forelse ($comments as $comment)
                                <div class="cd-comment d-flex align-items-start gap-2">
                                    <img src="{{ $comment->user->profile_picture ?? 'https://placehold.co/40x40/6f42c1/white?text=U' }}"
                                        class="rounded-circle flex-shrink-0" alt="User" width="40" height="40">
                                    <div class="min-w-0">
                                        <div class="cd-comment-head">
                                            {{ $comment->user->first_name . ' ' . $comment->user->last_name }}
                                            <span class="cd-comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="cd-comment-body mb-0">{{ $comment->comment }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small" id="no-comments">Be the first to comment on this lesson.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <div class="card cd-card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted d-block mb-3"></i>
                        <h4 class="fw-bold mb-1">No lesson found</h4>
                        <p class="text-muted mb-0">This course doesn't have any available lessons yet.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- ── Course content panel (sidebar on xl, drawer below) ──── --}}
        <aside class="cd-content" id="cdContent">
            <div class="cd-content-inner">
                <div class="cd-content-header">
                    <h2 class="cd-content-title mb-0">Course Content</h2>
                    <button class="btn cd-content-close d-xl-none" type="button" id="cdContentClose"
                        aria-label="Close course content">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="cd-progress-wrap">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="cd-progress-label">Your progress</span>
                        <span class="cd-progress-pct">{{ $progressPct }}%</span>
                    </div>
                    <div class="cd-progress">
                        <div class="cd-progress-bar" style="width: {{ $progressPct }}%"></div>
                    </div>
                </div>

                <div class="accordion cd-accordion" id="chaptersAccordion">
                    @forelse ($course_chapters as $c)
                        @php
                            $chapterLessons = $course_lessons->where('course_section_id', $c->id)->sortBy('id');
                            $isActiveChapter = $currentLesson && $c->id == $currentLesson->course_section_id;
                        @endphp
                        <div class="accordion-item cd-chapter">
                            <h3 class="accordion-header" id="heading{{ $c->id }}">
                                <button class="accordion-button cd-chapter-btn {{ $isActiveChapter ? '' : 'collapsed' }}"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $c->id }}"
                                    aria-expanded="{{ $isActiveChapter ? 'true' : 'false' }}"
                                    aria-controls="collapse{{ $c->id }}">
                                    <span class="cd-chapter-name">{{ $c->title }}</span>
                                    <span class="cd-chapter-count">{{ $chapterLessons->count() }} lessons</span>
                                </button>
                            </h3>
                            <div id="collapse{{ $c->id }}"
                                class="accordion-collapse collapse {{ $isActiveChapter ? 'show' : '' }}"
                                aria-labelledby="heading{{ $c->id }}" data-bs-parent="#chaptersAccordion">
                                <div class="accordion-body p-0">
                                    @foreach ($chapterLessons as $lesson)
                                        @php $isActiveLesson = $currentLesson && $lesson->id == $currentLesson->id; @endphp
                                        <a href="{{ route('student.course_details', ['course_id' => $course->id, 'lesson_id' => $lesson->id]) }}"
                                            class="cd-lesson-link {{ $isActiveLesson ? 'active' : '' }}">
                                            <span class="cd-lesson-ico">
                                                <i class="bi {{ $isActiveLesson ? 'bi-play-fill' : $lessonIcon($lesson->type) }}"></i>
                                            </span>
                                            <span class="flex-grow-1 min-w-0">
                                                <span class="cd-lesson-name">{{ $lesson->title }}</span>
                                                <span class="cd-lesson-sub">
                                                    <span class="text-capitalize">{{ $lesson->type }}</span>
                                                    @if ($lesson->duration)
                                                        <span class="mx-1">&bull;</span>{{ gmdate('i:s', $lesson->duration) }}
                                                    @endif
                                                </span>
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small p-3">No chapters found.</div>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>

    {{-- Drawer overlay (mobile only) --}}
    <div class="cd-overlay" id="cdOverlay"></div>

    @if ($currentLesson && $currentLesson->type === 'video' && $currentLesson->uses_direct_video_player)
        @push('scripts')
            <script type="module" src="https://cdn.jsdelivr.net/npm/@videojs/html/cdn/video-minimal.js"></script>
        @endpush
    @endif

    @push('styles')
        <style>
            :root {
                --cd-primary: #6f42c1;
                --cd-primary-dark: #4b2a87;
                --cd-primary-50: #f3e8ff;
                --cd-grad: linear-gradient(135deg, #7c3aed 0%, #6f42c1 55%, #4b2a87 100%);
                --cd-border: rgba(17, 24, 39, .08);
            }

            /* ── Top bar ── */
            .cd-topbar {
                display: flex;
                align-items: center;
                gap: .75rem;
                background: #fff;
                border: 1px solid var(--cd-border);
                border-radius: 16px;
                padding: .7rem .9rem;
                box-shadow: 0 4px 16px rgba(0, 0, 0, .04);
            }
            .cd-back {
                width: 40px; height: 40px; flex: 0 0 auto;
                border-radius: 11px;
                display: inline-flex; align-items: center; justify-content: center;
                background: var(--cd-primary-50); color: var(--cd-primary-dark);
                text-decoration: none; font-size: 1.1rem;
                transition: background .15s;
            }
            .cd-back:hover { background: #e9d5ff; color: var(--cd-primary-dark); }
            .cd-course-title { font-size: 1.02rem; font-weight: 800; line-height: 1.2; color: #1f2937; }
            .cd-course-meta { font-size: .8rem; color: #6b7280; }
            .cd-content-toggle {
                flex: 0 0 auto;
                border-radius: 11px; font-weight: 700; font-size: .85rem;
                background: var(--cd-grad); color: #fff; border: 0;
                padding: .5rem .8rem;
            }
            .cd-content-toggle:hover { color: #fff; opacity: .9; }

            /* ── Layout ── */
            .cd-layout { display: flex; align-items: flex-start; gap: 1rem; }
            .cd-main { flex: 1 1 auto; min-width: 0; }

            /* ── Cards ── */
            .cd-card { border: 0; border-radius: 18px; box-shadow: 0 4px 20px rgba(0, 0, 0, .05); overflow: hidden; }

            /* ── Player wrap / media ── */
            .cd-player-wrap { border-radius: 18px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, .05); }
            .cd-media { background: #000; }
            .cd-ratio { position: relative; width: 100%; aspect-ratio: 16 / 9; background: #000; }
            .cd-ratio-item { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
            .lesson-video-player { width: 100%; aspect-ratio: 16 / 9; background: #000; }
            .lesson-video-player video-player { display: block; width: 100%; height: 100%; }
            .cd-media .lesson-asset-viewer object,
            .cd-media .lesson-asset-viewer img { width: 100%; }

            /* ── Badges ── */
            .cd-type-badge, .cd-pos-badge, .cd-dur-badge {
                font-size: .74rem; font-weight: 700; border-radius: 999px;
                padding: .25rem .65rem; display: inline-flex; align-items: center;
            }
            .cd-type-video { background: #ede9fe; color: #6d28d9; }
            .cd-type-worksheet { background: #fce7f3; color: #be185d; }
            .cd-type-material { background: #fef3c7; color: #92400e; }
            .cd-pos-badge { background: #f3f4f6; color: #4b5563; }
            .cd-dur-badge { background: #e0f2fe; color: #075985; }

            .cd-lesson-title { font-size: 1.3rem; font-weight: 800; line-height: 1.25; color: #1f2937; margin: .25rem 0 1rem; }
            .cd-educator img { object-fit: cover; }
            .cd-divider { border-color: var(--cd-border); margin: 1.1rem 0; }
            .cd-section-h { font-size: 1rem; font-weight: 700; color: #1f2937; }
            .cd-notes { color: #4b5563; font-size: .92rem; line-height: 1.7; white-space: pre-line; }
            .cd-count { color: #9ca3af; font-weight: 600; }

            /* ── Textarea / button ── */
            .cd-textarea { border-radius: 12px; border-color: var(--cd-border); font-size: .92rem; resize: vertical; }
            .cd-textarea:focus { border-color: var(--cd-primary); box-shadow: 0 0 0 .2rem rgba(111, 66, 193, .15); }
            .cd-btn { border-radius: 11px; font-weight: 700; }

            /* ── Comments ── */
            .cd-comment img { object-fit: cover; }
            .cd-comment-head { font-size: .9rem; font-weight: 700; color: #1f2937; }
            .cd-comment-time { font-size: .76rem; font-weight: 500; color: #9ca3af; margin-left: .4rem; }
            .cd-comment-body { font-size: .9rem; color: #4b5563; line-height: 1.5; word-break: break-word; }

            /* ── Up next ── */
            .cd-upnext-item {
                display: flex; align-items: center; gap: .7rem;
                padding: .6rem .7rem; border-radius: 12px;
                text-decoration: none; color: inherit; border: 1px solid var(--cd-border);
                transition: background .15s, border-color .15s;
            }
            .cd-upnext-item:hover { background: #faf9ff; border-color: rgba(111, 66, 193, .25); }
            .cd-upnext-ico {
                width: 36px; height: 36px; flex: 0 0 auto; border-radius: 10px;
                background: var(--cd-primary-50); color: var(--cd-primary);
                display: inline-flex; align-items: center; justify-content: center;
            }
            .cd-upnext-title { font-size: .88rem; font-weight: 600; color: #1f2937; }
            .cd-upnext-meta { font-size: .76rem; color: #9ca3af; }

            /* ── Content panel ── */
            .cd-content { flex: 0 0 340px; width: 340px; }
            .cd-content-inner {
                position: sticky; top: calc(var(--s-header-h, 64px) + 1rem);
                max-height: calc(100dvh - var(--s-header-h, 64px) - 2rem);
                overflow-y: auto;
                background: #fff; border: 1px solid var(--cd-border);
                border-radius: 18px; padding: 1rem;
                box-shadow: 0 4px 20px rgba(0, 0, 0, .05);
            }
            .cd-content-inner::-webkit-scrollbar { width: 6px; }
            .cd-content-inner::-webkit-scrollbar-thumb { background: rgba(17, 24, 39, .12); border-radius: 99px; }
            .cd-content-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
            .cd-content-title { font-size: 1.05rem; font-weight: 800; color: #1f2937; }
            .cd-content-close {
                width: 36px; height: 36px; border-radius: 10px; padding: 0;
                display: inline-flex; align-items: center; justify-content: center;
                background: #f3f4f6; border: 0; color: #4b5563;
            }

            /* ── Progress ── */
            .cd-progress-wrap { margin-bottom: 1.1rem; }
            .cd-progress-label { font-size: .8rem; color: #6b7280; font-weight: 500; }
            .cd-progress-pct { font-size: .8rem; font-weight: 800; color: var(--cd-primary); }
            .cd-progress { height: 8px; border-radius: 999px; background: #f3f4f6; overflow: hidden; }
            .cd-progress-bar { height: 100%; border-radius: 999px; background: var(--cd-grad); transition: width .6s ease; }

            /* ── Accordion ── */
            .cd-accordion .accordion-item { border: 0; margin-bottom: .5rem; background: transparent; }
            .cd-chapter-btn {
                border-radius: 12px !important;
                background: #f9fafb; padding: .7rem .85rem;
                box-shadow: none; font-weight: 700; color: #1f2937;
                display: flex; flex-direction: column; align-items: flex-start; gap: .15rem;
            }
            .cd-chapter-btn:not(.collapsed) { background: var(--cd-primary-50); color: var(--cd-primary-dark); }
            .cd-chapter-btn:focus { box-shadow: none; border: 0; }
            .cd-chapter-btn::after { position: absolute; right: .85rem; top: 1rem; }
            .cd-chapter-name { font-size: .9rem; line-height: 1.3; padding-right: 1.5rem; }
            .cd-chapter-count { font-size: .72rem; font-weight: 600; color: #9ca3af; }
            .cd-chapter-btn:not(.collapsed) .cd-chapter-count { color: var(--cd-primary); }

            .cd-lesson-link {
                display: flex; align-items: center; gap: .65rem;
                padding: .6rem .7rem; margin: .25rem .25rem;
                border-radius: 10px; text-decoration: none; color: #4b5563;
                transition: background .15s;
            }
            .cd-lesson-link:hover { background: #f3f4f6; color: #1f2937; }
            .cd-lesson-link.active { background: var(--cd-primary-50); color: var(--cd-primary-dark); }
            .cd-lesson-ico {
                width: 30px; height: 30px; flex: 0 0 auto; border-radius: 8px;
                background: #fff; border: 1px solid var(--cd-border);
                display: inline-flex; align-items: center; justify-content: center; font-size: .9rem;
            }
            .cd-lesson-link.active .cd-lesson-ico { background: var(--cd-grad); color: #fff; border-color: transparent; }
            .cd-lesson-name { display: block; font-size: .86rem; font-weight: 600; line-height: 1.3; }
            .cd-lesson-sub { display: block; font-size: .74rem; color: #9ca3af; }

            /* ── Overlay ── */
            .cd-overlay {
                position: fixed; inset: 0; background: rgba(17, 24, 39, .5);
                -webkit-backdrop-filter: blur(3px); backdrop-filter: blur(3px);
                z-index: 1055; opacity: 0; visibility: hidden;
                transition: opacity .3s, visibility .3s;
            }
            .cd-overlay.show { opacity: 1; visibility: visible; }

            /* ── Drawer behaviour < xl ── */
            @media (max-width: 1199.98px) {
                .cd-content {
                    position: fixed; top: 0; right: 0; bottom: 0;
                    width: 340px; max-width: 88vw; flex: none;
                    z-index: 1060; transform: translateX(100%);
                    transition: transform .32s cubic-bezier(.4, 0, .2, 1);
                }
                .cd-content.show { transform: translateX(0); }
                .cd-content-inner {
                    position: static; top: auto; height: 100%; max-height: 100%;
                    border-radius: 0; border: 0; box-shadow: none;
                    padding: 1rem 1rem 2rem;
                }
                body.cd-drawer-open { overflow: hidden; }
            }

            /* ── Sticky video on mobile (YouTube-style) ── */
            @media (max-width: 991.98px) {
                .cd-player-sticky {
                    position: sticky;
                    top: var(--s-header-h, 64px);
                    z-index: 1020;
                    border-radius: 0;
                    /* full-bleed edge-to-edge while pinned */
                    margin-left: -1.15rem;
                    margin-right: -1.15rem;
                    box-shadow: 0 10px 24px rgba(0, 0, 0, .18);
                }
            }

            @media (max-width: 575.98px) {
                .cd-lesson-title { font-size: 1.12rem; }
                .cd-topbar { padding: .6rem .7rem; }
                .cd-course-title { font-size: .92rem; }
                .cd-player-sticky {
                    margin-left: -.85rem;
                    margin-right: -.85rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // ── Course-content drawer (mobile/tablet) ──
                const panel = document.getElementById('cdContent');
                const overlay = document.getElementById('cdOverlay');
                const toggle = document.getElementById('cdContentToggle');
                const closeBtn = document.getElementById('cdContentClose');

                const openDrawer = () => {
                    panel?.classList.add('show');
                    overlay?.classList.add('show');
                    document.body.classList.add('cd-drawer-open');
                };
                const closeDrawer = () => {
                    panel?.classList.remove('show');
                    overlay?.classList.remove('show');
                    document.body.classList.remove('cd-drawer-open');
                };

                toggle?.addEventListener('click', openDrawer);
                closeBtn?.addEventListener('click', closeDrawer);
                overlay?.addEventListener('click', closeDrawer);
                document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });
                window.addEventListener('resize', () => { if (window.innerWidth >= 1200) closeDrawer(); });

                // Close drawer when a lesson is tapped on small screens
                panel?.querySelectorAll('.cd-lesson-link').forEach(link => {
                    link.addEventListener('click', () => { if (window.innerWidth < 1200) closeDrawer(); });
                });

                // ── Comments AJAX ──
                const form = document.getElementById('comment-form');
                if (form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const formData = new FormData(form);
                        const list = document.getElementById('comments-list');
                        const textarea = form.querySelector('textarea[name="comment"]');
                        const tokenEl = form.querySelector('input[name="_token"]');
                        const submitBtn = form.querySelector('button[type="submit"]');

                        if (!textarea.value.trim()) return;
                        submitBtn.disabled = true;

                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': tokenEl ? tokenEl.value : '',
                                'Accept': 'application/json'
                            }
                        })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('no-comments')?.remove();
                                    const c = data.comment;
                                    const safe = s => String(s ?? '')
                                        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                                    const html = `
                                        <div class="cd-comment d-flex align-items-start gap-2">
                                            <img src="${safe(c.user_profile_picture)}" class="rounded-circle flex-shrink-0" alt="User" width="40" height="40">
                                            <div class="min-w-0">
                                                <div class="cd-comment-head">${safe(c.user_name)}<span class="cd-comment-time">${safe(c.created_at_human)}</span></div>
                                                <p class="cd-comment-body mb-0">${safe(c.comment_text)}</p>
                                            </div>
                                        </div>`;
                                    list.insertAdjacentHTML('afterbegin', html);
                                    textarea.value = '';
                                } else {
                                    alert('Error posting comment.');
                                }
                            })
                            .catch(err => {
                                console.error('Error:', err);
                                alert('An error occurred while posting your comment.');
                            })
                            .finally(() => { submitBtn.disabled = false; });
                    });
                }
            });
        </script>
    @endpush

</x-student-layout>
