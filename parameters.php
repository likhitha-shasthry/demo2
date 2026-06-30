<?php

include("db.php");

if (isset($_POST['add_parameter'])) {
    $category_id     = (int) $_POST['category_id'];
    $parameter_name  = mysqli_real_escape_string($conn, $_POST['parameter_name']);
    $max_marks       = (int) $_POST['max_marks'];
    $parameter_type  = mysqli_real_escape_string($conn, $_POST['parameter_type']);

    mysqli_query($conn,
        "INSERT INTO parameters (category_id, parameter_name, max_marks, parameter_type)
         VALUES ('$category_id','$parameter_name','$max_marks','$parameter_type')"
    );

    if ($category_id == 0) {
        $column_name = preg_replace('/[^a-zA-Z0-9_]/', '_', $parameter_name);
        $column_name = trim($column_name, '_');

        if ($column_name !== '') {
            $column_check_name = mysqli_real_escape_string($conn, $column_name);
            $column_exists = mysqli_query($conn,
                "SELECT COUNT(*) AS column_count
                 FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = 'applicants'
                   AND COLUMN_NAME = '$column_check_name'"
            );
            $column_row = mysqli_fetch_assoc($column_exists);

            if ((int)($column_row['column_count'] ?? 0) === 0) {
                if ($parameter_type == 'number') {
                    $sql = "ALTER TABLE applicants ADD COLUMN `$column_name` INT NULL";
                } else {
                    $sql = "ALTER TABLE applicants ADD COLUMN `$column_name` VARCHAR(255) NULL";
                }

                mysqli_query($conn, $sql);
            }
        }
    }

    header("Location: parameters.php");
    exit;
}

if (isset($_GET['delete'])) {
    $del_id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM parameters WHERE parameter_id='$del_id'");
    header("Location: parameters.php");
    exit;
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

if ($filter === 'all') {
    $parameters = mysqli_query($conn, "SELECT * FROM parameters ORDER BY category_id, parameter_id");
} elseif ($filter === 'personal') {
    $parameters = mysqli_query($conn, "SELECT * FROM parameters WHERE category_id = 0 ORDER BY parameter_id");
} else {
    $fid = (int) $filter;
    $parameters = mysqli_query($conn, "SELECT * FROM parameters WHERE category_id = '$fid' ORDER BY parameter_id");
}

function category_label($id) {
    if ($id == 0) return "Personal Info";
    if ($id == 1) return "Category 1";
    if ($id == 2) return "Category 2";
    if ($id == 3) return "Category 3";
    return "Unknown";
}

function category_badge_class($id) {
    if ($id == 0) return "badge-personal";
    if ($id == 1) return "badge-cat1";
    if ($id == 2) return "badge-cat2";
    if ($id == 3) return "badge-cat3";
    return "";
}

$applicant_columns = [];
$applicant_columns_result = mysqli_query($conn,
    "SELECT COLUMN_NAME
     FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'applicants'"
);
while ($column = mysqli_fetch_assoc($applicant_columns_result)) {
    $applicant_columns[$column['COLUMN_NAME']] = true;
}

function parameter_column_name($parameter_name) {
    return trim(preg_replace('/[^a-zA-Z0-9_]/', '_', $parameter_name), '_');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Parameters</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #060d1f;
      --surface:   #0e1b35;
      --surface2:  #162040;
      --border:    rgba(255,255,255,0.08);
      --border2:   rgba(255,255,255,0.14);
      --text:      #e8edf5;
      --muted:     #7a8aaa;
      --blue:      #3b82f6;
      --blue-dim:  rgba(59,130,246,0.15);
      --red:       #ef4444;
      --red-dim:   rgba(239,68,68,0.12);
      --green:     #22c55e;
      --amber:     #f59e0b;
      --purple:    #a855f7;
      --cyan:      #06b6d4;
      --mono:      'DM Mono', monospace;
      --sans:      'DM Sans', sans-serif;
      --radius:    14px;
      --radius-sm: 8px;
    }

    body {
      font-family: var(--sans);
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      padding: 32px 40px 60px;
    }

    /* ── TOP BAR ── */
    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
    }

    .page-title {
      font-size: 22px;
      font-weight: 700;
      letter-spacing: -0.3px;
    }

    .page-title span {
      color: var(--blue);
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 9px 18px;
      background: var(--surface2);
      border: 1px solid var(--border2);
      border-radius: var(--radius-sm);
      color: var(--text);
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      transition: background 0.15s;
    }

    .back-btn:hover { background: #1e2d50; }

    /* ── ADD FORM CARD ── */
    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 28px 28px 24px;
      margin-bottom: 28px;
    }

    .card-title {
      font-size: 13px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--muted);
      margin-bottom: 18px;
    }

    .add-form {
      display: grid;
      grid-template-columns: 180px 1fr 140px 160px auto;
      gap: 12px;
      align-items: end;
    }

    .form-group { display: flex; flex-direction: column; gap: 6px; }

    .form-label {
      font-size: 11px;
      font-weight: 500;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.06em;
    }

    .form-control {
      padding: 11px 14px;
      background: var(--surface2);
      border: 1px solid var(--border2);
      border-radius: var(--radius-sm);
      color: var(--text);
      font-family: var(--sans);
      font-size: 13.5px;
      outline: none;
      transition: border-color 0.15s;
      width: 100%;
    }

    .form-control:focus { border-color: var(--blue); }
    .form-control option { background: #1a2540; }

    /* marks = 0 hint for personal info */
    #max_marks_wrap .hint {
      font-size: 10px;
      color: var(--muted);
      margin-top: 3px;
      display: none;
    }

    .add-btn {
      padding: 11px 22px;
      background: var(--blue);
      color: #fff;
      border: none;
      border-radius: var(--radius-sm);
      font-family: var(--sans);
      font-size: 13.5px;
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
      transition: background 0.15s;
      align-self: end;
    }

    .add-btn:hover { background: #2563eb; }

    /* ── FILTER TABS ── */
    .filter-row {
      display: flex;
      gap: 6px;
      margin-bottom: 16px;
      flex-wrap: wrap;
    }

    .filter-tab {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 16px;
      border-radius: 999px;
      font-size: 12.5px;
      font-weight: 500;
      text-decoration: none;
      border: 1px solid var(--border2);
      background: var(--surface);
      color: var(--muted);
      transition: all 0.15s;
    }

    .filter-tab:hover { color: var(--text); border-color: rgba(255,255,255,0.25); }

    .filter-tab.active {
      color: #fff;
      border-color: transparent;
    }

    .filter-tab.active.t-all      { background: #334155; color: #fff; }
    .filter-tab.active.t-cat1     { background: var(--blue); }
    .filter-tab.active.t-cat2     { background: var(--green); }
    .filter-tab.active.t-cat3     { background: var(--amber); color: #111; }
    .filter-tab.active.t-personal { background: var(--purple); }

    .tab-count {
      font-family: var(--mono);
      font-size: 10px;
      background: rgba(255,255,255,0.15);
      padding: 1px 6px;
      border-radius: 999px;
    }

    /* ── TABLE ── */
    .table-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead tr {
      background: var(--surface2);
      border-bottom: 1px solid var(--border2);
    }

    thead th {
      padding: 12px 18px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.07em;
      color: var(--muted);
      text-align: left;
    }

    tbody tr {
      border-bottom: 1px solid var(--border);
      transition: background 0.1s;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(255,255,255,0.03); }

    tbody td {
      padding: 13px 18px;
      font-size: 13.5px;
      vertical-align: middle;
    }

    .param-name { font-weight: 500; }

    .marks-val {
      font-family: var(--mono);
      font-size: 13px;
      color: var(--text);
    }

    .type-chip {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 500;
      font-family: var(--mono);
      background: rgba(255,255,255,0.07);
      color: var(--muted);
    }

    /* category badges */
    .cat-badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 600;
    }

    .badge-personal { background: rgba(168,85,247,0.2); color: #c084fc; }
    .badge-cat1     { background: rgba(59,130,246,0.2); color: #60a5fa; }
    .badge-cat2     { background: rgba(34,197,94,0.2);  color: #4ade80; }
    .badge-cat3     { background: rgba(245,158,11,0.2); color: #fbbf24; }

    .delete-btn {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 6px 13px;
      background: var(--red-dim);
      color: var(--red);
      border: 1px solid rgba(239,68,68,0.2);
      border-radius: var(--radius-sm);
      text-decoration: none;
      font-size: 12px;
      font-weight: 600;
      transition: background 0.15s;
    }

    .delete-btn:hover { background: rgba(239,68,68,0.22); }

    .empty-state {
      text-align: center;
      padding: 52px 20px;
      color: var(--muted);
      font-size: 14px;
    }

    .empty-state .icon { font-size: 32px; margin-bottom: 10px; }
  </style>
</head>
<body>

<div class="topbar">
  <div class="page-title">Manage <span>Parameters</span></div>
  <a href="admin.php" class="back-btn">← Back to Dashboard</a>
</div>

<!-- ── ADD FORM ── -->
<div class="card">
  <div class="card-title">Add New Parameter</div>
  <form method="POST" class="add-form">

    <div class="form-group">
      <label class="form-label">Category</label>
      <select name="category_id" id="category_select" class="form-control" required onchange="handleCategoryChange(this)">
        <option value="">Select</option>
        <option value="0">Personal Info</option>
        <option value="1">Category 1 — Teaching</option>
        <option value="2">Category 2 — Prof. Dev.</option>
        <option value="3">Category 3 — Research</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label">Parameter Name</label>
      <input type="text" name="parameter_name" class="form-control" placeholder="e.g. FDP Workshop" required/>
    </div>

    <div class="form-group" id="max_marks_wrap">
      <label class="form-label">Max Marks</label>
      <input type="number" name="max_marks" id="max_marks_input" class="form-control" placeholder="e.g. 20" min="0" required/>
      <span class="hint" id="marks_hint">Set 0 for personal info fields</span>
    </div>

    <div class="form-group">
      <label class="form-label">Input Type</label>
      <select name="parameter_type" class="form-control" required>
        <option value="">Select</option>
        <option value="text">Text</option>
        <option value="number">Number</option>
      </select>
    </div>

    <button type="submit" name="add_parameter" class="add-btn">+ Add</button>

  </form>
</div>

<!-- ── FILTER TABS ── -->
<?php
// Count per category for badges
$counts = ['all' => 0, '0' => 0, '1' => 0, '2' => 0, '3' => 0];
$count_result = mysqli_query($conn, "SELECT category_id, COUNT(*) as cnt FROM parameters GROUP BY category_id");
while ($cr = mysqli_fetch_assoc($count_result)) {
    $counts[(string)$cr['category_id']] = (int)$cr['cnt'];
    $counts['all'] += (int)$cr['cnt'];
}
?>

<div class="filter-row">
  <a href="parameters.php?filter=all"
     class="filter-tab t-all <?php echo $filter === 'all' ? 'active' : ''; ?>">
    All <span class="tab-count"><?php echo $counts['all']; ?></span>
  </a>
  <a href="parameters.php?filter=1"
     class="filter-tab t-cat1 <?php echo $filter === '1' ? 'active' : ''; ?>">
    Category 1 <span class="tab-count"><?php echo $counts['1']; ?></span>
  </a>
  <a href="parameters.php?filter=2"
     class="filter-tab t-cat2 <?php echo $filter === '2' ? 'active' : ''; ?>">
    Category 2 <span class="tab-count"><?php echo $counts['2']; ?></span>
  </a>
  <a href="parameters.php?filter=3"
     class="filter-tab t-cat3 <?php echo $filter === '3' ? 'active' : ''; ?>">
    Category 3 <span class="tab-count"><?php echo $counts['3']; ?></span>
  </a>
  <a href="parameters.php?filter=personal"
     class="filter-tab t-personal <?php echo $filter === 'personal' ? 'active' : ''; ?>">
    Personal Info <span class="tab-count"><?php echo $counts['0']; ?></span>
  </a>
</div>

<!-- ── TABLE ── -->
<div class="table-card">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Category</th>
        <th>Parameter Name</th>
        <th>Applicants Column</th>
        <th>Max Marks</th>
        <th>Type</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      $has_rows = false;
      while ($row = mysqli_fetch_assoc($parameters)):
        $has_rows = true;
      ?>
      <tr>
        <td style="color:var(--muted);font-family:var(--mono);font-size:12px;"><?php echo $i++; ?></td>
        <td>
          <span class="cat-badge <?php echo category_badge_class($row['category_id']); ?>">
            <?php echo category_label($row['category_id']); ?>
          </span>
        </td>
        <td class="param-name"><?php echo htmlspecialchars($row['parameter_name']); ?></td>
        <td class="marks-val">
          <?php if ((int)$row['category_id'] == 0):
            $applicant_column = parameter_column_name($row['parameter_name']);
            $column_exists = isset($applicant_columns[$applicant_column]);
          ?>
            <?php echo htmlspecialchars($applicant_column); ?>
            <span style="color:<?php echo $column_exists ? 'var(--green)' : 'var(--red)'; ?>;font-family:var(--sans);font-size:11px;margin-left:8px;">
              <?php echo $column_exists ? 'Exists' : 'Missing'; ?>
            </span>
          <?php else: ?>
            <span style="color:var(--muted)">—</span>
          <?php endif; ?>
        </td>
        <td class="marks-val">
          <?php echo $row['category_id'] == 0 ? '<span style="color:var(--muted)">—</span>' : htmlspecialchars($row['max_marks']); ?>
        </td>
        <td>
          <span class="type-chip"><?php echo htmlspecialchars($row['parameter_type']); ?></span>
        </td>
        <td>
          <a href="parameters.php?delete=<?php echo $row['parameter_id']; ?>&filter=<?php echo urlencode($filter); ?>"
             class="delete-btn"
             onclick="return confirm('Delete this parameter?')">
            ✕ Delete
          </a>
        </td>
      </tr>
      <?php endwhile; ?>

      <?php if (!$has_rows): ?>
      <tr>
        <td colspan="7">
          <div class="empty-state">
            <div class="icon">📋</div>
            No parameters found for this filter.
          </div>
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
function handleCategoryChange(sel) {
    const val = sel.value;
    const marksInput = document.getElementById('max_marks_input');
    const hint = document.getElementById('marks_hint');

    if (val === '0') {
        marksInput.value = '0';
        marksInput.setAttribute('readonly', true);
        marksInput.style.opacity = '0.4';
        hint.style.display = 'block';
    } else {
        marksInput.removeAttribute('readonly');
        marksInput.style.opacity = '1';
        hint.style.display = 'none';
        if (marksInput.value === '0') marksInput.value = '';
    }
}
</script>

</body>
</html>
