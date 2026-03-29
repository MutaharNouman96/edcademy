<x-guest-layout>
    <div class="container my-5">
        <h1>Certificates</h1>
        <div class="edcademy-info-section mt-4">
            <p>
                Certificates are awarded after completion of certain courses on Ed-Cademy. These certificates confirm that a learner has finished a course or learning experience offered through the platform.
            </p>
            <p>Certificates should include:</p>
            <ul>
                <li>Course title</li>
                <li>Completion date</li>
                <li>Educator name</li>
                <li>Educator status (when appropriate)</li>
            </ul>
            <p>
                To protect transparency and avoid misleading claims, educator classification must be handled carefully:
            </p>
            <ul>
                <li>Only educators with a valid teaching credential may be identified as <strong>Certified Teachers</strong>.</li>
                <li>Substitute teachers are not considered certified unless they hold a verified full teaching license.</li>
                <li>Tutors may create courses, but certificates should label them as <strong>Tutor</strong>, not Certified Teacher.</li>
            </ul>
            <p>Certificates represent <strong>course completion only</strong>. They are NOT:</p>
            <ul>
                <li>Government diplomas</li>
                <li>School transcripts</li>
                <li>University credits</li>
                <li>State-issued credentials</li>
            </ul>
            <div class="alert alert-info mt-3" role="alert">
                Ed-Cademy verifies credentials before granting Certified Teacher status to maintain trust.
            </div>
        </div>
        @auth
            @if (auth()->user()->role === 'student')
                <div style="margin-top: 2rem;">
                    <a href="{{ route('student.certificates') }}" class="btn btn-primary">
                        View My Certificates
                    </a>
                </div>
            @endif
        @endauth
    </div>
</x-guest-layout>
