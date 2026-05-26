const candidateList = document.getElementById('candidate-list');
const reviewedList = document.getElementById('reviewed-list');

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
      fdpWorkshop: 4,
      moocCourses: 3,
      portfolioEvent: 2,
      seminarConference: 5,
      universityAcademic: 2
    },
    category3: {
      journalPublications: 6,
      booksChapters: 1,
      sponsoredProjects: 2,
      consultancyProjects: 1,
      phdGuidance: 3,
      phdRegistration: 2,
      studentProjectGuidance: 8,
      productDevelopment: 1,
      applicationDevelopment: 0,
      patents: 1,
      copyright: 0
    }
  }
];

const reviewedCandidates = [
  {
    id: 'CAND-000',
    name: 'Dr. Anjali Menon',
    reviewedAt: '2026-05-26T09:50:00Z',
    totalMarks: 78
  }
];

function openCandidateDetail(candidateId) {
  window.location.href = `admin-review.html?id=${encodeURIComponent(candidateId)}`;
}

function renderList(listElement, candidates, emptyText) {
  listElement.innerHTML = '';
  if (candidates.length === 0) {
    const empty = document.createElement('div');
    empty.className = 'no-candidates';
    empty.textContent = emptyText;
    listElement.appendChild(empty);
    return;
  }

  candidates.forEach(candidate => {
    const card = document.createElement('div');
    card.className = 'candidate-card';
    card.innerHTML = `
      <h3>${candidate.name}</h3>
      <p>ID: ${candidate.id}</p>
      <p>${candidate.reviewedAt ? 'Reviewed: ' + new Date(candidate.reviewedAt).toLocaleString() : 'Submitted: ' + new Date(candidate.submittedAt).toLocaleString()}</p>
    `;

    card.addEventListener('click', () => openCandidateDetail(candidate.id));
    listElement.appendChild(card);
  });
}

renderList(candidateList, pendingCandidates, 'No pending candidates.');
renderList(reviewedList, reviewedCandidates, 'No reviewed candidates.');