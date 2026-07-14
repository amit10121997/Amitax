<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$pageTitle = 'Blog';
$pageDescription = 'Tax, GST and compliance updates from the CA firm.';
$canonicalPath = 'blog.php';
$activePage = 'blog';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact">
    <p class="eyebrow">Blog</p>
    <h1>Helpful tax, GST and compliance notes.</h1>
    <p>Use admin to edit posts. Keep content original and useful for better search performance.</p>
</section>

<section class="section tight">
    <div class="grid blog-grid">
        <?php foreach ($data['posts'] as $post): ?>
            <article class="blog-card">
                <time datetime="<?= h($post['date'] ?? '') ?>"><?= h($post['date'] ?? '') ?></time>
                <h2><?= h($post['title'] ?? '') ?></h2>
                <p><?= h($post['excerpt'] ?? excerpt($post['content'] ?? '')) ?></p>
                <a class="text-link" href="<?= h(post_url($post)) ?>">Read article</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
