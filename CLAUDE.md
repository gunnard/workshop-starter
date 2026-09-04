# CLAUDE.md — Multi-Tenant Issue Tracker

## What this app is
A deliberately small Laravel 13 / PHP 8.5 multi-tenant issue tracker used as the
hands-on base for the "AI-Powered PHP Development in Practice" workshop. It ships
with three planted bugs that the workshop asks you to find and fix (each is
documented as a `->todo()` Pest test).

## Domain vocabulary
- **Tenant** — a paying organization (a customer account).
- **Seat** — a billed user within a tenant. `Tenant::seats_limit` caps seats.
- **Issue** — a support ticket, raised by a tenant's **reporter** (a user).
- **Open / in_progress / closed** — the issue status enum.

## Architecture conventions
- **Controllers are thin.** Business rules belong in the model (or a service).
- **Tenancy is by `tenant_id`** on `users` and `issues`. Never assume a single
  global dataset — every issue is owned by a tenant.
- **Seat/quota checks go through `Tenant::canAddUser()`** — do not reimplement
  the limit inline.
- **Eager-load relationships** when a list renders a related model (`with('reporter')`).

## Forbidden / footguns
- **Never expose an issue without checking it belongs to the requesting user's
  tenant.** Missing this is the planted authorization bug.
- **Never access `$issue->reporter` (or any relation) inside a loop without
  eager-loading.** That is the planted N+1 bug.
- **Seat limit comparison must be strict `< seats_limit`.** Using `<=` is the
  planted off-by-one bug and silently over-bills a seat.

## Running
- `composer install`
- `php artisan test` (Pest) — should be green: 17 pass, 3 `todo()` documenting the bugs.
- `php artisan migrate --seed` for sample tenants/users/issues.
