
document
  .getElementById('download-pdf')
  .addEventListener('click', downloadPDF);
  
function downloadPDF() {

  /* Fill PDF content */

  document.getElementById('pdf-c1-student').textContent =
    document.getElementById('c1-student-centric-display').textContent;

  document.getElementById('pdf-c1-ict').textContent =
    document.getElementById('c1-ict-tools-display').textContent;

  document.getElementById('pdf-c1-best').textContent =
    document.getElementById('c1-best-practice-display').textContent;

  document.getElementById('pdf-c2-fdp').textContent =
    document.getElementById('c2-fdp-workshop-display').textContent;

  document.getElementById('pdf-c2-mooc').textContent =
    document.getElementById('c2-mooc-courses-display').textContent;

  document.getElementById('pdf-c2-portfolio').textContent =
    document.getElementById('c2-portfolio-event-display').textContent;

  document.getElementById('pdf-c3-journal').textContent =
    document.getElementById('c3-journal-publications-display').textContent;

  document.getElementById('pdf-c3-books').textContent =
    document.getElementById('c3-books-chapters-display').textContent;

  document.getElementById('pdf-c3-sponsored').textContent =
    document.getElementById('c3-sponsored-projects-display').textContent;

  document.getElementById('pdf-c3-patents').textContent =
    document.getElementById('c3-patents-display').textContent;

  const element =
    document.getElementById('pdf-report');

  element.style.display = 'block';

  html2pdf()
    .set({
      margin:0,
      filename:'Faculty_Appraisal_Report.pdf',
      image:{
        type:'jpeg',
        quality:1
      },
      html2canvas:{
        scale:2
      },
      jsPDF:{
        unit:'mm',
        format:'a4',
        orientation:'portrait'
      }
    })
    .from(element)
    .save()
    .then(() => {
      element.style.display = 'none';
    });
}