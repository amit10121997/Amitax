<?php
require_once __DIR__ . '/includes/functions.php';

$data = load_site_data();
$pageTitle = 'Contact';
$pageDescription = 'Contact the CA firm for GST, tax, audit, accounting and compliance support.';
$canonicalPath = 'contact.php';
$activePage = 'contact';
$selectedService = (string) ($_GET['service'] ?? '');
$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_is_valid()) {
        $errors[] = 'Security token expired. Please submit again.';
    }

    if (!empty($_POST['website'])) {
        $errors[] = 'Spam detected.';
    }

    $name = trim((string) ($_POST['name'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $serviceName = trim((string) ($_POST['service'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if ($name === '') {
        $errors[] = 'Please enter your name.';
    }
    if ($phone === '') {
        $errors[] = 'Please enter your phone number.';
    }
    if ($message === '') {
        $errors[] = 'Please enter your message.';
    }

    if (!$errors) {
        $success = append_lead([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'service' => $serviceName,
            'message' => $message,
            'created_at' => date('c'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);
        if (!$success) {
            $errors[] = 'Could not save inquiry. Please call directly.';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact">
    <p class="eyebrow">Contact</p>
    <h1>Call or send your requirement.</h1>
    <p>Phone, WhatsApp and lead form are connected to editable settings.</p>
</section>

<section class="contact-layout">
    <div class="contact-card">
        <h2>Direct contact</h2>
        <p><strong>Phone:</strong> <a href="tel:<?= h(preg_replace('/\s+/', '', setting($data, 'phone'))) ?>"><?= h(setting($data, 'phone')) ?></a></p>
        <p><strong>Email:</strong> <a href="mailto:<?= h(setting($data, 'email')) ?>"><?= h(setting($data, 'email')) ?></a></p>
        <p><strong>Address:</strong> <?= h(setting($data, 'address')) ?>, <?= h(setting($data, 'city')) ?></p>
        <?php if (setting($data, 'whatsapp') !== ''): ?>
            <a class="btn ghost block" href="https://wa.me/<?= h(setting($data, 'whatsapp')) ?>">Open WhatsApp</a>
        <?php endif; ?>
    </div>

    <form class="form-card" method="post">
        <h2>Request callback</h2>
        <?php if ($success): ?>
            <div class="notice success">Inquiry saved. We will contact you soon.</div>
        <?php endif; ?>
        <?php foreach ($errors as $error): ?>
            <div class="notice error"><?= h($error) ?></div>
        <?php endforeach; ?>
        <?= csrf_field() ?>
        <label>
            Name
            <input name="name" required value="<?= h($_POST['name'] ?? '') ?>">
        </label>
        <label>
            Phone
            <input name="phone" required value="<?= h($_POST['phone'] ?? '') ?>">
        </label>
        <label>
            Email
            <input type="email" name="email" value="<?= h($_POST['email'] ?? '') ?>">
        </label>
        <label>
            Service
            <select name="service">
                <option value="">Select service</option>
                <?php foreach ($data['services'] as $service): ?>
                    <?php $serviceName = (string) ($service['name'] ?? ''); ?>
                    <option value="<?= h($serviceName) ?>" <?= $selectedService === $serviceName ? 'selected' : '' ?>><?= h($serviceName) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Message
            <textarea name="message" required rows="5"><?= h($_POST['message'] ?? '') ?></textarea>
        </label>
        <label class="hidden-field">
            Website
            <input name="website" tabindex="-1" autocomplete="off">
        </label>
        <button class="btn primary" type="submit">Submit Inquiry</button>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
