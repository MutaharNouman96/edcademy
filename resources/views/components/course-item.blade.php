 <div class="listing-course-card border">
     <!-- Thumbnail -->
     <div class="course-thumbnail">
         @if ($course->thumbnail != null)
             <img src="{{ $course->thumbnail_path }}" alt="{{ $course->title }}">
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
             <span><i class="fas fa-video"></i> {{ $course->lessons->count() }}
                 lessons</span>

             @php
                 $avgRating = $course->reviews->avg('rating');
             @endphp


             <span>
                 <i class="fas fa-star text-warning"></i>
                 {{ number_format($avgRating, 1) }}
             </span>

         </div>

         <div class="d-flex justify-content-between align-items-center mt-3">
             <span class="course-price">${{ number_format($course->price, 2) }}</span>
             <a href="{{ route('web.course.show', ['slug' => $course->slug, 'id' => $course->id]) }}" class="btn btn-sm"
                 style="background: var(--primary-cyan); color: white">Enroll Now</a>
         </div>
     </div>
 </div>
