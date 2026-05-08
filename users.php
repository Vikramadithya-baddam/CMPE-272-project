<?php
// ============================================================
// PARADOX SYSTEMS — users.php
// Public Users section with:
//   - User creation form (POST → inserts into DB)
//   - User search form  (GET  → queries by name/email/phone)
// ============================================================
require_once __DIR__ . '/db_config.php';

$active_page    = 'users';
$tab            = $_GET['tab'] ?? 'search';
$message        = null;
$msg_type       = 'ok';
$search_results = [];
$searched       = false;

// ── Handle user creation (POST) ───────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $tab          = 'create';
    $first_name   = trim($_POST['first_name']   ?? '');
    $last_name    = trim($_POST['last_name']    ?? '');
    $email        = trim($_POST['email']        ?? '');
    $home_address = trim($_POST['home_address'] ?? '');
    $home_phone   = trim($_POST['home_phone']   ?? '');
    $cell_phone   = trim($_POST['cell_phone']   ?? '');
    $role         = trim($_POST['role']         ?? 'Standard Member');

    if (!$first_name || !$last_name || !$email) {
        $message  = 'First name, last name, and email are required.';
        $msg_type = 'err';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message  = 'Please enter a valid email address.';
        $msg_type = 'err';
    } else {
        try {
            $pdo  = get_db();
            $name = $first_name . ' ' . $last_name;
            $stmt = $pdo->prepare(
                'INSERT INTO users
                   (name, first_name, last_name, email, home_address,
                    home_phone, cell_phone, role, joined, status)
                 VALUES
                   (:name,:fn,:ln,:email,:addr,:hph,:cph,:role,CURRENT_DATE,\'Active\')'
            );
            $stmt->execute([
                ':name'  => $name,
                ':fn'    => $first_name,
                ':ln'    => $last_name,
                ':email' => $email,
                ':addr'  => $home_address,
                ':hph'   => $home_phone,
                ':cph'   => $cell_phone,
                ':role'  => $role,
            ]);
            $message  = "User {$first_name} {$last_name} created successfully.";
            $msg_type = 'ok';
            // Reset so form is blank after success
            $first_name = $last_name = $email = $home_address = $home_phone = $cell_phone = '';
        } catch (PDOException $e) {
            $message  = str_contains($e->getMessage(), 'unique')
                      ? 'That email address is already registered.'
                      : 'Database error: ' . $e->getMessage();
            $msg_type = 'err';
        }
    }
}

// ── Handle search (GET) ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q']) && trim($_GET['q']) !== '') {
    $tab      = 'search';
    $searched = true;
    $q        = '%' . trim($_GET['q']) . '%';
    try {
        $pdo  = get_db();
        $stmt = $pdo->prepare(
            'SELECT id, first_name, last_name, name, email, role,
                    home_address, home_phone, cell_phone,
                    joined::text AS joined, status
             FROM users
             WHERE  first_name   ILIKE :q
                OR  last_name    ILIKE :q
                OR  name         ILIKE :q
                OR  email        ILIKE :q
                OR  home_phone   ILIKE :q
                OR  cell_phone   ILIKE :q
             ORDER BY last_name, first_name'
        );
        $stmt->execute([':q' => $q]);
        $search_results = $stmt->fetchAll();
    } catch (Exception $e) {
        $message  = 'Search error: ' . $e->getMessage();
        $msg_type = 'err';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Users — Paradox Systems</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23060611'/><text y='72' x='10' font-size='68' fill='%2300ff41' font-family='monospace'>_</text></svg>" />
  <style>
    /* ── Tab switcher ── */
    .tab-bar {
      display: flex; gap: 0; margin-bottom: 2.5rem;
      border: 1px solid var(--border); overflow: hidden;
    }
    .tab-btn {
      flex: 1; padding: 1rem 1.5rem; text-align: center;
      font-family: var(--font-mono); font-size: .75rem; letter-spacing: .14em;
      text-transform: uppercase; text-decoration: none;
      color: var(--text-dim); background: var(--bg2);
      border-right: 1px solid var(--border);
      display: flex; align-items: center; justify-content: center; gap: .5rem;
      transition: all .3s;
    }
    .tab-btn:last-child { border-right: none; }
    .tab-btn:hover { background: var(--surface); color: var(--white); }
    .tab-btn.active {
      background: var(--surface2); color: var(--green);
      border-bottom: 2px solid var(--green);
      box-shadow: inset 0 -2px 0 var(--green);
    }

    /* ── Forms ── */
    .form-card {
      background: var(--surface2); border: 1px solid var(--border);
      padding: 2.5rem; position: relative; overflow: hidden;
    }
    .form-card::before {
      content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
      background: linear-gradient(90deg, transparent, var(--green), transparent);
    }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
    .fg { display: flex; flex-direction: column; gap: .45rem; }
    .fg label {
      font-family: var(--font-mono); font-size: .65rem; color: var(--green);
      letter-spacing: .16em; text-transform: uppercase;
      display: flex; align-items: center; gap: .4rem;
    }
    .fg label i { font-size: .6rem; opacity: .7; }
    .fg label .req { color: var(--red); margin-left: .15rem; }
    .fg input, .fg select, .fg textarea {
      background: var(--bg2); border: 1px solid var(--border);
      color: var(--white); font-family: var(--font-mono); font-size: .85rem;
      padding: .75rem 1rem; outline: none; transition: border-color .3s, box-shadow .3s;
      width: 100%; box-sizing: border-box;
    }
    .fg input:focus, .fg select:focus {
      border-color: var(--green);
      box-shadow: 0 0 0 2px rgba(0,255,65,.07), 0 0 12px rgba(0,255,65,.1);
    }
    .fg input::placeholder { color: var(--text-dim); font-style: italic; }
    .fg select option { background: var(--bg2); }
    .fg.full { grid-column: 1 / -1; }

    /* ── Message bar ── */
    .msg-bar {
      display: flex; align-items: center; gap: .6rem;
      padding: .9rem 1.1rem; margin-bottom: 1.5rem;
      font-family: var(--font-mono); font-size: .78rem;
      border: 1px solid; border-radius: 0;
    }
    .msg-bar.ok  { color: var(--green); border-color: rgba(0,255,65,.3);  background: rgba(0,255,65,.05); }
    .msg-bar.err { color: var(--red);   border-color: rgba(255,0,60,.3);  background: rgba(255,0,60,.05); }

    /* ── Search bar ── */
    .search-wrap {
      display: flex; gap: 0; margin-bottom: 2rem;
    }
    .search-wrap input {
      flex: 1; background: var(--bg2); border: 1px solid var(--border);
      border-right: none; color: var(--white); font-family: var(--font-mono);
      font-size: .85rem; padding: .85rem 1.2rem; outline: none;
      transition: border-color .3s;
    }
    .search-wrap input:focus { border-color: var(--green); }
    .search-wrap input::placeholder { color: var(--text-dim); }
    .search-wrap button {
      background: var(--green); color: var(--bg);
      border: 1px solid var(--green); padding: .85rem 1.6rem;
      font-family: var(--font-mono); font-size: .8rem; font-weight: 700;
      letter-spacing: .1em; cursor: pointer; transition: all .3s;
      display: flex; align-items: center; gap: .5rem;
    }
    .search-wrap button:hover {
      background: transparent; color: var(--green);
    }

    /* ── Results table ── */
    .results-wrap { overflow-x: auto; border: 1px solid var(--border); }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--surface2); }
    th {
      font-family: var(--font-mono); font-size: .65rem; color: var(--text-dim);
      letter-spacing: .12em; text-transform: uppercase; padding: .85rem 1rem;
      text-align: left; border-bottom: 1px solid var(--border); white-space: nowrap;
    }
    td {
      font-family: var(--font-mono); font-size: .77rem; color: var(--text);
      padding: .8rem 1rem; border-bottom: 1px solid rgba(255,255,255,.04);
      vertical-align: top;
    }
    tbody tr:hover td { background: var(--surface); }
    tbody tr:last-child td { border-bottom: none; }
    .td-dim  { color: var(--text-dim); font-size: .72rem; }
    .td-name { color: var(--white); font-weight: 600; }

    .role-badge { font-size: .58rem; letter-spacing: .08em; padding: .15rem .5rem;
                  display: inline-block; border: 1px solid; white-space: nowrap; }
    .role-badge.enterprise { color: var(--gold); border-color: rgba(255,215,0,.35); }
    .role-badge.premium    { color: var(--cyan); border-color: rgba(0,212,255,.35); }
    .role-badge.standard   { color: var(--text-dim); border-color: var(--border); }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; margin-right: .4rem; }
    .status-dot.active   { background: var(--green); box-shadow: 0 0 6px var(--green); }
    .status-dot.inactive { background: var(--red); }

    /* ── Empty / no results ── */
    .empty-state {
      text-align: center; padding: 4rem 2rem;
      font-family: var(--font-mono); font-size: .8rem; color: var(--text-dim);
    }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: .25; }

    @media(max-width: 700px) {
      .form-grid-2 { grid-template-columns: 1fr; }
      .tab-btn span { display: none; }
    }
  </style>
</head>
<body>

<?php include '_nav.php'; ?>

<!-- Page hero -->
<div class="page-hero" data-bg-text="USERS">
  <div class="grid-bg"></div>
  <div class="container">
    <p class="eyebrow"><i class="fa-solid fa-users"></i> USERS — PARADOX SYSTEMS</p>
    <h1 style="color:var(--white);">USER <span style="color:var(--green);">DIRECTORY</span></h1>
    <p class="sub">
      Create new users or search the directory by name, email, or phone number.
      All data is stored in PostgreSQL on Render.
    </p>
  </div>
</div>

<section>
<div class="container">

  <!-- Tab switcher -->
  <div class="tab-bar reveal">
    <a href="users.php?tab=search"
       class="tab-btn <?php echo $tab === 'search' ? 'active' : ''; ?>">
      <i class="fa-solid fa-magnifying-glass"></i>
      <span>Search Users</span>
    </a>
    <a href="users.php?tab=create"
       class="tab-btn <?php echo $tab === 'create' ? 'active' : ''; ?>">
      <i class="fa-solid fa-user-plus"></i>
      <span>Create User</span>
    </a>
  </div>

  <!-- Message bar (shown after create) -->
  <?php if ($message): ?>
  <div class="msg-bar <?php echo $msg_type; ?> reveal">
    <i class="fa-solid <?php echo $msg_type === 'ok' ? 'fa-circle-check' : 'fa-triangle-exclamation'; ?>"></i>
    <?php echo htmlspecialchars($message); ?>
  </div>
  <?php endif; ?>

  <!-- ══════════════════════════════════════════════════════════
       TAB: SEARCH
       ══════════════════════════════════════════════════════════ -->
  <?php if ($tab === 'search'): ?>

  <div class="section-tag reveal">SEARCH_USERS</div>
  <h2 class="reveal d1" style="color:var(--white);margin-bottom:1.5rem;">
    Find a <span style="color:var(--green);">User</span>
  </h2>

  <!-- Terminal hint -->
  <div class="terminal reveal d1" style="max-width:640px;margin-bottom:1.8rem;">
    <div class="terminal-bar">
      <span class="t-dot r"></span><span class="t-dot y"></span><span class="t-dot g"></span>
      <span class="t-title">users.php — search query</span>
    </div>
    <div class="t-line">
      <span class="t-prompt">$</span>
      <span class="t-cmd">SELECT * FROM users WHERE name ILIKE :q OR email ILIKE :q OR phone ILIKE :q</span>
    </div>
    <div class="t-line">
      <span class="t-prompt"> </span>
      <?php if ($searched): ?>
        <span class="t-ok"><?php echo count($search_results); ?> result(s) for
          "<?php echo htmlspecialchars($_GET['q']); ?>"</span>
      <?php else: ?>
        <span class="t-out">awaiting query...</span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Search form -->
  <form method="GET" action="users.php" class="reveal d2">
    <input type="hidden" name="tab" value="search" />
    <div class="search-wrap">
      <input
        type="text"
        name="q"
        value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"
        placeholder="Search by first name, last name, email, home phone or cell phone..."
        autofocus
      />
      <button type="submit">
        <i class="fa-solid fa-magnifying-glass"></i> SEARCH
      </button>
    </div>
  </form>

  <!-- Results -->
  <?php if ($searched): ?>
    <?php if (empty($search_results)): ?>
      <div class="empty-state reveal">
        <i class="fa-solid fa-user-slash"></i>
        No users found matching "<?php echo htmlspecialchars($_GET['q']); ?>".
      </div>
    <?php else: ?>
      <div class="results-wrap reveal">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th><i class="fa-solid fa-user" style="font-size:.55rem;margin-right:.3rem;"></i>First Name</th>
              <th><i class="fa-solid fa-user" style="font-size:.55rem;margin-right:.3rem;"></i>Last Name</th>
              <th><i class="fa-solid fa-envelope" style="font-size:.55rem;margin-right:.3rem;"></i>Email</th>
              <th><i class="fa-solid fa-house" style="font-size:.55rem;margin-right:.3rem;"></i>Home Address</th>
              <th><i class="fa-solid fa-phone" style="font-size:.55rem;margin-right:.3rem;"></i>Home Phone</th>
              <th><i class="fa-solid fa-mobile" style="font-size:.55rem;margin-right:.3rem;"></i>Cell Phone</th>
              <th><i class="fa-solid fa-id-badge" style="font-size:.55rem;margin-right:.3rem;"></i>Role</th>
              <th><i class="fa-solid fa-signal" style="font-size:.55rem;margin-right:.3rem;"></i>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($search_results as $i => $u):
              $role     = $u['role'] ?? 'Standard Member';
              $rc       = str_contains($role,'Enterprise') ? 'enterprise'
                        : (str_contains($role,'Premium') ? 'premium' : 'standard');
              $status   = $u['status'] ?? 'Active';
              $fn       = htmlspecialchars($u['first_name'] ?: explode(' ', $u['name'])[0]);
              $ln       = htmlspecialchars($u['last_name']  ?: (explode(' ', $u['name'])[1] ?? ''));
            ?>
            <tr>
              <td class="td-dim">#<?php echo str_pad($u['id'], 2, '0', STR_PAD_LEFT); ?></td>
              <td class="td-name"><?php echo $fn; ?></td>
              <td class="td-name"><?php echo $ln; ?></td>
              <td class="td-dim"><?php echo htmlspecialchars($u['email'] ?? '—'); ?></td>
              <td class="td-dim" style="max-width:180px;word-break:break-word;">
                <?php echo htmlspecialchars($u['home_address'] ?: '—'); ?>
              </td>
              <td class="td-dim"><?php echo htmlspecialchars($u['home_phone'] ?: '—'); ?></td>
              <td class="td-dim"><?php echo htmlspecialchars($u['cell_phone'] ?: '—'); ?></td>
              <td><span class="role-badge <?php echo $rc; ?>"><?php echo htmlspecialchars($role); ?></span></td>
              <td>
                <span class="status-dot <?php echo strtolower($status); ?>"></span>
                <?php echo htmlspecialchars($status); ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div style="font-family:var(--font-mono);font-size:.65rem;color:var(--text-dim);
                  padding:.6rem 1rem;border:1px solid var(--border);border-top:none;
                  background:var(--surface2);">
        <?php echo count($search_results); ?> result(s) for
        "<span style="color:var(--green);"><?php echo htmlspecialchars($_GET['q']); ?></span>"
      </div>
    <?php endif; ?>
  <?php else: ?>
    <!-- Not yet searched — show prompt -->
    <div class="empty-state reveal">
      <i class="fa-solid fa-magnifying-glass"></i>
      Enter a name, email, or phone number above to search the user directory.
    </div>
  <?php endif; ?>

  <!-- ══════════════════════════════════════════════════════════
       TAB: CREATE
       ══════════════════════════════════════════════════════════ -->
  <?php else: ?>

  <div class="section-tag reveal">CREATE_USER</div>
  <h2 class="reveal d1" style="color:var(--white);margin-bottom:1.5rem;">
    Register a <span style="color:var(--green);">New User</span>
  </h2>

  <div class="form-card reveal d1" style="max-width:780px;">
    <form method="POST" action="users.php" novalidate>
      <input type="hidden" name="action" value="create" />

      <div class="form-grid-2">

        <!-- First Name -->
        <div class="fg">
          <label for="first_name">
            <i class="fa-solid fa-user"></i> First Name <span class="req">*</span>
          </label>
          <input type="text" id="first_name" name="first_name"
                 placeholder="e.g. Elliot"
                 value="<?php echo htmlspecialchars($first_name ?? ''); ?>"
                 required />
        </div>

        <!-- Last Name -->
        <div class="fg">
          <label for="last_name">
            <i class="fa-solid fa-user"></i> Last Name <span class="req">*</span>
          </label>
          <input type="text" id="last_name" name="last_name"
                 placeholder="e.g. Alderson"
                 value="<?php echo htmlspecialchars($last_name ?? ''); ?>"
                 required />
        </div>

        <!-- Email -->
        <div class="fg">
          <label for="email">
            <i class="fa-solid fa-envelope"></i> Email Address <span class="req">*</span>
          </label>
          <input type="email" id="email" name="email"
                 placeholder="you@example.com"
                 value="<?php echo htmlspecialchars($email ?? ''); ?>"
                 required />
        </div>

        <!-- Role -->
        <div class="fg">
          <label for="role">
            <i class="fa-solid fa-id-badge"></i> Role
          </label>
          <select id="role" name="role">
            <option value="Standard Member"
              <?php echo (($role ?? '') === 'Standard Member')   ? 'selected' : ''; ?>>
              Standard Member
            </option>
            <option value="Premium Member"
              <?php echo (($role ?? '') === 'Premium Member')    ? 'selected' : ''; ?>>
              Premium Member
            </option>
            <option value="Enterprise Member"
              <?php echo (($role ?? '') === 'Enterprise Member') ? 'selected' : ''; ?>>
              Enterprise Member
            </option>
          </select>
        </div>

        <!-- Home Address (full width) -->
        <div class="fg full">
          <label for="home_address">
            <i class="fa-solid fa-house"></i> Home Address
          </label>
          <input type="text" id="home_address" name="home_address"
                 placeholder="e.g. 142 Maple St, San Francisco, CA 94102"
                 value="<?php echo htmlspecialchars($home_address ?? ''); ?>" />
        </div>

        <!-- Home Phone -->
        <div class="fg">
          <label for="home_phone">
            <i class="fa-solid fa-phone"></i> Home Phone
          </label>
          <input type="tel" id="home_phone" name="home_phone"
                 placeholder="e.g. +1 (415) 555-0100"
                 value="<?php echo htmlspecialchars($home_phone ?? ''); ?>" />
        </div>

        <!-- Cell Phone -->
        <div class="fg">
          <label for="cell_phone">
            <i class="fa-solid fa-mobile"></i> Cell Phone
          </label>
          <input type="tel" id="cell_phone" name="cell_phone"
                 placeholder="e.g. +1 (415) 555-0101"
                 value="<?php echo htmlspecialchars($cell_phone ?? ''); ?>" />
        </div>

      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;
                  margin-top:1.8rem;padding-top:1.5rem;border-top:1px solid var(--border);
                  flex-wrap:wrap;gap:1rem;">
        <span style="font-family:var(--font-mono);font-size:.65rem;color:var(--text-dim);">
          <span class="req">*</span> Required fields &nbsp;|&nbsp;
          <i class="fa-solid fa-database" style="font-size:.55rem;"></i>
          Stored in PostgreSQL on Render
        </span>
        <button type="submit" class="btn btn-filled">
          <i class="fa-solid fa-user-plus"></i> CREATE USER
        </button>
      </div>

    </form>
  </div>

  <?php endif; ?>

</div>
</section>

<?php include '_footer.php'; ?>
</body>
</html>