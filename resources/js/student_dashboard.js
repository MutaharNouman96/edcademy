// ---------- Sample Data (replace with API sources) ----------
// const myCourses = [
//   { id: 1, title: 'Calculus I — Limits & Derivatives', subject: 'Math', progress: 0.72, hours: 6.2, last: '2d ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=Math', newVideos: 1 },
//   { id: 2, title: 'Physics: Mechanics (GCSE)', subject: 'Physics', progress: 0.36, hours: 3.1, last: '5h ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=Physics', newVideos: 0 },
//   { id: 3, title: 'IELTS Speaking Mastery', subject: 'English', progress: 0.58, hours: 4.4, last: '1d ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=IELTS', newVideos: 2 },
//   { id: 4, title: 'Organic Chemistry Basics', subject: 'Chemistry', progress: 0.21, hours: 1.0, last: '6d ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=Chem', newVideos: 0 },
//   { id: 5, title: 'Algebra II — Functions', subject: 'Math', progress: 0.93, hours: 7.0, last: '3h ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=Algebra', newVideos: 0 },
//   { id: 6, title: 'Essay Writing Bootcamp', subject: 'English', progress: 0.49, hours: 2.9, last: '4d ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=Writing', newVideos: 1 }
// ];

// const newVideos = [
//   { course: 'IELTS Speaking Mastery', lesson: 'Band 7+ Part 2', when: '2 hours ago', duration: '9m', id: 'nv1' },
//   { course: 'Calculus I — Limits & Derivatives', lesson: 'L\'Hôpital Rule Basics', when: 'Yesterday', duration: '12m', id: 'nv2' },
//   { course: 'Essay Writing Bootcamp', lesson: 'Upgrade your Thesis', when: '2 days ago', duration: '7m', id: 'nv3' }
// ];

(function () {
  // This bundle is included from the student layout; only run on the dashboard page.
  const coursesGridEl = document.getElementById('coursesGrid');
  if (!coursesGridEl) return;

  const safeMyCourses = Array.isArray(window.myCourses) ? window.myCourses : [];
  const safeNewVideos = Array.isArray(window.newVideos) ? window.newVideos : [];
  const safeCourseCompletionData = Array.isArray(window.courseCompletionData) ? window.courseCompletionData : [];
  const safeWatchTimeLabels = Array.isArray(window.watchTimeLabels) ? window.watchTimeLabels : [];
  const safeWatchTimeData = Array.isArray(window.watchTimeData) ? window.watchTimeData : [];

  const courseDetailsUrlTemplate =
    document.getElementById('dashboard-data')?.dataset?.courseDetailsUrl || '#';

  // ---------- Populate UI ----------
  function renderCourses(list) {
    const grid = document.getElementById('coursesGrid');
    if (!grid) return;

    if (!list || list.length === 0) {
      grid.innerHTML = `
        <div class="col-12">
          <div class="alert alert-light border mb-0">
            You don’t have any courses yet.
          </div>
        </div>`;
      return;
    }

    grid.innerHTML = list
      .map((c) => {
        const progressPct = Math.round((Number(c.progress) || 0) * 100);
        const hours = Number(c.hours) || 0;
        const title = c.title || 'Course';
        const subject = c.subject || 'General';
        const last = c.last || 'Never';
        const thumb = c.thumb || '';
        const courseUrl = courseDetailsUrlTemplate.replace('_COURSE_ID_', c.id);

        return `
          <div class="col-sm-6 col-xl-4">
            <div class="card course-card h-100 border-0">
              <img src="${thumb}" class="card-img-top rounded-top" alt="${title}">
              <div class="card-body d-flex flex-column">
                <h6 class="mb-1">${title}</h6>
                <div class="small text-muted mb-2">${subject} • Last viewed ${last}</div>
                <div class="progress mb-2">
                  <div class="progress-bar bg-primary" role="progressbar"
                    style="width:${progressPct}%"
                    aria-valuenow="${progressPct}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between small mb-3">
                  <span>${progressPct}% complete</span>
                  <span>${hours.toFixed(1)} h watched</span>
                </div>
                <div class="mt-auto d-flex gap-2">
                  <a class="btn btn-sm btn-primary w-100" href="${courseUrl}">
                    <i class="bi bi-play-fill me-1"></i> Resume
                  </a>
                  <button class="btn btn-sm btn-outline-primary" title="Course menu" type="button">
                    <i class="bi bi-three-dots"></i>
                  </button>
                </div>
              </div>
              ${
                Number(c.newVideos) > 0
                  ? `<span class="position-absolute top-0 end-0 m-2 badge text-bg-warning">${parseInt(
                      c.newVideos,
                      10
                    )} new</span>`
                  : ''
              }
            </div>
          </div>`;
      })
      .join('');
  }

  function renderNewVideos() {
    const ul = document.getElementById('newVideosList');
    if (!ul) return;

    if (!safeNewVideos || safeNewVideos.length === 0) {
      ul.innerHTML = `
        <li class="list-group-item text-muted small">
          No new videos yet.
        </li>`;
      return;
    }

    ul.innerHTML = safeNewVideos
      .map((n) => {
        const courseUrl = courseDetailsUrlTemplate.replace('_COURSE_ID_', n.course_id);
        return `
          <li class="list-group-item d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded border d-flex align-items-center justify-content-center" style="width:56px;height:36px;background:var(--primary-50);color:var(--primary)">
                <i class="bi bi-camera-video"></i>
              </div>
              <div>
                <div class="fw-semibold">${n.lesson} <span class="text-muted">• ${n.duration ?? ''}</span></div>
                <div class="small text-muted">${n.course} • ${n.when}</div>
              </div>
            </div>
            <div class="d-flex align-items-center gap-2">
              <a class="btn btn-sm btn-primary" href="${courseUrl}">
                <i class="bi bi-play-fill me-1"></i> Watch
              </a>
            </div>
          </li>`;
      })
      .join('');
  }

// function renderCerts(){
//   const ul = document.getElementById('certList');
//   ul.innerHTML = certificates.map(c => `
//     <li class="list-group-item d-flex align-items-center justify-content-between">
//       <div>
//         <div class="fw-semibold">${c.course}</div>
//         <div class="small text-muted">Issued ${c.date}</div>
//       </div>
//       <div class="btn-group btn-group-sm">
//         <button class="btn btn-outline-primary"><i class="bi bi-download"></i></button>
//         <button class="btn btn-outline-primary"><i class="bi bi-box-arrow-up-right"></i></button>
//       </div>
//     </li>`).join('');
// }


// function renderPayments(){
//   const tbody = document.getElementById('paymentsTable');
//   console.log('====================================');
//   console.log('Payments on dashboard', payments);
//   console.log('====================================');
//   tbody.innerHTML = payments.map(p => `
//     <tr>
//       <td>${p.date}</td>
//       <td>${p.course}</td>
//       <td>${p.method}</td>
//       <td class="text-end">$${parseFloat(p.amount).toFixed(2)}</td>
//     </tr>`).join('');
//   const total = payments.reduce((a,b)=>a+parseFloat(b.amount),0);
//   document.getElementById('kpiSpend').textContent = `$${parseFloat(total).toFixed(2)}`;
// }

  renderCourses(safeMyCourses);
  renderNewVideos();
// renderCerts();
// renderPayments();

  // Search courses
  const courseSearchEl = document.getElementById('courseSearch');
  courseSearchEl?.addEventListener('input', (e) => {
    const q = (e.target.value || '').toLowerCase();
    renderCourses(
      safeMyCourses.filter(
        (c) =>
          (c.title || '').toLowerCase().includes(q) ||
          (c.subject || '').toLowerCase().includes(q)
      )
    );
  });

// ---------- Charts ----------
// Completion per course bar chart
  const compCtx = document.getElementById('completionBar');
  let courseOrder = [...safeCourseCompletionData];
  function drawCompletion(sort = 'desc') {
    if (!compCtx || typeof Chart === 'undefined') return;

    courseOrder.sort((a, b) =>
      sort === 'asc'
        ? a.completion_percentage - b.completion_percentage
        : b.completion_percentage - a.completion_percentage
    );
    const labels = courseOrder.map((c) => (c.course_title || '').split(' — ')[0]);
    const data = courseOrder.map((c) => c.completion_percentage);

    // Destroy existing chart if it exists
    Chart.getChart(compCtx)?.destroy();

    new Chart(compCtx, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          { label: 'Completion %', data, backgroundColor: 'rgba(111,66,193,.6)' },
        ],
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, max: 100 } },
      },
    });
  }
  drawCompletion();

  document.getElementById('progressSort')?.addEventListener('change', (e) => {
    Chart.getChart(compCtx)?.destroy();
    drawCompletion(e.target.value);
  });

// Watch time line
  const watchCtx = document.getElementById('watchLine');
// const labels = Array.from({length: 14}, (_, i) => `D-${14-i}`);
// const mins = labels.map(()=> Math.floor(10 + Math.random()*40));
  if (watchCtx && typeof Chart !== 'undefined') {
    new Chart(watchCtx, {
      type: 'line',
      data: {
        labels: safeWatchTimeLabels,
        datasets: [
          {
            label: 'Minutes',
            data: safeWatchTimeData,
            tension: 0.35,
            borderColor: '#6f42c1',
            backgroundColor: 'rgba(111,66,193,.12)',
            fill: true,
            pointRadius: 0,
          },
        ],
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } },
      },
    });
  }

// KPIs from data (re-calculating to ensure accuracy if needed, though most should come from backend now)
// If your backend provides these directly, you might remove these lines.
// For now, re-calculating from `myCourses` as a fallback or if not all KPIs are directly passed.
  const kpiEnrolledEl = document.getElementById('kpiEnrolled');
  const kpiHoursEl = document.getElementById('kpiHours');
  const kpiCompletionEl = document.getElementById('kpiCompletion');

  if (kpiEnrolledEl) kpiEnrolledEl.textContent = safeMyCourses.length;
  const totalHoursWatchedFromCourses = safeMyCourses.reduce(
    (sum, course) => sum + (Number(course.hours) || 0),
    0
  );
  if (kpiHoursEl) kpiHoursEl.textContent = totalHoursWatchedFromCourses.toFixed(1) + ' h';

  const totalProgress = safeMyCourses.reduce(
    (sum, course) => sum + (Number(course.progress) || 0),
    0
  );
  const avgCompletion =
    safeMyCourses.length > 0 ? Math.round((totalProgress / safeMyCourses.length) * 100) : 0;
  if (kpiCompletionEl) kpiCompletionEl.textContent = avgCompletion + '%';

// Refresh progress (demo)
  document.getElementById('refreshProgress')?.addEventListener('click', () => {
  // pretend new data: nudge progresses randomly
  // myCourses.forEach(c=> c.progress = Math.min(1, Math.max(0, c.progress + (Math.random()*.1 - .05))));
  // Chart.getChart(compCtx)?.destroy();
  // drawCompletion(document.getElementById('progressSort').value);
  // renderCourses(myCourses);
  location.reload(); // Refresh the page to get new data
  });
})();
