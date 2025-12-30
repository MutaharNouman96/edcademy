<x-guest-layout>
    <div>
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container hero-content">
                <h1 class="text-dark">Discover Your Next Course</h1>
                <p class="text-dark">
                    Explore thousands of courses from expert educators
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <!-- Advanced Search Card -->
            <div class="advanced-search-card">
                <h4 class="search-title">
                    <i class="fas fa-search"></i>
                    Search Courses
                </h4>

                <div class="row">
                    <!-- Keyword Search -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Search by Title or Subject</label>
                        <div class="search-input-group">
                            <i class="fas fa-search"></i>
                            <input type="text" class="search-input" id="keywordSearch"
                                placeholder="e.g., Python, Math, Web Development..." />
                        </div>
                    </div>

                    <!-- Difficulty -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Difficulty Level</label>
                        <div class="filter-chips" id="difficultyFilters">
                            <div class="filter-chip" data-filter="Beginner">
                                Beginner
                            </div>
                            <div class="filter-chip" data-filter="Intermediate">
                                Intermediate
                            </div>
                            <div class="filter-chip" data-filter="Advanced">
                                Advanced
                            </div>
                        </div>
                    </div>

                    <!-- Price Type -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Price Type</label>
                        <div class="filter-chips" id="priceFilters">
                            <div class="filter-chip" data-filter="free">
                                Free
                            </div>
                            <div class="filter-chip" data-filter="paid">
                                Paid
                            </div>
                        </div>
                    </div>

                    <!-- Course Type -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Course Type</label>
                        <div class="filter-chips" id="typeFilters">
                            <div class="filter-chip" data-filter="Video">
                                Video
                            </div>
                            <div class="filter-chip" data-filter="Module">
                                Module
                            </div>
                            <div class="filter-chip" data-filter="Live">
                                Live
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="col-12 mt-3">
                        <button type="button" class="search-btn text-dark" id="searchBtn">
                            <i class="fas fa-search me-2 text-dark"></i>Search Courses
                        </button>
                    </div>

                </div>
            </div>

            <!-- Results Header -->
            <div class="results-header">
                <div class="results-count">
                    <strong id="courseCount">{{ $courses->total() }}</strong> courses found
                </div>
                <select class="sort-dropdown" id="sortDropdown">
                    <option value="newest">Newest First</option>
                    <option value="highest_rated">Highest Rated</option>
                    <option value="lowest_price">Price: Low to High</option>
                    <option value="highest_price">Price: High to Low</option>
                    <option value="most_popular">Most Popular</option>
                </select>
            </div>

            <!-- Course Grid -->
            <div class="row g-4 mb-5" id="courseGrid">
                @foreach ($courses as $course)
                    <div class="col-lg-4 col-md-6">
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
                                        $avgRating = $course->reviews->avg('rating');
                                    @endphp

                                    @if ($avgRating)
                                        <span>
                                            <i class="fas fa-star text-warning"></i> {{ number_format($avgRating, 1) }}
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('web.course.show', ['slug'=>$course->slug  , 'id' => $course->id]) }}" class="enroll-btn">
                                    Enroll Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $courses->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const keywordSearch = document.getElementById('keywordSearch');
            const categoryFilter = document.getElementById('categoryFilter');
            const difficultyFilters = document.getElementById('difficultyFilters');
            const priceFilters = document.getElementById('priceFilters');
            const typeFilters = document.getElementById('typeFilters');
            const sortDropdown = document.getElementById('sortDropdown');
            const courseGrid = document.getElementById('courseGrid');
            const courseCount = document.getElementById('courseCount');
            const paginationContainer = document.querySelector('.d-flex.justify-content-center.mt-4');

            // Function to toggle chip active state
            window.toggleChip = function(element) {
                element.classList.toggle('active');
            };

            // Function to collect all filter parameters
            function collectFilterParams() {
                const params = {};

                if (keywordSearch.value) {
                    params.search = keywordSearch.value;
                }

                const selectedDifficulties = Array.from(difficultyFilters.children)
                    .filter(chip => chip.classList.contains('active'))
                    .map(chip => chip.dataset.filter);
                if (selectedDifficulties.length > 0) {
                    params.difficulty = selectedDifficulties[0]; // Single selection for difficulty
                }

                const selectedPrices = Array.from(priceFilters.children)
                    .filter(chip => chip.classList.contains('active'))
                    .map(chip => chip.dataset.filter);
                if (selectedPrices.length > 0) {
                    params.price_type = selectedPrices[0]; // Single selection for price type
                }

                const selectedTypes = Array.from(typeFilters.children)
                    .filter(chip => chip.classList.contains('active'))
                    .map(chip => chip.dataset.filter);
                if (selectedTypes.length > 0) {
                    params.type = selectedTypes[0]; // Single selection for type
                }

                if (sortDropdown.value) {
                    params.sort_by = sortDropdown.value;
                }

                return params;
            }

            // Function to fetch and render courses
            async function fetchCourses(page = 1) {
                const params = collectFilterParams();
                const queryString = new URLSearchParams(params).toString();
                const url = `{{ url('/api/courses') }}?page=${page}&${queryString}`;

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Important for Laravel's AJAX detection
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json(); // Assuming JSON response

                    // Clear existing courses
                    courseGrid.innerHTML = '';

                    // Update course count
                    courseCount.textContent = data.total;

                    // Render new courses
                    data.data.forEach(course => {
                        const courseCardHtml = `
                            <div class="col-lg-4 col-md-6">
                                <div class="listing-course-card">
                                    <div class="course-thumbnail">
                                        ${course.thumbnail ?
                                            `<img src="/storage/${course.thumbnail}" alt="${course.title}">` :
                                            '<i class="fas fa-book-open fs-1 text-muted"></i>'
                                        }
                                        ${(!course.is_free && course.price > 0) ?
                                            '<span class="course-badge badge-premium">Premium</span>' : ''
                                        }
                                    </div>
                                    <div class="course-body">
                                        ${course.difficulty ? `
                                            <span class="difficulty-badge difficulty-${course.difficulty.toLowerCase()}">
                                                ${course.difficulty.charAt(0).toUpperCase() + course.difficulty.slice(1)}
                                            </span>
                                        ` : ''}
                                        <h5 class="course-title mt-2">${course.title}</h5>
                                        <div class="course-meta">
                                            <span><i class="fas fa-clock"></i> ${course.duration || '–'}</span>
                                            <span><i class="fas fa-video"></i> ${course.lessons_count} lessons</span>
                                            ${course.avg_rating > 0 ? `
                                                <span>
                                                    <i class="fas fa-star text-warning"></i> ${parseFloat(course.avg_rating).toFixed(1)}
                                                </span>
                                            ` : ''}
                                        </div>
                                        <p class="course-description">
                                            ${course.description.length > 120 ?
                                                course.description.substring(0, 120) + '...' :
                                                course.description
                                            }
                                        </p>
                                        <div class="educator-info">
                                            <small class="text-muted">
                                                By ${course.educator ? course.educator.name : 'Unknown'}
                                            </small>
                                        </div>
                                        <div class="course-footer">
                                            <span class="course-price">
                                                ${course.is_free ? 'Free' : '$' + parseFloat(course.price).toFixed(2)}
                                            </span>
                                            <a href="/course/${course.slug}" class="enroll-btn">
                                                Enroll Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        courseGrid.insertAdjacentHTML('beforeend', courseCardHtml);
                    });

                    // Update pagination links
                    renderPagination(data);

                } catch (error) {
                    console.error('Error fetching courses:', error);
                }
            }

            // Helper function to generate star ratings HTML
            function generateStarRating(avgRating) {
                let starsHtml = '';
                const fullStars = Math.floor(avgRating);
                const halfStar = Math.ceil(avgRating - fullStars);
                const emptyStars = 5 - fullStars - halfStar;

                for (let i = 0; i < fullStars; i++) {
                    starsHtml += '<i class="fas fa-star"></i>';
                }
                if (halfStar) {
                    starsHtml += '<i class="fas fa-star-half-alt"></i>';
                }
                for (let i = 0; i < emptyStars; i++) {
                    starsHtml += '<i class="far fa-star"></i>';
                }
                return starsHtml;
            }

            // Function to render pagination links
            function renderPagination(data) {
                paginationContainer.innerHTML = ''; // Clear existing pagination

                const ul = document.createElement('ul');
                ul.classList.add('pagination');

                // Previous Button
                if (data.prev_page_url) {
                    const li = document.createElement('li');
                    li.classList.add('page-item');
                    const a = document.createElement('a');
                    a.classList.add('page-link');
                    a.href = '#';
                    a.textContent = 'Previous';
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        fetchCourses(data.current_page - 1);
                    });
                    li.appendChild(a);
                    ul.appendChild(li);
                }

                // Page Numbers
                data.links.forEach(link => {
                    if (link.url && link.label.match(/^[0-9]+$/)) {
                        const li = document.createElement('li');
                        li.classList.add('page-item');
                        if (link.active) {
                            li.classList.add('active');
                        }
                        const a = document.createElement('a');
                        a.classList.add('page-link');
                        a.href = '#';
                        a.textContent = link.label;
                        a.addEventListener('click', (e) => {
                            e.preventDefault();
                            fetchCourses(link.label);
                        });
                        li.appendChild(a);
                        ul.appendChild(li);
                    }
                });

                // Next Button
                if (data.next_page_url) {
                    const li = document.createElement('li');
                    li.classList.add('page-item');
                    const a = document.createElement('a');
                    a.classList.add('page-link');
                    a.href = '#';
                    a.textContent = 'Next';
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        fetchCourses(data.current_page + 1);
                    });
                    li.appendChild(a);
                    ul.appendChild(li);
                }

                if (ul.children.length > 0) {
                    paginationContainer.appendChild(ul);
                }
            }

            // Event Listeners for filters and search
            // Search button click handler
            document.getElementById('searchBtn').addEventListener('click', () => fetchCourses(1));

            // Sort dropdown can trigger search immediately as it's a common UX pattern
            sortDropdown.addEventListener('change', () => fetchCourses(1));

            // Filter chip event listeners
            difficultyFilters.addEventListener('click', (event) => {
                if (event.target.classList.contains('filter-chip')) {
                    // Remove active from siblings in same group
                    Array.from(difficultyFilters.children).forEach(chip => {
                        if (chip !== event.target) chip.classList.remove('active');
                    });
                    toggleChip(event.target);
                }
            });

            priceFilters.addEventListener('click', (event) => {
                if (event.target.classList.contains('filter-chip')) {
                    // Remove active from siblings in same group
                    Array.from(priceFilters.children).forEach(chip => {
                        if (chip !== event.target) chip.classList.remove('active');
                    });
                    toggleChip(event.target);
                }
            });

            typeFilters.addEventListener('click', (event) => {
                if (event.target.classList.contains('filter-chip')) {
                    // Remove active from siblings in same group
                    Array.from(typeFilters.children).forEach(chip => {
                        if (chip !== event.target) chip.classList.remove('active');
                    });
                    toggleChip(event.target);
                }
            });
        });
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

            .hero-content {
                position: relative;
                z-index: 1;
                text-align: center;
            }

            .hero-section h1 {
                color: white;
                font-weight: 700;
                font-size: 2.5rem;
                margin-bottom: 1rem;
            }

            .hero-section p {
                color: rgba(255, 255, 255, 0.9);
                font-size: 1.1rem;
            }

            .advanced-search-card {
                background: white;
                border-radius: 20px;
                box-shadow: 0 10px 40px rgba(0, 131, 143, 0.2);
                padding: 30px;
                margin-top: -50px;
                position: relative;
                z-index: 2;
                margin-bottom: 40px;
            }

            .search-title {
                color: var(--primary-cyan);
                font-weight: 700;
                margin-bottom: 25px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .form-label {
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
            }

            .search-input-group {
                position: relative;
            }

            .search-input-group i {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--primary-cyan);
                z-index: 1;
            }

            .search-input,
            .form-select {
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                padding: 12px 15px 12px 45px;
                width: 100%;
                transition: all 0.3s;
            }

            .search-input:focus,
            .form-select:focus {
                outline: none;
                border-color: var(--primary-cyan);
                box-shadow: 0 0 0 3px rgba(0, 131, 143, 0.1);
            }

            .filter-chips {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .filter-chip {
                border: 2px solid #e0e0e0;
                background: white;
                padding: 8px 16px;
                border-radius: 25px;
                cursor: pointer;
                transition: all 0.3s;
                font-weight: 500;
                font-size: 0.9rem;
                user-select: none;
            }

            .filter-chip:hover {
                border-color: var(--light-cyan);
                transform: translateY(-2px);
            }

            .filter-chip.active {
                border-color: #999;
                background: #999;
                color: white;
            }

            .search-btn {
                background: var(--primary-cyan);
                color: white;
                border: none;
                border-radius: 12px;
                padding: 14px 40px;
                font-weight: 600;
                font-size: 1.1rem;
                transition: all 0.3s;
                width: 100%;
            }

            .search-btn:hover {
                background: var(--dark-cyan);
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(0, 131, 143, 0.3);
            }

            .results-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
            }

            .results-count {
                font-size: 1.1rem;
                color: #666;
            }

            .results-count strong {
                color: var(--primary-cyan);
                font-size: 1.3rem;
            }

            .sort-dropdown {
                border: 2px solid #e0e0e0;
                border-radius: 10px;
                padding: 10px 20px;
                font-weight: 600;
                background: white;
                cursor: pointer;
                transition: all 0.3s;
            }

            .sort-dropdown:focus {
                outline: none;
                border-color: var(--primary-cyan);
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

            .course-thumbnail img {
                width: 100%;
                height: 100%;
                object-fit: cover;
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

            .badge-premium {
                background: var(--accent-purple);
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

            .educator-info {
                margin-bottom: 15px;
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
                margin-bottom: 10px;
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

            @media (max-width: 768px) {
                .hero-section h1 {
                    font-size: 1.8rem;
                }

                .results-header {
                    flex-direction: column;
                    gap: 15px;
                    align-items: stretch;
                }

                .sort-dropdown {
                    width: 100%;
                }
            }
        </style>
    @endpush
</x-guest-layout>
