<?php
session_start();
include("db.php");

if (!isset($_SESSION['applicant']) || !isset($_SESSION['cat1']) || !isset($_SESSION['cat2']) || !isset($_SESSION['cat3'])) {
    header("Location: applicant.php");
    exit();
}

function display_value($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function year_value($values, $year)
{
    if (is_array($values)) {
        return $values[$year] ?? 0;
    }

    return $year == 'current' ? $values : 0;
}

function display_year_values($values)
{
    return 'Current: ' . display_value(year_value($values, 'current')) . ' | Previous: ' . display_value(year_value($values, 'previous'));
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

function category_responses($conn, $category_id, $session_data)
{
    $responses = [];
    $current_values = $session_data['current'] ?? [];
    $previous_values = $session_data['previous'] ?? [];
    $query = mysqli_prepare($conn, "SELECT parameter_id, parameter_name FROM parameters WHERE category_id = ? ORDER BY parameter_id ASC");
    mysqli_stmt_bind_param($query, "i", $category_id);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);

    while ($parameter = mysqli_fetch_assoc($result)) {
        $parameter_id = $parameter['parameter_id'];
        $parameter_name = $parameter['parameter_name'];
        $responses[$parameter_name] = [
            'category_id' => $category_id,
            'current' => $current_values[$parameter_id] ?? $current_values[$parameter_name] ?? '',
            'previous' => $previous_values[$parameter_id] ?? $previous_values[$parameter_name] ?? ''
        ];
    }

    mysqli_stmt_close($query);
    return $responses;
}

function insert_response($conn, $applicant_id, $category_id, $category_name, $current_value, $previous_value = 0)
{
    $value_json = json_encode([
        "current" => is_numeric($current_value) ? (int) $current_value : $current_value,
        "previous" => is_numeric($previous_value) ? (int) $previous_value : $previous_value
    ]);
    $marks_json = json_encode([
        "current" => 0,
        "previous" => 0
    ]);

    $stmt = mysqli_prepare($conn, "INSERT INTO applicants_response (applicant_id, category_id, category_name, value_json, marks_json) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iisss", $applicant_id, $category_id, $category_name, $value_json, $marks_json);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if (isset($_POST['final_submit'])) {

    $applicant = $_SESSION['applicant'];
    $responses_by_category = [
        1 => category_responses($conn, 1, $_SESSION['cat1']),
        2 => category_responses($conn, 2, $_SESSION['cat2']),
        3 => category_responses($conn, 3, $_SESSION['cat3'])
    ];

    mysqli_begin_transaction($conn);

    try {
        if (isset($_SESSION['applicant_id'])) {
            $applicant_id = (int) $_SESSION['applicant_id'];
        } else {
            $applicant_stmt = mysqli_prepare($conn, "INSERT INTO applicants
                (full_name, phone_number, email, degree_course, branch_specialization, university_board, year_of_passing, class_obtained, percentage, role)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            mysqli_stmt_bind_param(
                $applicant_stmt,
                "ssssssisds",
                $applicant['full_name'],
                $applicant['phone_number'],
                $applicant['email'],
                $applicant['degree_course'],
                $applicant['branch_specialization'],
                $applicant['university_board'],
                $applicant['year_of_passing'],
                $applicant['class_obtained'],
                $applicant['percentage'],
                $applicant['role']
            );
            mysqli_stmt_execute($applicant_stmt);
            mysqli_stmt_close($applicant_stmt);

            $applicant_id = mysqli_insert_id($conn);
            $_SESSION['applicant_id'] = $applicant_id;
        }

        foreach ($responses_by_category as $category_id => $responses) {
            foreach ($responses as $parameter_name => $values) {
                insert_response(
                    $conn,
                    $applicant_id,
                    $values['category_id'] ?? $category_id,
                    $parameter_name,
                    year_value($values, 'current'),
                    year_value($values, 'previous')
                );
            }
        }

        mysqli_commit($conn);
        session_destroy();

        echo "<script>
alert('Application Submitted Successfully!');
window.location.href='index.php';
</script>";
        exit();
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        die("Submission failed: " . $e->getMessage());
    }
}

$applicant = $_SESSION['applicant'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Final Submission - Application Review</title>
  <link rel="stylesheet" href="final.css" />
</head>

<body>
  <div id="pdfContent">
  <main class="page-shell">
    <section class="page-header">
      <h1>Application Review & Submission</h1>
      <p>Review all your information before final submission.</p>
    </section>

    <section class="page-body">
      <div class="submission-container">

        <?php foreach ([1 => category_responses($conn, 1, $_SESSION['cat1']), 2 => category_responses($conn, 2, $_SESSION['cat2']), 3 => category_responses($conn, 3, $_SESSION['cat3'])] as $category_id => $responses) { ?>
          <div class="category-content">
            <h3><?php echo category_title($category_id); ?></h3>

            <?php foreach ($responses as $parameter_name => $values) { ?>
              <div class="summary-item">
                <label><?php echo display_value($parameter_name); ?></label>
                <p class="display-text">
                  <?php echo $category_id == 1 ? display_value(year_value($values, 'current')) : display_year_values($values); ?>
                </p>
              </div>
            <?php } ?>
          </div>
        <?php } ?>

        <div class="download-section">
          <h3>Download Application Summary</h3>
         <button type="button"
        class="btn btn-download"
        onclick="downloadPDF()">
    Download as PDF
</button>
        </div>

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
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
function downloadPDF()
{
    const element = document.getElementById('pdfContent');

    html2pdf()
    .set({
        margin: 10,
        filename: '<?php echo preg_replace("/[^A-Za-z0-9_-]/", "_", $applicant["full_name"]); ?>.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 2 },
        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait'
        }
    })
    .from(element)
    .save();
}
</script>
</body>

</html>
