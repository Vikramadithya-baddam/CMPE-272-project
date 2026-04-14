<?php
// ============================================================
// PARADOX SYSTEMS — contact.php
// Contacts loaded from PostgreSQL (contacts table) via PDO.
// data/contacts.txt is no longer used.
// ============================================================
require_once __DIR__ . '/db_config.php';

// Load all contacts from DB
$contacts   = [];
$db_error   = null;
try {
    $pdo      = get_db();
    $stmt     = $pdo->query('SELECT * FROM contacts ORDER BY id ASC');
    $contacts = $stmt->fetchAll();
} catch (Exception $e) {
    $db_error = $e->getMessage();
}
$count = count($contacts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact — Paradox Systems</title>
  <meta name="description" content="Reach Paradox Systems. Establish an encrypted channel with our security team." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23080810'/><text y='72' x='10' font-size='68' fill='%2300ff41' font-family='monospace'>_</text></svg>" />
</head>
<body>

<div class="scanlines" aria-hidden="true"></div>
<canvas id="matrix-canvas" aria-hidden="true"></canvas>
<div class="cursor-dot" id="cDot"></div>
<div class="cursor-ring" id="cRing"></div>

<nav id="navbar">
  <div class="container nav-wrap">
    <a href="index.html" class="nav-logo"><span class="br">[</span>PARADOX<span class="cur">_</span><span class="br">]</span></a>
    <button class="hamburger" id="ham" aria-label="Toggle nav" aria-expanded="false">
      <span class="ham-line"></span><span class="ham-line"></span><span class="ham-line"></span>
    </button>
    <ul class="nav-links" id="navLinks">
      <li><a href="index.html"   class="nav-link">Home</a></li>
      <li><a href="about.html"   class="nav-link">About</a></li>
      <li><a href="products.php" class="nav-link">Products</a></li>
      <li><a href="news.html"    class="nav-link">News</a></li>
      <li><a href="contact.php"  class="nav-link active">Contact</a></li>
      <li><a href="login.php" class="nav-link" style="color:var(--gold);border:1px solid rgba(255,215,0,.25);padding:.4rem .8rem;font-size:.64rem;">
        <i class="fa-solid fa-lock" style="margin-right:.3rem;font-size:.55rem;"></i>Admin
      </a></li>
    </ul>
    <div class="nav-status"><span class="status-dot"></span><span id="navStatus">SECURE_CONN</span></div>
  </div>
</nav>

<main>

<div class="page-hero" data-bg-text="CONTACT">
  <div class="grid-bg"></div>
  <div class="container">
    <p class="eyebrow">// CONTACT.PHP &mdash; ENCRYPTED CHANNEL OPEN</p>
    <h1 style="color:var(--white);">ESTABLISH <span style="color:var(--green);">LINK</span></h1>
    <p class="sub">We don't have a call center. We have engineers. Every message is read by a human. Response time: under 24 hours.</p>
  </div>
</div>

<section>
  <div class="container">
    <div class="contact-grid">

      <!-- LEFT: Contacts from PostgreSQL -->
      <div>
        <div class="section-tag reveal">PERSONNEL &mdash; LOADED FROM DATABASE</div>
        <h2 class="reveal d1" style="color:var(--white);margin-bottom:0.5rem;">
          Our <span style="color:var(--cyan);">People</span>
        </h2>

        <!-- Terminal showing DB query -->
        <div class="terminal reveal d1" style="margin-bottom:1.5rem;">
          <div class="terminal-bar">
            <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
            <span class="t-title">paradox_db — contacts table</span>
          </div>
          <div class="t-line">
            <span class="t-prompt">$</span>
            <span class="t-cmd">SELECT * FROM contacts ORDER BY id ASC;</span>
          </div>
          <?php if ($db_error): ?>
          <div class="t-line">
            <span class="t-prompt"> </span>
            <span class="t-err">ERROR: <?php echo htmlspecialchars($db_error); ?></span>
          </div>
          <?php else: ?>
          <div class="t-line">
            <span class="t-prompt"> </span>
            <span class="t-ok"><?php echo $count; ?> rows returned &mdash; rendering below</span>
          </div>
          <?php endif; ?>
        </div>

        <?php if ($db_error): ?>
          <div class="terminal" style="color:var(--red);font-family:var(--font-mono);font-size:.8rem;padding:1rem;border:1px solid rgba(255,0,60,.3);">
            <p><i class="fa-solid fa-triangle-exclamation"></i> Database error: <?php echo htmlspecialchars($db_error); ?></p>
            <p style="margin-top:.5rem;color:var(--text-dim);">Check your DB_HOST, DB_NAME, DB_USER, DB_PASS environment variables on Render.</p>
          </div>

        <?php elseif (empty($contacts)): ?>
          <div class="terminal" style="color:var(--text-dim);font-family:var(--font-mono);font-size:.8rem;padding:1rem;">
            <p>No contacts found. Run db_setup.sql to seed the contacts table.</p>
          </div>

        <?php else: ?>
          <div class="person-list reveal d2">
            <?php foreach ($contacts as $person):
              // Build 2-letter initials
              $parts    = explode(' ', $person['name']);
              $initials = '';
              foreach ($parts as $pt) {
                  if (strlen($pt) > 0 && ctype_alpha($pt[0])) $initials .= strtoupper($pt[0]);
              }
              $initials = substr($initials, 0, 2);
              $email    = htmlspecialchars($person['email'] ?? '');
            ?>
            <div class="person-card">
              <div class="pc-initials"><?php echo htmlspecialchars($initials); ?></div>
              <div class="pc-person-name"><?php echo htmlspecialchars($person['name']); ?></div>
              <?php if (!empty($person['role'])): ?>
                <div class="pc-role"><?php echo htmlspecialchars($person['role']); ?></div>
              <?php endif; ?>
              <?php if (!empty($person['department'])): ?>
                <div class="pc-alias">DEPT: <?php echo htmlspecialchars($person['department']); ?></div>
              <?php endif; ?>
              <?php if (!empty($person['phone'])): ?>
                <div class="pc-row">
                  <span class="pc-key">PHONE:</span>
                  <span class="pc-val">
                    <a href="tel:<?php echo htmlspecialchars($person['phone']); ?>">
                      <?php echo htmlspecialchars($person['phone']); ?>
                    </a>
                  </span>
                </div>
              <?php endif; ?>
              <?php if (!empty($email)): ?>
                <div class="pc-row">
                  <span class="pc-key">EMAIL:</span>
                  <span class="pc-val">
                    <a href="<?php echo 'mailto:' . $email; ?>"><?php echo $email; ?></a>
                  </span>
                </div>
              <?php endif; ?>
              <?php if (!empty($person['location'])): ?>
                <div class="pc-row">
                  <span class="pc-key">LOCATION:</span>
                  <span class="pc-val"><?php echo htmlspecialchars($person['location']); ?></span>
                </div>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- General support box -->
        <div class="alert-box reveal" style="margin-top:1.5rem;border-color:rgba(0,212,255,0.3);background:rgba(0,212,255,0.04);">
          <div class="alert-title" style="color:var(--cyan);">GENERAL INQUIRIES</div>
          <p class="alert-text">
            For sales, partnerships, or press:<br>
            <a href="mailto:hello@paradoxsystems.io" style="color:var(--cyan);">hello@paradoxsystems.io</a><br><br>
            For technical support or platform issues:<br>
            <a href="mailto:support@paradoxsystems.io" style="color:var(--cyan);">support@paradoxsystems.io</a><br><br>
            Response time: <span style="color:var(--green);">under 4 business hours.</span>
          </p>
        </div>
      </div>

      <!-- RIGHT: Contact form -->
      <div class="reveal d2">
        <div class="contact-form">

          <div class="terminal" style="margin-bottom:1.4rem;">
            <div class="terminal-bar">
              <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
              <span class="t-title">secure_channel &mdash; TLS 1.3 &mdash; E2E encrypted</span>
            </div>
            <div class="t-line"><span class="t-prompt">$</span><span class="t-cmd">open_channel --encrypt --no-log</span></div>
            <div class="t-line"><span class="t-prompt"> </span><span class="t-ok">channel open. transmit when ready.</span></div>
          </div>

          <div class="cf-title">TRANSMIT MESSAGE</div>
          <p class="cf-sub">// We do not share, sell, or log your data beyond responding to your message. We're a security company. Acting otherwise would be embarrassing.</p>

          <form id="cf" novalidate>
            <div class="form-row2">
              <div class="fg"><label for="fname">FIRST NAME</label><input type="text" id="fname" name="fname" placeholder="e.g. Elliot" required /></div>
              <div class="fg"><label for="lname">LAST NAME</label><input type="text" id="lname" name="lname" placeholder="e.g. Alderson" required /></div>
            </div>
            <div class="fg"><label for="femail">EMAIL</label><input type="email" id="femail" name="email" placeholder="you@company.com" required /></div>
            <div class="fg"><label for="company">COMPANY</label><input type="text" id="company" name="company" placeholder="e.g. E Corp, Stark Industries..." /></div>
            <div class="fg">
              <label for="subject">INQUIRY TYPE</label>
              <select id="subject" name="subject">
                <option value="">-- SELECT TYPE --</option>
                <option>Penetration Testing</option>
                <option>ORACLE AI Defense Demo</option>
                <option>Quantum Encryption</option>
                <option>Dark Web Monitoring</option>
                <option>Active Incident Response</option>
                <option>Pricing and Tiers</option>
                <option>Press / Media</option>
                <option>Other</option>
              </select>
            </div>
            <div class="fg">
              <label for="msg">MESSAGE</label>
              <textarea id="msg" name="msg" placeholder="Describe your situation, infrastructure, timeline, or specific concerns..." required></textarea>
            </div>
            <div class="form-footer">
              <span class="form-note">// 256-bit AES &bull; No tracking &bull; No third parties</span>
              <button type="submit" class="btn btn-filled">TRANSMIT &rarr;</button>
            </div>
          </form>

          <div class="form-ok" id="form-ok">
            <span style="color:var(--green);">&#10003; TRANSMISSION RECEIVED</span><br><br>
            // Your message has been received and flagged for priority review.<br>
            // An engineer (not a bot) will respond within 24 hours.<br>
            // Active breach? Use: <a href="mailto:breach@paradoxsystems.io" style="color:var(--red);">breach@paradoxsystems.io</a>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

<!-- FAQ -->
<section style="background:var(--surface);padding:4rem 0 5rem;">
  <div class="container">
    <div class="section-tag reveal">FAQ</div>
    <h2 class="reveal d1" style="color:var(--white);margin-bottom:2.5rem;">Things <span style="color:var(--gold);">People Ask</span></h2>
    <div class="vals-grid reveal d2">
      <div class="val-item">
        <div class="val-num" style="font-size:1rem;color:var(--green);">Q_01</div>
        <div class="val-title">WHAT MAKES YOU DIFFERENT?</div>
        <p>We eat what we cook. Our own infrastructure runs on every product we sell. We're both lab and test subject. Also, no client has ever been breached on our watch. That's our actual record.</p>
      </div>
      <div class="val-item">
        <div class="val-num" style="font-size:1rem;color:var(--green);">Q_02</div>
        <div class="val-title">HOW LONG DOES A PEN TEST TAKE?</div>
        <p>Two to four weeks for full-scope. Scoping call first, then rules of engagement. Attack phase is typically 5-10 business days. Report delivered 48 hours post-engagement.</p>
      </div>
      <div class="val-item">
        <div class="val-num" style="font-size:1rem;color:var(--green);">Q_03</div>
        <div class="val-title">DO YOU WORK OUTSIDE THE US?</div>
        <p>Yes. Newark, London, Singapore, and Sao Paulo. We have worked in 28 countries. Threat actors don't respect borders. We match their geographic footprint.</p>
      </div>
      <div class="val-item">
        <div class="val-num" style="font-size:1rem;color:var(--green);">Q_04</div>
        <div class="val-title">WHAT IS YOUR INCIDENT RESPONSE SLA?</div>
        <p><span style="color:var(--red);">4 minutes</span> for active breaches via the breach@ channel. 24 hours for all standard inquiries. Someone is always awake, alert, and alarmed on your behalf.</p>
      </div>
    </div>
  </div>
</section>

</main>

<footer>
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="footer-logo"><span class="br">[</span>PARADOX<span class="cur">_</span><span class="br">]</span></div>
        <p class="footer-tagline">We existed before you knew you needed us.</p>
        <p class="footer-quote">"Hello, friend." <em>— Mr. Robot S01E01</em></p>
      </div>
      <div class="f-col"><h5>// Navigate</h5><ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="news.html">News</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul></div>
      <div class="f-col"><h5>// Products</h5><ul>
        <li><a href="product-pentestpro.php">PenTest Pro</a></li>
        <li><a href="product-oracle-ai.php">ORACLE AI Shield</a></li>
        <li><a href="product-quantumvault.php">QuantumVault</a></li>
        <li><a href="product-darkscan.php">DarkScan</a></li>
      </ul></div>
      <div class="f-col">
        <h5>// Transmission</h5>
        <p class="f-sub">Encrypted channel. No tracking. No logging.</p>
        <div class="f-email-row">
          <div class="f-input-wrap"><span class="f-prefix">root@paradox:~$</span><input type="email" placeholder="enter_email" aria-label="Newsletter" /></div>
          <button class="f-btn" type="button">TRANSMIT</button>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="f-copy"><span>&copy; <?php echo date('Y'); ?> Paradox Systems Inc.</span><span class="f-sep">|</span><span id="uptime">UPTIME: CALCULATING...</span></div>
      <div class="f-links"><a href="#">PRIVACY</a><a href="#">TERMS</a><a href="#">PGP</a></div>
    </div>
    <div class="f-easter" id="easter">// Hello, friend. Hello, friend? That's lame. Maybe I should give you a name.</div>
  </div>
</footer>

<script src="js/main.js" defer></script>
</body>
</html>
