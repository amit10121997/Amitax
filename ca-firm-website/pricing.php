<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$pageTitle = 'Pricing';
$pageDescription = 'Transparent starting prices and custom packages for GST, tax, audit, accounting and compliance services.';
$canonicalPath = 'pricing.php';
$activePage = 'pricing';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact">
    <p class="eyebrow">Pricing</p>
    <h1>Clear starting prices, with custom quotes where details matter.</h1>
    <p>Edit packages and service prices from admin. Final pricing can depend on documents, turnover, entity type and urgency.</p>
</section>

<section class="section tight">
    <div class="grid pricing-grid">
        <?php foreach ($data['pricing_packages'] as $package): ?>
            <article class="price-card">
                <h2><?= h($package['name'] ?? '') ?></h2>
                <strong><?= h($package['price_text'] ?? 'Price on request') ?></strong>
                <p><?= h($package['description'] ?? '') ?></p>
                <?php if (!empty($package['features'])): ?>
                    <ul class="check-list">
                        <?php foreach ($package['features'] as $feature): ?>
                            <li><?= h($feature) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <a class="btn primary block" href="contact.php?service=<?= rawurlencode((string) ($package['name'] ?? '')) ?>">Get Quote</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section muted">
    <div class="section-heading">
        <p class="eyebrow">Service-wise pricing</p>
        <h2>Quick price reference</h2>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Timeline</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['services'] as $service): ?>
                    <tr>
                        <td><?= h($service['name'] ?? '') ?></td>
                        <td><?= h($service['category'] ?? '') ?></td>
                        <td><?= h(format_price($service['price_text'] ?? '')) ?></td>
                        <td><?= h($service['duration'] ?? '') ?></td>
                        <td><a class="text-link" href="<?= h(service_url($service)) ?>">Details</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
