<?php
// ============================================================
// PARADOX SYSTEMS — cookie_handler.php
// Tracks last 5 visited products (recently-visited)
// Tracks most visited products (visit counts)
//
// Cookie: paradox_recent — JSON array, newest first, max 5
// Cookie: paradox_visits — JSON object {slug: count}
// Expiry: 30 days
//
// MUST be included BEFORE any HTML output
// ============================================================

define('COOKIE_EXPIRY', time() + (30 * 24 * 60 * 60));
define('COOKIE_PATH',   '/');

// ── Product catalog ───────────────────────────────────────────
// Central reference for all 10 products
function get_catalog() {
    return [
        'pentestpro'       => ['name' => 'PenTest Pro',         'url' => 'product-pentestpro.php',       'cat' => 'OFFENSIVE_SEC',  'price' => '$8,500/engagement',  'icon' => 'fa-bug',              'color' => 'green'],
        'oracle-ai'        => ['name' => 'ORACLE AI Shield',    'url' => 'product-oracle-ai.php',        'cat' => 'AI_DEFENSE',     'price' => '$2,800/mo',          'icon' => 'fa-brain',            'color' => 'cyan'],
        'quantumvault'     => ['name' => 'QuantumVault',        'url' => 'product-quantumvault.php',     'cat' => 'ENCRYPTION',     'price' => '$4,200/mo',          'icon' => 'fa-lock',             'color' => 'gold'],
        'darkscan'         => ['name' => 'DarkScan',            'url' => 'product-darkscan.php',         'cat' => 'INTELLIGENCE',   'price' => '$1,500/mo',          'icon' => 'fa-eye',              'color' => 'red'],
        'zerodayresponse'  => ['name' => 'ZeroDay Response',    'url' => 'product-zerodayresponse.php',  'cat' => 'INCIDENT_RESP',  'price' => '$15,000/yr',         'icon' => 'fa-bolt',             'color' => 'red'],
        'forensicslab'     => ['name' => 'ForensicsLab',        'url' => 'product-forensicslab.php',     'cat' => 'FORENSICS',      'price' => '$6,000/engagement',  'icon' => 'fa-magnifying-glass', 'color' => 'cyan'],
        'phishguard'       => ['name' => 'PhishGuard',          'url' => 'product-phishguard.php',       'cat' => 'TRAINING',       'price' => '$900/mo',            'icon' => 'fa-fish',             'color' => 'gold'],
        'cloudfortress'    => ['name' => 'CloudFortress',       'url' => 'product-cloudfortress.php',    'cat' => 'CLOUD_SEC',      'price' => '$3,400/mo',          'icon' => 'fa-cloud',            'color' => 'cyan'],
        'redops'           => ['name' => 'RedOps Suite',        'url' => 'product-redops.php',           'cat' => 'RED_TEAM',       'price' => '$12,000/quarter',    'icon' => 'fa-chess-knight',     'color' => 'red'],
        'complianceshield' => ['name' => 'ComplianceShield',    'url' => 'product-complianceshield.php', 'cat' => 'COMPLIANCE',     'price' => '$2,100/mo',          'icon' => 'fa-shield-halved',    'color' => 'green'],
    ];
}

// ── Read cookies ──────────────────────────────────────────────
function read_recent() {
    if (empty($_COOKIE['paradox_recent'])) return [];
    $d = json_decode(stripslashes($_COOKIE['paradox_recent']), true);
    return is_array($d) ? $d : [];
}

function read_visits() {
    if (empty($_COOKIE['paradox_visits'])) return [];
    $d = json_decode(stripslashes($_COOKIE['paradox_visits']), true);
    return is_array($d) ? $d : [];
}

// ── Update both cookies on product page visit ─────────────────
function track_product_visit($slug) {
    // Recently visited: remove duplicate, add to front, keep 5
    $recent = read_recent();
    $recent = array_values(array_filter($recent, function($s) use ($slug) { return $s !== $slug; }));
    array_unshift($recent, $slug);
    $recent = array_slice($recent, 0, 5);
    setcookie('paradox_recent', json_encode($recent), COOKIE_EXPIRY, COOKIE_PATH);

    // Most visited: increment count
    $visits = read_visits();
    $visits[$slug] = isset($visits[$slug]) ? $visits[$slug] + 1 : 1;
    setcookie('paradox_visits', json_encode($visits), COOKIE_EXPIRY, COOKIE_PATH);
}

// ── Get top 5 most visited ────────────────────────────────────
function get_most_visited($limit = 5) {
    $visits = read_visits();
    if (empty($visits)) return [];
    arsort($visits); // sort descending by count
    return array_slice($visits, 0, $limit, true);
}
