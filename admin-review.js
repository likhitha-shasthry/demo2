/**
 * admin-review.js
 * ───────────────
 * Handles candidate detail view & scoring for admin-review.html
 *
 * Category 1 (max 20):  Manual input by admin
 * Category 2 (max 30):  Auto-calculated from activity counts
 * Category 3 (max 50):  Auto-calculated from research counts
 */

/* ─────────────────────────────────────────────────────────────
   1.  MOCK DATA — replace with a real fetch() / localStorage
       call once your backend is wired up.
───────────────────────────────────────────────────────────── */
const pendingCandidates = [
  {
    id: 'CAND-001',
    name: 'Dr. Priya Sharma',
    submittedAt: '2026-05-25T14:30:00Z',
    category1: {
      studentCentric: 'Implemented flipped classroom techniques with project-based assessment.',
      ictTools: 'Used Moodle, Google Classroom and Kahoot for interactive lectures.',
      bestPractice: 'Introduced peer review sessions to improve student engagement.'
    },
    category2: {
      fdpWorkshop:         4,
      moocCourses:         3,
      portfolioEvent:      2,
      seminarConference:   5,
      universityAcademic:  2
    },
    category3: {
      journalPublications:    6,
      booksChapters:          1,
      sponsoredProjects:      2,
      consultancyProjects:    1,
      phdGuidance:            3,
      phdRegistration:        2,
      studentProjectGuidance: 8,
      productDevelopment:     1,
      applicationDevelopment: 0,
      patents:                1,
      copyright:              0
    }
  }
];

/* ─────────────────────────────────────────────────────────────
   2.  SCORING RULES
───────────────────────────────────────────────────────────── */

/** Category 2 — per-activity point values and individual caps */
const CAT2_RULES = {
  fdpWorkshop:        { label: 'FDP / Workshops',         pts: 2, cap: 10 },
  moocCourses:        { label: 'MOOC Courses',            pts: 2, cap: 8  },
  portfolioEvent:     { label: 'Portfolio Events',        pts: 1, cap: 4  },
  seminarConference:  { label: 'Seminars / Conferences',  pts: 1, cap: 5  },
  universityAcademic: { label: 'University Academic Work',pts: 1, cap: 3  },
};
const CAT2_MAX = 30;

/** Category 3 — per-item point values and individual caps */
const CAT3_RULES = {
  journalPublications:    { label: 'Journal Publications',       pts: 4, cap: 20 },
  booksChapters:          { label: 'Books / Chapters',           pts: 3, cap: 9  },
  sponsoredProjects:      { label: 'Sponsored Projects',         pts: 3, cap: 6  },
  consultancyProjects:    { label: 'Consultancy Projects',        pts: 2, cap: 4  },
  phdGuidance:            { label: 'PhD Guidance (awarded)',      pts: 3, cap: 9  },
  phdRegistration:        { label: 'PhD Guidance (registered)',   pts: 1, cap: 4  },
  studentProjectGuidance: { label: 'Student Project Guidance',   pts: 1, cap: 5  },
  productDevelopment:     { label: 'Product Development',        pts: 2, cap: 4  },
  applicationDevelopment: { label: 'Application Development',    pts: 2, cap: 4  },
  patents:                { label: 'Patents',                    pts: 3, cap: 6  },
  copyright:              { label: 'Copyrights',                 pts: 2, cap: 4  },
};
const CAT3_MAX = 50;

/* ─────────────────────────────────────────────────────────────
   3.  UTILITY
───────────────────────────────────────────────────────────── */
function getQueryParam(name) {
  return new URLSearchParams(window.location.search).get(name);
}

function findCandidate(id) {
  return pendingCandidates.find(c => c.id === id) || null;
}

function calcCategory(data, rules, max) {
  let total = 0;
  const breakdown = [];
  for (const key in rules) {
    const r   = rules[key];
    const cnt = data[key] ?? 0;
    const raw = cnt * r.pts;
    const pts = Math.min(raw, r.cap);
    total += pts;
    breakdown.push({ key, label: r.label, count: cnt, pts, cap: r.cap, perItem: r.pts });
  }
  return { score: Math.min(total, max), breakdown };
}

/* ─────────────────────────────────────────────────────────────
   4.  RENDER
───────────────────────────────────────────────────────────── */
function renderMetricsList(containerId, breakdown, catColor) {
  const el = document.getElementById(containerId);
  el.innerHTML = '';
  breakdown.forEach(item => {
    const div = document.createElement('div');
    div.className = 'metric-item';
    div.innerHTML = `
      <span class="metric-name">${item.label}</span>
      <div class="metric-row">
        <span class="metric-count">${item.count}</span>
        <span class="metric-pts">→ <strong>${item.pts}</strong> pts</span>
      </div>`;
    el.appendChild(div);
  });
}

function updateTotals() {
  const cat1Val = parseInt(document.getElementById('cat1-input').value) || 0;
  const cat2Score = parseInt(document.getElementById('cat2-score').dataset.score) || 0;
  const cat3Score = parseInt(document.getElementById('cat3-score').dataset.score) || 0;

  const total = cat1Val + cat2Score + cat3Score;

  document.getElementById('sum-cat1').textContent  = `${cat1Val} / 20`;
  document.getElementById('sum-cat2').textContent  = `${cat2Score} / 30`;
  document.getElementById('sum-cat3').textContent  = `${cat3Score} / 50`;
  document.getElementById('sum-total').textContent = `${total} / 100`;
  document.getElementById('totalScore').textContent = total;
}

/* ─────────────────────────────────────────────────────────────
   5.  INIT
───────────────────────────────────────────────────────────── */
function init() {
  const id        = getQueryParam('id');
  const candidate = findCandidate(id);

  if (!candidate) {
    document.body.innerHTML = `
      <div style="display:grid;place-items:center;height:100vh;font-family:sans-serif">
        <p style="font-size:20px;color:#666">Candidate not found.</p>
      </div>`;
    return;
  }

  /* ── Candidate header ── */
  document.getElementById('candidateId').textContent   = candidate.id;
  document.getElementById('candidateName').textContent = candidate.name;
  document.getElementById('submittedAt').textContent   =
    'Submitted: ' + new Date(candidate.submittedAt).toLocaleString('en-IN', { dateStyle:'medium', timeStyle:'short' });

  /* ── Category 1: text fields ── */
  const c1 = candidate.category1;
  document.getElementById('cat1-studentCentric').textContent = c1.studentCentric;
  document.getElementById('cat1-ictTools').textContent       = c1.ictTools;
  document.getElementById('cat1-bestPractice').textContent   = c1.bestPractice;

  /* ── Category 1: marks input listener ── */
  const input = document.getElementById('cat1-input');
  const errorEl = document.getElementById('cat1-error');

  input.addEventListener('input', () => {
    const val = parseInt(input.value);
    const invalid = isNaN(val) || val < 0 || val > 20;
    input.classList.toggle('invalid', invalid);
    errorEl.classList.toggle('hidden', !invalid || input.value === '');
    if (!invalid) updateTotals();
    else {
      // reset total contribution for cat1
      document.getElementById('sum-cat1').textContent  = '0 / 20';
      const c2 = parseInt(document.getElementById('cat2-score').dataset.score) || 0;
      const c3 = parseInt(document.getElementById('cat3-score').dataset.score) || 0;
      document.getElementById('sum-total').textContent = `${c2 + c3} / 100`;
      document.getElementById('totalScore').textContent = c2 + c3;
    }
  });

  /* ── Category 2: auto ── */
  const { score: cat2Score, breakdown: cat2Breakdown } = calcCategory(candidate.category2, CAT2_RULES, CAT2_MAX);
  renderMetricsList('cat2-metrics', cat2Breakdown);
  const cat2ScoreEl = document.getElementById('cat2-score');
  cat2ScoreEl.textContent   = `${cat2Score} / ${CAT2_MAX}`;
  cat2ScoreEl.dataset.score = cat2Score;

  /* ── Category 3: auto ── */
  const { score: cat3Score, breakdown: cat3Breakdown } = calcCategory(candidate.category3, CAT3_RULES, CAT3_MAX);
  renderMetricsList('cat3-metrics', cat3Breakdown);
  const cat3ScoreEl = document.getElementById('cat3-score');
  cat3ScoreEl.textContent   = `${cat3Score} / ${CAT3_MAX}`;
  cat3ScoreEl.dataset.score = cat3Score;

  /* ── Initial totals (cat1 = 0 until admin inputs) ── */
  document.getElementById('sum-cat2').textContent = `${cat2Score} / 30`;
  document.getElementById('sum-cat3').textContent = `${cat3Score} / 50`;
  document.getElementById('sum-total').textContent = `${cat2Score + cat3Score} / 100`;
  document.getElementById('totalScore').textContent = cat2Score + cat3Score;
}

/* ─────────────────────────────────────────────────────────────
   6.  SUBMIT
───────────────────────────────────────────────────────────── */
function submitReview() {
  const input  = document.getElementById('cat1-input');
  const val    = parseInt(input.value);

  /* Validate cat1 input */
  if (isNaN(val) || val < 0 || val > 20) {
    input.classList.add('invalid');
    document.getElementById('cat1-error').classList.remove('hidden');
    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  const cat2Score = parseInt(document.getElementById('cat2-score').dataset.score) || 0;
  const cat3Score = parseInt(document.getElementById('cat3-score').dataset.score) || 0;
  const total = val + cat2Score + cat3Score;

  const id = getQueryParam('id');

  /* ── Save to reviewedCandidates (in real app: POST to backend) ── */
  const result = {
    id,
    reviewedAt:  new Date().toISOString(),
    cat1Marks:   val,
    cat2Marks:   cat2Score,
    cat3Marks:   cat3Score,
    totalMarks:  total
  };

  console.log('Submitted review:', result);

  /* ── Disable button ── */
  const btn = document.getElementById('submitBtn');
  btn.disabled    = true;
  btn.textContent = '✓ Submitted';

  /* ── Toast ── */
  const toast = document.getElementById('toast');
  toast.classList.remove('hidden');
  setTimeout(() => {
    toast.classList.add('hidden');
    /* Redirect back after short delay */
    history.back();
  }, 2500);
}

/* ─────────────────────────────────────────────────────────────
   7.  KICK OFF
───────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', init);
