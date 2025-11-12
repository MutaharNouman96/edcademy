// ---------- Sample Data (replace with API sources) ----------
const myCourses = [
  { id: 1, title: 'Calculus I — Limits & Derivatives', subject: 'Math', progress: 0.72, hours: 6.2, last: '2d ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=Math', newVideos: 1 },
  { id: 2, title: 'Physics: Mechanics (GCSE)', subject: 'Physics', progress: 0.36, hours: 3.1, last: '5h ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=Physics', newVideos: 0 },
  { id: 3, title: 'IELTS Speaking Mastery', subject: 'English', progress: 0.58, hours: 4.4, last: '1d ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=IELTS', newVideos: 2 },
  { id: 4, title: 'Organic Chemistry Basics', subject: 'Chemistry', progress: 0.21, hours: 1.0, last: '6d ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=Chem', newVideos: 0 },
  { id: 5, title: 'Algebra II — Functions', subject: 'Math', progress: 0.93, hours: 7.0, last: '3h ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=Algebra', newVideos: 0 },
  { id: 6, title: 'Essay Writing Bootcamp', subject: 'English', progress: 0.49, hours: 2.9, last: '4d ago', thumb: 'https://via.placeholder.com/320x180/ffffff/6f42c1?text=Writing', newVideos: 1 }
];

const newVideos = [
  { course: 'IELTS Speaking Mastery', lesson: 'Band 7+ Part 2', when: '2 hours ago', duration: '9m', id: 'nv1' },
  { course: 'Calculus I — Limits & Derivatives', lesson: 'L\'Hôpital Rule Basics', when: 'Yesterday', duration: '12m', id: 'nv2' },
  { course: 'Essay Writing Bootcamp', lesson: 'Upgrade your Thesis', when: '2 days ago', duration: '7m', id: 'nv3' }
];

const certificates = [
  { course: 'Algebra II — Functions', date: '2025‑09‑10', id: 'cert1' },
  { course: 'Intro to Data Literacy', date: '2025‑08‑02', id: 'cert2' }
];

const payments = [
  { date: '2025‑09‑12', course: 'IELTS Speaking Mastery', method: 'Card •••• 4242', amount: 39.00 },
  { date: '2025‑09‑05', course: 'Organic Chemistry Basics', method: 'PayPal', amount: 35.00 },
  { date: '2025‑08‑28', course: 'Essay Writing Bootcamp', method: 'Card •••• 4242', amount: 19.00 },
  { date: '2025‑08‑20', course: 'Calculus I — Limits & Derivatives', method: 'Card •••• 4242', amount: 49.00 },
  { date: '2025‑08‑15', course: 'Physics: Mechanics (GCSE)', method: 'Card •••• 4242', amount: 39.00 },
  { date: '2025‑08‑02', course: 'Algebra II — Functions', method: 'Card •••• 4242', amount: 25.00 }
];

// ---------- Populate UI ----------
function renderCourses(list){
  const grid = document.getElementById('coursesGrid');
  grid.innerHTML = list.map(c => `
    <div class="col-sm-6 col-xl-4">
      <div class="card course-card h-100 border-0">
        <img src="${c.thumb}" class="card-img-top rounded-top" alt="${c.title}">
        <div class="card-body d-flex flex-column">
          <h6 class="mb-1">${c.title}</h6>
          <div class="small text-muted mb-2">${c.subject} • Last viewed ${c.last}</div>
          <div class="progress mb-2"><div class="progress-bar bg-primary" role="progressbar" style="width:${c.progress*100}%" aria-valuenow="${Math.round(c.progress*100)}" aria-valuemin="0" aria-valuemax="100"></div></div>
          <div class="d-flex justify-content-between small mb-3"><span>${Math.round(c.progress*100)}% complete</span><span>${c.hours.toFixed(1)} h watched</span></div>
          <div class="mt-auto d-flex gap-2">
            <a class="btn btn-sm btn-primary w-100"><i class="bi bi-play-fill me-1"></i> Resume</a>
            <button class="btn btn-sm btn-outline-primary" title="Course menu"><i class="bi bi-three-dots"></i></button>
          </div>
        </div>
        ${c.newVideos>0 ? `<span class="position-absolute top-0 end-0 m-2 badge text-bg-warning">${c.newVideos} new</span>`:''}
      </div>
    </div>`).join('');
}

function renderNewVideos(){
  const ul = document.getElementById('newVideosList');
  ul.innerHTML = newVideos.map(n => `
    <li class="list-group-item d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded border d-flex align-items-center justify-content-center" style="width:56px;height:36px;background:var(--primary-50);color:var(--primary)"><i class="bi bi-camera-video"></i></div>
        <div>
          <div class="fw-semibold">${n.lesson} <span class="text-muted">• ${n.duration}</span></div>
          <div class="small text-muted">${n.course} • ${n.when}</div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-circle me-1"></i> Add to queue</button>
        <a class="btn btn-sm btn-primary"><i class="bi bi-play-fill me-1"></i> Watch</a>
      </div>
    </li>`).join('');
}

function renderCerts(){
  const ul = document.getElementById('certList');
  ul.innerHTML = certificates.map(c => `
    <li class="list-group-item d-flex align-items-center justify-content-between">
      <div>
        <div class="fw-semibold">${c.course}</div>
        <div class="small text-muted">Issued ${c.date}</div>
      </div>
      <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-primary"><i class="bi bi-download"></i></button>
        <button class="btn btn-outline-primary"><i class="bi bi-box-arrow-up-right"></i></button>
      </div>
    </li>`).join('');
}

function renderPayments(){
  const tbody = document.querySelector('#paymentsTable tbody');
  tbody.innerHTML = payments.map(p => `
    <tr>
      <td>${p.date}</td>
      <td>${p.course}</td>
      <td>${p.method}</td>
      <td class="text-end">$${p.amount.toFixed(2)}</td>
    </tr>`).join('');
  const total = payments.reduce((a,b)=>a+b.amount,0);
  document.getElementById('kpiSpend').textContent = `$${total.toFixed(2)}`;
}

renderCourses(myCourses);
renderNewVideos();
renderCerts();
renderPayments();

// Search courses
document.getElementById('courseSearch').addEventListener('input', (e)=>{
  const q = e.target.value.toLowerCase();
  renderCourses(myCourses.filter(c => c.title.toLowerCase().includes(q) || c.subject.toLowerCase().includes(q)));
});

// ---------- Charts ----------
// Completion per course bar chart
const compCtx = document.getElementById('completionBar');
let courseOrder = [...myCourses];
function drawCompletion(sort='desc'){
  courseOrder.sort((a,b)=> sort==='asc' ? a.progress - b.progress : b.progress - a.progress);
  const labels = courseOrder.map(c=> c.title.split(' — ')[0]);
  const data = courseOrder.map(c=> Math.round(c.progress*100));
  new Chart(compCtx, {
    type: 'bar',
    data: { labels, datasets: [{ label: 'Completion %', data, backgroundColor: 'rgba(111,66,193,.6)' }] },
    options: { responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true, max:100 } } }
  });
}
drawCompletion();

document.getElementById('progressSort').addEventListener('change', (e)=>{
  // destroy and redraw
  Chart.getChart(compCtx)?.destroy();
  drawCompletion(e.target.value);
});

// Watch time line
const watchCtx = document.getElementById('watchLine');
const labels = Array.from({length: 14}, (_, i) => `D-${14-i}`);
const mins = labels.map(()=> Math.floor(10 + Math.random()*40));
new Chart(watchCtx, {
  type: 'line',
  data: { labels, datasets: [{ label: 'Minutes', data: mins, tension:.35, borderColor: '#6f42c1', backgroundColor: 'rgba(111,66,193,.12)', fill: true, pointRadius: 0 }] },
  options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// KPIs from data
document.getElementById('kpiEnrolled').textContent = myCourses.length;
const totalMins = mins.reduce((a,b)=>a+b,0) + myCourses.reduce((a,c)=>a + c.hours*60, 0);
document.getElementById('kpiHours').textContent = (totalMins/60).toFixed(1) + ' h';
const avgComp = Math.round(myCourses.reduce((a,c)=>a + c.progress, 0)/myCourses.length * 100);
document.getElementById('kpiCompletion').textContent = avgComp + '%';

// Refresh progress (demo)
document.getElementById('refreshProgress').addEventListener('click', ()=>{
  // pretend new data: nudge progresses randomly
  myCourses.forEach(c=> c.progress = Math.min(1, Math.max(0, c.progress + (Math.random()*.1 - .05))));
  Chart.getChart(compCtx)?.destroy();
  drawCompletion(document.getElementById('progressSort').value);
  renderCourses(myCourses);
});
