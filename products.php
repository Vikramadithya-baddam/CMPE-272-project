<?php
require_once 'cookie_handler.php';

$recent  = read_recent();
$catalog = get_catalog();
$top5    = get_most_visited(5);
$active_page = 'products';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Services &amp; Products — Paradox Systems</title>
  <meta name="description" content="Ten cybersecurity products and services: penetration testing, AI defense, quantum encryption, dark web monitoring and more." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23060611'/><text y='72' x='10' font-size='68' fill='%2300ff41' font-family='monospace'>_</text></svg>" />
</head>
<body>
<?php include '_nav.php'; ?>

<!-- PAGE HERO -->
<div class="page-hero" data-bg-text="ARSENAL">
  <div class="grid-bg"></div>
  <div class="container">
    <p class="eyebrow"><i class="fa-solid fa-chevron-right"></i> PRODUCTS &amp; SERVICES CATALOG</p>
    <h1 style="color:var(--white);">THE <span style="color:var(--red);">ARSENAL</span></h1>
    <p class="sub">Ten battle-tested cybersecurity products. Each engineered to protect, detect, and respond faster than any threat actor on the planet.</p>
  </div>
</div>

<!-- STATS -->
<section style="padding:2.5rem 0 0;">
  <div class="container">
    <div class="nums-strip reveal">
      <div class="num-item"><span class="big" style="color:var(--green);" data-n="10">0</span><div class="lbl">Products</div></div>
      <div class="num-item"><span class="big" style="color:var(--green);" data-n="340" data-s="+">0+</span><div class="lbl">Enterprise Clients</div></div>
      <div class="num-item"><span class="big" style="color:var(--gold);"  data-n="4" data-s="min">0</span><div class="lbl">Zero-Day Response</div></div>
      <div class="num-item"><span class="big" style="color:var(--red);"   data-n="0">0</span><div class="lbl">Breaches on Our Watch</div></div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════
     COOKIE TRACKING SECTIONS
     ══════════════════════════════════════════════ -->
<section style="padding:3rem 0 0;">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="reveal">

      <!-- Recently Visited -->
      <div class="terminal">
        <div class="terminal-bar">
          <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
          <span class="t-title">cookie: paradox_recent — last 5 visited</span>
        </div>
        <?php if (empty($recent)): ?>
          <div class="t-line"><span class="t-prompt">$</span><span class="t-out">No products visited yet. Start browsing below.</span></div>
        <?php else: ?>
          <div class="t-line"><span class="t-prompt">$</span><span class="t-cmd">read_cookie('paradox_recent')</span></div>
          <?php foreach ($recent as $i => $slug): ?>
            <?php if (isset($catalog[$slug])): ?>
              <div class="t-line">
                <span class="t-prompt">&nbsp;</span>
                <span class="t-out">[<?php echo $i+1; ?>]&nbsp;</span>
                <a href="<?php echo $catalog[$slug]['url']; ?>" style="color:var(--green);font-family:var(--font-mono);font-size:.78rem;">
                  <?php echo htmlspecialchars($catalog[$slug]['name']); ?>
                </a>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
        <div style="margin-top:1rem;padding-top:.8rem;border-top:1px solid var(--border);">
          <a href="recently-visited.php" class="btn btn-green" style="font-size:.72rem;padding:.55rem 1.2rem;">
            <i class="fa-solid fa-clock-rotate-left"></i> View Full Recently Visited
          </a>
        </div>
      </div>

      <!-- Most Visited -->
      <div class="terminal">
        <div class="terminal-bar">
          <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
          <span class="t-title">cookie: paradox_visits — top 5 by count</span>
        </div>
        <?php if (empty($top5)): ?>
          <div class="t-line"><span class="t-prompt">$</span><span class="t-out">No visit data yet. Explore products to populate.</span></div>
        <?php else: ?>
          <div class="t-line"><span class="t-prompt">$</span><span class="t-cmd">get_most_visited(5)</span></div>
          <?php foreach ($top5 as $slug => $count): ?>
            <?php if (isset($catalog[$slug])): ?>
              <div class="t-line">
                <span class="t-prompt">&nbsp;</span>
                <span class="t-out" style="min-width:30px;"><?php echo str_pad($count, 3, ' ', STR_PAD_LEFT); ?>x&nbsp;</span>
                <a href="<?php echo $catalog[$slug]['url']; ?>" style="color:var(--gold);font-family:var(--font-mono);font-size:.78rem;">
                  <?php echo htmlspecialchars($catalog[$slug]['name']); ?>
                </a>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
        <div style="margin-top:1rem;padding-top:.8rem;border-top:1px solid var(--border);">
          <a href="most-visited.php" class="btn btn-green" style="font-size:.72rem;padding:.55rem 1.2rem;">
            <i class="fa-solid fa-fire"></i> View Most Visited Products
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════
     ALL 10 PRODUCTS
     ══════════════════════════════════════════════ -->
<section>
  <div class="container">
    <div class="section-tag reveal">ALL_PRODUCTS.DB — 10 SERVICES</div>
    <h2 class="reveal d1" style="color:var(--white);margin-bottom:1.5rem;">Our Full <span style="color:var(--green);">Product Suite</span></h2>

    <!-- Search + Filter + Compare bar -->
    <div style="display:flex;align-items:center;gap:.8rem;flex-wrap:wrap;margin-bottom:2rem;" class="reveal d2">
      <!-- Search -->
      <div style="display:flex;align-items:center;gap:.5rem;background:var(--bg2);border:1px solid var(--border);padding:.55rem 1rem;flex:1;min-width:220px;transition:border-color .3s;" id="searchWrap">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--text-dim);font-size:.75rem;"></i>
        <input type="text" id="prodSearch" placeholder="Search products..."
               style="background:transparent;border:none;outline:none;font-family:var(--font-mono);font-size:.8rem;color:var(--text);width:100%;"
               oninput="filterProducts()" />
      </div>
      <!-- Category filter -->
      <select id="catFilter" onchange="filterProducts()"
              style="background:var(--bg2);border:1px solid var(--border);color:var(--text);font-family:var(--font-mono);font-size:.75rem;padding:.55rem 1rem;outline:none;cursor:pointer;">
        <option value="">All Categories</option>
        <option value="OFFENSIVE_SEC">Offensive Sec</option>
        <option value="AI_DEFENSE">AI Defense</option>
        <option value="ENCRYPTION">Encryption</option>
        <option value="INTELLIGENCE">Intelligence</option>
        <option value="INCIDENT_RESP">Incident Response</option>
        <option value="FORENSICS">Forensics</option>
        <option value="TRAINING">Training</option>
        <option value="CLOUD_SEC">Cloud Security</option>
        <option value="RED_TEAM">Red Team</option>
        <option value="COMPLIANCE">Compliance</option>
      </select>
      <!-- Compare link -->
      <a href="compare.php" class="btn btn-green" style="padding:.55rem 1.2rem;font-size:.72rem;white-space:nowrap;">
        <i class="fa-solid fa-code-compare"></i> Compare Products
      </a>
      <!-- Result count -->
      <span id="filterCount" style="font-family:var(--font-mono);font-size:.7rem;color:var(--text-dim);white-space:nowrap;">
        Showing <span id="countNum" style="color:var(--green);">10</span> of 10
      </span>
    </div>

    <!-- No results message -->
    <div id="noResults" style="display:none;text-align:center;padding:3rem;border:1px solid var(--border);background:var(--surface);margin-bottom:1px;">
      <i class="fa-solid fa-magnifying-glass" style="font-size:2rem;color:var(--text-dim);opacity:.4;margin-bottom:1rem;display:block;"></i>
      <div style="font-family:var(--font-head);font-size:.9rem;color:var(--white);margin-bottom:.5rem;">NO PRODUCTS FOUND</div>
      <p style="font-family:var(--font-mono);font-size:.75rem;color:var(--text-dim);">Try a different search term or category.</p>
    </div>

    <div class="prod-grid reveal d2">

      <!-- 01 PenTest Pro -->
      <div class="prod-card" data-cat="OFFENSIVE_SEC">
        <div class="pc-top">
          <span class="pc-icon"><i class="fa-solid fa-bug"></i></span>
          <span class="pc-tag green">OFFENSIVE_SEC</span>
        </div>
        <div class="pc-title">01. PenTest Pro</div>
        <p class="pc-desc">Nation-state grade attack simulation across every vector — external, internal, social engineering, wireless, and physical. We get in so your enemies can't.</p>
        <div class="pc-feats">
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Full-scope multi-vector attacks</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Executive + technical report</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Free re-test after remediation</span>
        </div>
        <div style="margin-top:1.2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.78rem;color:var(--green-lt);text-shadow:0 0 8px rgba(0,255,101,.3);">$8,500/engagement</span>
          <a href="product-pentestpro.php" class="btn btn-green" style="padding:.5rem 1rem;font-size:.7rem;">VIEW &rsaquo;</a>
        </div>
      </div>

      <!-- 02 ORACLE AI Shield -->
      <div class="prod-card" data-cat="AI_DEFENSE">
        <div class="pc-top">
          <span class="pc-icon"><i class="fa-solid fa-brain"></i></span>
          <span class="pc-tag cyan">AI_DEFENSE</span>
        </div>
        <div class="pc-title">02. ORACLE AI Shield</div>
        <p class="pc-desc">Our proprietary AI model trained on 15 years of breach data. Detects anomalies in under 100ms and responds automatically before humans even open their laptops.</p>
        <div class="pc-feats">
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Sub-100ms threat detection</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Behavioral baselining</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>SIEM integration included</span>
        </div>
        <div style="margin-top:1.2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.78rem;color:var(--cyan-lt);text-shadow:0 0 8px rgba(0,229,255,.3);">$2,800/mo</span>
          <a href="product-oracle-ai.php" class="btn btn-green" style="padding:.5rem 1rem;font-size:.7rem;">VIEW &rsaquo;</a>
        </div>
      </div>

      <!-- 03 QuantumVault -->
      <div class="prod-card" data-cat="ENCRYPTION">
        <div class="pc-top">
          <span class="pc-icon"><i class="fa-solid fa-lock"></i></span>
          <span class="pc-tag gold">ENCRYPTION</span>
        </div>
        <div class="pc-title">03. QuantumVault</div>
        <p class="pc-desc">Post-quantum cryptography suite using NIST-approved algorithms. Protects your data against quantum computers that don't exist yet — because they will.</p>
        <div class="pc-feats">
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Kyber-1024 key exchange</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Data at rest + in transit</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>FIPS 140-3 compliant</span>
        </div>
        <div style="margin-top:1.2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.78rem;color:var(--gold-lt);text-shadow:0 0 8px rgba(255,204,0,.3);">$4,200/mo</span>
          <a href="product-quantumvault.php" class="btn btn-green" style="padding:.5rem 1rem;font-size:.7rem;">VIEW &rsaquo;</a>
        </div>
      </div>

      <!-- 04 DarkScan -->
      <div class="prod-card" data-cat="INTELLIGENCE">
        <div class="pc-top">
          <span class="pc-icon"><i class="fa-solid fa-eye"></i></span>
          <span class="pc-tag red">INTELLIGENCE</span>
        </div>
        <div class="pc-title">04. DarkScan</div>
        <p class="pc-desc">40,000+ dark web sources monitored 24/7. Your credentials, source code, and PII could be for sale right now. We find it before the buyer does.</p>
        <div class="pc-feats">
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>40K+ dark web sources</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Real-time credential alerts</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Takedown service included</span>
        </div>
        <div style="margin-top:1.2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.78rem;color:var(--red-lt);text-shadow:0 0 8px rgba(255,26,78,.3);">$1,500/mo</span>
          <a href="product-darkscan.php" class="btn btn-green" style="padding:.5rem 1rem;font-size:.7rem;">VIEW &rsaquo;</a>
        </div>
      </div>

      <!-- 05 ZeroDay Response -->
      <div class="prod-card" data-cat="INCIDENT_RESP">
        <div class="pc-top">
          <span class="pc-icon"><i class="fa-solid fa-bolt"></i></span>
          <span class="pc-tag red">INCIDENT_RESP</span>
        </div>
        <div class="pc-title">05. ZeroDay Response</div>
        <p class="pc-desc">A zero-day drops at 2AM. We are paged in 60 seconds. An engineer is on your system in 4 minutes. Industry average is 72 hours. That gap is your vulnerability.</p>
        <div class="pc-feats">
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>4-minute response SLA</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>24/7/365 on-call team</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Regulatory notification help</span>
        </div>
        <div style="margin-top:1.2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.78rem;color:var(--orange-lt, #ff8c5a);text-shadow:0 0 8px rgba(255,107,53,.3);">$15,000/yr</span>
          <a href="product-zerodayresponse.php" class="btn btn-green" style="padding:.5rem 1rem;font-size:.7rem;">VIEW &rsaquo;</a>
        </div>
      </div>

      <!-- 06 ForensicsLab -->
      <div class="prod-card" data-cat="FORENSICS">
        <div class="pc-top">
          <span class="pc-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
          <span class="pc-tag cyan">FORENSICS</span>
        </div>
        <div class="pc-title">06. ForensicsLab</div>
        <p class="pc-desc">Reconstruct the complete attack timeline from raw artifacts. Memory dumps, network flows, registry hives. We tell you who, how, when — down to the millisecond.</p>
        <div class="pc-feats">
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Full attack reconstruction</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Threat actor attribution</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Court-ready evidence reports</span>
        </div>
        <div style="margin-top:1.2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.78rem;color:var(--teal-lt, #00d4be);text-shadow:0 0 8px rgba(0,180,160,.3);">$6,000/engagement</span>
          <a href="product-forensicslab.php" class="btn btn-green" style="padding:.5rem 1rem;font-size:.7rem;">VIEW &rsaquo;</a>
        </div>
      </div>

      <!-- 07 PhishGuard -->
      <div class="prod-card" data-cat="TRAINING">
        <div class="pc-top">
          <span class="pc-icon"><i class="fa-solid fa-fish"></i></span>
          <span class="pc-tag gold">TRAINING</span>
        </div>
        <div class="pc-title">07. PhishGuard</div>
        <p class="pc-desc">Real phishing simulations against your own employees. Identify who clicks what. Targeted training for high-risk users. Turn your weakest link into your strongest defense.</p>
        <div class="pc-feats">
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Realistic phishing campaigns</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Per-user risk scoring</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Automated training modules</span>
        </div>
        <div style="margin-top:1.2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.78rem;color:var(--gold-lt);text-shadow:0 0 8px rgba(255,224,64,.3);">$900/mo</span>
          <a href="product-phishguard.php" class="btn btn-green" style="padding:.5rem 1rem;font-size:.7rem;">VIEW &rsaquo;</a>
        </div>
      </div>

      <!-- 08 CloudFortress -->
      <div class="prod-card" data-cat="CLOUD_SEC">
        <div class="pc-top">
          <span class="pc-icon"><i class="fa-solid fa-cloud"></i></span>
          <span class="pc-tag cyan">CLOUD_SEC</span>
        </div>
        <div class="pc-title">08. CloudFortress</div>
        <p class="pc-desc">Complete cloud security posture management for AWS, Azure, and GCP. Misconfigured S3 buckets, exposed IAM roles, open security groups — we find them all before attackers do.</p>
        <div class="pc-feats">
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>AWS, Azure, GCP coverage</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Continuous misconfiguration scan</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>One-click remediation guides</span>
        </div>
        <div style="margin-top:1.2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.78rem;color:var(--purple-lt);text-shadow:0 0 8px rgba(192,132,252,.3);">$3,400/mo</span>
          <a href="product-cloudfortress.php" class="btn btn-green" style="padding:.5rem 1rem;font-size:.7rem;">VIEW &rsaquo;</a>
        </div>
      </div>

      <!-- 09 RedOps Suite -->
      <div class="prod-card" data-cat="RED_TEAM">
        <div class="pc-top">
          <span class="pc-icon"><i class="fa-solid fa-chess-knight"></i></span>
          <span class="pc-tag red">RED_TEAM</span>
        </div>
        <div class="pc-title">09. RedOps Suite</div>
        <p class="pc-desc">Full adversarial simulation. Our red team thinks like a nation-state actor and operates with the same tools, tactics, and procedures. No rules except the ones you set.</p>
        <div class="pc-feats">
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Nation-state TTPs</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Physical + digital + social</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Purple team debrief included</span>
        </div>
        <div style="margin-top:1.2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.78rem;color:var(--pink-lt, #ff5caa);text-shadow:0 0 8px rgba(255,92,170,.3);">$12,000/quarter</span>
          <a href="product-redops.php" class="btn btn-green" style="padding:.5rem 1rem;font-size:.7rem;">VIEW &rsaquo;</a>
        </div>
      </div>

      <!-- 10 ComplianceShield -->
      <div class="prod-card" data-cat="COMPLIANCE">
        <div class="pc-top">
          <span class="pc-icon"><i class="fa-solid fa-shield-halved"></i></span>
          <span class="pc-tag green">COMPLIANCE</span>
        </div>
        <div class="pc-title">10. ComplianceShield</div>
        <p class="pc-desc">Automated compliance management for SOC 2, ISO 27001, HIPAA, GDPR, and PCI-DSS. We turn 6-month audit prep into a continuous background process.</p>
        <div class="pc-feats">
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>SOC 2, ISO 27001, HIPAA, GDPR</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Automated evidence collection</span>
          <span class="pc-feat"><i class="fa-solid fa-circle-check"></i>Auditor-ready dashboards</span>
        </div>
        <div style="margin-top:1.2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.78rem;color:var(--teal-lt, #00d4be);text-shadow:0 0 8px rgba(0,212,190,.3);">$2,100/mo</span>
          <a href="product-complianceshield.php" class="btn btn-green" style="padding:.5rem 1rem;font-size:.7rem;">VIEW &rsaquo;</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- CTA -->
<section style="padding-bottom:6rem;">
  <div class="container">
    <div class="cta-box reveal" data-code="SH">
      <div>
        <div class="section-tag">READY_TO_DEPLOY</div>
        <h2 style="color:var(--white);">READY TO PROTECT<br><span style="color:var(--green);">YOUR KINGDOM?</span></h2>
        <p>Not sure which product fits your threat model? Our engineers will scope the right combination for you in under 48 hours.</p>
      </div>
      <div class="cta-actions">
        <a href="contact.php" class="btn btn-filled">BEGIN SCOPE CALL</a>
        <a href="about.html"  class="btn btn-green">WHO WE ARE</a>
      </div>
    </div>
  </div>
</section>

<?php include '_footer.php'; ?>
<script>
// Search + category filter for product grid
function filterProducts() {
  const q   = document.getElementById('prodSearch').value.toLowerCase().trim();
  const cat = document.getElementById('catFilter').value;
  const cards = document.querySelectorAll('.prod-card[data-cat]');
  let visible = 0;

  cards.forEach(card => {
    const title = (card.querySelector('.pc-title')?.textContent || '').toLowerCase();
    const desc  = (card.querySelector('.pc-desc')?.textContent  || '').toLowerCase();
    const cardCat = card.dataset.cat || '';

    const matchQ   = !q   || title.includes(q) || desc.includes(q);
    const matchCat = !cat || cardCat === cat;

    if (matchQ && matchCat) {
      card.style.display = '';
      card.style.animation = 'none';
      card.offsetHeight; // reflow
      card.style.animation = '';
      visible++;
    } else {
      card.style.display = 'none';
    }
  });

  // Update count
  const num = document.getElementById('countNum');
  if (num) num.textContent = visible;

  // Show/hide no results
  const noRes = document.getElementById('noResults');
  if (noRes) noRes.style.display = visible === 0 ? 'block' : 'none';

  // Rebuild grid border (gap elements hidden means bg shows through)
  const grid = document.querySelector('.prod-grid');
  if (grid) {
    const shown = grid.querySelectorAll('.prod-card[data-cat]:not([style*="display: none"])');
    grid.style.background = shown.length > 0 ? 'var(--border)' : 'transparent';
  }
}

// Focus search wrap styling
const sw = document.getElementById('searchWrap');
const si = document.getElementById('prodSearch');
if (sw && si) {
  si.addEventListener('focus', () => sw.style.borderColor = 'var(--green)');
  si.addEventListener('blur',  () => sw.style.borderColor = 'var(--border)');
}
</script>
