<div class="col-lg-4 col-md-6 mb-4">
    <div class="course-card">
        <!-- Thumbnail -->
        <div class="course-thumbnail">

            @if ($course->thumbnail!=null)
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

                <a href="{{ route('web.courses.show', $course->id) }}" class="enroll-btn">
                    Enroll Now
                </a>
            </div>
        </div>

    </div>
</div>
