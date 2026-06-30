<?php
include("db.php");

$message = "";

if(isset($_POST['add_user']))
{
    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $password = mysqli_real_escape_string(
        $conn,
        $_POST['password']
    );

    $check = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE email='$email'"
    );

    if(mysqli_num_rows($check) > 0)
    {
        $message = "User already exists";
    }
    else
    {
        $insert = mysqli_query(
            $conn,
            "INSERT INTO users
            (email,password)
            VALUES
            ('$email','$password')"
        );

        if($insert)
        {
            $message = "User Added Successfully";
        }
        else
        {
            $message = "Failed to Add User";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Add User</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter',sans-serif;
}

body{

    background:
    linear-gradient(135deg,#0f172a,#1e3a8a,#2563eb);

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

}

.card{

    width:500px;

    background:rgba(255,255,255,0.1);

    backdrop-filter:blur(16px);

    padding:40px;

    border-radius:25px;

    color:white;

}

.card h2{

    margin-bottom:25px;

    font-size:32px;

}

.input-group{

    margin-bottom:20px;

}

.input-group label{

    display:block;

    margin-bottom:8px;

}

.input-group input{

    width:100%;

    padding:15px;

    border:none;

    border-radius:12px;

    font-size:15px;

}

.btn{

    width:100%;

    padding:15px;

    background:#2563eb;

    border:none;

    border-radius:12px;

    color:white;

    font-size:16px;

    font-weight:600;

    cursor:pointer;

}

.btn:hover{

    background:#1d4ed8;

}

.message{

    margin-bottom:20px;

    background:#ffffff20;

    padding:12px;

    border-radius:10px;

}

.back-btn{

    display:inline-block;

    margin-top:20px;

    color:white;

    text-decoration:none;

}

</style>

</head>

<body>

<div class="card">

    <h2>Add User</h2>

    <?php
    if($message != "")
    {
        echo "<div class='message'>$message</div>";
    }
    ?>

    <form method="POST">

        <div class="input-group">

            <label>Email</label>

            <input type="email"
                   name="email"
                   required>

        </div>

        <div class="input-group">

            <label>Password</label>

            <input type="text"
                   name="password"
                   required>

        </div>

        <button type="submit"
                name="add_user"
                class="btn">

            Add User

        </button>

    </form>

    <a href="admin.php"
       class="back-btn">

       ← Back to Dashboard

    </a>

</div>

</body>
</html>