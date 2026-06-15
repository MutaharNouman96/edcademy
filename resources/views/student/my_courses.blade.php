<x-student-layout>
    @push('styles')
        <style>
            /* ─── My Courses tokens (aligned with dashboard) ─── */
            :root {
                --mc-primary:      #6f42c1;
                --mc-primary-dark: #4b2a87;
                --mc-primary-50:   #f3e8ff;
                --mc-grad: linear-gradient(135deg,#7c3aed 0%,#6f42c1 55%,#4b2a87 100%);
                --mc-green:  #10b981;
                --mc-amber:  #f59e0b;
                --mc-blue:   #3b82f6;
                --mc-pink:   #ec4899;
            }

            /* ─── Page header ─────────────────────────────── */
            .mc-header {
                background: var(--mc-grad);
                border-radius: 20px;
                padding: 1.6rem 1.75rem;
                color: #fff;
                position: relative;
                overflow: hidden;
            }
            .mc-header::before {
                content: '';
                position: absolute;
                inset: 0;
                background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
            .mc-header-title { font-size: clamp(1.3rem, 3vw, 1.75rem); font-weight: 800; margin-bottom: .2rem; }
            .mc-header-sub   { font-size: .9rem; opacity: .85; }

            /* ─── KPI Cards ───────────────────────────────── */
            .mc-kpi {
                border: 0; border-radius: 18px; overflow: hidden; height: 100%;
                box-shadow: 0 4px 16px rgba(0,0,0,.05);
                transition: transform .2s, box-shadow .2s;
            }
            .mc-kpi:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(0,0,0,.1); }
            .mc-kpi-body { padding: 1.15rem 1.3rem; display: flex; align-items: center; gap: 1rem; }
            .mc-kpi-ico {
                width: 50px; height: 50px; border-radius: 15px; flex: 0 0 auto;
                display: inline-flex; align-items: center; justify-content: center;
                font-size: 1.35rem;
            }
            .mc-kpi-ico-purple { background: var(--mc-primary-50); color: var(--mc-primary); }
            .mc-kpi-ico-blue   { background: #dbeafe; color: var(--mc-blue); }
            .mc-kpi-ico-amber  { background: #fef3c7; color: var(--mc-amber); }
            .mc-kpi-value { font-size: 1.7rem; font-weight: 800; line-height: 1; letter-spacing: -.03em; }
            .mc-kpi-label { font-size: .8rem; color: #6b7280; font-weight: 500; margin-top: .2rem; }

            /* ─── Tabs ────────────────────────────────────── */
            .mc-tabs { border: 0; gap: .4rem; }
            .mc-tabs .nav-link {
                border: 0; border-radius: 12px;
                font-size: .9rem; font-weight: 600; color: #6b7280;
                padding: .55rem 1.1rem; transition: all .15s;
            }
            .mc-tabs .nav-link:hover { background: var(--mc-primary-50); color: var(--mc-primary); }
            .mc-tabs .nav-link.active { background: var(--mc-grad); color: #fff; box-shadow: 0 6px 16px rgba(111,66,193,.3); }

            /* ─── Course / Lesson cards ───────────────────── */
            .mc-card {
                border: 0; border-radius: 18px; overflow: hidden;
                box-shadow: 0 4px 16px rgba(0,0,0,.06);
                transition: transform .2s, box-shadow .2s;
                height: 100%;
            }
            .mc-card:hover { transform: translateY(-4px); box-shadow: 0 16px 36px rgba(0,0,0,.1); }
            .mc-thumb {
                width: 100%; aspect-ratio: 16/9; object-fit: cover;
                background: var(--mc-primary-50);
            }
            .mc-thumb-placeholder {
                width: 100%; aspect-ratio: 16/9;
                background: var(--mc-grad);
                display: flex; align-items: center; justify-content: center;
                color: rgba(255,255,255,.75); font-size: 2.6rem;
            }
            .mc-card-body { padding: 1rem 1.1rem 1.1rem; }
            .mc-subject {
                display: inline-block; font-size: .7rem; font-weight: 700;
                text-transform: uppercase; letter-spacing: .06em;
                color: var(--mc-primary); background: var(--mc-primary-50);
                border-radius: 999px; padding: .2rem .6rem; margin-bottom: .5rem;
            }
            .mc-title { font-size: .98rem; font-weight: 700; line-height: 1.35; margin-bottom: .35rem; }
            .mc-prog-bar {
                height: 6px; border-radius: 999px; background: #f3f4f6; overflow: hidden;
                margin: .65rem 0 .35rem;
            }
            .mc-prog-fill {
                height: 100%; border-radius: 999px;
                background: var(--mc-grad);
                transition: width .6s ease;
            }
            .mc-prog-text { font-size: .78rem; color: #6b7280; }
            .mc-prog-pct  { font-weight: 700; color: var(--mc-primary); }
            .btn-mc-resume {
                border-radius: 11px; font-size: .85rem; font-weight: 700;
                background: var(--mc-grad); color: #fff; border: 0;
                padding: .55rem 0; width: 100%;
                transition: opacity .15s;
            }
            .btn-mc-resume:hover { opacity: .9; color: #fff; }

            /* ─── Lesson type badges ──────────────────────── */
            .mc-type-badge {
                font-size: .7rem; font-weight: 700; border-radius: 999px;
                padding: .25rem .65rem; display: inline-flex; align-items: center; gap: .3rem;
            }
            .mc-type-video    { background: var(--mc-primary-50); color: var(--mc-primary); }
            .mc-type-worksheet{ background: #fce7f3; color: var(--mc-pink); }
            .mc-lesson-thumb {
                width: 100%; aspect-ratio: 16/9; border-radius: 14px;
                display: flex; align-items: center; justify-content: center;
                font-size: 2.6rem;
            }
            .mc-lesson-thumb-video    { background: var(--mc-primary-50); color: var(--mc-primary); }
            .mc-lesson-thumb-worksheet{ background: #fce7f3; color: var(--mc-pink); }
            .mc-meta { font-size: .8rem; color: #6b7280; }
            .mc-free-badge {
                font-size: .7rem; font-weight: 700; border-radius: 999px;
                padding: .2rem .55rem; background: #d1fae5; color: #065f46;
            }
            .btn-mc-action {
                border-radius: 11px; font-size: .85rem; font-weight: 700;
                padding: .5rem 0; width: 100%; border: 0;
            }
            .btn-mc-action-video { background: var(--mc-grad); color: #fff; }
            .btn-mc-action-video:hover { opacity: .9; color: #fff; }
            .btn-mc-action-worksheet { background: #fce7f3; color: var(--mc-pink); }
            .btn-mc-action-worksheet:hover { background: #fbcfe8; color: var(--mc-pink); }

            /* ─── Empty state ─────────────────────────────── */
            .mc-empty {
                text-align: center; padding: 3rem 1rem;
                border-radius: 18px; background: #f9fafb;
                border: 2px dashed #e5e7eb;
            }
            .mc-empty-ico { font-size: 3rem; color: #d1d5db; margin-bottom: .85rem; }
            .mc-empty-text { color: #6b7280; font-size: .92rem; margin-bottom: 1rem; }

            @media (max-width: 575.98px) {
                .mc-header { padding: 1.25rem; }
                .mc-kpi-value { font-size: 1.45rem; }
            }
        </style>
    @endpush

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="mc-header mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="mc-header-title">My Learning 📚</div>
                <div class="mc-header-sub">Track your progress and pick up right where you left off.</div>
            </div>
            <a href="{{ route('web.courses') }}" class="btn btn-light fw-bold" style="border-radius:12px;">
                <i class="bi bi-compass me-1"></i> Browse Courses
            </a>
        </div>
    </div>

    {{-- ── Stats ──────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card mc-kpi">
                <div class="mc-kpi-body">
                    <span class="mc-kpi-ico mc-kpi-ico-purple"><i class="bi bi-bag-check"></i></span>
                    <div>
                        <div class="mc-kpi-value">{{ $stats['total'] }}</div>
                        <div class="mc-kpi-label">Items Purchased</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card mc-kpi">
                <div class="mc-kpi-body">
                    <span class="mc-kpi-ico mc-kpi-ico-purple"><i class="bi bi-journal-richtext"></i></span>
                    <div>
                        <div class="mc-kpi-value">{{ $stats['courses'] }}</div>
                        <div class="mc-kpi-label">Courses</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card mc-kpi">
                <div class="mc-kpi-body">
                    <span class="mc-kpi-ico mc-kpi-ico-blue"><i class="bi bi-play-circle"></i></span>
                    <div>
                        <div class="mc-kpi-value">{{ $stats['videos'] }}</div>
                        <div class="mc-kpi-label">Video Lessons</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card mc-kpi">
                <div class="mc-kpi-body">
                    <span class="mc-kpi-ico mc-kpi-ico-amber"><i class="bi bi-file-earmark-text"></i></span>
                    <div>
                        <div class="mc-kpi-value">{{ $stats['worksheets'] }}</div>
                        <div class="mc-kpi-label">Worksheets</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filter tabs ────────────────────────────────────────── --}}
    <ul class="nav mc-tabs mb-4" role="tablist" id="mc-filters">
        <li class="nav-item">
            <button type="button" class="nav-link active" data-filter="all">
                <i class="bi bi-grid-1x2 me-1"></i> All <span class="opacity-75">({{ $stats['total'] }})</span>
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" data-filter="course">
                <i class="bi bi-journal-richtext me-1"></i> Courses <span class="opacity-75">({{ $stats['courses'] }})</span>
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" data-filter="video">
                <i class="bi bi-play-circle me-1"></i> Videos <span class="opacity-75">({{ $stats['videos'] }})</span>
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" data-filter="worksheet">
                <i class="bi bi-file-earmark-text me-1"></i> Worksheets <span class="opacity-75">({{ $stats['worksheets'] }})</span>
            </button>
        </li>
    </ul>

    {{-- ── Unified grid (sorted: courses → videos → worksheets) ── --}}
    @if ($stats['total'] > 0)
        <div class="row g-3" id="mc-grid">

            {{-- COURSES --}}
            @foreach ($courses as $course)
                <div class="col-sm-6 col-lg-4 mc-item" data-type="course">
                    <div class="card mc-card">
                        @if ($course->thumbnail)
                            <img src="{{ $course->thumbnail }}" class="mc-thumb" alt="{{ $course->title }}">
                        @else
                            <div class="mc-thumb-placeholder">
                                <i class="bi bi-book"></i>
                            </div>
                        @endif

                        <div class="mc-card-body d-flex flex-column">
                            <span class="mc-subject">{{ $course->subject ?? 'General' }}</span>
                            <h6 class="mc-title">{{ $course->title }}</h6>

                            <div class="mc-prog-bar">
                                <div class="mc-prog-fill" style="width: {{ round(($course->progress ?? 0) * 100) }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between mc-prog-text mb-3">
                                <span><span class="mc-prog-pct">{{ round(($course->progress ?? 0) * 100) }}%</span> complete</span>
                                <span><i class="bi bi-clock me-1"></i>{{ $course->hours_watched ?? 0 }} h</span>
                            </div>

                            <div class="mt-auto">
                                <a href="{{ route('student.course_details', $course->id) }}" class="btn btn-mc-resume">
                                    <i class="bi bi-play-fill me-1"></i> Resume
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- VIDEO LESSONS --}}
            @foreach ($videoLessons as $lesson)
                <div class="col-sm-6 col-lg-4 mc-item" data-type="video">
                    <div class="card mc-card">
                        <div class="mc-card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="mc-type-badge mc-type-video"><i class="bi bi-play-circle-fill"></i> Video</span>
                                @if ($lesson->preview)
                                    <span class="text-info small fw-semibold"><i class="bi bi-eye me-1"></i>Preview</span>
                                @endif
                            </div>

                            <div class="mc-lesson-thumb mc-lesson-thumb-video mb-3">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>

                            <h6 class="mc-title">{{ $lesson->title }}</h6>

                            @if ($lesson->course)
                                <div class="mc-meta mb-2">
                                    <i class="bi bi-journal-bookmark me-1"></i>{{ $lesson->course->title }}
                                </div>
                            @endif
                            <div class="d-flex align-items-center gap-3 mc-meta mb-3">
                                @if ($lesson->duration)
                                    <span><i class="bi bi-clock me-1"></i>{{ $lesson->duration }} min</span>
                                @endif
                                @if ($lesson->price && !$lesson->free)
                                    <span class="fw-semibold text-dark">${{ number_format($lesson->price, 2) }}</span>
                                @elseif($lesson->free)
                                    <span class="mc-free-badge">Free</span>
                                @endif
                            </div>

                            <div class="mt-auto">
                                @if ($lesson->course)
                                    <a href="{{ route('student.course_details', ['course_id' => $lesson->course->id, 'lesson_id' => $lesson->id]) }}"
                                        class="btn btn-mc-action btn-mc-action-video">
                                        <i class="bi bi-play-fill me-1"></i> Watch Video
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- WORKSHEET LESSONS --}}
            @foreach ($worksheetLessons as $lesson)
                <div class="col-sm-6 col-lg-4 mc-item" data-type="worksheet">
                    <div class="card mc-card">
                        <div class="mc-card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="mc-type-badge mc-type-worksheet"><i class="bi bi-file-earmark-fill"></i> Worksheet</span>
                                @if ($lesson->preview)
                                    <span class="text-info small fw-semibold"><i class="bi bi-eye me-1"></i>Preview</span>
                                @endif
                            </div>

                            <div class="mc-lesson-thumb mc-lesson-thumb-worksheet mb-3">
                                <i class="bi bi-file-earmark-fill"></i>
                            </div>

                            <h6 class="mc-title">{{ $lesson->title }}</h6>

                            @if ($lesson->course)
                                <div class="mc-meta mb-2">
                                    <i class="bi bi-journal-bookmark me-1"></i>{{ $lesson->course->title }}
                                </div>
                            @endif
                            <div class="d-flex align-items-center gap-3 mc-meta mb-3">
                                @if ($lesson->price && !$lesson->free)
                                    <span class="fw-semibold text-dark">${{ number_format($lesson->price, 2) }}</span>
                                @elseif($lesson->free)
                                    <span class="mc-free-badge">Free</span>
                                @endif
                            </div>

                            <div class="mt-auto">
                                @if ($lesson->worksheets)
                                    <a href="{{ $lesson->worksheets_path }}" class="btn btn-mc-action btn-mc-action-worksheet" download>
                                        <i class="bi bi-download me-1"></i> Download Worksheet
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Shown by JS when the active filter has no items --}}
        <div class="mc-empty d-none" id="mc-filter-empty">
            <div class="mc-empty-ico"><i class="bi bi-funnel"></i></div>
            <div class="fw-semibold mb-1">Nothing here yet</div>
            <div class="mc-empty-text">You don't have any items in this category.</div>
        </div>
    @else
        {{-- Global empty state --}}
        <div class="mc-empty">
            <div class="mc-empty-ico"><i class="bi bi-journal-x"></i></div>
            <div class="fw-semibold mb-1">No purchases yet</div>
            <div class="mc-empty-text">Explore our catalog and start your learning journey today.</div>
            <a href="{{ route('web.courses') }}" class="btn btn-mc-resume d-inline-block px-4" style="width:auto;">
                <i class="bi bi-compass me-1"></i> Browse Courses
            </a>
        </div>
    @endif

    @push('scripts')
        <script>
            (function () {
                const filterBar = document.getElementById('mc-filters');
                const grid = document.getElementById('mc-grid');
                if (!filterBar || !grid) return;

                const items = Array.from(grid.querySelectorAll('.mc-item'));
                const emptyNote = document.getElementById('mc-filter-empty');

                function applyFilter(type) {
                    let visible = 0;
                    items.forEach(function (el) {
                        const show = type === 'all' || el.dataset.type === type;
                        el.style.display = show ? '' : 'none';
                        if (show) visible++;
                    });
                    if (emptyNote) emptyNote.classList.toggle('d-none', visible > 0);
                }

                filterBar.querySelectorAll('button[data-filter]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        filterBar.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        applyFilter(btn.dataset.filter);
                    });
                });
            })();
        </script>
    @endpush
</x-student-layout>
