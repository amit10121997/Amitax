<?php
require_once __DIR__ . '/../includes/functions.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_is_valid()) {
    http_response_code(400);
    echo 'Invalid request.';
    exit;
}

$data = load_site_data();
$form = (string) ($_POST['form'] ?? '');

function clean_item_value(array $item, string $key): string
{
    return trim((string) ($item[$key] ?? ''));
}

switch ($form) {
    case 'settings':
        foreach (['site_name', 'tagline', 'domain', 'phone', 'whatsapp', 'email', 'address', 'city', 'state', 'country', 'hero_image'] as $field) {
            $data['settings'][$field] = trim((string) ($_POST[$field] ?? ''));
        }

        $newPassword = trim((string) ($_POST['admin_password'] ?? ''));
        if ($newPassword !== '') {
            $data['settings']['admin_password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
            $data['settings']['admin_password'] = '';
        }
        break;

    case 'services':
        $services = [];
        $postedServices = $_POST['services'] ?? [];
        if (is_array($postedServices)) {
            foreach ($postedServices as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $name = clean_item_value($item, 'name');
                if ($name === '') {
                    continue;
                }
                $services[] = [
                    'slug' => slugify(clean_item_value($item, 'slug') ?: $name),
                    'name' => $name,
                    'category' => clean_item_value($item, 'category'),
                    'badge' => clean_item_value($item, 'badge'),
                    'price_text' => clean_item_value($item, 'price_text'),
                    'duration' => clean_item_value($item, 'duration'),
                    'short_description' => clean_item_value($item, 'short_description'),
                    'description' => clean_item_value($item, 'description'),
                    'features' => split_lines(clean_item_value($item, 'features')),
                ];
            }
        }
        $data['services'] = $services;
        break;

    case 'pricing':
        $packages = [];
        $postedPackages = $_POST['pricing_packages'] ?? [];
        if (is_array($postedPackages)) {
            foreach ($postedPackages as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $name = clean_item_value($item, 'name');
                if ($name === '') {
                    continue;
                }
                $packages[] = [
                    'name' => $name,
                    'price_text' => clean_item_value($item, 'price_text'),
                    'description' => clean_item_value($item, 'description'),
                    'features' => split_lines(clean_item_value($item, 'features')),
                ];
            }
        }
        $data['pricing_packages'] = $packages;
        break;

    case 'faqs':
        $faqs = [];
        $postedFaqs = $_POST['faqs'] ?? [];
        if (is_array($postedFaqs)) {
            foreach ($postedFaqs as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $question = clean_item_value($item, 'question');
                $answer = clean_item_value($item, 'answer');
                if ($question === '' && $answer === '') {
                    continue;
                }
                $faqs[] = [
                    'question' => $question,
                    'answer' => $answer,
                ];
            }
        }
        $data['faqs'] = $faqs;
        break;

    case 'posts':
        $posts = [];
        $postedPosts = $_POST['posts'] ?? [];
        if (is_array($postedPosts)) {
            foreach ($postedPosts as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $title = clean_item_value($item, 'title');
                if ($title === '') {
                    continue;
                }
                $posts[] = [
                    'slug' => slugify(clean_item_value($item, 'slug') ?: $title),
                    'title' => $title,
                    'date' => clean_item_value($item, 'date') ?: date('Y-m-d'),
                    'excerpt' => clean_item_value($item, 'excerpt'),
                    'content' => clean_item_value($item, 'content'),
                ];
            }
        }
        $data['posts'] = $posts;
        break;

    case 'testimonials':
        $testimonials = [];
        $postedTestimonials = $_POST['testimonials'] ?? [];
        if (is_array($postedTestimonials)) {
            foreach ($postedTestimonials as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $name = clean_item_value($item, 'name');
                $quote = clean_item_value($item, 'quote');
                if ($name === '' && $quote === '') {
                    continue;
                }
                $testimonials[] = [
                    'name' => $name,
                    'role' => clean_item_value($item, 'role'),
                    'quote' => $quote,
                ];
            }
        }
        $data['testimonials'] = $testimonials;
        break;

    default:
        http_response_code(400);
        echo 'Unknown form.';
        exit;
}

if (!save_site_data($data)) {
    http_response_code(500);
    echo 'Could not save data. Make sure the data folder is writable.';
    exit;
}

$anchors = [
    'settings' => 'settings',
    'services' => 'services',
    'pricing' => 'pricing',
    'faqs' => 'faqs',
    'posts' => 'blog',
    'testimonials' => 'testimonials',
];
$anchor = $anchors[$form] ?? 'settings';

header('Location: index.php?saved=1#' . rawurlencode($anchor));
exit;
