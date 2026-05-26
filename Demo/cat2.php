<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['cat2'] = [
        'fdp_workshop' => $_POST['fdp_workshop'],
        'mooc_courses' => $_POST['mooc_courses'],
        'portfolio_event' => $_POST['portfolio_event'],
        'seminar_conference' => $_POST['seminar_conference'],
        'university_academic' => $_POST['university_academic']
    ];

    header("Location: cat3.php"); // or next page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Category 2 — Candidate Details</title>
  <link rel="stylesheet" href="cat2.css" />
</head>

<body>
  <main class="page-shell">
    <section class="page-header">
      <h1>Category 2 — Candidate Details</h1>
      <p>Enter numeric values for each item below.</p>
    </section>

    <section class="page-body">
      <form class="portal-form category-form" id="category2-form" method="POST">
        <div class="input-group">
          <label for="c2-fdp-workshop">FDP / Workshop</label>
          <input id="c2-fdp-workshop"name="fdp_workshop" type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c2-mooc-courses">MOOC Courses</label>
          <input id="c2-mooc-courses" name="mooc_courses"type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c2-portfolio-event">Portfolio / Event Coordination</label>
          <input id="c2-portfolio-event"name="portfolio_event" type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c2-seminar-conference">Seminar / Webinar / Conferences</label>
          <input id="c2-seminar-conference"name="seminar_conference" type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c2-university-academic">University Academic Work</label>
          <input id="c2-university-academic"name="university_academic" type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="upload-section">
          <h3>Upload Supporting Documents</h3>
         
          
          <div class="upload-group">
            <label for="c2-documents">Select Files</label>
            <input id="c2-documents" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
            <button type="button" class="btn btn-upload" id="upload-to-drive">Upload</button>
            <div id="upload-status" class="upload-status"></div>
          </div>
        </div>

        <div class="button-row">
          <button type="button" class="btn btn-edit">Edit</button>
          <button type="submit" class="btn btn-submit">Save and Next</button>
        </div>
      </form>
    </section>
  </main>


</body>

</html>