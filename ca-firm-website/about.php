<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$pageTitle = 'About';
$pageDescription = 'Learn about the CA firm, service approach and compliance workflow.';
$canonicalPath = 'about.php';
$activePage = 'about';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <p class="eyebrow">About the firm</p>
    <h1>Professional CA support for business owners, startups and individuals.</h1>
    <p><?= h(setting($data, 'site_name')) ?> helps clients handle tax, GST, audit, accounting and compliance work with a practical, document-first process.</p>
</section>

<section class="section split">
    <div>
        <p class="eyebrow">Our approach</p>
        <h2>Simple communication, complete records and timely filing.</h2>
        <p>This template is written so your firm can explain services clearly and convert visitors into calls or inquiries. You can replace this text with your actual firm profile, partner names and registrations.</p>
        <ul class="check-list">
            <li>Document checklist before work starts</li>
            <li>Clear fee discussion before filing</li>
            <li>Status updates through phone, email or WhatsApp</li>
            <li>Records maintained for future reference</li>
        </ul>
    </div>
    <div class="feature-panel">
        <h3>Office Details</h3>
        <p><?= h(setting($data, 'address')) ?></p>
        <p><?= h(setting($data, 'city')) ?>, <?= h(setting($data, 'state')) ?>, <?= h(setting($data, 'country')) ?></p>
        <p><strong>Phone:</strong> <?= h(setting($data, 'phone')) ?></p>
        <p><strong>Email:</strong> <?= h(setting($data, 'email')) ?></p>
    </div>
</section>

<section class="section tight">
    <div class="section-heading">
        <p class="eyebrow">What we handle</p>
        <h2>Core work areas</h2>
    </div>
    <div class="grid mini-grid">
        <div class="mini-card"><strong>Tax</strong><span>ITR, tax planning, notices</span></div>
        <div class="mini-card"><strong>GST</strong><span>Registration, returns, reconciliation</span></div>
        <div class="mini-card"><strong>Audit</strong><span>Tax audit and books review</span></div>
        <div class="mini-card"><strong>Startup</strong><span>Company setup and compliance</span></div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
