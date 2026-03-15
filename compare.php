<?php
require_once 'cookie_handler.php';

$catalog     = get_catalog();
$active_page = 'products';

// Get selected products from query string (?a=slug&b=slug&c=slug)
$selected = [];
foreach (['a','b','c'] as $key) {
    $slug = $_GET[$key] ?? '';
    if ($slug && isset($catalog[$slug])) {
        $selected[$slug] = $catalog[$slug];
    }
}

// All spec keys used across products
$all_specs = [
    'Deployment', 'Duration', 'Engagement Duration', 'SLA', 'Uptime SLA',
    'Initial Response SLA', 'On-Call Coverage', 'Team Size', 'Report Delivery',
    'Methodology', 'Coverage', 'Frameworks', 'Standard',
    'Detection Latency', 'False Positive Rate', 'Model Updates',
    'Training Data', 'Alert Latency', 'Scan Frequency',
    'Re-test', 'Setup Time', 'Legal Admissibility',
];

// Build full product data for selected slugs (read from actual product files)
// We use the catalog for display and a static specs lookup
$product_specs = [
    'pentestpro'      => ['Duration'=>'2–4 weeks','Team Size'=>'2–4 engineers','Report Delivery'=>'48hrs','Re-test'=>'Free unlimited','Methodology'=>'PTES, OWASP, OSSTMM','Deployment'=>'On-site + Remote'],
    'oracle-ai'       => ['Detection Latency'=>'<100ms','False Positive Rate'=>'<0.003%','Model Updates'=>'Weekly','Uptime SLA'=>'99.97%','Deployment'=>'SaaS/On-prem/Hybrid','Coverage'=>'All users & devices'],
    'quantumvault'    => ['Standard'=>'NIST PQC Round 3','Migration Downtime'=>'0','Frameworks'=>'FIPS 140-3, CNSA 2.0','Deployment'=>'SaaS API/SDK/On-prem','Coverage'=>'At rest + in transit'],
    'darkscan'        => ['Coverage'=>'40,000+ sources','Alert Latency'=>'<15 minutes','Takedown SLA'=>'72hrs avg','Deployment'=>'SaaS','Frameworks'=>'Dark web, deep web, Telegram'],
    'zerodayresponse' => ['Initial Response SLA'=>'4 minutes','On-Call Coverage'=>'24/7/365','Re-test'=>'N/A','Report Delivery'=>'Within 1hr','Deployment'=>'Retainer','Frameworks'=>'GDPR, HIPAA, SEC, PCI'],
    'forensicslab'    => ['Duration'=>'Per engagement','Report Delivery'=>'5 business days','Legal Admissibility'=>'Court-ready','Deployment'=>'On-site','Coverage'=>'Win, macOS, Linux, iOS, Android'],
    'phishguard'      => ['Coverage'=>'Email, SMS, Vishing, QR','Deployment'=>'SaaS','Model Updates'=>'Monthly templates','Frameworks'=>'SCIM/SSO, LMS','Alert Latency'=>'Instant on click'],
    'cloudfortress'   => ['Scan Frequency'=>'Continuous (5min)','Deployment'=>'SaaS','Frameworks'=>'CIS, NIST, PCI, HIPAA','Alert Latency'=>'<5 minutes','Coverage'=>'AWS, Azure, GCP'],
    'redops'          => ['On-Call Coverage'=>'Continuous','Team Size'=>'3–6 operators','Methodology'=>'MITRE ATT&CK full','Coverage'=>'Digital, Physical, Social','Report Delivery'=>'Purple team debrief'],
    'complianceshield'=> ['Frameworks'=>'SOC2, ISO27001, HIPAA, GDPR, PCI','Deployment'=>'SaaS','Alert Latency'=>'Continuous drift alerts','Coverage'=>'100+ integrations','Setup Time'=>'~2 weeks'],
];

$colors = [
    'green' => ['text' => 'var(--green)', 'border' => 'rgba(0,255,65,.3)',   'bg' => 'rgba(0,255,65,.05)'],
    'cyan'  => ['text' => 'var(--cyan)',  'border' => 'rgba(0,212,255,.3)',  'bg' => 'rgba(0,212,255,.05)'],
    'red'   => ['text' => 'var(--red)',   'border' => 'rgba(255,0,60,.3)',   'bg' => 'rgba(255,0,60,.05)'],
    'gold'  => ['text' => 'var(--gold)',  'border' => 'rgba(255,215,0,.3)',  'bg' => 'rgba(255,215,0,.05)'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Compare Products — Paradox Systems</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23060611'/><text y='72' x='10' font-size='68' fill='%2300ff41' font-family='monospace'>_</text></svg>" />
  <style>
    .compare-table { width:100%; border-collapse:collapse; }
    .compare-table th, .compare-table td {
      padding:1rem 1.2rem; text-align:left;
      border-bottom:1px solid var(--border);
      font-family:var(--font-mono); font-size:.77rem;
    }
    .compare-table th { background:var(--surface); font-family:var(--font-head); font-size:.68rem; letter-spacing:.1em; color:var(--text-dim); text-transform:uppercase; }
    .compare-table td { background:var(--bg2); color:var(--text-dim); }
    .compare-table tbody tr:hover td { background:var(--surface); }
    .compare-table .row-label { color:var(--text); font-family:var(--font-mono); font-size:.72rem; background:var(--surface2) !important; }
    .prod-select { display:flex; gap:.6rem; flex-wrap:wrap; margin-bottom:2rem; }
    .prod-btn { font-family:var(--font-mono); font-size:.65rem; letter-spacing:.1em; text-transform:uppercase;
                padding:.4rem 1rem; border:1px solid var(--border); background:var(--bg2);
                color:var(--text-dim); cursor:pointer; transition:.3s; text-decoration:none;
                display:inline-flex; align-items:center; gap:.35rem; }
    .prod-btn:hover { border-color:var(--green); color:var(--green); background:rgba(0,255,65,.04); }
    .prod-btn.selected { border-color:var(--green); color:var(--green); background:rgba(0,255,65,.08); }
    .prod-header { text-align:center; }
    .prod-header .ph-icon { font-size:2rem; display:block; margin-bottom:.6rem; }
    .prod-header .ph-name { font-family:var(--font-head); font-size:.85rem; color:var(--white); letter-spacing:.06em; text-transform:uppercase; }
    .prod-header .ph-price { font-family:var(--font-mono); font-size:.85rem; margin-top:.3rem; }
    .prod-header .ph-cat { font-family:var(--font-mono); font-size:.6rem; letter-spacing:.1em; padding:.15rem .5rem; border:1px solid; display:inline-block; margin-top:.4rem; }
    .compare-val-yes { color:var(--green); }
    .compare-val-no  { color:var(--text-dim); opacity:.4; }
    @media(max-width:768px) { .compare-table th:nth-child(n+4), .compare-table td:nth-child(n+4) { display:none; } }
  </style>
</head>
<body>
<?php include '_nav.php'; ?>

<div class="page-hero" data-bg-text="COMPARE">
  <div class="grid-bg"></div>
  <div class="container">
    <p class="eyebrow"><i class="fa-solid fa-code-compare"></i> PRODUCT COMPARISON</p>
    <h1 style="color:var(--white);">COMPARE <span style="color:var(--cyan);">PRODUCTS</span></h1>
    <p class="sub">Select up to 3 products to compare side by side. Find the right fit for your threat model.</p>
  </div>
</div>

<section>
  <div class="container">

    <!-- Product selector -->
    <div class="section-tag reveal">SELECT_PRODUCTS — PICK UP TO 3</div>

    <?php
    // Build current selection array for URLs
    $cur_a = array_key_first($selected) ?? '';
    $cur_b = array_values($selected)[1] ?? '';
    $cur_c = array_values($selected)[2] ?? '';
    ?>

    <form method="GET" action="compare.php" style="margin-bottom:2.5rem;" class="reveal d1">
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.2rem;">
        <?php foreach (['a'=>'Product A','b'=>'Product B','c'=>'Product C'] as $key => $label):
          $curval = $_GET[$key] ?? '';
        ?>
        <div>
          <label style="font-family:var(--font-mono);font-size:.65rem;color:var(--green);letter-spacing:.14em;display:block;margin-bottom:.4rem;">
            <i class="fa-solid fa-<?php echo $key==='a'?'1':'($key===\'b\'?\'2\':\'3\')'; ?>"></i> <?php echo $label; ?>
          </label>
          <select name="<?php echo $key; ?>" style="width:100%;background:var(--bg2);border:1px solid var(--border);color:var(--text);font-family:var(--font-mono);font-size:.78rem;padding:.65rem .9rem;outline:none;cursor:pointer;">
            <option value="">-- None --</option>
            <?php foreach ($catalog as $slug => $prod): ?>
            <option value="<?php echo $slug; ?>" <?php echo $curval===$slug?'selected':''; ?>>
              <?php echo htmlspecialchars($prod['name']); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="submit" class="btn btn-filled">
        <i class="fa-solid fa-code-compare"></i> Compare Selected
      </button>
      <?php if (!empty($selected)): ?>
      <a href="compare.php" class="btn btn-ghost" style="margin-left:.5rem;">
        <i class="fa-solid fa-xmark"></i> Clear
      </a>
      <?php endif; ?>
    </form>

    <?php if (empty($selected)): ?>
    <!-- Empty state -->
    <div style="text-align:center;padding:5rem 2rem;border:1px solid var(--border);background:var(--surface);">
      <i class="fa-solid fa-code-compare" style="font-size:3rem;color:var(--text-dim);margin-bottom:1.2rem;display:block;opacity:.4;"></i>
      <div style="font-family:var(--font-head);font-size:1.1rem;color:var(--white);margin-bottom:.7rem;">SELECT PRODUCTS TO COMPARE</div>
      <p style="color:var(--text-dim);margin-bottom:1.8rem;">Choose at least 2 products from the dropdowns above and click Compare.</p>
      <a href="products.php" class="btn btn-green"><i class="fa-solid fa-arrow-left"></i> Browse Products</a>
    </div>

    <?php elseif (count($selected) < 2): ?>
    <div style="padding:2rem;border:1px solid rgba(255,215,0,.3);background:rgba(255,215,0,.04);font-family:var(--font-mono);font-size:.8rem;color:var(--gold);">
      <i class="fa-solid fa-triangle-exclamation"></i> Select at least 2 products to compare.
    </div>

    <?php else: ?>
    <!-- COMPARISON TABLE -->
    <div style="overflow-x:auto;border:1px solid var(--border);" class="reveal d2">
      <table class="compare-table">
        <thead>
          <tr>
            <th style="min-width:160px;">SPECIFICATION</th>
            <?php foreach ($selected as $slug => $prod):
              $c = $colors[$prod['color']] ?? $colors['green'];
            ?>
            <th class="prod-header">
              <span class="ph-icon" style="color:<?php echo $c['text']; ?>;text-shadow:0 0 16px <?php echo $c['text']; ?>;">
                <i class="fa-solid <?php echo $prod['icon']; ?>"></i>
              </span>
              <div class="ph-name"><?php echo htmlspecialchars($prod['name']); ?></div>
              <div class="ph-price" style="color:<?php echo $c['text']; ?>;"><?php echo htmlspecialchars($prod['price']); ?></div>
              <span class="ph-cat" style="color:<?php echo $c['text']; ?>;border-color:<?php echo $c['border']; ?>;background:<?php echo $c['bg']; ?>;">
                <?php echo htmlspecialchars($prod['cat']); ?>
              </span>
            </th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>

          <!-- Price row -->
          <tr>
            <td class="row-label"><i class="fa-solid fa-tag" style="color:var(--gold);margin-right:.4rem;font-size:.65rem;"></i>PRICE</td>
            <?php foreach ($selected as $slug => $prod): ?>
            <td style="color:<?php echo ($colors[$prod['color']]['text'] ?? 'var(--green)'); ?>;">
              <?php echo htmlspecialchars($prod['price']); ?>
            </td>
            <?php endforeach; ?>
          </tr>

          <!-- Category row -->
          <tr>
            <td class="row-label"><i class="fa-solid fa-tag" style="color:var(--cyan);margin-right:.4rem;font-size:.65rem;"></i>CATEGORY</td>
            <?php foreach ($selected as $slug => $prod): ?>
            <td><?php echo htmlspecialchars($prod['cat']); ?></td>
            <?php endforeach; ?>
          </tr>

          <!-- Spec rows -->
          <?php
          // Collect all spec keys across selected products
          $all_keys = [];
          foreach ($selected as $slug => $prod) {
              $specs = $product_specs[$slug] ?? [];
              foreach (array_keys($specs) as $k) {
                  if (!in_array($k, $all_keys)) $all_keys[] = $k;
              }
          }
          foreach ($all_keys as $spec_key):
          ?>
          <tr>
            <td class="row-label">
              <i class="fa-solid fa-chevron-right" style="color:var(--green);margin-right:.4rem;font-size:.55rem;"></i>
              <?php echo htmlspecialchars(strtoupper($spec_key)); ?>
            </td>
            <?php foreach ($selected as $slug => $prod):
              $val = $product_specs[$slug][$spec_key] ?? '—';
              $isNA = $val === '—';
            ?>
            <td class="<?php echo $isNA?'compare-val-no':'compare-val-yes'; ?>">
              <?php echo htmlspecialchars($val); ?>
            </td>
            <?php endforeach; ?>
          </tr>
          <?php endforeach; ?>

          <!-- View product links row -->
          <tr>
            <td class="row-label"><i class="fa-solid fa-arrow-up-right-from-square" style="color:var(--green);margin-right:.4rem;font-size:.65rem;"></i>FULL DETAILS</td>
            <?php foreach ($selected as $slug => $prod):
              $c = $colors[$prod['color']] ?? $colors['green'];
            ?>
            <td>
              <a href="<?php echo $prod['url']; ?>" style="color:<?php echo $c['text']; ?>;font-family:var(--font-mono);font-size:.7rem;letter-spacing:.08em;display:inline-flex;align-items:center;gap:.3rem;">
                VIEW PAGE <i class="fa-solid fa-arrow-right" style="font-size:.55rem;"></i>
              </a>
            </td>
            <?php endforeach; ?>
          </tr>

        </tbody>
      </table>
    </div>

    <div style="display:flex;gap:1rem;margin-top:2rem;" class="reveal">
      <a href="products.php" class="btn btn-filled"><i class="fa-solid fa-grid-2"></i> All Products</a>
      <a href="compare.php"  class="btn btn-ghost"><i class="fa-solid fa-rotate-left"></i> Reset Comparison</a>
    </div>

    <?php endif; ?>

  </div>
</section>

<?php include '_footer.php'; ?>