<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel"><i class="bi bi-x-circle text-danger me-2"></i>Reject Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.courses.reject', $course->id) }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-2">Rejecting: <strong>{{ $course->title }}</strong></p>
                    <div class="mb-2">
                        <label for="review_note" class="form-label fw-semibold">Review Note <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="review_note" name="review_note" rows="4" maxlength="1000" required
                            placeholder="Please provide a reason for rejection...">{{ $course->review_note }}</textarea>
                        <div class="form-text">This note is shared with the educator. Max 1000 characters.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Course</button>
                </div>
            </form>
        </div>
    </div>
</div>
