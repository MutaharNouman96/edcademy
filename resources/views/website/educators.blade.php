<x-guest-layout>
    <div>
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container hero-content">
                <h1 class="text-dark">Find Your Perfect Educator</h1>
                <p class="text-dark">
                    Connect with expert educators tailored to your learning
                    needs
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <!-- Advanced Search Card -->
            <div class="advanced-search-card">
                <h4 class="search-title">
                    <i class="fas fa-search"></i>
                    Search Educators
                </h4>

                <div class="row">
                    <!-- Keyword Search -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Search by Name or Keyword</label>
<div class="search-input-group">
    <i class="fas fa-search"></i>
    <input type="text" class="search-input" id="keywordSearch"
        placeholder="e.g., Math, Physics, Dr. Smith..." />
</div>
                    </div>

                    <!-- Primary Subject -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Primary Subject</label>
<div class="search-input-group">
    <i class="fas fa-book"></i>
    <select class="form-select search-input" id="subjectFilter">
        <option value="">All Subjects</option>
        <option value="mathematics">Mathematics</option>
        <option value="science">Science</option>
        <option value="programming">Programming</option>
        <option value="languages">Languages</option>
        <option value="arts">Arts & Design</option>
        <option value="business">Business</option>
        <option value="music">Music</option>
    </select>
</div>
                    </div>

                    <!-- Teaching Levels -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Teaching Levels</label>
<div class="filter-chips" id="levelFilters">
    <div class="filter-chip" data-filter="Elementary">
        Elementary
    </div>
    <div class="filter-chip" data-filter="Middle School">
        Middle School
    </div>
    <div class="filter-chip" data-filter="High School">
        High School
    </div>
    <div class="filter-chip" data-filter="College">
        College
    </div>
    <div class="filter-chip" data-filter="Professional">
        Professional
    </div>
</div>
                    </div>

                    <!-- Teaching Style -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Preferred Teaching Style</label>
<div class="filter-chips" id="styleFilters">
    <div class="filter-chip" data-filter="Hands-on">
        Hands-on
    </div>
    <div class="filter-chip" data-filter="Interactive">
        Interactive
    </div>
    <div class="filter-chip" data-filter="Project-based">
        Project-based
    </div>
    <div class="filter-chip" data-filter="Lecture-based">
        Lecture-based
    </div>
    <div class="filter-chip" data-filter="Visual Learning">
        Visual Learning
    </div>
</div>
                    </div>

                    <!-- Hourly Rate Range -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Maximum Hourly Rate</label>
                        <div class="price-range-container">
                            <input type="range" class="form-range" min="0" max="200" value="100"
                                id="priceRange" oninput="updatePrice(this.value)" />
                            <div class="price-values">
                                <span>$0</span>
                                <span id="maxPrice">$100</span>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Filters -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Additional Filters</label>
<div class="filter-chips" id="additionalFilters">
    <div class="filter-chip" data-filter="certified">
        Certified
    </div>
    <div class="filter-chip" data-filter="top_rated">
        Top Rated
    </div>
</div>
                    </div>


                </div>
            </div>

            <!-- Results Header -->
            <div class="results-header">
<div class="results-count">
    <strong id="educatorCount">{{ $educators->total() }}</strong> educators found
</div>
<select class="sort-dropdown" id="sortDropdown">
    <option value="highest_rated">Highest Rated</option>
    <option value="lowest_price">Lowest Price</option>
    <option value="highest_price">Highest Price</option>
    <option value="most_students">Most Students</option>
    <option value="most_experience">Most Experience</option>
</select>
            </div>

                        <div class="row g-4 mb-5" id="educatorGrid">
                @foreach ($educators as $educator)
                    <div class="col-lg-4 col-md-6">
                        <div class="educator-card">
                            <div class="educator-avatar-section"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-user-tie"></i>
                                @if ($educator->educatorProfile && $educator->educatorProfile->is_featured)
                                    <span class="featured-badge">FEATURED</span>
                                @endif
                                @if ($educator->is_online) {{-- Assuming 'is_online' attribute exists for user --}}
                                    <div class="online-indicator"></div>
                                @endif
                            </div>
                            <div class="educator-body">
                                <h3 class="educator-name">{{ $educator->first_name .' '. $educator->last_name }}</h3>
                                <p class="educator-subject">
                                    {{ $educator->educatorProfile->primary_subject ?? 'N/A' }}
                                </p>

                                <div class="rating-section">
                                    <div class="rating-stars">
                                        @php
                                            $avgRating = $educator->educatorReviews->avg('rating');
                                            $fullStars = floor($avgRating);
                                            $halfStar = ceil($avgRating - $fullStars);
                                            $emptyStars = 5 - $fullStars - $halfStar;
                                        @endphp
                                        @for ($i = 0; $i < $fullStars; $i++)

                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @if ($halfStar)
                                            <i class="fas fa-star-half-alt"></i>
                                        @endif
                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <i class="far fa-star"></i> {{-- Assuming 'far fa-star' for empty stars --}}
                                        @endfor
                                    </div>
                                    <span class="rating-text">
                                        {{ number_format($avgRating, 1) }}
                                        ({{ $educator->educatorReviews->count() }} reviews)
                                    </span>
                                </div>

                                @if ($educator->educatorProfile && $educator->educatorProfile->teaching_style)
                                    <div class="teaching-style-badge">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        {{ $educator->educatorProfile->teaching_style }}
                                    </div>
                                @endif

                                <div class="educator-stats">
                                    <span class="stat-item">
                                        <i class="fas fa-user-graduate"></i>
                                        {{ $educator->students_count ?? '0' }}+ Students
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        {{ $educator->educatorProfile->years_experience ?? '0' }} years
                                    </span>
                                </div>

                                <div class="teaching-levels">
                                    @if ($educator->educatorProfile && $educator->educatorProfile->teaching_levels)
                                        @foreach (json_decode($educator->educatorProfile->teaching_levels) as $level)
                                            <span class="level-badge">{{ $level }}</span>
                                        @endforeach
                                    @endif
                                </div>

                                <p class="educator-bio">
                                    {{ $educator->educatorProfile->bio ?? 'No bio available.' }}
                                </p>

                                @if ($educator->educatorProfile && $educator->educatorProfile->certifications)
                                    <div class="certifications-badge">
                                        <i class="fas fa-certificate"></i>
                                        {{ $educator->educatorProfile->certifications }}
                                    </div>
                                @endif

                                <div class="educator-footer">
                                    <div class="hourly-rate">
                                        <span class="rate-label">Starting at</span>
                                        <span class="rate-amount">${{ $educator->educatorProfile->hourly_rate ?? '0' }}<small>/hr</small></span>
                                    </div>
                                    <a href="{{ route('web.educator.show', $educator->id) }}" class="action-btn">
                                        <i class="fas fa-calendar-check me-1"></i>Book
                                    </a>
                                </div>
                                {{-- <a href="{{ route('web.educator.show' , ['educator' => $educator->id]) }}" class="action-btn">
                                    <i class="fas fa-calendar-check me-1"></i>Book
                                </a> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $educators->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const keywordSearch = document.getElementById('keywordSearch');
            const subjectFilter = document.getElementById('subjectFilter');
            const levelFilters = document.getElementById('levelFilters');
            const styleFilters = document.getElementById('styleFilters');
            const priceRange = document.getElementById('priceRange');
            const maxPriceSpan = document.getElementById('maxPrice');
            const additionalFilters = document.getElementById('additionalFilters');
            const sortDropdown = document.getElementById('sortDropdown');
            const educatorGrid = document.getElementById('educatorGrid');
            const educatorCount = document.getElementById('educatorCount');
            const paginationContainer = document.querySelector('.d-flex.justify-content-center.mt-4');

            // Function to update price display
            window.updatePrice = function(value) {
                maxPriceSpan.textContent = '$' + value;
            };

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
                if (subjectFilter.value) {
                    params.subject = subjectFilter.value;
                }

                const selectedLevels = Array.from(levelFilters.children)
                    .filter(chip => chip.classList.contains('active'))
                    .map(chip => chip.dataset.filter);
                if (selectedLevels.length > 0) {
                    params.levels = selectedLevels;
                }

                const selectedStyles = Array.from(styleFilters.children)
                    .filter(chip => chip.classList.contains('active'))
                    .map(chip => chip.dataset.filter);
                if (selectedStyles.length > 0) {
                    params.styles = selectedStyles;
                }

                params.max_rate = priceRange.value;

                const selectedAdditionalFilters = Array.from(additionalFilters.children)
                    .filter(chip => chip.classList.contains('active'))
                    .map(chip => chip.dataset.filter);
                if (selectedAdditionalFilters.length > 0) {
                    params.additional_filters = selectedAdditionalFilters;
                }

                if (sortDropdown.value) {
                    params.sort_by = sortDropdown.value;
                }

                return params;
            }

            // Function to fetch and render educators
            async function fetchEducators(page = 1) {
                const params = collectFilterParams();
                const queryString = new URLSearchParams(params).toString();
                const url = `{{ route('api.educators.index') }}?page=${page}&${queryString}`;

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Important for Laravel's AJAX detection
                        }
                    });
                    const data = await response.json(); // Assuming JSON response

                    // Clear existing educators
                    educatorGrid.innerHTML = '';

                    // Update educator count
                    educatorCount.textContent = data.total;

                    // Render new educators
                    data.data.forEach(educator => {
                        const educatorCardHtml = `
                            <div class="col-lg-4 col-md-6">
                                <div class="educator-card">
                                    <div class="educator-avatar-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <i class="fas fa-user-tie"></i>
                                        ${educator.educator_profile && educator.educator_profile.is_featured ? '<span class="featured-badge">FEATURED</span>' : ''}
                                        ${educator.is_online ? '<div class="online-indicator"></div>' : ''}
                                    </div>
                                    <div class="educator-body">
                                        <h3 class="educator-name">${educator.name}</h3>
                                        <p class="educator-subject">
                                            ${educator.educator_profile ? educator.educator_profile.main_subject : 'N/A'}
                                        </p>
                                        <div class="rating-section">
                                            <div class="rating-stars">
                                                ${generateStarRating(educator.avg_rating)}
                                            </div>
                                            <span class="rating-text">
                                                ${educator.avg_rating ? parseFloat(educator.avg_rating).toFixed(1) : '0.0'}
                                                (${educator.educator_reviews_count} reviews)
                                            </span>
                                        </div>
                                        ${educator.educator_profile && educator.educator_profile.teaching_style ? `
                                            <div class="teaching-style-badge">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                                ${educator.educator_profile.teaching_style}
                                            </div>
                                        ` : ''}
                                        <div class="educator-stats">
                                            <span class="stat-item">
                                                <i class="fas fa-user-graduate"></i>
                                                ${educator.students_count ?? '0'}+ Students
                                            </span>
                                            <span class="stat-item">
                                                <i class="fas fa-clock"></i>
                                                ${educator.educator_profile ? educator.educator_profile.years_experience : '0'} years
                                            </span>
                                        </div>
                                        <div class="teaching-levels">
                                            ${educator.educator_profile && educator.educator_profile.teaching_levels ?
                                                JSON.parse(educator.educator_profile.teaching_levels).map(level => `<span class="level-badge">${level}</span>`).join('')
                                                : ''}
                                        </div>
                                        <p class="educator-bio">
                                            ${educator.educator_profile ? educator.educator_profile.bio : 'No bio available.'}
                                        </p>
                                        ${educator.educator_profile && educator.educator_profile.certifications ? `
                                            <div class="certifications-badge">
                                                <i class="fas fa-certificate"></i>
                                                ${educator.educator_profile.certifications}
                                            </div>
                                        ` : ''}
                                        <div class="educator-footer">
                                            <div class="hourly-rate">
                                                <span class="rate-label">Starting at</span>
                                                <span class="rate-amount">$${educator.educator_profile ? educator.educator_profile.hourly_rate : '0'}<small>/hr</small></span>
                                            </div>
                                            <a href="/educator/${educator.id}" class="action-btn">
                                                <i class="fas fa-calendar-check me-1"></i>Book
                                            </a>
                                        </div>
                                </div>
                            </div>
                        `;
                        educatorGrid.insertAdjacentHTML('beforeend', educatorCardHtml);
                    });

                    // Update pagination links
                    renderPagination(data);

                } catch (error) {
                    console.error('Error fetching educators:', error);
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
                        fetchEducators(data.current_page - 1);
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
                            fetchEducators(link.label);
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
                        fetchEducators(data.current_page + 1);
                    });
                    li.appendChild(a);
                    ul.appendChild(li);
                }

                if (ul.children.length > 0) {
                    paginationContainer.appendChild(ul);
                }
            }


            // Event Listeners for filters and search
            keywordSearch.addEventListener('input', () => fetchEducators(1));
            subjectFilter.addEventListener('change', () => fetchEducators(1));
            priceRange.addEventListener('input', () => {
                updatePrice(priceRange.value);
                fetchEducators(1);
            });
            sortDropdown.addEventListener('change', () => fetchEducators(1));

            levelFilters.addEventListener('click', (event) => {
                if (event.target.classList.contains('filter-chip')) {
                    toggleChip(event.target);
                    fetchEducators(1);
                }
            });

            styleFilters.addEventListener('click', (event) => {
                if (event.target.classList.contains('filter-chip')) {
                    toggleChip(event.target);
                    fetchEducators(1);
                }
            });

            additionalFilters.addEventListener('click', (event) => {
                if (event.target.classList.contains('filter-chip')) {
                    toggleChip(event.target);
                    fetchEducators(1);
                }
            });

            // Initial fetch of educators when the page loads
            fetchEducators();
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
                /* border-color: var(--primary-cyan); */
                /* background: var(--primary-cyan); */
                border-color: #999;
                background: #999
                color: white;
            }

            .price-range-container {
                padding: 10px 0;
            }

            .price-values {
                display: flex;
                justify-content: space-between;
                margin-top: 10px;
                font-weight: 600;
                color: var(--primary-cyan);
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

            .educator-card {
                background: white;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
                transition: all 0.3s;
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .educator-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 40px rgba(0, 131, 143, 0.25);
            }

            .educator-avatar-section {
                height: 220px;
                background: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent-pink) 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            .educator-avatar-section i {
                font-size: 5rem;
                color: rgba(255, 255, 255, 0.4);
            }

            .featured-badge {
                position: absolute;
                top: 15px;
                left: 15px;
                background: var(--accent-yellow);
                color: #333;
                padding: 5px 12px;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 700;
            }

            .online-indicator {
                position: absolute;
                top: 15px;
                right: 15px;
                width: 15px;
                height: 15px;
                background: #4caf50;
                border: 3px solid white;
                border-radius: 50%;
                box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
            }

            .educator-body {
                padding: 25px;
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .educator-name {
                font-size: 1.4rem;
                font-weight: 700;
                color: #333;
                margin-bottom: 5px;
            }

            .educator-subject {
                color: var(--primary-cyan);
                font-weight: 600;
                margin-bottom: 15px;
                font-size: 1rem;
            }

            .rating-section {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 15px;
            }

            .rating-stars {
                color: var(--accent-yellow);
                font-size: 0.9rem;
            }

            .rating-text {
                color: #666;
                font-size: 0.9rem;
                font-weight: 600;
            }

            .teaching-style-badge {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                background: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent-pink) 100%);
                color: white;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
                margin-bottom: 15px;
                align-self: flex-start;
            }

            .educator-stats {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                margin-bottom: 15px;
                font-size: 0.85rem;
            }

            .stat-item {
                display: flex;
                align-items: center;
                gap: 5px;
                color: #666;
            }

            .stat-item i {
                color: var(--primary-cyan);
            }

            .teaching-levels {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                margin-bottom: 15px;
            }

            .level-badge {
                background: #e8f5f7;
                color: var(--primary-cyan);
                padding: 4px 10px;
                border-radius: 12px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .educator-bio {
                color: #666;
                line-height: 1.6;
                font-size: 0.9rem;
                margin-bottom: 15px;
                flex-grow: 1;
            }

            .certifications-badge {
                background: #fff3e0;
                border-left: 3px solid var(--accent-yellow);
                padding: 10px 12px;
                border-radius: 8px;
                font-size: 0.85rem;
                margin-bottom: 15px;
                color: #666;
            }

            .certifications-badge i {
                color: var(--accent-yellow);
                margin-right: 5px;
            }

            .educator-footer {
                border-top: 2px solid #f0f0f0;
                padding-top: 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .hourly-rate {
                display: flex;
                flex-direction: column;
            }

            .rate-label {
                font-size: 0.8rem;
                color: #999;
            }

            .rate-amount {
                font-size: 1.6rem;
                font-weight: 700;
                color: var(--primary-cyan);
            }

            .rate-amount small {
                font-size: 0.9rem;
                font-weight: 500;
            }

            .action-btn {
                background: var(--primary-cyan);
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.3s;
                font-size: 0.9rem;
            }

            .action-btn:hover {
                background: var(--dark-cyan);
                transform: scale(1.05);
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
