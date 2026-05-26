<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['cat3'] = [
        'journal_publications' => $_POST['journal_publications'],
        'books_chapters' => $_POST['books_chapters'],
        'sponsored_projects' => $_POST['sponsored_projects'],
        'consultancy_projects' => $_POST['consultancy_projects'],
        'phd_guidance' => $_POST['phd_guidance'],
        'phd_registration' => $_POST['phd_registration'],
        'student_project_guidance' => $_POST['student_project_guidance'],
        'product_development' => $_POST['product_development'],
        'application_development' => $_POST['application_development'],
        'patents' => $_POST['patents'],
        'copyright' => $_POST['copyright']
    ];

    header("Location: final.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Category 3 — Candidate Details</title>
  <link rel="stylesheet" href="cat3.css" />
</head>

<body>
  <main class="page-shell">
    <section class="page-header">
      <h1>Category 3 — Candidate Details</h1>
      <p>Enter numeric values for each item below.</p>
    </section>

    <section class="page-body">
      <form class="portal-form category-form" id="category3-form" method="POST">
        <div class="input-group">
          <label for="c3-journal-publications">Journal Publications</label>
          <input id="c3-journal-publications" name="journal_publications"type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c3-books-chapters">Books / Book Chapters</label>
          <input id="c3-books-chapters" name="books_chapters"type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c3-sponsored-projects">Sponsored Projects</label>
          <input id="c3-sponsored-projects"name="sponsored_projects" type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c3-consultancy-projects">Consultancy Projects</label>
          <input id="c3-consultancy-projects"name="consultancy_projects" type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c3-phd-guidance">PhD Guidance</label>
          <input id="c3-phd-guidance" name="phd_guidance" type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c3-phd-registration">PhD Registration</label>
          <input id="c3-phd-registration" name="phd_registration"type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c3-student-project-guidance">Student Project Guidance</label>
          <input id="c3-student-project-guidance" name="student_project_guidance"type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c3-product-development">Product Development</label>
          <input id="c3-product-development" name="product_development"type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c3-application-development">Application Development</label>
          <input id="c3-application-development"name="application_development" type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c3-patents">Patents</label>
          <input id="c3-patents"name="patents"  type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="input-group">
          <label for="c3-copyright">Copyright</label>
          <input id="c3-copyright"name="copyright" type="number" min="0" step="1" required placeholder="0" />
        </div>

        <div class="upload-section">
          <h3>Upload Supporting Documents</h3>
          
          <div class="upload-group">
            <label for="c3-documents">Select Files</label>
            <input id="c3-documents" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
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