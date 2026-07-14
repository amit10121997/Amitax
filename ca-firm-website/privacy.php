<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$pageTitle = 'Privacy Policy';
$pageDescription = 'Privacy policy for the CA firm website.';
$canonicalPath = 'privacy.php';
$activePage = '';

require __DIR__ . '/includes/header.php';
?>

<article class="article">
    <h1>Privacy Policy</h1>
    <p>This website collects contact details submitted through forms, including name, phone, email, selected service and message. This information is used only to respond to inquiries and provide services.</p>
    <p>Do not submit sensitive documents through this website unless the firm has requested them through a secure channel. Update this policy according to your actual practice, tools and applicable law.</p>
    <p>For privacy requests, contact <?= h(setting($data, 'email')) ?>.</p>
</article>

<?php require __DIR__ . '/includes/footer.php'; ?>
