<x-guest-layout>
    <div class="container my-5">
        <h1>Help Center</h1>
        <div class="edcademy-info-section mt-4">
            <p>
                The Help Center provides guidance on:
            </p>
            <ul>
                <li>Creating an account</li>
                <li>Buying courses</li>
                <li>Accessing materials</li>
                <li>Uploading content</li>
                <li>Setting up profiles</li>
                <li>Certificates</li>
                <li>Payments</li>
                <li>Verification</li>
            </ul>
            <p>
                Instructions are simple and user-friendly so learners and educators can get unstuck quickly.
            </p>
        </div>
        <div style="margin-top: 2rem;">
            <a href="{{ route('web.faqs') }}" class="btn btn-primary">
                View FAQs
            </a>
            <a href="{{ route('content.support.contact') }}" class="btn btn-outline-primary ms-2">
                Contact Support
            </a>
        </div>
    </div>
</x-guest-layout>
