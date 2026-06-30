<?php
session_start();
include("db.php"); // Your database connection file

$message = "";

/* =========================
   REGISTER USER
========================= */
if(isset($_POST['register']))
{
    $email = mysqli_real_escape_string($conn, $_POST['reg_email']);
    $password = mysqli_real_escape_string($conn, $_POST['reg_password']);

    // Check if already exists
    $check = mysqli_query($conn,
        "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check) > 0)
    {
        $message = "Account already exists!";
    }
    else
    {
        $insert = mysqli_query($conn,
"INSERT INTO users(email,password)
 VALUES('$email','$password')");

        if($insert)
        {
            $message = "Account created successfully! Please sign in.";
        }
        else
        {
            $message = "Registration failed!";
        }
    }
}

/* =========================
   LOGIN USER
========================= */
if(isset($_POST['login']))
{
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Admin Login
    if($email == "admin@gssedu.in" && $password == "admin123")
    {
        $_SESSION['admin'] = true;

        header("Location: admin.php");
        exit();
    }

    $query = mysqli_query($conn,
        "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($query) > 0)
{
    $row = mysqli_fetch_assoc($query);

    if($password == $row['password'])
{
    unset($_SESSION['admin']);
    $_SESSION['user'] = $row['email'];
    $_SESSION['user_id'] = $row['id'];
     $_SESSION['email'] = $row['email'];

    header("Location: applicant.php");
    exit();
}
    else
    {
        $message = "Incorrect password!";
    }
}
else
{
    $message = "Account not found!";
}
   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Faculty Recruitment Portal</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;
    background:linear-gradient(135deg,#0f172a,#1e3a8a,#2563eb);
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
}

.container{
    width:1200px;
    max-width:95%;
    height:720px;
    background:rgba(255,255,255,0.08);
    border-radius:30px;
    overflow:hidden;
    backdrop-filter:blur(14px);
    display:flex;
    box-shadow:0 0 40px rgba(0,0,0,0.3);
}

/* LEFT PANEL */

.left-panel{
    width:55%;
    padding:60px;
    position:relative;
    color:white;
    background:
    linear-gradient(rgba(15,23,42,0.85),
    rgba(15,23,42,0.92)),
    url('https://images.unsplash.com/photo-1523050854058-8df90110c9f0?q=80&w=1200');

    background-size:cover;
    background-position:center;
}

.logo{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:60px;
}

.logo-box{
    width:65px;
    height:65px;
    border-radius:18px;
    background:#2563eb;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:22px;
    font-weight:700;
}

.logo h1{
    font-size:26px;
    line-height:1.3;
}

.tag{
    margin-top:6px;
    color:#cbd5e1;
    font-size:13px;
}

.badge{
    display:inline-block;
    padding:10px 20px;
    background:rgba(255,255,255,0.15);
    border-radius:50px;
    margin-bottom:30px;
    font-size:14px;
    backdrop-filter:blur(10px);
}

.main-heading{
    font-size:50px;
    line-height:1.2;
    font-weight:700;
    margin-bottom:25px;
}

.highlight{
    color:#60a5fa;
}

.desc{
    font-size:17px;
    line-height:1.8;
    color:#dbeafe;
    margin-bottom:40px;
}

.stats{
    display:flex;
    gap:20px;
}

.card{
    flex:1;
    background:rgba(255,255,255,0.08);
    padding:25px;
    border-radius:20px;
    text-align:center;
}

.card h2{
    font-size:28px;
    color:#60a5fa;
}

.card p{
    margin-top:8px;
    font-size:14px;
    color:#dbeafe;
}

/* RIGHT PANEL */

.right-panel{
    width:45%;
    background:white;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:50px;
    position:relative;
}

.form-box{
    width:100%;
    max-width:400px;
}

.tabs{
    display:flex;
    margin-bottom:35px;
    background:#f1f5f9;
    border-radius:14px;
    padding:6px;
}

.tabs button{
    flex:1;
    border:none;
    background:none;
    padding:14px;
    font-size:15px;
    font-weight:600;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s;
}

.tabs button.active{
    background:#2563eb;
    color:white;
}

.form{
    display:none;
}

.form.active{
    display:block;
}

.form h2{
    font-size:32px;
    margin-bottom:10px;
    color:#0f172a;
}

.form p{
    color:#64748b;
    margin-bottom:30px;
}

.input-group{
    margin-bottom:22px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    font-weight:500;
    color:#334155;
}

.input-group input{
    width:100%;
    padding:16px;
    border:1px solid #cbd5e1;
    border-radius:14px;
    outline:none;
    font-size:15px;
    transition:0.3s;
}

.input-group input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,0.15);
}

.btn{
    width:100%;
    padding:16px;
    border:none;
    border-radius:14px;
    background:#2563eb;
    color:white;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.btn:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.message{
    background:#eff6ff;
    color:#1e3a8a;
    padding:14px;
    border-radius:12px;
    margin-bottom:20px;
    font-size:14px;
}

.footer-text{
    margin-top:20px;
    text-align:center;
    color:#64748b;
    font-size:14px;
}

.footer-text span{
    color:#2563eb;
    cursor:pointer;
    font-weight:600;
}

@media(max-width:950px){

.container{
    flex-direction:column;
    height:auto;
}

.left-panel,
.right-panel{
    width:100%;
}

.left-panel{
    padding:40px;
}

.main-heading{
    font-size:36px;
}

.stats{
    flex-direction:column;
}

}

</style>
</head>

<body>

<div class="container">

    <!-- LEFT SIDE -->

    <div class="left-panel">

        <div class="logo">
            <div class="logo-box">G</div>

            <div>
                <h1>GSSSIETW Mysuru</h1>
                <div class="tag">
                    Affiliated to VTU | NAAC 'A' Grade
                </div>
            </div>
        </div>

        <div class="badge">
            Faculty Recruitment 2026
        </div>

        <div class="main-heading">
            Empowering Women through
            <span class="highlight">Quality</span>
            Technical Education
        </div>

        <div class="desc">
            Karnataka's first engineering college exclusively for women invites applications from highly motivated faculty candidates.
        </div>

        <div class="stats">

            <div class="card">
                <h2>NAAC 'A'</h2>
                <p>Accredited Institution</p>
            </div>

            <div class="card">
                <h2>95%+</h2>
                <p>Placement Record</p>
            </div>

            <div class="card">
                <h2>Excellent</h2>
                <p>Research Environment</p>
            </div>

        </div>

    </div>

    <!-- RIGHT SIDE -->

    <div class="right-panel">

        <div class="form-box">

            <?php
            if($message != "")
            {
                echo "<div class='message'>$message</div>";
            }
            ?>

            <div class="tabs">
                <button id="loginBtn" class="active">
                    Sign In
                </button>

                <button id="registerBtn">
                    Create Account
                </button>
            </div>

            <!-- LOGIN -->

            <form method="POST"
                  class="form active"
                  id="loginForm">

                <h2>Welcome Back</h2>

                <p>
                    Sign in using your registered account.
                </p>

                <div class="input-group">
                    <label>Email Address</label>

                    <input type="email"
                           name="email"
                           required>
                </div>

                <div class="input-group">
                    <label>Password</label>

                    <input type="password"
                           name="password"
                           required>
                </div>

                <input type="submit"
       name="login"
       value="Login"
       class="btn">

                <div class="footer-text">
                    New applicant?
                    <span onclick="showRegister()">
                        Create account
                    </span>
                </div>

            </form>

            <!-- REGISTER -->

            <form method="POST"
                  class="form"
                  id="registerForm">

                <h2>Create Account</h2>

                <p>
                    Register before accessing the application portal.
                </p>

                <div class="input-group">
                    <label>Email Address</label>

                    <input type="email"
                           name="reg_email"
                           required>
                </div>

                <div class="input-group">
                    <label>Create Password</label>

                    <input type="password"
                           name="reg_password"
                           required>
                </div>

                <button type="submit"
                        name="register"
                        class="btn">

                    Create Account
                </button>

                <div class="footer-text">
                    Already registered?
                    <span onclick="showLogin()">
                        Sign In
                    </span>
                </div>

            </form>

        </div>

    </div>

</div>

<script>

const loginBtn =
document.getElementById("loginBtn");

const registerBtn =
document.getElementById("registerBtn");

const loginForm =
document.getElementById("loginForm");

const registerForm =
document.getElementById("registerForm");

function showLogin()
{
    loginForm.classList.add("active");
    registerForm.classList.remove("active");

    loginBtn.classList.add("active");
    registerBtn.classList.remove("active");
}

function showRegister()
{
    registerForm.classList.add("active");
    loginForm.classList.remove("active");

    registerBtn.classList.add("active");
    loginBtn.classList.remove("active");
}

loginBtn.addEventListener("click", showLogin);
registerBtn.addEventListener("click", showRegister);

</script>

</body>
</html>