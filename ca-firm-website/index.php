<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$pageTitle = setting($data, 'site_name');
$pageDescription = 'Tax, GST, accounting, audit and business compliance services with transparent pricing and direct call support.';
$canonicalPath = 'index.php';
$activePage = 'home';
$structuredData = [
    base_local_business_schema($data),
    [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => setting($data, 'site_name'),
        'url' => site_url($data),
    ],
];

$featuredServices = array_slice($data['services'], 0, 6);
require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow">CA firm website template</p>
        <h1>Tax, GST, audit and compliance help with clear pricing.</h1>
        <p><?= h(setting($data, 'tagline')) ?>. Edit every service, price, phone number and page from the admin panel.</p>
        <div class="hero-actions">
            <a class="btn primary" href="services.php">View Services</a>
            <a class="btn ghost" href="contact.php">Request Callback</a>
        </div>
        <div class="quick-stats">
            <span><strong><?= count($data['services']) ?>+</strong> Services</span>
            <span><strong>Direct</strong> Call CTA</span>
            <span><strong>SEO</strong> Ready</span>
        </div>
    </div>
    <div class="hero-media">
        <img src="<?= h(setting($data, 'hero_image')) ?>" alt="Accounting desk with financial paperwork">
    </div>
</section>

<section class="section tight">
    <div class="section-heading">
        <p class="eyebrow">Find a service</p>
        <h2>What do you need help with?</h2>
    </div>
    <div class="service-search">
        <input type="search" data-service-search placeholder="Search GST, ITR, audit, accounting...">
        <a class="btn small" href="services.php">All Services</a>
    </div>
    <div class="grid service-grid" data-service-list>
        <?php foreach ($featuredServices as $service): ?>
            <article class="service-card" data-service-card data-category="<?= h($service['category'] ?? '') ?>">
                <div class="card-top">
                    <span class="service-category"><?= h($service['category'] ?? 'Service') ?></span>
                    <?php if (!empty($service['badge'])): ?>
                        <span class="badge"><?= h($service['badge']) ?></span>
                    <?php endif; ?>
                </div>
                <h3><?= h($service['name'] ?? '') ?></h3>
                <p><?= h($service['short_description'] ?? '') ?></p>
                <div class="service-meta">
                    <strong><?= h(format_price($service['price_text'] ?? '')) ?></strong>
                    <span><?= h($service['duration'] ?? '') ?></span>
                </div>
                <a class="text-link" href="<?= h(service_url($service)) ?>">Details and price</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section split">
    <div>
        <p class="eyebrow">Why clients contact us</p>
        <h2>Compliance work needs clear steps, not confusion.</h2>
        <p>Every service page includes description, timeline, features, price and direct phone CTA. Your client can reach you from any page.</p>
        <ul class="check-list">
            <li>Editable services, pricing and service categories</li>
            <li>Contact form stores leads inside admin panel</li>
            <li>SEO meta, sitemap and structured data included</li>
            <li>Works on normal PHP hosting without a database</li>
        </ul>
    </div>
    <div class="feature-panel">
        <h3>Client journey</h3>
        <ol class="steps">
            <li><span>1</span> Search or browse a service</li>
            <li><span>2</span> Open details, documents and timeline</li>
            <li><span>3</span> See price and contact options</li>
            <li><span>4</span> Submit inquiry or call directly</li>
        </ol>
    </div>
</section>

<?php if (!empty($data['testimonials'])): ?>
<section class="section muted">
    <div class="section-heading">
        <p class="eyebrow">Client words</p>
        <h2>Trust signals you can edit anytime.</h2>
    </div>
    <div class="grid testimonial-grid">
        <?php foreach ($data['testimonials'] as $testimonial): ?>
            <figure class="testimonial-card">
                <blockquote><?= h($testimonial['quote'] ?? '') ?></blockquote>
                <figcaption>
                    <strong><?= h($testimonial['name'] ?? '') ?></strong>
                    <span><?= h($testimonial['role'] ?? '') ?></span>
                </figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
