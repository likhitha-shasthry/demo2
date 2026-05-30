<?php

session_start();
include("db.php");

if(!isset($_SESSION['applicant_id']))
{
    header("Location: index.php");
    exit();
}

$applicant_id = $_SESSION['applicant_id'];

if(isset($_POST['submit']))
{
    $_SESSION['cat3'] = $_POST;

    echo "<script>
    window.location.href='final.php';
    </script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
Category 3
</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    font-family:'Inter',sans-serif;

    background:#f3f4f6;

    padding:40px;

}

.container{

    max-width:1100px;

    margin:auto;

    background:#fff;

    border-radius:24px;

    padding:40px;

    box-shadow:0 10px 30px rgba(0,0,0,0.08);

}

.title{

    font-size:34px;

    font-weight:700;

    margin-bottom:10px;

    color:#1e3a8a;

}

.subtitle{

    color:#6b7280;

    margin-bottom:40px;

}

.table-wrapper{

    width:100%;

}

.table-head{

    display:grid;

    grid-template-columns:2fr 1fr 1fr;

    gap:20px;

    font-weight:700;

    margin-bottom:20px;

    padding-bottom:10px;

    border-bottom:2px solid #e5e7eb;

    color:#1e3a8a;

}

.table-row{

    display:grid;

    grid-template-columns:2fr 1fr 1fr;

    gap:20px;

    align-items:center;

    margin-bottom:22px;

}

.col-param{

    font-size:17px;

    font-weight:500;

    color:#111827;

}

.col-year input{

    width:100%;

    padding:14px;

    border:1px solid #d1d5db;

    border-radius:12px;

    font-size:15px;

}

.submit-btn{

    margin-top:40px;

    padding:14px 26px;

    border:none;

    background:#2563eb;

    color:#fff;

    border-radius:14px;

    font-size:16px;

    cursor:pointer;

    font-weight:600;

}

.submit-btn:hover{

    background:#1d4ed8;

}

@media(max-width:768px){

    .table-head,
    .table-row{

        grid-template-columns:1fr;

    }

}

</style>

</head>

<body>

<div class="container">

<h1 class="title">
Category 3 - Candidate Details
</h1>

<p class="subtitle">
Enter current year and previous year values
</p>

<form method="POST">

<div class="table-wrapper">

    <div class="table-head">

        <div>
            Parameter
        </div>

        <div>
            Current Year
        </div>

        <div>
            Previous Year
        </div>

    </div>

<?php

$query = mysqli_query($conn,

"SELECT * FROM parameters
 WHERE category_id = 3
 ORDER BY parameter_id ASC");

while($row = mysqli_fetch_assoc($query))
{

?>

<div class="table-row">

    <div class="col-param">
        <?php echo $row['parameter_name']; ?>
    </div>

    <div class="col-year">

        <input type="number"

               name="current[<?php echo $row['parameter_id']; ?>]"

               placeholder="Current Year"

               min="0">

    </div>

    <div class="col-year">

        <input type="number"

               name="previous[<?php echo $row['parameter_id']; ?>]"

               placeholder="Previous Year"

               min="0">

    </div>

</div>

<?php
}
?>

</div>

<button type="submit"
        name="submit"
        class="submit-btn">

Next

</button>

</form>

</div>

</body>
</html>
