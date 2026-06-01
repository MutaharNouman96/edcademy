(function () {
  'use strict';

  // Guard: only run on the dashboard page (coursesGrid element is unique to it)
  const coursesGridEl = document.getElementById('coursesGrid');
  if (!coursesGridEl) return;

  // ── Data from PHP ────────────────────────────────────────────────────────
  const myCourses            = Array.isArray(window.myCourses)            ? window.myCourses            : [];
  const newVideos            = Array.isArray(window.newVideos)            ? window.newVideos            : [];
  const courseCompletionData = Array.isArray(window.courseCompletionData) ? window.courseCompletionData : [];
  const watchTimeLabels      = Array.isArray(window.watchTimeLabels)      ? window.watchTimeLabels      : [];
  const watchTimeData        = Array.isArray(window.watchTimeData)        ? window.watchTimeData        : [];

  const courseDetailsUrlTpl =
    document.getElementById('dashboard-data')?.dataset?.courseDetailsUrl || '#';

  function courseUrl(id) {
    return courseDetailsUrlTpl.replace('_COURSE_ID_', id);
  }

  // ── Helpers ──────────────────────────────────────────────────────────────
  function esc(str) {
    return String(str ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // Subject → colour pair (deterministic by first char code)
  const subjectPalettes = [
    ['#7c3aed','#ede9fe'], ['#2563eb','#dbeafe'], ['#059669','#d1fae5'],
    ['#d97706','#fef3c7'], ['#db2777','#fce7f3'], ['#0891b2','#cffafe'],
  ];
  function subjectColour(subject) {
    const idx = (subject?.charCodeAt(0) || 0) % subjectPalettes.length;
    return subjectPalettes[idx];
  }

  // ── Render: Course grid ─────────────────────────────────────────────────
  function renderCourses(list) {
    const grid = document.getElementById('coursesGrid');
    if (!grid) return;

    if (!list || list.length === 0) {
      grid.innerHTML = `
        <div class="col-12">
          <div class="empty-state">
            <div class="empty-state-ico"><i class="bi bi-journal-x"></i></div>
            <div class="fw-semibold mb-1">No courses yet</div>
            <div class="empty-state-text mb-3">Browse our catalogue and enrol in something new!</div>
            <a href="/courses" class="btn btn-primary" style="border-radius:12px;">
              <i class="bi bi-compass me-1"></i> Browse Courses
            </a>
          </div>
        </div>`;
      return;
    }

    grid.innerHTML = list.map((c) => {
      const pct     = Math.round((Number(c.progress) || 0) * 100);
      const hours   = (Number(c.hours) || 0).toFixed(1);
      const title   = esc(c.title   || 'Untitled Course');
      const subject = esc(c.subject || 'General');
      const last    = esc(c.last    || 'Never');
      const url     = courseUrl(c.id);
      const [fg, bg] = subjectColour(c.subject);

      const thumbHtml = c.thumb
        ? `<img class="c-thumb" src="${esc(c.thumb)}" alt="${title}" loading="lazy">`
        : `<div class="c-thumb-placeholder"><i class="bi bi-play-circle"></i></div>`;

      const newBadge = Number(c.newVideos) > 0
        ? `<span class="c-new-badge">${parseInt(c.newVideos)} new</span>`
        : '';

      const completedLabel = pct >= 100
        ? `<span style="color:#059669;font-weight:700;font-size:.78rem;"><i class="bi bi-check-circle-fill me-1"></i>Completed</span>`
        : `<span class="c-prog-pct">${pct}%</span><span class="c-prog-text ms-1">complete</span>`;

      return `
        <div class="col-sm-6 col-xl-4">
          <div class="card c-card position-relative">
            ${thumbHtml}
            ${newBadge}
            <div class="c-body d-flex flex-column h-100">
              <span class="c-subject" style="background:${bg};color:${fg}">${subject}</span>
              <div class="c-title">${title}</div>
              <div class="c-meta"><i class="bi bi-clock me-1"></i>Last viewed ${last}</div>
              <div class="c-prog-bar">
                <div class="c-prog-fill" style="width:${pct}%"></div>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>${completedLabel}</div>
                <span class="c-prog-text">${hours} h watched</span>
              </div>
              <div class="mt-auto">
                <a class="btn-resume d-block text-center text-decoration-none" href="${url}">
                  <i class="bi bi-play-fill me-1"></i>${pct >= 100 ? 'Review' : 'Resume'}
                </a>
              </div>
            </div>
          </div>
        </div>`;
    }).join('');
  }

  // ── Render: New Videos ──────────────────────────────────────────────────
  function renderNewVideos() {
    const container = document.getElementById('newVideosList');
    if (!container) return;

    if (!newVideos || newVideos.length === 0) {
      container.innerHTML = `
        <div class="empty-state m-1">
          <div class="empty-state-ico"><i class="bi bi-camera-video-off"></i></div>
          <div class="fw-semibold mb-1">No new videos yet</div>
          <div class="empty-state-text">New lessons from your enrolled courses will show up here.</div>
        </div>`;
      return;
    }

    container.innerHTML = newVideos.map((n) => {
      const url      = courseUrl(n.course_id);
      const lesson   = esc(n.lesson  || 'Untitled Lesson');
      const course   = esc(n.course  || '');
      const when     = esc(n.when    || '');
      const duration = n.duration ? `<span class="video-dur ms-2"><i class="bi bi-clock me-1"></i>${esc(n.duration)}</span>` : '';
      const [fg, bg] = subjectColour(n.course);

      return `
        <div class="video-item">
          <div class="video-thumb" style="background:${bg};color:${fg}">
            <i class="bi bi-camera-video"></i>
          </div>
          <div class="flex-grow-1 min-w-0">
            <div class="video-title text-truncate">${lesson}</div>
            <div class="video-meta">${course} &bull; ${when}${duration}</div>
          </div>
          <a class="btn-watch text-decoration-none" href="${url}">
            <i class="bi bi-play-fill me-1"></i>Watch
          </a>
        </div>`;
    }).join('');
  }

  // ── Global Search (hero search box) ────────────────────────────────────
  const globalSearchEl = document.getElementById('globalSearch');
  globalSearchEl?.addEventListener('input', (e) => {
    const q = (e.target.value || '').toLowerCase().trim();
    renderCourses(
      q
        ? myCourses.filter(c =>
            (c.title   || '').toLowerCase().includes(q) ||
            (c.subject || '').toLowerCase().includes(q))
        : myCourses
    );
  });

  // ── Course search (section filter) ─────────────────────────────────────
  const courseSearchEl = document.getElementById('courseSearch');
  courseSearchEl?.addEventListener('input', (e) => {
    const q = (e.target.value || '').toLowerCase().trim();
    renderCourses(
      q
        ? myCourses.filter(c =>
            (c.title   || '').toLowerCase().includes(q) ||
            (c.subject || '').toLowerCase().includes(q))
        : myCourses
    );
  });

  // ── Chart helpers ───────────────────────────────────────────────────────
  const PURPLE      = '#7c3aed';
  const PURPLE_FILL = 'rgba(124,58,237,.12)';

  // Completion bar chart
  const compCtx = document.getElementById('completionBar');
  let courseOrder = [...courseCompletionData];

  function drawCompletion(sort = 'desc') {
    if (!compCtx || typeof Chart === 'undefined') return;

    courseOrder.sort((a, b) =>
      sort === 'asc'
        ? a.completion_percentage - b.completion_percentage
        : b.completion_percentage - a.completion_percentage
    );

    const labels = courseOrder.map(c =>
      (c.course_title || '').length > 22
        ? (c.course_title || '').slice(0, 20) + '…'
        : (c.course_title || '')
    );
    const data = courseOrder.map(c => c.completion_percentage);

    Chart.getChart(compCtx)?.destroy();

    new Chart(compCtx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Completion %',
          data,
          backgroundColor: data.map(v =>
            v >= 100 ? 'rgba(16,185,129,.7)' :
            v >= 60  ? 'rgba(124,58,237,.65)' :
                       'rgba(124,58,237,.35)'
          ),
          borderRadius: 8,
          borderSkipped: false,
        }],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: ctx => ` ${ctx.parsed.y}% complete`,
            },
          },
        },
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 11 } } },
          y: {
            beginAtZero: true, max: 100,
            grid: { color: 'rgba(0,0,0,.04)' },
            ticks: { callback: v => v + '%', font: { size: 11 } },
          },
        },
      },
    });
  }

  drawCompletion();

  document.getElementById('progressSort')?.addEventListener('change', (e) => {
    drawCompletion(e.target.value);
  });

  document.getElementById('refreshProgress')?.addEventListener('click', () => {
    location.reload();
  });

  // Watch time line chart
  const watchCtx = document.getElementById('watchLine');
  if (watchCtx && typeof Chart !== 'undefined') {
    new Chart(watchCtx, {
      type: 'line',
      data: {
        labels: watchTimeLabels,
        datasets: [{
          label: 'Minutes',
          data: watchTimeData,
          tension: 0.4,
          borderColor: PURPLE,
          backgroundColor: PURPLE_FILL,
          fill: true,
          pointRadius: 0,
          pointHoverRadius: 5,
          borderWidth: 2.5,
        }],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: ctx => ` ${ctx.parsed.y} min`,
            },
          },
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: {
              maxTicksLimit: 7,
              font: { size: 10 },
              maxRotation: 0,
            },
          },
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,.04)' },
            ticks: { font: { size: 10 }, callback: v => v + 'm' },
          },
        },
      },
    });
  }

  // ── KPI overrides from client-side data ─────────────────────────────────
  // The server already renders these; JS below only corrects them if needed.
  const kpiEnrolledEl   = document.getElementById('kpiEnrolled');
  const kpiHoursEl      = document.getElementById('kpiHours');
  const kpiCompletionEl = document.getElementById('kpiCompletion');

  if (kpiEnrolledEl && myCourses.length) {
    kpiEnrolledEl.textContent = myCourses.length;
  }

  const totalHours = myCourses.reduce((s, c) => s + (Number(c.hours) || 0), 0);
  if (kpiHoursEl && totalHours > 0) {
    kpiHoursEl.innerHTML = `${totalHours.toFixed(1)}<span style="font-size:1rem;font-weight:600"> h</span>`;
  }

  const avgCompletion = myCourses.length
    ? Math.round(myCourses.reduce((s, c) => s + (Number(c.progress) || 0), 0) / myCourses.length * 100)
    : 0;
  if (kpiCompletionEl && myCourses.length) {
    kpiCompletionEl.innerHTML = `${avgCompletion}<span style="font-size:1rem;font-weight:600">%</span>`;
  }

  // ── Init ─────────────────────────────────────────────────────────────────
  renderCourses(myCourses);
  renderNewVideos();
})();
