<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
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
    Applicant Information Form
  </title>

  <link rel="preconnect"
        href="https://fonts.googleapis.com">

  <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

  <style>

    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
    }

    body{

      font-family:'Inter',sans-serif;

      background:#000;

      min-height:100vh;

      color:#000;

      padding:40px 20px;

    }

    .container{

      max-width:1100px;

      margin:auto;

    }

    .top-bar{

      display:flex;

      justify-content:space-between;

      align-items:center;

      margin-bottom:30px;

    }

    .logo-section{

      display:flex;

      align-items:center;

      gap:15px;

    }

    .logo{

      width:60px;
      height:60px;

      border-radius:14px;

      background:#fff;

      color:#000;

      display:flex;

      justify-content:center;

      align-items:center;

      font-weight:800;

      font-size:24px;

    }

    .college-info h1{

      font-size:28px;

      font-family:'Outfit',sans-serif;

      color:#fff;

      font-weight:700;

    }

    .college-info p{

      color:#cfcfcf;

      margin-top:4px;

      font-size:14px;

    }

    .logout-btn{

      background:#fff;

      color:#000;

      text-decoration:none;

      padding:12px 22px;

      border-radius:10px;

      font-weight:600;

      transition:0.3s;

      border:2px solid #fff;

    }

    .logout-btn:hover{

      background:#000;

      color:#fff;

    }

    .form-card{

      background:#fff;

      border-radius:24px;

      padding:45px;

      box-shadow:
      0 10px 40px rgba(255,255,255,0.08);

    }

    .form-header{

      margin-bottom:35px;

    }

    .form-header h2{

      font-size:34px;

      font-family:'Outfit',sans-serif;

      margin-bottom:10px;

      color:#000;

    }

    .form-header p{

      color:#555;

      line-height:1.7;

    }

    .section-title{

      font-size:22px;

      margin-top:35px;

      margin-bottom:25px;

      color:#000;

      font-family:'Outfit',sans-serif;

      border-left:5px solid #000;

      padding-left:14px;

    }

    .form-grid{

  display:flex;

  flex-direction:column;

  gap:24px;

}

    .input-group{

      display:flex;

      flex-direction:column;

    }

    .input-group label{

      margin-bottom:10px;

      font-weight:600;

      color:#000;

    }

    .input-group input{

      background:#fff;

      border:2px solid #d4d4d4;

      padding:16px;

      border-radius:14px;

      outline:none;

      color:#000;

      font-size:15px;

      transition:0.3s;

    }

    .input-group input:focus{

      border-color:#000;

      box-shadow:
      0 0 0 4px rgba(0,0,0,0.08);

    }

    .input-group input::placeholder{

      color:#777;

    }

    .submit-section{

      margin-top:45px;

      display:flex;

      justify-content:flex-end;

    }

    .submit-btn{

      background:#000;

      border:none;

      padding:16px 34px;

      color:#fff;

      border-radius:14px;

      font-size:16px;

      font-weight:600;

      cursor:pointer;

      transition:0.3s;

      border:2px solid #000;

    }

    .submit-btn:hover{

      background:#fff;

      color:#000;

    }

    @media(max-width:768px){

      .top-bar{

        flex-direction:column;

        gap:20px;

        align-items:flex-start;

      }

      .form-card{

        padding:25px;

      }

      .form-header h2{

        font-size:28px;

      }

    }

  </style>

</head>

<body>

<div class="container">

  <div class="top-bar">

    <div class="logo-section">

      <div class="logo">
        G
      </div>

      <div class="college-info">

        <h1>
          GSSSIETW Mysuru
        </h1>

        <p>
          Faculty Recruitment Application Portal
        </p>

      </div>

    </div>

    <a href="logout.php"
       class="logout-btn">

      Logout

    </a>

  </div>

  <div class="form-card">

    <div class="form-header">

      <h2>
        Applicant Information Form
      </h2>

      <p>

        Complete your personal and educational
        details carefully before proceeding
        to the next section.

      </p>

    </div>

    <form action="save_applicant.php"
          method="POST">

      <!-- PERSONAL INFO -->

      <div class="section-title">
        Personal Information
      </div>

      <div class="form-grid">

        <div class="input-group">

          <label>
            Full Name
          </label>

          <input type="text"
                 name="full_name"
                 placeholder="Enter Full Name"
                 required>

        </div>

        <div class="input-group">

          <label>
            Phone Number
          </label>

          <input type="text"
                 name="phone_number"
                 placeholder="Enter Phone Number"
                 required>

        </div>

        <div class="input-group">

          <label>
            Email Address
          </label>

          <input type="email"
                 name="applicant_email"
                 placeholder="Enter Email Address"
                 required>

        </div>

      </div>

      <!-- EDUCATIONAL INFO -->

      <div class="section-title">
        Educational Information
      </div>

      <div class="form-grid">

        <div class="input-group">

          <label>
            Degree Course
          </label>

          <input type="text"
                 name="degree_course"
                 placeholder="Enter Degree Course"
                 required>

        </div>

        <div class="input-group">

          <label>
            Branch / Specialization
          </label>

          <input type="text"
                 name="branch_specialization"
                 placeholder="Enter Branch"
                 required>

        </div>

        <div class="input-group">

          <label>
            University / Board
          </label>

          <input type="text"
                 name="university_board"
                 placeholder="Enter University / Board"
                 required>

        </div>

        <div class="input-group">

          <label>
            Year of Passing
          </label>

          <input type="number"
                 name="year_of_passing"
                 placeholder="Enter Passing Year"
                 required>

        </div>

        <div class="input-group">

          <label>
            Class Obtained
          </label>

          <input type="text"
                 name="class_obtained"
                 placeholder="First Class / Distinction"
                 required>

        </div>

        <div class="input-group">

          <label>
            Percentage / CGPA
          </label>

          <input type="number"
                 step="0.01"
                 name="percentage"
                 placeholder="Enter Percentage"
                 required>

        </div>
        <div class="input-group">

          <label>
            Role Applied For
          </label>

          <input type="text"
                 name="role_applied_for"
                 placeholder="Enter Role Applied For"
                 required>

        </div>

      </div>

      <div class="submit-section">

        <button type="submit"
                 placeholder="Enter Role Applied For"
                 required>

        </div>

      </div>

      <div class="submit-section">

        <button type="submit"
                class="submit-btn">

          Save & Next →

        </button>

      </div>

    </form>

  </div>

</div>

</body>
</html>