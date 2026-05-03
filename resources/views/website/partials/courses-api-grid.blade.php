@foreach ($courses as $course)
    <div class="col-md-6 col-lg-3 mb-4">
        <x-course-item :course="$course" :itemType="'trending'" />
    </div>
@endforeach
