<x-educator-layout>
    <div id="calendar"></div>
    @push('styles')
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
    @endpush
    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    // 1. Theme and Plugins
                    initialView: 'dayGridMonth',
                    themeSystem: 'bootstrap5', // Use Bootstrap 5 theming

                    // 2. Toolbar Customization
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },

                    // 3. Random Events Data
                    events: [{
                            title: 'Team Meeting',
                            start: new Date(new Date().getFullYear(), new Date().getMonth(), 1, 10,
                            0), // 1st of the current month
                            end: new Date(new Date().getFullYear(), new Date().getMonth(), 1, 11, 30),
                            color: '#4CAF50' // Custom green color
                        },
                        {
                            title: 'Project Deadline',
                            start: new Date(new Date().getFullYear(), new Date().getMonth(),
                            7), // 7th of the current month
                            allDay: true,
                            color: '#FBC02D' // Custom yellow/amber color
                        },
                        {
                            title: 'All-Day Workshop',
                            start: new Date(new Date().getFullYear(), new Date().getMonth(), 18),
                            end: new Date(new Date().getFullYear(), new Date().getMonth(),
                            21), // Multi-day event
                            allDay: true,
                            color: '#D32F2F' // Custom red color
                        }
                    ]
                });

                calendar.render();
            });
        </script>
    @endpush
</x-educator-layout>
