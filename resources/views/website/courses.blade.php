<x-guest-layout>
    <div class="hero-section">
        <div class="container">
            <div class="text-center mb-4">
                <h1>Discover Your Next Course</h1>
                <p>Explore thousands of courses from expert educators</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="search-box">
                        <input type="text" placeholder="What do you want to learn today?">
                        <button><i class="fas fa-search me-2"></i>Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-2">
        <!-- Filters Section -->
        <div class="filters-section">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <h6 class="mb-2 text-muted">Category</h6>
                    <button class="filter-btn active">All</button>
                    @foreach ($firstFiveCategories as $category)
                        <button class="filter-btn">{{ $category->name }}</button>
                    @endforeach
                    @if ($remainingCategories->count() > 0)
                        <div class="dropdown d-inline-block">
                            <button class="filter-btn dropdown-toggle" data-bs-toggle="dropdown">
                                More
                            </button>
                            <ul class="dropdown-menu">
                                @foreach ($remainingCategories as $category)
                                    <li><a class="dropdown-item" href="#">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="mb-2 text-muted">Difficulty</h6>
                    <button class="filter-btn">Beginner</button>
                    <button class="filter-btn">Intermediate</button>
                    <button class="filter-btn">Advanced</button>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="mb-2 text-muted">Price</h6>
                    <button class="filter-btn">Free</button>
                    <button class="filter-btn">Paid</button>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="mb-2 text-muted">Type</h6>
                    <button class="filter-btn">Video</button>
                    <button class="filter-btn">Module</button>
                    <button class="filter-btn">Live</button>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="section-header mt-5">
            <div class="results-count">
                <strong>248</strong> courses found
            </div>
            <select class="sort-dropdown">
                <option>Most Popular</option>
                <option>Highest Rated</option>
                <option>Newest First</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
            </select>
        </div>

        <!-- Course Grid -->
        <div class="row g-4 mb-5">
            <!-- Course Card 1 -->
            @foreach ($courses as $course)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="listing-course-card">
                        <!-- Thumbnail -->
                        <div class="course-thumbnail">

                            @if ($course->thumbnail != null)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}">
                            @else
                                <i class="fas fa-book-open fs-1 text-muted"></i>
                            @endif

                            <!-- Premium Badge -->
                            @if (!$course->is_free && $course->price > 0)
                                <span class="course-badge badge-premium">Premium</span>
                            @endif
                        </div>

                        <div class="course-body">

                            <!-- Difficulty Badge -->
                            @if ($course->difficulty)
                                <span class="difficulty-badge difficulty-{{ strtolower($course->difficulty) }}">
                                    {{ ucfirst($course->difficulty) }}
                                </span>
                            @endif

                            <!-- Title -->
                            <h5 class="course-title mt-2">{{ $course->title }}</h5>

                            <!-- Meta -->
                            <div class="course-meta">
                                <span><i class="fas fa-clock"></i> {{ $course->duration ?? '–' }}</span>
                                <span><i class="fas fa-video"></i> {{ $course->lessons->count() }} lessons</span>

                                @php
                                    $rating = $course->features->rating ?? null;
                                @endphp

                                @if ($rating)
                                    <span>
                                        <i class="fas fa-star text-warning"></i> {{ number_format($rating, 1) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="course-description">
                                {{ \Illuminate\Support\Str::limit($course->description, 120) }}
                            </p>

                            <!-- Footer -->
                            <div class="course-footer">
                                <span class="course-price">
                                    @if ($course->is_free)
                                        Free
                                    @else
                                        ${{ number_format($course->price, 2) }}
                                    @endif
                                </span>

                                <a href="{{ route('web.course.show', $course->slug) }}" class="enroll-btn">
                                    Enroll Now
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <nav aria-label="Course pagination">
            {{-- <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item">
                    <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                </li>
            </ul> --}}
            {{ $courses->links() }}
        </nav>
    </div>

    @push('scripts')
        <script>
            // Filter button interactions
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active from siblings in same group
                    const parent = this.closest('.col-md-3');
                    if (parent) {
                        parent.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    }
                    this.classList.add('active');
                });
            });

            // Search functionality
            document.querySelector('.search-box button').addEventListener('click', function() {
                const searchValue = document.querySelector('.search-box input').value;
                console.log('Searching for:', searchValue);
                // Add your search logic here
            });

            // Sort dropdown
            document.querySelector('.sort-dropdown').addEventListener('change', function() {
                console.log('Sorting by:', this.value);
                // Add your sorting logic here
            });

            // Enroll buttons
          
        </script>
    @endpush
    @push('styles')
        <style>
            .hero-section {
                background: linear-gradient(135deg, var(--primary-cyan) 0%, var(--dark-cyan) 100%);
                padding: 60px 0 80px;
                position: relative;
                overflow: hidden;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
                opacity: 0.3;
            }

            .hero-section h1 {
                color: white;
                font-weight: 700;
                font-size: 2.5rem;
                margin-bottom: 1rem;
                position: relative;
                z-index: 1;
            }

            .hero-section p {
                color: rgba(255, 255, 255, 0.9);
                font-size: 1.1rem;
                position: relative;
                z-index: 1;
            }

            .search-box {
                background: white;
                border-radius: 50px;
                box-shadow: 0 10px 30px rgba(0, 131, 143, 0.2);
                padding: 8px 8px 8px 25px;
                display: flex;
                align-items: center;
                position: relative;
                z-index: 1;
            }

            .search-box input {
                border: none;
                outline: none;
                flex: 1;
                padding: 10px;
                font-size: 1rem;
            }

            .search-box button {
                background: var(--primary-cyan);
                border: none;
                border-radius: 50px;
                padding: 12px 35px;
                color: white;
                font-weight: 600;
                transition: all 0.3s;
            }

            .search-box button:hover {
                background: var(--dark-cyan);
                transform: translateX(2px);
            }

            .filters-section {
                background: white;
                border-radius: 15px;
                padding: 25px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
                margin-top: -40px;
                position: relative;
                z-index: 2;
            }

            .filter-btn {
                border: 2px solid #e0e0e0;
                background: white;
                padding: 8px 20px;
                border-radius: 25px;
                margin: 5px;
                transition: all 0.3s;
                font-weight: 500;
            }

            .filter-btn:hover,
            .filter-btn.active {
                border-color: var(--primary-cyan);
                background: var(--primary-cyan);
                color: white;
            }

            .listing-course-card {
                background: white;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
                transition: all 0.3s;
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .listing-course-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 35px rgba(0, 131, 143, 0.2);
            }

            .course-thumbnail {
                height: 200px;
                background: linear-gradient(135deg, var(--light-cyan) 0%, var(--primary-cyan) 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            .course-thumbnail i {
                font-size: 4rem;
                color: rgba(255, 255, 255, 0.3);
            }

            .course-badge {
                position: absolute;
                top: 15px;
                right: 15px;
                padding: 5px 15px;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
            }

            .badge-free {
                background: var(--accent-yellow);
                color: #333;
            }

            .badge-premium {
                background: var(--accent-purple);
                color: white;
            }

            .badge-live {
                background: var(--accent-pink);
                color: white;
            }

            .course-body {
                padding: 20px;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
            }

            .course-title {
                font-weight: 700;
                font-size: 1.2rem;
                color: #333;
                margin-bottom: 10px;
                line-height: 1.4;
            }

            .course-meta {
                display: flex;
                gap: 15px;
                font-size: 0.85rem;
                color: #666;
                margin-bottom: 15px;
            }

            .course-meta span {
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .course-description {
                color: #666;
                font-size: 0.9rem;
                line-height: 1.6;
                margin-bottom: 15px;
                flex-grow: 1;
            }

            .course-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-top: 15px;
                border-top: 1px solid #f0f0f0;
            }

            .course-price {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--primary-cyan);
            }

            .course-price.free {
                color: var(--accent-yellow);
            }

            .enroll-btn {
                background: var(--primary-cyan);
                color: white;
                border: none;
                padding: 10px 25px;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s;
            }

            .enroll-btn:hover {
                background: var(--dark-cyan);
                transform: scale(1.05);
            }

            .difficulty-badge {
                display: inline-block;
                padding: 3px 12px;
                border-radius: 15px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .difficulty-beginner {
                background: #e8f5e9;
                color: #2e7d32;
            }

            .difficulty-intermediate {
                background: #fff3e0;
                color: #ef6c00;
            }

            .difficulty-advanced {
                background: #fce4ec;
                color: #c2185b;
            }

            .pagination {
                margin-top: 50px;
            }

            .page-link {
                border: none;
                color: var(--primary-cyan);
                font-weight: 600;
                padding: 10px 18px;
                margin: 0 3px;
                border-radius: 8px;
                transition: all 0.3s;
            }

            .page-link:hover {
                background: var(--light-cyan);
                color: white;
            }

            .page-item.active .page-link {
                background: var(--primary-cyan);
                color: white;
            }

            .section-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
            }

            .results-count {
                color: #666;
                font-size: 1rem;
            }

            .sort-dropdown {
                border: 2px solid #e0e0e0;
                border-radius: 8px;
                padding: 8px 15px;
                outline: none;
                transition: all 0.3s;
            }

            .sort-dropdown:focus {
                border-color: var(--primary-cyan);
            }

            @media (max-width: 768px) {
                .hero-section h1 {
                    font-size: 1.8rem;
                }

                .search-box {
                    flex-direction: column;
                    padding: 15px;
                    border-radius: 15px;
                }

                .search-box input {
                    width: 100%;
                    margin-bottom: 10px;
                }

                .search-box button {
                    width: 100%;
                }
            }
        </style>
    @endpush
</x-guest-layout>
