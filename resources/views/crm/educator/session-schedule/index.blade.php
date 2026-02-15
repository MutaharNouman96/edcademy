<x-educator-layout>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                <h2 class="h4 mb-0"><i class="bi bi-calendar2-range me-2"></i>Session availability</h2>
            </div>
            <p class="text-muted small mb-4">Set the days and time ranges when you are available for 1-on-1 session bookings. Students will only see slots within these windows, up to your max sessions per day.</p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-5 mb-4">
                    <div class="border rounded p-3 bg-light">
                        <h6 class="mb-3"><i class="bi bi-plus-circle me-1"></i>Max sessions per day</h6>
                        <form action="{{ route('educator.session-schedule.max-sessions') }}" method="POST" class="d-flex align-items-end gap-2">
                            @csrf
                            <div class="flex-grow-1">
                                <label class="form-label small mb-1">Maximum number of bookable sessions per day</label>
                                <input type="number" name="max_sessions_per_day" class="form-control" min="1" max="20" value="{{ $maxSessionsPerDay }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <h6 class="mb-3"><i class="bi bi-clock me-1"></i>Add availability slot</h6>
            <form id="add-slot-form" class="row g-3 mb-4">
                @csrf
                <div class="col-md-2">
                    <label class="form-label">Day</label>
                    <select name="day_of_week" class="form-select" required>
                        @foreach(\App\Models\EducatorSessionSchedule::DAYS as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="time" name="start_time" class="form-control" value="09:00" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="time" name="end_time" class="form-control" value="17:00" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-plus me-1"></i>Add slot</button>
                </div>
            </form>

            <h6 class="mb-2">Your weekly availability</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Day</th>
                            <th>Time range</th>
                            <th width="100" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\EducatorSessionSchedule::DAYS as $dayNum => $dayName)
                            @php
                                $daySlots = $schedules->get($dayNum, collect());
                            @endphp
                            @if($daySlots->isEmpty())
                                <tr>
                                    <td>{{ $dayName }}</td>
                                    <td colspan="2" class="text-muted">No slots</td>
                                </tr>
                            @else
                                @foreach($daySlots as $slot)
                                    <tr>
                                        <td>{{ $dayName }}</td>
                                        <td>{{ substr($slot->start_time, 0, 5) }} – {{ substr($slot->end_time, 0, 5) }}</td>
                                        <td class="text-end">
                                            <form action="{{ route('educator.session-schedule.destroy', $slot->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this slot?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-center py-4">No availability set. Add slots above.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('add-slot-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = this;
            const btn = form.querySelector('button[type="submit"]');
            const origText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Adding...';
            const fd = new FormData(form);
            try {
                const r = await fetch('{{ route("educator.session-schedule.store") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: fd
                });
                const data = await r.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Failed to add slot.'));
                }
            } catch (err) {
                alert('Request failed. Please try again.');
            }
            btn.disabled = false;
            btn.innerHTML = origText;
        });
    </script>
    @endpush
</x-educator-layout>
