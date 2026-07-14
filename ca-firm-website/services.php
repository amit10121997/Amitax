<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$pageTitle = 'Services';
$pageDescription = 'Browse CA services including GST registration, GST return filing, ITR filing, tax audit, accounting and startup compliance.';
$canonicalPath = 'services.php';
$activePage = 'services';

$categories = array_values(array_unique(array_filter(array_map(static fn ($service) => $service['category'] ?? '', $data['services']))));
$structuredData = [
    base_local_business_schema($data),
    [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'itemListElement' => array_map(static function ($service, $index): array {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $service['name'] ?? '',
                'url' => service_url($service),
            ];
        }, $data['services'], array_keys($data['services'])),
    ],
];

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact">
    <p class="eyebrow">Services</p>
    <h1>CA services your clients can browse, compare and contact for.</h1>
    <p>Use the admin panel to add new services, change prices, update descriptions and manage categories.</p>
</section>

<section class="section tight">
    <div class="toolbar">
        <input type="search" data-service-search placeholder="Search services...">
        <select data-service-filter aria-label="Filter services">
            <option value="">All categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= h($category) ?>"><?= h($category) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="grid service-grid" data-service-list>
        <?php foreach ($data['services'] as $service): ?>
            <article class="service-card" data-service-card data-category="<?= h($service['category'] ?? '') ?>">
                <div class="card-top">
                    <span class="service-category"><?= h($service['category'] ?? 'Service') ?></span>
                    <?php if (!empty($service['badge'])): ?>
                        <span class="badge"><?= h($service['badge']) ?></span>
                    <?php endif; ?>
                </div>
                <h2><?= h($service['name'] ?? '') ?></h2>
                <p><?= h($service['short_description'] ?? '') ?></p>
                <div class="service-meta">
                    <strong><?= h(format_price($service['price_text'] ?? '')) ?></strong>
                    <span><?= h($service['duration'] ?? '') ?></span>
                </div>
                <a class="text-link" href="<?= h(service_url($service)) ?>">Open details</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
