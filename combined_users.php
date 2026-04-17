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
            // 'time'       => $time,
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
    /* Summary stat cards */
    .company-cards { display:grid;grid-template-columns:repeat(2,1fr);gap:1px;
                     background:var(--border);border:1px solid var(--border);margin-bottom:3rem; }
    .co-card { background:var(--bg2);padding:1.6rem 1.8rem;position:relative;overflow:hidden; }
    .co-card::before { content:'';position:absolute;top:0;left:0;right:0;height:2px; }
    .co-card.green::before { background:linear-gradient(90deg,transparent,var(--green),transparent); }
    .co-card.cyan::before  { background:linear-gradient(90deg,transparent,var(--cyan),transparent); }
    .co-name   { font-family:var(--font-head);font-size:.85rem;color:var(--white);letter-spacing:.06em;margin-bottom:.3rem; }
    .co-count  { font-family:var(--font-mono);font-size:2.2rem;line-height:1;margin-bottom:.25rem; }
    .co-count.green { color:var(--green);text-shadow:0 0 16px rgba(0,255,65,.3); }
    .co-count.cyan  { color:var(--cyan); text-shadow:0 0 16px rgba(0,212,255,.3); }
    .co-source { font-family:var(--font-mono);font-size:.63rem;color:var(--text-dim);margin-bottom:.4rem; }
    .co-status     { font-family:var(--font-mono);font-size:.65rem; }
    .co-status.ok  { color:var(--green); }
    .co-status.err { color:var(--red); }

    /* Per-company table block */
    .co-table-block { margin-bottom:3.5rem; }
    .co-table-header {
      display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.8rem;
      padding:1rem 1.2rem;
      border:1px solid var(--border);border-bottom:none;
      background:var(--surface2);
    }
    .co-table-header.green { border-top:2px solid var(--green); }
    .co-table-header.cyan  { border-top:2px solid var(--cyan); }
    .co-table-title {
      font-family:var(--font-head);font-size:.9rem;color:var(--white);
      letter-spacing:.07em;display:flex;align-items:center;gap:.6rem;
    }
    .co-table-title i { font-size:.8rem; }
    .co-table-title i.green { color:var(--green); }
    .co-table-title i.cyan  { color:var(--cyan); }
    .co-table-meta { font-family:var(--font-mono);font-size:.65rem;color:var(--text-dim);
                     display:flex;align-items:center;gap:1.2rem;flex-wrap:wrap; }
    .co-table-meta .pill { padding:.15rem .55rem;border:1px solid;font-size:.6rem; }
    .co-table-meta .pill.green { color:var(--green);border-color:rgba(0,255,65,.3);background:rgba(0,255,65,.05); }
    .co-table-meta .pill.cyan  { color:var(--cyan); border-color:rgba(0,212,255,.3);background:rgba(0,212,255,.05); }

    /* Search within each table */
    .tbl-search {
      background:var(--bg2);border:1px solid var(--border);color:var(--text);
      font-family:var(--font-mono);font-size:.72rem;padding:.4rem .8rem;
      outline:none;transition:border-color .3s;min-width:200px;
    }
    .tbl-search:focus { border-color:var(--green); }

    /* Tables */
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
    .td-name { color:var(--white);font-weight:600; }

    /* Status badges */
    .status-badge { font-size:.6rem;letter-spacing:.08em;padding:.15rem .55rem;
                    display:inline-flex;align-items:center;gap:.3rem;border:1px solid; }
    .status-badge.active    { color:var(--green);border-color:rgba(0,255,65,.3); background:rgba(0,255,65,.05); }
    .status-badge.inactive  { color:var(--red);  border-color:rgba(255,0,60,.3); background:rgba(255,0,60,.05); }
    .status-badge.suspended { color:var(--gold); border-color:rgba(255,215,0,.3);background:rgba(255,215,0,.05); }

    /* Empty / error states */
    .tbl-empty { text-align:center;padding:3rem;color:var(--text-dim);font-family:var(--font-mono);font-size:.78rem; }
    .tbl-empty i { font-size:2rem;display:block;margin-bottom:1rem;opacity:.3; }

    @media(max-width:600px){ .company-cards{ grid-template-columns:1fr; } }
  </style>
</head>
<body>

<?php $active_page = 'home'; include '_nav.php'; ?>

<div class="page-hero" data-bg-text="USERS">
  <div class="grid-bg"></div>
  <div class="container">
    <p class="eyebrow"><i class="fa-solid fa-users"></i> COMBINED_USERS — GROUP LAB</p>
    <h1 style="color:var(--white);">COMBINED <span style="color:var(--green);">USERS</span></h1>
    <p class="sub">
      A combined list of users from both companies. Paradox Systems users are read directly
      from our PostgreSQL database. NullCastle users are fetched live via CURL from their
      hosted API endpoint.
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
      <span class="t-cmd">SELECT * FROM users ORDER BY id; <span style="color:var(--text-dim);">-- Paradox PostgreSQL</span></span>
    </div>
    <div class="t-line">
      <span class="t-prompt"> </span>
      <?php if ($companies[0]['error']): ?>
        <span class="t-err">ERROR: <?php echo htmlspecialchars($companies[0]['error']); ?></span>
      <?php else: ?>
        <span class="t-ok"><?php echo count($companies[0]['users']); ?> rows returned — Paradox Systems</span>
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
        <span class="t-ok"><?php echo count($companies[1]['users']); ?> rows returned — NullCastle</span>
      <?php endif; ?>
    </div>
    <div class="t-line">
      <span class="t-prompt"> </span>
      <span class="t-out">total: <?php echo $total_users; ?> users across both companies</span>
    </div>
  </div>

  <!-- Summary cards -->
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
        <div class="co-status err"><i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars($co['error']); ?></div>
      <?php else: ?>
        <div class="co-status ok"><i class="fa-solid fa-circle-check"></i> Connected</div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- ── One table per company ── -->
  <?php foreach ($companies as $idx => $co):
    $color    = $co['color'];
    $tbl_id   = 'tbl_' . $co['company_id'];
    $srch_id  = 'srch_' . $co['company_id'];
    $cnt_id   = 'cnt_' . $co['company_id'];
    $co_users = $co['users'];
    $co_count = count($co_users);
    // Paradox has no department column — NullCastle does
    $show_dept = ($co['company_id'] === 'nullcastle');
  ?>
  <div class="co-table-block reveal">

    <!-- Table header with title + search -->
    <div class="co-table-header <?php echo $color; ?>">
      <div class="co-table-title">
        <i class="fa-solid <?php echo $color === 'green' ? 'fa-database' : 'fa-network-wired'; ?> <?php echo $color; ?>"></i>
        <?php echo htmlspecialchars($co['company']); ?>
      </div>
      <div class="co-table-meta">
        <span class="pill <?php echo $color; ?>">
          <?php echo $co_count; ?> users
        </span>
        <span><?php echo htmlspecialchars($co['source']); ?></span>
        <?php if ($co['error']): ?>
          <span style="color:var(--red);"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($co['error']); ?></span>
        <?php endif; ?>
        <input
          class="tbl-search"
          id="<?php echo $srch_id; ?>"
          placeholder="Search <?php echo htmlspecialchars($co['company']); ?>..."
          oninput="filterSingleTable('<?php echo $tbl_id; ?>','<?php echo $srch_id; ?>','<?php echo $cnt_id; ?>',<?php echo $co_count; ?>)"
        />
      </div>
    </div>

    <!-- Table -->
    <div class="users-wrap">
      <table id="<?php echo $tbl_id; ?>">
        <thead>
          <tr>
            <th>#</th>
            <th><i class="fa-solid fa-user" style="font-size:.55rem;margin-right:.3rem;"></i>Name</th>
            <th><i class="fa-solid fa-envelope" style="font-size:.55rem;margin-right:.3rem;"></i>Email</th>
            <th><i class="fa-solid fa-id-badge" style="font-size:.55rem;margin-right:.3rem;"></i>Role</th>
            <?php if ($show_dept): ?>
            <th><i class="fa-solid fa-building" style="font-size:.55rem;margin-right:.3rem;"></i>Dept</th>
            <?php endif; ?>
            <th><i class="fa-solid fa-calendar" style="font-size:.55rem;margin-right:.3rem;"></i>Joined</th>
            <th><i class="fa-solid fa-signal" style="font-size:.55rem;margin-right:.3rem;"></i>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($co['error']): ?>
            <tr>
              <td colspan="<?php echo $show_dept ? 7 : 6; ?>" class="tbl-empty">
                <i class="fa-solid fa-circle-xmark"></i>
                Could not load users: <?php echo htmlspecialchars($co['error']); ?>
              </td>
            </tr>
          <?php elseif (empty($co_users)): ?>
            <tr>
              <td colspan="<?php echo $show_dept ? 7 : 6; ?>" class="tbl-empty">
                <i class="fa-solid fa-database"></i>
                No users found.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($co_users as $rn => $u):
              $status    = $u['status'] ?? 'Active';
              $status_lc = strtolower($status);
            ?>
            <tr data-name="<?php echo htmlspecialchars(strtolower($u['name'] ?? '')); ?>"
                data-email="<?php echo htmlspecialchars(strtolower($u['email'] ?? '')); ?>"
                data-role="<?php echo htmlspecialchars(strtolower($u['role'] ?? '')); ?>">
              <td class="td-dim">#<?php echo str_pad($rn + 1, 2, '0', STR_PAD_LEFT); ?></td>
              <td class="td-name"><?php echo htmlspecialchars($u['name'] ?? '—'); ?></td>
              <td class="td-dim"><?php echo htmlspecialchars($u['email'] ?? '—'); ?></td>
              <td><?php echo htmlspecialchars($u['role'] ?? '—'); ?></td>
              <?php if ($show_dept): ?>
              <td class="td-dim"><?php echo htmlspecialchars($u['department'] ?: '—'); ?></td>
              <?php endif; ?>
              <td class="td-dim"><?php echo htmlspecialchars($u['joined'] ?? '—'); ?></td>
              <td>
                <span class="status-badge <?php echo $status_lc; ?>">
                  <i class="fa-solid <?php echo $status_lc === 'active' ? 'fa-circle' : 'fa-circle-xmark'; ?>"
                     style="font-size:.45rem;"></i>
                  <?php echo htmlspecialchars($status); ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Row count under each table -->
    <div style="font-family:var(--font-mono);font-size:.65rem;color:var(--text-dim);
                padding:.6rem 1rem;border:1px solid var(--border);border-top:none;background:var(--surface2);">
      <span id="<?php echo $cnt_id; ?>">Showing <b style="color:var(--<?php echo $color; ?>);"><?php echo $co_count; ?></b> of <?php echo $co_count; ?> users</span>
    </div>

  </div>
  <?php endforeach; ?>

</div>
</section>

<?php include '_footer.php'; ?>

<script>
function filterSingleTable(tblId, srchId, cntId, total) {
  const q    = document.getElementById(srchId).value.toLowerCase();
  const rows = document.querySelectorAll('#' + tblId + ' tbody tr[data-name]');
  let visible = 0;
  rows.forEach(r => {
    const show = !q
      || r.dataset.name.includes(q)
      || r.dataset.email.includes(q)
      || r.dataset.role.includes(q);
    r.style.display = show ? '' : 'none';
    if (show) visible++;
  });
  const color = document.querySelector('#' + tblId)
                  .closest('.co-table-block')
                  .querySelector('.co-table-title i').classList.contains('green') ? 'green' : 'cyan';
  document.getElementById(cntId).innerHTML =
    'Showing <b style="color:var(--' + color + ');">' + visible + '</b> of ' + total + ' users';
}
</script>
</body>
</html>