<?php
require_once 'cookie_handler.php';
track_product_visit('zerodayresponse');

$p = [
  'slug'     => 'zerodayresponse',
  'name'     => 'ZeroDay Response',
  'category' => 'INCIDENT_RESP',
  'cat_color'=> 'red',
  'icon_fa'  => 'fa-bolt',
  'price'    => '$15,000',
  'period'   => 'year',
  'tagline'  => '4-minute response SLA. Industry average is 72 hours. That gap is your entire attack surface.',
  'image'    => 'https://images.unsplash.com/photo-1563986768494-4dee2763ff3f?w=1400&q=80',
  'description' => [
    'A critical zero-day drops at 2:47AM on a Sunday. We are paged within 60 seconds. An engineer is accessing your system within 4 minutes. A patched mitigation strategy is in your hands within the hour. This is not a brochure claim. This is our contractual SLA — backed by financial penalties if we miss it.',
    'ZeroDay Response is a retainer-based annual program. When you sign, you get a dedicated incident response team that knows your environment before the crisis happens. We onboard your systems, document your architecture, and run quarterly tabletop exercises so that when a zero-day drops, there is no ramp-up time.',
    'The industry average zero-day response time is 72 hours. We consider that a catastrophic failure scenario.',
  ],
  'features' => [
    '4-minute initial engineer-on-system SLA (contractual)',
    '24/7/365 dedicated on-call response team',
    'Pre-engagement environment onboarding and documentation',
    'Parallel containment + root cause analysis tracks',
    'Regulatory notification support (GDPR, HIPAA, SEC 8-K)',
    'Quarterly tabletop exercises included',
    '20 retained incident response hours per quarter',
    'Post-incident hardening plan and security review',
  ],
  'specs' => [
    'Initial Response SLA'  => '4 minutes (contractual)',
    'On-Call Coverage'      => '24/7/365',
    'Retainer Hours'        => '20 hrs/quarter included',
    'Tabletop Exercises'    => 'Quarterly',
    'Notification Coverage' => 'GDPR, HIPAA, SEC, PCI',
    'Team Assignment'       => 'Dedicated named team',
    'SLA Penalty'           => 'Financial credit if missed',
  ],
  'who_for' => [
    ['icon' => 'fa-hospital-user',  'title' => 'Healthcare Systems',        'desc' => 'HIPAA breach notification and ransomware response with 4-minute SLA.'],
    ['icon' => 'fa-scale-balanced', 'title' => 'Legal & Professional Svcs', 'desc' => 'Client data breach response with attorney-client privilege protocols.'],
    ['icon' => 'fa-bolt',           'title' => 'Critical Infrastructure',   'desc' => 'OT/ICS incident response for utilities, energy, and manufacturing.'],
    ['icon' => 'fa-chart-bar',      'title' => 'Publicly Traded Companies', 'desc' => 'SEC 8-K incident disclosure support within the 4-day regulatory window.'],
  ],
];

include 'product_layout.php';
