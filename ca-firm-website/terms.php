<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$pageTitle = 'Terms';
$pageDescription = 'Terms of use for the CA firm website.';
$canonicalPath = 'terms.php';
$activePage = '';

require __DIR__ . '/includes/header.php';
?>

<article class="article">
    <h1>Terms of Use</h1>
    <p>The information on this website is for general awareness and service inquiry purposes only. It should not be treated as legal, tax or professional advice for a specific case.</p>
    <p>Final service scope, fees and timelines are confirmed after document review and client discussion. Government fees, penalties, late fees or third-party charges may be extra where applicable.</p>
    <p>Replace this page with terms reviewed for your firm before publishing.</p>
</article>

<?php require __DIR__ . '/includes/footer.php'; ?>
