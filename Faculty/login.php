<?php

session_start();

include 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users
            WHERE email='$email'
            AND password='$password'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['user_id'];

        header("Location: applicant.php");

        exit();

    }
    else{

        echo "
        <script>
        alert('Invalid Email or Password');
        window.location='index.php';
        </script>
        ";

    }

}
?>