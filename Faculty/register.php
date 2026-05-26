<?php

include 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = "SELECT * FROM users
              WHERE email='$email'";

    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) > 0){

        echo "
        <script>
        alert('Email already registered');
        window.location='index.php';
        </script>
        ";

    }
    else{

        $sql = "INSERT INTO users(email, password)

                VALUES('$email', '$password')";

        if(mysqli_query($conn, $sql)){

            echo "
            <script>
            alert('Registration Successful');
            window.location='index.php';
            </script>
            ";

        }
        else{

            echo mysqli_error($conn);

        }

    }

}

?>