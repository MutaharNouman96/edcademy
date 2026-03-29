<x-guest-layout>

    <div class="container my-5">
        <div>
            <h1>Browse Courses</h1>
        </div>
        <div class="edcademy-info-section" style="margin-top: 2rem;">
            <p>
                Ed-Cademy provides access to courses, lessons, worksheets, short learning modules, and full
                instructional units created by educators and subject specialists. The platform allows teachers and
                tutors to share their knowledge while earning additional income, which means many offerings are grounded
                in real classroom experience, practical teaching methods, and student-centered learning.
            </p>

            <p>
                Course listings may include video instruction, guided lessons, downloadable practice materials,
                assessments, project-based activities, and bundled teaching resources. Users can browse by subject,
                grade level, age range, skill area, course type, or educator background.
            </p>

            <p>
                Each listing should clearly identify the educator’s role and status so learners and families understand
                who created the material and what qualifications or experience support the course.
            </p>
        </div>
    <div style="margin-top: 2rem;">
        <a href="{{ route('educator.courses.index') }}" class="btn btn-primary">
            Explore Courses
        </a>
    </div>
    </div>

</x-guest-layout>
