# CLAUDE.md — Multi-Tenant Issue Tracker

Example context file for the workshop (checkpoint `ch4-start`). Yours from
the context-engineering exercise may look different; that's fine.

## What this app is
A small Laravel 13 multi-tenant issue tracker. Tenants are paying
organisations; their users raise issues. Every response is JSON.

## Domain vocabulary
- **Tenant**: a paying organisation (a customer account).
- **Seat**: a billed user within a tenant. `seats_limit` is the maximum
  number of users a tenant may have; a tenant at its limit cannot add one more.
- **Issue**: a support ticket, raised by one of the tenant's users (the
  **reporter**).
- **Status**: `open`, `in_progress` or `closed`.

## Architecture conventions
- Controllers are thin. Business rules belong in the model or a service.
- Tenancy is by `tenant_id` on `users` and `issues`. Every read and write is
  scoped to the requesting user's tenant; there is no global dataset.
- Seat checks go through `Tenant::canAddUser()`. Do not reimplement the limit
  inline.

## Forbidden / footguns
- Never return or change an issue without checking that it belongs to the
  requesting user's tenant.
- Never touch a relation (for example `$issue->reporter`) inside a loop
  without eager-loading it first.

## Testing
- Pest. `php artisan test` needs no `.env` (it uses `.env.testing` and
  in-memory SQLite).
- Known bugs are recorded as `->todo()` tests. When you fix one, remove
  `->todo()` in the same commit so the test guards the fix.
