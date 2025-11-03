<x-educator-layout>
    <div class="card shadow-sm">
        <div class="section-header">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="section-title"><i class="bi bi-collection"></i> Courses</h2>

                <div class="toolbar d-flex flex-wrap gap-2">
                    <input name.debounce.500ms="search" type="text" class="form-control form-control-sm"
                        placeholder="Search title or subject...">

                    <select name="filterStatus" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="scheduled">Scheduled</option>
                    </select>

                    <select name="filterType" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="module">Online Module</option>
                        <option value="video">Video Pack</option>
                        <option value="live">Live Cohort</option>
                    </select>

                    <input name.debounce.500ms="filterSubject" class="form-control form-control-sm"
                        placeholder="Filter by subject…" />

                    <button wire:click="resetFilters" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <div class="p-3">
            @if (session('message'))
                <div class="alert alert-success py-2 mb-3">
                    <i class="bi bi-check-circle me-1"></i> {{ session('message') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped align-middle w-100">
                    <thead>
                        <tr>
                            <th>Thumb</th>
                            <th>Title</th>
                            <th>Subject</th>
                            <th>Level</th>
                            <th>Price</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Lessons</th>
                            <th>Students</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr>
                                <td>
                                    @if ($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="thumb"
                                            class="rounded" style="width:50px;height:50px;object-fit:cover;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $course->title }}</td>
                                <td>{{ ucfirst($course->subject) }}</td>
                                <td>{{ ucfirst($course->level) }}</td>
                                <td>${{ number_format($course->price, 2) }}</td>
                                <td>{{ ucfirst($course->type) }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $course->status == 'published' ? 'success' : ($course->status == 'draft' ? 'secondary' : 'info') }}">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </td>
                                <td>{{ $course->lessons()->count() }}</td>
                                <td>—</td> <!-- TODO: link enrolled count -->
                                <td>{{ $course->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('educator.courses.edit', $course->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button wire:click="confirmDelete({{ $course->id }})"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-3">
                                    No courses found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $courses->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-trash3 me-2"></i>Delete Course</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        Are you sure you want to delete <strong>{{ $deleteTitle ?? 'this course' }}</strong>?
                        This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button wire:click="deleteCourse" class="btn btn-danger">
                        <i class="bi bi-trash3 me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('show-delete-modal', () => {
                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });

            window.addEventListener('hide-delete-modal', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                modal.hide();
            });
        </script>
    @endpush

</x-educator-layout>
