<?php
require_once 'cookie_handler.php';
track_product_visit('phishguard');

$p = [
  'slug'     => 'phishguard',
  'name'     => 'PhishGuard',
  'category' => 'TRAINING',
  'cat_color'=> 'gold',
  'icon_fa'  => 'fa-fish',
  'price'    => '$900',
  'period'   => 'month',
  'tagline'  => 'Real phishing simulations against your own people. Turn your weakest link into your strongest defense.',
  'image'    => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1400&q=80',
  'description' => [
    'PhishGuard runs continuous, realistic phishing simulations against your employees — using the same techniques, pretexts, and lures that actual threat actors deploy. We craft campaigns that impersonate your CEO, your IT helpdesk, your payroll provider, and your bank. Because that is what real attackers do.',
    'Every employee who clicks gets immediate in-the-moment training — not a scolding email three days later. Every employee gets a personal risk score. High-risk users get targeted micro-training modules. Over 90 days, click rates drop by an average of 78% across our client base.',
    'Your employees are the most-attacked surface area you have. PhishGuard makes them a defense layer instead.',
  ],
  'features' => [
    'Unlimited phishing simulation campaigns per month',
    'AI-generated pretexts targeting company-specific context',
    'SMS/vishing simulation (not just email)',
    'Per-employee risk scoring and trend tracking',
    'Instant in-the-moment training on click',
    '200+ training micro-modules in 12 languages',
    'Executive and board member targeted campaigns',
    'Monthly phishing threat intelligence report',
  ],
  'specs' => [
    'Campaign Types'        => 'Email, SMS, Vishing, QR code',
    'Training Modules'      => '200+ in 12 languages',
    'Avg Click Rate Drop'   => '78% over 90 days',
    'LMS Integration'       => 'Workday, Cornerstone, custom',
    'SCIM/SSO'              => 'Okta, Azure AD, Google',
    'Reporting'             => 'Real-time + monthly digest',
    'Custom Templates'      => 'Unlimited',
  ],
  'who_for' => [
    ['icon' => 'fa-people-group',   'title' => 'All-Staff Organizations',   'desc' => 'Any company where humans read email — which is every company.'],
    ['icon' => 'fa-graduation-cap', 'title' => 'Education Institutions',    'desc' => 'Faculty, staff, and student phishing simulation at scale.'],
    ['icon' => 'fa-hand-holding-dollar','title'=>'Financial Services',     'desc' => 'High-value target training for wire fraud and BEC prevention.'],
    ['icon' => 'fa-user-shield',    'title' => 'IT & Security Departments', 'desc' => 'Measure security culture and track improvement over time.'],
  ],
];

include 'product_layout.php';
