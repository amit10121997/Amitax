<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$slug = (string) ($_GET['slug'] ?? '');
$service = find_service($data, $slug);

if ($service === null) {
    http_response_code(404);
    $pageTitle = 'Service Not Found';
    $pageDescription = 'The requested service could not be found.';
    $canonicalPath = 'services.php';
    $activePage = 'services';
    require __DIR__ . '/includes/header.php';
    ?>
    <section class="page-hero compact">
        <p class="eyebrow">404</p>
        <h1>Service not found.</h1>
        <p>Please browse all services or contact us directly.</p>
        <a class="btn primary" href="services.php">View Services</a>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $service['name'] ?? 'Service';
$pageDescription = $service['short_description'] ?? excerpt($service['description'] ?? '');
$canonicalPath = service_url($service);
$activePage = 'services';
$structuredData = [
    base_local_business_schema($data),
    [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service['name'] ?? '',
        'description' => $service['short_description'] ?? '',
        'provider' => [
            '@type' => 'AccountingService',
            'name' => setting($data, 'site_name'),
            'telephone' => setting($data, 'phone'),
        ],
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'INR',
            'description' => $service['price_text'] ?? 'Price on request',
        ],
    ],
];

$related = array_values(array_filter($data['services'], static function ($item) use ($service): bool {
    return ($item['slug'] ?? '') !== ($service['slug'] ?? '') && ($item['category'] ?? '') === ($service['category'] ?? '');
}));
$related = array_slice($related, 0, 3);

require __DIR__ . '/includes/header.php';
?>

<section class="service-detail">
    <div class="service-detail-main">
        <p class="eyebrow"><?= h($service['category'] ?? 'Service') ?></p>
        <h1><?= h($service['name'] ?? '') ?></h1>
        <p class="lead"><?= h($service['description'] ?? '') ?></p>
        <?php if (!empty($service['features'])): ?>
            <h2>What is included</h2>
            <ul class="check-list columns">
                <?php foreach ($service['features'] as $feature): ?>
                    <li><?= h($feature) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <aside class="quote-box">
        <?php if (!empty($service['badge'])): ?>
            <span class="badge"><?= h($service['badge']) ?></span>
        <?php endif; ?>
        <h2><?= h(format_price($service['price_text'] ?? '')) ?></h2>
        <p><?= h($service['duration'] ?? 'Timeline shared after review') ?></p>
        <a class="btn primary block" href="tel:<?= h(preg_replace('/\s+/', '', setting($data, 'phone'))) ?>">Call <?= h(setting($data, 'phone')) ?></a>
        <?php if (setting($data, 'whatsapp') !== ''): ?>
            <a class="btn ghost block" href="https://wa.me/<?= h(setting($data, 'whatsapp')) ?>?text=I%20need%20help%20with%20<?= rawurlencode((string) ($service['name'] ?? 'service')) ?>">Ask on WhatsApp</a>
        <?php endif; ?>
        <a class="text-link" href="contact.php?service=<?= rawurlencode((string) ($service['name'] ?? '')) ?>">Request callback</a>
    </aside>
</section>

<?php if (!empty($related)): ?>
<section class="section tight">
    <div class="section-heading">
        <p class="eyebrow">Related</p>
        <h2>More services in <?= h($service['category'] ?? 'this category') ?></h2>
    </div>
    <div class="grid service-grid">
        <?php foreach ($related as $item): ?>
            <article class="service-card">
                <h3><?= h($item['name'] ?? '') ?></h3>
                <p><?= h($item['short_description'] ?? '') ?></p>
                <a class="text-link" href="<?= h(service_url($item)) ?>">Open details</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
