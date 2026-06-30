<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db.php");

if (!isset($_GET['id'])) {
    die("No Applicant ID Found");
}

$id = (int) $_GET['id'];

function escape_html($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function category_title($category_id)
{
    if ((int) $category_id == 1) return "TEACHING, LEARNING & EVALUATION";
    if ((int) $category_id == 2) return "PROFESSIONAL DEVELOPMENT";
    return "RESEARCH & PUBLICATIONS";
}

function response_json_data($response, $column)
{
    $data = json_decode($response[$column] ?? '', true);
    return is_array($data) ? $data : [];
}

function response_value($response, $year)
{
    $value_data = response_json_data($response, 'value_json');
    return $value_data[$year] ?? '';
}

function response_saved_mark($response, $year)
{
    $marks_data = response_json_data($response, 'marks_json');
    return $marks_data[$year] ?? 0;
}

function response_year_marks($response, $year)
{
    if (!$response) return 0;
    if ((int) $response['category_id'] == 1) return response_saved_mark($response, $year);
    return ((int) response_value($response, $year)) * (int) $response['max_marks'];
}

function response_marks($response)
{
    if (!$response) return 0;
    if ((int) $response['category_id'] == 1) return response_saved_mark($response, 'admin');
    return response_year_marks($response, 'current');
}

function responsepp($response)
{
    if (!$response) return 0;
    if ((int) $response['category_id'] == 1) return response_saved_mark($response, 'admin');
    return response_year_marks($response, 'previous');
}

// Returns true if every parameter in this category has 0 for both current and previous
function category_all_zero($resps, $cat_id)
{
    foreach ($resps as $r) {
        if ((int) $cat_id == 1) {
            if ((int) response_saved_mark($r, 'admin') !== 0) return false;
        } else {
            if ((int) response_value($r, 'current') !== 0) return false;
            if ((int) response_value($r, 'previous') !== 0) return false;
        }
    }
    return true;
}

$applicant_stmt = mysqli_prepare($conn, "SELECT * FROM applicants WHERE applicant_id = ?");
mysqli_stmt_bind_param($applicant_stmt, "i", $id);
mysqli_stmt_execute($applicant_stmt);
$applicant_result = mysqli_stmt_get_result($applicant_stmt);
if (mysqli_num_rows($applicant_result) == 0) die("Candidate Not Found");
$applicant = mysqli_fetch_assoc($applicant_result);
mysqli_stmt_close($applicant_stmt);

$response_stmt = mysqli_prepare($conn, "SELECT
    ar.response_id, ar.applicant_id, ar.category_id, ar.category_name,
    p.max_marks, ar.value_json, ar.marks_json
    FROM applicants_response ar
    JOIN parameters p ON ar.category_name = p.parameter_name
    WHERE ar.applicant_id = ?
    ORDER BY ar.category_id, ar.response_id");
mysqli_stmt_bind_param($response_stmt, "i", $id);
mysqli_stmt_execute($response_stmt);
$response_result = mysqli_stmt_get_result($response_stmt);

$responses_by_category = [1 => [], 2 => [], 3 => []];
while ($response = mysqli_fetch_assoc($response_result)) {
    $responses_by_category[(int) $response['category_id']][] = $response;
}
mysqli_stmt_close($response_stmt);

$category_totals = [1 => 0, 2 => 0, 3 => 0];
$category_pp     = [1 => 0, 2 => 0, 3 => 0];
foreach ($responses_by_category as $cat_id => $resps) {
    foreach ($resps as $r) {
        $category_totals[$cat_id] += response_marks($r);
        $category_pp[$cat_id]     += responsepp($r);
    }
}
$total_score = $category_totals[1] + $category_totals[2] + $category_totals[3];
$total_pp    = $category_pp[1]     + $category_pp[2]     + $category_pp[3];

// Pre-compute which categories are all-zero
$cat_zero = [
    1 => category_all_zero($responses_by_category[1], 1),
    2 => category_all_zero($responses_by_category[2], 2),
    3 => category_all_zero($responses_by_category[3], 3),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Report – <?php echo escape_html($applicant['full_name']); ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"/>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Courier Prime', 'Courier New', monospace;
      background: #fff;
      color: #111;
      font-size: 15px;
      line-height: 1.5;
    }

    .page {
      max-width: 800px;
      margin: 0 auto;
      padding: 48px 40px 64px;
    }

    .rule {
      overflow: hidden;
      white-space: nowrap;
      line-height: 1;
      margin: 0;
    }

    .report-title {
      text-align: center;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 0.12em;
      padding: 10px 0;
    }

    .report-label {
      text-align: center;
      font-size: 11px;
      letter-spacing: 0.1em;
      padding-bottom: 2px;
      color: #555;
    }

    .meta-block { padding: 10px 0; }
    .meta-row { display: flex; padding: 1px 0; }
    .meta-key  { min-width: 130px; }
    .meta-sep  { margin-right: 8px; }
    .meta-val  { font-weight: 700; }

    .cat-heading {
      font-weight: 700;
      letter-spacing: 0.05em;
      padding: 9px 0;
    }

    /* zero-category notice */
    .zero-notice {
      padding: 8px 12px;
      font-style: italic;
      color: #555;
      border: 1px dashed #bbb;
      border-radius: 4px;
      margin: 6px 0 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .zero-notice .zero-score { font-weight: 700; font-style: normal; color: #111; }

    /* ── TABLES ── */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    table.cat1 { table-layout: fixed; }
    table.cat1 col.c-param   { width: 26%; }
    table.cat1 col.c-resp    { width: 44%; }
    table.cat1 col.c-num     { width: 15%; }

    table.cat23 { table-layout: fixed; }
    table.cat23 col.c-param  { width: 36%; }
    table.cat23 col.c-num    { width: 16%; }

    table th, table td {
      border: 1px solid #111;
      padding: 5px 9px;
      font-family: 'Courier Prime', 'Courier New', monospace;
      font-size: 18px;
      vertical-align: middle;
    }

    table th {
      font-weight: 700;
      background: #efefef;
      text-align: center;
      white-space: nowrap;
    }

    table th.left { text-align: left; }

    table td { text-align: left; }
    table td.num {
      text-align: center;
      white-space: nowrap;
      font-weight: 700;
    }

    table td.italic {
      font-style: italic;
      color: #333;
      font-size: 19px;
      white-space: normal;
      line-height: 1.4;
      word-break: break-word;
    }

    table tr:nth-child(even) td { background: #fafafa; }

    table tr.subtotal td {
      background: #f0f0f0;
      font-weight: 700;
    }

    /* ── TOTALS ── */
    .totals-block { padding: 8px 0 4px; }
    .total-row {
      display: flex;
      justify-content: space-between;
      padding: 2px 0;
    }
    .total-row.grand {
      font-weight: 700;
      font-size: 14px;
      border-top: 1px solid #111;
      margin-top: 4px;
      padding-top: 6px;
    }

    /* ── SIGNATURE ── */
    .sig-section { padding: 10px 0 4px; }
    .sig-row { display: flex; align-items: baseline; gap: 8px; padding: 3px 0; }
    .sig-label { font-weight: 700; min-width: 200px; }
    .sig-line { flex: 1; border-bottom: 1px solid #111; }

    @media print {
      @page { size: A4 landscape; margin: 10mm 12mm; }
      body { background: white; font-size: 15px; line-height: 1.4; }
      .page { padding: 0; max-width: 100%; }
      .rule { font-size: 11px; }
      .report-title { font-size: 14px; padding: 6px 0; letter-spacing: 0.08em; }
      .report-label { font-size: 10px; padding-bottom: 2px; }
      .meta-block { padding: 5px 0; }
      .meta-row   { padding: 1px 0; }
      .meta-key   { min-width: 110px; font-size: 15px; }
      .meta-val   { font-size: 11px; }
      .cat-heading { font-size: 15px; padding: 5px 0; letter-spacing: 0.03em; }
      table th, table td { font-size: 18px; padding: 4px 8px; }
      table td.italic { font-size: 18px; line-height: 1.3; }
      br { display: none; }
      .totals-block { padding: 4px 0; }
      .total-row    { padding: 2px 0; font-size: 11px; }
      .total-row.grand { font-size: 12px; padding-top: 4px; margin-top: 3px; }
      .sig-section  { padding: 6px 0 2px; }
      .sig-row      { padding: 3px 0; }
      .sig-label    { min-width: 160px; font-size: 11px; }
      .zero-notice  { font-size: 11px; padding: 5px 10px; }
    }
  </style>
</head>
<body>
<div class="page">

  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>
  <div class="report-title">FACULTY RECRUITMENT EVALUATION REPORT</div>
  <div class="report-label">ADMIN COPY — CONFIDENTIAL</div>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>

  <!-- ══ PERSONAL INFORMATION ══ -->
  <div class="cat-heading">PERSONAL INFORMATION</div>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>

  <table style="width:100%;border-collapse:collapse;table-layout:fixed;">
    <colgroup><col style="width:35%"><col style="width:65%"></colgroup>
    <tbody>
      <tr><td style="border:1px solid #111;padding:4px 10px;background:#efefef;font-weight:700;">Name</td><td style="border:1px solid #111;padding:4px 10px;"><?php echo escape_html($applicant['full_name']); ?></td></tr>
      <tr><td style="border:1px solid #111;padding:4px 10px;background:#efefef;font-weight:700;">Email</td><td style="border:1px solid #111;padding:4px 10px;"><?php echo escape_html($applicant['email']); ?></td></tr>
    </tbody>
  </table>

  <br>

  <!-- ══ CATEGORY 1 ══ -->
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>
  <div class="cat-heading">CATEGORY 1 : <?php echo category_title(1); ?></div>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>

  <table class="cat1">
    <colgroup>
      <col class="c-param">
      <col class="c-resp">
      <col class="c-num">
      <col class="c-num">
    </colgroup>
    <thead>
      <tr>
        <th class="left">Parameter</th>
        <th class="left">Response (Submitted)</th>
        <th>Max Marks</th>
        <th>Admin Marks</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($responses_by_category[1] as $response): ?>
      <tr>
        <td><?php echo escape_html($response['category_name']); ?></td>
        <td class="italic"><?php echo escape_html(response_value($response, 'current') ?: '—'); ?></td>
        <td class="num"><?php echo (int)$response['max_marks']; ?></td>
        <td class="num"><?php echo response_saved_mark($response, 'admin'); ?></td>
      </tr>
      <?php endforeach; ?>
      <tr class="subtotal">
        <td colspan="3" style="text-align:right;">Category 1 Total</td>
        <td class="num"><?php echo $category_totals[1]; ?></td>
      </tr>
    </tbody>
  </table>
  <!-- ══ CATEGORY 2 ══ -->
  <br>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>
  <div class="cat-heading">CATEGORY 2 : <?php echo category_title(2); ?></div>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>

  <?php if ($cat_zero[2]): ?>
    <div class="zero-notice">
      <span>All parameters are zero.</span>
      <span class="zero-score">Category 2 Score: 0</span>
    </div>
  <?php else: ?>
  <table class="cat23">
    <colgroup>
      <col class="c-param">
      <col class="c-num">
      <col class="c-num">
      <col class="c-num">
      <col class="c-num">
    </colgroup>
    <thead>
      <tr>
        <th class="left">Parameter</th>
        <th>Curr</th>
        <th>Prev</th>
        <th>Curr Marks</th>
        <th>Prev Marks</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($responses_by_category[2] as $response):
        $cv = response_value($response, 'current');
        $pv = response_value($response, 'previous');
        $cm = response_year_marks($response, 'current');
        $pm = response_year_marks($response, 'previous');
      ?>
      <tr>
        <td><?php echo escape_html($response['category_name']); ?></td>
        <td class="num"><?php echo $cv !== '' ? escape_html($cv) : '0'; ?></td>
        <td class="num"><?php echo $pv !== '' ? escape_html($pv) : '0'; ?></td>
        <td class="num"><?php echo $cm; ?></td>
        <td class="num"><?php echo $pm; ?></td>
      </tr>
      <?php endforeach; ?>
      <tr class="subtotal">
        <td colspan="3" style="text-align:right;">Category 2 Total</td>
        <td class="num"><?php echo $category_totals[2]; ?></td>
        <td class="num"><?php echo $category_pp[2]; ?></td>
      </tr>
    </tbody>
  </table>
  <?php endif; ?>

  <!-- ══ CATEGORY 3 ══ -->
  <br>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>
  <div class="cat-heading">CATEGORY 3 : <?php echo category_title(3); ?></div>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>

  <?php if ($cat_zero[3]): ?>
    <div class="zero-notice">
      <span>All parameters are zero.</span>
      <span class="zero-score">Category 3 Score: 0</span>
    </div>
  <?php else: ?>
  <table class="cat23">
    <colgroup>
      <col class="c-param">
      <col class="c-num">
      <col class="c-num">
      <col class="c-num">
      <col class="c-num">
    </colgroup>
    <thead>
      <tr>
        <th class="left">Parameter</th>
        <th>Curr</th>
        <th>Prev</th>
        <th>Curr Marks</th>
        <th>Prev Marks</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($responses_by_category[3] as $response):
        $cv = response_value($response, 'current');
        $pv = response_value($response, 'previous');
        $cm = response_year_marks($response, 'current');
        $pm = response_year_marks($response, 'previous');
      ?>
      <tr>
        <td><?php echo escape_html($response['category_name']); ?></td>
        <td class="num"><?php echo $cv !== '' ? escape_html($cv) : '0'; ?></td>
        <td class="num"><?php echo $pv !== '' ? escape_html($pv) : '0'; ?></td>
        <td class="num"><?php echo $cm; ?></td>
        <td class="num"><?php echo $pm; ?></td>
      </tr>
      <?php endforeach; ?>
      <tr class="subtotal">
        <td colspan="3" style="text-align:right;">Category 3 Total</td>
        <td class="num"><?php echo $category_totals[3]; ?></td>
        <td class="num"><?php echo $category_pp[3]; ?></td>
      </tr>
    </tbody>
  </table>
  <?php endif; ?>

  <!-- ══ SCORE SUMMARY ══ -->
  <br>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>
  <div class="cat-heading">SCORE SUMMARY</div>

    <div class="total-row grand">
      <span>GRAND TOTAL: Previous Academic Year (Last 1 Year)</span>
      <span><?php echo $total_score; ?></span>
    </div>
    <div class="total-row grand" style="border-top:none;margin-top:0;padding-top:2px;">
      <span>GRAND TOTAL: Overall Career Contribution</span>
      <span><?php echo $total_pp; ?></span>
    </div>
  </div>

  <div class="rule" style="margin-top:10px;">-------------------------------------------------------------------------------------------------------------------------</div>

  <div class="sig-section">
    <div class="sig-row">
      <span class="sig-label">Reviewing Authority</span>
      <span class="meta-sep">:</span>
      <span class="sig-line"></span>
    </div>
    <div class="sig-row" style="margin-top:14px;">
      <span class="sig-label">Date</span>
      <span class="meta-sep">:</span>
      <span class="sig-line"></span>
    </div>
  </div>

  <div class="rule" style="margin-top:10px;">-------------------------------------------------------------------------------------------------------------------------</div>

</div>
<script>
  window.onload = function() { window.print(); };
</script>
</body>
</html>