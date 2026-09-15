# Dental Connect — Backup & Disaster Recovery

Source of truth: PRD §20.

## Targets

- **RPO**: <= 24 hours (unless a stricter business requirement is later approved).
- **RTO**: <= 8 hours under normal access conditions.

## What is backed up

| Area | Requirement |
|---|---|
| Database | Daily automated backup minimum; an additional pre-deployment backup before any risky migration. |
| Files | Verification/private files included in backup or replicated to separate protected storage. |
| Source code | Company-controlled Git repository; protected `main` branch and tagged releases. |
| Environment/config | Secrets stored securely (host secret manager / protected `.env`), non-secret deployment configuration documented in this repo. |
| Off-site copy | An encrypted copy is maintained outside the primary hosting failure domain where commercially practical. |

## Restore testing

- A production-like backup is restored into a clean staging environment before
  launch and periodically thereafter (§20, §23.2 "Backup restore rehearsal before
  launch").
- Restore steps are documented and re-verified whenever the schema or hosting
  provider changes materially.

## Restore procedure (fill in with exact Hostinger panel/CLI steps once
provisioned)

1. Identify the correct backup snapshot (database dump + file archive) by timestamp.
2. Provision or reuse a clean target environment (staging first, always).
3. Restore the database dump into a fresh MariaDB schema.
4. Restore/relink the private files volume.
5. Restore `.env` from the secret store (never from Git).
6. Run `php artisan migrate --force` only if the restored dump predates the current
   migration set — otherwise skip to avoid double-applying.
7. Run the health check + a smoke test of the critical workflows (§26): patient
   appointment, clinic verification, supplier RFQ, admin moderation.
8. Only after staging restore is verified, repeat against production during an
   approved maintenance window.
