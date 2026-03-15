<?php
// ============================================================
// PARADOX SYSTEMS — product_layout.php
// Shared template for all product pages.
// Includes: breadcrumbs, product number, you might also like
// ============================================================

$active_page  = 'products';
$prod_num     = get_product_number($p['slug']);
$prod_total   = get_product_count();
$related      = get_related($p['slug'], 3);

// Color map
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
      background:
        linear-gradient(to right, var(--bg) 35%, rgba(5,5,15,0.5) 100%),
        linear-gradient(to top, rgba(5,5,15,0.8) 0%, transparent 40%),
        url('<?php echo $p['image']; ?>') center/cover no-repeat;
      z-index: 0;
    }
    /* Category-specific tint overlay */
    .product-hero-img::after {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at 80% 50%, <?php echo $c['bg']; ?> 0%, transparent 60%);
      pointer-events: none;
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
    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1.2rem;flex-wrap:wrap;font-family:var(--font-mono);font-size:.68rem;">
      <!-- Breadcrumbs -->
      <a href="index.html" style="color:var(--text-dim);display:flex;align-items:center;gap:.3rem;">
        <i class="fa-solid fa-house" style="font-size:.55rem;"></i> Home
      </a>
      <span style="color:var(--text-dim);opacity:.3;">/</span>
      <a href="products.php" style="color:var(--text-dim);">Products</a>
      <span style="color:var(--text-dim);opacity:.3;">/</span>
      <span style="color:<?php echo $c['text']; ?>;"><?php echo htmlspecialchars($p['name']); ?></span>

      <!-- Separator -->
      <span style="margin-left:auto;display:flex;align-items:center;gap:.8rem;">
        <!-- Product number badge -->
        <span style="color:var(--text-dim);border:1px solid var(--border);padding:.15rem .6rem;letter-spacing:.1em;">
          PRODUCT <?php echo str_pad($prod_num, 2, '0', STR_PAD_LEFT); ?> / <?php echo str_pad($prod_total, 2, '0', STR_PAD_LEFT); ?>
        </span>
        <!-- Compare link -->
        <a href="compare.php?a=<?php echo urlencode($p['slug']); ?>"
           style="color:var(--gold);border:1px solid rgba(255,215,0,.25);background:rgba(255,215,0,.04);padding:.15rem .7rem;display:flex;align-items:center;gap:.3rem;">
          <i class="fa-solid fa-code-compare" style="font-size:.55rem;"></i> Compare
        </a>
      </span>
    </div>

    <!-- Category badge -->
    <div style="margin-bottom:.8rem;">
      <span style="font-family:var(--font-mono);font-size:.65rem;
                   color:<?php echo $c['text']; ?>;letter-spacing:.12em;
                   border:1px solid <?php echo $c['border']; ?>;
                   background:<?php echo $c['bg']; ?>;
                   padding:.2rem .65rem;">
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

<!-- YOU MIGHT ALSO LIKE -->
<?php if (!empty($related)): ?>
<section style="background:var(--bg2);padding:4rem 0;border-top:1px solid var(--border);">
  <div class="container">
    <div class="section-tag reveal">YOU_MIGHT_ALSO_LIKE</div>
    <h2 class="reveal d1" style="color:var(--white);margin-bottom:2.5rem;">
      Related <span style="color:<?php echo $c['text']; ?>;">Products</span>
    </h2>
    <div style="display:grid;grid-template-columns:repeat(<?php echo count($related); ?>,1fr);gap:1px;background:var(--border);border:1px solid var(--border);" class="reveal d2">
      <?php foreach ($related as $rslug => $rprod):
        $rc = $colors[$rprod['color']] ?? $colors['green'];
        $rnum = get_product_number($rslug);
      ?>
      <div style="background:var(--bg2);padding:2rem;transition:background .3s,transform .3s;position:relative;overflow:hidden;"
           onmouseover="this.style.background='var(--surface)';this.style.transform='translateY(-3px)'"
           onmouseout="this.style.background='var(--bg2)';this.style.transform='translateY(0)'">
        <!-- Top glow on hover via inline doesn't work well, using a pseudo instead with a border -->
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
          <span style="font-size:1.8rem;color:<?php echo $rc['text']; ?>;text-shadow:0 0 16px <?php echo $rc['text']; ?>;">
            <i class="fa-solid <?php echo $rprod['icon']; ?>"></i>
          </span>
          <span style="font-family:var(--font-mono);font-size:.62rem;color:<?php echo $rc['text']; ?>;border:1px solid <?php echo $rc['border']; ?>;background:<?php echo $rc['bg']; ?>;padding:.15rem .5rem;">
            <?php echo htmlspecialchars($rprod['cat']); ?>
          </span>
        </div>
        <div style="font-family:var(--font-head);font-size:.9rem;color:var(--white);letter-spacing:.06em;text-transform:uppercase;margin-bottom:.4rem;">
          <?php echo htmlspecialchars($rprod['name']); ?>
        </div>
        <div style="font-family:var(--font-mono);font-size:.72rem;color:<?php echo $rc['text']; ?>;margin-bottom:1.2rem;">
          <?php echo htmlspecialchars($rprod['price']); ?>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--border);">
          <span style="font-family:var(--font-mono);font-size:.62rem;color:var(--text-dim);">
            PRODUCT <?php echo str_pad($rnum,2,'0',STR_PAD_LEFT); ?>
          </span>
          <a href="<?php echo $rprod['url']; ?>" style="font-family:var(--font-mono);font-size:.7rem;color:<?php echo $rc['text']; ?>;letter-spacing:.1em;display:flex;align-items:center;gap:.3rem;text-transform:uppercase;">
            VIEW <i class="fa-solid fa-arrow-right" style="font-size:.6rem;"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

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
