# AI-Powered PHP Development in Practice — Workshop Starter

The hands-on base repo for the **AI-Powered PHP Development in Practice** half-day
workshop at Longhorn PHP 2026. A deliberately small **multi-tenant issue tracker**
(Laravel 13, PHP 8.3 or newer) that ships with three planted bugs to find and fix
during the session.

## What's here

- Multi-tenant issue tracker: `Tenant` → `User` (seats) → `Issue`.
- **Three planted bugs**, each documented as a `->todo()` Pest test. Finding
  where they live is part of the exercise.
- A **legacy report controller** (`LegacyReportController`) for the refactoring
  exercise (N+1, duplicated date logic, only three shallow tests).
- **No `CLAUDE.md` or `AGENTS.md` on `master`.** Writing one is part of the
  session; a worked example is on the `ch4-start` branch.
- CI pipeline: PHPStan (level 6), Pint, Pest on PHP 8.3 and 8.5, `composer audit`.

## Getting started

```bash
composer install
php artisan test
```

No `.env` or database is needed for the tests (they use `.env.testing` and
in-memory SQLite). `php artisan test` should be **green: 15 passed, 3 todos**.
The three `todo()` tests document the bugs — fix the bug in the code, then
remove `->todo()` so the test runs and passes.

To run the app itself with sample data:

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Checkpoints

Fell behind? Each branch is a known-good starting point:

| Branch | State |
|--------|-------|
| `master` | The starting point: no context file, all three bugs present. |
| `ch4-start` | Adds an example `CLAUDE.md` and `AGENTS.md`. |
| `ch8-start` | Adds the seat-quota fix, with its test switched on (for the mutation exercise). |
| `solution` | All three bugs fixed and their tests switched on. |

```bash
git fetch origin
git switch ch4-start        # or ch8-start, solution
```

## The bugs

| Bug | Test that documents it |
|-----|------------------------|
| Seat quota off by one | `a tenant cannot seat more users than its seats_limit` |
| Cross-tenant authorization gap | `a user cannot view another tenant issue` |
| N+1 query on the issue list | `listing issues does not trigger an N+1 on the reporter` |

## License

MIT
