<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

const SITE_DATA_FILE = __DIR__ . '/../data/site.json';
const LEADS_DATA_FILE = __DIR__ . '/../data/leads.json';

function default_site_data(): array
{
    return [
        'settings' => [
            'site_name' => 'Sharma & Associates',
            'tagline' => 'Chartered Accountants for tax, GST, audit and compliance',
            'domain' => 'https://example.com',
            'phone' => '+91 98765 43210',
            'whatsapp' => '919876543210',
            'email' => 'info@example.com',
            'address' => 'Office 204, Business Tower, New Delhi',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'country' => 'IN',
            'hero_image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1600&q=80',
            'admin_password' => 'change-me-now',
            'admin_password_hash' => '',
        ],
        'services' => [],
        'pricing_packages' => [],
        'faqs' => [],
        'posts' => [],
        'testimonials' => [],
    ];
}

function read_json_file(string $file, array $fallback): array
{
    if (!is_file($file)) {
        return $fallback;
    }

    $raw = file_get_contents($file);
    $decoded = json_decode((string) $raw, true);

    return is_array($decoded) ? $decoded : $fallback;
}

function write_json_file(string $file, array $data): bool
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return false;
    }

    return file_put_contents($file, $json . PHP_EOL, LOCK_EX) !== false;
}

function load_site_data(): array
{
    $defaults = default_site_data();
    $stored = read_json_file(SITE_DATA_FILE, []);

    if (isset($stored['settings']) && is_array($stored['settings'])) {
        $defaults['settings'] = array_merge($defaults['settings'], $stored['settings']);
    }

    foreach (['services', 'pricing_packages', 'faqs', 'posts', 'testimonials'] as $key) {
        if (isset($stored[$key]) && is_array($stored[$key])) {
            $defaults[$key] = $stored[$key];
        }
    }

    return $defaults;
}

function save_site_data(array $data): bool
{
    return write_json_file(SITE_DATA_FILE, $data);
}

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function setting(array $data, string $key, string $default = ''): string
{
    return (string) ($data['settings'][$key] ?? $default);
}

function site_url(array $data, string $path = ''): string
{
    $domain = rtrim(setting($data, 'domain', 'https://example.com'), '/');
    $path = ltrim($path, '/');

    return $path === '' ? $domain : $domain . '/' . $path;
}

function public_path(string $path): string
{
    return ltrim($path, '/');
}

function active_class(string $activePage, string $page): string
{
    return $activePage === $page ? ' class="active"' : '';
}

function format_price(string $price): string
{
    $price = trim($price);
    return $price === '' ? 'Price on request' : $price;
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    $text = trim($text, '-');

    return $text !== '' ? $text : 'item-' . time();
}

function find_service(array $data, string $slug): ?array
{
    foreach ($data['services'] as $service) {
        if (($service['slug'] ?? '') === $slug) {
            return $service;
        }
    }

    return null;
}

function find_post(array $data, string $slug): ?array
{
    foreach ($data['posts'] as $post) {
        if (($post['slug'] ?? '') === $slug) {
            return $post;
        }
    }

    return null;
}

function service_url(array $service): string
{
    return 'service.php?slug=' . rawurlencode((string) ($service['slug'] ?? ''));
}

function post_url(array $post): string
{
    return 'blog-post.php?slug=' . rawurlencode((string) ($post['slug'] ?? ''));
}

function excerpt(string $text, int $length = 150): string
{
    $plain = trim(strip_tags($text));
    if (strlen($plain) <= $length) {
        return $plain;
    }

    return rtrim(substr($plain, 0, $length - 3)) . '...';
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

function csrf_is_valid(): bool
{
    $posted = (string) ($_POST['csrf_token'] ?? '');
    $session = (string) ($_SESSION['csrf_token'] ?? '');

    return $posted !== '' && $session !== '' && hash_equals($session, $posted);
}

function verify_admin_password(array $data, string $password): bool
{
    $hash = setting($data, 'admin_password_hash');
    if ($hash !== '' && password_verify($password, $hash)) {
        return true;
    }

    $plain = setting($data, 'admin_password');
    return $plain !== '' && hash_equals($plain, $password);
}

function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function require_admin(): void
{
    if (!is_admin_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function read_leads(): array
{
    return read_json_file(LEADS_DATA_FILE, []);
}

function append_lead(array $lead): bool
{
    $leads = read_leads();
    array_unshift($leads, $lead);

    return write_json_file(LEADS_DATA_FILE, array_slice($leads, 0, 500));
}

function split_lines(string $value): array
{
    $lines = preg_split('/\R/', $value) ?: [];
    $lines = array_map('trim', $lines);

    return array_values(array_filter($lines, static fn (string $line): bool => $line !== ''));
}

function json_ld(array $payload): string
{
    $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return $json === false ? '' : '<script type="application/ld+json">' . $json . '</script>';
}

function base_local_business_schema(array $data): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'AccountingService',
        'name' => setting($data, 'site_name'),
        'url' => site_url($data),
        'telephone' => setting($data, 'phone'),
        'email' => setting($data, 'email'),
        'image' => setting($data, 'hero_image'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => setting($data, 'address'),
            'addressLocality' => setting($data, 'city'),
            'addressRegion' => setting($data, 'state'),
            'addressCountry' => setting($data, 'country', 'IN'),
        ],
    ];
}
