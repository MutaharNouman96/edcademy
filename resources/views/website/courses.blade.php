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
                    const url = `{{ url('/api/courses') }}?page=${page}&render_component=1&${queryString}`;

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest' // Important for Laravel's AJAX detection
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        courseGrid.innerHTML = typeof data.html === 'string' ? data.html : '';

                        courseCount.textContent = data.total;

                        renderPagination(data);

                    } catch (error) {
                        console.error('Error fetching courses:', error);
                    }
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
