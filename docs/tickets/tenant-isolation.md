# Users can open other customers' issues

A user reported that changing the number in an issue URL showed them an issue belonging to a different company. Viewing an issue must be limited to the user's own tenant.

Acceptance test: `a user cannot view another tenant issue`

```bash
php bin/loop --agent=claude --ticket=docs/tickets/tenant-isolation.md --accept="a user cannot view another tenant issue"
```
