<?php
// Shared nav partial — include AFTER PHP logic block, before HTML body content
// Expects $active_page to be set (optional, defaults to 'products')
$active_page = $active_page ?? 'products';
?>
<div class="scanlines" aria-hidden="true"></div>
<canvas id="matrix-canvas" aria-hidden="true"></canvas>
<div class="cursor-dot"  id="cDot"></div>
<div class="cursor-ring" id="cRing"></div>

<nav id="navbar">
  <div class="container nav-wrap">
    <a href="index.html" class="nav-logo"><span class="br">[</span>PARADOX<span class="cur">_</span><span class="br">]</span></a>
    <button class="hamburger" id="ham" aria-label="Toggle nav" aria-expanded="false">
      <span class="ham-line"></span><span class="ham-line"></span><span class="ham-line"></span>
    </button>
    <ul class="nav-links" id="navLinks">
      <li><a href="index.html"    class="nav-link <?php echo $active_page==='home'    ?'active':''; ?>">Home</a></li>
      <li><a href="about.html"    class="nav-link <?php echo $active_page==='about'   ?'active':''; ?>">About</a></li>
      <li><a href="products.php"  class="nav-link <?php echo $active_page==='products'?'active':''; ?>">Services</a></li>
      <li><a href="news.html"     class="nav-link <?php echo $active_page==='news'    ?'active':''; ?>">News</a></li>
      <li><a href="contact.php"   class="nav-link <?php echo $active_page==='contact' ?'active':''; ?>">Contact</a></li>
      <li><a href="login.php" class="nav-link" style="color:var(--gold);border:1px solid rgba(255,215,0,.25);padding:.4rem .8rem;font-size:.64rem;"><i class="fa-solid fa-lock" style="margin-right:.3rem;font-size:.55rem;"></i>Admin</a></li>
    </ul>
    <div class="nav-status"><span class="status-dot"></span><span id="navStatus">SECURE_CONN</span></div>
  </div>
</nav>
<main>
