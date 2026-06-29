<x-guest-layout>
    @php
        $reviewCount = $educator_reviews->count();
        $avgRatingValue = (float) $educatorAverageRating;
    @endphp
    <div class="educator-profile-page">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="container profile-content">
                <div class="educator-main-info">
                    <div class="educator-avatar-wrap">
                        <img src="{{ $educator->profile_picture_url }}" alt="{{ $educator->full_name }}" class="educator-avatar-img" />
                        @if ($educator_profile?->featured ?? false)
                            <span class="featured-pill">Featured</span>
                        @endif
                    </div>
                    <div class="educator-info">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <h1 class="text-dark mb-0">{{ $educator->full_name }}</h1>
                            @if ($educator_profile?->educator_type)
                                <span class="educator-type-badge educator-type-{{ $educator_profile->educator_type }}">
                                    {{ ucfirst($educator_profile->educator_type) }}
                                </span>
                            @endif
                        </div>
                        @if (count($primarySubjects))
                            <div class="header-subjects">
                                @foreach ($primarySubjects as $subject)
                                    <span class="subject-chip">{{ $subject }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="educator-subject text-dark mb-0">Educator</p>
                        @endif
                        <div class="educator-meta">
                            <span class="rating-badge">
                                <i class="fas fa-star"></i>
                                {{ $reviewCount > 0 ? number_format($avgRatingValue, 1) : 'New' }}
                                @if ($reviewCount > 0)
                                    <span class="rating-count">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                                @endif
                            </span>
                            <div class="meta-item">
                                <i class="fas fa-user-graduate"></i>
                                <span>{{ number_format($studentCount) }} {{ Str::plural('Student', $studentCount) }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-book"></i>
                                <span>{{ $courses->count() }} {{ Str::plural('Course', $courses->count()) }}</span>
                            </div>
                            @if ($educator_profile?->location)
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $educator_profile->location }}</span>
                                </div>
                            @endif
                        </div>
                        @if (count($teachingLevels))
                            <div class="header-levels mt-3">
                                @foreach ($teachingLevels as $level)
                                    <span class="level-chip">{{ $level }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="container">
            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#overview">
                        <i class="fas fa-info-circle me-2"></i>Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#courses">
                        <i class="fas fa-book me-2"></i>Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#lessons">
                        <i class="fas fa-video me-2"></i>Sample Lessons
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#reviews">
                        <i class="fas fa-star me-2"></i>Reviews
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container mt-4">
            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <div class="tab-content">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview">
                            @if ($introVideoUrl)
                                <div class="content-card" id="intro-video">
                                    <h3 class="section-title">
                                        <i class="fas fa-video"></i>
                                        Intro Video
                                    </h3>
                                    <div class="intro-video-player">
                                        <video-player>
                                            <video-minimal-skin>
                                                <video
                                                    data-src="{{ $introVideoUrl }}"
                                                    playsinline
                                                    preload="none"
                                                ></video>
                                            </video-minimal-skin>
                                        </video-player>
                                    </div>
                                </div>
                            @endif
                            <!-- Stats -->
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-chart-line"></i>
                                    Performance Stats
                                </h3>
                                <div class="stats-grid">
                                    <div class="stat-box">
                                        <div class="stat-number">{{ number_format($studentCount) }}</div>
                                        <div class="stat-label">Total Students</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-number">{{ $courses->count() }}</div>
                                        <div class="stat-label">Courses</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-number">{{ $totalLessons }}</div>
                                        <div class="stat-label">Lessons</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-number">{{ $reviewCount > 0 ? number_format($avgRatingValue, 1) : '—' }}</div>
                                        <div class="stat-label">Avg Rating</div>
                                    </div>
                                </div>
                            </div>

                            <!-- About -->
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-user"></i>
                                    About Me
                                </h3>
                                @if (filled($educatorBio))
                                    <p class="about-text">{{ $educatorBio }}</p>
                                @else
                                    <p class="text-muted mb-0">This educator has not added a bio yet.</p>
                                @endif
                            </div>

                            @if (count($primarySubjects))
                                <div class="content-card">
                                    <h3 class="section-title">
                                        <i class="fas fa-book-open"></i>
                                        Primary Subjects
                                    </h3>
                                    <div class="teaching-style-list">
                                        @foreach ($primarySubjects as $subject)
                                            <span class="subject-tag">{{ $subject }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (count($teachingStyles))
                                <div class="content-card">
                                    <h3 class="section-title">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        Teaching Style
                                    </h3>
                                    <div class="teaching-style-list">
                                        @foreach ($teachingStyles as $style)
                                            <span class="teaching-style-tag">{{ $style }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (count($teachingLevels))
                                <div class="content-card">
                                    <h3 class="section-title">
                                        <i class="fas fa-layer-group"></i>
                                        Teaching Levels
                                    </h3>
                                    <div class="teaching-style-list">
                                        @foreach ($teachingLevels as $level)
                                            <span class="level-chip level-chip--large">{{ $level }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (filled($educator_profile?->certifications))
                                <div class="content-card">
                                    <h3 class="section-title">
                                        <i class="fas fa-certificate"></i>
                                        Certifications & Qualifications
                                    </h3>
                                    <div class="certification-badge">
                                        <i class="fas fa-graduation-cap"></i>
                                        {{ $educator_profile->certifications }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Courses Tab -->
                        <div class="tab-pane fade" id="courses">
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-book"></i>
                                    Available Courses ({{ $courses->count() }})
                                </h3>
                                @if ($courses->isEmpty())
                                    <div class="empty-state">
                                        <i class="fas fa-book-open"></i>
                                        <p>No published courses yet. Check back soon.</p>
                                    </div>
                                @else
                                    <div class="row g-4">
                                        @foreach ($courses as $course)
                                            <div class="col-md-6">
                                                <article class="course-card">
                                                    <a href="{{ route('web.course.show', ['slug' => $course->slug, 'id' => $course->id]) }}" class="course-thumbnail-link">
                                                        <div class="course-thumbnail" @if ($course->thumbnail) style="background-image: url('{{ $course->thumbnail_path }}')" @endif>
                                                            @unless ($course->thumbnail)
                                                                <i class="fas fa-book-open"></i>
                                                            @endunless
                                                            <span class="course-price-badge">${{ number_format($course->price ?? 0, 2) }}</span>
                                                        </div>
                                                    </a>
                                                    <div class="course-body">
                                                        @if ($course->category?->name)
                                                            <span class="course-category-pill">{{ $course->category->name }}</span>
                                                        @endif
                                                        <h5 class="course-title">
                                                            <a href="{{ route('web.course.show', ['slug' => $course->slug, 'id' => $course->id]) }}">
                                                                {{ $course->title }}
                                                            </a>
                                                        </h5>
                                                        <div class="course-meta">
                                                            <span>
                                                                <i class="fas fa-clock"></i>
                                                                {{ $course->duration ?: ($course->total_lesson_duration ? round($course->total_lesson_duration / 60, 1) . ' hrs' : '—') }}
                                                            </span>
                                                            <span>
                                                                <i class="fas fa-user-graduate"></i>
                                                                {{ $course->enrolled_students }} students
                                                            </span>
                                                        </div>
                                                        <p class="course-desc">{{ Str::limit($course->description, 120) }}</p>
                                                        <div class="course-footer">
                                                            <span class="lessons-count">
                                                                <i class="fas fa-play-circle"></i>
                                                                {{ $course->published_lessons_count }} lessons
                                                            </span>
                                                            <a href="{{ route('web.course.show', ['slug' => $course->slug, 'id' => $course->id]) }}" class="view-btn">
                                                                View Course
                                                            </a>
                                                        </div>
                                                    </div>
                                                </article>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Lessons Tab -->
                        <div class="tab-pane fade" id="lessons">
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-video"></i>
                                    Sample Video Lessons
                                </h3>
                                <p class="text-muted mb-4">Preview this educator's teaching style with recent lessons.</p>

                                @if ($recent_videos->isEmpty())
                                    <div class="empty-state">
                                        <i class="fas fa-video-slash"></i>
                                        <p>No sample lessons available yet.</p>
                                    </div>
                                @else
                                    <div class="video-scroll-container">
                                        @foreach ($recent_videos as $lesson)
                                            <div class="video-card" data-video-url="{{ $lesson->lesson_video_path }}">
                                                <div class="video-thumbnail" @if ($lesson->thumbnail) style="background-image: url('{{ asset('storage/' . $lesson->thumbnail) }}')" @endif>
                                                    <div class="play-overlay">
                                                        <i class="fas fa-play"></i>
                                                    </div>
                                                    @if ($lesson->duration)
                                                        <span class="video-duration">{{ $lesson->duration }} min</span>
                                                    @endif
                                                </div>
                                                <div class="video-info">
                                                    <h6 class="video-title">{{ $lesson->title }}</h6>
                                                    <p class="video-lesson">
                                                        {{ $lesson->course?->title }}
                                                        @if ($lesson->courseSection?->title)
                                                            • {{ $lesson->courseSection->title }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @if ($popular_videos->isNotEmpty())
                                <div class="content-card">
                                    <h3 class="section-title">
                                        <i class="fas fa-star"></i>
                                        Popular Lessons
                                    </h3>
                                    <div class="video-scroll-container">
                                        @foreach ($popular_videos as $lesson)
                                            <div class="video-card" data-video-url="{{ $lesson->lesson_video_path }}">
                                                <div class="video-thumbnail" @if ($lesson->thumbnail) style="background-image: url('{{ asset('storage/' . $lesson->thumbnail) }}')" @endif>
                                                    <div class="play-overlay">
                                                        <i class="fas fa-play"></i>
                                                    </div>
                                                    @if ($lesson->duration)
                                                        <span class="video-duration">{{ $lesson->duration }} min</span>
                                                    @endif
                                                </div>
                                                <div class="video-info">
                                                    <h6 class="video-title">{{ $lesson->title }}</h6>
                                                    <p class="video-lesson">
                                                        {{ $lesson->course?->title }}
                                                        @if ($lesson->courseSection?->title)
                                                            • {{ $lesson->courseSection->title }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews">
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-star"></i>
                                    Student Reviews ({{ $reviewCount }})
                                </h3>

                                @if ($reviewCount === 0)
                                    <div class="empty-state">
                                        <i class="far fa-star"></i>
                                        <p>No reviews yet. Be the first to book a session.</p>
                                    </div>
                                @else
                                    <div class="row mb-4 review-summary">
                                        <div class="col-md-4 text-center">
                                            <div class="review-score">{{ number_format($avgRatingValue, 1) }}</div>
                                            <div class="rating-stars review-stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($avgRatingValue >= $i)
                                                        <i class="fas fa-star"></i>
                                                    @elseif ($avgRatingValue > ($i - 1))
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="text-muted">Based on {{ $reviewCount }} reviews</div>
                                        </div>
                                        <div class="col-md-8">
                                            @foreach ([5, 4, 3, 2, 1] as $star)
                                                <div class="rating-bar-row">
                                                    <span class="rating-bar-label">{{ $star }} stars</span>
                                                    <div class="progress rating-progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $starPercentages[$star] ?? 0 }}%;"
                                                            aria-valuenow="{{ $starPercentages[$star] ?? 0 }}"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <span class="rating-bar-value">{{ $starPercentages[$star] ?? 0 }}%</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    @foreach ($educator_reviews as $review)
                                        <div class="review-item">
                                            <div class="d-flex gap-3">
                                                <div class="review-avatar">
                                                    {{ strtoupper(substr($review->student?->first_name ?? 'S', 0, 1)) }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                                        <div>
                                                            <h6 class="review-author mb-1">
                                                                {{ $review->student?->full_name ?? 'Student' }}
                                                            </h6>
                                                            <div class="rating-stars review-item-stars">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= floor($review->rating))
                                                                        <i class="fas fa-star"></i>
                                                                    @elseif ($i - 0.5 <= $review->rating)
                                                                        <i class="fas fa-star-half-alt"></i>
                                                                    @else
                                                                        <i class="far fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($review->created_at)->format('M d, Y') }}</small>
                                                    </div>
                                                    @if (filled($review->comment))
                                                        <p class="review-comment">{{ $review->comment }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Booking Card -->
                <div class="col-lg-4">
                    <div class="booking-card">
                        <div class="price-section">
                            <div class="hourly-rate">${{ $educator_profile->hourly_rate ?? 'N/A' }}<small>/hour</small></div>
                            <p class="rate-label">Starting rate for 1-on-1 sessions</p>
                        </div>

                        <form class="booking-form" id="booking-form">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="far fa-calendar me-2"></i>Select Date
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="booking-date-display" placeholder="Click to pick a date" readonly>
                                    <button type="button" class="btn btn-outline-secondary" id="open-calendar-btn" title="Choose date"><i class="far fa-calendar-alt"></i></button>
                                </div>
                                <input type="hidden" name="date" id="booking-date-iso" value="" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="far fa-clock me-2"></i>Select Time
                                </label>
                                <select class="form-select" name="time" id="booking-time-select" required disabled>
                                    <option value="">Choose a date first</option>
                                </select>
                                <small class="text-muted d-block mt-1" id="time-slot-hint">Pick a date to see available times</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-hourglass-half me-2"></i>Duration
                                </label>
                                <select class="form-select" name="duration" required>
                                    <option value="">Choose duration</option>
                                    @php $hr = $educator_profile->hourly_rate ?? 0; @endphp
                                    <option value="1">1 hour - ${{ number_format($hr * 1, 2) }}</option>
                                    <option value="1.5">1.5 hours - ${{ number_format($hr * 1.5, 2) }}</option>
                                    <option value="2">2 hours - ${{ number_format($hr * 2, 2) }}</option>
                                    <option value="3">3 hours - ${{ number_format($hr * 3, 2) }}</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-book me-2"></i>Subject
                                </label>
                                <select class="form-select" name="subject" required>
                                    <option value="">Choose subject</option>
                                    @forelse ($bookingSubjects as $subject)
                                        <option value="{{ Str::slug($subject) }}">{{ $subject }}</option>
                                    @empty
                                        <option value="general">General tutoring</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-comment me-2"></i>Message (Optional)
                                </label>
                                <textarea class="form-control" name="message" rows="3" placeholder="Tell me about your learning goals..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-outline-secondary text-dark btn-block w-100" onclick="bookSession(event)">
                                <i class="fas fa-check-circle me-2"></i>Book Session
                            </button>
                        </form>

                        <button class="contact-btn" onclick="contactEducator()">
                            <i class="fas fa-envelope me-2"></i>Contact Educator
                        </button>

                        <ul class="features-list">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Instant confirmation</span>
                            </li>
                            <li>
                                <i class="fas fa-undo"></i>
                                <span>Free cancellation 24h before</span>
                            </li>
                            <li>
                                <i class="fas fa-shield-alt"></i>
                                <span>Secure payment</span>
                            </li>
                            <li>
                                <i class="fas fa-video"></i>
                                <span>Online or in-person options</span>
                            </li>
                            <li>
                                <i class="fas fa-award"></i>
                                <span>Satisfaction guaranteed</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Stats Card -->
                    <div class="booking-card mt-3">
                        <h6 class="quick-stats-title">Quick Stats</h6>
                        <ul class="features-list">
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>{{ $availabilitySummary }}</span>
                            </li>
                            @if ($educator_profile?->location)
                                <li>
                                    <i class="fas fa-globe"></i>
                                    <span>{{ $educator_profile->location }}</span>
                                </li>
                            @endif
                            @if (count($primarySubjects))
                                <li>
                                    <i class="fas fa-book"></i>
                                    <span>Specializes in {{ implode(', ', $primarySubjects) }}</span>
                                </li>
                            @endif
                            <li>
                                <i class="fas fa-video"></i>
                                <span>{{ $totalLessons }} published lessons</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


    </div>


<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video Playback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <iframe id="videoFrame" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Date picker calendar modal -->
<div class="modal fade" id="bookingCalendarModal" tabindex="-1" aria-labelledby="bookingCalendarModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingCalendarModalLabel"><i class="far fa-calendar-alt me-2"></i>Select a date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="booking-calendar" style="min-height: 380px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css" rel="stylesheet">
    @endpush
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
        <script>
            const educatorId = {{ $educator->id }};
            const apiBase = '{{ url("/api/educator") }}';

            let bookingCalendar = null;
            const calendarModalEl = document.getElementById('bookingCalendarModal');
            const bookingDateIso = document.getElementById('booking-date-iso');
            const bookingDateDisplay = document.getElementById('booking-date-display');
            const bookingTimeSelect = document.getElementById('booking-time-select');
            const timeSlotHint = document.getElementById('time-slot-hint');

            function formatDisplayDate(isoDate) {
                const d = new Date(isoDate + 'T12:00:00');
                return d.toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            }

            function loadTimeSlots(dateIso) {
                bookingTimeSelect.disabled = true;
                bookingTimeSelect.innerHTML = '<option value="">Loading...</option>';
                timeSlotHint.textContent = 'Loading available times...';
                fetch(`${apiBase}/${educatorId}/available-slots?date=${encodeURIComponent(dateIso)}`)
                    .then(r => r.json())
                    .then(data => {
                        bookingTimeSelect.innerHTML = '<option value="">Choose a time slot</option>';
                        if (data.success && data.slots && data.slots.length) {
                            data.slots.forEach(function(t) {
                                const opt = document.createElement('option');
                                opt.value = t;
                                const [h, m] = t.split(':');
                                const hour = parseInt(h, 10);
                                const ampm = hour >= 12 ? 'PM' : 'AM';
                                const hour12 = hour % 12 || 12;
                                opt.textContent = `${hour12}:${m} ${ampm}`;
                                bookingTimeSelect.appendChild(opt);
                            });
                            bookingTimeSelect.disabled = false;
                            timeSlotHint.textContent = data.slots.length + ' slot(s) available';
                        } else {
                            timeSlotHint.textContent = 'No slots available this day';
                        }
                    })
                    .catch(function() {
                        bookingTimeSelect.innerHTML = '<option value="">Error loading slots</option>';
                        timeSlotHint.textContent = 'Could not load times. Try again.';
                    });
            }

            calendarModalEl.addEventListener('shown.bs.modal', function() {
                if (!bookingCalendar) {
                    bookingCalendar = new FullCalendar.Calendar(document.getElementById('booking-calendar'), {
                        initialView: 'dayGridMonth',
                        selectable: true,
                        selectLongPressDelay: 0,
                        validRange: { start: new Date().toISOString().slice(0, 10) },
                        headerToolbar: { left: 'prev,next', center: 'title', right: '' },
                        dateClick: function(info) {
                            const dateStr = info.dateStr;
                            bookingDateIso.value = dateStr;
                            bookingDateDisplay.value = formatDisplayDate(dateStr);
                            bookingDateIso.setAttribute('value', dateStr);
                            loadTimeSlots(dateStr);
                            bootstrap.Modal.getInstance(calendarModalEl).hide();
                        }
                    });
                    bookingCalendar.render();
                } else {
                    bookingCalendar.updateSize();
                }
            });

            function openCalendarModal() {
                const modal = bootstrap.Modal.getOrCreateInstance(calendarModalEl);
                modal.show();
            }

            document.getElementById('open-calendar-btn').addEventListener('click', openCalendarModal);
            bookingDateDisplay.addEventListener('click', openCalendarModal);

            function bookSession(event) {
                event.preventDefault();
                const form = document.getElementById('booking-form');

                // Basic client-side validation before hitting the server.
                if (!bookingDateIso.value) {
                    Swal.fire({ icon: 'warning', title: 'Pick a date', text: 'Please select a date first.', confirmButtonColor: '#6f42c1' });
                    return;
                }
                if (!bookingTimeSelect.value) {
                    Swal.fire({ icon: 'warning', title: 'Pick a time', text: 'Please choose an available time slot.', confirmButtonColor: '#6f42c1' });
                    return;
                }
                if (!form.duration.value || !form.subject.value) {
                    Swal.fire({ icon: 'warning', title: 'Missing details', text: 'Please choose a duration and subject.', confirmButtonColor: '#6f42c1' });
                    return;
                }

                const formData = new FormData(form);
                formData.set('date', bookingDateIso.value);
                formData.append('educator_id', educatorId);

                // Loading state while we create the checkout session.
                Swal.fire({
                    title: 'Preparing secure checkout...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                });

                fetch("{{ route('book.session') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async response => ({ status: response.status, body: await response.json() }))
                .then(({ status, body }) => {
                    if (body.success && body.checkout_url) {
                        // Redirect the student to Stripe Checkout.
                        Swal.fire({
                            icon: 'success',
                            title: 'Redirecting to payment',
                            text: body.message || 'Taking you to secure checkout...',
                            timer: 1200,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                        });
                        setTimeout(() => { window.location.href = body.checkout_url; }, 1000);
                        return;
                    }

                    if (status === 401 && body.require_auth) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Please log in',
                            text: body.message || 'You need to be logged in to book a session.',
                            showCancelButton: true,
                            confirmButtonText: 'Log in',
                            confirmButtonColor: '#6f42c1',
                        }).then(result => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('login') }}";
                            }
                        });
                        return;
                    }

                    Swal.fire({ icon: 'error', title: 'Could not book', text: body.message || 'Booking failed. Please try again.', confirmButtonColor: '#6f42c1' });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({ icon: 'error', title: 'Something went wrong', text: 'An unexpected error occurred. Please try again.', confirmButtonColor: '#6f42c1' });
                });
            }

            // Contact educator
            function contactEducator() {
                Swal.fire({
                    icon: 'info',
                    title: 'Contact Educator',
                    text: 'Messaging will be available from your dashboard once your session is booked.',
                    confirmButtonColor: '#6f42c1',
                });
            }

            // Surface server flash messages (e.g. cancelled payment) as a Swal popup.
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Booking not completed', text: @json(session('error')), confirmButtonColor: '#6f42c1' });
            @endif
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Success', text: @json(session('success')), confirmButtonColor: '#6f42c1' });
            @endif

            // Video play buttons
            document.querySelectorAll('.play-overlay').forEach(btn => {
                btn.addEventListener('click', function() {
                    const videoCard = this.closest('.video-card');
                    const videoTitle = videoCard.querySelector('.video-title').textContent;
                    const videoUrl = videoCard.dataset.videoUrl;

                    if (videoUrl) {
                        const videoFrame = document.getElementById('videoFrame');
                        videoFrame.src = toEmbedUrl(videoUrl);
                        const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
                        videoModal.show();
                        document.getElementById('videoModalLabel').textContent = `Playing: ${videoTitle}`;
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Preview unavailable',
                            text: `No video is available for "${videoTitle}" yet.`,
                            confirmButtonColor: '#6f42c1',
                        });
                    }
                });
            });

            function toEmbedUrl(url) {
                if (url.includes('youtube.com/watch')) {
                    const videoId = new URL(url).searchParams.get('v');
                    return videoId ? `https://www.youtube.com/embed/${videoId}` : url;
                }
                if (url.includes('youtu.be/')) {
                    const videoId = url.split('youtu.be/')[1]?.split(/[?&]/)[0];
                    return videoId ? `https://www.youtube.com/embed/${videoId}` : url;
                }
                return url;
            }

            // Clear video src when modal is closed
            document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
                const videoFrame = document.getElementById('videoFrame');
                videoFrame.src = '';
            });
        </script>
        @if ($introVideoUrl)
            <script type="module" src="https://cdn.jsdelivr.net/npm/@videojs/html/cdn/video-minimal.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const introVideo = document.querySelector('#intro-video video[data-src]');
                    if (!introVideo) {
                        return;
                    }

                    const loadIntroVideo = function () {
                        if (!introVideo.dataset.src) {
                            return;
                        }

                        introVideo.src = introVideo.dataset.src;
                        introVideo.removeAttribute('data-src');
                    };

                    introVideo.closest('video-player')?.addEventListener('pointerdown', loadIntroVideo, { once: true, capture: true });
                    introVideo.addEventListener('play', loadIntroVideo, { once: true });
                });
            </script>
        @endif
    @endpush
    @push('styles')
        <style>
            .educator-profile-page {
                padding-bottom: 3rem;
            }

            .profile-header {
                background: linear-gradient(135deg, var(--primary-cyan) 0%, var(--dark-cyan) 100%);
                color: white;
                padding: 48px 0 72px;
                position: relative;
            }

            .profile-header::before {
                content: '';
                position: absolute;
                inset: 0;
                background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
                opacity: 0.3;
            }

            .profile-content {
                position: relative;
                z-index: 1;
            }

            .educator-main-info {
                display: flex;
                gap: 32px;
                align-items: center;
            }

            .educator-avatar-wrap {
                position: relative;
                flex-shrink: 0;
            }

            .educator-avatar-img {
                width: 180px;
                height: 180px;
                border-radius: 50%;
                object-fit: cover;
                border: 5px solid rgba(255, 255, 255, 0.95);
                box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
            }

            .featured-pill {
                position: absolute;
                bottom: 8px;
                left: 50%;
                transform: translateX(-50%);
                background: var(--accent-yellow);
                color: #333;
                font-size: 0.75rem;
                font-weight: 700;
                padding: 4px 12px;
                border-radius: 999px;
                white-space: nowrap;
            }

            .educator-info h1 {
                font-size: 2.4rem;
                font-weight: 700;
            }

            .educator-subject {
                font-size: 1.15rem;
                opacity: 0.95;
                margin-bottom: 16px;
            }

            .header-subjects {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-bottom: 16px;
            }

            .subject-chip {
                display: inline-flex;
                align-items: center;
                background: rgba(255, 255, 255, 0.2);
                color: #fff;
                border: 1px solid rgba(255, 255, 255, 0.35);
                padding: 6px 14px;
                border-radius: 999px;
                font-size: 0.9rem;
                font-weight: 600;
            }

            .subject-tag {
                display: inline-flex;
                align-items: center;
                background: linear-gradient(135deg, var(--primary-cyan) 0%, var(--dark-cyan) 100%);
                color: white;
                padding: 8px 14px;
                border-radius: 999px;
                font-size: 0.88rem;
                font-weight: 600;
            }

            .intro-video-player {
                border-radius: 14px;
                overflow: hidden;
                background: #0f172a;
            }

            .intro-video-player video-player {
                display: block;
                width: 100%;
            }

            .educator-meta {
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
            }

            .meta-item {
                display: flex;
                align-items: center;
                gap: 8px;
                color: rgba(255, 255, 255, 0.95);
            }

            .rating-badge {
                background: var(--accent-yellow);
                color: #333;
                padding: 8px 15px;
                border-radius: 20px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .rating-count {
                font-weight: 600;
            }

            .header-levels,
            .teaching-style-list {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .level-chip {
                display: inline-flex;
                align-items: center;
                background: rgba(255, 255, 255, 0.18);
                color: #fff;
                border: 1px solid rgba(255, 255, 255, 0.35);
                padding: 6px 12px;
                border-radius: 999px;
                font-size: 0.85rem;
                font-weight: 600;
            }

            .level-chip--large {
                background: #f3f7f8;
                color: #35515a;
                border-color: #dbe7ea;
            }

            .nav-tabs-custom {
                background: white;
                border-radius: 16px 16px 0 0;
                margin-top: -36px;
                position: relative;
                z-index: 2;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
                border-bottom: 2px solid #e8ecef;
                padding: 0 8px;
            }

            .nav-tabs-custom .nav-link {
                border: none;
                color: #667085;
                font-weight: 600;
                padding: 18px 24px;
                transition: all 0.25s ease;
            }

            .nav-tabs-custom .nav-link:hover {
                color: var(--primary-cyan);
            }

            .nav-tabs-custom .nav-link.active {
                color: var(--primary-cyan);
                background: transparent;
                border-bottom: 3px solid var(--primary-cyan);
            }

            .content-card {
                background: white;
                border-radius: 16px;
                padding: 28px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
                margin-bottom: 24px;
                border: 1px solid #eef2f4;
            }

            .section-title {
                font-size: 1.35rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .section-title i {
                color: var(--primary-cyan);
            }

            .about-text {
                line-height: 1.8;
                color: #5f6b7a;
                margin-bottom: 0;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 16px;
            }

            .stat-box {
                text-align: center;
                padding: 20px 16px;
                background: linear-gradient(180deg, #f8fbfc 0%, #f2f7f8 100%);
                border-radius: 14px;
                border: 1px solid #e7eef1;
                transition: transform 0.25s ease, box-shadow 0.25s ease;
            }

            .stat-box:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 24px rgba(0, 131, 143, 0.08);
            }

            .stat-number {
                font-size: 1.9rem;
                font-weight: 700;
                color: var(--primary-cyan);
            }

            .stat-label {
                color: #667085;
                font-size: 0.9rem;
                margin-top: 6px;
            }

            .empty-state {
                text-align: center;
                padding: 48px 20px;
                color: #7a8699;
            }

            .empty-state i {
                font-size: 2.2rem;
                color: #c5d0d6;
                margin-bottom: 12px;
            }

            .course-card {
                background: white;
                border: 1px solid #e5eaee;
                border-radius: 16px;
                overflow: hidden;
                transition: all 0.25s ease;
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .course-card:hover {
                border-color: rgba(0, 131, 143, 0.35);
                transform: translateY(-4px);
                box-shadow: 0 14px 30px rgba(0, 131, 143, 0.12);
            }

            .course-thumbnail-link {
                display: block;
                text-decoration: none;
            }

            .course-thumbnail {
                height: 180px;
                background: linear-gradient(135deg, var(--light-cyan) 0%, var(--primary-cyan) 100%);
                background-size: cover;
                background-position: center;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            .course-thumbnail i {
                font-size: 2.5rem;
                color: rgba(255, 255, 255, 0.45);
            }

            .course-price-badge {
                position: absolute;
                top: 14px;
                right: 14px;
                background: rgba(255, 255, 255, 0.95);
                color: #1f2937;
                padding: 7px 14px;
                border-radius: 999px;
                font-weight: 700;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            }

            .course-body {
                padding: 20px;
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .course-category-pill {
                display: inline-block;
                background: #eef7f8;
                color: var(--primary-cyan);
                font-size: 0.75rem;
                font-weight: 700;
                padding: 4px 10px;
                border-radius: 999px;
                margin-bottom: 10px;
            }

            .course-title {
                font-size: 1.05rem;
                font-weight: 700;
                margin-bottom: 10px;
            }

            .course-title a {
                color: #1f2937;
                text-decoration: none;
            }

            .course-title a:hover {
                color: var(--primary-cyan);
            }

            .course-meta {
                display: flex;
                gap: 14px;
                flex-wrap: wrap;
                font-size: 0.85rem;
                color: #667085;
                margin-bottom: 10px;
            }

            .course-desc {
                color: #667085;
                font-size: 0.92rem;
                line-height: 1.6;
                flex-grow: 1;
                margin-bottom: 14px;
            }

            .course-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-top: 14px;
                border-top: 1px solid #eef2f4;
                gap: 12px;
            }

            .lessons-count {
                color: var(--primary-cyan);
                font-weight: 600;
                font-size: 0.88rem;
            }

            .view-btn {
                background: var(--primary-cyan);
                color: white !important;
                border: none;
                padding: 8px 16px;
                border-radius: 10px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.25s ease;
                white-space: nowrap;
            }

            .view-btn:hover {
                background: var(--dark-cyan);
                color: white !important;
            }

            .video-scroll-container {
                display: flex;
                gap: 18px;
                overflow-x: auto;
                padding-bottom: 12px;
                scroll-behavior: smooth;
            }

            .video-scroll-container::-webkit-scrollbar {
                height: 8px;
            }

            .video-scroll-container::-webkit-scrollbar-thumb {
                background: var(--primary-cyan);
                border-radius: 10px;
            }

            .video-card {
                min-width: 280px;
                background: white;
                border: 1px solid #e5eaee;
                border-radius: 14px;
                overflow: hidden;
                transition: all 0.25s ease;
            }

            .video-card:hover {
                border-color: rgba(0, 131, 143, 0.35);
                transform: translateY(-3px);
                box-shadow: 0 10px 24px rgba(0, 131, 143, 0.1);
            }

            .video-thumbnail {
                height: 160px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                background-size: cover;
                background-position: center;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            .play-overlay {
                width: 52px;
                height: 52px;
                background: rgba(255, 255, 255, 0.92);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.25s ease;
            }

            .play-overlay:hover {
                transform: scale(1.08);
            }

            .play-overlay i {
                color: var(--primary-cyan);
                font-size: 1.4rem;
                margin-left: 3px;
            }

            .video-duration {
                position: absolute;
                bottom: 10px;
                right: 10px;
                background: rgba(0, 0, 0, 0.75);
                color: white;
                padding: 4px 8px;
                border-radius: 6px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .video-info {
                padding: 14px 16px 16px;
            }

            .video-title {
                font-weight: 600;
                color: #1f2937;
                font-size: 0.95rem;
                margin-bottom: 4px;
            }

            .video-lesson {
                font-size: 0.82rem;
                color: #8a94a6;
                margin-bottom: 0;
            }

            .review-summary {
                align-items: center;
            }

            .review-score {
                font-size: 3rem;
                font-weight: 700;
                color: var(--primary-cyan);
                line-height: 1;
            }

            .review-stars {
                font-size: 1.35rem;
                color: var(--accent-yellow);
                margin: 10px 0;
            }

            .rating-bar-row {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 10px;
            }

            .rating-bar-label,
            .rating-bar-value {
                width: 58px;
                font-size: 0.88rem;
                color: #667085;
            }

            .rating-progress {
                flex: 1;
                height: 10px;
                background: #edf2f5;
                border-radius: 999px;
            }

            .rating-progress .progress-bar {
                background: var(--accent-yellow);
                border-radius: 999px;
            }

            .review-item {
                padding: 20px 0;
                border-bottom: 1px solid #edf2f5;
            }

            .review-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .review-avatar {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary-cyan), var(--dark-cyan));
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                flex-shrink: 0;
            }

            .review-author {
                font-weight: 700;
                color: #1f2937;
            }

            .review-item-stars {
                color: var(--accent-yellow);
                font-size: 0.88rem;
            }

            .review-comment {
                margin: 12px 0 0;
                color: #5f6b7a;
                line-height: 1.65;
            }

            .booking-card {
                position: sticky;
                top: 88px;
                background: white;
                border-radius: 16px;
                padding: 24px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
                border: 1px solid #eef2f4;
            }

            .price-section {
                text-align: center;
                margin-bottom: 22px;
                padding-bottom: 22px;
                border-bottom: 1px solid #eef2f4;
            }

            .hourly-rate {
                font-size: 2.3rem;
                font-weight: 700;
                color: var(--primary-cyan);
            }

            .hourly-rate small {
                font-size: 1rem;
                font-weight: 500;
            }

            .rate-label {
                color: #667085;
                margin-top: 6px;
                margin-bottom: 0;
            }

            .booking-form .form-label {
                font-weight: 600;
                color: #344054;
                margin-bottom: 8px;
            }

            .booking-form .form-control,
            .booking-form .form-select {
                border: 1px solid #d7dee3;
                border-radius: 12px;
                padding: 12px;
            }

            .booking-form .form-control:focus,
            .booking-form .form-select:focus {
                border-color: var(--primary-cyan);
                box-shadow: 0 0 0 3px rgba(0, 131, 143, 0.12);
            }

            .contact-btn {
                width: 100%;
                padding: 12px;
                background: white;
                color: var(--primary-cyan);
                border: 2px solid var(--primary-cyan);
                border-radius: 12px;
                font-weight: 600;
                transition: all 0.25s ease;
                margin-top: 10px;
            }

            .contact-btn:hover {
                background: var(--primary-cyan);
                color: white;
            }

            .features-list {
                list-style: none;
                padding: 0;
                margin-top: 16px;
                margin-bottom: 0;
            }

            .features-list li {
                padding: 10px 0;
                display: flex;
                align-items: flex-start;
                gap: 10px;
                color: #5f6b7a;
            }

            .features-list i {
                color: var(--primary-cyan);
                margin-top: 3px;
            }

            .quick-stats-title {
                font-weight: 700;
                margin-bottom: 12px;
                color: #1f2937;
            }

            .teaching-style-tag {
                display: inline-flex;
                align-items: center;
                background: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent-pink) 100%);
                color: white;
                padding: 8px 14px;
                border-radius: 999px;
                font-size: 0.88rem;
                font-weight: 600;
            }

            .certification-badge {
                background: #fff8eb;
                border-left: 4px solid var(--accent-yellow);
                padding: 16px;
                border-radius: 10px;
                color: #5f4b32;
            }

            .certification-badge i {
                color: var(--accent-yellow);
                margin-right: 8px;
            }

            @media (max-width: 768px) {
                .educator-main-info {
                    flex-direction: column;
                    text-align: center;
                }

                .educator-avatar-img {
                    width: 140px;
                    height: 140px;
                }

                .educator-info h1 {
                    font-size: 1.9rem;
                }

                .header-levels,
                .educator-meta {
                    justify-content: center;
                }

                .booking-card {
                    position: relative;
                    top: 0;
                }

                .nav-tabs-custom .nav-link {
                    padding: 14px 16px;
                    font-size: 0.92rem;
                }
            }
        </style>
    @endpush
</x-guest-layout>
