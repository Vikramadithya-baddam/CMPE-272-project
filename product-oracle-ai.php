<?php
require_once 'cookie_handler.php';
track_product_visit('oracle-ai');

$p = [
  'slug'     => 'oracle-ai',
  'name'     => 'ORACLE AI Shield',
  'category' => 'AI_DEFENSE',
  'cat_color'=> 'cyan',
  'icon_fa'  => 'fa-brain',
  'price'    => '$2,800',
  'period'   => 'month',
  'tagline'  => 'AI-powered threat detection trained on 15 years of breach data. Sub-100ms response.',
  'image'    => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1400&q=80',
  'description' => [
    'ORACLE is our proprietary AI threat-detection engine — built from scratch by our own engineers on 15 years of real-world breach data, 400 million threat patterns, and continuous red-team adversarial training. It does not look for known signatures. It understands behavior.',
    'The model baselines every user, device, and service in your environment. When something deviates — even slightly — ORACLE detects it, classifies it, and triggers an automated response in under 100 milliseconds. Most security teams open their first alert email around minute 45. ORACLE is already done.',
    'Jessica Park built the first version in 96 hours on ramen and spite. She is unreasonably proud of this. She should be.',
  ],
  'features' => [
    'Behavioral baselining for every user, device, and service',
    'Sub-100ms anomaly detection and automated containment',
    'Adversarial training — updated weekly against new TTPs',
    'SIEM integration (Splunk, QRadar, Microsoft Sentinel)',
    'Automated playbooks for 200+ threat scenarios',
    'Explainable AI — every alert shows its reasoning chain',
    'Zero-trust posture enforcement layer',
    'Monthly threat intelligence digest included',
  ],
  'specs' => [
    'Detection Latency' => '< 100ms',
    'Model Updates'     => 'Weekly adversarial retraining',
    'Training Data'     => '15 years / 400M+ patterns',
    'False Positive Rate' => '< 0.003%',
    'Deployment'        => 'SaaS / On-premise / Hybrid',
    'SIEM Compatible'   => 'Splunk, QRadar, Sentinel',
    'Uptime SLA'        => '99.97%',
  ],
  'who_for' => [
    ['icon' => 'fa-shield',        'title' => 'SOC Teams',                  'desc' => 'Reduce alert fatigue by 94%. ORACLE triages so your analysts focus on what matters.'],
    ['icon' => 'fa-hospital',      'title' => 'Healthcare Organizations',   'desc' => 'HIPAA-compliant behavioral monitoring across patient data systems.'],
    ['icon' => 'fa-bank',          'title' => 'Financial Institutions',     'desc' => 'Real-time fraud and insider threat detection across trading and banking systems.'],
    ['icon' => 'fa-globe',         'title' => 'Global Enterprises',         'desc' => 'Multi-tenant deployment with region-aware data sovereignty controls.'],
  ],
];

include 'product_layout.php';
