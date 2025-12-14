<x-guest-layout>
    <!-- Course Hero -->
    <div class="course-hero">
        <div class="container hero-content">

            <span class="course-category">{{ $course->category->name }}</span>

            <h1 class="course-title">{{ $course->title }}</h1>

            <p class="lead">{{ $course->description }}</p>

            <div class="course-meta">
                <div class="meta-item">
                    <div class="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($course->reviews_avg_rating))
                                <i class="fas fa-star"></i>
                            @elseif($i - $course->reviews_avg_rating < 1)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>

                    <strong>{{ number_format($course->reviews_avg_rating, 1) }}</strong>
                    ({{ $course->reviews_count }} reviews)
                </div>

                <div class="meta-item">
                    <i class="fas fa-user-graduate"></i>
                    <span>{{ $studentsEnrolled }} students enrolled</span>
                </div>

                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $course->duration }} hrs</span>
                </div>

                <div class="meta-item">
                    <i class="fas fa-signal"></i>
                    <span>{{ $course->level }}</span>
                </div>

                <div class="meta-item">
                    <i class="fas fa-sync-alt"></i>
                    <span>Updated {{ $course->updated_at->format('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>


    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Course Curriculum -->
                <div class="content-card">
                    <h3 class="section-title">
                        <i class="fas fa-list"></i> Course Curriculum
                    </h3>

                    @foreach ($course->sections as $section)
                        <div class="course-section">
                            <div class="section-header {{ $loop->first ? 'active' : '' }}"
                                onclick="toggleSection(this)">
                                <div class="section-info">
                                    <h5>
                                        <i class="fas fa-chevron-down"></i>
                                        {{ $section->title }}
                                    </h5>
                                    <div class="section-meta">
                                        <span>{{ $section->lessons->count() }} lessons</span>
                                    </div>
                                </div>
                            </div>

                            <div class="section-content {{ $loop->first ? 'show' : '' }}">

                                @foreach ($section->lessons as $lesson)
                                    <div class="lesson-item">

                                        <div class="lesson-info">
                                            <div
                                                class="lesson-icon {{ $lesson->type == 'video' ? 'icon-video' : ($lesson->type == 'pdf' ? 'icon-pdf' : 'icon-sheet') }}">
                                                @if ($lesson->type == 'video')
                                                    <i class="fas fa-play"></i>
                                                @elseif($lesson->type == 'pdf')
                                                    <i class="fas fa-file-pdf"></i>
                                                @else
                                                    <i class="fas fa-file-alt"></i>
                                                @endif
                                            </div>

                                            <div class="lesson-details">
                                                <div class="lesson-title">{{ $lesson->title }}</div>
                                                <div class="lesson-meta">
                                                    <span>
                                                        <i class="far fa-clock"></i> {{ $lesson->duration }}
                                                    </span>

                                                    <span>
                                                        @if ($lesson->type == 'video')
                                                            <i class="fas fa-video"></i> Video
                                                        @elseif($lesson->type == 'pdf')
                                                            <i class="fas fa-file-pdf"></i> PDF Document
                                                        @else
                                                            <i class="fas fa-tasks"></i> Worksheet
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lesson-price">
                                            @if ($lesson->is_preview)
                                                <span class="preview-badge">PREVIEW</span>
                                            @elseif($lesson->price > 0)
                                                <span class="item-price">${{ $lesson->price }}</span>
                                            @else
                                                <span class="free-badge">FREE</span>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endforeach

                </div>


                <!-- Instructor Profile -->
                <div class="educator-card">
                    <h3 class="section-title"><i class="fas fa-user-tie"></i> About the Instructor</h3>

                    <div class="educator-profile">
                        <div class="educator-avatar">
                            <img src="{{ $course->educator->profile_photo_url }}"
                                style="width:70px; height:70px; border-radius:50%;">
                        </div>

                        <div class="educator-info">
                            <h5>{{ $course->educator->name }}</h5>
                            <p class="educator-subject">{{ $course->educator->expertise }}</p>

                            <div class="educator-stats">
                                <span><i class="fas fa-star"></i> {{ number_format($course->reviews_avg_rating, 1) }}
                                    Rating</span>
                                <span><i class="fas fa-user-graduate"></i> {{ $studentsEnrolled }} Students</span>
                                <span><i class="fas fa-play-circle"></i> {{ $moreCourses->count() + 1 }} Courses</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted">
                        {{ $course->educator->bio }}
                    </p>
                </div>


                <!-- More Courses -->
                <div class="content-card">
                    <h3 class="section-title"><i class="fas fa-graduation-cap"></i> More Courses by
                        {{ $course->educator->name }}</h3>

                    <div class="row g-3">
                        @foreach ($moreCourses as $rc)
                            <div class="col-md-4">
                                <a href="{{ route('web.course.show', $rc->slug) }}" class="related-course">
                                    <div class="related-thumb rounded">
                                        <img src="{{ asset('storage/' . $rc->thumbnail) }}" class="img-fluid">
                                        <span class="related-price">${{ $rc->price }}</span>
                                    </div>

                                    <div class="related-body">
                                        <h6 class="related-title">{{ $rc->title }}</h6>
                                        <div class="related-meta">
                                            <span><i class="fas fa-clock"></i> {{ $rc->duration }}</span>
                                            <span><i class="fas fa-user-graduate"></i>
                                                {{ $rc->coursePurchases->count() }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right Column - Purchase Card -->
            <div class="col-lg-4">
                <div class="purchase-card">
                    <div class="course-preview-img " style="cursor: pointer" data-bs-toggle="modal"
                        data-bs-target="#previewModal">
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="">
                    </div>

                    <div class="price-section">
                        <span class="current-price">${{ $course->price }}</span>
                    </div>

                    <form action="{{ route('web.cart.addToCart') }}" method="post">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $course->id }}">
                        <input type="hidden" name="model" value="App\Models\Course">
                        <input type="hidden" name="price" value="{{ $course->price }}">
                        <input type="hidden" name="quantity" value="{{ 1 }}">
                    
                        <button class="buy-btn" type="submit">
                            <i class="fas fa-shopping-bag me-2"></i>Buy Now
                        </button>
                    </form>


                    <button class="cart-btn" onclick="addToCart()">
                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                    </button>

                    {{-- <div style="text-align: center; margin: 15px 0; color: #666; font-size: 0.9rem;">
                        <i class="fas fa-clock me-1"></i> Sale ends in 2 days
                    </div> --}}

                    <ul class="includes-list">
                        <li>
                            <i class="fas fa-infinity"></i>
                            <span>Lifetime access</span>
                        </li>
                        <li>
                            <i class="fas fa-video"></i>
                            <span>{{ $course->lessons->where('type', 'video')->count() }} video lessons
                                ({{ $course->duration }}+ hours)</span>
                        </li>
                        <li>
                            <i class="fas fa-file-pdf"></i>
                            <span>15 PDF documents</span>
                        </li>
                        <li>
                            <i class="fas fa-file-alt"></i>
                            <span>20 worksheets & exercises</span>
                        </li>
                        <li>
                            <i class="fas fa-clipboard-check"></i>
                            <span>8 quizzes & assessments</span>
                        </li>
                        <li>
                            <i class="fas fa-folder-open"></i>
                            <span>Downloadable resources</span>
                        </li>
                        <li>
                            <i class="fas fa-mobile-alt"></i>
                            <span>Mobile & desktop access</span>
                        </li>
                        <li>
                            <i class="fas fa-certificate"></i>
                            <span>Certificate of completion</span>
                        </li>
                        <li>
                            <i class="fas fa-undo"></i>
                            <span>30-day money-back guarantee</span>
                        </li>
                    </ul>

                    <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-top: 20px;">
                        <h6 style="font-weight: 700; margin-bottom: 10px; color: #333;">
                            <i class="fas fa-tag me-2" style="color: var(--primary-cyan);"></i>
                            Individual Purchase Options
                        </h6>
                        <p style="font-size: 0.85rem; color: #666; margin: 0;">
                            You can also purchase sections or individual lessons separately. Expand any section to see
                            individual prices.
                        </p>
                    </div>

                    <div
                        style="background: #fff3e0; padding: 15px; border-radius: 10px; margin-top: 15px; border-left: 4px solid var(--accent-yellow);">
                        <h6 style="font-weight: 700; margin-bottom: 5px; color: #333;">
                            <i class="fas fa-calculator me-2" style="color: var(--accent-yellow);"></i>
                            Save Money!
                        </h6>
                        <p style="font-size: 0.85rem; color: #666; margin: 0;">
                            Buying the complete course saves you <strong>$83.48</strong> compared to purchasing all
                            items individually ($173.47 total).
                        </p>
                    </div>
                </div>

                <!-- Quick Info Card -->
                <div class="purchase-card mt-3">
                    <h6 style="font-weight: 700; margin-bottom: 15px;">Course Details</h6>
                    <ul class="includes-list">
                        <li>
                            <i class="fas fa-signal"></i>
                            <span><strong>Level:</strong> {{ $course->level }}</span>
                        </li>
                        <li>
                            <i class="fas fa-language"></i>
                            <span><strong>Language:</strong> English</span>
                        </li>
                        <li>
                            <i class="fas fa-closed-captioning"></i>
                            <span><strong>Subtitles:</strong> Available</span>
                        </li>
                        <li>
                            <i class="fas fa-calendar-alt"></i>
                            <span><strong>Last Updated:</strong> {{ $course->updated_at->format('M Y') }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Share Card -->
                <div class="purchase-card mt-3">
                    <h6 style="font-weight: 700; margin-bottom: 15px;">Share this course</h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary flex-fill" style="border-radius: 8px;">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button class="btn btn-outline-info flex-fill" style="border-radius: 8px;">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button class="btn btn-outline-danger flex-fill" style="border-radius: 8px;">
                            <i class="fab fa-pinterest"></i>
                        </button>
                        <button class="btn btn-outline-success flex-fill" style="border-radius: 8px;">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade " id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-play-circle me-2"></i> Course Preview :
                        {{ $course->title }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <video controls width="100%" height="315">
                        <source src="{{ asset('storage/' . $freeVideo->preview_video) }}" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Toggle section content
            function toggleSection(header) {
                const section = header.parentElement;
                const content = section.querySelector('.section-content');
                const icon = header.querySelector('i');

                // Close all other sections
                document.querySelectorAll('.course-section').forEach(s => {
                    if (s !== section) {
                        s.querySelector('.section-content').classList.remove('show');
                        s.querySelector('.section-header').classList.remove('active');
                        const otherIcon = s.querySelector('.section-header i');
                        otherIcon.classList.remove('fa-chevron-up');
                        otherIcon.classList.add('fa-chevron-down');
                    }
                });

                // Toggle current section
                if (content.classList.contains('show')) {
                    content.classList.remove('show');
                    header.classList.remove('active');
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    content.classList.add('show');
                    header.classList.add('active');
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            }

            // Buy now
            function buyNow(data) {

                swal.fire({
                    icon: 'info',
                    title: 'Buy Now',
                    text: 'You will be redirected to the checkout page.'
                }).then(() => {
                    window.location.href = '{{ route('web.cart.checkout') }}';
                })
            }

            // Add to cart
            function addToCart() {
                alert('Added to cart!\n\nComplete Calculus Mastery - $89.99\n\nView cart or continue shopping?');

                window.location.href = '{{ route('web.cart') }}';
            }

            // View educator profile
            function viewProfile() {
                alert('Redirecting to Dr. Sarah Johnson\'s profile...');
            }

            // Preview video
            document.querySelector('.preview-play').addEventListener('click', function() {
                alert('Playing course preview video...');
            });

            // Share buttons
            document.querySelectorAll('.btn-outline-primary, .btn-outline-info, .btn-outline-danger, .btn-outline-success')
                .forEach(btn => {
                    btn.addEventListener('click', function() {
                        if (this.querySelector('i').classList.contains('fa-facebook-f')) {
                            alert('Sharing on Facebook...');
                        } else if (this.querySelector('i').classList.contains('fa-twitter')) {
                            alert('Sharing on Twitter...');
                        } else if (this.querySelector('i').classList.contains('fa-pinterest')) {
                            alert('Sharing on Pinterest...');
                        } else if (this.querySelector('i').classList.contains('fa-whatsapp')) {
                            alert('Sharing on WhatsApp...');
                        }
                    });
                });

            // Related course clicks
            document.querySelectorAll('.related-course').forEach(course => {
                course.addEventListener('click', function() {
                    const title = this.querySelector('.related-title').textContent;
                    alert('Viewing course: ' + title);
                });
            });
        </script>
    @endpush


    @push('styles')
        <style>
            .course-hero {
                background: linear-gradient(135deg, var(--primary-cyan) 0%, var(--dark-cyan) 100%);
                color: white;
                padding: 50px 0;
                position: relative;
                overflow: hidden;
            }

            .course-hero::before {
                content: '';
                position: absolute;
                inset: 0;
                background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
                opacity: 0.3;
            }

            .hero-content {
                position: relative;
                z-index: 1;
            }

            .course-category {
                display: inline-block;
                background: var(--accent-yellow);
                color: #333;
                padding: 5px 15px;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 700;
                margin-bottom: 15px;
            }

            .course-title {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 15px;
                color: #fff3e0;
            }

            .course-meta {
                display: flex;
                gap: 25px;
                flex-wrap: wrap;
                margin-top: 20px;
                color: #fff3e0
            }

            .meta-item {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .rating-stars {
                color: var(--accent-yellow);
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

            .course-section {
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                margin-bottom: 20px;
                overflow: hidden;
                transition: all 0.3s;
            }

            .course-section:hover {
                border-color: var(--light-cyan);
            }

            .section-header {
                background: #f8f9fa;
                padding: 20px 25px;
                cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: all 0.3s;
            }

            .section-header:hover {
                background: #e8f5f7;
            }

            .section-header.active {
                background: #e8f5f7;
            }

            .section-info h5 {
                font-weight: 700;
                color: #333;
                margin-bottom: 5px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .section-meta {
                color: #666;
                font-size: 0.9rem;
            }

            .section-price {
                text-align: right;
            }

            .section-total {
                font-size: 1.3rem;
                font-weight: 700;
                color: var(--primary-cyan);
            }

            .section-items {
                font-size: 0.85rem;
                color: #666;
                margin-top: 5px;
            }

            .section-content {
                display: none;
                background: white;
            }

            .section-content.show {
                display: block;
            }

            .lesson-item {
                padding: 15px 25px;
                border-top: 2px solid #f0f0f0;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: all 0.3s;
            }

            .lesson-item:hover {
                background: #f8f9fa;
            }

            .lesson-info {
                display: flex;
                gap: 15px;
                align-items: center;
                flex: 1;
            }

            .lesson-icon {
                width: 45px;
                height: 45px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
                flex-shrink: 0;
            }

            .icon-video {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .icon-pdf {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                color: white;
            }

            .icon-sheet {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                color: white;
            }

            .icon-quiz {
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
                color: white;
            }

            .icon-material {
                background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
                color: white;
            }

            .lesson-details {
                flex: 1;
            }

            .lesson-title {
                font-weight: 600;
                color: #333;
                margin-bottom: 3px;
            }

            .lesson-meta {
                font-size: 0.85rem;
                color: #666;
                display: flex;
                gap: 15px;
                flex-wrap: wrap;
            }

            .lesson-price {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 5px;
            }

            .item-price {
                font-size: 1.1rem;
                font-weight: 700;
                color: var(--primary-cyan);
            }

            .free-badge {
                background: var(--accent-yellow);
                color: #333;
                padding: 4px 12px;
                border-radius: 15px;
                font-size: 0.75rem;
                font-weight: 700;
            }

            .preview-badge {
                background: var(--accent-pink);
                color: white;
                padding: 4px 12px;
                border-radius: 15px;
                font-size: 0.75rem;
                font-weight: 700;
            }

            .purchase-card {
                position: sticky;
                top: 20px;
                background: white;
                border-radius: 15px;
                padding: 25px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            }

            .course-preview-img {
                width: 100%;
                height: 200px;
                background: linear-gradient(135deg, var(--light-cyan) 0%, var(--primary-cyan) 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 20px;
                position: relative;
            }

            .preview-play {
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.9);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s;
            }

            .preview-play:hover {
                transform: scale(1.1);
                background: white;
            }

            .preview-play i {
                color: var(--primary-cyan);
                font-size: 1.5rem;
                margin-left: 3px;
            }

            .price-section {
                text-align: center;
                padding: 20px 0;
                border-bottom: 2px solid #f0f0f0;
                margin-bottom: 20px;
            }

            .current-price {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--primary-cyan);
            }

            .original-price {
                font-size: 1.2rem;
                color: #999;
                text-decoration: line-through;
                margin-left: 10px;
            }

            .discount-badge {
                background: var(--accent-pink);
                color: white;
                padding: 5px 15px;
                border-radius: 20px;
                font-weight: 700;
                margin-top: 10px;
                display: inline-block;
            }

            .includes-list {
                list-style: none;
                padding: 0;
                margin-bottom: 20px;
            }

            .includes-list li {
                padding: 10px 0;
                display: flex;
                align-items: center;
                gap: 10px;
                border-bottom: 1px solid #f0f0f0;
            }

            .includes-list li:last-child {
                border-bottom: none;
            }

            .includes-list i {
                color: var(--primary-cyan);
            }

            .buy-btn {
                width: 100%;
                padding: 15px;
                background: var(--primary-cyan);
                color: white;
                border: none;
                border-radius: 10px;
                font-weight: 700;
                font-size: 1.1rem;
                transition: all 0.3s;
                margin-bottom: 10px;
            }

            .buy-btn:hover {
                background: var(--dark-cyan);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 131, 143, 0.3);
            }

            .cart-btn {
                width: 100%;
                padding: 12px;
                background: white;
                color: var(--primary-cyan);
                border: 2px solid var(--primary-cyan);
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.3s;
            }

            .cart-btn:hover {
                background: var(--primary-cyan);
                color: white;
            }

            .educator-card {
                background: white;
                border-radius: 15px;
                padding: 25px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
                margin-bottom: 30px;
            }

            .educator-profile {
                display: flex;
                gap: 20px;
                align-items: center;
                margin-bottom: 20px;
            }

            .educator-avatar {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent-pink) 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .educator-avatar i {
                font-size: 2rem;
                color: rgba(255, 255, 255, 0.5);
            }

            .educator-info h5 {
                font-weight: 700;
                color: #333;
                margin-bottom: 5px;
            }

            .educator-subject {
                color: var(--primary-cyan);
                font-weight: 600;
                margin-bottom: 10px;
            }

            .educator-stats {
                display: flex;
                gap: 15px;
                font-size: 0.85rem;
                color: #666;
            }

            .related-course {
                background: white;
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.3s;
                height: 100%;
            }

            .related-course:hover {
                border-color: var(--primary-cyan);
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(0, 131, 143, 0.15);
            }

            .related-thumb {
                height: 140px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            .related-thumb i {
                font-size: 2.5rem;
                color: rgba(255, 255, 255, 0.4);
            }

            .related-price {
                position: absolute;
                top: 10px;
                right: 10px;
                background: var(--accent-yellow);
                color: #333;
                padding: 5px 12px;
                border-radius: 15px;
                font-weight: 700;
                font-size: 0.85rem;
            }

            .related-body {
                padding: 15px;
            }

            .related-title {
                font-weight: 700;
                color: #333;
                font-size: 0.95rem;
                margin-bottom: 8px;
            }

            .related-meta {
                font-size: 0.8rem;
                color: #666;
                display: flex;
                gap: 10px;
            }

            @media (max-width: 768px) {
                .course-title {
                    font-size: 1.8rem;
                }

                .purchase-card {
                    position: relative;
                }
            }
        </style>
    @endpush
</x-guest-layout>
