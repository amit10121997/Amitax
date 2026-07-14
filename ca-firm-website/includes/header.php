<?php
$data = $data ?? load_site_data();
$pageTitle = $pageTitle ?? setting($data, 'site_name');
$pageDescription = $pageDescription ?? setting($data, 'tagline');
$canonicalPath = $canonicalPath ?? '';
$activePage = $activePage ?? '';
$bodyClass = $bodyClass ?? '';
$structuredData = $structuredData ?? [base_local_business_schema($data)];
$fullTitle = $pageTitle === setting($data, 'site_name') ? $pageTitle : $pageTitle . ' | ' . setting($data, 'site_name');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($fullTitle) ?></title>
    <meta name="description" content="<?= h($pageDescription) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= h(site_url($data, $canonicalPath)) ?>">
    <meta property="og:title" content="<?= h($fullTitle) ?>">
    <meta property="og:description" content="<?= h($pageDescription) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= h(site_url($data, $canonicalPath)) ?>">
    <meta property="og:image" content="<?= h(setting($data, 'hero_image')) ?>">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php foreach ($structuredData as $schema): ?>
        <?= json_ld($schema) . PHP_EOL ?>
    <?php endforeach; ?>
</head>
<body class="<?= h($bodyClass) ?>">
    <header class="site-header">
        <a class="brand" href="index.php" aria-label="<?= h(setting($data, 'site_name')) ?> home">
            <span class="brand-mark">CA</span>
            <span>
                <strong><?= h(setting($data, 'site_name')) ?></strong>
                <small><?= h(setting($data, 'tagline')) ?></small>
            </span>
        </a>

        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="primary-nav" id="primary-nav">
            <a<?= active_class($activePage, 'home') ?> href="index.php">Home</a>
            <a<?= active_class($activePage, 'services') ?> href="services.php">Services</a>
            <a<?= active_class($activePage, 'pricing') ?> href="pricing.php">Pricing</a>
            <a<?= active_class($activePage, 'about') ?> href="about.php">About</a>
            <a<?= active_class($activePage, 'blog') ?> href="blog.php">Blog</a>
            <a<?= active_class($activePage, 'faq') ?> href="faq.php">FAQ</a>
            <a<?= active_class($activePage, 'contact') ?> href="contact.php">Contact</a>
        </nav>

        <a class="header-call" href="tel:<?= h(preg_replace('/\s+/', '', setting($data, 'phone'))) ?>">
            <span>Call</span>
            <strong><?= h(setting($data, 'phone')) ?></strong>
        </a>
    </header>
    <main>
