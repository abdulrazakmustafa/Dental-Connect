# Dental Connect

Multi-sided dental ecosystem platform (Patients, Clinics, Dentists, Suppliers, Platform Admin).

Domain: `dentalconnect.co.tz` · Initial market: Dar es Salaam, Tanzania

## Repository layout

```
/
├── app/                 Laravel 13 application (modular monolith)
├── Dental_Connect_Production_Master_PRD_v2.1.pdf   Source-of-truth PRD
├── mockup/              Approved UI reference screens
├── ARCHITECTURE.md
├── DATABASE.md
├── SECURITY.md
├── API.md
├── DEPLOYMENT.md
└── BACKUP_RESTORE.md
```

## Stack

- Laravel 13, PHP 8.3+
- Blade + Livewire + Alpine.js + Tailwind CSS
- MariaDB / InnoDB
- spatie/laravel-permission for RBAC (contextual clinic/supplier scope enforced in policies)

## Local setup

```bash
cd app
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

See [ARCHITECTURE.md](ARCHITECTURE.md) for module boundaries and [DATABASE.md](DATABASE.md) for schema/index rationale.

## Delivery status

This platform is being delivered in milestones per the Production Master PRD (`§30`). See commit history / project board for current milestone progress. Milestone 1 (Architecture & Data foundation) and Milestone 2 (Foundation: auth, RBAC, design system) are in progress.
