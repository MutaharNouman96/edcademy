<x-educator-layout>
    <div id="calendar"></div>

    <!-- Schedule Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="scheduleForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scheduleModalLabel">Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="sch_id" />
                        <div class="mb-2">
                            <label class="form-label">Session (optional)</label>
                            <select id="sch_session" class="form-select">
                                <option value="">— Select a session —</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Title</label>
                            <input id="sch_title" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Start</label>
                            <input id="sch_start" type="datetime-local" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">End</label>
                            <input id="sch_end" type="datetime-local" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Description</label>
                            <textarea id="sch_description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-2 d-flex align-items-center gap-2">
                            <div>Color:</div>
                            <div id="sch_color_preview"
                                style="width:28px;height:18px;border-radius:4px;border:1px solid #ddd;"></div>
                            <small class="text-muted"> (auto from session status) </small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btnDeleteSchedule" class="btn btn-danger me-auto"
                            style="display:none">Delete</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnSaveSchedule" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css" rel="stylesheet">

        <style>
            :root {
                /* FullCalendar CSS Variables for a clean look */
                --fc-border-color: #e0e0e0;
                /* Lighter borders */
                --fc-button-hover-bg-color: #e9ecef;
                /* Light hover effect for buttons */
                --fc-event-bg-color: #3f51b5;
                /* Primary blue for events */
                --fc-event-border-color: #3f51b5;
                --fc-event-text-color: #ffffff;
                /* White text on blue events */
                --fc-daygrid-event-dot-width: 8px;
                /* Slightly larger event dots */
            }

            #calendar {
                max-width: 900px;
                /* Constrain width for better presentation */
                margin: 40px auto;
                /* Center the calendar */
                padding: 20px;
                background-color: #ffffff;
                border-radius: 8px;
                /* Soft rounded corners */
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                /* Subtle shadow */
            }

            .fc-toolbar-title {
                font-size: 1.5rem;
                font-weight: 600;
            }
        </style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>

        <script>
            const startPicker = flatpickr('#sch_start', {
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
                time_24hr: true,
                minDate: new Date(),
                onChange: function(selectedDates) {
                    endPicker.set('minDate', selectedDates[0]);
                }
            });

            const endPicker = flatpickr('#sch_end', {
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
                time_24hr: true
            });
            document.addEventListener('DOMContentLoaded', async function() {
                const calendarEl = document.getElementById('calendar');
                const modalEl = document.getElementById('scheduleModal');
                const scheduleModal = new bootstrap.Modal(modalEl);
                const form = document.getElementById('scheduleForm');

                // CSRF token for non-GET (if needed)
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                // load sessions for dropdown
                let sessions = [];
                async function loadSessions() {
                    try {
                        const res = await fetch('{{ url('/') }}/api/sessions', {
                            credentials: 'same-origin'
                        });
                        sessions = await res.json();
                        const sel = document.getElementById('sch_session');
                        sel.innerHTML = `<option value="">— Select a session —</option>`;
                        sessions.forEach(s => {
                            const opt = document.createElement('option');
                            opt.value = s.id;
                            opt.textContent = s.title + (s.status ? ` — ${s.status}` : '');
                            opt.dataset.start = s.start_time || '';
                            opt.dataset.end = s.end_time || '';
                            opt.dataset.status = s.status || '';
                            sel.appendChild(opt);
                        });
                    } catch (err) {
                        console.error('Failed to load sessions', err);
                    }
                }

                await loadSessions();

                function formatLocalForInput(iso) {
                    if (!iso) return '';
                    // Convert ISO to local 'YYYY-MM-DDTHH:MM' for datetime-local input
                    const d = new Date(iso);
                    const offset = d.getTimezoneOffset();
                    const local = new Date(d.getTime() - offset * 60000);
                    return local.toISOString().slice(0, 16);
                }

                function isoFromInput(val) {
                    if (!val) return null;
                    // treat as local time and convert to ISO (no timezone offset)
                    const d = new Date(val);
                    return new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString();
                }

                // helper to set color preview
                function setColorPreview(hex) {
                    document.getElementById('sch_color_preview').style.backgroundColor = hex || '#3f51b5';
                }

                // get color map for session statuses (match server)
                const statusColorMap = {
                    'draft': '#9E9E9E',
                    'scheduled': '#4CAF50',
                    'completed': '#607D8B',
                    'cancelled': '#D32F2F'
                };

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    themeSystem: 'bootstrap5',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },

                    events: {
                        url: '{{ url('/') }}/api/schedules',
                        method: 'GET',
                        extraParams: {},
                        failure: function() {
                            console.error('Failed to fetch schedules');
                        }
                    },

                    dateClick: function(info) {
                        // open create modal with start prefilled
                        document.getElementById('sch_id').value = '';
                        document.getElementById('sch_session').value = '';
                        document.getElementById('sch_title').value = '';
                        document.getElementById('sch_description').value = '';
                        document.getElementById('sch_start').value = formatLocalForInput(info.dateStr +
                            'T09:00:00');
                        document.getElementById('sch_end').value = formatLocalForInput(info.dateStr +
                            'T10:00:00');
                        document.getElementById('btnDeleteSchedule').style.display = 'none';
                        setColorPreview('#3f51b5');
                        scheduleModal.show();
                    },

                    eventClick: function(info) {

                        const ev = info.event;

                        const now = new Date();

                        // 3. If event is in the past, don't open modal
                        const eventStart = info.event.start;

                        if (eventStart < now) {

                            // Optional: Show a message
                            alert("You cannot modify past events.");

                            return; // stop execution and do not open modal
                        }

                        // fill modal with event data
                        document.getElementById('sch_id').value = ev.id;
                        document.getElementById('sch_title').value = ev.title;
                        document.getElementById('sch_description').value = ev.extendedProps
                            .description || '';
                        document.getElementById('sch_start').value = formatLocalForInput(ev.start
                            ?.toISOString());
                        document.getElementById('sch_end').value = formatLocalForInput(ev.end
                            ?.toISOString());
                        document.getElementById('sch_session').value = ev.extendedProps.session_id ||
                            '';
                        const color = ev.backgroundColor || ev.extendedProps.color || '#3f51b5';
                        setColorPreview(color);
                        document.getElementById('btnDeleteSchedule').style.display = 'inline-block';
                        scheduleModal.show();
                    },

                    editable: true,
                    selectable: true,
                    eventDrop: async function(info) {
                        // update start/end after drag
                        const id = info.event.id;
                        await patchSchedule(id, {
                            start: info.event.start?.toISOString(),
                            end: info.event.end?.toISOString()
                        });
                        info.revert = false;
                    },

                    eventResize: async function(info) {
                        // update end after resize
                        const id = info.event.id;
                        await patchSchedule(id, {
                            start: info.event.start?.toISOString(),
                            end: info.event.end?.toISOString()
                        });
                    },

                    eventDidMount: function(info) {
                        // tooltip or additional UI could be added
                    }
                });

                calendar.render();

                // When session dropdown changes, autofill title and times
                document.getElementById('sch_session').addEventListener('change', function(e) {
                    const selected = sessions.find(s => String(s.id) === String(this.value));
                    if (selected) {
                        // autofill title and datetime inputs if empty
                        if (!document.getElementById('sch_title').value) {
                            document.getElementById('sch_title').value = selected.title;
                        }
                        if (selected.start_time) {
                            document.getElementById('sch_start').value = formatLocalForInput(selected
                                .start_time);
                        }
                        if (selected.end_time) {
                            document.getElementById('sch_end').value = formatLocalForInput(selected
                                .end_time);
                        }
                        // color preview from session status
                        const col = statusColorMap[selected.status] || '#3f51b5';
                        setColorPreview(col);
                    } else {
                        // cleared
                        setColorPreview('#3f51b5');
                    }
                });

                // Save (create or update)
                form.addEventListener('submit', async function(ev) {
                    ev.preventDefault();
                    const id = document.getElementById('sch_id').value;
                    const payload = {
                        title: document.getElementById('sch_title').value,
                        description: document.getElementById('sch_description').value,
                        start: isoFromInput(document.getElementById('sch_start').value),
                        end: isoFromInput(document.getElementById('sch_end').value),
                        session_id: document.getElementById('sch_session').value || null
                    };

                    try {
                        if (id) {
                            // update
                            const updated = await patchSchedule(id, payload);
                            // update event in calendar
                            const ev = calendar.getEventById(String(id));
                            if (ev) {
                                ev.setProp('title', updated.title);
                                ev.setStart(updated.start);
                                ev.setEnd(updated.end);
                                ev.setExtendedProp('description', updated.description);
                                ev.setExtendedProp('session_id', updated.session_id);
                                ev.setProp('backgroundColor', updated.color);
                            }
                        } else {
                            // create
                            const created = await postSchedule(payload);
                            calendar.addEvent({
                                id: created.id,
                                title: created.title,
                                start: created.start,
                                end: created.end,
                                backgroundColor: created.color,
                                extendedProps: {
                                    description: created.description,
                                    session_id: created.session_id
                                }
                            });
                        }
                        scheduleModal.hide();
                    } catch (err) {
                        console.error(err);
                        alert('Failed to save schedule. See console for details.');
                    }
                });

                // Delete button
                document.getElementById('btnDeleteSchedule').addEventListener('click', async function() {
                    const id = document.getElementById('sch_id').value;
                    if (!id) return;
                    if (!confirm('Delete this schedule?')) return;
                    try {
                        const res = await fetch(`{{ url('/') }}/api/schedules/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            credentials: 'same-origin'
                        });
                        if (!res.ok) throw new Error('Delete failed');
                        // remove event
                        const ev = calendar.getEventById(String(id));
                        if (ev) ev.remove();
                        scheduleModal.hide();
                    } catch (err) {
                        console.error(err);
                        alert('Failed to delete.');
                    }
                });

                // helper: POST
                async function postSchedule(payload) {
                    const res = await fetch(`{{ url('/') }}/api/schedules`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(payload)
                    });
                    if (!res.ok) {
                        const json = await res.json().catch(() => ({}));
                        throw json;
                    }
                    return res.json();
                }

                // helper: PATCH/PUT
                async function patchSchedule(id, payload) {
                    const res = await fetch(`{{ url('/') }}/api/schedules/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(payload)
                    });
                    if (!res.ok) {
                        const json = await res.json().catch(() => ({}));
                        throw json;
                    }
                    return res.json();
                }
            });
        </script>
    @endpush

</x-educator-layout>
