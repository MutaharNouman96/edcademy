<x-guest-layout>
    <div class="container my-5">
        <h1>Student Blog</h1>
        <div class="edcademy-info-section mt-4">
            <p>
                The Student Blog includes articles written by educators, tutors, and education professionals.
            </p>
            <p>Topics may include:</p>
            <ul>
                <li>Study skills</li>
                <li>Time management</li>
                <li>Subject-specific tips</li>
                <li>Exam preparation</li>
                <li>Academic confidence</li>
                <li>Family support strategies</li>
            </ul>
            <p>
                The blog also helps educators build visibility by sharing expertise and insights.
            </p>
            <p>
                All content is reviewed before publication to ensure quality and alignment with the platform&rsquo;s mission.
            </p>
        </div>
        <div style="margin-top: 2rem;">
            <a href="{{ route('blogs.index') }}" class="btn btn-primary">
                Read the Blog
            </a>
        </div>
    </div>
</x-guest-layout>
