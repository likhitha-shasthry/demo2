<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db.php");

if (!isset($_GET['id'])) {
    die("No Applicant ID Found");
}

$id = (int) $_GET['id'];
$message = "";

function escape_html($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function category_title($category_id)
{
    if ((int) $category_id == 1) {
        return "Teaching, Learning & Evaluation";
    }

    if ((int) $category_id == 2) {
        return "Professional Development";
    }

    return "Research & Publications";
}

function response_marks($response)
{
    if (!$response) {
        return 0;
    }

    if ((int) $response['category_id'] == 1) {
        return response_saved_mark($response, 'admin');
    }

    return response_year_marks($response, 'current') ;
}
function responsepp($response)
{
    if (!$response) {
        return 0;
    }

    if ((int) $response['category_id'] == 1) {
        return response_saved_mark($response, 'admin');
    }

    return response_year_marks($response, 'previous');
}
function response_json_data($response, $column)
{
    $data = json_decode($response[$column] ?? '', true);
    return is_array($data) ? $data : [];
}

function response_value($response, $year)
{
    $value_data = response_json_data($response, 'value_json');
    return $value_data[$year] ?? 0;
}

function response_saved_mark($response, $year)
{
    $marks_data = response_json_data($response, 'marks_json');
    return (int) ($marks_data[$year] ?? 0);
}

function response_year_marks($response, $year)
{
    if (!$response) {
        return 0;
    }

    if ((int) $response['category_id'] == 1) {
        return response_saved_mark($response, $year);
    }

    return ((int) response_value($response, $year)) * (int) $response['max_marks'];
}

$applicant_stmt = mysqli_prepare($conn, "SELECT * FROM applicants WHERE applicant_id = ?");
mysqli_stmt_bind_param($applicant_stmt, "i", $id);
mysqli_stmt_execute($applicant_stmt);
$applicant_result = mysqli_stmt_get_result($applicant_stmt);

if (mysqli_num_rows($applicant_result) == 0) {
    die("Candidate Not Found");
}

$applicant = mysqli_fetch_assoc($applicant_result);
mysqli_stmt_close($applicant_stmt);

if(isset($_POST['save_scores']))
{

    $get_all = mysqli_query($conn,

    "SELECT ar.*,
            p.max_marks

     FROM applicants_response ar

     JOIN parameters p
     ON ar.category_name = p.parameter_name

     WHERE ar.applicant_id='$id'");

    while($row = mysqli_fetch_assoc($get_all))
    {

        $category_name = $row['category_name'];

        $category_id = (int)$row['category_id'];

        $max_marks = (int)$row['max_marks'];

        $value_json = json_decode($row['value_json'], true);

        $current = (int)($value_json['current'] ?? 0);

        $previous = (int)($value_json['previous'] ?? 0);

        /*
        CATEGORY 1
        ADMIN ENTERS MARKS
        */

        if($category_id == 1)
        {

            $admin_mark = (int)($_POST['admin_marks'][$category_name] ?? 0);

            if($admin_mark > $max_marks)
            {
                $admin_mark = $max_marks;
            }

            $marks_json = json_encode([

                "admin" => $admin_mark,

                "current" => 0,

                "previous" => 0

            ]);

        }

        /*
        CATEGORY 2 & 3
        AUTO CALCULATED
        */

        else
        {

            $marks_json = json_encode([

                "current" => $current * $max_marks,

                "previous" => $previous * $max_marks

            ]);

        }

        mysqli_query($conn,

        "UPDATE applicants_response

         SET marks_json='$marks_json'

         WHERE applicant_id='$id'
         AND category_name='$category_name'");

    }

    $message = "Scores saved successfully.";
}

$responses = [];
$responses_by_category = [
    1 => [],
    2 => [],
    3 => []
];

$response_stmt = mysqli_prepare($conn, "SELECT
    ar.response_id,
    ar.applicant_id,
    ar.category_id,
    ar.category_name,
    p.max_marks,
    ar.value_json,
    ar.marks_json
    FROM applicants_response ar
    JOIN parameters p
    ON ar.category_name = p.parameter_name
    WHERE ar.applicant_id = ?
    ORDER BY ar.category_id, ar.response_id");

mysqli_stmt_bind_param($response_stmt, "i", $id);
mysqli_stmt_execute($response_stmt);
$response_result = mysqli_stmt_get_result($response_stmt);

while ($response = mysqli_fetch_assoc($response_result)) {
    $responses[$response['category_name']] = $response;
    $responses_by_category[(int) $response['category_id']][] = $response;
}

mysqli_stmt_close($response_stmt);

$category_totals = [
    1 => 0,
    2 => 0,
    3 => 0
];
$category_pp= [
    1 => 0,
    2 => 0,
    3 => 0
];
foreach ($responses_by_category as $category_id => $responses) {
    foreach ($responses as $response) {
        $category_totals[$category_id] += response_marks($response);
    }
}

foreach ($responses_by_category as $category_id => $responses) {
    foreach ($responses as $response) {
        $category_pp[$category_id] += responsepp($response);
    }
}

$total_score = $category_totals[1] + $category_totals[2] + $category_totals[3];
$total_pp = $category_pp[1] + $category_pp[2] + $category_pp[3];

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

  <style>
  @media print {

      .top-bar,
      .back-btn,
      .submit-btn {
          display: none !important;
      }

      body {
          background: white !important;
      }

      .page-wrap {
          margin: 0;
          padding: 0;
      }
  }
  </style>

</head>

<body>

<header class="top-bar">

  <div class="top-bar-inner">

    <div class="logo-block">

      <span class="logo-icon">
        &#9679;
      </span>

      <span class="logo-text">
        Faculty Report
      </span>

    </div>

    <div style="display:flex;gap:10px;">

      <button class="back-btn"
              onclick="window.location.href='admin.php'">

        Back to Dashboard

      </button>

      <button class="back-btn"
              onclick="window.print()">

        Download / Print PDF

      </button>

    </div>

  </div>

</header>

<div id="pdfContent">
<main class="page-wrap">

  <?php if ($message != "") { ?>
    <div class="review-message">
      <?php echo escape_html($message); ?>
    </div>
  <?php } ?>

  <section class="candidate-hero">

    <div class="candidate-meta">

      <p class="candidate-id-label">
        CAND-<?php echo escape_html($applicant['applicant_id']); ?>
      </p>

      <h1 class="candidate-name">
        <?php echo escape_html($applicant['full_name']); ?>
      </h1>

      <p class="submitted-label">

        Email:
        <?php echo escape_html($applicant['email']); ?>

      </p>

    </div>

    <div class="score-badge-wrap">

      <div class="score-badge">

        <span class="score-num">
          <?php echo $total_score; ?>
        </span>

        <span class="score-label">
          marks
        </span>

      </div>

      <p class="score-caption">
        Candidate Review
      </p>

    </div>

  </section>

  <form method="POST">

  <div class="categories-grid">

    <?php for ($category_id = 1; $category_id <= 3; $category_id++) { ?>

    <section class="cat-card cat-<?php echo $category_id; ?>">

      <div class="cat-header">

        <div class="cat-num">
          <?php echo str_pad($category_id, 2, "0", STR_PAD_LEFT); ?>
        </div>

        <div>

          <h2 class="cat-title">
            <?php echo category_title($category_id); ?>
          </h2>

          <p class="cat-subtitle">
            <?php echo $category_id == 1 ? "Candidate Submitted Details" : "Auto Calculated Data"; ?>
          </p>

        </div>

      </div>

      <?php if ($category_id == 1) { ?>

        <?php foreach ($responses_by_category[$category_id] as $response) { ?>

          <div class="field-block">

            <label class="field-label">
              <?php echo escape_html($response['category_name']); ?>
            </label>

            <p class="field-value">
              <?php echo escape_html(response_value($response, 'current')); ?>
            </p>

            <?php if (response_value($response, 'previous') !== '') { ?>
              <p class="field-value">
                Previous: <?php echo escape_html(response_value($response, 'previous')); ?>
              </p>
            <?php } ?>

          </div>

          <div class="marks-row">

            <label class="marks-label">
              Admin Marks
            </label>

            <div class="marks-input-wrap">
              <input class="marks-input"
                     type="number"
                     name="admin_marks[<?php echo escape_html($response['category_name']); ?>]"
                     min="0"
                     max="<?php echo $response['max_marks']; ?>"
                     value="<?php echo response_saved_mark($response, 'admin'); ?>">
            </div>

          </div>

        <?php } ?>

      <?php } else { ?>

        <div class="metrics-list">

          <?php foreach ($responses_by_category[$category_id] as $response) { ?>

            <div class="metric-item">

              <span class="metric-name">
                <?php echo escape_html($response['category_name']); ?>
              </span>

              <div class="year-display">

                <div class="year-card">
                  <h4>Current Year</h4>
                  <p>
                    <?php echo escape_html(response_value($response, 'current')); ?>
                  </p>
                </div>

                <div class="year-card">
                  <h4>Previous Year</h4>
                  <p>
                    <?php echo escape_html(response_value($response, 'previous')); ?>
                  </p>
                </div>

              </div>

              <div class="metric-row">

                <span class="metric-pts">
                  Marks:
                  <strong><?php echo response_year_marks($response, 'current'); ?></strong>
                  +
                  <strong><?php echo response_year_marks($response, 'previous'); ?></strong>
                </span>

              </div>

            </div>

          <?php } ?>

        </div>

      <?php } ?>

      <div class="cat-subtotal-row">
        <span>Category <?php echo $category_id; ?> Total</span>
        <span class="cat-subtotal"><?php echo $category_totals[$category_id]; ?></span>
      </div>

    </section>

    <?php } ?>

  </div>

  <div class="summary-bar">

    <div class="summary-inner">

      <div class="summary-item">
        <span class="summary-label">Category 1</span>
        <span class="summary-val"><?php echo $category_totals[1]; ?></span>
      </div>

      <span class="summary-divider">+</span>

      <div class="summary-item">
        <span class="summary-label">Category 2</span>
        <span class="summary-val"><?php echo $category_totals[2]; ?></span>
      </div>

      <span class="summary-divider">+</span>

      <div class="summary-item">
        <span class="summary-label">Category 3</span>
        <span class="summary-val"><?php echo $category_totals[3]; ?></span>
      </div>

      <span class="summary-divider">=</span>

      <div class="summary-item summary-total">
        <span class="summary-label">Total</span>
        <span class="summary-val total-val"><?php echo $total_score; ?></span>
      </div>

    </div>

    <div class="summary-bar">
<br>
    <div class="summary-inner">

      <div class="summary-item">
        <span class="summary-label">Category 1</span>
        <span class="summary-val"><?php echo $category_pp[1]; ?></span>
      </div>

      <span class="summary-divider">+</span>

      <div class="summary-item">
        <span class="summary-label">Category 2</span>
        <span class="summary-val"><?php echo $category_pp[2]; ?></span>
      </div>

      <span class="summary-divider">+</span>

      <div class="summary-item">
        <span class="summary-label">Category 3</span>
        <span class="summary-val"><?php echo $category_pp[3]; ?></span>
      </div>

      <span class="summary-divider">=</span>

      <div class="summary-item summary-total">
        <span class="summary-label">Total</span>
        <span class="summary-val total-val"><?php echo $total_pp; ?></span>
      </div>

    </div>

    <button type="submit"
            name="save_scores"
            class="submit-btn">
      Save Scores
    </button>

    

  </div>

  </form>

</main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

</body>
</html>
