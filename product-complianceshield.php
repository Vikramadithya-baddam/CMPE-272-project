<?php
require_once 'cookie_handler.php';
track_product_visit('complianceshield');

$p = [
  'slug'     => 'complianceshield',
  'name'     => 'ComplianceShield',
  'category' => 'COMPLIANCE',
  'cat_color'=> 'green',
  'icon_fa'  => 'fa-shield-halved',
  'price'    => '$2,100',
  'period'   => 'month',
  'tagline'  => 'Turn 6-month audit prep into a continuous background process. SOC 2, ISO 27001, HIPAA, GDPR, PCI-DSS.',
  'image'    => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1400&q=80',
  'description' => [
    'ComplianceShield automates the most painful parts of security compliance: evidence collection, control mapping, policy management, vendor risk assessment, and audit preparation. What most companies spend 3 to 6 months preparing for, ComplianceShield keeps ready 365 days a year.',
    'The platform maps your controls across SOC 2 Type II, ISO 27001, HIPAA, GDPR, and PCI-DSS simultaneously. Fix a control once and it satisfies requirements across every framework. Your compliance posture is always audit-ready — not just in the sprint before the auditor shows up.',
    'Toby from our HR team says this is "the first compliance tool that does not make him want to resign." He sets the bar low. ComplianceShield clears it convincingly.',
  ],
  'features' => [
    'SOC 2 Type II, ISO 27001, HIPAA, GDPR, PCI-DSS coverage',
    'Automated evidence collection from 100+ integrations',
    'Cross-framework control mapping — fix once, satisfy all',
    'Vendor risk assessment and third-party management',
    'Policy management with version control and attestations',
    'Continuous control monitoring with drift alerts',
    'Auditor-ready exportable evidence packages',
    'GRC workflow with task assignment and due dates',
  ],
  'specs' => [
    'Frameworks'              => 'SOC 2, ISO 27001, HIPAA, GDPR, PCI-DSS',
    'Integrations'            => '100+ (AWS, GCP, GitHub, Jira, etc.)',
    'Evidence Collection'     => 'Automated + manual',
    'Audit Readiness'         => 'Continuous',
    'Vendor Assessments'      => 'Unlimited',
    'Policy Templates'        => '300+ pre-built',
    'Avg Setup Time'          => '2 weeks to first audit',
  ],
  'who_for' => [
    ['icon' => 'fa-rocket',         'title' => 'SaaS Companies',            'desc' => 'SOC 2 Type II for enterprise sales — close deals that need compliance proof.'],
    ['icon' => 'fa-heart-pulse',    'title' => 'Healthcare Technology',     'desc' => 'HIPAA compliance automation for health tech and EHR vendors.'],
    ['icon' => 'fa-money-bill-wave','title' => 'Fintech & Payments',        'desc' => 'PCI-DSS Level 1 certification support and continuous compliance.'],
    ['icon' => 'fa-earth-europe',   'title' => 'EU-Operating Companies',    'desc' => 'GDPR Article 32 technical measures documentation and DPA management.'],
  ],
];

include 'product_layout.php';
