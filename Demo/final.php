<?php
session_start();

if (!isset($_SESSION['cat1']) || !isset($_SESSION['cat2']) || !isset($_SESSION['cat3'])) {
    header("Location: cat1.php");
    exit();
}


if (isset($_POST['final_submit'])) {

    $conn = new mysqli("localhost", "root", "", "faculty");

    $cat1 = $_SESSION['cat1'];
    $cat2 = $_SESSION['cat2'];
    $cat3 = $_SESSION['cat3'];
    $applicant_id = $_SESSION['applicant_id'];

   $applicant_id = $_SESSION['applicant_id'];

$sql = "INSERT INTO candidate_kpi (

    applicant_id,

    student_centric_learning,
    ict_tools_usage,
    best_practice_beyond_syllabus,

    fdp_workshop,
    mooc_courses,
    portfolio_event_coordination,
    seminar_webinar_conference,
    university_academic_work,

    journal_publications,
    books_book_chapters,
    sponsored_projects,
    consultancy_projects,

    phd_guidance,
    phd_registration,
    student_project_guidance,
    product_development,
    application_development,
    patents,
    copyright_work

) VALUES (

    '$applicant_id',

    '{$cat1['student_centric']}',
    '{$cat1['ict_tools']}',
    '{$cat1['best_practice']}',

    '{$cat2['fdp_workshop']}',
    '{$cat2['mooc_courses']}',
    '{$cat2['portfolio_event']}',
    '{$cat2['seminar_conference']}',
    '{$cat2['university_academic']}',

    '{$cat3['journal_publications']}',
    '{$cat3['books_chapters']}',
    '{$cat3['sponsored_projects']}',
    '{$cat3['consultancy_projects']}',

    '{$cat3['phd_guidance']}',
    '{$cat3['phd_registration']}',
    '{$cat3['student_project_guidance']}',
    '{$cat3['product_development']}',
    '{$cat3['application_development']}',
    '{$cat3['patents']}',
    '{$cat3['copyright']}'

)";

    $conn->query($sql);

    session_destroy();

echo "<script>
alert('Application Submitted Successfully!');
window.location.href='index.php';
</script>";

exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Final Submission — Application Review</title>
  <link rel="stylesheet" href="final.css" />
</head>

<body>
  <main class="page-shell">
    <section class="page-header">
      <h1>Application Review & Submission</h1>
      <p>Review all your information before final submission.</p>
    </section>

    <section class="page-body">
      <div class="submission-container">

        <!-- Category 1 Summary -->
        <div class="category-content">

    <div class="summary-item">
        <label>Student Centric Learning - 10 Marks</label>

        <p class="display-text">
            <?php echo $_SESSION['cat1']['student_centric']; ?>
        </p>
    </div>

    <div class="summary-item">
        <label>ICT Tools Usage - 10 Marks</label>

        <p class="display-text">
            <?php echo $_SESSION['cat1']['ict_tools']; ?>
        </p>
    </div>

    <div class="summary-item">
        <label>Best Practice - 10 Marks</label>

        <p class="display-text">
            <?php echo $_SESSION['cat1']['best_practice']; ?>
        </p>
    </div>

</div>

        <!-- Category 2 Summary -->
        <div class="category-content">

    <div class="summary-item">
        <label>FDP / Workshop</label>

        <p class="display-text">
            <?php echo $_SESSION['cat2']['fdp_workshop']; ?>
        </p>
    </div>

    <div class="summary-item">
        <label>MOOC Courses</label>

        <p class="display-text">
            <?php echo $_SESSION['cat2']['mooc_courses']; ?>
        </p>
    </div>

    <div class="summary-item">
        <label>Portfolio / Event Coordination</label>

        <p class="display-text">
            <?php echo $_SESSION['cat2']['portfolio_event']; ?>
        </p>
    </div>

    <div class="summary-item">
        <label>Seminar / Webinar / Conferences</label>

        <p class="display-text">
            <?php echo $_SESSION['cat2']['seminar_conference']; ?>
        </p>
    </div>

    <div class="summary-item">
        <label>University Academic Work</label>

        <p class="display-text">
            <?php echo $_SESSION['cat2']['university_academic']; ?>
        </p>
    </div>

</div>

        <!-- Category 3 Summary -->
        <div class="category-content">

    <div class="summary-item">
        <label>Journal Publications</label>

        <p class="display-text">
            <?php echo $_SESSION['cat3']['journal_publications']; ?>
        </p>
    </div>

    <div class="summary-item">
        <label>Books / Book Chapters</label>

        <p class="display-text">
            <?php echo $_SESSION['cat3']['books_chapters']; ?>
        </p>
    </div>

    <div class="summary-item">
        <label>Sponsored Projects</label>

        <p class="display-text">
            <?php echo $_SESSION['cat3']['sponsored_projects']; ?>
        </p>
    </div>

    <div class="summary-item">
        <label>Consultancy Projects</label>

        <p class="display-text">
            <?php echo $_SESSION['cat3']['consultancy_projects']; ?>
        </p>
    </div>

</div>

        <!-- PDF Download Section -->
        <div class="download-section">
          <h3>Download Application Summary</h3>
          <button type="button" class="btn btn-download" id="download-pdf">
            📥 Download as PDF
          </button>
        </div>

        <!-- Confirmation Section -->
        <div class="confirmation-section">
          <h3>Final Confirmation</h3>
          <div class="checkbox-group">
            <input type="checkbox" id="confirm-accuracy" required />
            <label for="confirm-accuracy">I confirm that all information provided is accurate and complete.</label>
          </div>

          <div class="checkbox-group">
            <input type="checkbox" id="confirm-documents" required />
            <label for="confirm-documents">I have uploaded all supporting documents.</label>
          </div>

          <div class="checkbox-group">
            <input type="checkbox" id="confirm-terms" required />
            <label for="confirm-terms">I agree to the terms and conditions.</label>
          </div>
        </div>

        <!-- Action Buttons -->
        <form method="POST">
  <div class="button-row">
    
    <button type="button" class="btn btn-back" onclick="goBack()">
      Back
    </button>

    <button type="submit" name="final_submit" class="btn btn-submit">
      Submit Application
    </button>

  </div>
</form>

        <div id="submission-status" class="submission-status"></div>
      </div>
    </section>
  </main>

  
</body>

</html>