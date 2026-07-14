<?php
require_once __DIR__ . '/../includes/functions.php';

$data = load_site_data();
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'login') {
    if (!csrf_is_valid()) {
        $loginError = 'Security token expired. Please try again.';
    } elseif (verify_admin_password($data, (string) ($_POST['password'] ?? ''))) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $loginError = 'Wrong password.';
    }
}

if (!is_admin_logged_in()):
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-page">
    <main class="admin-login">
        <form class="form-card" method="post">
            <p class="eyebrow">Admin</p>
            <h1>Login</h1>
            <p>Default password is <strong>change-me-now</strong>. Change it after first login.</p>
            <?php if ($loginError !== ''): ?>
                <div class="notice error"><?= h($loginError) ?></div>
            <?php endif; ?>
            <?= csrf_field() ?>
            <input type="hidden" name="form" value="login">
            <label>
                Password
                <input type="password" name="password" required autofocus>
            </label>
            <button class="btn primary block" type="submit">Login</button>
            <a class="text-link" href="../index.php">Back to website</a>
        </form>
    </main>
</body>
</html>
<?php
exit;
endif;

$leads = read_leads();
$saved = isset($_GET['saved']);
$serviceItems = $data['services'] ?: [[
    'name' => '',
    'slug' => '',
    'category' => '',
    'badge' => '',
    'price_text' => '',
    'duration' => '',
    'short_description' => '',
    'description' => '',
    'features' => [],
]];
$packageItems = $data['pricing_packages'] ?: [[
    'name' => '',
    'price_text' => '',
    'description' => '',
    'features' => [],
]];
$faqItems = $data['faqs'] ?: [['question' => '', 'answer' => '']];
$postItems = $data['posts'] ?: [[
    'title' => '',
    'slug' => '',
    'date' => date('Y-m-d'),
    'excerpt' => '',
    'content' => '',
]];
$testimonialItems = $data['testimonials'] ?: [['name' => '', 'role' => '', 'quote' => '']];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Website Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-page">
    <header class="admin-header">
        <div>
            <p class="eyebrow">Website Admin</p>
            <h1><?= h(setting($data, 'site_name')) ?></h1>
        </div>
        <nav>
            <a class="btn small ghost" href="../index.php">View Site</a>
            <a class="btn small" href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="admin-shell">
        <?php if ($saved): ?>
            <div class="notice success">Saved successfully.</div>
        <?php endif; ?>

        <aside class="admin-nav">
            <a href="#settings">Settings</a>
            <a href="#services">Services</a>
            <a href="#pricing">Pricing</a>
            <a href="#faqs">FAQ</a>
            <a href="#blog">Blog</a>
            <a href="#testimonials">Testimonials</a>
            <a href="#leads">Leads</a>
        </aside>

        <div class="admin-content">
            <section class="admin-section" id="settings">
                <form method="post" action="save.php">
                    <?= csrf_field() ?>
                    <input type="hidden" name="form" value="settings">
                    <div class="admin-section-head">
                        <h2>Settings</h2>
                        <button class="btn primary" type="submit">Save Settings</button>
                    </div>
                    <div class="form-grid">
                        <?php foreach (['site_name', 'tagline', 'domain', 'phone', 'whatsapp', 'email', 'address', 'city', 'state', 'country', 'hero_image'] as $field): ?>
                            <label>
                                <?= h(ucwords(str_replace('_', ' ', $field))) ?>
                                <input name="<?= h($field) ?>" value="<?= h(setting($data, $field)) ?>">
                            </label>
                        <?php endforeach; ?>
                        <label>
                            New admin password
                            <input type="password" name="admin_password" placeholder="Leave blank to keep current password">
                        </label>
                    </div>
                </form>
            </section>

            <section class="admin-section" id="services">
                <form method="post" action="save.php" data-repeater-form="services">
                    <?= csrf_field() ?>
                    <input type="hidden" name="form" value="services">
                    <div class="admin-section-head">
                        <h2>Services</h2>
                        <div>
                            <button class="btn small ghost" type="button" data-add-item="#services-list">Add Service</button>
                            <button class="btn primary" type="submit">Save Services</button>
                        </div>
                    </div>
                    <div class="admin-list" id="services-list">
                        <?php foreach ($serviceItems as $i => $service): ?>
                            <div class="admin-item" data-repeater-item>
                                <div class="admin-item-head">
                                    <h3><?= h($service['name'] ?? 'Service') ?></h3>
                                    <button class="btn small danger" type="button" data-remove-item>Remove</button>
                                </div>
                                <div class="form-grid">
                                    <label>Name<input name="services[<?= $i ?>][name]" value="<?= h($service['name'] ?? '') ?>"></label>
                                    <label>Slug<input name="services[<?= $i ?>][slug]" value="<?= h($service['slug'] ?? '') ?>"></label>
                                    <label>Category<input name="services[<?= $i ?>][category]" value="<?= h($service['category'] ?? '') ?>"></label>
                                    <label>Badge<input name="services[<?= $i ?>][badge]" value="<?= h($service['badge'] ?? '') ?>"></label>
                                    <label>Price<input name="services[<?= $i ?>][price_text]" value="<?= h($service['price_text'] ?? '') ?>"></label>
                                    <label>Duration<input name="services[<?= $i ?>][duration]" value="<?= h($service['duration'] ?? '') ?>"></label>
                                </div>
                                <label>Short description<textarea name="services[<?= $i ?>][short_description]" rows="2"><?= h($service['short_description'] ?? '') ?></textarea></label>
                                <label>Full description<textarea name="services[<?= $i ?>][description]" rows="4"><?= h($service['description'] ?? '') ?></textarea></label>
                                <label>Features, one per line<textarea name="services[<?= $i ?>][features]" rows="5"><?= h(implode("\n", $service['features'] ?? [])) ?></textarea></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </section>

            <section class="admin-section" id="pricing">
                <form method="post" action="save.php" data-repeater-form="pricing_packages">
                    <?= csrf_field() ?>
                    <input type="hidden" name="form" value="pricing">
                    <div class="admin-section-head">
                        <h2>Pricing Packages</h2>
                        <div>
                            <button class="btn small ghost" type="button" data-add-item="#pricing-list">Add Package</button>
                            <button class="btn primary" type="submit">Save Pricing</button>
                        </div>
                    </div>
                    <div class="admin-list" id="pricing-list">
                        <?php foreach ($packageItems as $i => $package): ?>
                            <div class="admin-item" data-repeater-item>
                                <div class="admin-item-head">
                                    <h3><?= h($package['name'] ?? 'Package') ?></h3>
                                    <button class="btn small danger" type="button" data-remove-item>Remove</button>
                                </div>
                                <div class="form-grid">
                                    <label>Name<input name="pricing_packages[<?= $i ?>][name]" value="<?= h($package['name'] ?? '') ?>"></label>
                                    <label>Price<input name="pricing_packages[<?= $i ?>][price_text]" value="<?= h($package['price_text'] ?? '') ?>"></label>
                                </div>
                                <label>Description<textarea name="pricing_packages[<?= $i ?>][description]" rows="3"><?= h($package['description'] ?? '') ?></textarea></label>
                                <label>Features, one per line<textarea name="pricing_packages[<?= $i ?>][features]" rows="5"><?= h(implode("\n", $package['features'] ?? [])) ?></textarea></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </section>

            <section class="admin-section" id="faqs">
                <form method="post" action="save.php" data-repeater-form="faqs">
                    <?= csrf_field() ?>
                    <input type="hidden" name="form" value="faqs">
                    <div class="admin-section-head">
                        <h2>FAQ</h2>
                        <div>
                            <button class="btn small ghost" type="button" data-add-item="#faq-list">Add FAQ</button>
                            <button class="btn primary" type="submit">Save FAQ</button>
                        </div>
                    </div>
                    <div class="admin-list" id="faq-list">
                        <?php foreach ($faqItems as $i => $faq): ?>
                            <div class="admin-item" data-repeater-item>
                                <div class="admin-item-head">
                                    <h3>Question</h3>
                                    <button class="btn small danger" type="button" data-remove-item>Remove</button>
                                </div>
                                <label>Question<input name="faqs[<?= $i ?>][question]" value="<?= h($faq['question'] ?? '') ?>"></label>
                                <label>Answer<textarea name="faqs[<?= $i ?>][answer]" rows="3"><?= h($faq['answer'] ?? '') ?></textarea></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </section>

            <section class="admin-section" id="blog">
                <form method="post" action="save.php" data-repeater-form="posts">
                    <?= csrf_field() ?>
                    <input type="hidden" name="form" value="posts">
                    <div class="admin-section-head">
                        <h2>Blog Posts</h2>
                        <div>
                            <button class="btn small ghost" type="button" data-add-item="#post-list">Add Post</button>
                            <button class="btn primary" type="submit">Save Posts</button>
                        </div>
                    </div>
                    <div class="admin-list" id="post-list">
                        <?php foreach ($postItems as $i => $post): ?>
                            <div class="admin-item" data-repeater-item>
                                <div class="admin-item-head">
                                    <h3><?= h($post['title'] ?? 'Post') ?></h3>
                                    <button class="btn small danger" type="button" data-remove-item>Remove</button>
                                </div>
                                <div class="form-grid">
                                    <label>Title<input name="posts[<?= $i ?>][title]" value="<?= h($post['title'] ?? '') ?>"></label>
                                    <label>Slug<input name="posts[<?= $i ?>][slug]" value="<?= h($post['slug'] ?? '') ?>"></label>
                                    <label>Date<input type="date" name="posts[<?= $i ?>][date]" value="<?= h($post['date'] ?? '') ?>"></label>
                                </div>
                                <label>Excerpt<textarea name="posts[<?= $i ?>][excerpt]" rows="2"><?= h($post['excerpt'] ?? '') ?></textarea></label>
                                <label>Content<textarea name="posts[<?= $i ?>][content]" rows="8"><?= h($post['content'] ?? '') ?></textarea></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </section>

            <section class="admin-section" id="testimonials">
                <form method="post" action="save.php" data-repeater-form="testimonials">
                    <?= csrf_field() ?>
                    <input type="hidden" name="form" value="testimonials">
                    <div class="admin-section-head">
                        <h2>Testimonials</h2>
                        <div>
                            <button class="btn small ghost" type="button" data-add-item="#testimonial-list">Add Testimonial</button>
                            <button class="btn primary" type="submit">Save Testimonials</button>
                        </div>
                    </div>
                    <div class="admin-list" id="testimonial-list">
                        <?php foreach ($testimonialItems as $i => $testimonial): ?>
                            <div class="admin-item" data-repeater-item>
                                <div class="admin-item-head">
                                    <h3><?= h($testimonial['name'] ?? 'Testimonial') ?></h3>
                                    <button class="btn small danger" type="button" data-remove-item>Remove</button>
                                </div>
                                <div class="form-grid">
                                    <label>Name<input name="testimonials[<?= $i ?>][name]" value="<?= h($testimonial['name'] ?? '') ?>"></label>
                                    <label>Role<input name="testimonials[<?= $i ?>][role]" value="<?= h($testimonial['role'] ?? '') ?>"></label>
                                </div>
                                <label>Quote<textarea name="testimonials[<?= $i ?>][quote]" rows="3"><?= h($testimonial['quote'] ?? '') ?></textarea></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </section>

            <section class="admin-section" id="leads">
                <div class="admin-section-head">
                    <h2>Leads</h2>
                    <span class="badge"><?= count($leads) ?> saved</span>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Service</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leads as $lead): ?>
                                <tr>
                                    <td><?= h($lead['created_at'] ?? '') ?></td>
                                    <td><?= h($lead['name'] ?? '') ?></td>
                                    <td><?= h($lead['phone'] ?? '') ?></td>
                                    <td><?= h($lead['email'] ?? '') ?></td>
                                    <td><?= h($lead['service'] ?? '') ?></td>
                                    <td><?= h($lead['message'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>

    <script src="../assets/js/main.js"></script>
</body>
</html>
