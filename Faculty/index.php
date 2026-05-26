
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Faculty Recruitment Portal for GSSS Institute of Engineering and Technology for Women (GSSSIETW), Mysuru. Register and sign in to apply.">
  <title>Faculty Recruitment Portal | GSSS Institute of Engineering &amp; Technology for Women, Mysuru</title>
  
  <!-- Google Fonts: Outfit (headings) and Inter (body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <!-- Hidden state controls for CSS-only logic -->
  <input type="radio" id="tab-login" name="portal-view" checked class="state-control">
  <input type="radio" id="tab-register" name="portal-view" class="state-control">
  <input type="radio" id="tab-forgot" name="portal-view" class="state-control">
  <input type="checkbox" id="theme-switch" class="state-control">

  <div class="portal-wrapper">
    
    <!-- Top Header bar containing Logo and Theme Switcher -->
    <header class="main-header">
      <div class="logo-container">
        <div class="logo-icon" style="flex-direction: column; gap: 1px;">
          <span style="font-size: 0.75rem; font-weight: 800; line-height: 1;">GSSS</span>
          <span style="font-size: 0.5rem; font-weight: 600; opacity: 0.9; line-height: 1; letter-spacing: 0.5px;">IETW</span>
        </div>
        <div class="logo-text">
          <h1>GSSSIETW, Mysuru</h1>
          <span class="tagline">Affiliated to VTU, Belagavi | Approved by AICTE | NAAC Accredited with 'A' Grade</span>
        </div>
      </div>
      
      <!-- Theme Switch Label acting as a toggle button -->
      <label for="theme-switch" class="theme-toggle-btn" aria-label="Toggle dark/light theme">
        <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
        <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
        <span class="toggle-slider"></span>
      </label>
    </header>

    <main class="main-content">
      
      <!-- Left Branding and Info Panel -->
      <section class="brand-panel">
        <!-- Technical overlay grid lines -->
        <div class="blueprint-overlay"></div>
        
        <div class="brand-info">
          <span class="announcement-badge">Faculty Recruitment 2026</span>
          <h2>Empowering Women through <span class="highlight">Quality</span> Technical Education</h2>
          <p class="brand-desc">
            Karnataka's first engineering college exclusively for women invites applications from exceptionally motivated and research-oriented candidates to join our esteemed faculty.
          </p>

          <!-- Core stats list -->
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-num">NAAC 'A'</div>
              <div class="stat-label">Accredited Institution</div>
            </div>
            <div class="stat-card">
              <div class="stat-num">Excellent</div>
              <div class="stat-label">ARIIA Ranking Band</div>
            </div>
            <div class="stat-card">
              <div class="stat-num">95%+</div>
              <div class="stat-label">Placement Record</div>
            </div>
          </div>

          <!-- Academic announcement ticker in CSS -->
          <div class="announcement-ticker">
            <div class="ticker-title">Updates:</div>
            <div class="ticker-content">
              <div class="ticker-scroll">
                <span>• Applications open for Professor, Assoc. Professor &amp; Asst. Professor roles</span>
                <span>• Multiple vacancies in CSE, ECE, ISE, EEE, AI&amp;ML, and AI&amp;DS</span>
                <span>• NBA-accredited courses under VTU affiliation</span>
                <span>• State-of-the-art research centers and funding support</span>
              </div>
            </div>
          </div>
        </div>

        <div class="floating-shapes">
          <div class="shape shape-1"></div>
          <div class="shape shape-2"></div>
        </div>
      </section><!-- LOGIN FORM -->
<form class="portal-form login-form"
      id="form-login"
      action="login.php"
      method="POST">

  <div class="form-intro">
    <h3>Welcome Back</h3>
    <p>
      Sign in to access your recruitment portal using your credentials.
    </p>
  </div>

  <div class="input-group">

    <input type="email"
           id="login-userid"
           name="email"
           required
           placeholder=" ">

    <label for="login-userid">
      User ID / Email Address
    </label>

    <div class="input-icon">
      <svg xmlns="http://www.w3.org/2000/svg"
           width="18"
           height="18"
           viewBox="0 0 24 24"
           fill="none"
           stroke="currentColor"
           stroke-width="2"
           stroke-linecap="round"
           stroke-linejoin="round">

        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>

      </svg>
    </div>

  </div>

  <div class="input-group">

    <input type="password"
           id="login-password"
           name="password"
           required
           placeholder=" ">

    <label for="login-password">
      Password
    </label>

    <div class="input-icon">
      <svg xmlns="http://www.w3.org/2000/svg"
           width="18"
           height="18"
           viewBox="0 0 24 24"
           fill="none"
           stroke="currentColor"
           stroke-width="2"
           stroke-linecap="round"
           stroke-linejoin="round">

        <rect width="18"
              height="11"
              x="3"
              y="11"
              rx="2"
              ry="2"/>

        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>

      </svg>
    </div>

  </div>

  <div class="form-options">

    <label class="checkbox-container">

      <input type="checkbox"
             id="remember-me">

      <span class="custom-checkbox"></span>

      Remember me

    </label>

    <label for="tab-forgot"
           class="forgot-link">

      Forgot Password?

    </label>

  </div>

  <button type="submit"
          class="btn btn-primary">

    <span>Sign In to Portal</span>

  </button>

  <div class="form-footer">

    <span>Don't have an account? </span>

    <label for="tab-register"
           class="link-label">

      Create one now

    </label>

  </div>

</form>

<!-- REGISTER FORM -->
<form class="portal-form register-form"
      id="form-register"
      action="register.php"
      method="POST">

  <div class="form-intro">

    <h3>Register Candidate</h3>

    <p>
      Create your recruitment account using email and password.
    </p>

  </div>

  <div class="input-group">

    <input type="email"
           id="reg-email"
           name="email"
           required
           placeholder=" ">

    <label for="reg-email">
      Email Address
    </label>

    <div class="input-icon">
      <svg xmlns="http://www.w3.org/2000/svg"
           width="18"
           height="18"
           viewBox="0 0 24 24"
           fill="none"
           stroke="currentColor"
           stroke-width="2"
           stroke-linecap="round"
           stroke-linejoin="round">

        <rect width="20"
              height="16"
              x="2"
              y="4"
              rx="2"/>

        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>

      </svg>
    </div>

  </div>

  <div class="input-group">

    <input type="password"
           id="reg-password"
           name="password"
           required
           placeholder=" ">

    <label for="reg-password">
      Create Password
    </label>

    <div class="input-icon">
      <svg xmlns="http://www.w3.org/2000/svg"
           width="18"
           height="18"
           viewBox="0 0 24 24"
           fill="none"
           stroke="currentColor"
           stroke-width="2"
           stroke-linecap="round"
           stroke-linejoin="round">

        <rect width="18"
              height="11"
              x="3"
              y="11"
              rx="2"
              ry="2"/>

        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>

      </svg>
    </div>

  </div>

  <div class="input-group">

    <input type="password"
           id="reg-confirm-password"
           required
           placeholder=" ">

    <label for="reg-confirm-password">
      Confirm Password
    </label>

    <div class="input-icon">
      <svg xmlns="http://www.w3.org/2000/svg"
           width="18"
           height="18"
           viewBox="0 0 24 24"
           fill="none"
           stroke="currentColor"
           stroke-width="2"
           stroke-linecap="round"
           stroke-linejoin="round">

        <path d="M20 6 9 17l-5-5"/>

      </svg>
    </div>

  </div>

  <button type="submit"
          class="btn btn-primary">

    <span>Register with Email</span>

  </button>

  <div class="form-footer">

    <span>Already registered? </span>

    <label for="tab-login"
           class="link-label">

      Sign In

    </label>

  </div>

</form>

<script>

document.addEventListener('DOMContentLoaded', () => {

  const formForgot =
      document.getElementById('form-forgot');

  function showToast(message, type = 'success') {

    const container =
        document.getElementById('toast-container');

    const toast =
        document.createElement('div');

    toast.className = `toast ${type}`;

    toast.innerHTML = `
      <div class="toast-message">${message}</div>
    `;

    container.appendChild(toast);

    setTimeout(() => {

      toast.classList.add('hide');

      setTimeout(() => {

        toast.remove();

      }, 300);

    }, 4000);

  }

  if(formForgot){

    formForgot.addEventListener('submit', (e) => {

      e.preventDefault();

      showToast(
        'Password reset instructions sent.'
      );

    });

  }

});

</script>