@php
    $tabs = [
        ['route' => 'admin.courses.show', 'label' => 'Overview', 'icon' => 'bi-grid-1x2'],
        ['route' => 'admin.courses.purchases', 'label' => 'Purchases', 'icon' => 'bi-people'],
        ['route' => 'admin.courses.revenue', 'label' => 'Revenue', 'icon' => 'bi-cash-stack'],
        ['route' => 'admin.courses.reviews', 'label' => 'Reviews', 'icon' => 'bi-star-half'],
        ['route' => 'admin.courses.content', 'label' => 'Content', 'icon' => 'bi-collection-play'],
    ];
@endphp

<div class="course-subnav mb-4">
    <div class="nav nav-pills flex-wrap gap-2">
        @foreach ($tabs as $tab)
            <a href="{{ route($tab['route'], $course->id) }}"
                class="nav-link {{ request()->routeIs($tab['route']) ? 'active' : '' }}">
                <i class="bi {{ $tab['icon'] }} me-1"></i>{{ $tab['label'] }}
            </a>
        @endforeach
    </div>
</div>
