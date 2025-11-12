@extends('layouts.student')

@section('content')
<main class="col-12 col-md-12 col-lg-12 p-4">
    <!-- Overview / KPIs -->
    <section id="section-overview" class="mb-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h2 class="h4 mb-0">Overview</h2>
        <div class="input-group" style="max-width: 320px;">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input id="globalSearch" class="form-control" placeholder="Search your courses, lessons..." />
        </div>
      </div>

      <div class="row g-3">
        <div class="col-6 col-lg-3">
          <div class="card kpi-card p-3">
            <div class="d-flex align-items-center justify-content-between">
              <span class="kpi-icon"><i class="bi bi-journal-richtext"></i></span>
              <span class="pill" style="background:var(--primary-50); color:var(--primary-dark)">Enrolled</span>
            </div>
            <div class="mt-3">
              <div class="h3 mb-0" id="kpiEnrolled">6</div>
              <small class="text-muted">Active courses</small>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="card kpi-card p-3">
            <div class="d-flex align-items-center justify-content-between">
              <span class="kpi-icon"><i class="bi bi-clock-history"></i></span>
              <span class="pill" style="background:var(--primary-50); color:var(--primary-dark)">30 days</span>
            </div>
            <div class="mt-3">
              <div class="h3 mb-0" id="kpiHours">18.6 h</div>
              <small class="text-muted">Watched time</small>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="card kpi-card p-3">
            <div class="d-flex align-items-center justify-content-between">
              <span class="kpi-icon"><i class="bi bi-check2-circle"></i></span>
              <span class="pill" style="background:var(--primary-50); color:var(--primary-dark)">Avg</span>
            </div>
            <div class="mt-3">
              <div class="h3 mb-0" id="kpiCompletion">54%</div>
              <small class="text-muted">Completion rate</small>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="card kpi-card p-3">
            <div class="d-flex align-items-center justify-content-between">
              <span class="kpi-icon"><i class="bi bi-wallet2"></i></span>
              <span class="pill" style="background:var(--primary-50); color:var(--primary-dark)">Total</span>
            </div>
            <div class="mt-3">
              <div class="h3 mb-0" id="kpiSpend">$186.00</div>
              <small class="text-muted">Spent to date</small>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Charts Row -->
    <section id="section-progress" class="mb-4">
      <div class="row g-3">
        <div class="col-lg-8">
          <div class="card p-3">
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Course completion by course</h5>
              <div class="d-flex gap-2">
                <select id="progressSort" class="form-select form-select-sm" style="width:auto">
                  <option value="desc">Highest first</option>
                  <option value="asc">Lowest first</option>
                </select>
                <button class="btn btn-sm btn-outline-primary" id="refreshProgress"><i class="bi bi-arrow-clockwise"></i></button>
              </div>
            </div>
            <canvas id="completionBar" height="130" class="mt-3"></canvas>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card p-3 h-100">
            <h5 class="mb-0">Watch time (last 14 days)</h5>
            <canvas id="watchLine" height="160" class="mt-3"></canvas>
            <div class="mt-2 small text-muted">Minutes watched per day</div>
          </div>
        </div>
      </div>
    </section>

    <!-- New Videos Feed -->
    <section id="section-new-videos" class="mb-4">
      <div class="card p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h3 class="h6 mb-0">New videos from your courses</h3>
          <a class="small" href="#">Manage notifications</a>
        </div>
        <ul class="list-group list-group-flush" id="newVideosList"></ul>
      </div>
    </section>

    <!-- My Courses -->
    <section id="section-my-courses" class="mb-4">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h3 class="h6 mb-0">My Courses</h3>
        <div class="input-group input-group-sm" style="max-width: 260px;">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input id="courseSearch" class="form-control" placeholder="Search title or subject" />
        </div>
      </div>
      <div class="row g-3" id="coursesGrid"></div>
    </section>

    <!-- Certificates / Payments Row -->
    <div class="row g-3">
      <section id="section-certificates" class="col-lg-5">
        <div class="card p-3 h-100">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h3 class="h6 mb-0">Certificates</h3>
            <a class="small" href="#">View all</a>
          </div>
          <ul class="list-group list-group-flush" id="certList">
            <!-- injected by JS -->
          </ul>
        </div>
      </section>
      <section id="section-payments" class="col-lg-7">
        <div class="card p-3 h-100">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h3 class="h6 mb-0">Payments</h3>
            <a class="small" href="#">Download invoices</a>
          </div>
          <div class="table-responsive">
            <table class="table align-middle" id="paymentsTable">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Course</th>
                  <th>Method</th>
                  <th class="text-end">Amount</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </section>
    </div>

    <!-- Messages placeholder -->
    <section id="section-messages" class="mt-4">
      <div class="card p-3">
        <h3 class="h6 mb-1">Messages</h3>
        <p class="small text-muted mb-0">Open your inbox to chat with educators about assignments, feedback, and scheduling.</p>
      </div>
    </section>

    <!-- Settings placeholder -->
    <section id="section-settings" class="mt-3">
      <div class="card p-3">
        <h3 class="h6 mb-1">Settings</h3>
        <p class="small text-muted mb-0">Profile, notifications, language, time‑zone, and privacy controls.</p>
      </div>
    </section>
  </main>
@endsection
