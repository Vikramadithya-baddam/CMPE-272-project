<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact — Paradox Systems</title>
  <meta name="description" content="Reach Paradox Systems. Establish an encrypted channel with our security team." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
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
      <li><a href="index.html"    class="nav-link">Home</a></li>
      <li><a href="about.html"    class="nav-link">About</a></li>
      <li><a href="products.html" class="nav-link">Services</a></li>
      <li><a href="news.html"     class="nav-link">News</a></li>
      <li><a href="contact.php"   class="nav-link active">Contact</a></li>
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

      <!-- ============================================================
           LEFT SIDE: Contact offices loaded from contacts.txt via PHP
           PHP is ONLY used here to read the text file.
           ============================================================ -->
      <div>
        <div class="section-tag reveal">PERSONNEL &mdash; LOADED FROM contacts.txt</div>
        <h2 class="reveal d1" style="color:var(--white);margin-bottom:0.5rem;">Our <span style="color:var(--cyan);">People</span></h2>
        <p class="reveal d2" style="color:var(--text-dim);font-family:var(--font-mono);font-size:0.75rem;margin-bottom:1.5rem;line-height:1.6;">
          <?php
            $file = dirname(__FILE__) . '/data/contacts.txt';
            if (!file_exists($file)) {
                $file = $_SERVER['DOCUMENT_ROOT'] . '/data/contacts.txt';
            }
            $count = 0;
            if (file_exists($file)) {
                $blocks = explode('---', file_get_contents($file));
                foreach ($blocks as $b) { if (trim($b)) $count++; }
            }
            echo '// ' . $count . ' team members &mdash; read from data/contacts.txt';
          ?>
        </p>

        <?php
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $blocks  = explode('---', $content);

            echo '<div class="person-list reveal d2">';

            foreach ($blocks as $block) {
                $block = trim($block);
                if (empty($block)) continue;

                $data = array();
                foreach (explode("\n", $block) as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    $pos = strpos($line, '=');
                    if ($pos !== false) {
                        $key = trim(substr($line, 0, $pos));
                        $val = trim(substr($line, $pos + 1));
                        $data[$key] = $val;
                    }
                }

                if (empty($data) || empty($data['name'])) continue;

                // Build 2-letter initials from name
                $parts    = explode(' ', $data['name']);
                $initials = '';
                foreach ($parts as $p) {
                    if (strlen($p) > 0 && ctype_alpha($p[0])) {
                        $initials .= strtoupper($p[0]);
                    }
                }
                $initials = substr($initials, 0, 2);

                echo '<div class="person-card">';
                echo   '<div class="pc-initials">' . htmlspecialchars($initials) . '</div>';
                echo   '<div class="pc-person-name">' . htmlspecialchars($data['name']) . '</div>';

                if (!empty($data['role'])) {
                    echo '<div class="pc-role">' . htmlspecialchars($data['role']) . '</div>';
                }
                if (!empty($data['department'])) {
                    echo '<div class="pc-alias">DEPT: ' . htmlspecialchars($data['department']) . '</div>';
                }
                if (!empty($data['phone'])) {
                    echo '<div class="pc-row"><span class="pc-key">PHONE:</span><span class="pc-val"><a href="tel:' . htmlspecialchars($data['phone']) . '">' . htmlspecialchars($data['phone']) . '</a></span></div>';
                }
                if (!empty($data['email'])) {
                    echo '<div class="pc-row"><span class="pc-key">EMAIL:</span><span class="pc-val"><a href="mailto:' . htmlspecialchars($data['email']) . '">' . htmlspecialchars($data['email']) . '</a></span></div>';
                }
                if (!empty($data['location'])) {
                    echo '<div class="pc-row"><span class="pc-key">LOCATION:</span><span class="pc-val">' . htmlspecialchars($data['location']) . '</span></div>';
                }

                echo '</div>';
            }

            echo '</div>';

        } else {
            echo '<div class="terminal" style="color:var(--red);font-family:var(--font-mono);font-size:.8rem;padding:1rem;">';
            echo '<p>ERROR: data/contacts.txt not found.<br>Check that the data/ folder was included in your Docker image.</p>';
            echo '</div>';
        }
        ?>

        <!-- General support box -->
        <div class="alert-box reveal" style="margin-top:1.5rem; border-color:rgba(0,212,255,0.3); background:rgba(0,212,255,0.04);">
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

      <!-- RIGHT SIDE: Contact form (HTML only, no PHP) -->
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
            <div class="fg"><label for="email">EMAIL</label><input type="email" id="email" name="email" placeholder="you@company.com" required /></div>
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
            <span style="color:var(--green);">v TRANSMISSION RECEIVED</span><br><br>
            // Your message has been received and flagged for priority review.<br>
            // An engineer (not a bot) will respond within 24 hours.<br>
            // Active breach? Use: <a href="/cdn-cgi/l/email-protection#bcdeced9dddfd4fcccddceddd8d3c4cfc5cfc8d9d1cf92d5d3" style="color:var(--red);"><span class="__cf_email__" data-cfemail="ff9d8d9a9e9c97bf8f9e8d9e9b90878c868c8b9a928cd19690">[email&#160;protected]</span></a>
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
      <div class="f-col"><h5>// Navigate</h5><ul><li><a href="index.html">Home</a></li><li><a href="about.html">About</a></li><li><a href="products.html">Services</a></li><li><a href="news.html">News</a></li><li><a href="contact.php">Contact</a></li></ul></div>
      <div class="f-col"><h5>// Services</h5><ul><li><a href="products.html">Penetration Testing</a></li><li><a href="products.html">AI Defense</a></li><li><a href="products.html">Quantum Encryption</a></li><li><a href="products.html">Dark Web Monitor</a></li></ul></div>
      <div class="f-col"><h5>// Transmission</h5><p class="f-sub">Encrypted channel. No tracking. No logging.</p><div class="f-email-row"><div class="f-input-wrap"><span class="f-prefix">root@paradox:~$</span><input type="email" placeholder="enter_email" aria-label="Newsletter" /></div><button class="f-btn" type="button">TRANSMIT</button></div></div>
    </div>
    <div class="footer-bottom">
      <div class="f-copy"><span>&copy; 2025 Paradox Systems Inc.</span><span class="f-sep">|</span><span id      <div class="f-copy"><span>&copy; 2025 Paradox Systems Inc.</span><span class="f-sep">|</span><span id="uptime">UPTIME: CALCULATING...</span></div>
      <div class="f-links"><a href="#">PRIVACY</a><a href="#">TERMS</a><a href="#">PGP</a></div>
    </div>
    <div class="f-easter" id="easter">// Hello, friend. Hello, friend? That's lame. Maybe I should give you a name.</div>
  </div>
</footer>

<script src="js/main.js" defer></script>
</body>
</html>
