# The issue list gets slower as tenants grow

The issue list page makes one database query per issue. It should take a fixed number of queries however many issues a tenant has.

Acceptance test: `listing issues does not trigger an N+1 on the reporter`

```bash
php bin/loop --agent=claude --ticket=docs/tickets/issue-list-queries.md --accept="listing issues does not trigger an N+1 on the reporter"
```
