<?php
require_once 'cookie_handler.php';
track_product_visit('redops');

$p = [
  'slug'     => 'redops',
  'name'     => 'RedOps Suite',
  'category' => 'RED_TEAM',
  'cat_color'=> 'red',
  'icon_fa'  => 'fa-chess-knight',
  'price'    => '$12,000',
  'period'   => 'quarter',
  'tagline'  => 'Full adversarial simulation. We think like a nation-state actor and operate with the same tools.',
  'image'    => 'https://images.unsplash.com/photo-1510511459019-5dda7724fd87?w=1400&q=80',
  'description' => [
    'RedOps Suite is our flagship continuous red team program. Unlike a one-time penetration test, RedOps is a sustained adversarial presence in your environment — our operators are always running, always probing, using real nation-state techniques, tools, and procedures against your defenses every quarter.',
    'We operate across every domain simultaneously: digital, physical, and human. Our operators will attempt to compromise your network, clone your access badges, intercept your deliveries, compromise your supply chain vendors, and social engineer your most senior executives. All with your prior written consent, obviously.',
    'After every operation, we sit with your blue team in a purple team debrief and show them exactly how we got in. Your defenders learn from every attack. Your defenses get measurably better every quarter.',
  ],
  'features' => [
    'Continuous quarterly red team operations',
    'Nation-state TTPs (MITRE ATT&CK full coverage)',
    'Physical red team — badge cloning, tailgating, drop devices',
    'Supply chain and vendor compromise simulation',
    'C2 infrastructure operated by Paradox team',
    'Full OPSEC — operations run without tipping off IT',
    'Purple team debrief after every operation',
    'Measurable improvement metrics quarter-over-quarter',
  ],
  'specs' => [
    'Operation Frequency'   => 'Continuous / Quarterly formal ops',
    'TTPs Coverage'         => 'MITRE ATT&CK full matrix',
    'Domains'               => 'Digital, Physical, Social',
    'OPSEC Level'           => 'Full — IT not pre-notified',
    'Debrief Format'        => 'Purple team + executive brief',
    'Custom Scenarios'      => 'Yes, tailored to your threat model',
    'Team Size'             => '3 – 6 senior operators',
  ],
  'who_for' => [
    ['icon' => 'fa-shield-virus',   'title' => 'Mature Security Teams',     'desc' => 'Blue teams that need a real opponent to test and improve against.'],
    ['icon' => 'fa-landmark-dome',  'title' => 'Government & Defense',      'desc' => 'High-value target simulation at national-security threat levels.'],
    ['icon' => 'fa-microchip',      'title' => 'Technology Companies',      'desc' => 'Protect IP and customer data against sophisticated, targeted attackers.'],
    ['icon' => 'fa-trophy',         'title' => 'CISO Office',               'desc' => 'Quantifiable security improvement metrics for board reporting.'],
  ],
];

include 'product_layout.php';
