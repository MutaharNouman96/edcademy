<x-guest-layout>
    <div class="container my-5">
        <h1>Create Courses</h1>
        <div class="edcademy-info-section mt-4">
            <p>
                Educators can create and sell courses using:
            </p>
            <ul>
                <li>Videos</li>
                <li>Worksheets</li>
                <li>Lesson packets</li>
                <li>Interactive assignments</li>
                <li>Short modules</li>
                <li>Full learning units</li>
            </ul>
            <p><strong>Educator controls:</strong></p>
            <ul>
                <li>Set pricing</li>
                <li>Choose format and structure</li>
                <li>Upload original content</li>
                <li>Bundle lessons and materials</li>
            </ul>
            <p>
                All content must be original or legally owned. Violations may result in removal.
            </p>
        </div>
        @auth
            @if (auth()->user()->role === 'educator')
                <div style="margin-top: 2rem;">
                    <a href="{{ route('educator.courses.index') }}" class="btn btn-primary">
                        Manage My Courses
                    </a>
                </div>
            @else
                <div style="margin-top: 2rem;">
                    <a href="{{ route('web.eudcator.signup') }}" class="btn btn-primary">
                        Get Started as an Educator
                    </a>
                </div>
            @endif
        @else
            <div style="margin-top: 2rem;">
                <a href="{{ route('web.eudcator.signup') }}" class="btn btn-primary">
                    Get Started as an Educator
                </a>
            </div>
        @endauth
    </div>
</x-guest-layout>
