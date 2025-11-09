<x-educator-layout>
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm p-4">
                    <h4 class="mb-3 text-center"><i class="bi bi-calendar-plus me-2"></i>Create New Session</h4>
                    <form id="sessionForm" method="POST" action="{{ route('educator.sessions.store') }}">
                        @csrf


                        <div class="mb-3">
                            <label for=""> Title </label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <!-- Student Selection -->
                        <div class="mb-3">
                            <label class="form-label">Select Student <span>*</span></label>
                            <select class="form-select" name="student_id">
                                <option value="">Select a Student</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Start & End Time -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Time <span>*</span></label>
                                <input type="datetime-local" min="{{ now()->format('Y-m-d H:i') }}" name="start_time"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Time <span>*</span></label>
                                <input type="datetime-local" min="{{ now()->format('Y-m-d H:i') }}" name="end_time"
                                    class="form-control" required>
                            </div>
                        </div>

                        <!-- Meeting Link -->
                        <div class="mb-3">
                            <label class="form-label">Meeting Link <span>*</span></label>
                            <input type="url" name="meeting_link" class="form-control"
                                placeholder="https://zoom.us/..." required>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label">Session Status</label>
                            <select name="status" class="form-select">
                                <option value="booked" selected>Booked</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label class="form-label">Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Add any remarks or details..."></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle me-1"></i> Create Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById("sessionForm");

                form.addEventListener("submit", function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                            },
                            body: formData,
                        })
                        .then(async (response) => {
                            const data = await response.json();

                            if (response.ok && data.success) {
                                //  Success Alert
                                Swal.fire({
                                    title: "Session Created!",
                                    text: data.message ||
                                        "Your session has been created successfully.",
                                    icon: "success",
                                    confirmButtonColor: "#006b7d",
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Optional: reset form
                                form.reset();

                            } else {
                                //  Validation / logic error
                                console.error("Validation Error:", data);
                                Swal.fire({
                                    title: "Error",
                                    text: data.message ||
                                        "Something went wrong while creating the session.",
                                    icon: "error",
                                    confirmButtonColor: "#d33"
                                });
                            }
                        })
                        .catch((error) => {
                            //  Network / unexpected error
                            console.error("AJAX Error:", error);
                            Swal.fire({
                                title: "Server Error",
                                text: "Unable to process your request. Check console for details.",
                                icon: "error",
                                confirmButtonColor: "#d33"
                            });
                        });
                });
            });
        </script>
    @endpush
</x-educator-layout>
