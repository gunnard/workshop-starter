# The loop

Every chapter of the workshop was one part of the same system:

| Chapter | Part | Where it lives in this repo |
|---|---|---|
| 3 | A context file the agent reads every time | `AGENTS.md` (and `CLAUDE.md`, which imports it) |
| 4–5 | A ticket with a test that says when it's done | `docs/tickets/`, and the acceptance test |
| 5 | Small increments | one ticket per run |
| 6 | A review by someone other than the author | you, at the end of every run |
| 8 | A test that fails until the work is done | `--accept`, checked *before* the agent starts |
| 9 | Checks that decide, not the model | `composer ci` (PHPStan, Pint, the test suite) |
| 3 | Keep the context current | `docs/lessons.md` |

During the workshop you ran each part by hand, once. `bin/loop` connects them,
so a failed check goes straight back to the agent and a reviewer's sendback
becomes a rule the next run reads:

```
  ticket + AGENTS.md + lessons
            │
            ▼
         agent ──────► checks ──── fail ───┐
            ▲        (acceptance            │
            │         test, then            │
            │         composer ci)          │
            └──── the failure output ◄──────┘
                         │
                       pass
                         ▼
                  you review the diff ──► sent something back? add a lesson
```

## Run it

Switch to this branch, and check that you're starting green:

```bash
git switch loop
composer install
composer ci
```

Then give the agent a ticket:

```bash
php bin/loop --agent=claude --ticket=docs/tickets/seat-quota.md --accept="a tenant cannot seat more users than its seats_limit"
```

`--agent` is `claude`, `opencode` or `copilot`, or the path to any command
that takes the prompt as its last argument. The agent must be installed and
signed in. Run `bin/loop` from your own terminal, not from inside an agent
session.

Three tickets are ready in `docs/tickets/`, one per known bug. Each file has the
command to run it.

## What it does

1. **Refuses to start if the check can't fail.** The acceptance test must exist and
   must not pass yet. A test that already passes can't tell you when you're
   done (Chapter 8).
2. **Sends the agent one prompt**: the ticket, `AGENTS.md`, `docs/lessons.md`,
   and the definition of done. Each prompt is saved in `storage/logs/loop/`, so
   you can read exactly what the agent was told.
3. **Runs the checks itself**: the acceptance test (a `->todo()` test does not
   count as passing), then `composer ci`.
4. **On failure, sends the output back** and tries again, up to `--max` attempts
   (default 3).
5. **On success, checks the tests.** If the agent removed or rewrote a line
   under `tests/`, other than the `->todo()` it was asked to drop, the loop says
   so and doesn't call it done. A test the agent edited to pass proves nothing.
6. **Never commits.** It stops and hands you the diff.

| Exit code | Meaning |
|---|---|
| 0 | The checks pass. Your turn: review, add any lessons, commit. |
| 1 | Gave up after `--max` attempts. The changes are left for you to look at. |
| 2 | Didn't run: uncommitted changes, a missing or already-passing test, or the agent couldn't start. |
| 3 | The checks pass, but tests were changed. Read the test diff first. |

## Your part: review, and keep the context current

The loop can't do Chapter 6. When it stops, review the diff with the checklist
in `docs/prompt-library.md`. Whenever you have to send something back, add one
line to `docs/lessons.md`: what went wrong, and the rule that prevents it.
The next run reads it.

When a lesson keeps coming up, turn it into a check (a test, a PHPStan rule)
and delete the lesson. Rules that a check enforces don't need to be remembered.

## Where to take it next

- **Write your own ticket.** Write the failing test first, then the ticket, then
  run the loop. There's a fourth bug in this repo that no ticket mentions.
- **Add a check.** Anything that can fail the build can steer the agent: a
  PHPStan level, an architecture test, a mutation score.
- **Put it in CI.** The same checks belong in your pipeline, so a person never
  merges what the loop would have sent back.
