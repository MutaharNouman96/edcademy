<x-student-layout>
    @push('styles')
        <style>
            /* ─── New Videos tokens (aligned with dashboard) ─── */
            :root {
                --nv-primary:      #6f42c1;
                --nv-primary-dark: #4b2a87;
                --nv-primary-50:   #f3e8ff;
                --nv-grad: linear-gradient(135deg,#7c3aed 0%,#6f42c1 55%,#4b2a87 100%);
            }

            /* ─── Page header ─────────────────────────────── */
            .nv-header {
                background: var(--nv-grad);
                border-radius: 20px;
                padding: 1.6rem 1.75rem;
                color: #fff;
                position: relative;
                overflow: hidden;
            }
            .nv-header::before {
                content: '';
                position: absolute;
                inset: 0;
                background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
            .nv-header-title { font-size: clamp(1.3rem, 3vw, 1.75rem); font-weight: 800; margin-bottom: .2rem; }
            .nv-header-sub   { font-size: .9rem; opacity: .85; }

            /* ─── Video cards ─────────────────────────────── */
            .nv-card {
                border: 0; border-radius: 18px; overflow: hidden;
                box-shadow: 0 4px 16px rgba(0,0,0,.06);
                transition: transform .2s, box-shadow .2s;
                height: 100%;
            }
            .nv-card:hover { transform: translateY(-4px); box-shadow: 0 16px 36px rgba(0,0,0,.1); }
            .nv-thumb-wrap { position: relative; }
            .nv-thumb {
                width: 100%; aspect-ratio: 16/9; object-fit: cover;
                background: var(--nv-primary-50); display: block;
            }
            .nv-thumb-placeholder {
                width: 100%; aspect-ratio: 16/9;
                background: var(--nv-grad);
                display: flex; align-items: center; justify-content: center;
                color: rgba(255,255,255,.8); font-size: 2.8rem;
            }
            .nv-play-overlay {
                position: absolute; inset: 0;
                display: flex; align-items: center; justify-content: center;
                background: rgba(17,24,39,.18);
                opacity: 0; transition: opacity .2s;
            }
            .nv-card:hover .nv-play-overlay { opacity: 1; }
            .nv-play-btn {
                width: 56px; height: 56px; border-radius: 50%;
                background: rgba(255,255,255,.92); color: var(--nv-primary);
                display: flex; align-items: center; justify-content: center;
                font-size: 1.6rem; box-shadow: 0 6px 18px rgba(0,0,0,.25);
            }
            .nv-when-badge {
                position: absolute; top: .65rem; left: .65rem;
                background: rgba(17,24,39,.72); color: #fff;
                border-radius: 999px; font-size: .7rem; font-weight: 600;
                padding: .2rem .6rem; backdrop-filter: blur(4px);
            }
            .nv-dur-badge {
                position: absolute; bottom: .65rem; right: .65rem;
                background: rgba(17,24,39,.78); color: #fff;
                border-radius: 8px; font-size: .72rem; font-weight: 700;
                padding: .15rem .5rem;
            }
            .nv-card-body { padding: 1rem 1.1rem 1.1rem; }
            .nv-subject {
                display: inline-block; font-size: .7rem; font-weight: 700;
                text-transform: uppercase; letter-spacing: .06em;
                color: var(--nv-primary); background: var(--nv-primary-50);
                border-radius: 999px; padding: .2rem .6rem; margin-bottom: .5rem;
            }
            .nv-title {
                font-size: .98rem; font-weight: 700; line-height: 1.35; margin-bottom: .9rem;
                display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
            }
            .btn-nv-watch {
                border-radius: 11px; font-size: .85rem; font-weight: 700;
                background: var(--nv-grad); color: #fff; border: 0;
                padding: .55rem 0; width: 100%;
                transition: opacity .15s;
            }
            .btn-nv-watch:hover { opacity: .9; color: #fff; }

            /* ─── Empty state ─────────────────────────────── */
            .nv-empty {
                text-align: center; padding: 3rem 1rem;
                border-radius: 18px; background: #f9fafb;
                border: 2px dashed #e5e7eb;
            }
            .nv-empty-ico { font-size: 3rem; color: #d1d5db; margin-bottom: .85rem; }
            .nv-empty-text { color: #6b7280; font-size: .92rem; margin-bottom: 1rem; }

            @media (max-width: 575.98px) {
                .nv-header { padding: 1.25rem; }
            }
        </style>
    @endpush

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="nv-header mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="nv-header-title">New Videos 🎬</div>
                <div class="nv-header-sub">The latest lessons added to your enrolled courses.</div>
            </div>
            <a href="{{ route('student.my-courses') }}" class="btn btn-light fw-bold" style="border-radius:12px;">
                <i class="bi bi-grid me-1"></i> My Courses
            </a>
        </div>
    </div>

    {{-- ── Feed ───────────────────────────────────────────────── --}}
    @if (count($newVideosFeed) > 0)
        <div class="row g-3">
            @foreach ($newVideosFeed as $video)
                <div class="col-sm-6 col-lg-4">
                    <a href="{{ route('student.course_details', ['course_id' => $video['course_id']]) }}"
                        class="text-decoration-none text-reset d-block h-100">
                        <div class="card nv-card">
                            <div class="nv-thumb-wrap">
                                @if (!empty($video['thumbnail']))
                                    <img src="{{ $video['thumbnail'] }}" class="nv-thumb" alt="{{ $video['lesson'] }}"
                                        onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <div class="nv-thumb-placeholder" style="display:none;">
                                        <i class="bi bi-play-circle-fill"></i>
                                    </div>
                                @else
                                    <div class="nv-thumb-placeholder">
                                        <i class="bi bi-play-circle-fill"></i>
                                    </div>
                                @endif

                                <div class="nv-play-overlay">
                                    <span class="nv-play-btn"><i class="bi bi-play-fill"></i></span>
                                </div>

                                <span class="nv-when-badge"><i class="bi bi-clock-history me-1"></i>{{ $video['when'] }}</span>
                                @if (!empty($video['duration']))
                                    <span class="nv-dur-badge">{{ $video['duration'] }} min</span>
                                @endif
                            </div>

                            <div class="nv-card-body d-flex flex-column">
                                <span class="nv-subject">{{ $video['course'] }}</span>
                                <h6 class="nv-title">{{ $video['lesson'] }}</h6>
                                <div class="mt-auto">
                                    <span class="btn btn-nv-watch">
                                        <i class="bi bi-play-fill me-1"></i> Watch
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="nv-empty">
            <div class="nv-empty-ico"><i class="bi bi-camera-video-off"></i></div>
            <div class="fw-semibold mb-1">No new videos yet</div>
            <div class="nv-empty-text">New lessons added to your enrolled courses will show up here.</div>
            <a href="{{ route('web.courses') }}" class="btn btn-nv-watch d-inline-block px-4" style="width:auto;">
                <i class="bi bi-compass me-1"></i> Browse Courses
            </a>
        </div>
    @endif
</x-student-layout>
