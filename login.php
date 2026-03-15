<!DOCTYPE html>
<?php
// ============================================================
// PARADOX SYSTEMS — login.php
// Admin Authentication using PHP Sessions + password_verify()
// Method: POST form → PHP checks credentials → session set
// ============================================================
session_start();

// ── Admin Credentials ────────────────────────────────────────
// Username : admin
// Password : Paradox@2025
//
// The password is stored as a bcrypt hash using PHP PASSWORD_DEFAULT.
// To change the password, generate a new hash by running:
//   php -r "echo password_hash('YourNewPassword', PASSWORD_DEFAULT);"
// Then replace ADMIN_PASS_HASH below with the output.
//
// Hash below = password_hash('Paradox@2025', PASSWORD_DEFAULT)
// ─────────────────────────────────────────────────────────────
define('ADMIN_USER', 'admin');
define('ADMIN_PASS_HASH', '$2y$10$YQlxOHFGg.9h8nEJy0NXOO9N4IF.5k7zJ0gxR02TqH.wqfHSK5pQO');

// ── If already logged in → redirect straight to secure page ──
if (isset($_SESSION['paradox_admin']) && $_SESSION['paradox_admin'] === true) {
    header('Location: secure.php');
    exit;
}

// ── Handle POST login attempt ─────────────────────────────────
$error   = '';
$attempt = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attempt  = true;
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Both fields are required.';
    } elseif ($username !== ADMIN_USER) {
        // Intentionally vague — don't reveal which field is wrong
        $error = 'Invalid credentials. Access denied.';
    } elseif (!password_verify($password, ADMIN_PASS_HASH)) {
        $error = 'Invalid credentials. Access denied.';
    } else {
        // ✅ Authentication successful
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['paradox_admin']    = true;
        $_SESSION['paradox_user']     = $username;
        $_SESSION['paradox_login_ts'] = time();
        header('Location: secure.php');
        exit;
    }
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login — Paradox Systems</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23060611'/><text y='72' x='10' font-size='68' fill='%2300ff41' font-family='monospace'>_</text></svg>" />
  <style>
    /* ── Login page specific styles ── */
    body { display: flex; align-items: center; justify-content: center; min-height: 100vh; }

    .login-wrap {
      width: 100%; max-width: 480px;
      padding: 1.5rem;
      position: relative; z-index: 2;
    }

    /* Glowing corner brackets */
    .login-box {
      background: var(--surface2);
      border: 1px solid var(--border);
      padding: 3rem 2.5rem;
      position: relative;
      animation: box-appear 0.6s ease both;
    }
    @keyframes box-appear {
      from { opacity: 0; transform: translateY(20px) scale(0.98); }
      to   { opacity: 1; transform: translateY(0)   scale(1); }
    }
    /* Animated top border */
    .login-box::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0; height: 2px;
      background: linear-gradient(90deg, transparent, var(--green), var(--cyan), transparent);
      box-shadow: 0 0 20px var(--green);
    }
    /* Corner bracket TL */
    .login-box::after {
      content: '';
      position: absolute; bottom: 0; right: 0;
      width: 20px; height: 20px;
      border-right: 1px solid var(--green);
      border-bottom: 1px solid var(--green);
      box-shadow: 4px 4px 8px rgba(0,255,65,.2);
    }
    .corner-tl {
      position: absolute; top: 0; left: 0;
      width: 20px; height: 20px;
      border-left: 1px solid var(--green);
      border-top: 1px solid var(--green);
    }

    .login-logo {
      font-family: var(--font-head); font-size: 1.5rem; font-weight: 900;
      color: var(--white); letter-spacing: .1em; text-align: center;
      margin-bottom: .4rem; text-decoration: none; display: block;
    }
    .login-logo .br  { color: var(--green); }
    .login-logo .cur { color: var(--green); animation: blink 1s step-end infinite; }

    .login-subtitle {
      font-family: var(--font-mono); font-size: .72rem; color: var(--text-dim);
      text-align: center; letter-spacing: .18em; text-transform: uppercase;
      margin-bottom: 2.5rem;
      display: flex; align-items: center; justify-content: center; gap: .5rem;
    }
    .login-subtitle i { color: var(--red); font-size: .65rem; }

    /* Terminal prompt header */
    .terminal-header {
      font-family: var(--font-mono); font-size: .75rem; color: var(--text-dim);
      margin-bottom: 2rem; padding-bottom: 1rem;
      border-bottom: 1px solid var(--border);
      line-height: 1.8;
    }
    .terminal-header .t-prompt { color: var(--green); }
    .terminal-header .t-cmd    { color: var(--cyan); }

    /* Form fields */
    .login-field { margin-bottom: 1.3rem; }
    .login-field label {
      display: flex; align-items: center; gap: .4rem;
      font-family: var(--font-mono); font-size: .65rem;
      color: var(--green); letter-spacing: .18em;
      text-transform: uppercase; margin-bottom: .5rem;
    }
    .login-field label i { font-size: .62rem; opacity: .7; }

    .input-wrap {
      display: flex; align-items: center;
      background: var(--bg2); border: 1px solid var(--border);
      transition: border-color .3s, box-shadow .3s;
    }
    .input-wrap:focus-within {
      border-color: var(--green);
      box-shadow: 0 0 0 2px rgba(0,255,65,.08), 0 0 16px rgba(0,255,65,.12);
    }
    .input-prefix {
      font-family: var(--font-mono); font-size: .75rem;
      color: var(--green); opacity: .5;
      padding: .78rem .9rem; white-space: nowrap;
      border-right: 1px solid var(--border);
      user-select: none;
    }
    .input-wrap input {
      flex: 1; background: transparent; border: none; outline: none;
      font-family: var(--font-mono); font-size: .85rem;
      color: var(--white); padding: .78rem 1rem;
      letter-spacing: .05em;
    }
    .input-wrap input::placeholder { color: var(--text-dim); font-style: italic; letter-spacing: .04em; }

    /* Error message */
    .login-error {
      display: flex; align-items: center; gap: .55rem;
      background: rgba(255,0,60,.07); border: 1px solid rgba(255,0,60,.3);
      padding: .85rem 1rem; margin-bottom: 1.4rem;
      font-family: var(--font-mono); font-size: .75rem; color: var(--red);
      animation: shake .4s ease;
    }
    .login-error i { font-size: .8rem; flex-shrink: 0; }
    @keyframes shake {
      0%,100%{ transform: translateX(0); }
      20%{ transform: translateX(-6px); }
      40%{ transform: translateX(6px); }
      60%{ transform: translateX(-4px); }
      80%{ transform: translateX(4px); }
    }

    /* Submit button */
    .login-btn {
      width: 100%; padding: 1rem;
      background: var(--green); color: var(--bg);
      font-family: var(--font-mono); font-size: .85rem; font-weight: 700;
      letter-spacing: .14em; text-transform: uppercase;
      border: 1px solid var(--green); cursor: pointer;
      transition: all .3s ease; margin-top: .5rem;
      display: flex; align-items: center; justify-content: center; gap: .6rem;
      position: relative; overflow: hidden;
    }
    .login-btn::before {
      content: ''; position: absolute;
      top: 0; left: -100%; width: 60%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(0,0,0,.12), transparent);
    }
    .login-btn:hover {
      background: transparent; color: var(--green);
      box-shadow: 0 0 20px rgba(0,255,65,.4), 0 0 60px rgba(0,255,65,.15);
      transform: translateY(-1px);
    }
    .login-btn:hover::before { animation: shimmer .55s ease forwards; }
    @keyframes shimmer { 0%{left:-100%} 100%{left:200%} }

    /* Footer links */
    .login-footer {
      text-align: center; margin-top: 2rem;
      padding-top: 1.5rem; border-top: 1px solid var(--border);
    }
    .login-footer a {
      font-family: var(--font-mono); font-size: .7rem;
      color: var(--text-dim); letter-spacing: .12em;
      text-transform: uppercase; transition: color .3s;
      display: inline-flex; align-items: center; gap: .4rem;
    }
    .login-footer a:hover { color: var(--green); }
    .login-footer a i { font-size: .62rem; }

    /* Scanline in background */
    .login-scanlines {
      position: fixed; inset: 0; pointer-events: none; z-index: 0;
      background: repeating-linear-gradient(
        0deg, transparent, transparent 2px,
        rgba(0,255,65,.008) 2px, rgba(0,255,65,.008) 4px
      );
    }

    /* Floating grid */
    .login-grid {
      position: fixed; inset: 0; pointer-events: none; z-index: 0;
      background-image:
        linear-gradient(rgba(0,255,65,.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,255,65,.02) 1px, transparent 1px);
      background-size: 50px 50px;
      animation: grid-move 25s linear infinite;
    }
    @keyframes grid-move { from{transform:translateY(0)} to{transform:translateY(50px)} }

    /* Attempt animation on failed login */
    <?php if ($attempt && $error): ?>
    .login-box { animation: shake .4s ease; }
    <?php endif; ?>
  </style>
</head>
<body>

<canvas id="matrix-canvas" aria-hidden="true"></canvas>
<div class="login-grid"  aria-hidden="true"></div>
<div class="login-scanlines" aria-hidden="true"></div>
<div class="cursor-dot"  id="cDot"></div>
<div class="cursor-ring" id="cRing"></div>

<div class="login-wrap">
  <div class="login-box">
    <div class="corner-tl"></div>

    <!-- Logo -->
    <a href="index.html" class="login-logo">
      <span class="br">[</span>PARADOX<span class="cur">_</span><span class="br">]</span>
    </a>
    <p class="login-subtitle">
      <i class="fa-solid fa-shield-halved"></i>
      Secure Admin Access
    </p>

    <!-- Terminal prompt -->
    <div class="terminal-header">
      <div><span class="t-prompt">root@paradox:~$</span> <span class="t-cmd">ssh admin@secure.paradox.internal</span></div>
      <div><span class="t-prompt"> </span> <span style="color:var(--gold);">Authenticating... Enter credentials to proceed.</span></div>
    </div>

    <!-- Logged out success message -->
    <?php if (isset($_GET['loggedout'])): ?>
    <div style="display:flex;align-items:center;gap:.55rem;background:rgba(0,255,65,.07);border:1px solid rgba(0,255,65,.25);padding:.85rem 1rem;margin-bottom:1.4rem;font-family:var(--font-mono);font-size:.75rem;color:var(--green);">
      <i class="fa-solid fa-circle-check"></i>
      Session terminated. You have been logged out.
    </div>
    <?php endif; ?>

    <!-- Error message -->
    <?php if ($attempt && $error): ?>
    <div class="login-error" role="alert">
      <i class="fa-solid fa-triangle-exclamation"></i>
      <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="login.php" novalidate>

      <div class="login-field">
        <label for="username">
          <i class="fa-solid fa-user"></i> User ID
        </label>
        <div class="input-wrap">
          <span class="input-prefix">ID://</span>
          <input
            type="text"
            id="username"
            name="username"
            placeholder="enter user id"
            value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
            autocomplete="username"
            required
            autofocus
          />
        </div>
      </div>

      <div class="login-field">
        <label for="password">
          <i class="fa-solid fa-lock"></i> Password
        </label>
        <div class="input-wrap">
          <span class="input-prefix">PW://</span>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="enter password"
            autocomplete="current-password"
            required
          />
        </div>
      </div>

      <button type="submit" class="login-btn">
        <i class="fa-solid fa-right-to-bracket"></i>
        Authenticate &amp; Enter
      </button>

    </form>

    <!-- Footer -->
    <div class="login-footer">
      <a href="index.html">
        <i class="fa-solid fa-arrow-left"></i> Return to Public Site
      </a>
    </div>

  </div><!-- /.login-box -->
</div><!-- /.login-wrap -->

<script src="js/main.js" defer></script>
</body>
</html>
