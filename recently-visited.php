<?php
require_once 'cookie_handler.php';

// Handle reset
if (isset($_GET['reset']) && $_GET['reset'] === '1') {
    reset_cookies();
    header('Location: recently-visited.php?cleared=1');
    exit;
}

$recent  = read_recent();
$catalog = get_catalog();
$active_page = 'products';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recently Visited Products — Paradox Systems</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23060611'/><text y='72' x='10' font-size='68' fill='%2300ff41' font-family='monospace'>_</text></svg>" />
</head>
<body>
<?php include '_nav.php'; ?>

<div class="page-hero" data-bg-text="RECENT">
  <div class="grid-bg"></div>
  <div class="container">
    <p class="eyebrow"><i class="fa-solid fa-clock-rotate-left"></i> COOKIE: paradox_recent</p>
    <h1 style="color:var(--white);">RECENTLY <span style="color:var(--green);">VISITED</span></h1>
    <p class="sub">Your last <?php echo min(count($recent), 5); ?> viewed products — tracked via browser cookie. No server-side storage. No account required.</p>
  </div>
</div>

<section>
  <div class="container">

    <!-- How it works -->
    <div class="terminal reveal" style="max-width:680px;margin-bottom:3rem;">
      <div class="terminal-bar">
        <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
        <span class="t-title">cookie_handler.php — read_recent()</span>
      </div>
      <div class="t-line"><span class="t-prompt">$</span><span class="t-cmd">cat /cookie/paradox_recent</span></div>
      <div class="t-line"><span class="t-prompt"> </span>
        <span class="t-out"><?php echo htmlspecialchars(json_encode($recent)); ?></span>
      </div>
      <div class="t-line" style="margin-top:.5rem;"><span class="t-prompt"> </span>
        <span class="t-out"><?php echo count($recent); ?> product(s) in cookie &mdash; max 5 &mdash; newest first &mdash; expires 30 days</span>
      </div>
    </div>

    <?php if (empty($recent)): ?>
      <!-- Empty state -->
      <div style="text-align:center;padding:5rem 2rem;border:1px solid var(--border);background:var(--surface);">
        <i class="fa-solid fa-cookie-bite" style="font-size:3rem;color:var(--text-dim);margin-bottom:1.2rem;display:block;"></i>
        <div style="font-family:var(--font-head);font-size:1.2rem;color:var(--white);margin-bottom:.7rem;">NO COOKIE DATA YET</div>
        <p style="color:var(--text-dim);margin-bottom:1.8rem;">You have not visited any products in this session. Browse the catalog and come back.</p>
        <a href="products.php" class="btn btn-filled"><i class="fa-solid fa-arrow-left"></i> Browse Products</a>
      </div>
    <?php else: ?>

      <div class="section-tag reveal">LAST <?php echo count($recent); ?> VISITED &mdash; ORDERED NEWEST FIRST</div>
      <h2 class="reveal d1" style="color:var(--white);margin-bottom:2.5rem;">Your <span style="color:var(--green);">Recent History</span></h2>

      <div style="display:flex;flex-direction:column;gap:1px;background:var(--border);border:1px solid var(--border);" class="reveal d2">
        <?php foreach ($recent as $i => $slug):
          $prod = $catalog[$slug] ?? null;
          if (!$prod) continue;
          $cmap = ['green'=>'var(--green)','cyan'=>'var(--cyan)','red'=>'var(--red)','gold'=>'var(--gold)'];
          $col  = $cmap[$prod['color']] ?? 'var(--green)';
        ?>
        <div style="background:var(--bg2);padding:1.8rem 2rem;display:flex;align-items:center;gap:2rem;transition:background var(--trans);"
             onmouseover="this.style.background='var(--surface)'" onmouseout="this.style.background='var(--bg2)'">

          <!-- Rank number -->
          <div style="font-family:var(--font-mono);font-size:2.5rem;color:rgba(0,255,65,.08);font-weight:900;min-width:48px;line-height:1;">
            <?php echo str_pad($i+1, 2, '0', STR_PAD_LEFT); ?>
          </div>

          <!-- Icon -->
          <div style="font-size:1.8rem;color:<?php echo $col; ?>;text-shadow:0 0 16px <?php echo $col; ?>;min-width:50px;text-align:center;">
            <i class="fa-solid <?php echo $prod['icon']; ?>"></i>
          </div>

          <!-- Info -->
          <div style="flex:1;">
            <div style="font-family:var(--font-head);font-size:1rem;color:var(--white);letter-spacing:.06em;text-transform:uppercase;margin-bottom:.25rem;">
              <?php echo htmlspecialchars($prod['name']); ?>
            </div>
            <div style="display:flex;gap:1rem;flex-wrap:wrap;">
              <span style="font-family:var(--font-mono);font-size:.65rem;color:<?php echo $col; ?>;letter-spacing:.12em;border:1px solid;border-color:<?php echo $col; ?>33;background:<?php echo $col; ?>0d;padding:.15rem .5rem;">
                <?php echo htmlspecialchars($prod['cat']); ?>
              </span>
              <span style="font-family:var(--font-mono);font-size:.68rem;color:var(--text-dim);">
                <?php echo htmlspecialchars($prod['price']); ?>
              </span>
            </div>
          </div>

          <!-- CTA -->
          <a href="<?php echo $prod['url']; ?>" class="btn btn-green" style="padding:.6rem 1.4rem;font-size:.72rem;white-space:nowrap;">
            VIEW <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>

    <!-- Links -->
    <div style="display:flex;gap:1rem;margin-top:2.5rem;flex-wrap:wrap;" class="reveal">
      <a href="products.php"     class="btn btn-filled"><i class="fa-solid fa-grid-2"></i> All Products</a>
      <a href="most-visited.php" class="btn btn-green"><i class="fa-solid fa-fire"></i> Most Visited</a>
      <?php if (!empty($recent)): ?>
      <a href="recently-visited.php?reset=1"
         onclick="return confirm('Clear your recently visited history?')"
         class="btn btn-red" style="margin-left:auto;">
        <i class="fa-solid fa-trash"></i> Clear History
      </a>
      <?php endif; ?>
    </div>

    <?php if (isset($_GET['cleared'])): ?>
    <div style="margin-top:1rem;background:rgba(0,255,65,.05);border:1px solid rgba(0,255,65,.2);padding:.9rem 1rem;font-family:var(--font-mono);font-size:.78rem;color:var(--green);">
      <i class="fa-solid fa-circle-check"></i> Cookie cleared. Visit history has been reset.
    </div>
    <?php endif; ?>

  </div>
</section>

<?php include '_footer.php'; ?>