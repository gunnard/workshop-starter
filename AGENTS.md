# AGENTS.md — Multi-Tenant Issue Tracker

<!-- Read by OpenCode, Codex and Copilot. CLAUDE.md imports this file, so
     Claude Code reads the same rules. bin/loop also sends it with every prompt. -->

Example context file for the workshop (branch `loop`). Yours from the
context-engineering exercise may look different; that's fine.

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

## Scope
- Work on the ticket you were given. If you notice other problems, list them
  at the end of your reply; do not fix them unless asked.
- Do not change what an existing test asserts to make it pass. If you think a
  test is wrong, stop and say why.

## Done means
- `composer ci` passes: PHPStan, Pint (code style) and the full test suite.
- A person has reviewed the diff. Do not commit.

## Lessons
- Read `docs/lessons.md` before starting. It holds what reviewers had to send
  back on earlier tickets; don't repeat those mistakes.

## Testing
- Pest. `php artisan test` needs no `.env` (it uses `.env.testing` and
  in-memory SQLite).
- Known bugs are recorded as `->todo()` tests. When you fix one, remove
  `->todo()` in the same commit so the test guards the fix.
