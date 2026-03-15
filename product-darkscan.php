<?php
require_once 'cookie_handler.php';
track_product_visit('darkscan');

$p = [
  'slug'     => 'darkscan',
  'name'     => 'DarkScan',
  'category' => 'INTELLIGENCE',
  'cat_color'=> 'red',
  'icon_fa'  => 'fa-eye',
  'price'    => '$1,500',
  'period'   => 'month',
  'tagline'  => 'We send agents where most companies will not look. 40,000+ dark web sources monitored 24/7.',
  'image'    => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1400&q=80',
  'description' => [
    'DarkScan monitors over 40,000 dark web forums, paste sites, criminal marketplaces, Telegram groups, and private IRC channels in real time. Your credentials, source code, customer PII, proprietary documents, and internal communications could be for sale right now — and you would not know.',
    'We find it before the buyer does. When a match is detected, you receive an immediate alert with the source, the data exposed, and a recommended containment action. For leaked credentials, we offer an automated forced-reset integration with your identity provider.',
    '"You are only seeing what they want you to see." We see everything else.',
  ],
  'features' => [
    '40,000+ dark web forums, markets, and paste sites monitored',
    'Real-time credential leak detection with forced-reset integration',
    'Source code and IP leak monitoring (GitHub, paste sites)',
    'Brand impersonation and domain spoofing detection',
    'Executive PII and personal data exposure alerts',
    'Takedown service for verified leaked content',
    'Weekly dark web intelligence briefing',
    'Threat actor profiling and attribution reports',
  ],
  'specs' => [
    'Sources Monitored'     => '40,000+',
    'Alert Latency'         => '< 15 minutes',
    'Coverage'              => 'Dark web, deep web, Telegram, IRC',
    'Languages Monitored'   => '28 languages',
    'Takedown SLA'          => '72 hours average',
    'Data Retention'        => '12 months historical',
    'IdP Integration'       => 'Okta, Azure AD, Google Workspace',
  ],
  'who_for' => [
    ['icon' => 'fa-store',         'title' => 'E-Commerce & Retail',        'desc' => 'Customer payment card and account credential monitoring.'],
    ['icon' => 'fa-building-user', 'title' => 'HR & Recruiting Firms',      'desc' => 'Employee data and candidate PII protection and leak alerting.'],
    ['icon' => 'fa-newspaper',     'title' => 'Media & Publishing',         'desc' => 'Pre-publication leak detection and intellectual property monitoring.'],
    ['icon' => 'fa-crown',         'title' => 'C-Suite & Executives',       'desc' => 'Personal data, home address, and family member exposure monitoring.'],
  ],
];

include 'product_layout.php';
