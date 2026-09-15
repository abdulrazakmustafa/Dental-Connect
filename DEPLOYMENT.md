# Dental Connect — Deployment (Hostinger)

Source of truth: PRD §21–§22.

## Requirements

- PHP 8.3 or 8.4 with OPcache enabled (dev environment here runs 8.5; verify target
  Hostinger plan's available version before deploy and pin `composer.json`
  `"php": "^8.3"` accordingly).
- Required extensions: `pdo_mysql`, `mbstring`, `bcmath`, `ctype`, `fileinfo`,
  `openssl`, `tokenizer`, `xml`, `curl`, `gd` (or `imagick`).
- MariaDB (InnoDB).
- Composer 2.
- Node/npm for building frontend assets (build locally/CI, deploy built `public/build`
  — Hostinger shared plans should not run `npm run build` on the server).

## Environment

- `.env` lives only on the server, never committed. `app/.env.example` documents
  every required key with no secrets.
- `APP_DEBUG=false`, `APP_ENV=production` in production.
- Force HTTPS: `APP_URL=https://dentalconnect.co.tz`, secure session cookie config.

## Steps (initial Hostinger Web/Cloud posture)

1. Deploy application code from the company-controlled Git repository or a built
   release artifact (not manual FTP edits).
2. `composer install --no-dev --optimize-autoloader`
3. `php artisan storage:link`
4. `php artisan migrate --force`
5. `php artisan config:cache && php artisan route:cache && php artisan view:cache`
6. Point document root at `app/public`.
7. Configure one cron entry (Laravel Scheduler):
   `* * * * * php /path/to/app/artisan schedule:run >> /dev/null 2>&1`
8. Queue: database-backed queue, processed by the scheduler-driven
   `queue:work --stop-when-empty` pattern (no persistent daemon assumed on shared
   hosting) — see §12.2.
9. Run health check + smoke test immediately after deploy.

## VPS migration triggers (§21.2)

Move to VPS/cloud when: native mobile apps create sustained API concurrency, Redis/
Horizon is needed for low-latency queues, DB size/connections approach shared-plan
limits, the clinical-records module requires stronger isolation, or RPO/RTO targets
tighten beyond what shared hosting can provide. The codebase's cache/queue
abstractions are chosen specifically so this migration does not require rewriting
business logic (§12.1, §21.2).

## Environments

`local` → `staging/UAT` → `production`. No experimental changes are tested directly
in production; production data is never copied down to development without masking
and authorization.
