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
    return (int) ($marks_data[$year] ?? 0);
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

// Fetch applicant
$applicant_stmt = mysqli_prepare($conn, "SELECT * FROM applicants WHERE applicant_id = ?");
mysqli_stmt_bind_param($applicant_stmt, "i", $id);
mysqli_stmt_execute($applicant_stmt);
$applicant_result = mysqli_stmt_get_result($applicant_stmt);
if (mysqli_num_rows($applicant_result) == 0) die("Candidate Not Found");
$applicant = mysqli_fetch_assoc($applicant_result);
mysqli_stmt_close($applicant_stmt);

// Fetch responses
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Faculty Sign Report – <?php echo escape_html($applicant['full_name']); ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"/>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Courier Prime', 'Courier New', monospace;
      background: #fff;
      color: #111;
      font-size: 13px;
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

    .cat-heading {
      font-weight: 700;
      letter-spacing: 0.05em;
      padding: 9px 0;
    }

    /* ── PERSONAL INFO TABLE ── */
    table.info-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
      margin-bottom: 0;
    }

    table.info-table col.c-key  { width: 35%; }
    table.info-table col.c-val  { width: 65%; }

    table.info-table td {
      border: 1px solid #111;
      padding: 6px 12px;
      font-family: 'Courier Prime', 'Courier New', monospace;
      font-size: 22px;
      vertical-align: middle;
    }

    table.info-table td.key {
      background: #efefef;
      font-weight: 700;
    }

    table.info-table td.val {
      font-weight: 400;
    }

    /* ── PARAMETER TABLES ── */
    table.param-table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }

    /* cat1: param + response */
    table.cat1 col.c-param  { width: 30%; }
    table.cat1 col.c-resp   { width: 70%; }

    /* cat2&3: param + curr + prev */
    table.cat23 col.c-param { width: 70%; }
    table.cat23 col.c-num   { width: 15%; }

    table.param-table th,
    table.param-table td {
      border: 1px solid #111;
      padding: 6px 12px;
      font-family: 'Courier Prime', 'Courier New', monospace;
      font-size: 22px;
      vertical-align: middle;
    }

    table.param-table th {
      font-weight: 700;
      background: #efefef;
      text-align: center;
      white-space: nowrap;
    }

    table.param-table th.left { text-align: left; }
    table.param-table td.num  { text-align: center; font-weight: 700; }

    table.param-table td.italic {
      font-style: italic;
      color: #333;
      font-size: 22px;
      white-space: normal;
      line-height: 1.4;
      word-break: break-word;
    }

    table.param-table tr:nth-child(even) td { background: #fafafa; }
    table.param-table tr.subtotal td {
      background: #f0f0f0;
      font-weight: 700;
    }

    /* ── SIGNATURE ── */
    .sig-section { padding: 10px 0 4px; }
    .sig-row { display: flex; align-items: baseline; gap: 8px; padding: 3px 0; }
    .sig-label { font-weight: 700; min-width: 200px; }
    .sig-line { flex: 1; border-bottom: 1px solid #111; }

    @media print {
      @page { size: A4 portrait; margin: 10mm 12mm; }
      body { background: white; font-size: 10px; line-height: 1.3; }
      .page { padding: 0; max-width: 100%; }
      .rule { font-size: 10px; }
      .report-title { font-size: 12px; padding: 4px 0; }
      .cat-heading { font-size: 10px; padding: 4px 0; }
      table.info-table td,
      table.param-table th,
      table.param-table td { font-size: 12px; padding: 2px 6px; }
      table.param-table td.italic { font-size: 12px; }
      .sig-label { font-size: 10px; min-width: 140px; }
      .sig-section { padding: 5px 0 2px; }
      br { display: none; }
    }
  </style>
</head>
<body>
<div class="page">

  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>
  <div class="report-title">FACULTY RECRUITMENT REPORT</div>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>


  <!-- ══ PERSONAL INFORMATION ══ -->
  <div class="cat-heading">PERSONAL INFORMATION</div>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>

  <table class="info-table">
    <colgroup> <col style="width:15%">
    <col style="width:35%">
    <col style="width:15%">
    <col style="width:35%"></colgroup>
   <tbody>
   <tr>
    <td class="key">Name</td>
    <td class="val">
        <?php echo escape_html($applicant['full_name']); ?>
    </td>
<td rowspan="5" colspan="2" style="vertical-align:top;padding:10px;">

    <div style="
        display:flex;
        align-items:flex-start;
        justify-content:flex-end;
        gap:15px;
    ">

        <img src="<?php echo escape_html($applicant['photo']); ?>"
             style="
                width:120px;
                height:150px;
                object-fit:cover;
                border:1px solid #000;
             ">

        <div style="
            font-size:12px;
            line-height:1.8;
            min-width:150px;
        ">
            <strong>DOB:</strong>
            <?php echo escape_html($applicant['DOB']); ?><br>

            <strong>Age:</strong>
            <?php echo escape_html($applicant['Age']); ?><br>

            <strong>Nationality:</strong>
            <?php echo escape_html($applicant['Nationality']); ?><br>

            <strong>Caste:</strong>
            <?php echo escape_html($applicant['Caste']); ?>
        </div>

    </div>

</td>
</tr>

<tr>
    <td class="key">Phone</td>
    <td class="val"><?php echo escape_html($applicant['phone_number'] ?? ''); ?></td>

   <td class="key">Email</td>
    <td class="val"><?php echo escape_html($applicant['email']); ?></td>
</tr>

<tr>
    <td class="key">Religion</td>
    <td class="val"><?php echo escape_html($applicant['Religion'] ?? ''); ?></td>

    <td class="key">Sex</td>
    <td class="val"><?php echo escape_html($applicant['Sex'] ?? ''); ?></td>
</tr>



<tr>
     <td class="key">Role</td>
    <td class="val"><?php echo escape_html($applicant['role'] ?? ''); ?></td>


    <td class="key">Marital Status</td>
    <td class="val"><?php echo escape_html($applicant['Marital Status'] ?? ''); ?></td>
</tr>

<tr>
   
    <td class="key">Present Address</td>
    <td class="val"><?php echo escape_html($applicant['Present Address'] ?? ''); ?></td>
</tr>

<tr>
    <td class="key">Permanent Address</td>
    <td class="val"><?php echo escape_html($applicant['Permanent Address'] ?? ''); ?></td>
</tr>
    <td class="key"></td>
    <td class="val"></td>

</tbody>
  </table>

  <!-- ══ CATEGORY 1 ══ -->
  <br>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>
  <div class="cat-heading">CATEGORY 1 : <?php echo category_title(1); ?></div>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>

  <table class="param-table cat1">
    <colgroup><col class="c-param"><col class="c-resp"></colgroup>
    <thead>
      <tr>
        <th class="left">Parameter</th>
        <th class="left">Response (Submitted)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($responses_by_category[1] as $response):
        $val = response_value($response, 'current');
        $display = ($val === '' || $val === null) ? '—' : escape_html($val);
      ?>
      <tr>
        <td><?php echo escape_html($response['category_name']); ?></td>
        <td class="italic"><?php echo $display; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- ══ CATEGORY 2 ══ -->
  <br>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>
  <div class="cat-heading">CATEGORY 2 : <?php echo category_title(2); ?></div>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>

  <table class="param-table cat23">
    <colgroup><col class="c-param"><col class="c-num"><col class="c-num"></colgroup>
    <thead>
      <tr>
        <th class="left">Parameter</th>
        <th>Curr</th>
        <th>Prev</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($responses_by_category[2] as $response):
        $curr = response_value($response, 'current');
        $prev = response_value($response, 'previous');
      ?>
      <tr>
        <td><?php echo escape_html($response['category_name']); ?></td>
        <td class="num"><?php echo escape_html($curr !== '' ? $curr : '0'); ?></td>
        <td class="num"><?php echo escape_html($prev !== '' ? $prev : '0'); ?></td>
      </tr>
      <?php endforeach; ?>
    
    </tbody>
  </table>

  <!-- ══ CATEGORY 3 ══ -->
  <br>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>
  <div class="cat-heading">CATEGORY 3 : <?php echo category_title(3); ?></div>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>

  <table class="param-table cat23">
    <colgroup><col class="c-param"><col class="c-num"><col class="c-num"></colgroup>
    <thead>
      <tr>
        <th class="left">Parameter</th>
        <th>Curr</th>
        <th>Prev</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($responses_by_category[3] as $response):
        $curr = response_value($response, 'current');
        $prev = response_value($response, 'previous');
      ?>
      <tr>
        <td><?php echo escape_html($response['category_name']); ?></td>
        <td class="num"><?php echo escape_html($curr !== '' ? $curr : '0'); ?></td>
        <td class="num"><?php echo escape_html($prev !== '' ? $prev : '0'); ?></td>
      </tr>
      <?php endforeach; ?>
      
    </tbody>
  </table>

  <!-- ══ SIGNATURE ══ -->
  <br>
  <div class="rule">-------------------------------------------------------------------------------------------------------------------------</div>

  <div class="sig-section">
    <div class="sig-row">
      <span class="sig-label">Faculty Signature</span>
      <span>:</span>
      <span class="sig-line"></span>
    </div>
    <div class="sig-row" style="margin-top:14px;">
      <span class="sig-label">Date</span>
      <span>:</span>
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