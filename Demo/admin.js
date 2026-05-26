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
  function generateReport(candidate) {

  const reportWindow = window.open('', '_blank');

  reportWindow.document.write(`

    <html>

    <head>

      <title>
        Faculty Appraisal Report
      </title>

      <style>

        body{
          font-family:Arial,sans-serif;
          padding:40px;
          color:#222;
          background:white;
        }

        .container{
          max-width:900px;
          margin:auto;
        }

        h1{
          text-align:center;
          margin-bottom:10px;
        }

        .subtitle{
          text-align:center;
          color:#666;
          margin-bottom:35px;
        }

        .section{
          margin-bottom:30px;
        }

        .section-title{
          font-size:18px;
          font-weight:bold;
          border-bottom:2px solid #ddd;
          padding-bottom:8px;
          margin-bottom:15px;
        }

        .info-grid{
          display:grid;
          grid-template-columns:1fr 1fr;
          gap:15px;
        }

        .info-box{
          border:1px solid #ddd;
          padding:14px;
          border-radius:8px;
        }

        .label{
          font-size:13px;
          color:#666;
          margin-bottom:5px;
        }

        .value{
          font-size:15px;
          font-weight:600;
        }

        table{
          width:100%;
          border-collapse:collapse;
        }

        table th,
        table td{
          border:1px solid #ccc;
          padding:10px;
          text-align:left;
        }

        table th{
          background:#f5f5f5;
        }

        .score-box{
          margin-top:35px;
          border:2px solid #111;
          padding:25px;
          text-align:center;
          border-radius:10px;
        }

        .score{
          font-size:40px;
          font-weight:bold;
          margin-top:10px;
        }

        .print-btn{
          position:fixed;
          top:20px;
          right:20px;
          padding:10px 18px;
          border:none;
          border-radius:6px;
          background:#111827;
          color:white;
          cursor:pointer;
        }

        @media print{

          .print-btn{
            display:none;
          }

          body{
            padding:0;
          }

        }

      </style>

    </head>

    <body>

      <button class="print-btn" onclick="window.print()">
        Print Report
      </button>

      <div class="container">

        <h1>
          Faculty Appraisal Report
        </h1>

        <div class="subtitle">
          Academic Performance Evaluation
        </div>

        <div class="section">

          <div class="section-title">
            Candidate Information
          </div>

          <div class="info-grid">

            <div class="info-box">
              <div class="label">Candidate ID</div>
              <div class="value">${candidate.id}</div>
            </div>

            <div class="info-box">
              <div class="label">Candidate Name</div>
              <div class="value">${candidate.name}</div>
            </div>

            <div class="info-box">
              <div class="label">Reviewed Date</div>
              <div class="value">
                ${new Date(candidate.reviewedAt).toLocaleString()}
              </div>
            </div>

            <div class="info-box">
              <div class="label">Final Score</div>
              <div class="value">
                ${candidate.totalMarks} / 100
              </div>
            </div>

          </div>

        </div>

        <div class="section">

          <div class="section-title">
            Final Evaluation Summary
          </div>

          <table>

            <thead>

              <tr>
                <th>Category</th>
                <th>Marks</th>
              </tr>

            </thead>

            <tbody>

              <tr>
                <td>Category 1 - Teaching & Learning</td>
                <td>${candidate.cat1Marks || '-'}</td>
              </tr>

              <tr>
                <td>Category 2 - Professional Development</td>
                <td>${candidate.cat2Marks || '-'}</td>
              </tr>

              <tr>
                <td>Category 3 - Research & Contributions</td>
                <td>${candidate.cat3Marks || '-'}</td>
              </tr>

            </tbody>

          </table>

        </div>

        <div class="score-box">

          <div>
            OVERALL SCORE
          </div>

          <div class="score">
            ${candidate.totalMarks}/100
          </div>

        </div>

      </div>

    </body>

    </html>

  `);

  reportWindow.document.close();
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
    card.addEventListener('click', () => {

  if (candidate.reviewedAt) {
    generateReport(candidate);
  } else {
    openCandidateDetail(candidate.id);
  }

});
    listElement.appendChild(card);
  });
}

renderList(candidateList, pendingCandidates, 'No pending candidates.');
renderList(reviewedList, reviewedCandidates, 'No reviewed candidates.');