<?php
// ============================================================
// PARADOX SYSTEMS — combined_users.php
// PUBLIC PAGE — no login required.
// Paradox Systems → PostgreSQL (Render hosted)
// NullCastle      → CURL to their api/users.php
// ============================================================
require_once __DIR__ . '/db_config.php';

define('NULLCASTLE_URL', 'https://nullcastle.rishikeshaluguvelli.me/api/users.php');

// ── Paradox: direct PostgreSQL query ─────────────────────────
function fetch_local_users(): array {
    try {
        $pdo  = get_db();
        $stmt = $pdo->query(
            'SELECT id, name, email, role, joined::text AS joined, status
             FROM users ORDER BY id ASC'
        );
        $users = array_map(fn($u) => [
            'id'         => $u['id'],
            'name'       => $u['name'],
            'email'      => $u['email'],
            'role'       => $u['role'],
            'department' => '',
            'status'     => $u['status'],
            'joined'     => $u['joined'],
        ], $stmt->fetchAll());
        return [
            'company'    => 'Paradox Systems',
            'company_id' => 'paradox',
            'color'      => 'green',
            'source'     => 'PostgreSQL (Render)',
            'users'      => $users,
            'error'      => null,
                'time'       => $time,
        ];
    } catch (Exception $e) {
        return [
            'company'    => 'Paradox Systems',
            'company_id' => 'paradox',
            'color'      => 'green',
            'source'     => 'PostgreSQL (Render)',
            'users'      => [],
            'error'      => $e->getMessage(),
        ];
    }
}

// ── NullCastle: CURL their API ────────────────────────────────
// Their JSON shape:
// { "meta": { "total": 15, ... },
//   "users": [ { "id","name","email","role","department",
//                "clearance","status","joined","last_login" } ] }
function fetch_nullcastle_users(): array {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => NULLCASTLE_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,       // Render free tier can take 20s+ to cold start
        CURLOPT_CONNECTTIMEOUT => 20,       // give it time to wake up
        CURLOPT_SSL_VERIFYPEER => false,    // skip SSL cert check (avoids cert chain issues)
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_FOLLOWLOCATION => true,     // follow any redirects
        CURLOPT_MAXREDIRS      => 3,
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
            'User-Agent: ParadoxSystems/1.0',
        ],
    ]);
    $raw   = curl_exec($ch);
    $error = curl_error($ch);
    $code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $time  = round(curl_getinfo($ch, CURLINFO_TOTAL_TIME), 2);
    curl_close($ch);

    if ($raw === false || $error) {
        return [
            'company'    => 'NullCastle',
            'company_id' => 'nullcastle',
            'color'      => 'cyan',
            'source'     => 'PostgreSQL (Render) via CURL',
            'users'      => [],
            'error'      => "CURL error after {$time}s: {$error}",
            'time'       => $time,
        ];
    }
    if ($code !== 200) {
        return [
            'company'    => 'NullCastle',
            'company_id' => 'nullcastle',
            'color'      => 'cyan',
            'source'     => 'PostgreSQL (Render) via CURL',
            'users'      => [],
            'error'      => "HTTP {$code} (took {$time}s)",
            'time'       => $time,
        ];
    }
    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'company'    => 'NullCastle',
            'company_id' => 'nullcastle',
            'color'      => 'cyan',
            'source'     => 'PostgreSQL (Render) via CURL',
            'users'      => [],
            'error'      => 'Invalid JSON: ' . json_last_error_msg(),
            'time'       => $time,
        ];
    }

    $raw_users = $data['users'] ?? [];

    // Normalize their fields to the common shape used in the table
    $users = array_map(fn($u) => [
        'id'         => $u['id']         ?? '—',
        'name'       => $u['name']       ?? '—',
        'email'      => $u['email']      ?? '—',
        'role'       => $u['role']       ?? '—',
        'department' => $u['department'] ?? '—',
        'status'     => $u['status']     ?? '—',
        'joined'     => $u['joined']     ?? '—',
    ], $raw_users);

    return [
        'company'    => 'NullCastle',
        'company_id' => 'nullcastle',
        'color'      => 'cyan',
        'source'     => 'PostgreSQL (Render) via CURL',
        'users'      => $users,
        'error'      => null,
        'time'       => $time,
    ];
}

// ── Merge both companies ──────────────────────────────────────
$companies = [
    fetch_local_users(),
    fetch_nullcastle_users(),
];

$all_users = [];
foreach ($companies as $co) {
    foreach ($co['users'] as $u) {
        $u['_company']    = $co['company'];
        $u['_company_id'] = $co['company_id'];
        $u['_color']      = $co['color'];
        $u['_source']     = $co['source'];
        $all_users[]      = $u;
    }
}
$total_users = count($all_users);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Combined Users — Paradox Systems</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23060611'/><text y='72' x='10' font-size='68' fill='%2300ff41' font-family='monospace'>_</text></svg>" />
  <style>
    .company-cards { display:grid;grid-template-columns:repeat(2,1fr);gap:1px;
                     background:var(--border);border:1px solid var(--border);margin-bottom:2.5rem; }
    .co-card { background:var(--bg2);padding:1.6rem 1.8rem;position:relative;overflow:hidden; }
    .co-card::before { content:'';position:absolute;top:0;left:0;right:0;height:2px; }
    .co-card.green::before { background:linear-gradient(90deg,transparent,var(--green),transparent); }
    .co-card.cyan::before  { background:linear-gradient(90deg,transparent,var(--cyan),transparent); }
    .co-name  { font-family:var(--font-head);font-size:.85rem;color:var(--white);letter-spacing:.06em;margin-bottom:.3rem; }
    .co-count { font-family:var(--font-mono);font-size:2.2rem;line-height:1;margin-bottom:.25rem; }
    .co-count.green { color:var(--green);text-shadow:0 0 16px rgba(0,255,65,.3); }
    .co-count.cyan  { color:var(--cyan); text-shadow:0 0 16px rgba(0,212,255,.3); }
    .co-source { font-family:var(--font-mono);font-size:.63rem;color:var(--text-dim);margin-bottom:.4rem; }
    .co-status     { font-family:var(--font-mono);font-size:.65rem; }
    .co-status.ok  { color:var(--green); }
    .co-status.err { color:var(--red); }

    .filter-bar { display:flex;gap:.8rem;margin-bottom:1.2rem;flex-wrap:wrap;align-items:center; }
    .filter-bar input,.filter-bar select {
      background:var(--bg2);border:1px solid var(--border);color:var(--text);
      font-family:var(--font-mono);font-size:.75rem;padding:.55rem .9rem;outline:none;
      transition:border-color .3s;
    }
    .filter-bar input { flex:1;min-width:200px; }
    .filter-bar input:focus,.filter-bar select:focus { border-color:var(--green); }
    .count-badge { font-family:var(--font-mono);font-size:.7rem;color:var(--text-dim);
                   margin-left:auto;white-space:nowrap; }
    .count-badge b { color:var(--green); }

    .users-wrap { overflow-x:auto;border:1px solid var(--border); }
    table { width:100%;border-collapse:collapse; }
    thead tr { background:var(--surface2); }
    th { font-family:var(--font-mono);font-size:.65rem;color:var(--text-dim);letter-spacing:.12em;
         text-transform:uppercase;padding:.85rem 1rem;text-align:left;
         border-bottom:1px solid var(--border);white-space:nowrap; }
    td { font-family:var(--font-mono);font-size:.78rem;color:var(--text);
         padding:.75rem 1rem;border-bottom:1px solid rgba(255,255,255,.04); }
    tbody tr:hover td { background:var(--surface); }
    tbody tr:last-child td { border-bottom:none; }
    .td-dim { color:var(--text-dim);font-size:.72rem; }

    .src-pill { font-size:.6rem;letter-spacing:.1em;padding:.15rem .55rem;border:1px solid;
                display:inline-flex;align-items:center;gap:.3rem;white-space:nowrap; }
    .src-pill.green { color:var(--green);border-color:rgba(0,255,65,.3); background:rgba(0,255,65,.05); }
    .src-pill.cyan  { color:var(--cyan); border-color:rgba(0,212,255,.3);background:rgba(0,212,255,.05); }

    .status-badge { font-size:.6rem;letter-spacing:.08em;padding:.15rem .55rem;
                    display:inline-flex;align-items:center;gap:.3rem;border:1px solid; }
    .status-badge.active    { color:var(--green);border-color:rgba(0,255,65,.3); background:rgba(0,255,65,.05); }
    .status-badge.inactive  { color:var(--red);  border-color:rgba(255,0,60,.3); background:rgba(255,0,60,.05); }
    .status-badge.suspended { color:var(--gold); border-color:rgba(255,215,0,.3);background:rgba(255,215,0,.05); }

    @media(max-width:600px){ .company-cards{ grid-template-columns:1fr; } }
  </style>
</head>
<body>

<?php $active_page = 'home'; include '_nav.php'; ?>

<div class="page-hero" data-bg-text="USERS">
  <div class="grid-bg"></div>
  <div class="container">
    <p class="eyebrow"><i class="fa-solid fa-users"></i> COMBINED_USERS — GROUP LAB</p>
    <h1 style="color:var(--white);">ALL COMPANY <span style="color:var(--green);">USERS</span></h1>
    <p class="sub">
      Paradox Systems users from local PostgreSQL.
      NullCastle users fetched live via CURL from their hosted API.
    </p>
  </div>
</div>

<section>
<div class="container">

  <!-- Terminal fetch log -->
  <div class="terminal reveal" style="max-width:720px;margin-bottom:2.5rem;">
    <div class="terminal-bar">
      <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
      <span class="t-title">combined_users.php — fetch log</span>
    </div>
    <div class="t-line">
      <span class="t-prompt">$</span>
      <span class="t-cmd">SELECT * FROM users ORDER BY id; <span style="color:var(--text-dim);">-- local PostgreSQL</span></span>
    </div>
    <div class="t-line">
      <span class="t-prompt"> </span>
      <?php if ($companies[0]['error']): ?>
        <span class="t-err">ERROR: <?php echo htmlspecialchars($companies[0]['error']); ?></span>
      <?php else: ?>
        <span class="t-ok"><?php echo count($companies[0]['users']); ?> rows — Paradox Systems (PostgreSQL, Render)</span>
      <?php endif; ?>
    </div>
    <div class="t-line">
      <span class="t-prompt">$</span>
      <span class="t-cmd">curl "<?php echo htmlspecialchars(NULLCASTLE_URL); ?>"</span>
    </div>
    <div class="t-line">
      <span class="t-prompt"> </span>
      <?php if ($companies[1]['error']): ?>
        <span class="t-err">ERROR: <?php echo htmlspecialchars($companies[1]['error']); ?></span>
      <?php else: ?>
        <span class="t-ok"><?php echo count($companies[1]['users']); ?> rows — NullCastle (PostgreSQL, Render) via CURL</span>
      <?php endif; ?>
    </div>
    <div class="t-line">
      <span class="t-prompt"> </span>
      <span class="t-out">total: <?php echo $total_users; ?> users merged and displayed below</span>
    </div>
  </div>

  <!-- Company cards -->
  <div class="company-cards reveal">
    <?php foreach ($companies as $co): ?>
    <div class="co-card <?php echo $co['color']; ?>">
      <div class="co-name"><?php echo htmlspecialchars($co['company']); ?></div>
      <div class="co-count <?php echo $co['color']; ?>"><?php echo count($co['users']); ?></div>
      <div style="font-family:var(--font-mono);font-size:.63rem;color:var(--text-dim);margin-bottom:.4rem;">users</div>
      <div class="co-source">
        <i class="fa-solid fa-database" style="font-size:.55rem;margin-right:.3rem;"></i>
        <?php echo htmlspecialchars($co['source']); ?>
      </div>
      <?php if ($co['error']): ?>
        <div class="co-status err">
          <i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($co['error']); ?>
        </div>
      <?php else: ?>
        <div class="co-status ok">
          <i class="fa-solid fa-circle-check"></i> Connected
        </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Filters -->
  <div class="filter-bar reveal">
    <input type="text" id="srch" placeholder="Search name, email, role..." oninput="filterTable()" />
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
      <option value="ACTIVE">ACTIVE</option>
      <option value="SUSPENDED">SUSPENDED</option>
    </select>
    <span class="count-badge" id="countBadge">
      Showing <b><?php echo $total_users; ?></b> of <?php echo $total_users; ?> users
    </span>
  </div>

  <!-- Combined table -->
  <div class="users-wrap reveal d1">
    <table id="usersTable">
      <thead>
        <tr>
          <th>#</th>
          <th><i class="fa-solid fa-user" style="font-size:.55rem;margin-right:.3rem;"></i>Name</th>
          <th><i class="fa-solid fa-envelope" style="font-size:.55rem;margin-right:.3rem;"></i>Email</th>
          <th><i class="fa-solid fa-id-badge" style="font-size:.55rem;margin-right:.3rem;"></i>Role</th>
          <th><i class="fa-solid fa-building" style="font-size:.55rem;margin-right:.3rem;"></i>Dept</th>
          <th><i class="fa-solid fa-calendar" style="font-size:.55rem;margin-right:.3rem;"></i>Joined</th>
          <th><i class="fa-solid fa-signal" style="font-size:.55rem;margin-right:.3rem;"></i>Status</th>
          <th><i class="fa-solid fa-network-wired" style="font-size:.55rem;margin-right:.3rem;"></i>Source</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($all_users)): ?>
          <tr>
            <td colspan="8" style="text-align:center;padding:3rem;color:var(--text-dim);">
              <i class="fa-solid fa-database" style="font-size:2rem;display:block;margin-bottom:1rem;opacity:.3;"></i>
              No users found. Check your database connection and CURL endpoint.
            </td>
          </tr>
        <?php else: ?>
          <?php $rn = 1; foreach ($all_users as $u):
            $status    = $u['status'] ?? 'Active';
            $status_lc = strtolower($status);
            $color     = $u['_color'];
          ?>
          <tr data-name="<?php echo htmlspecialchars(strtolower($u['name'] ?? '')); ?>"
              data-email="<?php echo htmlspecialchars(strtolower($u['email'] ?? '')); ?>"
              data-role="<?php echo htmlspecialchars(strtolower($u['role'] ?? '')); ?>"
              data-company="<?php echo htmlspecialchars($u['_company_id']); ?>"
              data-status="<?php echo htmlspecialchars($status); ?>">
            <td class="td-dim">#<?php echo str_pad($rn++, 2, '0', STR_PAD_LEFT); ?></td>
            <td style="color:var(--white);font-weight:600;"><?php echo htmlspecialchars($u['name'] ?? '—'); ?></td>
            <td class="td-dim"><?php echo htmlspecialchars($u['email'] ?? '—'); ?></td>
            <td><?php echo htmlspecialchars($u['role'] ?? '—'); ?></td>
            <td class="td-dim"><?php echo htmlspecialchars($u['department'] ?: '—'); ?></td>
            <td class="td-dim"><?php echo htmlspecialchars($u['joined'] ?? '—'); ?></td>
            <td>
              <span class="status-badge <?php echo $status_lc; ?>">
                <i class="fa-solid <?php echo in_array($status_lc, ['active']) ? 'fa-circle' : 'fa-circle-xmark'; ?>"
                   style="font-size:.45rem;"></i>
                <?php echo htmlspecialchars($status); ?>
              </span>
            </td>
            <td>
              <span class="src-pill <?php echo $color; ?>">
                <i class="fa-solid <?php echo $color === 'green' ? 'fa-database' : 'fa-network-wired'; ?>"
                   style="font-size:.55rem;"></i>
                <?php echo htmlspecialchars($u['_company']); ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>
</section>

<?php include '_footer.php'; ?>

<script>
function filterTable() {
  const q  = document.getElementById('srch').value.toLowerCase();
  const co = document.getElementById('coFilter').value;
  const st = document.getElementById('statusFilter').value;
  const rows = document.querySelectorAll('#usersTable tbody tr[data-name]');
  let visible = 0;
  rows.forEach(r => {
    const show = (!q  || r.dataset.name.includes(q)
                      || r.dataset.email.includes(q)
                      || r.dataset.role.includes(q))
              && (!co || r.dataset.company === co)
              && (!st || r.dataset.status  === st);
    r.style.display = show ? '' : 'none';
    if (show) visible++;
  });
  document.getElementById('countBadge').innerHTML =
    'Showing <b>' + visible + '</b> of <?php echo $total_users; ?> users';
}
</script>
</body>
</html>