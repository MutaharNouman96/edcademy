<x-guest-layout>

    <div>
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="container profile-content">
                <div class="educator-main-info">
                    <div style="position: relative;">
                        <div class="educator-avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="online-badge"></div>
                    </div>
                    <div class="educator-info">
                        <h1>
                            {{ $educator->full_name }}
                        </h1>
                        <p class="educator-subject">Mathematics & Physics Specialist</p>
                        <div class="educator-meta">
                            <span class="rating-badge">
                                <i class="fas fa-star"></i> 4.9 (127 reviews)
                            </span>
                            <div class="meta-item">
                                <i class="fas fa-user-graduate"></i>
                                <span>450+ Students</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>5 Years Experience</span>
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
                                        <div class="stat-number">450+</div>
                                        <div class="stat-label">Total Students</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-number">12</div>
                                        <div class="stat-label">Courses</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-number">4.9</div>
                                        <div class="stat-label">Avg Rating</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-number">98%</div>
                                        <div class="stat-label">Response Rate</div>
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
                                    Hello! I'm Dr. Sarah Johnson, a passionate educator with a PhD in Applied
                                    Mathematics from MIT. With over 5 years of teaching experience, I've had the
                                    privilege of helping 450+ students achieve their academic goals. My teaching
                                    philosophy centers on making complex concepts accessible and engaging through
                                    hands-on learning and real-world applications.
                                </p>
                                <p style="line-height: 1.8; color: #666;">
                                    I specialize in Mathematics and Physics, covering everything from basic algebra to
                                    advanced calculus and quantum mechanics. Whether you're struggling with homework or
                                    preparing for important exams, I'm here to guide you every step of the way with
                                    personalized attention and proven teaching methods.
                                </p>
                            </div>

                            <!-- Teaching Style -->
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    Teaching Style
                                </h3>
                                <div class="mb-3">
                                    <span class="teaching-style-tag">
                                        <i class="fas fa-hands-helping me-2"></i>Hands-on Learning
                                    </span>
                                    <span class="teaching-style-tag">
                                        <i class="fas fa-users me-2"></i>Interactive Sessions
                                    </span>
                                    <span class="teaching-style-tag">
                                        <i class="fas fa-lightbulb me-2"></i>Problem-solving Focus
                                    </span>
                                </div>
                                <p style="color: #666;">
                                    I believe in learning by doing. My sessions are highly interactive with plenty of
                                    practice problems, real-world examples, and immediate feedback. I adapt my teaching
                                    style to match each student's learning pace and preferences.
                                </p>
                            </div>

                            <!-- Certifications -->
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-certificate"></i>
                                    Certifications & Qualifications
                                </h3>
                                <div class="certification-badge">
                                    <i class="fas fa-graduation-cap"></i>
                                    <strong>PhD in Applied Mathematics</strong> - Massachusetts Institute of Technology
                                    (MIT)
                                </div>
                                <div class="certification-badge">
                                    <i class="fas fa-certificate"></i>
                                    <strong>Professional Teaching License</strong> - State Board of Education
                                </div>
                                <div class="certification-badge">
                                    <i class="fas fa-award"></i>
                                    <strong>Advanced Physics Certificate</strong> - American Physical Society
                                </div>
                            </div>
                        </div>

                        <!-- Courses Tab -->
                        <div class="tab-pane fade" id="courses">
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-book"></i>
                                    Available Courses (12)
                                </h3>
                                <div class="row g-4">
                                    <!-- Course 1 -->
                                    <div class="col-md-6">
                                        <div class="course-card">
                                            <div class="course-thumbnail">
                                                <i class="fas fa-calculator"></i>
                                                <span class="course-price-badge">$89.99</span>
                                            </div>
                                            <div class="course-body">
                                                <h5 class="course-title">Complete Calculus Mastery</h5>
                                                <div class="course-meta">
                                                    <span><i class="fas fa-clock"></i> 25 hours</span>
                                                    <span><i class="fas fa-user-graduate"></i> 145 students</span>
                                                </div>
                                                <p class="course-desc">Master calculus from limits to integrals with
                                                    step-by-step guidance and practice problems.</p>
                                                <div class="course-footer">
                                                    <span class="lessons-count"><i class="fas fa-play-circle"></i> 48
                                                        lessons</span>
                                                    <button class="view-btn">View Course</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Course 2 -->
                                    <div class="col-md-6">
                                        <div class="course-card">
                                            <div class="course-thumbnail"
                                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <i class="fas fa-atom"></i>
                                                <span class="course-price-badge">$79.99</span>
                                            </div>
                                            <div class="course-body">
                                                <h5 class="course-title">Physics Fundamentals</h5>
                                                <div class="course-meta">
                                                    <span><i class="fas fa-clock"></i> 20 hours</span>
                                                    <span><i class="fas fa-user-graduate"></i> 98 students</span>
                                                </div>
                                                <p class="course-desc">Learn mechanics, thermodynamics, and
                                                    electromagnetism through engaging experiments.</p>
                                                <div class="course-footer">
                                                    <span class="lessons-count"><i class="fas fa-play-circle"></i> 35
                                                        lessons</span>
                                                    <button class="view-btn">View Course</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Course 3 -->
                                    <div class="col-md-6">
                                        <div class="course-card">
                                            <div class="course-thumbnail"
                                                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                                <i class="fas fa-square-root-alt"></i>
                                                <span class="course-price-badge">$69.99</span>
                                            </div>
                                            <div class="course-body">
                                                <h5 class="course-title">Linear Algebra Made Easy</h5>
                                                <div class="course-meta">
                                                    <span><i class="fas fa-clock"></i> 18 hours</span>
                                                    <span><i class="fas fa-user-graduate"></i> 87 students</span>
                                                </div>
                                                <p class="course-desc">Understand vectors, matrices, and
                                                    transformations with practical applications.</p>
                                                <div class="course-footer">
                                                    <span class="lessons-count"><i class="fas fa-play-circle"></i> 30
                                                        lessons</span>
                                                    <button class="view-btn">View Course</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Course 4 -->
                                    <div class="col-md-6">
                                        <div class="course-card">
                                            <div class="course-thumbnail"
                                                style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                                <i class="fas fa-chart-line"></i>
                                                <span class="course-price-badge">$59.99</span>
                                            </div>
                                            <div class="course-body">
                                                <h5 class="course-title">Statistics & Probability</h5>
                                                <div class="course-meta">
                                                    <span><i class="fas fa-clock"></i> 15 hours</span>
                                                    <span><i class="fas fa-user-graduate"></i> 120 students</span>
                                                </div>
                                                <p class="course-desc">Learn data analysis, distributions, and
                                                    hypothesis testing with real examples.</p>
                                                <div class="course-footer">
                                                    <span class="lessons-count"><i class="fas fa-play-circle"></i> 28
                                                        lessons</span>
                                                    <button class="view-btn">View Course</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                    <div class="video-card">
                                        <div class="video-thumbnail">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">12:34</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">Introduction to Derivatives</h6>
                                            <p class="video-lesson">Calculus Mastery • Lesson 5</p>
                                        </div>
                                    </div>

                                    <!-- Video 2 -->
                                    <div class="video-card">
                                        <div class="video-thumbnail"
                                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">18:22</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">Newton's Laws Explained</h6>
                                            <p class="video-lesson">Physics Fundamentals • Lesson 3</p>
                                        </div>
                                    </div>

                                    <!-- Video 3 -->
                                    <div class="video-card">
                                        <div class="video-thumbnail"
                                            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">15:47</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">Matrix Multiplication</h6>
                                            <p class="video-lesson">Linear Algebra • Lesson 8</p>
                                        </div>
                                    </div>

                                    <!-- Video 4 -->
                                    <div class="video-card">
                                        <div class="video-thumbnail"
                                            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">21:15</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">Probability Distributions</h6>
                                            <p class="video-lesson">Statistics • Lesson 12</p>
                                        </div>
                                    </div>

                                    <!-- Video 5 -->
                                    <div class="video-card">
                                        <div class="video-thumbnail"
                                            style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">14:30</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">Solving Quadratic Equations</h6>
                                            <p class="video-lesson">Algebra Basics • Lesson 6</p>
                                        </div>
                                    </div>

                                    <!-- Video 6 -->
                                    <div class="video-card">
                                        <div class="video-thumbnail"
                                            style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">19:45</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">Quantum Mechanics Intro</h6>
                                            <p class="video-lesson">Advanced Physics • Lesson 1</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- More Sample Lessons -->
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-star"></i>
                                    Popular Lessons
                                </h3>
                                <div class="video-scroll-container">
                                    <!-- Video 7 -->
                                    <div class="video-card">
                                        <div class="video-thumbnail"
                                            style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">16:20</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">Trigonometry Essentials</h6>
                                            <p class="video-lesson">Pre-Calculus • Lesson 4</p>
                                        </div>
                                    </div>

                                    <!-- Video 8 -->
                                    <div class="video-card">
                                        <div class="video-thumbnail"
                                            style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">22:10</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">Electromagnetic Waves</h6>
                                            <p class="video-lesson">Physics Advanced • Lesson 7</p>
                                        </div>
                                    </div>

                                    <!-- Video 9 -->
                                    <div class="video-card">
                                        <div class="video-thumbnail"
                                            style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">13:55</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">Integration Techniques</h6>
                                            <p class="video-lesson">Calculus II • Lesson 15</p>
                                        </div>
                                    </div>

                                    <!-- Video 10 -->
                                    <div class="video-card">
                                        <div class="video-thumbnail"
                                            style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);">
                                            <div class="play-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <span class="video-duration">17:40</span>
                                        </div>
                                        <div class="video-info">
                                            <h6 class="video-title">Vectors and Forces</h6>
                                            <p class="video-lesson">Mechanics • Lesson 9</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews">
                            <div class="content-card">
                                <h3 class="section-title">
                                    <i class="fas fa-star"></i>
                                    Student Reviews (127)
                                </h3>

                                <!-- Review Summary -->
                                <div class="row mb-4">
                                    <div class="col-md-4 text-center">
                                        <div style="font-size: 3rem; font-weight: 700; color: var(--primary-cyan);">4.9
                                        </div>
                                        <div class="rating-stars"
                                            style="font-size: 1.5rem; color: var(--accent-yellow); margin: 10px 0;">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                        <div class="text-muted">Based on 127 reviews</div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span style="width: 60px;">5 stars</span>
                                                <div class="progress flex-grow-1" style="height: 10px;">
                                                    <div class="progress-bar"
                                                        style="width: 85%; background: var(--accent-yellow);"></div>
                                                </div>
                                                <span style="width: 40px;">85%</span>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span style="width: 60px;">4 stars</span>
                                                <div class="progress flex-grow-1" style="height: 10px;">
                                                    <div class="progress-bar"
                                                        style="width: 12%; background: var(--accent-yellow);"></div>
                                                </div>
                                                <span style="width: 40px;">12%</span>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span style="width: 60px;">3 stars</span>
                                                <div class="progress flex-grow-1" style="height: 10px;">
                                                    <div class="progress-bar"
                                                        style="width: 2%; background: var(--accent-yellow);"></div>
                                                </div>
                                                <span style="width: 40px;">2%</span>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span style="width: 60px;">2 stars</span>
                                                <div class="progress flex-grow-1" style="height: 10px;">
                                                    <div class="progress-bar"
                                                        style="width: 1%; background: var(--accent-yellow);"></div>
                                                </div>
                                                <span style="width: 40px;">1%</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span style="width: 60px;">1 star</span>
                                                <div class="progress flex-grow-1" style="height: 10px;">
                                                    <div class="progress-bar"
                                                        style="width: 0%; background: var(--accent-yellow);"></div>
                                                </div>
                                                <span style="width: 40px;">0%</span>
                                            </div>
                                        </div>
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
                    const videoTitle = this.closest('.video-card').querySelector('.video-title').textContent;
                    alert(`Playing video: ${videoTitle}`);
                });
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
