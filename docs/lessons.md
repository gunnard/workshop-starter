# Lessons

What reviewers had to send back on earlier tickets. `bin/loop` sends this file
with every prompt, and `AGENTS.md` tells agents to read it.

Add a line after every review where you sent something back. One line per
lesson: what went wrong, and the rule that prevents it. Delete a lesson once a
check enforces it (a test, PHPStan, Pint): a rule a check enforces doesn't
need to be remembered.

<!-- Example:
- 2026-10-15 · seat quota: fixed the limit inline in the controller. Seat
  checks go through Tenant::canAddUser(), and nowhere else.
-->
