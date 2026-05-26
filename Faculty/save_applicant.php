<?php

session_start();

include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$full_name = $_POST['full_name'];
$phone_number = $_POST['phone_number'];
$email = $_POST['email'];

$degree_course = $_POST['degree_course'];
$branch_specialization = $_POST['branch_specialization'];
$university_board = $_POST['university_board'];
$year_of_passing = $_POST['year_of_passing'];
$class_obtained = $_POST['class_obtained'];
$percentage = $_POST['percentage'];

$sql = "INSERT INTO applicant_details (

            user_id,
            full_name,
            phone_number,
            email,
            degree_course,
            branch_specialization,
            university_board,
            year_of_passing,
            class_obtained,
            percentage

        )

        VALUES (

            '$user_id',
            '$full_name',
            '$phone_number',
            '$email',
            '$degree_course',
            '$branch_specialization',
            '$university_board',
            '$year_of_passing',
            '$class_obtained',
            '$percentage'
        )";

if(mysqli_query($conn, $sql)){
    echo "Application Submitted Successfully";
}
else{
    echo "Error: " . mysqli_error($conn);
}

?>