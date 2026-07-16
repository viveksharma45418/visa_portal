<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — Visa Vista Global</title>
  <meta name="robots" content="noindex, nofollow">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary:       #0D1B3E;
      --primary-light: #1A2F5E;
      --primary-dark:  #070E20;
      --gold:          #D4AF37;
      --gold-light:    #E8CC6A;
      --gold-dark:     #A8891A;
      --danger:        #EF4444;
      --success:       #10B981;
      --border:        rgba(255,255,255,0.1);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      background: var(--primary-dark);
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    /* Animated background */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse at 20% 50%, rgba(212,175,55,0.06) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(26,47,94,0.8)  0%, transparent 60%),
        linear-gradient(135deg, #070E20 0%, #0D1B3E 50%, #1A2F5E 100%);
      z-index: 0;
    }

    /* Floating orbs */
    .orb {
      position: fixed;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.15;
      animation: float 8s ease-in-out infinite;
      pointer-events: none;
      z-index: 0;
    }
    .orb-1 { width: 400px; height: 400px; background: var(--gold);   top: -100px; right: -100px; animation-delay: 0s; }
    .orb-2 { width: 300px; height: 300px; background: #3B82F6;       bottom: -80px; left: -80px;  animation-delay: 3s; }
    .orb-3 { width: 200px; height: 200px; background: var(--primary-light); top: 40%; left: 40%; animation-delay: 1.5s; }

    @keyframes float {
      0%,100% { transform: translate(0,0) scale(1); }
      50%      { transform: translate(20px,-20px) scale(1.05); }
    }

    /* Grid overlay */
    body::after {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
      background-size: 40px 40px;
      z-index: 0;
    }

    /* Login wrapper */
    .login-wrapper {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 440px;
      padding: 20px;
      animation: slideUp 0.5s ease both;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* Logo section */
    .login-logo {
      text-align: center;
      margin-bottom: 32px;
    }

    .logo-circle {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, var(--gold), var(--gold-dark));
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      color: var(--primary-dark);
      margin-bottom: 16px;
      box-shadow: 0 8px 32px rgba(212,175,55,0.4);
    }

    .brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      font-weight: 700;
      color: #FFFFFF;
      display: block;
    }

    .brand-tagline {
      font-size: 12px;
      color: rgba(255,255,255,0.45);
      letter-spacing: 2px;
      text-transform: uppercase;
      display: block;
      margin-top: 4px;
    }

    /* Card */
    .login-card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 20px;
      padding: 40px 36px;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      box-shadow:
        0 24px 60px rgba(0,0,0,0.4),
        inset 0 1px 0 rgba(255,255,255,0.08);
      position: relative;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--gold-dark), var(--gold), var(--gold-dark));
      border-radius: 20px 20px 0 0;
    }

    .card-title {
      font-size: 22px;
      font-weight: 800;
      color: #FFFFFF;
      margin-bottom: 4px;
      text-align: center;
    }

    .card-subtitle {
      font-size: 13px;
      color: rgba(255,255,255,0.45);
      text-align: center;
      margin-bottom: 28px;
    }

    /* Divider */
    .section-divider {
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
      margin: 20px 0;
    }

    /* Alert */
    .login-alert {
      background: rgba(239,68,68,0.12);
      border: 1px solid rgba(239,68,68,0.3);
      border-radius: 10px;
      padding: 12px 16px;
      color: #FCA5A5;
      font-size: 13px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin-bottom: 20px;
      animation: shake 0.4s ease;
    }

    .login-alert.lockout {
      background: rgba(245,158,11,0.12);
      border-color: rgba(245,158,11,0.3);
      color: #FCD34D;
    }

    .login-alert.expired {
      background: rgba(59,130,246,0.12);
      border-color: rgba(59,130,246,0.3);
      color: #93C5FD;
    }

    @keyframes shake {
      0%,100% { transform: translateX(0); }
      25%      { transform: translateX(-6px); }
      75%      { transform: translateX(6px); }
    }

    /* Form fields */
    .field-group { margin-bottom: 18px; }

    .field-label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: rgba(255,255,255,0.6);
      letter-spacing: 0.8px;
      text-transform: uppercase;
      margin-bottom: 8px;
    }

    .field-input-wrap { position: relative; }

    .field-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255,255,255,0.3);
      font-size: 15px;
      pointer-events: none;
      transition: color 0.2s;
    }

    .field-input {
      width: 100%;
      background: rgba(255,255,255,0.06);
      border: 1.5px solid rgba(255,255,255,0.12);
      border-radius: 10px;
      padding: 13px 44px 13px 44px;
      color: #FFFFFF;
      font-size: 14px;
      font-family: 'Inter', sans-serif;
      transition: all 0.25s ease;
      outline: none;
    }

    .field-input::placeholder { color: rgba(255,255,255,0.25); }

    .field-input:focus {
      border-color: var(--gold);
      background: rgba(212,175,55,0.06);
      box-shadow: 0 0 0 3px rgba(212,175,55,0.12);
    }
    .field-input:focus + .field-icon-right { color: var(--gold); }
    .field-input:focus ~ .field-icon       { color: var(--gold); }

    /* Password toggle */
    .toggle-password {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: rgba(255,255,255,0.3);
      font-size: 14px;
      padding: 4px;
      transition: color 0.2s;
    }
    .toggle-password:hover { color: var(--gold); }

    /* Submit button */
    .btn-login {
      width: 100%;
      background: linear-gradient(135deg, var(--gold), var(--gold-dark));
      border: none;
      border-radius: 10px;
      padding: 14px;
      color: var(--primary-dark);
      font-size: 15px;
      font-weight: 800;
      font-family: 'Inter', sans-serif;
      cursor: pointer;
      transition: all 0.25s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-top: 24px;
      box-shadow: 0 4px 20px rgba(212,175,55,0.35);
      letter-spacing: 0.3px;
    }

    .btn-login:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(212,175,55,0.5);
    }

    .btn-login:active:not(:disabled) { transform: translateY(0); }

    .btn-login:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    /* Back link */
    .back-link {
      text-align: center;
      margin-top: 20px;
    }

    .back-link a {
      color: rgba(255,255,255,0.35);
      font-size: 13px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: color 0.2s;
    }
    .back-link a:hover { color: var(--gold-light); }

    /* Security badge */
    .security-badge {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 28px;
      color: rgba(255,255,255,0.2);
      font-size: 11px;
      letter-spacing: 0.5px;
    }
    .security-badge i { color: var(--success); font-size: 12px; }

    /* Spinner for loading state */
    .spinner {
      width: 16px; height: 16px;
      border: 2px solid rgba(13,27,62,0.3);
      border-top-color: var(--primary-dark);
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
      display: none;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body>

<!-- Floating orbs -->
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="login-wrapper">

  <!-- Logo -->
  <div class="login-logo">
    <div class="logo-circle">
      <i class="fas fa-globe-americas"></i>
    </div>
    <span class="brand-name">Visa Vista Global</span>
    <span class="brand-tagline">Admin Portal</span>
  </div>

  <!-- Card -->
  <div class="login-card">

    <h1 class="card-title">Welcome Back</h1>
    <p class="card-subtitle">Sign in to access the admin dashboard</p>

    <div class="section-divider"></div>

    <!-- Alerts -->
    <?php if (!empty($error)): ?>
    <div class="login-alert <?= (strpos($error,'locked') !== false || strpos($error,'wait') !== false) ? 'lockout' : '' ?>">
      <i class="fas fa-<?= (strpos($error,'locked') !== false || strpos($error,'wait') !== false) ? 'clock' : 'exclamation-circle' ?>" style="margin-top:1px;flex-shrink:0;"></i>
      <span><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['expired'])): ?>
    <div class="login-alert expired">
      <i class="fas fa-info-circle" style="margin-top:1px;flex-shrink:0;"></i>
      <span>Your session has expired. Please log in again.</span>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['logout'])): ?>
    <div class="login-alert" style="background:rgba(16,185,129,0.12);border-color:rgba(16,185,129,0.3);color:#6EE7B7;">
      <i class="fas fa-check-circle" style="margin-top:1px;flex-shrink:0;"></i>
      <span>You have been logged out successfully.</span>
    </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="index.php?page=admin&action=login" id="loginForm" novalidate>
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <!-- Username -->
      <div class="field-group">
        <label class="field-label" for="username">Username</label>
        <div class="field-input-wrap">
          <i class="fas fa-user field-icon"></i>
          <input
            type="text"
            class="field-input"
            id="username"
            name="username"
            placeholder="Enter your username"
            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
            autocomplete="username"
            autofocus
            required>
        </div>
      </div>

      <!-- Password -->
      <div class="field-group">
        <label class="field-label" for="password">Password</label>
        <div class="field-input-wrap">
          <i class="fas fa-lock field-icon"></i>
          <input
            type="password"
            class="field-input"
            id="password"
            name="password"
            placeholder="Enter your password"
            autocomplete="current-password"
            required>
          <button type="button" class="toggle-password" id="togglePwd" title="Show/Hide password">
            <i class="fas fa-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn-login" id="loginBtn">
        <span id="btnText"><i class="fas fa-sign-in-alt"></i> Sign In to Dashboard</span>
        <div class="spinner" id="btnSpinner"></div>
      </button>
    </form>

    <!-- Back to Portal -->
    <div class="back-link">
      <a href="index.php">
        <i class="fas fa-arrow-left"></i>
        Back to Client Portal
      </a>
    </div>

  </div><!-- /login-card -->

  <!-- Security badge -->
  <div class="security-badge">
    <i class="fas fa-shield-alt"></i>
    Protected by session-based authentication &bull; CSRF protected
  </div>

</div><!-- /login-wrapper -->

<script>
  // Password toggle
  document.getElementById('togglePwd').addEventListener('click', function() {
    const pwd  = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
      pwd.type   = 'text';
      icon.className = 'fas fa-eye-slash';
    } else {
      pwd.type   = 'password';
      icon.className = 'fas fa-eye';
    }
  });

  // Loading state on submit
  document.getElementById('loginForm').addEventListener('submit', function() {
    const btn     = document.getElementById('loginBtn');
    const text    = document.getElementById('btnText');
    const spinner = document.getElementById('btnSpinner');
    btn.disabled      = true;
    text.style.display    = 'none';
    spinner.style.display = 'block';
  });
</script>

</body>
</html>
