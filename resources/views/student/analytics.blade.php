@extends('layouts.student')

@section('content')
        <h2 class="h4 mb-4">Analytics</h2>

        <!-- KPI cards -->
        <div class="row g-3 mb-4">
          <div class="col-6 col-lg-3">
            <div class="card kpi-card p-3">
              <div class="d-flex align-items-center justify-content-between">
                <span class="kpi-icon"><i class="bi bi-journal-richtext"></i></span>
                <span class="small text-muted">Total</span>
              </div>
              <div class="mt-2">
                <h3 id="kpiTotal">0</h3>
                <small class="text-muted">Courses Taken</small>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="card kpi-card p-3">
              <div class="d-flex align-items-center justify-content-between">
                <span class="kpi-icon"><i class="bi bi-check-circle"></i></span>
                <span class="small text-muted">Done</span>
              </div>
              <div class="mt-2">
                <h3 id="kpiCompleted">0</h3>
                <small class="text-muted">Completed</small>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="card kpi-card p-3">
              <div class="d-flex align-items-center justify-content-between">
                <span class="kpi-icon"><i class="bi bi-hourglass-split"></i></span>
                <span class="small text-muted">Active</span>
              </div>
              <div class="mt-2">
                <h3 id="kpiOngoing">0</h3>
                <small class="text-muted">Ongoing Courses</small>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="card kpi-card p-3">
              <div class="d-flex align-items-center justify-content-between">
                <span class="kpi-icon"><i class="bi bi-clock-history"></i></span>
                <span class="small text-muted">Total</span>
              </div>
              <div class="mt-2">
                <h3 id="kpiWatch">0 h</h3>
                <small class="text-muted">Watch Time</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts -->
        <div class="row g-3 mb-4">
          <div class="col-lg-8">
            <div class="card p-3">
              <h6 class="mb-2">Course Completion by Course</h6>
              <canvas id="completionChart" height="130"></canvas>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card p-3">
              <h6 class="mb-2">Watch Time (14 days)</h6>
              <canvas id="watchChart" height="160"></canvas>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="card p-3">
          <h6 class="mb-2">Course Status</h6>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Course</th>
                  <th>Subject</th>
                  <th>Status</th>
                  <th>Progress</th>
                </tr>
              </thead>
              <tbody id="courseTableBody"></tbody>
            </table>
          </div>
        </div>
@endsection

@push('scripts')
<script>
    // Sample student data
    const courses = @json($courses);
    const totalCourses = @json($totalCourses);
    const completedCoursesCount = @json($completedCoursesCount);
    const ongoingCoursesCount = @json($ongoingCoursesCount);
    const totalWatchTime = @json(number_format($totalWatchTime, 1));
    const watchTimeLabels = @json($watchTimeLabels);
    const watchTimeData = @json($watchTimeData);

     // KPI values
     document.getElementById("kpiTotal").textContent = totalCourses;
     document.getElementById("kpiCompleted").textContent = completedCoursesCount;
     document.getElementById("kpiOngoing").textContent = ongoingCoursesCount;
     document.getElementById("kpiWatch").textContent = totalWatchTime + " h";

     // Table
     const tbody = document.getElementById("courseTableBody");
     tbody.innerHTML = courses.map(c=>`
       <tr>
         <td>${c.title}</td>
         <td>${c.subject}</td>
         <td>${c.progress>=1?'<span class="badge bg-success">Completed</span>':(c.progress>0?'<span class="badge bg-warning text-dark">Ongoing</span>':'<span class="badge bg-secondary">Not Started</span>')}</td>
         <td>${Math.round(c.progress*100)}%</td>
       </tr>
     `).join("");

     // Charts
     const compCtx = document.getElementById("completionChart");
     new Chart(compCtx,{
       type:"bar",
       data:{
         labels:courses.map(c=>c.title),
         datasets:[{ label:"Completion %", data:courses.map(c=>Math.round(c.progress*100)), backgroundColor:"rgba(111,66,193,.7)" }]
       },
       options:{responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true, max:100}}}
     });

     const watchCtx = document.getElementById("watchChart");
     const labels = watchTimeLabels;
     const mins = watchTimeData;
     new Chart(watchCtx,{
       type:"line",
       data:{ labels, datasets:[{ label:"Minutes", data:mins, borderColor:"#6f42c1", fill:true, backgroundColor:"rgba(111,66,193,.1)", tension:.3 }] },
       options:{responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}}}
     });
   </script>
@endpush
