# AI-Powered PHP Development in Practice — Workshop Starter

The hands-on base repo for the **AI-Powered PHP Development in Practice** half-day
workshop at Longhorn PHP 2026. A deliberately small **multi-tenant issue tracker**
(Laravel 13, PHP 8.5) that ships with three planted bugs to find and fix during
the session.

## What's here

- Multi-tenant issue tracker: `Tenant` → `User` (seats) → `Issue`.
- **Three planted bugs**, each documented as a `->todo()` Pest test:
  1. Off-by-one seat quota (`Tenant::canAddUser()` uses `<=`).
  2. Cross-tenant authorization gap (`IssueController::show()`).
  3. N+1 query on the issues index (reporter loaded per row).
- A **legacy report controller** (`LegacyReportController`) for the refactoring
  exercise (N+1, duplicated date logic, no tests).
- `CLAUDE.md` + `AGENTS.md` context files (domain vocabulary, architecture,
  forbidden patterns).
- CI pipeline: PHPStan (level 6), Pint, Pest, `composer audit`.

## Getting started

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan test
```

`php artisan test` should be **green: 17 pass, 3 `todo()`**. The three `todo()`
tests document the bugs — fix the bug in the code, then remove `->todo()` so the
test runs and passes.

## The bugs

| Bug | Where | Test |
|-----|-------|------|
| Off-by-one seat quota | `app/Models/Tenant.php` | `a tenant cannot seat more users than its seats_limit` |
| Authorization gap | `app/Http/Controllers/IssueController.php` | `a user cannot view another tenant issue` |
| N+1 query | `app/Http/Controllers/IssueController.php` | `listing issues does not trigger an N+1 on the reporter` |

## License

MIT
