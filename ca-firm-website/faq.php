<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$pageTitle = 'FAQ';
$pageDescription = 'Frequently asked questions about CA services, pricing, GST, tax and website inquiries.';
$canonicalPath = 'faq.php';
$activePage = 'faq';
$structuredData = [
    base_local_business_schema($data),
    [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(static function ($faq): array {
            return [
                '@type' => 'Question',
                'name' => $faq['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'] ?? '',
                ],
            ];
        }, $data['faqs']),
    ],
];

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact">
    <p class="eyebrow">FAQ</p>
    <h1>Common questions before clients call.</h1>
    <p>Edit FAQs from admin so visitors understand documents, pricing and timelines.</p>
</section>

<section class="section narrow">
    <div class="faq-list">
        <?php foreach ($data['faqs'] as $index => $faq): ?>
            <details <?= $index === 0 ? 'open' : '' ?>>
                <summary><?= h($faq['question'] ?? '') ?></summary>
                <p><?= h($faq['answer'] ?? '') ?></p>
            </details>
        <?php endforeach; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
