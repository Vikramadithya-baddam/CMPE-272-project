<?php
// ============================================================
// PARADOX SYSTEMS — combined_users.php
// Shows users from ALL companies:
//   - Paradox Systems (this site)  → direct PostgreSQL query
//   - Company B                    → CURL to their API
//   - Company C                    → CURL to their API
//
// SETUP: Replace COMPANY_B_URL and COMPANY_C_URL below with
// your group partners' actual deployed URLs.
// ============================================================
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db_config.php';

// ── Company API endpoints ─────────────────────────────────────
// Replace these with your partners' actual URLs
define('COMPANY_B_URL', 'https://company-b-site.onrender.com/api/users.php'); // replace with actual URL
define('COMPANY_C_URL', 'https://company-c-site.onrender.com/api/users.php'); // replace with actual URL

// ── 1. Fetch local users from PostgreSQL ─────────────────────
function fetch_local_users(): array {
    try {
        $pdo  = get_db();
        $stmt = $pdo->query('SELECT id, name, email, role, joined::text AS joined, status FROM users ORDER BY id');
        return [
            'company'    => 'Paradox Systems',
            'company_id' => 'paradox',
            'color'      => 'green',    // for styling
            'users'      => $stmt->fetchAll(),
            'error'      => null,
        ];
    } catch (Exception $e) {
        return ['company' => 'Paradox Systems', 'company_id' => 'paradox', 'color' => 'green', 'users' => [], 'error' => $e->getMessage()];
    }
}

// ── 2. Fetch remote users via CURL ───────────────────────────
function fetch_remote_users(string $url, string $company_name, string $color): array {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,          // 10-second timeout
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        CURLOPT_USERAGENT      => 'ParadoxSystems-CombinedUsers/1.0',
    ]);

    $raw   = curl_exec($ch);
    $error = curl_error($ch);
    $code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($error || $code !== 200) {
        return [
            'company'    => $company_name,
            'company_id' => strtolower(str_replace(' ', '_', $company_name)),
            'color'      => $color,
            'users'      => [],
            'error'      => $error ?: "HTTP {$code}",
        ];
    }

    $data = json_decode($raw, true);
    return [
        'company'    => $data['company']  ?? $company_name,
        'company_id' => $data['company_id'] ?? 'remote',
        'color'      => $color,
        'users'      => $data['users']    ?? [],
        'error'      => null,
    ];
}

// ── Collect all companies ─────────────────────────────────────
$companies = [
    fetch_local_users(),
    fetch_remote_users(COMPANY_B_URL, 'Company B', 'cyan'),
    fetch_remote_users(COMPANY_C_URL, 'Company C', 'gold'),
];

// Flatten all users for the combined table (add source company)
$all_users = [];
foreach ($companies as $co) {
    foreach ($co['users'] as $u) {
        $u['_company']    = $co['company'];
        $u['_company_id'] = $co['company_id'];
        $u['_color']      = $co['color'];
        $all_users[]      = $u;
    }
}

$total_users = count($all_users);
$active_page = 'products'; // nav highlight
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Combined Users — Paradox Systems Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body { padding-top: 80px; }

    /* Admin top bar */
    .admin-bar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
      background: rgba(6,6,17,.97); backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255,215,0,.2);
      padding: .75rem 0;
    }
    .admin-bar-inner {
      display: flex; align-items: center; justify-content: space-between;
      gap: 1rem; max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;
    }
    .admin-logo { font-family: var(--font-head); font-size: 1.1rem; font-weight: 900;
                  color: var(--white); letter-spacing: .08em; text-decoration: none; }
    .admin-logo .br { color: var(--gold); }
    .admin-badge { font-family: var(--font-mono); font-size: .63rem; color: var(--gold);
                   border: 1px solid rgba(255,215,0,.3); background: rgba(255,215,0,.06);
                   padding: .22rem .7rem; letter-spacing: .12em; }
    .logout-btn { font-family: var(--font-mono); font-size: .65rem; letter-spacing: .1em;
                  padding: .35rem .9rem; color: var(--red); border: 1px solid rgba(255,0,60,.3);
                  background: rgba(255,0,60,.06); text-decoration: none; display: flex;
                  align-items: center; gap: .35rem; transition: all .3s; }
    .logout-btn:hover { background: rgba(255,0,60,.14); border-color: var(--red); }

    /* Company summary cards */
    .company-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px;
                     background: var(--border); border: 1px solid var(--border); margin-bottom: 2.5rem; }
    .co-card { background: var(--bg2); padding: 1.6rem 1.8rem; position: relative; overflow: hidden; }
    .co-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; }
    .co-card.green::before { background: linear-gradient(90deg, transparent, var(--green), transparent); }
    .co-card.cyan::before  { background: linear-gradient(90deg, transparent, var(--cyan),  transparent); }
    .co-card.gold::before  { background: linear-gradient(90deg, transparent, var(--gold),  transparent); }
    .co-name { font-family: var(--font-head); font-size: .85rem; color: var(--white);
               letter-spacing: .06em; margin-bottom: .35rem; }
    .co-count { font-family: var(--font-mono); font-size: 2.2rem; line-height: 1; margin-bottom: .3rem; }
    .co-count.green { color: var(--green); text-shadow: 0 0 16px rgba(0,255,65,.3); }
    .co-count.cyan  { color: var(--cyan);  text-shadow: 0 0 16px rgba(0,212,255,.3); }
    .co-count.gold  { color: var(--gold);  text-shadow: 0 0 16px rgba(255,215,0,.3); }
    .co-status { font-family: var(--font-mono); font-size: .65rem; }
    .co-status.ok  { color: var(--green); }
    .co-status.err { color: var(--red); }

    /* Filters */
    .filter-bar { display: flex; gap: .8rem; margin-bottom: 1.2rem; flex-wrap: wrap; align-items: center; }
    .filter-bar input, .filter-bar select {
      background: var(--bg2); border: 1px solid var(--border); color: var(--text);
      font-family: var(--font-mono); font-size: .75rem; padding: .55rem .9rem;
      outline: none; transition: border-color .3s;
    }
    .filter-bar input { flex: 1; min-width: 200px; }
    .filter-bar input:focus, .filter-bar select:focus { border-color: var(--green); }
    .count-badge { font-family: var(--font-mono); font-size: .7rem; color: var(--text-dim);
                   margin-left: auto; white-space: nowrap; }

    /* Table */
    .users-wrap { overflow-x: auto; border: 1px solid var(--border); }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--surface2); }
    th { font-family: var(--font-mono); font-size: .65rem; color: var(--text-dim);
         letter-spacing: .12em; text-transform: uppercase; padding: .85rem 1rem;
         text-align: left; border-bottom: 1px solid var(--border); white-space: nowrap; }
    td { font-family: var(--font-mono); font-size: .78rem; color: var(--text);
         padding: .75rem 1rem; border-bottom: 1px solid rgba(255,255,255,.04); }
    tbody tr:hover td { background: var(--surface); }
    tbody tr:last-child td { border-bottom: none; }

    /* Company source pill */
    .src-pill { font-size: .6rem; letter-spacing: .1em; padding: .15rem .55rem;
                border: 1px solid; display: inline-flex; align-items: center; gap: .3rem; }
    .src-pill.green { color: var(--green); border-color: rgba(0,255,65,.3);  background: rgba(0,255,65,.05); }
    .src-pill.cyan  { color: var(--cyan);  border-color: rgba(0,212,255,.3); background: rgba(0,212,255,.05); }
    .src-pill.gold  { color: var(--gold);  border-color: rgba(255,215,0,.3); background: rgba(255,215,0,.05); }

    /* Role + status badges */
    .role-badge { font-size: .6rem; letter-spacing: .08em; padding: .15rem .55rem;
                  display: inline-block; border: 1px solid; }
    .role-badge.enterprise { color: var(--gold);  border-color: rgba(255,215,0,.35); }
    .role-badge.premium    { color: var(--cyan);  border-color: rgba(0,212,255,.35); }
    .role-badge.standard   { color: var(--text-dim); border-color: var(--border); }
    .status-ok  { color: var(--green); }
    .status-off { color: var(--text-dim); }

    /* Terminal (method explanation) */
    .method-terminal { max-width: 700px; margin-bottom: 2.5rem; }

    @media(max-width: 800px) {
      .company-cards { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<!-- Admin top bar -->
<div class="admin-bar">
  <div class="admin-bar-inner">
    <a href="index.html" class="admin-logo"><span class="br">[</span>PARADOX<span class="br">]</span></a>
    <span class="admin-badge"><i class="fa-solid fa-users"></i> COMBINED USERS — ADMIN</span>
    <div style="display:flex;gap:.8rem;">
      <a href="secure.php"   class="logout-btn"><i class="fa-solid fa-arrow-left"></i> Dashboard</a>
      <a href="logout.php"   class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </div>
</div>

<main style="padding: 2.5rem 0 5rem;">
<div class="container">

  <!-- How it works -->
  <div class="section-tag">COMBINED_USERS — LAB ASSIGNMENT</div>
  <h2 style="color:var(--white);margin-bottom:1rem;">
    All Company <span style="color:var(--green);">Users</span>
  </h2>

  <div class="method-terminal terminal reveal">
    <div class="terminal-bar">
      <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
      <span class="t-title">combined_users.php — data fetch method</span>
    </div>
    <div class="t-line"><span class="t-prompt">$</span><span class="t-cmd">paradox_db.query('SELECT * FROM users')</span></div>
    <div class="t-line"><span class="t-prompt"> </span><span class="t-ok"><?php echo count($companies[0]['users']); ?> users loaded from local PostgreSQL</span></div>
    <div class="t-line"><span class="t-prompt">$</span><span class="t-cmd">curl <?php echo htmlspecialchars(COMPANY_B_URL); ?></span></div>
    <div class="t-line"><span class="t-prompt"> </span>
      <?php if ($companies[1]['error']): ?>
        <span class="t-err">ERROR: <?php echo htmlspecialchars($companies[1]['error']); ?></span>
      <?php else: ?>
        <span class="t-ok"><?php echo count($companies[1]['users']); ?> users received from Company B</span>
      <?php endif; ?>
    </div>
    <div class="t-line"><span class="t-prompt">$</span><span class="t-cmd">curl <?php echo htmlspecialchars(COMPANY_C_URL); ?></span></div>
    <div class="t-line"><span class="t-prompt"> </span>
      <?php if ($companies[2]['error']): ?>
        <span class="t-err">ERROR: <?php echo htmlspecialchars($companies[2]['error']); ?></span>
      <?php else: ?>
        <span class="t-ok"><?php echo count($companies[2]['users']); ?> users received from Company C</span>
      <?php endif; ?>
    </div>
    <div class="t-line"><span class="t-prompt"> </span><span class="t-out">total: <?php echo $total_users; ?> users merged and displayed below</span></div>
  </div>

  <!-- Company summary cards -->
  <div class="company-cards reveal">
    <?php foreach ($companies as $co): ?>
    <div class="co-card <?php echo $co['color']; ?>">
      <div class="co-name"><?php echo htmlspecialchars($co['company']); ?></div>
      <div class="co-count <?php echo $co['color']; ?>"><?php echo count($co['users']); ?></div>
      <div style="font-family:var(--font-mono);font-size:.65rem;color:var(--text-dim);margin-bottom:.5rem;">users</div>
      <?php if ($co['error']): ?>
        <div class="co-status err"><i class="fa-solid fa-circle-xmark"></i> CURL failed: <?php echo htmlspecialchars($co['error']); ?></div>
      <?php elseif ($co['company_id'] === 'paradox'): ?>
        <div class="co-status ok"><i class="fa-solid fa-database"></i> PostgreSQL — local query</div>
      <?php else: ?>
        <div class="co-status ok"><i class="fa-solid fa-network-wired"></i> CURL — remote API</div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Filter bar -->
  <div class="filter-bar reveal">
    <input type="text" id="srch" placeholder="Search name, email, company..." oninput="filterTable()" />
    <select id="coFilter" onchange="filterTable()">
      <option value="">All companies</option>
      <?php foreach ($companies as $co): ?>
        <option value="<?php echo htmlspecialchars($co['company_id']); ?>">
          <?php echo htmlspecialchars($co['company']); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <select id="statusFilter" onchange="filterTable()">
      <option value="">All statuses</option>
      <option value="Active">Active</option>
      <option value="Inactive">Inactive</option>
    </select>
    <select id="roleFilter" onchange="filterTable()">
      <option value="">All roles</option>
      <option value="Enterprise Member">Enterprise</option>
      <option value="Premium Member">Premium</option>
      <option value="Standard Member">Standard</option>
    </select>
    <span class="count-badge" id="countBadge">Showing <b><?php echo $total_users; ?></b> of <?php echo $total_users; ?> users</span>
  </div>

  <!-- Combined table -->
  <div class="users-wrap reveal d1">
    <table id="usersTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Joined</th>
          <th>Status</th>
          <th>Source</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $row_num = 1;
        foreach ($all_users as $u):
          $role = $u['role'] ?? 'Standard Member';
          $rc   = str_contains($role, 'Enterprise') ? 'enterprise'
                : (str_contains($role, 'Premium') ? 'premium' : 'standard');
          $status = $u['status'] ?? 'Active';
          $color  = $u['_color'];
        ?>
        <tr data-name="<?php echo htmlspecialchars(strtolower($u['name'] ?? '')); ?>"
            data-email="<?php echo htmlspecialchars(strtolower($u['email'] ?? '')); ?>"
            data-company="<?php echo htmlspecialchars($u['_company_id']); ?>"
            data-status="<?php echo htmlspecialchars($status); ?>"
            data-role="<?php echo htmlspecialchars($role); ?>">
          <td style="color:var(--text-dim);">#<?php echo str_pad($row_num++, 2, '0', STR_PAD_LEFT); ?></td>
          <td><?php echo htmlspecialchars($u['name'] ?? '—'); ?></td>
          <td style="color:var(--text-dim);font-size:.72rem;"><?php echo htmlspecialchars($u['email'] ?? '—'); ?></td>
          <td><span class="role-badge <?php echo $rc; ?>"><?php echo htmlspecialchars($role); ?></span></td>
          <td><?php echo htmlspecialchars($u['joined'] ?? '—'); ?></td>
          <td class="<?php echo $status==='Active'?'status-ok':'status-off'; ?>">
            <i class="fa-solid <?php echo $status==='Active'?'fa-circle':'fa-circle-xmark'; ?>" style="font-size:.55rem;margin-right:.3rem;"></i>
            <?php echo htmlspecialchars($status); ?>
          </td>
          <td><span class="src-pill <?php echo $color; ?>">
            <i class="fa-solid <?php echo $color==='green'?'fa-database':'fa-network-wired'; ?>" style="font-size:.55rem;"></i>
            <?php echo htmlspecialchars($u['_company']); ?>
          </span></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($all_users)): ?>
        <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-dim);">
          No users found. Check your database and CURL endpoints.
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>
</main>

<script src="js/main.js" defer></script>
<script>
function filterTable() {
  const q      = document.getElementById('srch').value.toLowerCase();
  const co     = document.getElementById('coFilter').value;
  const status = document.getElementById('statusFilter').value;
  const role   = document.getElementById('roleFilter').value;
  const rows   = document.querySelectorAll('#usersTable tbody tr[data-name]');
  let visible  = 0;

  rows.forEach(row => {
    const matchQ  = !q || row.dataset.name.includes(q) || row.dataset.email.includes(q);
    const matchCo = !co     || row.dataset.company === co;
    const matchSt = !status || row.dataset.status  === status;
    const matchRo = !role   || row.dataset.role     === role;
    const show    = matchQ && matchCo && matchSt && matchRo;
    row.style.display = show ? '' : 'none';
    if (show) visible++;
  });

  document.getElementById('countBadge').innerHTML =
    'Showing <b>' + visible + '</b> of <?php echo $total_users; ?> users';
}
</script>
</body>
</html>
