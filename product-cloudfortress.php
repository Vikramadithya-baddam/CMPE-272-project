<?php
require_once 'cookie_handler.php';
track_product_visit('cloudfortress');

$p = [
  'slug'     => 'cloudfortress',
  'name'     => 'CloudFortress',
  'category' => 'CLOUD_SEC',
  'cat_color'=> 'cyan',
  'icon_fa'  => 'fa-cloud',
  'price'    => '$3,400',
  'period'   => 'month',
  'tagline'  => 'Complete cloud security posture management for AWS, Azure, and GCP. Find misconfigs before attackers do.',
  'image'    => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=1400&q=80',
  'description' => [
    'CloudFortress is continuous cloud security posture management across AWS, Azure, and GCP simultaneously. It scans your entire cloud environment — every S3 bucket, every IAM policy, every security group, every Lambda function, every Kubernetes cluster — and identifies every misconfiguration, overpermission, and exposed resource in real time.',
    'The average enterprise cloud environment has 34 critical misconfigurations at any given time. Most are not the result of negligence — they are the result of speed. CloudFortress finds them continuously so you can fix them continuously.',
    'Aisha from our infrastructure team has personally misconfigured an S3 bucket in production exactly once. CloudFortress caught it in 3 minutes. She tells this story at every client onboarding. It is effective.',
  ],
  'features' => [
    'Continuous posture scanning across AWS, Azure, GCP',
    'IAM privilege escalation path detection',
    'Public exposure detection — S3, blobs, storage buckets',
    'Kubernetes and container security assessment',
    'Infrastructure-as-Code scanning (Terraform, CloudFormation)',
    'One-click remediation guides for every finding',
    'Cloud spend anomaly and cryptojacking detection',
    'CIS Benchmark and CSPM compliance reporting',
  ],
  'specs' => [
    'Cloud Platforms'     => 'AWS, Azure, GCP, multi-cloud',
    'Scan Frequency'      => 'Continuous (5-min interval)',
    'IaC Scanning'        => 'Terraform, CloudFormation, Pulumi',
    'Frameworks'          => 'CIS, NIST, PCI, HIPAA, SOC 2',
    'Alert Latency'       => '< 5 minutes',
    'API Integration'     => 'REST API + Webhook',
    'Setup Time'          => '< 1 hour',
  ],
  'who_for' => [
    ['icon' => 'fa-cloud-arrow-up', 'title' => 'Cloud-Native Startups',     'desc' => 'Ship fast without leaving security holes across your entire cloud stack.'],
    ['icon' => 'fa-arrows-spin',    'title' => 'DevOps & Platform Teams',   'desc' => 'Shift-left security integrated into your CI/CD pipeline.'],
    ['icon' => 'fa-briefcase',      'title' => 'Enterprise Cloud Ops',      'desc' => 'Multi-account, multi-cloud visibility with centralized governance.'],
    ['icon' => 'fa-file-shield',    'title' => 'Compliance Teams',          'desc' => 'Automated evidence collection for cloud-based compliance frameworks.'],
  ],
];

include 'product_layout.php';
