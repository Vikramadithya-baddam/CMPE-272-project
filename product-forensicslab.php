<?php
require_once 'cookie_handler.php';
track_product_visit('forensicslab');

$p = [
  'slug'     => 'forensicslab',
  'name'     => 'ForensicsLab',
  'category' => 'FORENSICS',
  'cat_color'=> 'cyan',
  'icon_fa'  => 'fa-magnifying-glass',
  'price'    => '$6,000',
  'period'   => 'engagement',
  'tagline'  => 'Reconstruct the complete attack timeline. We tell you who, how, when — down to the millisecond.',
  'image'    => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1400&q=80',
  'description' => [
    'ForensicsLab reconstructs the complete attack story from raw digital artifacts. Memory dumps, network flow records, Windows event logs, registry hives, browser artifacts, file system metadata — our forensics engineers read them all fluently and assemble the timeline of exactly what happened and who did it.',
    'We work two parallel tracks simultaneously: containment and investigation. We do not make you choose between stopping the bleeding and understanding the wound. Both happen at the same time by separate teams.',
    '"Elementary." We say this once per engagement. It is a rule.',
  ],
  'features' => [
    'Complete attack timeline reconstruction (millisecond precision)',
    'Memory forensics — malware extraction and analysis',
    'Network forensics — packet capture and flow analysis',
    'Endpoint forensics — Windows, macOS, Linux',
    'Mobile device forensics (iOS, Android)',
    'Threat actor attribution with confidence scoring',
    'Chain-of-custody evidence collection for legal proceedings',
    'Litigation-ready expert witness reporting',
  ],
  'specs' => [
    'Timeline Precision'    => 'Millisecond',
    'OS Coverage'           => 'Windows, macOS, Linux, iOS, Android',
    'Evidence Handling'     => 'Full chain-of-custody',
    'Report Delivery'       => '5 business days',
    'Legal Admissibility'   => 'Court-ready reports',
    'Expert Witness'        => 'Available on request',
    'Data Preservation'     => 'Write-blocked forensic imaging',
  ],
  'who_for' => [
    ['icon' => 'fa-gavel',          'title' => 'Legal Teams & Attorneys',   'desc' => 'Court-admissible digital evidence with expert witness support.'],
    ['icon' => 'fa-building-shield','title' => 'Insurance Carriers',        'desc' => 'Cyber incident investigation for claim validation and fraud detection.'],
    ['icon' => 'fa-user-tie',       'title' => 'HR & Internal Investigations','desc' => 'Insider threat and employee misconduct digital evidence collection.'],
    ['icon' => 'fa-shield-cat',     'title' => 'Security Teams Post-Breach','desc' => 'Understanding the full attack chain to prevent recurrence.'],
  ],
];

include 'product_layout.php';
