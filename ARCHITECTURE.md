# Dental Connect — Architecture

Source of truth: `Dental_Connect_Production_Master_PRD_v2.1.pdf` (§2–§5, §25).

## Decision: modular monolith (ARCH-001)

One Laravel application, one primary MariaDB database, split into explicit business
modules under `app/Modules/<Module>`. No microservices at MVP. Each module owns its
routes, controllers, actions/services, policies, form requests, resources, migrations
and tests.

## Module map

| Code | Module | Owns |
|---|---|---|
| CORE | Core Platform | Settings, feature flags, files, shared primitives |
| IAM | Identity & Access | Users, sessions, auth, roles, permissions, MFA |
| PAT | Patient | Patient profile, clinic_patients, preferences, consent |
| CLN | Clinic & Practitioner | Clinic onboarding, locations, services, dentists, verification |
| APT | Appointment | Availability, requests, status history |
| SUP | Supplier | Supplier onboarding, verification, profile |
| MKT | Marketplace | Categories, products, RFQs — clinics/suppliers/admin only |
| NOT | Notifications | In-app + email/SMS/WhatsApp adapters, delivery logs |
| TRU | Trust & Support | Reviews, complaints/disputes |
| ADM | Administration | Verification queues, moderation, settings |
| ANL | Analytics | Operational metrics, aggregated dashboards |
| AUD | Audit | Append-only privileged action/event audit |

Reserved for later phases (not created prematurely — ARCH-005/009): PAY, ORD, LOG, FIN,
INS, REC, COR, LOY, AI.

## Module communication rules (ARCH-003, §4.1)

- A module reads another module only through a defined query service / read contract.
- A module changes another module's state only through an application service or a
  domain event — never a direct cross-module table write.
- Non-critical side effects (notifications, analytics, search indexing) go through
  queued events, retryable.
- Critical same-database state changes that must succeed together use one DB
  transaction inside the owning module.
- The core business transaction commits first; external-provider calls are queued
  after commit (REL-003/004).

## Directory layout

```
app/
  Modules/
    Identity/{Domain,Application,Http,Infrastructure,Database,Tests}
    Patient/...
    Clinic/...
    Appointment/...
    Supplier/...
    Marketplace/...
    Notification/...
    TrustSupport/...
    AdminAnalytics/...
  Shared/{Contracts,Support,ValueObjects}
routes/
  web.php
  api.php
```

## RBAC (§6)

Roles: `patient`, `clinic_owner`, `clinic_admin`, `clinic_staff`, `supplier_owner`,
`supplier_admin`, `supplier_staff`, `admin`, `super_admin`.

Permissions are separate from roles (spatie/laravel-permission) and are always
evaluated together with **contextual scope** (clinic_id / supplier_id ownership) in
policies — permission alone never grants cross-tenant access. Hiding a UI control is
never sufficient (IAM-001); every protected controller/action re-checks scope
server-side.

## Marketplace access (non-negotiable, §11/§45/§46)

`marketplace.access` middleware + policy stack:

```
patient        -> 403
guest          -> redirect to login
unverified org -> restricted per business rule
verified clinic/supplier, authorized admin -> allowed
```

No `/patient/marketplace` routes exist. This is enforced server-side, not just by
hiding navigation.

## Patient model (non-negotiable, §7.2, §13)

- `users` — one global Dental Connect login identity per human.
- `clinic_patients` — the clinical relationship, one row per (clinic, patient),
  owns `patient_number`, enrollment status. A user may have many `clinic_patients`
  rows (one per clinic they enroll with); each is fully isolated from other clinics.

## Fault isolation reality (§2.2, §13)

Module-level code isolation ≠ process independence at Phase 1: all modules share one
app + one database. What Phase 1 guarantees:

- An external integration failure (SMS/WhatsApp/email) never blocks the core DB
  transaction it's attached to.
- A marketplace error never breaks patient login/appointments.
- Non-core modules can be disabled via feature flags without touching auth/admin.

True process-level isolation is a future VPS-stage option (ARCH-009).
