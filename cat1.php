<?php
session_start();
include("db.php");

$query = mysqli_query($conn, "SELECT * FROM parameters WHERE category_id = 1 ORDER BY parameter_id ASC");
$category1 = [];

while ($row = mysqli_fetch_assoc($query)) {
    $category1[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['cat1'] = $_POST;

    header("Location: cat2.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Category 1 - Candidate Details</title>
  <link rel="stylesheet" href="cat1.css" />
</head>

<body>
  <main class="page-shell">
    <section class="page-header">
      <h1>Category 1 - Candidate Details</h1>
      <p>Provide long descriptive responses for each field below.</p>
    </section>

    <section class="page-body">
      <form class="portal-form category-form" id="category1-form" method="POST">
        <?php foreach ($category1 as $field) { ?>
          <div class="field-row">
            <label>
              <?php echo htmlspecialchars($field['parameter_name'], ENT_QUOTES, 'UTF-8'); ?>
              <?php if (!empty($field['max_marks'])) { ?>
                - <?php echo (int) $field['max_marks']; ?> Marks
              <?php } ?>
            </label>

            <textarea name="current[<?php echo htmlspecialchars($field['parameter_name'], ENT_QUOTES, 'UTF-8'); ?>]"
                      rows="4"
                      required
                      placeholder="Enter details"><?php echo htmlspecialchars($_SESSION['cat1'][$field['parameter_name']]['current'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
          </div>
        <?php } ?>

        <div class="button-row">
          <button type="button" class="btn btn-edit">Edit</button>
          <button type="submit" class="btn btn-submit">Save and Next</button>
        </div>
      </form>
    </section>
  </main>
</body>

</html>
