<x-guest-layout>
    <div class="container my-5">
        <h1>Find an Educator</h1>
        <div class="edcademy-info-section mt-4">
            <p>
                The <strong>Find an Educator</strong> section helps learners and families search for teachers and tutors based on qualifications, experience, subject area, grade level, and service type. This section is intended to make it easier for users to connect with the right educational support based on their needs.
            </p>
            <p>
                Educators on Ed-Cademy are grouped into clear categories so users can make informed decisions:
            </p>
            <ul>
                <li>
                    <strong>Certified Teacher:</strong> A certified teacher holds a valid teaching credential, license, or official certification issued by a recognized government, state, or accredited authority.
                </li>
                <li>
                    <strong>Tutor:</strong> A tutor may include a substitute teacher, teaching assistant, university student, retired educator, paraprofessional, or subject specialist who does not hold a full teaching credential.
                </li>
            </ul>
            <div class="alert alert-info mt-3" role="alert">
                <strong>Important classification rule:</strong> Substitute teachers are <u>not</u> considered certified teachers unless they also hold a valid full teaching license. On the platform, substitutes should be listed as tutors unless separate certification has been verified.
            </div>
            <p class="mt-3">
                Each educator profile shows the educator’s status, areas of expertise, and available services. This helps maintain transparency and trust, especially when families are comparing tutoring services or course creators.
            </p>
        </div>
        <div style="margin-top: 2rem;">
            <a href="{{ route('web.educators.index') }}" class="btn btn-primary">
                Explore Educators
            </a>
        </div>
    </div>
</x-guest-layout>