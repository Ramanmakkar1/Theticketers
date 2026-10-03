# TheTicketers

A lightweight, server-rendered **PHP** ticket marketplace for Dubai events and attractions, powered by the HelloTickets Discovery API with Impact affiliate deep links. No framework, no Composer, no build step — upload and it runs on any PHP 8.1+ shared host.

## Design

"Dubai Golden Hour" design system: warm ivory canvas, deep navy ink, sunset-gradient CTAs, gold accents, Plus Jakarta Sans + Inter typography, rounded cards with soft shadows, cinematic full-bleed hero with glass search bar. Fully responsive.

## What is included

- SEO-friendly PHP routes for home, events, attractions, city pages, category pages, detail pages and search.
- HelloTickets API client with file caching and currency/locale headers.
- Impact tracking redirect at `/go`, including click logging in `storage/clicks.log`.
- Dynamic `sitemap.xml` and `robots.txt`, canonical URLs, Open Graph tags and JSON-LD schema.
- Phase 1 programmatic SEO index (`storage/seo-index.json`) for expanded event, artist, artist-city, venue, city-date and city-category URLs, exposed through split sitemaps.

## Configuration

Set these environment variables in production (sensible fallbacks are built into `src/config.php` so the site also works without them):

```bash
SITE_NAME="TheTicketers"
SITE_URL="https://TheTicketers.com"
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

## SEO index

Generate the expanded Phase 1 sitemap inventory after Ticketmaster keys are configured:

```bash
php bin/build-city-index.php
php bin/build-seo-index.php
```

`/sitemap.xml` is a sitemap index that points to `/sitemap-static.xml`, `/sitemap-events.xml`, `/sitemap-artists.xml`, `/sitemap-venues.xml` and `/sitemap-cities.xml`.

## Cache housekeeping

`storage/cache/` is never evicted by the app: `index.php` only republishes an HTML entry when that exact URL is requested again, and `HelloTicketsClient` only overwrites an API entry when its exact key is re-requested. Nothing removes a file by age, so a crawl of the ~28K-page long tail grows the directory without bound. `bin/sweep-cache.php` is the other half — it deletes entries older than a TTL, recurses into `storage/cache/html/`, never touches `.gitkeep` or anything outside `storage/cache/`, and reports what it removed:

```bash
php bin/sweep-cache.php --dry-run         # report only, delete nothing
php bin/sweep-cache.php                   # 7 days (default)
php bin/sweep-cache.php --ttl=86400       # 24 hours
CACHE_SWEEP_TTL=86400 php bin/sweep-cache.php
```

Run it by hand or from cron (hourly is plenty; it is a no-op when nothing has aged out). It is deliberately not part of any deploy step.

## Deploy

1. Upload the project (or `git pull`) to a PHP 8.1+ host with Apache rewrite support — `.htaccess` already routes everything to `index.php`. On Nginx, send all non-file routes to `index.php`.
2. Make sure `storage/` and `storage/cache/` are writable by PHP.
3. Set `SITE_URL` to your real domain so canonical URLs and the sitemap are correct.
4. Run `php bin/build-city-index.php` on the host. `storage/city-index.json` is a generated artifact that is tracked in git, so a deploy ships the copy in the repo, not the host's inventory. The readers fail closed, so a stale or pre-date-key index silently hides every Today / This-Week link and month arrow; the log names the problem and the command (`[city-index] … run php bin/build-city-index.php`). Staleness is judged at 14 days — override with `CITY_INDEX_STALE_DAYS`.
5. Do **not** upload `preview-server.mjs` (or just leave it — it is harmless without Node).
