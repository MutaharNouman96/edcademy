<x-guest-layout>
    <div class="container my-5">
        <h1>Educator Resources</h1>
        <div class="edcademy-info-section mt-4">
            <p>
                Ed-Cademy provides tools to improve course quality:
            </p>
            <ul>
                <li>Course planning templates</li>
                <li>Lesson design guides</li>
                <li>Video recording tips</li>
                <li>Assessment examples</li>
                <li>Content organization ideas</li>
                <li>Guidance on descriptions and outcomes</li>
            </ul>
            <p>
                These are especially helpful for educators new to digital products.
            </p>
        </div>
        <div style="margin-top: 2rem;">
            @auth
                @if (auth()->user()->role === 'educator')
                    <a href="{{ route('educator.resources.index') }}" class="btn btn-primary">
                        Open Educator Resources
                    </a>
                @else
                    <a href="{{ route('web.eudcator.signup') }}" class="btn btn-primary">
                        Become an Educator
                    </a>
                @endif
            @else
                <a href="{{ route('web.eudcator.signup') }}" class="btn btn-primary">
                    Become an Educator
                </a>
            @endauth
        </div>
    </div>
</x-guest-layout>
