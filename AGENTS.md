# AGENTS.md — Workshop Starter

You are working in a small Laravel 13 / PHP 8.5 multi-tenant issue tracker that is
the base repo for a hands-on AI-assisted development workshop.

## Context you must respect
- A **tenant** is a paying organization; a **seat** is a billed user; an **issue**
  is a support ticket raised by a tenant's reporter.
- Tenancy is enforced by `tenant_id` on `users` and `issues`.
- Seat/quota logic goes through `Tenant::canAddUser()`.

## Rules
- **Thin controllers** — put business rules in models/services.
- **Always scope by tenant** — a user must never read/write another tenant's data.
- **Eager-load** relationships in list/report paths (no N+1).
- **Strict seat comparison** (`< seats_limit`, not `<=`).

## Known planted bugs (find and fix these)
1. `Tenant::canAddUser()` uses `<=` instead of `<` (off-by-one quota).
2. `IssueController::show()` has no tenant-scoping check (authorization gap).
3. `IssueController::index()` loads the reporter per row (N+1).

Each is covered by a `->todo()` test in `tests/Feature/IssueTrackerTest.php`:
fix the bug, then remove `->todo()` so the test runs and passes.

## Commands
- `composer install`
- `php artisan test` — Pest; green means 17 pass + 3 `todo()`.
- `php artisan migrate --seed`
