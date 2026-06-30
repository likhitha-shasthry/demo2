<?php
session_start();
include("db.php");

if (!isset($_SESSION['applicant_id'])) {
    header("Location: index.php");
    exit();
}

$applicant_id = $_SESSION['applicant_id'];

if (isset($_POST['submit'])) {
    $_SESSION['cat3'] = $_POST;
    header("Location: final.php");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM parameters WHERE category_id = 3 ORDER BY parameter_id ASC");
$params = [];
while ($row = mysqli_fetch_assoc($query)) {
    $params[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Category 3</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: #f0f4fb;
      padding: 32px 20px;
      min-height: 100vh;
    }

    .container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      border-radius: 20px;
      padding: 36px 40px;
      box-shadow: 0 4px 24px rgba(30,58,138,0.09);
    }

    .title { font-size: 28px; font-weight: 700; color: #1e3a8a; margin-bottom: 4px; }
    .subtitle { color: #6b7280; font-size: 14px; margin-bottom: 32px; }

    /* Quick-set global bar */
    .global-bar {
      display: flex;
      align-items: center;
      gap: 10px;
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      border-radius: 12px;
      padding: 10px 16px;
      margin-bottom: 28px;
      flex-wrap: wrap;
    }
    .global-bar span { font-size: 13px; font-weight: 600; color: #1e3a8a; margin-right: 4px; }
    .global-bar .hint { font-size: 12px; color: #6b7280; margin-left: auto; }

    /* Table */
    .table-head {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr;
      gap: 12px;
      font-size: 13px;
      font-weight: 700;
      color: #1e3a8a;
      text-transform: uppercase;
      letter-spacing: .04em;
      padding-bottom: 10px;
      border-bottom: 2px solid #e5e7eb;
      margin-bottom: 6px;
    }

    .table-row {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr;
      gap: 12px;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px solid #f3f4f6;
    }
    .table-row:last-child { border-bottom: none; }

    .col-param { font-size: 15px; font-weight: 500; color: #111827; }

    /* Stepper widget */
    .stepper {
      display: flex;
      align-items: center;
      border: 1.5px solid #d1d5db;
      border-radius: 10px;
      overflow: hidden;
      background: #fff;
      transition: border-color .2s;
    }
    .stepper:focus-within { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }

    .stepper button {
      width: 34px;
      height: 42px;
      background: #f9fafb;
      border: none;
      font-size: 18px;
      cursor: pointer;
      color: #374151;
      flex-shrink: 0;
      transition: background .15s;
      line-height: 1;
      user-select: none;
    }
    .stepper button:hover { background: #e5e7eb; }
    .stepper button:active { background: #d1d5db; }

    .stepper input {
      flex: 1;
      min-width: 0;
      text-align: center;
      border: none;
      outline: none;
      font-size: 15px;
      font-family: inherit;
      font-weight: 600;
      color: #111827;
      background: transparent;
      padding: 0 2px;
      height: 42px;
      -moz-appearance: textfield;
    }
    .stepper input::-webkit-outer-spin-button,
    .stepper input::-webkit-inner-spin-button { -webkit-appearance: none; }

    /* Quick-set chips */
    .chip {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 5px 11px;
      border-radius: 999px;
      border: 1.5px solid #bfdbfe;
      background: #fff;
      color: #1d4ed8;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: background .15s, border-color .15s;
      user-select: none;
    }
    .chip:hover { background: #dbeafe; border-color: #93c5fd; }
    .chip:active { background: #bfdbfe; }

    /* Buttons */
    .btn-row { margin-top: 36px; display: flex; gap: 10px; }
    .btn {
      padding: 13px 28px;
      border: none;
      border-radius: 12px;
      font-size: 15px;
      cursor: pointer;
      font-weight: 600;
      transition: background .2s, transform .1s;
    }
    .btn:active { transform: scale(.97); }
    .btn-back { background: #e5e7eb; color: #374151; }
    .btn-back:hover { background: #d1d5db; }
    .btn-next { background: #2563eb; color: #fff; }
    .btn-next:hover { background: #1d4ed8; }

    @media(max-width: 600px) {
      .container { padding: 24px 16px; }
      .table-head, .table-row { grid-template-columns: 1fr; gap: 8px; }
      .table-head { display: none; }
      .col-param { font-weight: 600; font-size: 14px; margin-bottom: 4px; }
      .table-row { padding: 14px 0; }
    }
  </style>
</head>
<body>
<div class="container">

  <h1 class="title">Category 3 — Candidate Details</h1>
  <p class="subtitle">Enter current year and previous year values</p>

  

  <form method="POST" id="catForm">
    <div class="table-head">
      <div>Parameter</div>
      <div>Current Year</div>
      <div>Previous Year</div>
    </div>

    <?php foreach ($params as $i => $row):
      $pid = $row['parameter_id'];
      $saved_current  = $_SESSION['cat3']['current'][$pid]  ?? '';
      $saved_previous = $_SESSION['cat3']['previous'][$pid] ?? '';
    ?>
    <div class="table-row">

      <div class="col-param"><?php echo htmlspecialchars($row['parameter_name']); ?></div>

      <!-- Current Year stepper -->
      <div class="stepper">
        <button type="button" onclick="step('current[<?php echo $pid; ?>]', -1)">−</button>
        <input type="number"
               name="current[<?php echo $pid; ?>]"
               id="current_<?php echo $pid; ?>"
               min="0"
               value="<?php echo htmlspecialchars($saved_current); ?>"
               placeholder="0"
               onkeydown="handleKey(event)">
        <button type="button" onclick="step('current[<?php echo $pid; ?>]', 1)">+</button>
      </div>

      <!-- Previous Year stepper -->
      <div class="stepper">
        <button type="button" onclick="step('previous[<?php echo $pid; ?>]', -1)">−</button>
        <input type="number"
               name="previous[<?php echo $pid; ?>]"
               id="previous_<?php echo $pid; ?>"
               min="0"
               value="<?php echo htmlspecialchars($saved_previous); ?>"
               placeholder="0"
               onkeydown="handleKey(event)">
        <button type="button" onclick="step('previous[<?php echo $pid; ?>]', 1)">+</button>
      </div>

    </div>
    <?php endforeach; ?>

    <div class="btn-row">
      <button type="button" class="btn btn-back" onclick="window.location.href='cat2.php'">Back</button>
      <button type="submit" name="submit" class="btn btn-next">Submit →</button>
    </div>
  </form>

</div>

<script>
  const pids = [<?php echo implode(',', array_column($params, 'parameter_id')); ?>];

  function step(name, delta) {
    const input = document.querySelector(`input[name="${name}"]`);
    if (!input) return;
    const val = parseInt(input.value) || 0;
    input.value = Math.max(0, val + delta);
  }

  function setAll(val) {
    pids.forEach(pid => {
      const c = document.getElementById('current_' + pid);
      const p = document.getElementById('previous_' + pid);
      if (c) c.value = val;
      if (p) p.value = val;
    });
  }

  function handleKey(e) {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    const allInputs = Array.from(document.querySelectorAll('input[type="number"]'));
    const idx = allInputs.indexOf(e.target);
    if (idx < allInputs.length - 1) allInputs[idx + 1].focus();
  }
</script>
</body>
</html>