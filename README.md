# Nestshield Cybersecurity

Complete responsive React + TypeScript website with React Router, Vite, Framer Motion, Lucide icons, page metadata, a cPanel SPA rewrite, and a PHP consultation endpoint.

**Business facts on the site (keep these accurate):** 20 years of cybersecurity experience, a free initial 15-minute video consultation, four packages at $1,500–$4,500 USD, and client reviews (46 reviews, 4.9 out of 5). The years of experience is held in one place — `EXPERIENCE_YEARS` at the top of `src/App.tsx` — and every mention on the site (hero badge, pricing note, About, footer) reads from it. Never hard-code the number in copy again; edit that one constant and rebuild.

Site copy is written in plain, specific language: what gets checked, who approves it, what it costs. No stock marketing phrases, and no numbers that can't be proven from real business records.

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

This runs TypeScript checks, builds the optimized static website into `dist/`, then copies the deployable site to **`deployment/public_html/`**. That folder is the compiled cPanel-ready deliverable and contains the assets, `.htaccess`, PHP endpoint, configuration, favicon, sitemap, and robots file. It does not require Node.js on the hosting server. Always rebuild after editing `src/` or `public/` — never hand-edit files inside `deployment/public_html/`, they get overwritten.

## Deploy to ordinary cPanel hosting

1. Build the website locally with `npm install` and `npm run build`.
2. In cPanel File Manager (or SFTP), upload **the contents of `deployment/public_html/`** into your account's `public_html/` directory. Upload hidden files too — especially `.htaccess` — and preserve the `api/` folder.
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

- Replace `BUSINESS_EMAIL_PLACEHOLDER` with the official monitored business email. Do not leave the placeholder in production: the PHP endpoint deliberately refuses to claim successful delivery while it is unset. **This is still outstanding — the form cannot deliver enquiries until it's set.**
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

- **Routes:** Home (`/`), About (`/about`), Cybersecurity Services (`/services`), Security Packages (`/packages`), Reviews & Testimonials (`/reviews`), Our Approach (`/approach`), Contact / Book Consultation (`/contact`), Privacy Policy (`/privacy`), Terms of Service (`/terms`), plus a 404 page.
- Home includes the hero with the 20-years badge, a reviews section (first 3 testimonials + 4.9/5 from 46 reviews), FAQs, and the package pricing note. The Reviews page shows all testimonials. Testimonials are clients' own words — don't rewrite them into marketing copy.
- Responsive mobile navigation, package-to-form links with URL query prefill (`?package=`, `?concern=`, `?method=`), accessible form labels/validation, loading/error/success states, page titles/descriptions/Open Graph metadata, and JSON-LD organization data on the home page.
- Exact package prices and included items are held in one reusable data source (`packages`) in `src/App.tsx`.

## Still outstanding before launch

- [ ] Real business email in `business-config.json` (blocks form delivery)
- [ ] Real domain in `sitemap.xml` / `robots.txt`
- [ ] Privacy/Terms reviewed against actual business practices and applicable law
- [ ] Optional: official Zoom booking URL in `business-config.json`
- [ ] Test enquiry submitted and received on the live HTTPS site

## Project structure

```text
src/                    React app (App.tsx = all pages/copy), routes and styles
public/                 PHP endpoint, editable config, .htaccess, SEO files, favicon, images
scripts/                Build packaging script (dist/ -> deployment/public_html/)
dist/                   Vite production build (generated, git-ignored output of build)
deployment/public_html/ cPanel-ready compiled deployment copy (generated by npm run build)
```
