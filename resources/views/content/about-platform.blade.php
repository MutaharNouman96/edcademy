<x-guest-layout>
    <div class="container my-5">
        <h1>About Ed-Cademy</h1>
        <div class="edcademy-info-section mt-4">
            <p class="lead">
                Ed-Cademy is a platform where educators can share their knowledge, support learners, and earn additional income through courses, tutoring, and educational materials.
            </p>
            <p>
                Whether you are exploring free resources, enrolling in a course, or teaching on the platform, Ed-Cademy connects learners with verified educators and high-quality educational content.
            </p>
        </div>
        <div style="margin-top: 2rem;">
            <a href="{{ route('content.browse-courses') }}" class="btn btn-primary">
                Browse Courses
            </a>
            <a href="{{ route('content.find-an-educator') }}" class="btn btn-outline-primary ms-2">
                Find an Educator
            </a>
        </div>
    </div>
</x-guest-layout>
