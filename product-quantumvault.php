<?php
require_once 'cookie_handler.php';
track_product_visit('quantumvault');

$p = [
  'slug'     => 'quantumvault',
  'name'     => 'QuantumVault',
  'category' => 'ENCRYPTION',
  'cat_color'=> 'gold',
  'icon_fa'  => 'fa-lock',
  'price'    => '$4,200',
  'period'   => 'month',
  'tagline'  => 'Post-quantum cryptography suite. Protect your data against computers that do not exist yet — because they will.',
  'image'    => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=1400&q=80',
  'description' => [
    'QuantumVault implements NIST-approved post-quantum cryptographic algorithms across your entire data stack. The harvest-now-decrypt-later threat is not theoretical — adversaries are already collecting your encrypted traffic today with plans to decrypt it when quantum computers reach cryptographic scale in 4–6 years.',
    'By then, retroactive protection is impossible. QuantumVault migrates you to Kyber, Dilithium, and SPHINCS+ today so that when that day comes, your data is still unreadable to anyone who collected it.',
    'Dr. Kozlov describes this as "trivially straightforward." The rest of the engineering team has stopped asking him to explain it.',
  ],
  'features' => [
    'Kyber-1024 post-quantum key encapsulation',
    'Dilithium digital signatures (quantum-safe)',
    'SPHINCS+ stateless hash-based signatures',
    'Transparent migration from RSA/ECC — no downtime',
    'Data at rest and in transit full coverage',
    'HSM integration and key ceremony management',
    'FIPS 140-3 compliance documentation included',
    'Crypto-agility layer for future algorithm upgrades',
  ],
  'specs' => [
    'Algorithms'         => 'Kyber-1024, Dilithium, SPHINCS+',
    'Standard'           => 'NIST PQC Round 3 Finalists',
    'Migration Downtime' => '0 (transparent swap)',
    'Key Management'     => 'HSM + Software',
    'Compliance'         => 'FIPS 140-3, CNSA 2.0',
    'Deployment'         => 'SaaS API / SDK / On-prem',
    'Audit Logging'      => 'Immutable, tamper-evident',
  ],
  'who_for' => [
    ['icon' => 'fa-landmark',    'title' => 'Government & Defense',      'desc' => 'CNSA 2.0 readiness for classified and sensitive data systems.'],
    ['icon' => 'fa-microscope',  'title' => 'Research Institutions',     'desc' => 'Protect IP and research data with a 20-year security horizon.'],
    ['icon' => 'fa-suitcase',    'title' => 'Financial Services',        'desc' => 'Long-term transaction record protection against future decryption.'],
    ['icon' => 'fa-pills',       'title' => 'Healthcare & Pharma',       'desc' => 'Patient records and trial data encrypted against quantum-era threats.'],
  ],
];

include 'product_layout.php';
