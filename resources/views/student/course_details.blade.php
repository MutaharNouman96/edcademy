<x-student-layout>


    <!-- Overlay for mobile sidebar -->
    <div class="overlay"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-xl-2 sidebar p-0">
                <div class="p-3">
                    <h5 class="fw-bold mb-3" style="color: var(--primary-orange);">Course Content</h5>

                    <!-- Progress Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small">Course Progress</span>
                            <span class="small fw-bold">35%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 35%;" aria-valuenow="35"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <!-- Chapters List -->
                    <div class="chapters-list">
                        @foreach($course_chapters as $c)
                            <div class="chapter-item p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $c->title }}</h6>
                                        <p class="mb-0 small text-muted">{{ $course_lessons->where('course_section_id', $c->id)->count() }} lessons</p>
                                    </div>
                                    <i class="bi bi-play-circle text-primary"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-xl-10 main-content">
                <!-- Current Video Section -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card p-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="fw-bold mb-3">Current Lesson: HTML & CSS Fundamentals</h4>
                                    <div class="video-thumbnail mb-3 position-relative">
                                        <img src="https://placehold.co/800x450/FF6B35/white?text=Video+Thumbnail"
                                            class="img-fluid rounded" alt="Video Thumbnail">
                                        <div class="play-icon">
                                            <i class="bi bi-play-fill"></i>
                                        </div>
                                        <div class="video-duration">15:30</div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <span class="badge bg-primary me-2">Chapter 1</span>
                                            <span class="badge bg-secondary">Lesson 4 of 5</span>
                                        </div>
                                        <button class="btn btn-primary">
                                            <i class="bi bi-play-fill me-2"></i>Continue Watching
                                        </button>
                                    </div>
                                    <p class="text-muted">In this lesson, we'll explore the core concepts of HTML and
                                        CSS, including semantic markup, CSS selectors, and responsive design principles.
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="fw-bold mb-3">Up Next</h5>
                                    <div class="list-group">
                                        <a href="#"
                                            class="list-group-item list-group-item-action d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="https://placehold.co/80x45/FF8C5A/white?text=5"
                                                    class="rounded me-3" alt="Thumbnail">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">JavaScript Basics</h6>
                                                <small class="text-muted">12:45</small>
                                            </div>
                                        </a>
                                        <a href="#"
                                            class="list-group-item list-group-item-action d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="https://placehold.co/80x45/FF8C5A/white?text=6"
                                                    class="rounded me-3" alt="Thumbnail">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">Chapter 1 Recap</h6>
                                                <small class="text-muted">08:20</small>
                                            </div>
                                        </a>
                                        <a href="#"
                                            class="list-group-item list-group-item-action d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="https://placehold.co/80x45/E55A2B/white?text=1"
                                                    class="rounded me-3" alt="Thumbnail">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">Chapter 2: Introduction</h6>
                                                <small class="text-muted">10:15</small>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="fw-bold mb-4">{{ $course_name->title }}</h2>
                <!-- Chapter Lessons -->
                @foreach($course_chapters as $section)
                    <div class="row">
                        <div class="col-12">
                            <h4 class="fw-bold mb-4">{{ $section->title }}</h4>
                            <div class="list-group">
                                @foreach($course_lessons->where('course_section_id', $section->id) as $lesson)
                                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                        <div class="flex-shrink-0 me-3 position-relative">
                                            <img src="https://placehold.co/120x67/FF8C5A/white?text=1" class="rounded"
                                                alt="Thumbnail">
                                            <div class="video-duration text-muted mt-2"><i class="bi bi-clock-fill"></i> {{ $lesson->duration }} minutes</div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="mb-1">{{ $lesson->title }}</h5>
                                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                            </div>
                                            <p class="mb-1 text-muted">{{ $lesson->description }}</p>
                                            <small>Watched 2 days ago</small>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.overlay');

            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });

            overlay.addEventListener('click', function () {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });

            // Update progress bar based on completed chapters
            const progressBar = document.querySelector('.progress-bar');
            const completedChapters = document.querySelectorAll('.chapter-item .bi-check-circle-fill').length;
            const totalChapters = document.querySelectorAll('.chapter-item').length;
            const progressPercentage = (completedChapters / totalChapters) * 100;

            progressBar.style.width = `${progressPercentage}%`;
            progressBar.setAttribute('aria-valuenow', progressPercentage);
            progressBar.textContent = `${Math.round(progressPercentage)}%`;
        });
    </script>
</x-student-layout>
