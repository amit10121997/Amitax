# CA Firm Dynamic Website

This is an original PHP + JSON website template for a CA firm. It does not need a database and can run on most shared hosting plans that support PHP.

## Pages Included

- `index.php` - home page
- `services.php` - all services with search/filter
- `service.php?slug=...` - dynamic service detail page
- `pricing.php` - packages and service price table
- `about.php` - firm profile
- `contact.php` - callback form and direct call/WhatsApp buttons
- `blog.php` and `blog-post.php` - editable blog
- `faq.php` - editable FAQ
- `privacy.php` and `terms.php`
- `sitemap.php` and `robots.txt`
- `admin/` - content editor

## Admin Login

Open:

```text
https://yourdomain.com/admin/
```

Default password:

```text
change-me-now
```

Change this password immediately from Admin > Settings. After changing it once, the website stores the password as a PHP password hash.

## What You Can Edit

From the admin panel you can edit:

- Firm name, phone, WhatsApp, email, address and website domain
- Hero image URL
- Services, categories, prices, details, timelines and features
- Pricing packages
- FAQs
- Blog posts
- Testimonials
- Saved contact leads

All editable content is stored in:

```text
data/site.json
data/leads.json
```

## Hosting Setup

1. Upload all files inside this folder to your hosting public folder, normally `public_html`.
2. Make sure PHP is enabled.
3. Make the `data` folder writable by PHP if the admin panel cannot save.
4. Edit Settings in admin:
   - `domain` should be your real domain, for example `https://yourdomain.com`
   - phone, WhatsApp and email should be real
   - replace address and firm name
5. Edit `robots.txt` and replace `https://example.com/sitemap.php` with your real sitemap URL.
6. Submit `https://yourdomain.com/sitemap.php` in Google Search Console.

## SEO Notes

The template includes page titles, meta descriptions, canonical URLs, XML sitemap output and JSON-LD structured data. SEO ranking is not automatic or guaranteed. Add original service content, real location details, fast hosting, client trust signals and useful blog posts.

## Important Security Notes

- Change the default admin password before publishing.
- Keep `data/.htaccess` uploaded so JSON files are not directly visible on Apache hosting.
- If your hosting is not Apache/Nginx rules do not protect `data`, move the `data` folder outside the public web root or ask hosting support to block direct access.
- This starter contact form saves leads locally. Add email/SMTP later if you want automatic email notifications.
