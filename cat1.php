<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['cat1'] = [
        'student_centric' => $_POST['student_centric'],
        'ict_tools' => $_POST['ict_tools'],
        'best_practice' => $_POST['best_practice']
    ];

    header("Location: cat2.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Category 1 — Candidate Details</title>
  <link rel="stylesheet" href="cat1.css" />
</head>

<body>
  <main class="page-shell">
    <section class="page-header">
      <h1>Category 1 — Candidate Details</h1>
      <p>Provide long descriptive responses for each field below.</p>
    </section>

    <section class="page-body">
      <form class="portal-form category-form" id="category1-form" method="POST">
        <div class="input-group">
          <label for="c1-student-centric">Student Centric Learning - 10 Marks</label>
          <textarea name="student_centric" id="c1-student-centric" rows="4" required
            placeholder="Describe your student-centric learning strategies, examples, and outcomes..."></textarea>
        </div>

        <div class="input-group">
          <label for="c1-ict-tools">ICT Tools Usage - 10 Marks</label>
          <textarea name="ict_tools" id="c1-ict-tools" rows="4" required
            placeholder="Describe the ICT tools you use and how they support teaching, learning, or research..."></textarea>
        </div>

        <div class="input-group">
          <label for="c1-best-practice">Best Practice - 10 Marks</label>
          <textarea name="best_practice" id="c1-best-practice" rows="4" required
            placeholder="Describe a best practice you implemented, the process, and its impact..."></textarea>
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