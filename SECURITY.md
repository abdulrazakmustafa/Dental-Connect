# Dental Connect — Security

Source of truth: PRD §14–§17. Security is a launch blocker (§62), not optional polish.

## Authentication (§14.1, §63)

- Laravel-approved one-way hashing (bcrypt/argon2id).
- Secure, HttpOnly cookies, appropriate SameSite, HTTPS-only in production.
- CSRF protection on all session-authenticated mutations.
- Login/password-reset endpoints rate-limited; repeated failures trigger a temporary
  lockout.
- Session regeneration on login/privilege change; session invalidation on password
  reset, suspension, and other security events (IAM-007).
- MFA required for Super Admin / privileged admin roles before production launch
  (IAM-004, §64).

## Authorization (§14.2, §65–§66)

- Every protected operation is authorized **server-side** via middleware + Laravel
  Policies/Gates + spatie permissions. UI hiding is never sufficient.
- Clinic/supplier-scoped queries always filter by the authenticated actor's
  organization id — IDs received from the client are never trusted as sole proof of
  ownership.
- Direct URL/API manipulation of another clinic's/supplier's resource returns
  403/404 without disclosing existence.
- Cross-tenant access is treated as a security bug and is covered by automated
  negative tests (see `tests/Feature/Security/TenantIsolationTest.php` once added).

## Input/output (§14.3)

- All external input validated via Form Request classes.
- Parameterized ORM/query-builder usage only; no raw SQL string interpolation.
- Output escaped by default (Blade `{{ }}`); raw HTML rendering only for
  explicitly-sanitized trusted admin content.
- Uploaded filenames are never trusted; server generates storage filenames.
- MIME/type/size validated server-side for every upload.

## File security (§16)

- Verification documents stored outside the public web root.
- Access only through an authorized controller that checks permission before
  streaming / issuing a short-lived signed URL.
- Dangerous executable/script file types rejected outright.

## Audit logging (§17)

Append-only `audit_logs`, separate from `analytics_events`. Captures actor, action,
resource type + id, timestamp, safe before/after metadata. Never stores passwords,
tokens, or full sensitive documents. Logged events include: admin login, clinic/
supplier approval/rejection/suspension, role/permission changes, product moderation,
complaint/dispute status changes, settings/feature-flag changes, verification
document access.

## API security (§14.4, §72)

- Versioned (`/api/v1`), every request validated, structured error envelopes, no
  stack traces leaked.
- Provider credentials live only in environment/secrets configuration, never in
  source control.
- Incoming webhooks verify provider signatures; outgoing calls use timeouts + capped
  exponential backoff retries, idempotent references.

## Production hardening (§73–§75)

- `APP_DEBUG=false` in production; no SQL errors/env vars/stack traces/paths shown
  to end users.
- HTTPS required; HTTP redirects to HTTPS.
- Security headers configured where compatible with Hostinger: CSP, X-Frame-Options,
  X-Content-Type-Options, Referrer-Policy (tuned incrementally, verified against
  real pages before tightening).

## Privacy — Tanzania Personal Data Protection Act readiness (§15)

Dar es Salaam is the initial market; Tanzania's Cap. 44 / Act No. 11 of 2022 treats
health information as sensitive personal data. This repository provides *technical*
readiness only — not legal advice:

- Data minimization: Phase 1 does not collect diagnosis/X-ray/clinical detail.
- Consent version + timestamp stored per clinic relationship where clinic-specific
  consent applies (`patient_consents`).
- Sensitive documents are private by default.
- Analytics avoid storing more personal data than necessary.

Legal/product-owner review of controller/processor registration, privacy notices,
lawful basis, retention and any cross-border transfer arrangement is required before
public launch and is out of scope for this repository.
