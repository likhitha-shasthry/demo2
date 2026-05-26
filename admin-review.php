<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db.php");

/* CHECK ID */

if(!isset($_GET['id']))
{
    die("No Applicant ID Found");
}

$id = $_GET['id'];

/* FETCH DATA */

$query = mysqli_query($conn,

"SELECT *
FROM applicants a
JOIN candidate_kpi c
ON a.applicant_id = c.applicant_id
WHERE a.applicant_id='$id'"

);

if(mysqli_num_rows($query) == 0)
{
    die("Candidate Not Found");
}

$row = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8" />

  <meta name="viewport"
        content="width=device-width, initial-scale=1.0"/>

  <title>
    Faculty Report
  </title>

  <link rel="stylesheet"
        href="admin-review.css" />

  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet"/>

</head>

<body>

<header class="top-bar">

  <div class="top-bar-inner">

    <div class="logo-block">

      <span class="logo-icon">
        ◈
      </span>

      <span class="logo-text">
        Faculty Report
      </span>

    </div>

    <button class="back-btn"
            onclick="history.back()">

      ← Back to Dashboard

    </button>

  </div>

</header>

<main class="page-wrap">

  <!-- HEADER -->

  <section class="candidate-hero">

    <div class="candidate-meta">

      <p class="candidate-id-label">
        CAND-<?php echo $row['applicant_id']; ?>
      </p>

      <h1 class="candidate-name">
        <?php echo $row['full_name']; ?>
      </h1>

      <p class="submitted-label">

        Email:
        <?php echo $row['email']; ?>

      </p>

    </div>

    <div class="score-badge-wrap">

      <div class="score-badge">

        <span class="score-num">
          100
        </span>

        <span class="score-label">
          / 100
        </span>

      </div>

      <p class="score-caption">
        Candidate Review
      </p>

    </div>

  </section>

  <!-- CATEGORY GRID -->

  <div class="categories-grid">

    <!-- CATEGORY 1 -->

    <section class="cat-card cat-1">

      <div class="cat-header">

        <div class="cat-num">
          01
        </div>

        <div>

          <h2 class="cat-title">
            Teaching, Learning & Evaluation
          </h2>

          <p class="cat-subtitle">
            Candidate Submitted Details
          </p>

        </div>

      </div>

      <div class="field-block">

        <label class="field-label">
          Student-Centric Methods
        </label>

        <p class="field-value">
          <?php echo $row['student_centric_learning']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          ICT Tools Used
        </label>

        <p class="field-value">
          <?php echo $row['ict_tools_usage']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Best Practice
        </label>

        <p class="field-value">
          <?php echo $row['best_practice_beyond_syllabus']; ?>
        </p>

      </div>

    </section>

    <!-- CATEGORY 2 -->

    <section class="cat-card cat-2">

      <div class="cat-header">

        <div class="cat-num">
          02
        </div>

        <div>

          <h2 class="cat-title">
            Professional Development
          </h2>

          <p class="cat-subtitle">
            Auto Calculated Data
          </p>

        </div>

      </div>

      <div class="field-block">

        <label class="field-label">
          FDP / Workshop
        </label>

        <p class="field-value">
          <?php echo $row['fdp_workshop']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          MOOC Courses
        </label>

        <p class="field-value">
          <?php echo $row['mooc_courses']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Portfolio / Event Coordination
        </label>

        <p class="field-value">
          <?php echo $row['portfolio_event_coordination']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Seminar / Webinar / Conference
        </label>

        <p class="field-value">
          <?php echo $row['seminar_webinar_conference']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          University Academic Work
        </label>

        <p class="field-value">
          <?php echo $row['university_academic_work']; ?>
        </p>

      </div>

    </section>

    <!-- CATEGORY 3 -->

    <section class="cat-card cat-3">

      <div class="cat-header">

        <div class="cat-num">
          03
        </div>

        <div>

          <h2 class="cat-title">
            Research & Publications
          </h2>

          <p class="cat-subtitle">
            Auto Calculated Data
          </p>

        </div>

      </div>

      <div class="field-block">

        <label class="field-label">
          Journal Publications
        </label>

        <p class="field-value">
          <?php echo $row['journal_publications']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Books / Book Chapters
        </label>

        <p class="field-value">
          <?php echo $row['books_book_chapters']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Sponsored Projects
        </label>

        <p class="field-value">
          <?php echo $row['sponsored_projects']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Consultancy Projects
        </label>

        <p class="field-value">
          <?php echo $row['consultancy_projects']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          PhD Guidance
        </label>

        <p class="field-value">
          <?php echo $row['phd_guidance']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          PhD Registration
        </label>

        <p class="field-value">
          <?php echo $row['phd_registration']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Student Project Guidance
        </label>

        <p class="field-value">
          <?php echo $row['student_project_guidance']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Product Development
        </label>

        <p class="field-value">
          <?php echo $row['product_development']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Application Development
        </label>

        <p class="field-value">
          <?php echo $row['application_development']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Patents
        </label>

        <p class="field-value">
          <?php echo $row['patents']; ?>
        </p>

      </div>

      <div class="field-block">

        <label class="field-label">
          Copyright
        </label>

        <p class="field-value">
          <?php echo $row['copyright_work']; ?>
        </p>

      </div>

    </section>

  </div>

</main>

</body>
</html>