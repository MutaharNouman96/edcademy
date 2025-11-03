<x-educator-layout>

    <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="bi bi-bar-chart-line me-2"></i>Video Statistics</h5>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width:auto;">
                    <option>All Courses</option>
                    <option>Math — Calculus I</option>
                    <option>Physics — Motion & Force</option>
                </select>
                <select class="form-select form-select-sm" style="width:auto;">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option>All Time</option>
                </select>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="fw-semibold text-secondary small">Total Views</div>
                    <div class="fs-4 fw-bold text-primary">12,430</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="fw-semibold text-secondary small">Average Watch Time</div>
                    <div class="fs-4 fw-bold text-primary">7m 23s</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="fw-semibold text-secondary small">Completion Rate</div>
                    <div class="fs-4 fw-bold text-success">82%</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="fw-semibold text-secondary small">Top Performing Video</div>
                    <div class="fs-6 fw-bold text-dark">Lesson 3: Derivatives</div>
                </div>
            </div>
        </div>

        <hr class="my-4" />

        <!-- Chart Placeholder -->
        <div class="mb-4">
            <h6 class="fw-semibold mb-2">Views Over Time</h6>
            <div class="border rounded p-3 bg-light text-center text-muted" style="height:300px;">
                <i class="bi bi-graph-up fs-3"></i><br>
                Chart Placeholder (e.g., Recharts or Chart.js)
            </div>
        </div>

        <!-- Table: Per-Video Breakdown -->
        <h6 class="fw-semibold mb-2">Per-Video Breakdown</h6>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Lesson Title</th>
                        <th>Views</th>
                        <th>Avg Watch Time</th>
                        <th>Completion</th>
                        <th>Likes</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lesson 1 — Introduction</td>
                        <td>2,130</td>
                        <td>4m 15s</td>
                        <td><span class="text-success">78%</span></td>
                        <td>45</td>
                        <td>12</td>
                    </tr>
                    <tr>
                        <td>Lesson 2 — Limits & Notation</td>
                        <td>3,780</td>
                        <td>8m 10s</td>
                        <td><span class="text-success">86%</span></td>
                        <td>63</td>
                        <td>19</td>
                    </tr>
                    <tr>
                        <td>Lesson 3 — Derivatives</td>
                        <td>6,520</td>
                        <td>9m 02s</td>
                        <td><span class="text-success">91%</span></td>
                        <td>82</td>
                        <td>26</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</x-educator-layout>
