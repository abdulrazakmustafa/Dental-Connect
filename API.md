# Dental Connect — API

Versioned REST API under `/api/v1`, built to be consumed by the same web app's AJAX
needs today and future native Android/iOS clients later (§8, §25.1). Business logic
lives in Actions/Services shared with web controllers — never duplicated.

## Conventions

- Base path: `/api/v1`
- Auth: Laravel Sanctum-compatible token auth for API clients; session auth for
  first-party web/Livewire calls.
- All responses use a consistent envelope:

```json
{ "data": {}, "meta": {}, "errors": [] }
```

- All list endpoints are paginated (`page`, `per_page`, capped max `per_page`).
  Unbounded collection responses are prohibited.
- Public identifiers in URLs are ULIDs (`public_id`), never internal auto-increment
  ids.
- Errors return structured JSON with an HTTP status matching the failure
  (400/401/403/404/422/429/500) — no stack traces, no internal paths.
- Rate limits apply per route group by sensitivity (auth, mutation, search).

## Planned endpoint groups (filled in as each module ships)

| Group | Base | Notes |
|---|---|---|
| Auth | `/api/v1/auth` | register, login, logout, password reset, token issuance |
| Patient | `/api/v1/patient` | profile, clinic enrollments, preferences |
| Clinics | `/api/v1/clinics` | public directory/search, profile |
| Appointments | `/api/v1/appointments` | request, list, status transitions |
| Suppliers | `/api/v1/suppliers` | profile, verification status |
| Marketplace | `/api/v1/marketplace` | categories, products, RFQs — **requires** `marketplace.access` |
| Health | `/health` | liveness/readiness — app, DB, queue, integration status independently (ARCH-008) |

Each endpoint's method, path, auth requirement, request/response schema and error
cases will be documented here as it is implemented — this file is updated in the
same PR as the endpoint.
