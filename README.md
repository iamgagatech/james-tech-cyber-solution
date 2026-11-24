# Nestshield Cybersecurity

Complete responsive React + TypeScript website with React Router, Vite, Framer Motion, Lucide icons, page metadata, a cPanel SPA rewrite, and a PHP consultation endpoint.

## Run locally

```bash
npm install
npm run dev
```

The React website works in local preview. The consultation endpoint is PHP and requires PHP-enabled hosting; submitting locally in Vite will clearly report that form delivery is not active.

## Production build

```bash
npm run build
```

This runs TypeScript checks, builds the optimized static website into `dist/`, then copies the deployable site to **`deployment/public_html/`**. The latter folder is the compiled cPanel-ready deliverable and contains the assets, `.htaccess`, PHP endpoint, configuration, favicon, sitemap, and robots file. It does not require Node.js on the hosting server.

## Deploy to ordinary cPanel hosting

1. Build the website locally with `npm install` and `npm run build`.
2. In cPanel File Manager (or SFTP), upload **the contents of `deployment/public_html/`** into your account's `public_html/` directory. Upload hidden files too—especially `.htaccess`—and preserve the `api/` folder.
3. Enable HTTPS for the domain in cPanel. The rewrite supports clean React routes such as `/services` and `/contact`; Apache `mod_rewrite` is needed for direct page refreshes. Existing assets and the PHP endpoint are served directly.
4. Replace `YOUR-DOMAIN-HERE` in `sitemap.xml` and `robots.txt` with the actual canonical domain after it is known. Submit the sitemap to search engines only after this replacement.
5. Review `privacy` and `terms` content for the actual business, jurisdiction, data practices and applicable laws before launch.

No Node.js runtime is needed on cPanel. PHP 7.4+ is required only for live consultation form delivery. If the account does not support PHP, the form reports that the enquiry service is not configured instead of claiming a request was delivered.

## Business configuration (single editable file)

Edit **`public/business-config.json`** before building (or edit the deployed `business-config.json` directly):

```json
{
  "businessName": "Nestshield Cybersecurity",
  "businessEmail": "BUSINESS_EMAIL_PLACEHOLDER",
  "zoomBookingUrl": ""
}
```

- Replace `BUSINESS_EMAIL_PLACEHOLDER` with the official monitored business email. Do not leave the placeholder in production: the PHP endpoint deliberately refuses to claim successful delivery while it is unset.
- Add the real official Zoom booking URL only if/when one is provided. The current site does not create or invent a Zoom meeting link; clients may request a Zoom call and receive details after their enquiry is reviewed.
- Re-run `npm run build` and re-upload the deployment folder after changing source configuration. A direct edit to the deployed JSON takes effect immediately for the displayed email and PHP recipient.

## Activating email delivery

`public/api/contact.php` validates and bounds form fields, checks same-origin requests where an Origin header is supplied, ignores a honeypot, and sends an email to `businessEmail` from `business-config.json` using PHP's `mail()` function. It only returns a success response if the configured PHP mail transport accepts the message. The website itself never collects banking passwords or payment authentication codes.

To activate and verify delivery:

1. Configure the real business email in `business-config.json`.
2. In cPanel, use an address on the business's own domain where possible. Configure that mailbox and the hosting account's outbound mail routing/DNS (SPF, DKIM, and DMARC) according to the hosting provider's instructions. PHP `mail()` depends on the host's mail transport; it is not SMTP credentials embedded in the site.
3. Ask the cPanel host whether PHP `mail()` is enabled and whether they require a verified sender address. If the host requires authenticated SMTP, adapt the endpoint to the host's approved SMTP service/library and store credentials in a server-only environment/config file outside the public web root. Never put SMTP passwords in `business-config.json`, React source, or public files.
4. Submit a test enquiry from the deployed HTTPS website and confirm receipt and spam handling before announcing the form. The form shows success only when PHP reports mail accepted by the configured transport; this is not a guarantee of final inbox delivery.

There is no email address, phone number, office location, social account, Zoom URL or SMTP credential invented in this project. Those require real business information/configuration.

## Pages and functionality

- Home, About, Cybersecurity Services, Security Packages, Our Approach, Contact / Book Consultation.
- Privacy Policy and Terms of Service pages are included and linked in the footer.
- Responsive mobile navigation, internal routes, package-to-form links, URL query prefill, FAQs, accessible form labels/validation, loading/error/success states, reduced-motion handling, page titles/descriptions/Open Graph defaults and a custom technical shield motif.
- Exact package prices and included items are held in one reusable data source in `src/App.tsx`.

## Project structure

```text
src/                  React app, routes and styles
public/               PHP endpoint, editable config, .htaccess, SEO files, favicon
scripts/               Build packaging script
 dist/                 Vite production build (generated)
deployment/public_html/ cPanel-ready compiled deployment copy (generated)
```
