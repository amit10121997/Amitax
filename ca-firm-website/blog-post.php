<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$slug = (string) ($_GET['slug'] ?? '');
$post = find_post($data, $slug);

if ($post === null) {
    http_response_code(404);
    $pageTitle = 'Article Not Found';
    $pageDescription = 'The requested article could not be found.';
    $canonicalPath = 'blog.php';
    $activePage = 'blog';
    require __DIR__ . '/includes/header.php';
    ?>
    <section class="page-hero compact">
        <p class="eyebrow">404</p>
        <h1>Article not found.</h1>
        <a class="btn primary" href="blog.php">View Blog</a>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $post['title'] ?? 'Article';
$pageDescription = $post['excerpt'] ?? excerpt($post['content'] ?? '');
$canonicalPath = post_url($post);
$activePage = 'blog';
$structuredData = [
    base_local_business_schema($data),
    [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post['title'] ?? '',
        'datePublished' => $post['date'] ?? '',
        'author' => [
            '@type' => 'Organization',
            'name' => setting($data, 'site_name'),
        ],
    ],
];

require __DIR__ . '/includes/header.php';
?>

<article class="article">
    <a class="text-link" href="blog.php">Back to blog</a>
    <time datetime="<?= h($post['date'] ?? '') ?>"><?= h($post['date'] ?? '') ?></time>
    <h1><?= h($post['title'] ?? '') ?></h1>
    <p class="lead"><?= h($post['excerpt'] ?? '') ?></p>
    <div class="article-body">
        <?php foreach (preg_split('/\R{2,}/', (string) ($post['content'] ?? '')) ?: [] as $paragraph): ?>
            <p><?= nl2br(h(trim($paragraph))) ?></p>
        <?php endforeach; ?>
    </div>
</article>

<?php require __DIR__ . '/includes/footer.php'; ?>
