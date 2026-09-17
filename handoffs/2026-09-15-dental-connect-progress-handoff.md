# Dental Connect — Session Handoff (2026-09-15)

## 1. What this project is

A production Laravel build of **Dental Connect** (dentalconnect.co.tz), a
multi-sided dental ecosystem for Tanzania: patients, dental clinics,
dentists, dental-material suppliers and platform admins. Built against a
130-section "Master Production Build Prompt" plus a detailed PRD
(`Dental_Connect_Production_Master_PRD_v2.1.pdf` at repo root) and 15
approved UI mockups (`mockup/` — Landing + 14 patient screens; no
clinic/supplier/admin mockups were provided).

Repo: **git@github.com:abdulrazakmustafa/Dental-Connect.git**, branch
`main`. Working tree is clean and everything below is pushed
(`71f6688` is HEAD as of this handoff).

Stack: Laravel 13 (modular monolith under `app/Modules/*`), Blade +
Livewire + Alpine.js + Tailwind v4, MariaDB/MySQL, spatie/laravel-permission
for RBAC, Chart.js (bundled locally, not CDN — see §5).

## 2. Repo layout

```
/ (repo root)
├── app/                                Laravel application (see below)
├── Dental_Connect_Production_Master_PRD_v2.1.pdf   Source-of-truth PRD
├── mockup/                              Approved mockup PNGs (Landing + 14 patient screens)
├── ARCHITECTURE.md, DATABASE.md, SECURITY.md, API.md, DEPLOYMENT.md, BACKUP_RESTORE.md
├── README.md
└── handoffs/                            ← this file lives here
```

Inside `app/`, domain code is under `app/Modules/<ModuleName>/` (Identity,
Patient, Clinic, Appointment, Supplier, Marketplace, Notification,
TrustSupport, AdminAnalytics, Audit, Core, Shared) — each module owns its
own Models, Http/Controllers, Http/Requests, Policies, Actions. This
structure is documented in `ARCHITECTURE.md` at the repo root — read that
first if picking this up fresh, it explains the module-boundary rules
(ARCH-001 through ARCH-009 from the PRD).

## 3. What's actually built and working (6 commits this session/history)

Commit history (oldest → newest), each independently tested/pushed:

1. **`b6a06a4` Foundation** — Laravel scaffold, modular structure, full DB
   schema (ULID public IDs, all core tables), RBAC (roles: patient,
   clinic_owner/admin/staff, supplier_owner/admin/staff, admin,
   super_admin), auth (register/login/password-reset), the non-negotiable
   `clinic_patients` tenant-isolation model, `marketplace.access`
   middleware, golden-path patient→clinic→appointment flow.
2. **`7902ce0` Marketplace** — supplier product CRUD, marketplace
   browse/search, RFQ workflow (clinic requests quote → supplier
   responds), admin product moderation queue.
3. **`468807d` Patient redesign** — **this is the important one to know
   about**: the patient-facing UI was originally built from the PRD's
   *described* visual direction without looking at the actual mockup
   files. Partway through, we caught this and rebuilt every patient
   screen (login, register, reset-password, clinic directory/profile,
   multi-step booking with a real Alpine calendar, appointment
   confirmation/list/detail/reschedule, Messages/Notifications, Reviews,
   Profile/Settings) to pixel-match the 15 mockups in `mockup/`. Three
   screens (Notifications, Reviews, Profile) didn't exist as features at
   all before this — they were built from scratch. **If you touch any
   patient screen, open the corresponding mockup PNG first.**
4. **`76b5540` Clinic module** — dentist CRUD, per-clinic service pricing,
   working-hours + blackout dates, staff invite flow (creates a Dental
   Connect account with a one-time temp password if the invitee doesn't
   have one — there's no email/SMS send yet, see §7), real dashboard
   metrics.
5. **`7279dfc` Glassmorphism + charts** — `.dc-card` is now a frosted-glass
   surface everywhere (patient/clinic/supplier/admin), real Chart.js
   graphs on every dashboard (appointment trends, RFQ trends, signup
   trends, status breakdowns), the one modal in the app (patient clinic
   enrollment) converted to a full page.
6. **`71f6688` Landing page rebuild** — the user pushed back that the
   landing page didn't match a reference design they liked (a "Dentora"
   template by Orbix Studio, shared via screenshots + a Pinterest link).
   **We did not clone that design** — it's someone else's paid work
   (their copy, their stock photos, their branding). We built an original
   page with the same general *composition* (photo hero + stat overlay +
   sections + footer) using our own teal palette and copy: hero, 4-step
   "how it works" strip, About section with **live DB stats** (verified
   clinic count, dentist count, avg rating — not fabricated numbers),
   feature grid, "Why Dental Connect" panel, 3 original oral-care tip
   cards, and a newsletter signup that's a real feature (own table +
   controller, not a decorative dead-end).

**Test suite: 41/41 passing** (`php artisan test`), including the
non-negotiable tenant-isolation and marketplace-access security suites
(PRD §103/§104) plus new coverage for reviews, reschedule/cancel,
dentist/staff/pricing/availability isolation.

## 4. How to run this locally

```bash
cd app
composer install
npm install
cp .env.example .env   # if starting fresh; current .env already configured for local MySQL
php artisan key:generate
mysql -u root -e "CREATE DATABASE dentalconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed
npm run build           # or npm run dev for hot reload
php artisan serve --port=8010
```

Local dev DB is MySQL (`mysql` CLI works via `/opt/homebrew/bin/mysql`,
server already running on this machine), database name `dentalconnect`,
user `root`, no password — matches `.env`. Tests use in-memory SQLite
(configured in `phpunit.xml`), separate from the dev DB.

**Demo/seed accounts** (via `php artisan migrate --seed`, all password
`password`):
- `clinic.owner@example.com` — owns "Smile Dental Clinic" (approved,
  seeded with a dentist, services/pricing, a confirmed and a completed
  appointment with a review)
- `patient@example.com` (Aisha Hassan) — enrolled with Smile Dental Clinic
- `supplier.owner@example.com` — owns "Dental Supplies TZ" (approved, 4
  seeded products)
- `admin@dentalconnect.co.tz` — super_admin, log in at
  `/platform/admin-login` (deliberately not linked from main nav)

## 5. Decisions made this session worth knowing about

- **Phone is the primary login identifier**, email is optional/nullable
  now (schema changed: `users.email` nullable+unique, `users.phone`
  unique). Login accepts either via a single `login` field that
  auto-detects email vs. phone format. This matches the mockups exactly.
  Password reset only actually works for email input right now (no
  SMS/WhatsApp channel exists) — it responds identically either way so it
  never leaks which accounts exist, but phone-based reset is a silent
  no-op pending the Notification module's SMS adapter.
- **Chart.js is bundled locally via a separate Vite entry**
  (`resources/js/charts.js`), not loaded from CDN. It was originally
  wired via CDN per the PRD's stack table, but the browser sandbox used
  for testing couldn't reach external CDNs at all (zero network attempts
  logged), so charts silently failed. Switched to an npm-bundled,
  code-split entry that's only loaded on pages passing `:charts="true"`
  to the dashboard layout — this is arguably the more correct choice for
  production too (no external runtime dependency). If you add a new chart
  page, make sure to pass `:charts="true"` on the `<x-layouts.dashboard>`
  component.
- **Glassmorphism is now the default `.dc-card` surface everywhere**
  (translucent white + backdrop-blur over a persistent gradient-mesh
  `body` background), including data-dense dashboard tables — the PRD
  originally said glassmorphism should be decorative-only and tables
  should stay flat white, but the user explicitly asked for glass
  everywhere and we complied. There's a `.dc-card-dense` variant (less
  blur, more opacity) used for tables specifically to protect text
  contrast/WCAG.
- **Hero photos are not yet in the repo.** The user attached 3 real
  dental-practice photos in chat twice, but pasted chat images cannot be
  pulled onto disk by the agent — only the user can save them as actual
  files. The hero (`resources/views/public/home.blade.php`) already
  auto-detects `public/images/hero/desktop.jpg` (and `tablet.jpg` /
  `mobile.jpg`) via `file_exists()` and will switch from the illustration
  fallback to real photos the instant those files exist — **no code
  change needed**, just get the files into
  `app/public/images/hero/`. There's a README in that folder with exact
  filenames/specs.
- **IP note**: the user asked to "copy exactly" a reference landing page
  from a Pinterest link (a paid Orbix Studio template called "Dentora").
  We explicitly declined to clone their specific design, copy, or stock
  photography, and explained why (their IP), then built an original page
  taking only generic structural inspiration. The user has not pushed
  back further on this since. If they raise it again, hold the same
  line — general layout patterns (photo hero + stat card + sections) are
  fine to take inspiration from; specific copy/photos/branding are not.

## 6. Real bugs found and fixed this session (useful pattern-recognition for future work)

- **Blade `@php(expr)` inline directive compiles to `<?php(` with no
  space**, which PHP doesn't recognize as a tag opener — the whole line
  silently rendered as literal text instead of executing. Always use the
  block form `@php ... @endphp`, never the parenthesized inline form for
  anything beyond a trivial no-side-effect expression.
- **`@include('some.blade.partial')` does not export variables back to
  the caller's scope** — a shared `$nav` array extracted into a Blade
  partial via `@include` silently produced "Undefined variable $nav".
  Fixed by using a plain PHP data file (`clinic/_nav.php`, returns an
  array) with `include resource_path(...)` instead, which runs in the
  caller's own scope. This pattern (`resources/views/clinic/_nav.php`) is
  now how the clinic sidebar nav is shared across ~8 view files — if you
  add a new clinic page, add its nav entry there, not inline.
- **Route-model-binding breaks silently when a restricted-column eager
  load omits the model's route key** (e.g. `->with(['clinic:id,name'])`
  when `Clinic`'s route key is `public_id`, not `id`) — always include
  `public_id` in any column-limited `select()`/`with()` for a model using
  `HasPublicUlid`.
- **Eloquent's default table-name pluralization breaks on compound
  model names** — `AppointmentStatusHistory` guesses table
  `appointment_status_histories` (we actually named it
  `appointment_status_history`, singular); `ClinicStaff`/`SupplierStaff`
  guess `..._staffs`. Both needed explicit `protected $table = '...'`.
  Worth grep-checking any new model against its actual migration name.
- **Editing an already-*approved* clinic's profile was silently
  resetting `verification_status` back to `submitted`** — the onboarding
  form is reused for both first-time submission and later edits; fixed so
  only clinics still in `draft`/`changes_requested` actually re-submit.
- The in-browser testing tool (`mcp__Claude_Browser__computer` clicks)
  is **flaky** — clicks intermittently no-op or time out with no error
  surfaced in the page. Workaround used throughout: prefer
  `mcp__Claude_Browser__javascript_tool` to set field values and call
  `.submit()` / `.click()` directly on a precisely-selected element
  (careful: `document.querySelector('form')` on a dashboard page grabs
  the *sidebar logout form* first, not the page's main form — always
  scope the selector, e.g. `form[action*="dentists"]`).
- Reusing a browser tab across `preview_stop`/`preview_start` cycles
  **carries over stale session cookies** from earlier test logins even
  after the dev DB has been dropped/reseeded, which can make a fresh
  login appear to land on the wrong dashboard. Open a **new tab**
  (`tabs_create`) rather than reusing one when starting a fresh
  auth-flow test after resetting the DB.

## 7. What's explicitly NOT done yet (remaining work, in the order the PRD's milestone table suggests)

Per PRD §30's 8-milestone plan, roughly done: **1 (Architecture & Data),
2 (Foundation), 3 (Patient/Public), 4 (Clinic), 5 (Supplier/Marketplace)**.
Remaining:

- **Milestone 6 — Administration** (next up, not started this session
  beyond what already existed): user management (list/suspend/edit
  patients, clinics, suppliers beyond the verification queues that
  already exist), reviews & complaints moderation UI (the `reviews` and
  `complaints` tables exist, `ReviewController` exists for
  patient-submission but there's **no admin moderation screen** for
  reviews or complaints yet), roles/permissions management UI (spatie
  roles exist and are seeded but there's no admin screen to view/edit
  them), audit log viewer (the `audit_logs` table is being written to via
  `AuditLogger` service but nothing renders it), reporting/CSV export.
- **Milestone 7 — Notifications & hardening**: in-app notifications exist
  and are wired into appointment status changes + enrollment, but the
  actual **email/SMS/WhatsApp adapters don't exist** — this blocks real
  password-reset-by-phone and clinic-staff invite emails (currently the
  temp password is just shown once on screen instead of emailed).
  Broader security/perf hardening pass (query review, rate-limit audit,
  MFA for admin — PRD IAM-004 requires this before launch and it's not
  built) hasn't happened yet either.
- **Milestone 8 — Deployment**: no staging deploy has been attempted.
  Hostinger SSH credentials were shared by the user early in the project
  but **never used** — deployment work hasn't started. `DEPLOYMENT.md`
  and `BACKUP_RESTORE.md` at the repo root describe the intended process
  but are documentation only, not yet executed against real
  infrastructure. **Do not connect to that server without re-confirming
  with the user first** — it's a live production system decision, not
  something to do opportunistically.
- **Supplier module**: has products/RFQs/marketplace/dashboard, but no
  mockups were ever provided for it (same as clinic/admin) — screens are
  original/extrapolated, not verified against any approved design. User
  has not flagged this as a problem yet but hasn't reviewed it closely
  either.
- **Clinic/Supplier/Admin visual design**: same caveat — no mockups
  exist for these, so they're built on the shared design-system tokens
  (glassmorphism, teal palette, Chart.js) but the *layout* is original,
  not verified pixel-fidelity against anything. If the user's reaction to
  the landing page redo is any signal, they may want another pass here
  too once they've reviewed it.

## 8. Immediate next steps when resuming

1. **Check whether the user dropped hero photos into
   `app/public/images/hero/`** — if so, verify the hero renders correctly
   with real photos (the code path is already there, just needs visual
   confirmation across mobile/tablet/desktop breakpoints).
2. **Get the user's reaction to the current landing page** before doing
   anything else — their last message before ending the session was
   "we have to finalize everything in landing page first," and we shipped
   a rebuild but never got confirmation it satisfies them. Don't assume
   it's approved; ask, or offer to walk them through it live again.
3. Once landing/front-end is confirmed acceptable, the natural next
   module is **Administration** (§7 above) — that's what was queued up
   when the session ended.
4. Run `php artisan test` and a quick `migrate:fresh --seed` sanity check
   first thing — confirms nothing drifted between sessions.

## 9. Git / attribution note

A new system instruction arrived near the end of this session changing
commit-message attribution format going forward:

```
Co-Authored-By: Claude Sonnet 5 <noreply@anthropic.com>
```

(same as what was already being used) and, for any future PR
descriptions specifically (not used yet — no PRs opened this session,
only direct pushes to `main`):

```
🤖 Generated with [Claude Code](https://claude.com/claude-code)
```

Keep using both correctly going forward if a PR is ever opened instead of
pushing straight to `main`.
