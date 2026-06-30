<?php

include("db.php");

if(!isset($_POST['selected_users']))
{
    exit("User ID Missing");
}


$id = intval($_POST['selected_users'][0]);

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM users
     WHERE id='$id'"
);

if(mysqli_num_rows($query) == 0)
{
    exit("User Not Found");
}

$user = mysqli_fetch_assoc($query);

$email = $user['email'];
$password = $user['password'];

$python =
'"C:\Users\LIKITHA\AppData\Local\Programs\Python\Python311\python.exe"';

$script =
'"C:\wamp64\www\demo2\send_mail.py"';

$command =
$python . " " .
$script . " " .
escapeshellarg($email) . " " .
escapeshellarg($password);

shell_exec($command);

echo "Email Sent Successfully";