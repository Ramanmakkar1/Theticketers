# TicketSouq

A lightweight, server-rendered **PHP** ticket marketplace for Dubai events and attractions, powered by the HelloTickets Discovery API with Impact affiliate deep links. No framework, no Composer, no build step — upload and it runs on any PHP 8.1+ shared host.

## Design

"Dubai Golden Hour" design system: warm ivory canvas, deep navy ink, sunset-gradient CTAs, gold accents, Plus Jakarta Sans + Inter typography, rounded cards with soft shadows, cinematic full-bleed hero with glass search bar. Fully responsive.

## What is included

- SEO-friendly PHP routes for home, events, attractions, city pages, category pages, detail pages and search.
- HelloTickets API client with file caching and currency/locale headers.
- Impact tracking redirect at `/go`, including click logging in `storage/clicks.log`.
- Dynamic `sitemap.xml` and `robots.txt`, canonical URLs, Open Graph tags and JSON-LD schema.

## Configuration

Set these environment variables in production (sensible fallbacks are built into `src/config.php` so the site also works without them):

```bash
SITE_NAME="TicketSouq"
SITE_URL="https://your-domain.com"
HELLOTICKETS_API_URL="https://api-live.hellotickets.com"
HELLOTICKETS_PUBLIC_KEY="pub-bcaaca28-c7df-4fc1-9274-61a0f1439d13"
HELLOTICKETS_CURRENCY="AED"
HELLOTICKETS_LOCALE="en-GB"
IMPACT_BASE_URL="https://hellotickets.sjv.io/MKNd7K"
```

## Run locally

With PHP 8.1+ installed:

```bash
php -S 127.0.0.1:8000 index.php
```

This Mac does not have PHP installed, so the repo also ships `preview-server.mjs` — a Node mirror of the PHP pages used **only for local design preview**:

```bash
node preview-server.mjs   # http://127.0.0.1:8000
```

The production site is the PHP code (`index.php` + `src/` + `assets/`); the preview server is never deployed.

## Deploy

1. Upload the project (or `git pull`) to a PHP 8.1+ host with Apache rewrite support — `.htaccess` already routes everything to `index.php`. On Nginx, send all non-file routes to `index.php`.
2. Make sure `storage/` and `storage/cache/` are writable by PHP.
3. Set `SITE_URL` to your real domain so canonical URLs and the sitemap are correct.
4. Do **not** upload `preview-server.mjs` (or just leave it — it is harmless without Node).
