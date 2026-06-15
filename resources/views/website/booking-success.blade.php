<x-guest-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                    <div class="text-center text-white p-5"
                         style="background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);">
                        <i class="fas fa-circle-check" style="font-size: 64px;"></i>
                        <h2 class="mt-3 mb-1 fw-bold">Session Booked!</h2>
                        <p class="mb-0">Your payment was successful and your session is confirmed.</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold mb-3">Session details</h5>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Educator</span>
                                <span class="fw-semibold">{{ optional($booking->educator)->full_name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Subject</span>
                                <span class="fw-semibold">{{ ucfirst($booking->subject) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Date &amp; Time</span>
                                <span class="fw-semibold">{{ $booking->scheduled_at->format('l, M j, Y \a\t g:i A') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Duration</span>
                                <span class="fw-semibold">{{ $booking->duration_minutes }} minutes</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Amount paid</span>
                                <span class="fw-semibold">${{ number_format((float) $booking->amount, 2) }} {{ $booking->currency }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Platform</span>
                                <span class="fw-semibold">{{ $booking->platform }}</span>
                            </li>
                        </ul>

                        @if($booking->meeting_link)
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-video me-2"></i>
                                <div>
                                    Your meeting link:
                                    <a href="{{ $booking->meeting_link }}" target="_blank" rel="noopener">{{ $booking->meeting_link }}</a>
                                    @if($booking->meeting_password)
                                        <div class="small text-muted">Passcode: {{ $booking->meeting_password }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <p class="text-muted small">
                            A confirmation email has been sent to you and the educator. You'll both get a reminder
                            30 minutes before the session starts.
                        </p>

                        <div class="d-flex gap-2 mt-4">
                            @auth
                                <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-gauge me-2"></i>Go to Dashboard
                                </a>
                            @endauth
                            <a href="{{ route('web.educator.show', $booking->educator_id) }}" class="btn btn-outline-secondary">
                                Back to Educator
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Session Booked!',
                        text: 'Your payment was successful and your session is confirmed.',
                        confirmButtonColor: '#6f42c1',
                    });
                }
            });
        </script>
    @endpush
</x-guest-layout>
