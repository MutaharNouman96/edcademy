@props(['course'])

@php
    $courseUrl = route('web.course.show', ['slug' => $course->slug, 'id' => $course->id]);
    $educatorUrl = route('web.educator.show', $course->educator->id);
    $reviewCount = $course->reviews->count();
    $avgRating = $reviewCount ? round($course->reviews->avg('rating'), 1) : 0;
    $educatorInitials = collect(explode(' ', trim($course->educator->full_name)))
        ->filter()
        ->take(2)
        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
        ->implode('');
@endphp

<div class="col-lg-4 col-md-6 col-sm-12">
    <article class="glass-landing--card">
        <a class="glass-landing--card-media" href="{{ $courseUrl }}" aria-label="{{ $course->title }}">
            @if ($course->thumbnail)
                <img src="{{ $course->thumbnail_path }}" alt="{{ $course->title }}"
                    class="glass-landing--card-img" loading="lazy" />
            @else
                <div class="glass-landing--card-placeholder">
                    <i class="bi bi-play-circle" aria-hidden="true"></i>
                </div>
            @endif

            @if ($reviewCount > 0)
                <span class="glass-landing--card-rating">
                    <i class="fas fa-star" aria-hidden="true"></i>
                    <strong>{{ number_format($avgRating, 1) }}</strong>
                    <span class="glass-landing--card-rating-count">({{ $reviewCount }})</span>
                </span>
            @endif
        </a>

        <div class="glass-landing--card-body">
            <a class="glass-landing--card-title-link" href="{{ $courseUrl }}">
                <h3 class="glass-landing--card-title">{{ $course->title }}</h3>
            </a>

            <div class="glass-landing--card-educator-row">
                <a class="glass-landing--card-educator" href="{{ $educatorUrl }}">
                    @if ($course->educator->profile_picture)
                        <img src="{{ $course->educator->profile_picture_url }}" alt=""
                            class="glass-landing--card-educator-avatar" />
                    @else
                        <span
                            class="glass-landing--card-educator-avatar glass-landing--card-educator-avatar--initials"
                            aria-hidden="true">{{ $educatorInitials ?: '?' }}</span>
                    @endif
                    <span class="glass-landing--card-educator-name">{{ $course->educator->full_name }}</span>
                </a>
                <a href="{{ $educatorUrl }}" class="glass-landing--card-follow">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>
                    <span>Follow</span>
                </a>
            </div>

            <div class="glass-landing--card-footer">
                <div class="glass-landing--card-price">
                    <span class="glass-landing--card-price-currency">$</span>{{ $course->price }}
                </div>
                <a href="{{ $courseUrl }}" class="glass-landing--card-cta">
                    <span>View Course</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </article>
</div>
