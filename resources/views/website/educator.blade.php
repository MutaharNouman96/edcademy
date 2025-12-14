<x-guest-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
  rel="stylesheet"
/>
    <div>
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="container profile-content">
                <div class="educator-main-info">
                    <div style="position: relative;">
                            <img src="{{ $educator->profile_picture }}" style="border-radius: 50%" class="img-fluid d-block mx-auto shadow" />
                        {{-- <div class="online-badge"></div> --}}
                    </div>
                    <div class="educator-info">
                        <h1 class="text-dark">
                            {{ $educator->full_name }}
                        </h1>
                        <p class="educator-subject text-dark">{{ $educator_profile->primary_subject }}</p>
                        <div class="educator-meta">
                            <span class="rating-badge">
                                <i class="fas fa-star"></i> {{ $educatorAverageRating }} ({{ $educator_reviews->count() }} reviews)
                            </span>
                            <div class="meta-item">
                                <i class="fas fa-user-graduate"></i>
                                <span>{{ $studentCount }} Students</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Boston, MA</span>
                            </div>
                        </div>
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
                            <!-- Stats -->
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-chart-line"></i>
                                    Performance Stats
                                </h3>
                                <div class="stats-grid">
                                    <div class="stat-box">
                                        <div class="stat-number">{{$studentCount}}</div>
                                        <div class="stat-label">Total Students</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-number">{{ $courses->count() }}</div>
                                        <div class="stat-label">Courses</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-number">{{ $educatorAverageRating }}</div>
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
                                <p style="line-height: 1.8; color: #666;">
                                   {{ $educator_profile->bio ?? '' }}
                                </p>

                            </div>

                            <!-- Teaching Style -->
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    Teaching Style
                                </h3>
                                <div class="mb-3">
                                    @php
                                        $teachingStyles = explode(' \\/ ', $educator_profile->preferred_teaching_style ?? '');
                                    @endphp
                                    @foreach ($teachingStyles as $style)
                                        <span class="teaching-style-tag text-dark mb-2">
                                            <i class="bi bi-arrow-right me-2 text-dark"></i>{{ trim(str_replace('"', '', $style)) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Certifications -->
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-certificate"></i>
                                    Certifications & Qualifications
                                </h3>
                                <div class="certification-badge">
                                    <i class="fas fa-graduation-cap"></i>
                                    {{ $educator_profile->certifications ?? '' }}
                                </div>

                            </div>
                        </div>

                        <!-- Courses Tab -->
                        <div class="tab-pane fade" id="courses">
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-book"></i>
                                    Available Courses ({{ $courses->count() }})
                                </h3>
                                <div class="row g-4">
                                    <!-- Courses -->
                                    @foreach ($courses as $c)
                                    <div class="col-md-6">
                                        <div class="course-card">
                                            <div class="course-thumbnail" style="background-image: url('{{ asset($c->thumbnail) }}')">

                                                <i class="fas fa-calculator"></i>
                                                <span class="course-price-badge bg-white">${{ $c->price ?? '' }}</span>
                                            </div>
                                            <div class="course-body">
                                                <h5 class="course-title">{{ $c->title ?? '' }}</h5>
                                                <?php
                                                $course_students = DB::table('course_purchases')
                                                ->where('course_id', $c->id)
                                                ->distinct('course_purchases.student_id')
                                                ->count();

                                                $course_lessons = DB::table('lessons')
                                                ->where('course_id', $c->id)
                                                ->where('status', 'Published')
                                                ->count();

                                                $course_duration = DB::table('lessons')
                                                ->where('course_id', $c->id)
                                                ->where('status', 'Published')
                                                ->sum('duration');
                                                ?>
                                                <div class="course-meta">
                                                    <span><i class="fas fa-clock"></i> <?php echo $course_duration ?> hours</span>
                                                    <span><i class="fas fa-user-graduate"></i> <?php echo $course_students ?> students</span>
                                                </div>
                                                <p class="course-desc">{{ $c->description ?? '' }}</p>
                                                <div class="course-footer">
                                                    <span class="lessons-count"><i class="fas fa-play-circle"></i> <?php echo $course_lessons ?>
                                                        lessons</span>
                                                    <button class="view-btn">View Course</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        <!-- Lessons Tab -->
                        <div class="tab-pane fade" id="lessons">
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-video"></i>
                                    Sample Video Lessons
                                </h3>
                                <p class="text-muted mb-4">Preview some of my teaching style with these sample lessons
                                </p>

                                <div class="video-scroll-container">
                                    <!-- Video 1 -->
                                    @foreach ($recent_videos as $v)
                                    <div class="video-card" data-video-url="{{ $v->video_link ?? '' }}">
                                        <div class="video-thumbnail">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">{{ $v->duration ?? '' }} mint</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">{{ $v->lesson_title ?? '' }}</h6>
                                            <p class="video-lesson">{{ $v->course_title }} • {{ DB::table('course_sections')->where('id', $v->course_section_id)->first()->title }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- More Sample Lessons -->
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-star"></i>
                                    Popular Lessons
                                </h3>
                                <div class="video-scroll-container">
                                    <!-- Popular -->
                                    @foreach ($popular_videos as $v)
                                    <div class="video-card" data-video-url="{{ $v->video_link ?? '' }}">
                                        <div class="video-thumbnail">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">{{ $v->duration ?? '' }} mint</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">{{ $v->lesson_title ?? '' }}</h6>
                                            <p class="video-lesson">{{ $v->course_title }} • {{ DB::table('course_sections')->where('id', $v->course_section_id)->first()->title }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews">
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-star"></i>
                                    Student Reviews ({{ $educator_reviews->count() }})
                                </h3>

                                <!-- Review Summary -->
<?php
// Calculate review counts for each star rating
$starRatings = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

foreach ($educator_reviews as $review) {
    // Convert rating to integer here ↓↓↓ THIS is where you add it
    $rating = intval($review->rating);

    if (isset($starRatings[$rating])) {
        $starRatings[$rating]++;
    }
}

$totalReviews = $educator_reviews->count();

// Calculate percentages
$starPercentages = [];
foreach ($starRatings as $star => $count) {
    $starPercentages[$star] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
}

?>
                                <div class="row mb-4">
                                    <div class="col-md-4 text-center">
                                        <div style="font-size: 3rem; font-weight: 700; color: var(--primary-cyan);">{{ number_format($educatorAverageRating, 1) }}
                                        </div>
                                        <div class="rating-stars"
                                            style="font-size: 1.5rem; color: var(--accent-yellow); margin: 10px 0;">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($educatorAverageRating >= $i)
                                                    <i class="fas fa-star"></i>
                                                @elseif ($educatorAverageRating > ($i - 1) && $educatorAverageRating < $i)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="text-muted">Based on {{ $educator_reviews->count() }} reviews</div>
                                    </div>
                                    <div class="col-md-8">

                                        @foreach ([5, 4, 3, 2, 1] as $star)
                                            <div class="mb-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span style="width: 60px;">{{ $star }} stars</span>
                                                    <div class="progress flex-grow-1" style="height: 10px;">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" style="width: {{ $starPercentages[$star] ?? 0 }}%;" aria-valuenow="{{ $starPercentages[$star] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">{{ $starPercentages[$star] ?? 0 }}%</div>
                                                          </div>
                                                    </div>
                                                    <span style="width: 40px;">{{ $starPercentages[$star] }}%</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Individual Reviews -->
                                <div class="review-item" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0;">
                                    <div class="d-flex gap-3 mb-3">
                                        <div
                                            style="width: 50px; height: 50px; border-radius: 50%; background: var(--light-cyan); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                            JD
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1" style="font-weight: 700;">John Doe</h6>
                                                    <div class="rating-stars"
                                                        style="color: var(--accent-yellow); font-size: 0.9rem;">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                </div>
                                                <small class="text-muted">2 weeks ago</small>
                                            </div>
                                            <p class="mt-3 mb-0" style="color: #666; line-height: 1.6;">
                                                Dr. Johnson is an exceptional educator! Her teaching methods are clear
                                                and engaging. I went from struggling with calculus to actually enjoying
                                                it. Highly recommend her courses to anyone looking to master
                                                mathematics.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="review-item" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0;">
                                    <div class="d-flex gap-3 mb-3">
                                        <div
                                            style="width: 50px; height: 50px; border-radius: 50%; background: var(--accent-purple); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                            SM
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1" style="font-weight: 700;">Sarah Miller</h6>
                                                    <div class="rating-stars"
                                                        style="color: var(--accent-yellow); font-size: 0.9rem;">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                </div>
                                                <small class="text-muted">1 month ago</small>
                                            </div>
                                            <p class="mt-3 mb-0" style="color: #666; line-height: 1.6;">
                                                The Physics Fundamentals course was outstanding! Dr. Johnson's
                                                explanations are so clear and her patience is incredible. She made
                                                difficult concepts easy to understand. Worth every penny!
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="review-item" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0;">
                                    <div class="d-flex gap-3 mb-3">
                                        <div
                                            style="width: 50px; height: 50px; border-radius: 50%; background: var(--accent-pink); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                            MK
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1" style="font-weight: 700;">Mike Kumar</h6>
                                                    <div class="rating-stars"
                                                        style="color: var(--accent-yellow); font-size: 0.9rem;">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                </div>
                                                <small class="text-muted">2 months ago</small>
                                            </div>
                                            <p class="mt-3 mb-0" style="color: #666; line-height: 1.6;">
                                                Great instructor with excellent content. The one-on-one sessions were
                                                particularly helpful. My only suggestion would be to include more
                                                practice problems in each lesson.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button class="btn btn-outline-primary"
                                        style="border-color: var(--primary-cyan); color: var(--primary-cyan); padding: 10px 30px; border-radius: 10px; font-weight: 600;">
                                        Load More Reviews
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Booking Card -->
                <div class="col-lg-4">
                    <div class="booking-card">
                        <div class="price-section">
                            <div class="hourly-rate">$85<small>/hour</small></div>
                            <p class="rate-label">Starting rate for 1-on-1 sessions</p>
                        </div>

                        <form class="booking-form">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="far fa-calendar me-2"></i>Select Date
                                </label>
                                <input type="date" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="far fa-clock me-2"></i>Select Time
                                </label>
                                <select class="form-select" required>
                                    <option value="">Choose a time slot</option>
                                    <option value="09:00">09:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="13:00">01:00 PM</option>
                                    <option value="14:00">02:00 PM</option>
                                    <option value="15:00">03:00 PM</option>
                                    <option value="16:00">04:00 PM</option>
                                    <option value="17:00">05:00 PM</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-hourglass-half me-2"></i>Duration
                                </label>
                                <select class="form-select" required>
                                    <option value="">Choose duration</option>
                                    <option value="1">1 hour - $85</option>
                                    <option value="1.5">1.5 hours - $127.50</option>
                                    <option value="2">2 hours - $170</option>
                                    <option value="3">3 hours - $255</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-book me-2"></i>Subject
                                </label>
                                <select class="form-select" required>
                                    <option value="">Choose subject</option>
                                    <option value="calculus">Calculus</option>
                                    <option value="physics">Physics</option>
                                    <option value="algebra">Linear Algebra</option>
                                    <option value="statistics">Statistics</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-comment me-2"></i>Message (Optional)
                                </label>
                                <textarea class="form-control" rows="3" placeholder="Tell me about your learning goals..."></textarea>
                            </div>

                            <button type="submit" class="book-btn" onclick="bookSession(event)">
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
                        <h6 style="font-weight: 700; margin-bottom: 15px;">Quick Stats</h6>
                        <ul class="features-list">
                            <li>
                                <i class="fas fa-reply"></i>
                                <span>Usually responds in 2 hours</span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Available Mon-Fri, 9am-6pm</span>
                            </li>
                            <li>
                                <i class="fas fa-globe"></i>
                                <span>Boston, MA (EST)</span>
                            </li>
                            <li>
                                <i class="fas fa-language"></i>
                                <span>English, Spanish</span>
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
    @push('scripts')
        <script>
            // Book session
            function bookSession(event) {
                event.preventDefault();
                const date = event.target.querySelector('input[type="date"]').value;
                const time = event.target.querySelector('select').value;

                if (date && time) {
                    alert(
                        `Booking session with Dr. Sarah Johnson\nDate: ${date}\nTime: ${time}\n\nYou will be redirected to payment...`);
                }
            }

            // Contact educator
            function contactEducator() {
                alert('Opening message form to contact Dr. Sarah Johnson...');
            }

            // View course buttons
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const courseTitle = this.closest('.course-body').querySelector('.course-title').textContent;
                    alert(`Viewing course: ${courseTitle}`);
                });
            });

            // Video play buttons
            document.querySelectorAll('.play-overlay').forEach(btn => {
                btn.addEventListener('click', function() {
                    const videoCard = this.closest('.video-card');
                    const videoTitle = videoCard.querySelector('.video-title').textContent;
                    const videoUrl = videoCard.dataset.videoUrl;

                    if (videoUrl) {
                        const videoFrame = document.getElementById('videoFrame');
                        videoFrame.src = videoUrl;
                        const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
                        videoModal.show();

                        // Optional: Update modal title with video title
                        document.getElementById('videoModalLabel').textContent = `Playing: ${videoTitle}`;
                    } else {
                        alert(`Video URL not found for: ${videoTitle}`);
                    }
                });
            });

            // Clear video src when modal is closed
            document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
                const videoFrame = document.getElementById('videoFrame');
                videoFrame.src = '';
            });
        </script>
    @endpush
    @push('styles')
        <style>
            .profile-header {
                background: linear-gradient(135deg, var(--primary-cyan) 0%, var(--dark-cyan) 100%);
                color: white;
                padding: 40px 0 60px;
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
                gap: 30px;
                align-items: start;
            }

            .educator-avatar {
                width: 150px;
                height: 150px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent-pink) 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                border: 5px solid white;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                flex-shrink: 0;
            }

            .educator-avatar i {
                font-size: 4rem;
                color: rgba(255, 255, 255, 0.5);
            }

            .online-badge {
                position: absolute;
                bottom: 10px;
                right: 10px;
                width: 20px;
                height: 20px;
                background: #4caf50;
                border: 3px solid white;
                border-radius: 50%;
            }

            .educator-info h1 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 10px;
            }

            .educator-subject {
                font-size: 1.3rem;
                opacity: 0.95;
                margin-bottom: 15px;
            }

            .educator-meta {
                display: flex;
                gap: 25px;
                flex-wrap: wrap;
            }

            .meta-item {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .rating-badge {
                background: var(--accent-yellow);
                color: #333;
                padding: 8px 15px;
                border-radius: 20px;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .nav-tabs-custom {
                background: white;
                border-radius: 15px 15px 0 0;
                margin-top: -30px;
                position: relative;
                z-index: 2;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
                border-bottom: 2px solid #e0e0e0;
            }

            .nav-tabs-custom .nav-link {
                border: none;
                color: #666;
                font-weight: 600;
                padding: 20px 30px;
                transition: all 0.3s;
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
                border-radius: 15px;
                padding: 30px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
                margin-bottom: 30px;
            }

            .section-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: #333;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .section-title i {
                color: var(--primary-cyan);
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }

            .stat-box {
                text-align: center;
                padding: 20px;
                background: #f8f9fa;
                border-radius: 12px;
                transition: all 0.3s;
            }

            .stat-box:hover {
                background: #e8f5f7;
                transform: translateY(-3px);
            }

            .stat-number {
                font-size: 2rem;
                font-weight: 700;
                color: var(--primary-cyan);
            }

            .stat-label {
                color: #666;
                font-size: 0.9rem;
                margin-top: 5px;
            }

            .course-card {
                background: white;
                border: 2px solid #e0e0e0;
                border-radius: 15px;
                overflow: hidden;
                transition: all 0.3s;
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .course-card:hover {
                border-color: var(--primary-cyan);
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 131, 143, 0.15);
            }

            .course-thumbnail {
                height: 180px;
                background: linear-gradient(135deg, var(--light-cyan) 0%, var(--primary-cyan) 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            .course-thumbnail i {
                font-size: 3rem;
                color: rgba(255, 255, 255, 0.4);
            }

            .course-price-badge {
                position: absolute;
                top: 15px;
                right: 15px;
                background: var(--accent-yellow);
                color: #333;
                padding: 8px 15px;
                border-radius: 20px;
                font-weight: 700;
            }

            .course-body {
                padding: 20px;
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .course-title {
                font-size: 1.1rem;
                font-weight: 700;
                color: #333;
                margin-bottom: 10px;
            }

            .course-meta {
                display: flex;
                gap: 15px;
                font-size: 0.85rem;
                color: #666;
                margin-bottom: 10px;
            }

            .course-desc {
                color: #666;
                font-size: 0.9rem;
                line-height: 1.6;
                flex-grow: 1;
                margin-bottom: 15px;
            }

            .course-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-top: 15px;
                border-top: 1px solid #f0f0f0;
            }

            .lessons-count {
                color: var(--primary-cyan);
                font-weight: 600;
                font-size: 0.9rem;
            }

            .view-btn {
                background: var(--primary-cyan);
                color: white;
                border: none;
                padding: 8px 20px;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s;
            }

            .view-btn:hover {
                background: var(--dark-cyan);
            }

            .video-scroll-container {
                display: flex;
                gap: 20px;
                overflow-x: auto;
                padding-bottom: 20px;
                scroll-behavior: smooth;
            }

            .video-scroll-container::-webkit-scrollbar {
                height: 8px;
            }

            .video-scroll-container::-webkit-scrollbar-track {
                background: #f0f0f0;
                border-radius: 10px;
            }

            .video-scroll-container::-webkit-scrollbar-thumb {
                background: var(--primary-cyan);
                border-radius: 10px;
            }

            .video-card {
                min-width: 280px;
                background: white;
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.3s;
            }

            .video-card:hover {
                border-color: var(--primary-cyan);
                transform: translateY(-3px);
            }

            .video-thumbnail {
                height: 160px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            .play-overlay {
                width: 50px;
                height: 50px;
                background: rgba(255, 255, 255, 0.9);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s;
            }

            .play-overlay:hover {
                transform: scale(1.1);
                background: white;
            }

            .play-overlay i {
                color: var(--primary-cyan);
                font-size: 1.5rem;
                margin-left: 3px;
            }

            .video-duration {
                position: absolute;
                bottom: 10px;
                right: 10px;
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 4px 8px;
                border-radius: 5px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .video-info {
                padding: 15px;
            }

            .video-title {
                font-weight: 600;
                color: #333;
                font-size: 0.95rem;
                margin-bottom: 5px;
            }

            .video-lesson {
                font-size: 0.8rem;
                color: #999;
            }

            .booking-card {
                position: sticky;
                top: 20px;
                background: white;
                border-radius: 15px;
                padding: 25px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            }

            .price-section {
                text-align: center;
                margin-bottom: 25px;
                padding-bottom: 25px;
                border-bottom: 2px solid #f0f0f0;
            }

            .hourly-rate {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--primary-cyan);
            }

            .hourly-rate small {
                font-size: 1.2rem;
                font-weight: 500;
            }

            .rate-label {
                color: #666;
                margin-top: 5px;
            }

            .booking-form .form-label {
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
            }

            .booking-form .form-control,
            .booking-form .form-select {
                border: 2px solid #e0e0e0;
                border-radius: 10px;
                padding: 12px;
                transition: all 0.3s;
            }

            .booking-form .form-control:focus,
            .booking-form .form-select:focus {
                border-color: var(--primary-cyan);
                box-shadow: 0 0 0 3px rgba(0, 131, 143, 0.1);
            }

            .book-btn {
                width: 100%;
                padding: 15px;
                background: var(--primary-cyan);
                color: white;
                border: none;
                border-radius: 10px;
                font-weight: 700;
                font-size: 1.1rem;
                transition: all 0.3s;
                margin-top: 20px;
            }

            .book-btn:hover {
                background: var(--dark-cyan);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 131, 143, 0.3);
            }

            .contact-btn {
                width: 100%;
                padding: 12px;
                background: white;
                color: var(--primary-cyan);
                border: 2px solid var(--primary-cyan);
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.3s;
                margin-top: 10px;
            }

            .contact-btn:hover {
                background: var(--primary-cyan);
                color: white;
            }

            .features-list {
                list-style: none;
                padding: 0;
                margin-top: 20px;
            }

            .features-list li {
                padding: 10px 0;
                display: flex;
                align-items: center;
                gap: 10px;
                color: #666;
            }

            .features-list i {
                color: var(--primary-cyan);
            }

            .teaching-style-tag {
                display: inline-block;
                background: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent-pink) 100%);
                color: white;
                padding: 8px 15px;
                border-radius: 20px;
                font-size: 0.9rem;
                font-weight: 600;
                margin-right: 10px;
                margin-bottom: 10px;
            }

            .certification-badge {
                background: #fff3e0;
                border-left: 4px solid var(--accent-yellow);
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 15px;
            }

            .certification-badge i {
                color: var(--accent-yellow);
                margin-right: 10px;
            }

            @media (max-width: 768px) {
                .educator-main-info {
                    flex-direction: column;
                    text-align: center;
                }

                .educator-avatar {
                    width: 120px;
                    height: 120px;
                }

                .educator-info h1 {
                    font-size: 1.8rem;
                }

                .booking-card {
                    position: relative;
                }
            }
        </style>
    @endpush
</x-guest-layout>
