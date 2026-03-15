<?php
require_once 'cookie_handler.php';

// Handle reset
if (isset($_GET['reset']) && $_GET['reset'] === '1') {
    reset_cookies();
    header('Location: most-visited.php?cleared=1');
    exit;
}

$top5    = get_most_visited(5);
$catalog = get_catalog();
$visits  = read_visits();
$active_page = 'products';

// Total visits across all products
$total_visits = array_sum($visits);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Most Visited Products — Paradox Systems</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23060611'/><text y='72' x='10' font-size='68' fill='%2300ff41' font-family='monospace'>_</text></svg>" />
  <style>
    .visit-bar-wrap { height:4px; background:rgba(255,255,255,.05); border-radius:2px; margin-top:.5rem; overflow:hidden; }
    .visit-bar      { height:100%; border-radius:2px; transition:width .8s ease; }
  </style>
</head>
<body>
<?php include '_nav.php'; ?>

<div class="page-hero" data-bg-text="TOP5">
  <div class="grid-bg"></div>
  <div class="container">
    <p class="eyebrow"><i class="fa-solid fa-fire"></i> COOKIE: paradox_visits</p>
    <h1 style="color:var(--white);">MOST <span style="color:var(--gold);">VISITED</span></h1>
    <p class="sub">Your top 5 most-visited products ranked by visit count — tracked entirely via browser cookie. Reset your cookie and the count starts over.</p>
  </div>
</div>

<section>
  <div class="container">

    <!-- Cookie data debug terminal -->
    <div class="terminal reveal" style="max-width:680px;margin-bottom:3rem;">
      <div class="terminal-bar">
        <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
        <span class="t-title">cookie_handler.php — get_most_visited(5)</span>
      </div>
      <div class="t-line"><span class="t-prompt">$</span><span class="t-cmd">cat /cookie/paradox_visits | sort -t: -k2 -rn | head -5</span></div>
      <?php if (!empty($visits)): ?>
        <?php foreach ($top5 as $slug => $cnt): ?>
          <?php if (isset($catalog[$slug])): ?>
          <div class="t-line">
            <span class="t-prompt"> </span>
            <span class="t-out" style="min-width:40px;"><?php echo str_pad($cnt, 3); ?>x</span>
            <span style="color:var(--gold);font-family:var(--font-mono);font-size:.77rem;">
              <?php echo htmlspecialchars($catalog[$slug]['name']); ?>
            </span>
          </div>
          <?php endif; ?>
        <?php endforeach; ?>
        <div class="t-line" style="margin-top:.5rem;">
          <span class="t-prompt"> </span>
          <span class="t-out">total visits tracked: <?php echo $total_visits; ?> &mdash; across <?php echo count($visits); ?> products</span>
        </div>
      <?php else: ?>
        <div class="t-line"><span class="t-prompt"> </span><span class="t-out">{} &mdash; empty. no visit data in cookie yet.</span></div>
      <?php endif; ?>
    </div>

    <?php if (empty($top5)): ?>
      <!-- Empty state -->
      <div style="text-align:center;padding:5rem 2rem;border:1px solid var(--border);background:var(--surface);">
        <i class="fa-solid fa-chart-bar" style="font-size:3rem;color:var(--text-dim);margin-bottom:1.2rem;display:block;"></i>
        <div style="font-family:var(--font-head);font-size:1.2rem;color:var(--white);margin-bottom:.7rem;">NO VISIT DATA YET</div>
        <p style="color:var(--text-dim);margin-bottom:1.8rem;">Visit some products to start populating your most-visited list.</p>
        <a href="products.php" class="btn btn-filled"><i class="fa-solid fa-arrow-left"></i> Browse Products</a>
      </div>
    <?php else: ?>

      <div class="section-tag reveal">TOP <?php echo count($top5); ?> &mdash; RANKED BY VISIT COUNT</div>
      <h2 class="reveal d1" style="color:var(--white);margin-bottom:2.5rem;">Your <span style="color:var(--gold);">Most Visited</span></h2>

      <?php
      $max_visits = max(array_values($top5));
      $rank_colors = ['var(--gold)','var(--text-bright)','var(--cyan)','var(--text)','var(--text-dim)'];
      $rank_icons  = ['fa-trophy','fa-medal','fa-award','fa-star','fa-circle'];
      $i = 0;
      ?>

      <div style="display:flex;flex-direction:column;gap:1px;background:var(--border);border:1px solid var(--border);" class="reveal d2">
        <?php foreach ($top5 as $slug => $count):
          $prod  = $catalog[$slug] ?? null;
          if (!$prod) continue;
          $cmap  = ['green'=>'var(--green)','cyan'=>'var(--cyan)','red'=>'var(--red)','gold'=>'var(--gold)'];
          $pcol  = $cmap[$prod['color']] ?? 'var(--green)';
          $rcol  = $rank_colors[$i]   ?? 'var(--text-dim)';
          $ricon = $rank_icons[$i]    ?? 'fa-circle';
          $pct   = $max_visits > 0 ? round(($count / $max_visits) * 100) : 0;
        ?>
        <div style="background:var(--bg2);padding:1.8rem 2rem;transition:background var(--trans);"
             onmouseover="this.style.background='var(--surface)'" onmouseout="this.style.background='var(--bg2)'">
          <div style="display:flex;align-items:center;gap:2rem;">

            <!-- Rank icon -->
            <div style="font-size:1.6rem;color:<?php echo $rcol; ?>;min-width:42px;text-align:center;">
              <i class="fa-solid <?php echo $ricon; ?>"></i>
            </div>

            <!-- Product icon -->
            <div style="font-size:1.6rem;color:<?php echo $pcol; ?>;text-shadow:0 0 16px <?php echo $pcol; ?>;min-width:42px;text-align:center;">
              <i class="fa-solid <?php echo $prod['icon']; ?>"></i>
            </div>

            <!-- Info + bar -->
            <div style="flex:1;min-width:0;">
              <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:.3rem;">
                <div style="font-family:var(--font-head);font-size:.9rem;color:var(--white);letter-spacing:.06em;text-transform:uppercase;">
                  <?php echo htmlspecialchars($prod['name']); ?>
                </div>
                <div style="font-family:var(--font-mono);font-size:1.3rem;color:<?php echo $rcol; ?>;text-shadow:0 0 12px <?php echo $rcol; ?>;">
                  <?php echo $count; ?><span style="font-size:.65rem;color:var(--text-dim);"> visits</span>
                </div>
              </div>
              <div style="display:flex;align-items:center;gap:1rem;margin-bottom:.5rem;">
                <span style="font-family:var(--font-mono);font-size:.62rem;color:<?php echo $pcol; ?>;border:1px solid <?php echo $pcol; ?>33;padding:.12rem .45rem;">
                  <?php echo htmlspecialchars($prod['cat']); ?>
                </span>
                <span style="font-family:var(--font-mono);font-size:.65rem;color:var(--text-dim);">
                  <?php echo htmlspecialchars($prod['price']); ?>
                </span>
              </div>
              <!-- Visit percentage bar -->
              <div class="visit-bar-wrap">
                <div class="visit-bar" style="width:<?php echo $pct; ?>%;background:<?php echo $rcol; ?>;box-shadow:0 0 6px <?php echo $rcol; ?>;"></div>
              </div>
            </div>

            <!-- CTA -->
            <a href="<?php echo $prod['url']; ?>" class="btn btn-green" style="padding:.6rem 1.4rem;font-size:.72rem;white-space:nowrap;flex-shrink:0;">
              VIEW <i class="fa-solid fa-arrow-right"></i>
            </a>

          </div>
        </div>
        <?php $i++; endforeach; ?>
      </div>

      <!-- All products visit summary -->
      <?php if (count($visits) > 0): ?>
      <div style="margin-top:2.5rem;" class="reveal">
        <div class="section-tag">ALL_VISIT_DATA</div>
        <div class="terminal">
          <div class="terminal-bar">
            <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
            <span class="t-title">paradox_visits — full cookie dump</span>
          </div>
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:.5rem;margin-top:.3rem;">
            <?php foreach ($visits as $slug => $cnt):
              $prod = $catalog[$slug] ?? null;
              if (!$prod) continue;
            ?>
            <div style="font-family:var(--font-mono);font-size:.72rem;color:var(--text-dim);display:flex;justify-content:space-between;padding:.3rem .4rem;border:1px solid var(--border);">
              <span style="color:var(--text);"><?php echo htmlspecialchars($prod['name']); ?></span>
              <span style="color:var(--gold);"><?php echo $cnt; ?>x</span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>

    <?php endif; ?>

    <div style="display:flex;gap:1rem;margin-top:2.5rem;flex-wrap:wrap;" class="reveal">
      <a href="products.php"         class="btn btn-filled"><i class="fa-solid fa-grid-2"></i> All Products</a>
      <a href="recently-visited.php" class="btn btn-green"><i class="fa-solid fa-clock-rotate-left"></i> Recently Visited</a>
      <?php if (!empty($visits)): ?>
      <a href="most-visited.php?reset=1"
         onclick="return confirm('Reset all visit counts?')"
         class="btn btn-red" style="margin-left:auto;">
        <i class="fa-solid fa-rotate-left"></i> Reset Counts
      </a>
      <?php endif; ?>
    </div>
    <?php if (isset($_GET['cleared'])): ?>
    <div style="margin-top:1rem;background:rgba(0,255,65,.05);border:1px solid rgba(0,255,65,.2);padding:.9rem 1rem;font-family:var(--font-mono);font-size:.78rem;color:var(--green);">
      <i class="fa-solid fa-circle-check"></i> Visit counts reset. Cookie cleared.
    </div>
    <?php endif; ?>

  </div>
</section>

<?php include '_footer.php'; ?>