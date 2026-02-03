<x-guest-layout>
    <div>
        <!-- Hero Section -->
     
       @include('components.courses-filter')    

        <!-- Main Content -->
        <div class="container">
            <!-- Advanced Search Card -->


            <!-- Results Header -->
            <div class="results-header">
                <div class="results-count">
                    <strong id="courseCount">{{ $courses->total() }}</strong> courses found
                </div>

            </div>

            <!-- Course Grid -->
            <div class="row g-4 mb-5" id="courseGrid">
                @foreach ($courses as $course)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <x-course-item :course="$course" :itemType="'trending'" />
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
    @endpush
</x-guest-layout>
