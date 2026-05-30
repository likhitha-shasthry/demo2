<?php

include("db.php");

if(isset($_POST['add_parameter']))
{
    $category_id = $_POST['category_id'];
    $parameter_name = $_POST['parameter_name'];
    $max_marks = $_POST['max_marks'];
    $parameter_type = $_POST['parameter_type'];

    mysqli_query($conn,

    "INSERT INTO parameters
    (category_id, parameter_name, max_marks, parameter_type)

    VALUES
    ('$category_id','$parameter_name','$max_marks','$parameter_type')");

    header("Location: parameters.php");
}

if(isset($_GET['delete']))
{
    $id = $_GET['delete'];

    mysqli_query($conn,

    "DELETE FROM parameters
    WHERE parameter_id='$id'");

    header("Location: parameters.php");
}

$parameters = mysqli_query($conn,

"SELECT * FROM parameters
ORDER BY category_id");

?>

<!DOCTYPE html>

<html>

<head>

<title>
Manage Parameters
</title>

<link rel="stylesheet" href="admin.css">

<style>

body{
    background:#071c52;
    font-family:DM Sans;
    padding:40px;
    color:#fff;
}

.parameter-card{
    background:rgba(255,255,255,0.08);
    padding:30px;
    border-radius:24px;
    backdrop-filter:blur(12px);
}

.parameter-form{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:16px;
    margin-bottom:30px;
}

.parameter-form input,
.parameter-form select{

    padding:14px;
    border:none;
    border-radius:12px;
}

.add-btn{

    padding:14px;
    border:none;
    border-radius:12px;
    background:#2563eb;
    color:#fff;
    font-weight:600;
    cursor:pointer;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th,
table td{

    padding:16px;
    text-align:left;
}

.delete-btn{

    padding:8px 14px;
    background:#ef4444;
    color:#fff;
    border-radius:8px;
    text-decoration:none;
}

.back-btn{

    display:inline-block;
    margin-bottom:20px;
    color:#fff;
    text-decoration:none;
}

</style>

</head>

<body>

<a href="admin.php" class="back-btn">
Back to Dashboard
</a>

<div class="parameter-card">

<h1>
Manage Parameters
</h1>

<form method="POST" class="parameter-form">

<select name="category_id" required>

<option value="">
Category
</option>

<option value="1">
Category 1
</option>

<option value="2">
Category 2
</option>

<option value="3">
Category 3
</option>

</select>

<input type="text"
       name="parameter_name"
       placeholder="Parameter Name"
       required>

<input type="number"
       name="max_marks"
       placeholder="Max Marks"
       required>

<select name="parameter_type" required>

<option value="">
Type
</option>

<option value="text">
Text
</option>

<option value="number">
Number
</option>

</select>

<button type="submit"
        name="add_parameter"
        class="add-btn">

Add Parameter

</button>

</form>

<table>

<tr>

<th>
Category
</th>

<th>
Name
</th>

<th>
Marks
</th>

<th>
Type
</th>

<th>
Action
</th>

</tr>

<?php while($row = mysqli_fetch_assoc($parameters)) { ?>

<tr>

<td>
<?php echo $row['category_id']; ?>
</td>

<td>
<?php echo $row['parameter_name']; ?>
</td>

<td>
<?php echo $row['max_marks']; ?>
</td>

<td>
<?php echo $row['parameter_type']; ?>
</td>

<td>

<a href="parameters.php?delete=<?php echo $row['parameter_id']; ?>"
   class="delete-btn">

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>

</html>
