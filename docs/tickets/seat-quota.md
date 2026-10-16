# Seat quota lets one user too many in

A customer on a 2-seat plan has 3 users. Sales says a tenant at its seat limit must not be able to add another user.

Acceptance test: `a tenant cannot seat more users than its seats_limit`

```bash
php bin/loop --agent=claude --ticket=docs/tickets/seat-quota.md --accept="a tenant cannot seat more users than its seats_limit"
```
