<x-guest-layout>
    <div>
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container hero-content">
                <h1>Find Your Perfect Educator</h1>
                <p>
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
                            <input type="text" class="search-input"
                                placeholder="e.g., Math, Physics, Dr. Smith..." />
                        </div>
                    </div>

                    <!-- Primary Subject -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Primary Subject</label>
                        <div class="search-input-group">
                            <i class="fas fa-book"></i>
                            <select class="form-select search-input">
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
                        <div class="filter-chips">
                            <div class="filter-chip" onclick="toggleChip(this)">
                                Elementary
                            </div>
                            <div class="filter-chip" onclick="toggleChip(this)">
                                Middle School
                            </div>
                            <div class="filter-chip" onclick="toggleChip(this)">
                                High School
                            </div>
                            <div class="filter-chip" onclick="toggleChip(this)">
                                College
                            </div>
                            <div class="filter-chip" onclick="toggleChip(this)">
                                Professional
                            </div>
                        </div>
                    </div>

                    <!-- Teaching Style -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Preferred Teaching Style</label>
                        <div class="filter-chips">
                            <div class="filter-chip" onclick="toggleChip(this)">
                                Hands-on
                            </div>
                            <div class="filter-chip" onclick="toggleChip(this)">
                                Interactive
                            </div>
                            <div class="filter-chip" onclick="toggleChip(this)">
                                Project-based
                            </div>
                            <div class="filter-chip" onclick="toggleChip(this)">
                                Lecture-based
                            </div>
                            <div class="filter-chip" onclick="toggleChip(this)">
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
                        <div class="filter-chips">
                            <div class="filter-chip" onclick="toggleChip(this)">
                                Certified
                            </div>
                            <div class="filter-chip" onclick="toggleChip(this)">
                                Top Rated
                            </div>
                            <div class="filter-chip" onclick="toggleChip(this)">
                                Available Now
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="col-12">
                        <button class="search-btn" onclick="searchEducators()">
                            <i class="fas fa-search me-2"></i>Search Educators
                        </button>
                    </div>
                </div>
            </div>

            <!-- Results Header -->
            <div class="results-header">
                <div class="results-count">
                    <strong>42</strong> educators found
                </div>
                <select class="sort-dropdown">
                    <option>Highest Rated</option>
                    <option>Lowest Price</option>
                    <option>Highest Price</option>
                    <option>Most Students</option>
                    <option>Most Experience</option>
                </select>
            </div>

            <!-- Educator Grid -->
            <div class="row g-4 mb-5">
                <!-- Educator 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="educator-card">
                        <div class="educator-avatar-section"
                            style="
                                background: linear-gradient(
                                    135deg,
                                    #667eea 0%,
                                    #764ba2 100%
                                );
                            ">
                            <i class="fas fa-user-tie"></i>
                            <span class="featured-badge">FEATURED</span>
                            <div class="online-indicator"></div>
                        </div>
                        <div class="educator-body">
                            <h3 class="educator-name">Dr. Sarah Johnson</h3>
                            <p class="educator-subject">
                                Mathematics & Physics
                            </p>

                            <div class="rating-section">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-text">4.9 (127 reviews)</span>
                            </div>

                            <div class="teaching-style-badge">
                                <i class="fas fa-hands-helping"></i>
                                Hands-on Learning
                            </div>

                            <div class="educator-stats">
                                <span class="stat-item">
                                    <i class="fas fa-user-graduate"></i>
                                    450+ Students
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    5 years
                                </span>
                            </div>

                            <div class="teaching-levels">
                                <span class="level-badge">High School</span>
                                <span class="level-badge">College</span>
                                <span class="level-badge">Professional</span>
                            </div>

                            <p class="educator-bio">
                                PhD in Applied Mathematics from MIT. Passionate
                                about making complex concepts simple and
                                engaging.
                            </p>

                            <div class="certifications-badge">
                                <i class="fas fa-certificate"></i>
                                PhD Mathematics (MIT), Teaching License
                            </div>

                            <div class="educator-footer">
                                <div class="hourly-rate">
                                    <span class="rate-label">Starting at</span>
                                    <span class="rate-amount">$85<small>/hr</small></span>
                                </div>
                                <button class="action-btn">
                                    <i class="fas fa-calendar-check me-1"></i>Book
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Educator 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="educator-card">
                        <div class="educator-avatar-section"
                            style="
                                background: linear-gradient(
                                    135deg,
                                    #f093fb 0%,
                                    #f5576c 100%
                                );
                            ">
                            <i class="fas fa-user"></i>
                            <div class="online-indicator"></div>
                        </div>
                        <div class="educator-body">
                            <h3 class="educator-name">Prof. Michael Chen</h3>
                            <p class="educator-subject">Computer Science</p>

                            <div class="rating-section">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="rating-text">4.8 (203 reviews)</span>
                            </div>

                            <div class="teaching-style-badge">
                                <i class="fas fa-project-diagram"></i>
                                Project-based
                            </div>

                            <div class="educator-stats">
                                <span class="stat-item">
                                    <i class="fas fa-user-graduate"></i>
                                    680+ Students
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    8 years
                                </span>
                            </div>

                            <div class="teaching-levels">
                                <span class="level-badge">Middle School</span>
                                <span class="level-badge">High School</span>
                                <span class="level-badge">College</span>
                            </div>

                            <p class="educator-bio">
                                Former Google engineer. Expert in Python,
                                JavaScript, and web development with real-world
                                projects.
                            </p>

                            <div class="certifications-badge">
                                <i class="fas fa-certificate"></i>
                                MSc CS (Stanford), AWS Certified
                            </div>

                            <div class="educator-footer">
                                <div class="hourly-rate">
                                    <span class="rate-label">Starting at</span>
                                    <span class="rate-amount">$95<small>/hr</small></span>
                                </div>
                                <button class="action-btn">
                                    <i class="fas fa-calendar-check me-1"></i>Book
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Educator 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="educator-card">
                        <div class="educator-avatar-section"
                            style="
                                background: linear-gradient(
                                    135deg,
                                    #4facfe 0%,
                                    #00f2fe 100%
                                );
                            ">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="educator-body">
                            <h3 class="educator-name">Emma Rodriguez</h3>
                            <p class="educator-subject">English & Literature</p>

                            <div class="rating-section">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="rating-text">5.0 (89 reviews)</span>
                            </div>

                            <div class="teaching-style-badge">
                                <i class="fas fa-users"></i>
                                Interactive
                            </div>

                            <div class="educator-stats">
                                <span class="stat-item">
                                    <i class="fas fa-user-graduate"></i>
                                    320+ Students
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    4 years
                                </span>
                            </div>

                            <div class="teaching-levels">
                                <span class="level-badge">Elementary</span>
                                <span class="level-badge">Middle School</span>
                                <span class="level-badge">High School</span>
                            </div>

                            <p class="educator-bio">
                                Published author specializing in creative
                                writing. Focus on building confidence and
                                fostering love for reading.
                            </p>

                            <div class="certifications-badge">
                                <i class="fas fa-certificate"></i>
                                MA English (Oxford), TESOL Certified
                            </div>

                            <div class="educator-footer">
                                <div class="hourly-rate">
                                    <span class="rate-label">Starting at</span>
                                    <span class="rate-amount">$65<small>/hr</small></span>
                                </div>
                                <button class="action-btn">
                                    <i class="fas fa-calendar-check me-1"></i>Book
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Educator 4 -->
                <div class="col-lg-4 col-md-6">
                    <div class="educator-card">
                        <div class="educator-avatar-section"
                            style="
                                background: linear-gradient(
                                    135deg,
                                    #fa709a 0%,
                                    #fee140 100%
                                );
                            ">
                            <i class="fas fa-user-md"></i>
                            <span class="featured-badge">TOP RATED</span>
                        </div>
                        <div class="educator-body">
                            <h3 class="educator-name">Dr. James Patterson</h3>
                            <p class="educator-subject">Chemistry & Biology</p>

                            <div class="rating-section">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-text">4.9 (156 reviews)</span>
                            </div>

                            <div class="teaching-style-badge">
                                <i class="fas fa-flask"></i>
                                Experimental
                            </div>

                            <div class="educator-stats">
                                <span class="stat-item">
                                    <i class="fas fa-user-graduate"></i>
                                    590+ Students
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    12 years
                                </span>
                            </div>

                            <div class="teaching-levels">
                                <span class="level-badge">High School</span>
                                <span class="level-badge">College</span>
                                <span class="level-badge">Professional</span>
                            </div>

                            <p class="educator-bio">
                                Award-winning professor. Specializing in organic
                                chemistry and biochemistry with engaging lab
                                demos.
                            </p>

                            <div class="certifications-badge">
                                <i class="fas fa-certificate"></i>
                                PhD Chemistry (Harvard), Research Fellow
                            </div>

                            <div class="educator-footer">
                                <div class="hourly-rate">
                                    <span class="rate-label">Starting at</span>
                                    <span class="rate-amount">$110<small>/hr</small></span>
                                </div>
                                <button class="action-btn">
                                    <i class="fas fa-calendar-check me-1"></i>Book
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Educator 5 -->
                <div class="col-lg-4 col-md-6">
                    <div class="educator-card">
                        <div class="educator-avatar-section"
                            style="
                                background: linear-gradient(
                                    135deg,
                                    #30cfd0 0%,
                                    #330867 100%
                                );
                            ">
                            <i class="fas fa-user-astronaut"></i>
                            <div class="online-indicator"></div>
                        </div>
                        <div class="educator-body">
                            <h3 class="educator-name">Maria Garcia</h3>
                            <p class="educator-subject">Spanish & French</p>

                            <div class="rating-section">
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-text">4.7 (94 reviews)</span>
                            </div>

                            <div class="teaching-style-badge">
                                <i class="fas fa-comments"></i>
                                Conversational
                            </div>

                            <div class="educator-stats">
                                <span class="stat-item">
                                    <i class="fas fa-user-graduate"></i>
                                    280+ Students
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    6 years
                                </span>
                            </div>

                            <div class="teaching-levels">
                                <span class="level-badge">All Levels</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
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
                border-color: var(--primary-cyan);
                background: var(--primary-cyan);
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
