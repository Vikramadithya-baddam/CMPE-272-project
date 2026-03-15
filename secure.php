<!DOCTYPE html>
<?php
// ============================================================
// PARADOX SYSTEMS — secure.php
// Protected admin dashboard — requires active session.
// Reads registered users from data/users.txt
// ============================================================
session_start();

// ── Session guard ─────────────────────────────────────────────
// If not authenticated, boot to login page immediately.
if (!isset($_SESSION['paradox_admin']) || $_SESSION['paradox_admin'] !== true) {
    header('Location: login.php');
    exit;
}

// ── Session timeout (30 minutes of inactivity) ────────────────
$timeout = 1800; // seconds
if (isset($_SESSION['paradox_login_ts']) && (time() - $_SESSION['paradox_login_ts']) > $timeout) {
    session_destroy();
    header('Location: login.php?reason=timeout');
    exit;
}
// Refresh activity timestamp
$_SESSION['paradox_login_ts'] = time();

// ── Read users from data/users.txt ───────────────────────────
function load_users($filepath) {
    if (!file_exists($filepath)) return [];

    $content = file_get_contents($filepath);
    $blocks  = explode('---', $content);
    $users   = [];

    foreach ($blocks as $block) {
        $block = trim($block);
        if (empty($block)) continue;

        $u = [];
        foreach (explode("\n", $block) as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            $pos = strpos($line, '=');
            if ($pos !== false) {
                $key = trim(substr($line, 0, $pos));
                $val = trim(substr($line, $pos + 1));
                $u[$key] = $val;
            }
        }
        if (!empty($u['name'])) $users[] = $u;
    }
    return $users;
}

$users_file = dirname(__FILE__) . '/data/users.txt';
if (!file_exists($users_file)) {
    $users_file = $_SERVER['DOCUMENT_ROOT'] . '/data/users.txt';
}
$users = load_users($users_file);

// ── Stats ─────────────────────────────────────────────────────
$total    = count($users);
$active   = count(array_filter($users, fn($u) => ($u['status'] ?? '') === 'Active'));
$inactive = $total - $active;
$by_role  = array_count_values(array_column($users, 'role'));

$admin_name = $_SESSION['paradox_user'] ?? 'admin';
$login_time = date('Y-m-d H:i:s', $_SESSION['paradox_login_ts'] ?? time());
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Secure Admin — Paradox Systems</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23060611'/><text y='72' x='10' font-size='68' fill='%2300ff41' font-family='monospace'>_</text></svg>" />
  <style>
    /* ── Secure section specific styles ── */

    /* Admin navbar override */
    .admin-bar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
      background: rgba(6,6,17,.97);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255,215,0,.2);
      padding: .75rem 0;
      box-shadow: 0 2px 20px rgba(0,0,0,.5), 0 1px 0 rgba(255,215,0,.15);
    }
    .admin-bar-inner {
      display: flex; align-items: center; justify-content: space-between;
      gap: 1.5rem;
    }
    .admin-logo {
      font-family: var(--font-head); font-size: 1.2rem;
      font-weight: 900; color: var(--white); letter-spacing: .08em;
      text-decoration: none;
    }
    .admin-logo .br  { color: var(--gold); }
    .admin-logo .cur { color: var(--gold); animation: blink 1s step-end infinite; }

    .admin-badge {
      font-family: var(--font-mono); font-size: .65rem;
      color: var(--gold); border: 1px solid rgba(255,215,0,.3);
      background: rgba(255,215,0,.06);
      padding: .25rem .8rem; letter-spacing: .14em; text-transform: uppercase;
      display: flex; align-items: center; gap: .4rem;
    }
    .admin-badge i { font-size: .6rem; }

    .admin-session {
      font-family: var(--font-mono); font-size: .65rem; color: var(--text-dim);
      display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
    }
    .admin-session .s-item {
      display: flex; align-items: center; gap: .3rem;
    }
    .admin-session i { font-size: .6rem; color: var(--gold); opacity: .7; }

    .logout-btn {
      font-family: var(--font-mono); font-size: .68rem;
      letter-spacing: .12em; text-transform: uppercase;
      padding: .4rem 1rem; color: var(--red);
      border: 1px solid rgba(255,0,60,.3);
      background: rgba(255,0,60,.06);
      cursor: pointer; transition: all .3s;
      display: flex; align-items: center; gap: .4rem;
      text-decoration: none;
    }
    .logout-btn:hover {
      background: rgba(255,0,60,.14);
      border-color: var(--red);
      box-shadow: 0 0 12px rgba(255,0,60,.2);
    }
    .logout-btn i { font-size: .6rem; }

    /* Page layout */
    .secure-page { padding-top: calc(var(--navbar-h) + 1rem); }

    /* Hero banner */
    .secure-hero {
      background: linear-gradient(135deg, var(--surface2) 0%, var(--bg2) 100%);
      border-bottom: 1px solid rgba(255,215,0,.15);
      padding: 2.5rem 0;
      position: relative; overflow: hidden;
    }
    .secure-hero::before {
      content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
      background: linear-gradient(90deg, transparent, var(--gold), var(--green), transparent);
      box-shadow: 0 0 20px var(--gold);
    }
    .secure-hero::after {
      content: '\f023';
      font-family: 'Font Awesome 6 Free'; font-weight: 900;
      position: absolute; right: 3rem; bottom: -1rem;
      font-size: 8rem; color: transparent;
      -webkit-text-stroke: 1px rgba(255,215,0,.05);
      pointer-events: none;
    }
    .secure-hero-inner {
      display: flex; align-items: center; justify-content: space-between;
      gap: 2rem; flex-wrap: wrap;
    }
    .sh-title {
      font-family: var(--font-head); font-size: clamp(1.4rem, 3vw, 2.2rem);
      color: var(--white); font-weight: 900; letter-spacing: .05em;
      text-transform: uppercase; margin-bottom: .4rem;
    }
    .sh-title span { color: var(--gold); }
    .sh-sub {
      font-family: var(--font-mono); font-size: .75rem;
      color: var(--text-dim); letter-spacing: .1em;
      display: flex; align-items: center; gap: .5rem;
    }
    .sh-sub i { color: var(--gold); font-size: .65rem; }

    /* Stats row */
    .stats-row {
      display: grid; grid-template-columns: repeat(4, 1fr);
      gap: 1px; background: rgba(255,215,0,.12);
      border: 1px solid rgba(255,215,0,.15);
      margin: 2rem 0;
    }
    .stat-box {
      background: var(--surface2); padding: 1.4rem; text-align: center;
      transition: background .3s;
    }
    .stat-box:hover { background: var(--surface); }
    .stat-box .s-num {
      font-family: var(--font-mono); font-size: 2rem; font-weight: 400;
      display: block; line-height: 1;
    }
    .stat-box .s-lbl {
      font-family: var(--font-mono); font-size: .65rem;
      color: var(--text-dim); letter-spacing: .12em;
      text-transform: uppercase; margin-top: .35rem;
    }
    .stat-box .s-icon {
      font-size: .75rem; margin-bottom: .5rem; display: block; opacity: .6;
    }

    /* Search + filter bar */
    .table-controls {
      display: flex; align-items: center; justify-content: space-between;
      gap: 1rem; flex-wrap: wrap; margin-bottom: 1.2rem;
    }
    .search-wrap {
      display: flex; align-items: center; gap: .3rem;
      background: var(--bg2); border: 1px solid var(--border);
      padding: .5rem .9rem; min-width: 260px;
      transition: border-color .3s, box-shadow .3s;
    }
    .search-wrap:focus-within {
      border-color: var(--green);
      box-shadow: 0 0 0 2px rgba(0,255,65,.08);
    }
    .search-wrap i { color: var(--text-dim); font-size: .75rem; }
    .search-wrap input {
      flex: 1; background: transparent; border: none; outline: none;
      font-family: var(--font-mono); font-size: .78rem; color: var(--text);
    }
    .search-wrap input::placeholder { color: var(--text-dim); font-style: italic; }

    .filter-select {
      font-family: var(--font-mono); font-size: .72rem; color: var(--text);
      background: var(--bg2); border: 1px solid var(--border);
      padding: .5rem .9rem; outline: none; cursor: pointer;
      transition: border-color .3s;
    }
    .filter-select:focus { border-color: var(--green); }
    .filter-select option { background: var(--bg2); }

    .table-count {
      font-family: var(--font-mono); font-size: .7rem; color: var(--text-dim);
      letter-spacing: .08em;
    }
    .table-count span { color: var(--green); }

    /* Users table */
    .users-table-wrap {
      border: 1px solid var(--border);
      overflow-x: auto;
    }
    table {
      width: 100%; border-collapse: collapse;
      font-family: var(--font-mono); font-size: .78rem;
    }
    thead tr {
      background: var(--surface);
      border-bottom: 1px solid rgba(255,215,0,.15);
    }
    thead th {
      padding: .9rem 1.2rem; text-align: left;
      font-family: var(--font-head); font-size: .62rem;
      color: var(--gold); letter-spacing: .14em; text-transform: uppercase;
      white-space: nowrap; font-weight: 700;
    }
    thead th i { margin-right: .4rem; opacity: .7; }
    tbody tr {
      border-bottom: 1px solid var(--border);
      transition: background .2s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--surface); }
    tbody td {
      padding: .85rem 1.2rem; color: var(--text-dim);
      vertical-align: middle;
    }
    td.td-id {
      color: var(--text-dim); font-size: .7rem; width: 50px;
    }
    td.td-name { color: var(--white); font-weight: 600; }
    td.td-email a {
      color: var(--cyan); transition: text-shadow .2s;
    }
    td.td-email a:hover { text-shadow: 0 0 8px rgba(0,212,255,.4); }

    /* Role badges */
    .role-badge {
      display: inline-flex; align-items: center; gap: .3rem;
      font-size: .62rem; letter-spacing: .1em; text-transform: uppercase;
      padding: .18rem .6rem; border: 1px solid;
    }
    .role-badge i { font-size: .5rem; }
    .role-badge.standard  { color: var(--text);  border-color: rgba(255,255,255,.12); }
    .role-badge.premium   { color: var(--cyan);  border-color: rgba(0,212,255,.3); background: rgba(0,212,255,.05); }
    .role-badge.enterprise{ color: var(--gold);  border-color: rgba(255,215,0,.3); background: rgba(255,215,0,.05); }

    /* Status badges */
    .status-badge {
      display: inline-flex; align-items: center; gap: .3rem;
      font-size: .62rem; letter-spacing: .1em; text-transform: uppercase;
      padding: .18rem .6rem; border: 1px solid;
    }
    .status-badge i { font-size: .45rem; }
    .status-badge.active   { color: var(--green); border-color: rgba(0,255,65,.3); background: rgba(0,255,65,.05); }
    .status-badge.inactive { color: var(--red);   border-color: rgba(255,0,60,.3);  background: rgba(255,0,60,.05); }

    /* Empty state */
    .empty-state {
      text-align: center; padding: 4rem 2rem;
      font-family: var(--font-mono); font-size: .8rem; color: var(--text-dim);
    }
    .empty-state i { font-size: 2rem; color: var(--gold); margin-bottom: 1rem; display: block; opacity: .5; }

    /* Access log terminal */
    .access-log {
      background: var(--bg2); border: 1px solid var(--border);
      padding: 1.4rem; font-family: var(--font-mono); font-size: .75rem;
      margin-top: 2rem;
    }
    .access-log .log-header {
      font-size: .65rem; color: var(--gold); letter-spacing: .16em;
      text-transform: uppercase; margin-bottom: 1rem;
      display: flex; align-items: center; gap: .5rem;
    }
    .access-log .log-header i { font-size: .6rem; }
    .log-line {
      display: flex; gap: 1.2rem; padding: .3rem 0;
      border-bottom: 1px solid rgba(255,255,255,.03);
      color: var(--text-dim); flex-wrap: wrap;
    }
    .log-line:last-child { border-bottom: none; }
    .log-ts   { color: var(--text-dim); min-width: 140px; }
    .log-lvl  { min-width: 60px; }
    .log-lvl.ok   { color: var(--green); }
    .log-lvl.warn { color: var(--gold); }
    .log-msg  { color: var(--text); }

    @media(max-width: 768px) {
      .stats-row { grid-template-columns: repeat(2,1fr); }
      .admin-session { display: none; }
      thead th:nth-child(3), tbody td:nth-child(3) { display: none; }
    }
  </style>
</head>
<body>

<canvas id="matrix-canvas" aria-hidden="true"></canvas>
<div class="scanlines" aria-hidden="true"></div>
<div class="cursor-dot"  id="cDot"></div>
<div class="cursor-ring" id="cRing"></div>

<!-- ── Admin Navbar ── -->
<header class="admin-bar" role="banner">
  <div class="container admin-bar-inner">
    <div style="display:flex;align-items:center;gap:1rem;">
      <a href="index.html" class="admin-logo">
        <span class="br">[</span>PARADOX<span class="cur">_</span><span class="br">]</span>
      </a>
      <div class="admin-badge">
        <i class="fa-solid fa-shield-halved"></i>
        Admin Panel
      </div>
    </div>

    <div class="admin-session">
      <span class="s-item">
        <i class="fa-solid fa-user-shield"></i>
        Logged in as: <strong style="color:var(--gold);"><?php echo htmlspecialchars($admin_name); ?></strong>
      </span>
      <span class="s-item">
        <i class="fa-solid fa-clock"></i>
        Session: <?php echo date('H:i:s'); ?> EST
      </span>
    </div>

    <a href="logout.php" class="logout-btn">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
  </div>
</header>

<main class="secure-page">

  <!-- ── Secure Hero ── -->
  <div class="secure-hero">
    <div class="container">
      <div class="secure-hero-inner">
        <div>
          <div class="sh-title">
            Registered <span>Users</span>
          </div>
          <p class="sh-sub">
            <i class="fa-solid fa-database"></i>
            Loaded from data/users.txt &mdash; PHP file_get_contents()
          </p>
        </div>
        <div style="font-family:var(--font-mono);font-size:.7rem;color:var(--text-dim);text-align:right;line-height:1.8;">
          <div><i class="fa-solid fa-calendar" style="color:var(--gold);margin-right:.3rem;"></i><?php echo date('D, d M Y'); ?></div>
          <div><i class="fa-solid fa-server"   style="color:var(--gold);margin-right:.3rem;"></i>paradox.secure.local</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Stats Row ── -->
  <section style="padding:2rem 0 0;">
    <div class="container">
      <div class="stats-row reveal">
        <div class="stat-box">
          <i class="fa-solid fa-users s-icon" style="color:var(--green);"></i>
          <span class="s-num" style="color:var(--green);"><?php echo $total; ?></span>
          <div class="s-lbl">Total Users</div>
        </div>
        <div class="stat-box">
          <i class="fa-solid fa-circle-check s-icon" style="color:var(--cyan);"></i>
          <span class="s-num" style="color:var(--cyan);"><?php echo $active; ?></span>
          <div class="s-lbl">Active</div>
        </div>
        <div class="stat-box">
          <i class="fa-solid fa-circle-xmark s-icon" style="color:var(--red);"></i>
          <span class="s-num" style="color:var(--red);"><?php echo $inactive; ?></span>
          <div class="s-lbl">Inactive</div>
        </div>
        <div class="stat-box">
          <i class="fa-solid fa-crown s-icon" style="color:var(--gold);"></i>
          <span class="s-num" style="color:var(--gold);"><?php echo $by_role['Enterprise Member'] ?? 0; ?></span>
          <div class="s-lbl">Enterprise</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Users Table ── -->
  <section style="padding:2rem 0 5rem;">
    <div class="container">

      <!-- Controls -->
      <div class="table-controls reveal">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" id="userSearch" placeholder="Search by name, email, role..." />
        </div>
        <div style="display:flex;align-items:center;gap:.8rem;flex-wrap:wrap;">
          <select class="filter-select" id="statusFilter">
            <option value="">All Statuses</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
          <select class="filter-select" id="roleFilter">
            <option value="">All Roles</option>
            <option value="Standard Member">Standard</option>
            <option value="Premium Member">Premium</option>
            <option value="Enterprise Member">Enterprise</option>
          </select>
          <div class="table-count" id="tableCount">
            Showing <span><?php echo $total; ?></span> of <?php echo $total; ?> users
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="users-table-wrap reveal d1">
        <table id="usersTable">
          <thead>
            <tr>
              <th><i class="fa-solid fa-hashtag"></i>ID</th>
              <th><i class="fa-solid fa-user"></i>Full Name</th>
              <th><i class="fa-solid fa-envelope"></i>Email Address</th>
              <th><i class="fa-solid fa-id-badge"></i>Role</th>
              <th><i class="fa-solid fa-calendar-plus"></i>Joined</th>
              <th><i class="fa-solid fa-signal"></i>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($users)): ?>
            <tr>
              <td colspan="6">
                <div class="empty-state">
                  <i class="fa-solid fa-database"></i>
                  No users found in data/users.txt
                </div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($users as $u): ?>
            <?php
              $role_class   = 'standard';
              $role_val     = $u['role'] ?? 'Standard Member';
              if (strpos($role_val, 'Premium')    !== false) $role_class = 'premium';
              if (strpos($role_val, 'Enterprise') !== false) $role_class = 'enterprise';
              $role_icon    = $role_class === 'enterprise' ? 'fa-crown'       :
                              ($role_class === 'premium'   ? 'fa-star'        : 'fa-user');
              $status       = $u['status'] ?? 'Active';
              $status_class = strtolower($status);
              $status_icon  = $status === 'Active' ? 'fa-circle' : 'fa-circle-xmark';
            ?>
            <tr data-name="<?php echo htmlspecialchars(strtolower($u['name'] ?? '')); ?>"
                data-email="<?php echo htmlspecialchars(strtolower($u['email'] ?? '')); ?>"
                data-role="<?php echo htmlspecialchars($u['role'] ?? ''); ?>"
                data-status="<?php echo htmlspecialchars($status); ?>">
              <td class="td-id">#<?php echo htmlspecialchars(str_pad($u['id'] ?? '?', 2, '0', STR_PAD_LEFT)); ?></td>
              <td class="td-name"><?php echo htmlspecialchars($u['name'] ?? '—'); ?></td>
              <td class="td-email">
                <a href="mailto:<?php echo htmlspecialchars($u['email'] ?? ''); ?>">
                  <?php echo htmlspecialchars($u['email'] ?? '—'); ?>
                </a>
              </td>
              <td>
                <span class="role-badge <?php echo $role_class; ?>">
                  <i class="fa-solid <?php echo $role_icon; ?>"></i>
                  <?php echo htmlspecialchars($role_val); ?>
                </span>
              </td>
              <td><?php echo htmlspecialchars($u['joined'] ?? '—'); ?></td>
              <td>
                <span class="status-badge <?php echo $status_class; ?>">
                  <i class="fa-solid <?php echo $status_icon; ?>"></i>
                  <?php echo htmlspecialchars($status); ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div><!-- /users-table-wrap -->

      <!-- Access Log Terminal -->
      <div class="access-log reveal d2">
        <div class="log-header">
          <i class="fa-solid fa-terminal"></i>
          Access Log — Current Session
        </div>
        <div class="log-line">
          <span class="log-ts"><?php echo date('Y-m-d H:i:s'); ?></span>
          <span class="log-lvl ok">[AUTH]</span>
          <span class="log-msg">Admin '<?php echo htmlspecialchars($admin_name); ?>' authenticated successfully.</span>
        </div>
        <div class="log-line">
          <span class="log-ts"><?php echo date('Y-m-d H:i:s'); ?></span>
          <span class="log-lvl ok">[READ]</span>
          <span class="log-msg">data/users.txt loaded — <?php echo $total; ?> records parsed.</span>
        </div>
        <div class="log-line">
          <span class="log-ts"><?php echo date('Y-m-d H:i:s'); ?></span>
          <span class="log-lvl ok">[VIEW]</span>
          <span class="log-msg">Secure dashboard rendered. Session expires in 30 minutes of inactivity.</span>
        </div>
        <div class="log-line">
          <span class="log-ts" id="liveTs"><?php echo date('Y-m-d H:i:s'); ?></span>
          <span class="log-lvl warn">[LIVE]</span>
          <span class="log-msg">Session active <span id="sessionTimer" style="color:var(--green);">00:00</span></span>
        </div>
      </div>

    </div>
  </section>

</main>

<script src="js/main.js" defer></script>
<script>
// ── Live session timer ─────────────────────────────────────
const sessionStart = Date.now();
const timerEl = document.getElementById('sessionTimer');
const tsEl    = document.getElementById('liveTs');
if (timerEl) {
  setInterval(() => {
    const elapsed = Math.floor((Date.now() - sessionStart) / 1000);
    const m = String(Math.floor(elapsed / 60)).padStart(2,'0');
    const s = String(elapsed % 60).padStart(2,'0');
    timerEl.textContent = `${m}:${s}`;
    if (tsEl) tsEl.textContent = new Date().toISOString().replace('T',' ').slice(0,19);
  }, 1000);
}

// ── Live search + filter ───────────────────────────────────
const searchInput  = document.getElementById('userSearch');
const statusFilter = document.getElementById('statusFilter');
const roleFilter   = document.getElementById('roleFilter');
const countEl      = document.getElementById('tableCount');
const tableTotal   = <?php echo $total; ?>;

function filterTable() {
  const q      = (searchInput?.value || '').toLowerCase();
  const status = statusFilter?.value  || '';
  const role   = roleFilter?.value    || '';
  const rows   = document.querySelectorAll('#usersTable tbody tr[data-name]');
  let visible  = 0;

  rows.forEach(row => {
    const name   = row.dataset.name   || '';
    const email  = row.dataset.email  || '';
    const rRole  = row.dataset.role   || '';
    const rStat  = row.dataset.status || '';

    const matchQ      = !q      || name.includes(q) || email.includes(q) || rRole.toLowerCase().includes(q);
    const matchStatus = !status || rStat === status;
    const matchRole   = !role   || rRole === role;

    if (matchQ && matchStatus && matchRole) {
      row.style.display = '';
      visible++;
    } else {
      row.style.display = 'none';
    }
  });

  if (countEl) {
    countEl.innerHTML = `Showing <span>${visible}</span> of ${tableTotal} users`;
  }
}

searchInput?.addEventListener('input', filterTable);
statusFilter?.addEventListener('change', filterTable);
roleFilter?.addEventListener('change', filterTable);
</script>
</body>
</html>
