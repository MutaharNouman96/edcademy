<style>
    .advanced-search-card {
        background: linear-gradient(135deg,
                var(--dark-cyan) 0%,
                var(--primary-cyan) 100%);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        margin: 20px 0;
    }

    .search-title {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-title i {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 10px;
    }

    .filter-row {
        display: grid;
        grid-template-columns: 2fr 2fr 1fr 1fr 1fr;
        justify-content: space-between;
        gap: 15px;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .search-input-group {
        position: relative;
    }

    .search-input-group i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-cyan);
    }

    .search-input {
        width: 100%;
        padding: 12px 12px 12px 42px;
        border: none;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.95);
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .search-input:focus {
        outline: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    .filter-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .filter-chip {
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border-radius: 25px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        white-space: nowrap;
    }

    .filter-chip:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .filter-chip.active {
        background: #fff;
        color: var(--primary-cyan);
        border-color: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .sort-dropdown {
        width: 100%;
        padding: 12px 16px;
        border: none;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.95);
        font-size: 0.95rem;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23667eea' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
    }

    .sort-dropdown:focus {
        outline: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .search-btn {
        width: 100%;
        padding: 12px 24px;
        background: #fff;
        color: var(--primary-cyan) !important;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.25);
    }

    .search-btn i {
        color: var(--primary-cyan) !important;
    }

    /* Responsive Breakpoints */
    @media (max-width: 1200px) {
        .filter-group {
            min-width: 180px;
        }
    }

    @media (max-width: 992px) {
        .filter-group {
            min-width: calc(50% - 16px);
        }

        .filter-row {
            gap: 15px;
        }
    }

    @media (max-width: 768px) {
        .advanced-search-card {
            padding: 20px 16px;
            margin: 15px 10px;
        }

        .search-title {
            font-size: 1.25rem;
        }

        .filter-group {
            min-width: 100%;
        }

        .filter-chips {
            gap: 6px;
        }

        .filter-chip {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 480px) {
        .filter-row {
            grid-template-columns: 1fr;
        }

        .filter-group {
            min-width: 100%;
        }

        .filter-chips {
            gap: 6px;
        }
        .advanced-search-card {
            padding: 16px 12px;
            border-radius: 12px;
        }

        .search-title {
            font-size: 1.1rem;
        }

        .search-title i {
            padding: 8px;
        }

        .search-input,
        .sort-dropdown {
            padding: 10px 10px 10px 38px;
            font-size: 0.9rem;
        }

        .search-btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="advanced-search-card">
        <h4 class="search-title">
            
           Discover Your Next Course
        </h4>

        <div class="filter-row">

            <!-- Keyword Search -->
            <div class="filter-group  ">
                <label class="form-label">Search by Title or Subject</label>
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input
                        type="text"
                        class="search-input"
                        id="keywordSearch"
                        placeholder="e.g., Python, Math, Web Development..."
                    />
                </div>
            </div>

            <!-- Difficulty -->
            <div class="filter-group  ">
                <label class="form-label">Difficulty Level</label>
                <div class="filter-chips" id="difficultyFilters">
                    <div class="filter-chip" data-filter="Beginner">Beginner</div>
                    <div class="filter-chip" data-filter="Intermediate">Intermediate</div>
                    <div class="filter-chip" data-filter="Advanced">Advanced</div>
                </div>
            </div>

            <!-- Price Type -->
            <div class="filter-group  ">
                <label class="form-label">Price Type</label>
                <div class="filter-chips" id="priceFilters">
                    <div class="filter-chip" data-filter="free">Free</div>
                    <div class="filter-chip" data-filter="paid">Paid</div>
                </div>
            </div>

            <!-- Course Type -->
            <div class="filter-group   d-none">
                <label class="form-label">Course Type</label>
                <div class="filter-chips" id="typeFilters">
                    <div class="filter-chip" data-filter="Video">Video</div>
                    <div class="filter-chip" data-filter="Module">Module</div>
                    <div class="filter-chip" data-filter="Live">Live</div>
                </div>
            </div>

            <!-- Sort -->
            <div class="filter-group   ">
                <label class="form-label">Sort By</label>
                <select class="sort-dropdown" id="sortDropdown">
                    <option value="newest">Newest First</option>
                    <option value="highest_rated">Highest Rated</option>
                    <option value="lowest_price">Price: Low to High</option>
                    <option value="highest_price">Price: High to Low</option>
                    <option value="most_popular">Most Popular</option>
                </select>
            </div>

            <!-- Addon Filters Modal Trigger -->

          
            <!-- Search Button -->
            <div class="filter-group  ">
                <label class="form-label d-md-block d-none">&nbsp;</label>
                <button type="button" class="search-btn text-dark" id="searchBtn">
                    <i class="fas fa-search me-2 text-dark"></i>
                    Search Courses
                </button>
            </div>

        </div>
    </div>
</div>

