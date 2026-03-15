<?php
// ============================================================
// PARADOX SYSTEMS — product_layout.php
// Shared template for all product pages.
// Each product page sets $p array then includes this file.
//
// Required $p keys:
//   slug, name, category, cat_color, price, period,
//   tagline, image, description, features[], specs[],
//   who_for[], icon_fa
// ============================================================

// Must be included AFTER cookie logic so cookies are already set
// (cookie_handler.php is included by the product page first)

$active_page = 'products';

// Color map for category badges
$colors = [
    'green' => ['text' => 'var(--green)', 'border' => 'rgba(0,255,65,.3)',   'bg' => 'rgba(0,255,65,.05)'],
    'cyan'  => ['text' => 'var(--cyan)',  'border' => 'rgba(0,212,255,.3)',  'bg' => 'rgba(0,212,255,.05)'],
    'red'   => ['text' => 'var(--red)',   'border' => 'rgba(255,0,60,.3)',   'bg' => 'rgba(255,0,60,.05)'],
    'gold'  => ['text' => 'var(--gold)',  'border' => 'rgba(255,215,0,.3)',  'bg' => 'rgba(255,215,0,.05)'],
];
$c = $colors[$p['cat_color']] ?? $colors['green'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($p['name']); ?> — Paradox Systems</title>
  <meta name="description" content="<?php echo htmlspecialchars($p['tagline']); ?>" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23060611'/><text y='72' x='10' font-size='68' fill='%2300ff41' font-family='monospace'>_</text></svg>" />
  <style>
    .product-hero-img {
      position: absolute; inset: 0;
      background: linear-gradient(to right, var(--bg) 40%, rgba(6,6,17,.7) 100%),
                  url('<?php echo $p['image']; ?>') center/cover no-repeat;
      z-index: 0;
    }
    .product-detail { display:grid; grid-template-columns:1.1fr 1fr; gap:3rem; align-items:start; }
    .product-desc p { color:var(--text); margin-bottom:1rem; font-size:1.05rem; }
    .feat-list { display:flex; flex-direction:column; gap:.7rem; margin-top:1.2rem; }
    .feat-list li {
      display:flex; align-items:flex-start; gap:.7rem;
      font-size:.95rem; color:var(--text);
    }
    .feat-list li i { color:<?php echo $c['text']; ?>; margin-top:.25rem; font-size:.75rem; flex-shrink:0; }
    .spec-row { display:flex; justify-content:space-between; align-items:center;
                padding:.6rem 0; border-bottom:1px solid var(--border);
                font-family:var(--font-mono); font-size:.75rem; }
    .spec-row:last-child { border-bottom:none; }
    .spec-key { color:var(--text-dim); }
    .spec-val { color:<?php echo $c['text']; ?>; text-align:right; }
    .who-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:1px;
                background:var(--border); border:1px solid var(--border); margin-top:2rem; }
    .who-item { background:var(--bg2); padding:1.4rem; transition:background var(--trans); }
    .who-item:hover { background:var(--surface); }
    .who-item i { font-size:1.3rem; margin-bottom:.7rem; display:block; }
    .who-item h4 { font-family:var(--font-head); font-size:.8rem; color:var(--white); margin-bottom:.4rem; }
    .who-item p  { font-size:.82rem; color:var(--text-dim); }
    @media(max-width:900px) { .product-detail { grid-template-columns:1fr; } }
  </style>
</head>
<body>
<?php include '_nav.php'; ?>

<!-- PRODUCT HERO -->
<div class="page-hero" style="min-height:55vh;display:flex;align-items:flex-end;padding-bottom:3.5rem;">
  <div class="product-hero-img"></div>
  <div class="container" style="position:relative;z-index:1;">
    <div style="display:flex;align-items:center;gap:.8rem;margin-bottom:1rem;flex-wrap:wrap;">
      <a href="products.php" style="font-family:var(--font-mono);font-size:.7rem;color:var(--text-dim);letter-spacing:.12em;display:flex;align-items:center;gap:.3rem;">
        <i class="fa-solid fa-arrow-left" style="font-size:.6rem;"></i> ALL PRODUCTS
      </a>
      <span style="color:var(--text-dim);opacity:.3;">/</span>
      <span style="font-family:var(--font-mono);font-size:.7rem;
                   color:<?php echo $c['text']; ?>;letter-spacing:.12em;
                   border:1px solid <?php echo $c['border']; ?>;
                   background:<?php echo $c['bg']; ?>;
                   padding:.15rem .55rem;">
        <?php echo htmlspecialchars($p['category']); ?>
      </span>
    </div>
    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:.8rem;">
      <span style="font-size:2.2rem;color:<?php echo $c['text']; ?>;text-shadow:0 0 20px <?php echo $c['text']; ?>;">
        <i class="fa-solid <?php echo $p['icon_fa']; ?>"></i>
      </span>
      <h1 style="color:var(--white);"><?php echo htmlspecialchars($p['name']); ?></h1>
    </div>
    <p style="color:var(--text);max-width:580px;font-size:1.1rem;"><?php echo htmlspecialchars($p['tagline']); ?></p>
    <div style="margin-top:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
      <a href="contact.php" class="btn btn-filled">
        <i class="fa-solid fa-right-to-bracket"></i> Request Access
      </a>
      <span style="font-family:var(--font-mono);font-size:.9rem;color:<?php echo $c['text']; ?>;
                   text-shadow:0 0 16px <?php echo $c['text']; ?>;">
        <?php echo htmlspecialchars($p['price']); ?>
        <span style="font-size:.7rem;color:var(--text-dim);"> / <?php echo htmlspecialchars($p['period']); ?></span>
      </span>
    </div>
  </div>
</div>

<!-- PRODUCT DETAIL -->
<section>
  <div class="container">
    <div class="product-detail">

      <!-- Description + Features -->
      <div class="product-desc reveal">
        <div class="section-tag">OVERVIEW</div>
        <h2 style="color:var(--white);margin-bottom:1.2rem;"><?php echo htmlspecialchars($p['name']); ?></h2>
        <?php foreach ($p['description'] as $para): ?>
          <p><?php echo $para; ?></p>
        <?php endforeach; ?>
        <div class="section-tag" style="margin-top:2rem;">KEY CAPABILITIES</div>
        <ul class="feat-list">
          <?php foreach ($p['features'] as $f): ?>
            <li><i class="fa-solid fa-chevron-right"></i><?php echo $f; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Specs terminal -->
      <div class="reveal d2">
        <div class="terminal">
          <div class="terminal-bar">
            <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
            <span class="t-title"><?php echo strtolower(str_replace(' ','_', $p['name'])); ?>.specs</span>
          </div>
          <div class="t-line" style="margin-bottom:.8rem;">
            <span class="t-prompt">$</span>
            <span class="t-cmd">cat <?php echo htmlspecialchars($p['slug']); ?>/specs.json</span>
          </div>
          <?php foreach ($p['specs'] as $key => $val): ?>
            <div class="spec-row">
              <span class="spec-key"><?php echo htmlspecialchars($key); ?></span>
              <span class="spec-val"><?php echo htmlspecialchars($val); ?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- CTA card -->
        <div style="background:var(--surface2);border:1px solid <?php echo $c['border']; ?>;padding:1.8rem;margin-top:1.5rem;position:relative;overflow:hidden;">
          <div style="position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,<?php echo $c['text']; ?>,transparent);box-shadow:0 0 12px <?php echo $c['text']; ?>;"></div>
          <div style="font-family:var(--font-mono);font-size:.68rem;color:<?php echo $c['text']; ?>;letter-spacing:.16em;margin-bottom:.8rem;">PRICING</div>
          <div style="font-family:var(--font-mono);font-size:2rem;color:<?php echo $c['text']; ?>;text-shadow:0 0 20px <?php echo $c['text']; ?>;">
            <?php echo htmlspecialchars($p['price']); ?>
          </div>
          <div style="font-family:var(--font-mono);font-size:.7rem;color:var(--text-dim);margin-bottom:1.2rem;">
            per <?php echo htmlspecialchars($p['period']); ?>
          </div>
          <a href="contact.php" class="btn btn-filled" style="width:100%;justify-content:center;">
            <i class="fa-solid fa-envelope"></i> Get a Quote
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHO IT'S FOR -->
<section style="background:var(--surface);padding:4rem 0;">
  <div class="container">
    <div class="section-tag reveal">WHO_ITS_FOR</div>
    <h2 class="reveal d1" style="color:var(--white);">Built For <span style="color:<?php echo $c['text']; ?>;">These Teams</span></h2>
    <div class="who-grid reveal d2">
      <?php foreach ($p['who_for'] as $w): ?>
        <div class="who-item">
          <i class="fa-solid <?php echo $w['icon']; ?>" style="color:<?php echo $c['text']; ?>;"></i>
          <h4><?php echo htmlspecialchars($w['title']); ?></h4>
          <p><?php echo htmlspecialchars($w['desc']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section style="padding-bottom:6rem;">
  <div class="container">
    <div class="cta-box reveal" data-code=">>">
      <div>
        <div class="section-tag">NEXT_STEP</div>
        <h2 style="color:var(--white);">READY TO DEPLOY<br><span style="color:<?php echo $c['text']; ?>;"><?php echo strtoupper($p['name']); ?>?</span></h2>
        <p>Talk to an engineer. Most engagements are scoped within 48 hours of first contact.</p>
      </div>
      <div class="cta-actions">
        <a href="contact.php"  class="btn btn-filled">START NOW</a>
        <a href="products.php" class="btn btn-green">ALL PRODUCTS</a>
      </div>
    </div>
  </div>
</section>

<?php include '_footer.php'; ?>
