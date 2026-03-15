/* ============================================================
   PARADOX SYSTEMS — main.js
   ============================================================ */

/* ── Custom Cursor ── */
const dot  = document.getElementById('cDot');
const ring = document.getElementById('cRing');
let mx = 0, my = 0, rx = 0, ry = 0;
if (dot && ring) {
  document.addEventListener('mousemove', e => {
    mx = e.clientX; my = e.clientY;
    dot.style.left = mx + 'px'; dot.style.top = my + 'px';
  });
  const animR = () => {
    rx += (mx - rx) * 0.12; ry += (my - ry) * 0.12;
    ring.style.left = rx + 'px'; ring.style.top = ry + 'px';
    requestAnimationFrame(animR);
  };
  animR();
  document.querySelectorAll('a,button,input,select,textarea').forEach(el => {
    el.addEventListener('mouseenter', () => { ring.style.width = '48px'; ring.style.height = '48px'; });
    el.addEventListener('mouseleave', () => { ring.style.width = '28px'; ring.style.height = '28px'; });
  });
}

/* ── Matrix Rain ── */
const canvas = document.getElementById('matrix-canvas');
if (canvas) {
  const ctx = canvas.getContext('2d');
  const resize = () => { canvas.width = window.innerWidth; canvas.height = window.innerHeight; };
  resize();
  window.addEventListener('resize', resize);
  const CHARS = '01アイウエカキクサシスタチABCDEFGHIJKLMNOPQRSTUVWXYZ><{}[]#@$%'.split('');
  const FS = 13;
  let drops = [];
  const init = () => { drops = Array(Math.floor(canvas.width / FS)).fill(1); };
  init();
  window.addEventListener('resize', init);
  setInterval(() => {
    ctx.fillStyle = 'rgba(8,8,16,0.06)';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#00ff41';
    ctx.font = FS + 'px "Share Tech Mono",monospace';
    drops.forEach((y, i) => {
      ctx.fillText(CHARS[Math.floor(Math.random() * CHARS.length)], i * FS, y * FS);
      if (y * FS > canvas.height && Math.random() > 0.975) drops[i] = 0;
      drops[i]++;
    });
  }, 45);
}

/* ── Navbar scroll ── */
const nav = document.getElementById('navbar');
if (nav) {
  window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 60), { passive: true });
  nav.classList.toggle('scrolled', scrollY > 60);
}

/* ── Hamburger ── */
const ham = document.getElementById('ham');
const navLinks = document.getElementById('navLinks');
if (ham && navLinks) {
  ham.addEventListener('click', () => {
    const open = navLinks.classList.toggle('open');
    ham.setAttribute('aria-expanded', open);
  });
  navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', () => navLinks.classList.remove('open')));
}

/* ── Active nav link ── */
const page = location.pathname.split('/').pop() || 'index.html';
document.querySelectorAll('.nav-link').forEach(a => {
  const href = a.getAttribute('href') || '';
  if (href === page || (page === '' && href === 'index.html')) a.classList.add('active');
});

/* ── Scroll reveal ── */
const reveals = document.querySelectorAll('.reveal');
if (reveals.length) {
  const obs = new IntersectionObserver(
    entries => entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('show'); obs.unobserve(e.target); } }),
    { threshold: 0.1 }
  );
  reveals.forEach(el => obs.observe(el));
}

/* ── Animated counters ── */
function animateCount(el) {
  const target = parseFloat(el.dataset.n);
  const suffix = el.dataset.s || '';
  const duration = 1800;
  const start = performance.now();
  const isFloat = String(target).includes('.');
  const step = now => {
    const p = Math.min((now - start) / duration, 1);
    const ease = 1 - Math.pow(1 - p, 3);
    el.textContent = (isFloat ? (target * ease).toFixed(1) : Math.floor(target * ease)) + suffix;
    if (p < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}
document.querySelectorAll('[data-n]').forEach(el => {
  const obs = new IntersectionObserver(([e]) => { if (e.isIntersecting) { animateCount(el); obs.unobserve(el); } }, { threshold: 0.5 });
  obs.observe(el);
});

/* ── Hero terminal lines typewriter ── */
const termLines = document.querySelectorAll('.t-line[data-hidden]');
termLines.forEach((line, i) => {
  line.style.opacity = '0';
  setTimeout(() => {
    line.style.transition = 'opacity 0.4s';
    line.style.opacity = '1';
    line.removeAttribute('data-hidden');
  }, 1200 + i * 550);
});

/* ── Status rotator ── */
const statusEl = document.getElementById('navStatus');
if (statusEl) {
  const msgs = ['SECURE_CONN','TLS_1.3_OK','ENCRYPTING','0_INTRUSIONS','AI_ONLINE','NO_TRACE','ROOT_ACCESS'];
  let si = 0;
  setInterval(() => {
    si = (si + 1) % msgs.length;
    statusEl.style.opacity = '0';
    setTimeout(() => { statusEl.textContent = msgs[si]; statusEl.style.opacity = '1'; }, 150);
  }, 3000);
}

/* ── Uptime counter ── */
const uptimeEl = document.getElementById('uptime');
if (uptimeEl) {
  const epoch = new Date('2018-01-01T00:00:00').getTime();
  const tick = () => {
    const s = Math.floor((Date.now() - epoch) / 1000);
    const d = Math.floor(s / 86400);
    const h = String(Math.floor((s % 86400) / 3600)).padStart(2,'0');
    const m = String(Math.floor((s % 3600) / 60)).padStart(2,'0');
    const sec = String(s % 60).padStart(2,'0');
    uptimeEl.textContent = `UPTIME: ${d}d ${h}:${m}:${sec}`;
  };
  tick(); setInterval(tick, 1000);
}

/* ── Contact form (demo) ── */
const cf = document.getElementById('cf');
if (cf) {
  cf.addEventListener('submit', e => {
    e.preventDefault();
    const btn = cf.querySelector('[type=submit]');
    btn.textContent = 'ENCRYPTING...'; btn.disabled = true;
    setTimeout(() => {
      cf.style.display = 'none';
      const ok = document.getElementById('form-ok');
      if (ok) ok.style.display = 'block';
    }, 1500);
  });
}

/* ── News filter buttons (visual) ── */
document.querySelectorAll('.nf-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.nf-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  });
});

/* ── Konami Easter Egg ── */
const K = [38,38,40,40,37,39,37,39,66,65];
let ki = 0;
document.addEventListener('keydown', e => {
  ki = (e.keyCode === K[ki]) ? ki + 1 : 0;
  if (ki === K.length) {
    const ee = document.getElementById('easter');
    if (ee) { ee.style.color = 'rgba(0,255,65,0.6)'; setTimeout(() => ee.style.color = '', 4000); }
    ki = 0;
  }
});

/* ── Page fade ── */
document.querySelectorAll('a[href]').forEach(link => {
  const h = link.getAttribute('href') || '';
  if (link.hostname === location.hostname && !h.startsWith('#') && !link.target) {
    link.addEventListener('click', e => {
      e.preventDefault();
      document.body.style.cssText = 'opacity:0;transition:opacity 0.25s ease';
      setTimeout(() => location.href = link.href, 260);
    });
  }
});

window.addEventListener('load', () => {
  document.body.style.cssText = 'opacity:0;transition:none';
  requestAnimationFrame(() => { document.body.style.cssText = 'opacity:1;transition:opacity 0.45s ease'; });
});

/* ── Fix back/forward button black page ──────────────────────
   When the browser restores a page from its back-forward cache
   (bfcache), it snapshots the DOM exactly as it was — including
   opacity:0 from the fade-out. The 'pageshow' event fires on
   every bfcache restore (persisted === true), so we reset
   opacity back to 1 immediately.
   ─────────────────────────────────────────────────────────── */
window.addEventListener('pageshow', (e) => {
  if (e.persisted) {
    // Page was restored from bfcache — reset opacity immediately
    document.body.style.cssText = 'opacity:1;transition:none';
  }
});