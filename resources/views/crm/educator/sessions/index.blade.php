<x-educator-layout>
    <div class="card shadow-sm mb-3">
        <div class="section-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h2 class="section-title"><i class="bi bi-calendar3-event"></i> Sessions</h2>
                <div>
                    <a href="{{ route('educator.sessions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus me-1"></i> New Session
                    </a>
                </div>

            </div>

            <div class="toolbar d-flex flex-wrap gap-2 mt-3">
                <select id="filterType" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="1on1">1:1</option>
                    <option value="group">Group</option>
                    <option value="cohort">Cohort</option>
                </select>
                <select id="filterStatus" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                    <option value="no-show">No‑show</option>
                </select>
                <input id="searchText" class="form-control form-control-sm" placeholder="Search name/course…" />
                <button id="resetFilters" class="btn btn-sm btn-outline-secondary"><i
                        class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
        <div class="p-3 pt-0">
            {{-- <div class="d-flex justify-content-between w-100">
                <ul class="nav nav-pills gap-2 mt-3" id="sessionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="today-tab" data-bs-toggle="pill" data-bs-target="#today"
                            type="button" role="tab" aria-controls="today" aria-selected="true"><i
                                class="bi bi-sun me-1"></i> Today</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="upcoming-tab" data-bs-toggle="pill" data-bs-target="#upcoming"
                            type="button" role="tab" aria-controls="upcoming" aria-selected="false"><i
                                class="bi bi-calendar2-week me-1"></i> Upcoming</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="past-tab" data-bs-toggle="pill" data-bs-target="#past"
                            type="button" role="tab" aria-controls="past" aria-selected="false"><i
                                class="bi bi-clock-history me-1"></i> Past</button>
                    </li>
                </ul>
                <div>
                    <a href="{{ route('educator.sessions.create') }}" class="btn btn-primary mt-3  ">
                        <i class="bi bi-plus me-1"></i> New Session
                    </a>
                </div>
            </div> --}}

            {{-- <div class="tab-content mt-5">
                <!-- Today -->
                <div class="tab-pane fade show active" id="today" role="tabpanel">
                    <div class="table-responsive">
                       
                    </div>
                </div>
                <!-- Upcoming -->
                <div class="tab-pane fade" id="upcoming" role="tabpanel">
                    <div class="table-responsive">
                        <table id="upcomingTable" class="table table-striped align-middle w-100">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Student</th>
                                    <th>Course/Lesson</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- Past -->
                <div class="tab-pane fade" id="past" role="tabpanel">
                    <div class="table-responsive">
                        <table id="pastTable" class="table table-striped align-middle w-100">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Course/Lesson</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div> --}}

            <div class="card">
                <div class="card-body">
                    <table id="todayTable" class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Student</th>
                                <th scope="col">Type</th>
                                <th scope="col">Price</th>
                                <th scope="col">Start Time</th>
                                <th scope="col">End Time</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sessions as $index => $session)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $session->title ?? 'Untitled Session' }}</td>
                                    <td>
                                        @if ($session->students && count($session->students))
                                            {{ $session->students->first()->name }}
                                        @else
                                            <span class="text-muted">No student</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $session->is_paid ? 'Paid' : 'Free' }}
                                    </td>
                                    <td>
                                        @if ($session->is_paid)
                                            ${{ number_format($session->price, 2) }}
                                        @else
                                            <span class="badge bg-success-subtle text-success">Free</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($session->start_time)->format('M d, Y h:i A') }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($session->end_time)->format('M d, Y h:i A') }}</td>
                                    <td>
                                        @if ($session->status === 'booked')
                                            <span class="badge bg-info-subtle text-info"><i
                                                    class="bi bi-calendar-check me-1"></i> Booked</span>
                                        @elseif($session->status === 'completed')
                                            <span class="badge bg-success-subtle text-success"><i
                                                    class="bi bi-check2-circle me-1"></i> Completed</span>
                                        @elseif($session->status === 'cancelled')
                                            <span class="badge bg-danger-subtle text-danger"><i
                                                    class="bi bi-x-circle me-1"></i> Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ $session->meeting_link }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary me-1" title="Join Meeting">
                                            <i class="bi bi-camera-video"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-secondary me-1" title="Edit"
                                            onclick="editSession({{ $session->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Delete"
                                            onclick="deleteSession({{ $session->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                                        No sessions scheduled yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-calendar2-plus me-2"></i>Reschedule Session</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New date & time</label>
                        <input id="rescheduleDateTime" type="datetime-local" class="form-control" />
                        <div class="form-text">Shown in your timezone (<span id="tzLabel2">—</span>).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason (optional)</label>
                        <textarea id="rescheduleReason" class="form-control" rows="3" placeholder="Add a short note for the student…"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="confirmRescheduleBtn" class="btn btn-primary"><i
                            class="bi bi-check2 me-1"></i>Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-x-octagon me-2"></i>Cancel Session</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Cancellations may follow your policy (automatic refund/credit rules
                        apply).</p>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea id="cancelReason" class="form-control" rows="3"
                            placeholder="Share a brief reason (sent to student)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Keep session</button>
                    <button id="confirmCancelBtn" class="btn btn-danger"><i class="bi bi-trash3 me-1"></i> Cancel
                        session</button>
                </div>
            </div>
        </div>
    </div>
</x-educator-layout>
