<?php
session_start();

include("db.php");

if(!isset($_SESSION['email']))
{
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];

if(isset($_POST['submit']))
{
    $email = $_SESSION['email'];

    $applicant = [
        'full_name' => $_POST['full_name'],
        'phone_number' => $_POST['phone_number'],
        'email' => $email,
        'applicant_family' => $_POST['fam'],
        'permanent_address' => $_POST['permanent_address'],
        'present_address' => $_POST['present_address'],
        'age' => $_POST['age'],
        'dob' => $_POST['dob'],
        'nationality' => $_POST['nationality'],
        'religion' => $_POST['religion'],
        'sex' => $_POST['sex'],
        'caste' => $_POST['caste'],
        'marital_status' => $_POST['marital_status'],
        'degree_course' => $_POST['degree_course'],
        'branch_specialization' => $_POST['branch_specialization'],
        'university_board' => $_POST['university_board'],
        'year_of_passing' => $_POST['year_of_passing'],
        'class_obtained' => $_POST['class_obtained'],
        'percentage' => $_POST['percentage'],
        'role' => $_POST['role']
    ];

    if (isset($_SESSION['applicant_id'])) {
        $applicant_id = (int) $_SESSION['applicant_id'];
        $stmt = mysqli_prepare($conn, "UPDATE applicants
            SET full_name = ?,
                phone_number = ?,
                email = ?,
                fam = ?,
                `Permanent Address` = ?,
                `Present Address` = ?,
                Age = ?,
                DOB = ?,
                Nationality = ?,
                Religion = ?,
                Sex = ?,
                Caste = ?,
                `Marital Status` = ?,
                degree_course = ?,
                branch_specialization = ?,
                university_board = ?,
                year_of_passing = ?,
                class_obtained = ?,
                percentage = ?,
                role = ?
            WHERE applicant_id = ?");

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssisssssssssisdsi",
            $applicant['full_name'],
            $applicant['phone_number'],
            $applicant['email'],
            $applicant['applicant_family'],
            $applicant['permanent_address'],
            $applicant['present_address'],
            $applicant['age'],
            $applicant['dob'],
            $applicant['nationality'],
            $applicant['religion'],
            $applicant['sex'],
            $applicant['caste'],
            $applicant['marital_status'],
            $applicant['degree_course'],
            $applicant['branch_specialization'],
            $applicant['university_board'],
            $applicant['year_of_passing'],
            $applicant['class_obtained'],
            $applicant['percentage'],
            $applicant['role'],
            $applicant_id
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO applicants
            (
                full_name,
                phone_number,
                email,
                fam,
                `Permanent Address`,
                `Present Address`,
                Age,
                DOB,
                Nationality,
                Religion,
                Sex,
                Caste,
                `Marital Status`,
                degree_course,
                branch_specialization,
                university_board,
                year_of_passing,
                class_obtained,
                percentage,
                role
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssisssssssssisds",
            $applicant['full_name'],
            $applicant['phone_number'],
            $applicant['email'],
            $applicant['applicant_family'],
            $applicant['permanent_address'],
            $applicant['present_address'],
            $applicant['age'],
            $applicant['dob'],
            $applicant['nationality'],
            $applicant['religion'],
            $applicant['sex'],
            $applicant['caste'],
            $applicant['marital_status'],
            $applicant['degree_course'],
            $applicant['branch_specialization'],
            $applicant['university_board'],
            $applicant['year_of_passing'],
            $applicant['class_obtained'],
            $applicant['percentage'],
            $applicant['role']
        );
        mysqli_stmt_execute($stmt);
        $applicant_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        $_SESSION['applicant_id'] = $applicant_id;
    }

    $_SESSION['applicant'] = $applicant;

  echo "<script>
alert('Applicant information saved');
window.location.href='cat1.php';
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

   /* =========================
   REPLACE YOUR ENTIRE <style>
   WITH THIS STYLE
========================= */

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
}

body{

  font-family:'Inter',sans-serif;

  min-height:100vh;

  background:
  linear-gradient(135deg,#0f172a,#1e3a8a,#2563eb);

  padding:40px 20px;

  color:#fff;

}

/* MAIN CONTAINER */

.container{

  max-width:1200px;

  margin:auto;

}

/* TOP BAR */

.top-bar{

  display:flex;

  justify-content:space-between;

  align-items:center;

  margin-bottom:35px;

}

/* LOGO SECTION */

.logo-section{

  display:flex;

  align-items:center;

  gap:18px;

}

.logo{

  width:70px;

  height:70px;

  border-radius:18px;

  background:#2563eb;

  color:#fff;

  display:flex;

  justify-content:center;

  align-items:center;

  font-size:28px;

  font-weight:800;

  box-shadow:
  0 10px 25px rgba(37,99,235,0.4);

}

.college-info h1{

  font-size:30px;

  font-family:'Outfit',sans-serif;

  font-weight:700;

  color:#fff;

}

.college-info p{

  margin-top:6px;

  color:#dbeafe;

  font-size:14px;

}

/* LOGOUT BUTTON */

.logout-btn{

  text-decoration:none;

  padding:14px 24px;

  background:#fff;

  color:#0f172a;

  border-radius:14px;

  font-weight:600;

  transition:0.3s;

  border:2px solid transparent;

}

.logout-btn:hover{

  background:transparent;

  color:#fff;

  border:2px solid #fff;

}

/* FORM CARD */

.form-card{

  background:rgba(255,255,255,0.12);

  backdrop-filter:blur(18px);

  border:1px solid rgba(255,255,255,0.15);

  border-radius:28px;

  padding:50px;

  box-shadow:
  0 20px 60px rgba(0,0,0,0.25);

}

/* HEADER */

.form-header{

  margin-bottom:40px;

}

.form-header h2{

  font-size:40px;

  font-family:'Outfit',sans-serif;

  margin-bottom:12px;

  color:#fff;

}

.form-header p{

  color:#dbeafe;

  line-height:1.8;

  max-width:700px;

}

/* SECTION TITLE */

.section-title{

  font-size:24px;

  font-family:'Outfit',sans-serif;

  margin-top:40px;

  margin-bottom:25px;

  color:#fff;

  border-left:5px solid #60a5fa;

  padding-left:14px;

}

/* GRID */

.form-grid{

  display:grid;

  grid-template-columns:repeat(2,1fr);

  gap:24px;

}

/* INPUT GROUP */

.input-group{

  display:flex;

  flex-direction:column;

}

.input-group label{

  margin-bottom:10px;

  font-weight:600;

  color:#fff;

}

/* INPUTS */

.input-group input{

  padding:16px 18px;

  border-radius:16px;

  border:1px solid rgba(255,255,255,0.25);

  background:rgba(255,255,255,0.08);

  color:#fff;

  font-size:15px;

  outline:none;

  transition:0.3s;

}

/* PLACEHOLDER */

.input-group input::placeholder{

  color:#cbd5e1;

}

/* FOCUS */

.input-group input:focus{

  border-color:#60a5fa;

  background:rgba(255,255,255,0.12);

  box-shadow:
  0 0 0 4px rgba(96,165,250,0.2);

}

/* BUTTON SECTION */

.submit-section{

  margin-top:45px;

  display:flex;

  justify-content:flex-end;

}

/* SUBMIT BUTTON */

.submit-btn{

  padding:16px 36px;

  border:none;

  border-radius:16px;

  background:#2563eb;

  color:#fff;

  font-size:16px;

  font-weight:600;

  cursor:pointer;

  transition:0.3s;

  box-shadow:
  0 10px 25px rgba(37,99,235,0.35);

}

.submit-btn:hover{

  background:#1d4ed8;

  transform:translateY(-2px);

}

/* RESPONSIVE */

@media(max-width:900px){

  .form-grid{

    grid-template-columns:1fr;

  }

}

@media(max-width:768px){

  .top-bar{

    flex-direction:column;

    gap:20px;

    align-items:flex-start;

  }

  .form-card{

    padding:30px 22px;

  }

  .form-header h2{

    font-size:32px;

  }

  .college-info h1{

    font-size:24px;

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

    <form method="POST">

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
        Name and Occupation of Father/Spouse
      </label>

      <input type="text"
             name="fam"
             placeholder="Name and Occupation of Father/Spouse"
             required>

    </div>

     <div class="input-group">

      <label>
        Permanent Address 
      </label>

      <input type="text"
             name="permanent_address"
             placeholder="Permanent Address "
             required>

    </div>
     <div class="input-group">

      <label>
        Present Address
      </label>

      <input type="text"
             name="present_address"
             placeholder="Present Address"
             required>

    </div>
     <div class="input-group">

      <label>
        Age
      </label>

      <input type="text"
             name="age"
             placeholder="Age"
             required>

    </div>
     <div class="input-group">

      <label>
      Date of Birth
      </label>

      <input type="text"
             name="dob"
             placeholder="Date of Birth"
             required>

    </div>
     <div class="input-group">

      <label>
       Nationality
      </label>

      <input type="text"
             name="nationality"
             placeholder="Nationality"
             required>

    </div>
     <div class="input-group">

      <label>
       Religion
      </label>

      <input type="text"
             name="religion"
             placeholder="Religion"
             required>

    </div>
     <div class="input-group">

      <label>
        Sex
      </label>

      <input type="text"
             name="sex"
             placeholder="Sex"
             required>

    </div>
     <div class="input-group">

      <label>
       Caste & Sub-caste / Group
      </label>

      <input type="text"
             name="caste"
             placeholder="Caste & Sub-caste / Group"
             required>

    </div>
     <div class="input-group">

      <label>
       Marital Status
      </label>

      <input type="text"
             name="marital_status"
             placeholder="Marital Status"
             required>

    </div>











    

    <div class="input-group">

      <label>
        Email Address
      </label>

      <input type="email"
             name="email"
             value="<?php echo $email; ?>"
             readonly>

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
             name="role"
             placeholder="Enter Role Applied For"
             required>

    </div>

  </div>

  <div class="submit-section">

    <button type="submit"
            name="submit"
            class="submit-btn">

      Save & Next →

    </button>

  </div>

</form>

  </div>

</div>

</body>
</html>
