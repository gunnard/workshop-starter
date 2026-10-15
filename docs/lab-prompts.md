# Lab Prompts

Every prompt and ticket from the workshop slides, so you can copy them instead of retyping them off the screen. Each block has a copy button in its top-right corner on GitHub.

Close enough is fine. The wording isn't magic; the shape of the ask is.

Run every prompt from inside the `workshop-starter` folder, so your agent can see the code.

---

## Chapter 2 · What does your agent know with no help?

Save the answer somewhere outside the repo. You'll compare it in Chapter 3.

```text
Explain what this application does, in three sentences, based only on reading the code.
```

Finished early?

```text
What testing framework does this project use, and are there any anti-patterns in the test suite?
```

## Chapter 3 · Write the context file

Run this **before** you create any context file, and save the plan it gives you. Then run it again **after**, and compare.

```text
Add the ability to invite a user to a tenant. Propose a plan: which files you'd change and how. Don't edit any files.
```

Start your `AGENTS.md` (or `CLAUDE.md` for Claude Code) with this line, then add one rule of your own that you found in the code or the tests:

```text
Seat checks go through Tenant::canAddUser().
```

## Chapter 4 · Reshape a vague prompt

The vague way first:

```text
Add CSV export for issues. Don't edit any files — just tell me what you'd do.
```

Then the shaped way, and compare the two answers:

```text
I need to add CSV export for issues. Propose two or three approaches, with tradeoffs, before writing any code. Don't edit any files.
```

Finished early? Add a lens to the end:

```text
...as an engineer worried about speed — this table could have millions of rows.
```

## Chapter 5 · Bulk-close, in increments

The ticket, with the request for questions:

```text
Users want to be able to close multiple issues at once instead of one at a time. Add it to the issues API.

Before you propose anything, ask me clarifying questions about scope, authorization and edge cases.
```

Once you've answered its questions:

```text
Propose two or three approaches with tradeoffs. Don't write code yet.
```

Then, one step at a time:

```text
Implement increment 1 only: the route, input handling and authorization. Stop after that.
```

## Chapter 7 · Characterize, then extract one thing

```text
List every behavior of LegacyReportController::summary(), and for each one say whether the existing tests pin it down. Don't change anything.
```

```text
Write characterization tests for the behaviors the existing tests miss — an issue in every status, by_reporter, and the tenant name. Capture current behavior exactly, including any bugs. Do not fix anything.
```

When those tests pass and you've committed them:

```text
Extract the counting logic into a new ReportSummary class. Preserve behavior, including existing bugs, unless I tell you otherwise. One small, reviewable change.
```

## Chapter 8 · Does your test guard anything?

```text
Write Pest tests for Tenant::canAddUser(). Edge cases and boundaries first: below, at and over the limit, a limit of zero. Then the happy path.
```

## Chapter 10 · One ticket, the whole loop

```text
Tenant admins want to see how many of their issues are open, in progress, and closed.

Before proposing anything, ask me clarifying questions.
```

```text
Propose two or three approaches with tradeoffs. Don't write code yet.
```

Then build one increment, review it, and write one edge-case test before the code it checks. Which edge case is your call.

---

Looking for more? The full [prompt library and checklists](prompt-library.md) are in this folder too.
