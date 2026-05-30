<!-- =========================================
FILE NAME: admin_dashboard.php
========================================= -->

<?php

session_start();
include("db.php");

/* FETCH ALL APPLICANTS */

$query = mysqli_query($conn,
"SELECT * FROM applicants ORDER BY applicant_id DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
Admin Dashboard
</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap"
      rel="stylesheet">

<link rel="stylesheet"
      href="admin.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    font-family:'Inter',sans-serif;

    background:
    linear-gradient(135deg,#0f172a,#1e3a8a,#2563eb);

    min-height:100vh;

    padding:40px;

    color:#fff;

}

.container{

    max-width:1300px;

    margin:auto;

}

.top-bar{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:35px;

}

.title-section h1{

    font-size:40px;

    font-family:'Outfit',sans-serif;

    margin-bottom:8px;

}

.title-section p{

    color:#dbeafe;

}

.logout-btn{

    text-decoration:none;

    background:#fff;

    color:#0f172a;

    padding:14px 22px;

    border-radius:14px;

    font-weight:600;

    transition:0.3s;

}

.logout-btn:hover{

    background:#dbeafe;

}

.top-actions{

    display:flex;

    gap:14px;

    align-items:center;

}

.dashboard-card{

    background:rgba(255,255,255,0.1);

    backdrop-filter:blur(16px);

    border-radius:28px;

    padding:35px;

    box-shadow:
    0 20px 50px rgba(0,0,0,0.25);

}

.table-title{

    font-size:26px;

    margin-bottom:25px;

    font-family:'Outfit',sans-serif;

}

.table-wrapper{

    overflow-x:auto;

}

table{

    width:100%;

    border-collapse:collapse;

}

table th{

    background:rgba(255,255,255,0.15);

    padding:18px;

    text-align:left;

    font-size:15px;

    color:#fff;

}

table td{

    padding:18px;

    background:rgba(255,255,255,0.05);

    border-bottom:1px solid rgba(255,255,255,0.08);

    color:#fff;

}

.view-btn{

    display:inline-block;

    text-decoration:none;

    background:#2563eb;

    color:#fff;

    padding:10px 16px;

    border-radius:10px;

    font-size:14px;

    font-weight:600;

    white-space:nowrap;

    transition:0.3s;

}

.view-btn:hover{

    background:#1d4ed8;

}

.no-data{

    text-align:center;

    padding:40px;

    color:#dbeafe;

    font-size:18px;

}

.applicant-table{

    width:100%;

    table-layout:auto;

}

.applicant-table td{

    white-space:nowrap;

}

.applicant-table td:last-child{

    min-width:130px;

}

@media(max-width:768px){

    body{

        padding:20px;

    }

    .top-bar{

        flex-direction:column;

        gap:20px;

        align-items:flex-start;

    }

    .title-section h1{

        font-size:30px;

    }

}

</style>

</head>

<body>

<div class="container">

    <div class="top-bar">

        <div class="title-section">

            <h1>
                Admin Dashboard
            </h1>

            <p>
                View all faculty recruitment applicants
            </p>

        </div>

        <div class="top-actions">

            <a href="parameters.php"
               class="parameter-btn">

                Manage Parameters

            </a>

            <a href="index.php"
               class="logout-btn">

               Logout

            </a>

        </div>

    </div>

    <div class="dashboard-card">

        <div class="table-title">
            Applicant List
        </div>

        <div class="table-wrapper">

        <table class="applicant-table">

            <tr>

                <th>ID</th>

                <th>Full Name</th>

                <th>Email</th>

                <th>Phone</th>

                <th>Role Applied</th>

                <th>Action</th>

            </tr>

            <?php

            if(mysqli_num_rows($query) > 0)
            {
                while($row = mysqli_fetch_assoc($query))
                {
            ?>

            <tr>

                <td>
                    <?php echo $row['applicant_id']; ?>
                </td>

                <td>
                    <?php echo $row['full_name']; ?>
                </td>

                <td>
                    <?php echo $row['email']; ?>
                </td>

                <td>
                    <?php echo $row['phone_number']; ?>
                </td>

                <td>
                    <?php echo $row['role']; ?>
                </td>

                <td>

                    <a href="admin-review.php?id=<?php echo (int) $row['applicant_id']; ?>"
                       class="view-btn">

                       View Details

                    </a>

                </td>

            </tr>

            <?php
                }
            }
            else
            {
            ?>

            <tr>

                <td colspan="6"
                    class="no-data">

                    No Applicants Found

                </td>

            </tr>

            <?php
            }
            ?>

        </table>

        </div>

    </div>

</div>

</body>
</html>
