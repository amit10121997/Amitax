<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$urls = [
    'index.php',
    'services.php',
    'pricing.php',
    'about.php',
    'blog.php',
    'faq.php',
    'contact.php',
    'privacy.php',
    'terms.php',
];

foreach ($data['services'] as $service) {
    $urls[] = service_url($service);
}

foreach ($data['posts'] as $post) {
    $urls[] = post_url($post);
}

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $url): ?>
    <url>
        <loc><?= h(site_url($data, $url)) ?></loc>
        <changefreq>weekly</changefreq>
        <priority><?= $url === 'index.php' ? '1.0' : '0.7' ?></priority>
    </url>
<?php endforeach; ?>
</urlset>
