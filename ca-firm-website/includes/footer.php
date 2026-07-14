<?php $whatsapp = setting($data, 'whatsapp'); ?>
    </main>
    <section class="bottom-cta">
        <div>
            <p class="eyebrow">Need CA support?</p>
            <h2>Talk to the team before your next due date.</h2>
            <p>Share your requirement and get a clear response on documents, timeline and pricing.</p>
        </div>
        <div class="cta-actions">
            <a class="btn primary" href="tel:<?= h(preg_replace('/\s+/', '', setting($data, 'phone'))) ?>">Call Now</a>
            <?php if ($whatsapp !== ''): ?>
                <a class="btn ghost" href="https://wa.me/<?= h($whatsapp) ?>">WhatsApp</a>
            <?php endif; ?>
        </div>
    </section>

    <footer class="site-footer">
        <div>
            <strong><?= h(setting($data, 'site_name')) ?></strong>
            <p><?= h(setting($data, 'tagline')) ?></p>
        </div>
        <div>
            <p><?= h(setting($data, 'address')) ?></p>
            <p><?= h(setting($data, 'city')) ?>, <?= h(setting($data, 'state')) ?>, <?= h(setting($data, 'country')) ?></p>
        </div>
        <div>
            <a href="mailto:<?= h(setting($data, 'email')) ?>"><?= h(setting($data, 'email')) ?></a>
            <a href="tel:<?= h(preg_replace('/\s+/', '', setting($data, 'phone'))) ?>"><?= h(setting($data, 'phone')) ?></a>
        </div>
        <div class="footer-links">
            <a href="privacy.php">Privacy</a>
            <a href="terms.php">Terms</a>
            <a href="sitemap.php">Sitemap</a>
            <a href="admin/">Admin</a>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
