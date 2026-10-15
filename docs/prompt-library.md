# Prompt Library and Checklists

From the Longhorn PHP 2026 workshop *AI-Powered PHP Development in Practice*. Everything here is meant to be copied into your own team's prompt library and adapted, not memorized.

- [Prompt library](#prompt-library): 100 prompts, grouped by the six prompt shapes from Chapter 4
- [Context file templates](#context-file-templates): starting points for `CLAUDE.md` / `AGENTS.md`
- [Reusable workflows](#reusable-workflows): daily, bug, feature, refactor, PR and release
- [Checklists](#checklists): code review and test quality

Words in [square brackets] are placeholders: replace them with your own project's details.

## Prompt library

A curated set of production-quality prompts, organized by the six shapes from Chapter 4. Adapt bracketed placeholders to your own project’s vocabulary and CLAUDE.md context. These are meant to be copied into your own team’s shared prompt library alongside your context files, not memorized.

### Planning Prompts (15)

1.  “I need to [goal]. Propose two or three different approaches, with tradeoffs for each, before writing any code.”
2.  “Given the constraint that [constraint], what’s the simplest architecture that satisfies it? Explain why, don’t implement yet.”
3.  “What would change about your recommended approach if [scale/traffic assumption] were 10x higher?”
4.  “List the open questions you’d need answered before you could confidently implement [feature]. Don’t guess at answers.”
5.  “Compare implementing [feature] as a queued job versus a synchronous request. What does our codebase’s existing pattern suggest we should default to?”
6.  “Sketch, in prose only, how you’d break [large feature] into independently shippable increments.”
7.  “What’s the smallest version of [feature] that would still be genuinely useful, versus the full scope as described?”
8.  “Given our existing [pattern/convention from CLAUDE.md], what’s the idiomatic way to add [new capability] without introducing a second, competing pattern?”
9.  “What’s the migration path if we need to change [decision] later? Does the approach you’re proposing make that easier or harder?”
10. “Before proposing an implementation, ask me any clarifying questions you have about scope, authorization, and edge cases.”
11. “What would you flag as a risk in this approach if you were reviewing it, not proposing it?”
12. “Which of these two approaches is easier to test in isolation, and why?”
13. “If this feature needs to support [future requirement] within a year, does your proposed approach make that easy or does it need to be rearchitected?”
14. “What’s the failure mode of this approach under [specific adverse condition]?”
15. “Propose an approach, then argue against your own proposal as if you were a skeptical senior reviewer.”

### Exploration Prompts (10)

16. “Explain how [existing subsystem] currently works in this codebase. Cite the specific files and methods involved. Don’t propose any changes.”
17. “Where in this codebase is [business rule] currently enforced? Is it enforced in more than one place?”
18. “Walk me through what happens, step by step, when [specific user action] occurs, from request to response.”
19. “What assumptions does [class/service] make about its inputs that aren’t enforced by type hints or validation?”
20. “Find every place in the codebase that touches [table/model] and summarize what each does with it.”
21. “Is there existing precedent in this codebase for [pattern] I should follow, or would this be the first instance?”
22. “What would break if I removed [method/class] entirely? Trace its usages.”
23. “Explain the difference between [ClassA] and [ClassB] in this codebase — why do both exist?”
24. “What test coverage currently exists for [subsystem], and what’s conspicuously missing?”
25. “Summarize the authorization model for [feature] as it’s actually implemented today, not as it’s documented.”

### Architecture Prompts (10)

26. “Given that this needs to work in a multi-tenant context, what pattern fits better here — and why not the more obvious single-tenant pattern?”
27. “Should this logic live in a service, a model scope, or a query object, given our existing conventions?”
28. “What’s the tradeoff between an event-driven approach and a direct method call here, given our current infrastructure?”
29. “Does this warrant a new abstraction, or is that premature given we only have one implementation so far?”
30. “How would this decision differ if we expected 100x the current data volume?”
31. “What’s the blast radius if this component fails? Does that change how isolated it should be?”
32. “Should this be synchronous or queued, given our existing job infrastructure and this operation’s latency profile?”
33. “Is this a case where a value object would reduce bugs, or is a plain array/DTO sufficient here?”
34. “What existing interface in this codebase could this new component implement, rather than introducing a new one?”
35. “Given our forbidden-patterns list in CLAUDE.md, is there a cleaner way to achieve this than the first approach that comes to mind?”

### Refactoring Prompts (15)

36. “This has no tests. Before we refactor, write characterization tests that capture its current behavior exactly, including any bugs — don’t fix anything yet.”
37. “Extract [logic] into a new [ClassName] using dependency injection. Preserve behavior, including existing bugs, unless I tell you otherwise. Keep this to a single, small, reviewable change.”
38. “What would you extract first if you were refactoring this file, and why that piece specifically?”
39. “Is this duplication accidental (should be unified) or coincidental (looks similar but represents a different concept)?”
40. “Rewrite this using dependency injection instead of the static facade call, preserving exact behavior.”
41. “What’s the smallest safe first step toward breaking up this class?”
42. “Identify code smells in this file without fixing them yet — just list and categorize them.”
43. “Would extracting an interface here actually help, or is this a case of unnecessary abstraction?”
44. “This method has six responsibilities. Propose a split, one responsibility per new method, without changing any behavior.”
45. “Convert this from array-based data passing to a typed DTO, preserving exact current behavior.”
46. “What’s the risk profile of refactoring this specific piece, given it touches [sensitive subsystem]?”
47. “Show me the diff for just the next single extraction — don’t do the whole file at once.”
48. “Does this refactor change the public interface of this class? If so, what else needs updating?”
49. “Is there a Rector rule that could do this mechanical transformation more safely than a hand-written prompt?”
50. “After this extraction, re-run the characterization tests and confirm they’re still green before we proceed.”

### Review Prompts (15)

51. “Review this diff as a security-focused senior engineer. Flag anything that could leak tenant data across the multi-tenant boundary.”
52. “Review this as if you were the on-call engineer who’ll get paged if it breaks at 2am. What worries you?”
53. “Does this code belong in the layer it’s currently in, per our architecture conventions?”
54. “What would you ask the author of this diff in a PR review, if you could only ask one question?”
55. “Is there an N+1 query risk anywhere in this diff?”
56. “Does this diff introduce any mass-assignment risk given our model’s `$fillable`/`$guarded` configuration?”
57. “Review this for maintainability specifically — would a new hire understand this in six months without asking anyone?”
58. “Are there any silent behavior changes in this diff relative to what was asked for?”
59. “Does this handle the case where [specific edge case] occurs?”
60. “Is there a simpler way to achieve this same behavior with less code?”
61. “Review this diff purely for naming — are these names accurate, or do they overpromise what the code does?”
62. “What’s the worst-case performance characteristic of this code as data volume grows?”
63. “Does this diff follow the forbidden-patterns section of our CLAUDE.md?”
64. “If this were a human’s first pull request on the team, what feedback would you give, kindly but honestly?”
65. “Would you approve this diff as written, or request changes? Be specific about why.”

### Testing & Debugging Prompts (15)

66. “Write tests for the edge cases and failure modes first: [list specific scenarios]. Add happy-path tests after those.”
67. “Does this test suite assert on observable behavior, or does it assert on implementation details that would break under a valid refactor?”
68. “What edge case is conspicuously untested here?”
69. “Would this test suite have caught [specific known bug]? Walk through why or why not.”
70. “Here’s a stack trace: [paste]. Help me narrow down which layer this is coming from before proposing a fix.”
71. “Here’s a query log showing a performance problem: [paste]. What pattern does this suggest?”
72. “Does this proposed fix address why the condition occurred, or does it just prevent this specific symptom from appearing?”
73. “What would a mutation of this boundary condition look like, and would our current tests catch it?”
74. “Given this bug report, what’s the smallest reproduction case you’d want before attempting a fix?”
75. “Is this test flaky because of timing, shared state, or a genuine race condition in the production code?”
76. “Write a regression test that specifically encodes the bug we just fixed, so it can never silently reappear.”
77. “What’s the difference between what this test executes and what it actually asserts?”
78. “Given this log output, what are two or three plausible root causes, ranked by likelihood?”
79. “Would this fix work identically for a tenant with zero records, given the boundary condition we discussed?”
80. “Explain, in plain language, why this test currently fails, before proposing a fix for the underlying code.”

### Miscellaneous / CI & Workflow Prompts (20)

81. “Generate a GitHub Actions workflow that runs PHPStan, Pint, and PHPUnit as separate, independent jobs.”
82. “What PHPStan level is realistic to start at for an existing codebase of this size, given the current finding count at level 9?”
83. “Write a `phpstan.neon` baseline that lets us adopt strict analysis without fixing every existing finding immediately.”
84. “Draft a commit message for this diff that explains why, not just what.”
85. “Summarize this diff for a PR description aimed at reviewers unfamiliar with this subsystem.”
86. “What’s a reasonable branch protection rule set for this repository given our team size?”
87. “Should this check run on every commit, or only nightly, given its runtime cost?”
88. “Does `composer audit` currently flag anything in our dependency tree that needs attention?”
89. “What Rector rule set would help modernize this codebase toward PHP 8.4 idioms safely?”
90. “Given our current CI runtime, which job should run first to fail fast most cheaply?”
91. “Write a CLAUDE.md section documenting this newly introduced pattern so future sessions respect it.”
92. “What forbidden-pattern entry should we add to CLAUDE.md based on the bug we just found?”
93. “Draft an AGENTS.md equivalent of our CLAUDE.md for a Copilot-based teammate.”
94. “What’s missing from our current context file that this session’s confusion suggests we should add?”
95. “Summarize today’s session into a short changelog entry.”
96. “What manual QA steps, if any, should accompany this change before release, given our current test coverage gaps?”
97. “Is this change safe to deploy independently, or does it need to be coordinated with another in-flight change?”
98. “What’s the rollback plan if this specific deployment causes a production issue?”
99. “Given our current feature flag conventions, should this ship behind a flag?”
100. “Write a short internal announcement for the team about this new capability, in plain, non-marketing language.”

------------------------------------------------------------------------

## Context file templates

### B.1 Complete CLAUDE.md Example (Laravel application)

    # Project Context: [Application Name]

    ## What this application does
    [Two or three sentences — plain language, not marketing copy. A new engineer
    or an AI agent should understand the domain immediately.]

    ## Domain vocabulary
    - "[Term]" means [precise definition], not [common confusion this term invites].
    - "[Term]" is distinct from "[similar term]" because [reason].
    - [List every domain-specific term that a general-purpose reading of the code
      would get subtly wrong. This section prevents more downstream confusion
      than any other section in the file.]

    ## Architecture conventions
    - Controllers are thin. Business logic belongs in app/Services.
    - [Repository pattern / interface-driven services / whatever your team
      actually does — describe what's actually true of this codebase, not an
      aspirational ideal you haven't fully adopted yet.]
    - Money is always handled as integer cents, never floats. [Or your
      equivalent domain-critical numeric convention.]
    - Domain events are dispatched for [list of business-significant occurrences]
      and consumed by [listeners/subscribers pattern in use].

    ## Testing conventions
    - We use [Pest/PHPUnit]. New features require both a happy-path test and at
      least one edge-case test before merge.
    - [Any project-specific testing conventions — factories, seeders, database
      strategy for tests.]

    ## Forbidden / footgun patterns
    - Never call [legacy system / direct SQL / specific shortcut] directly.
      Always go through [the sanctioned abstraction], because [the non-obvious
      reason this matters — a retry quirk, a caching requirement, a compliance
      rule].
    - Never [pattern that looks reasonable in general but is wrong here].

    ## Common tasks (reusable prompts)
    - Adding a new API endpoint: [link to or paraphrase your team's standard
      approach].
    - Adding a new queued job: [same].

    ## Recent architecture decisions worth knowing
    - [Date/PR reference]: We chose [decision] over [alternative] because
      [reason]. This is documented here specifically because it isn't obvious
      from reading the resulting code alone.

### B.2 Directory-Scoped Context Example

`app/Services/Billing/CLAUDE.md`:

    # Context: Billing Services

    This directory implements quota calculation and the legacy SOAP billing
    integration. In addition to the root CLAUDE.md conventions:

    - Never call the legacy SOAP client (App\Legacy\SoapBillingClient) directly.
      Always go through LegacyBillingAdapter, which handles a retry quirk in the
      upstream service not visible from the SOAP client's own interface.
    - Quota calculations must account for the 30-day archived-issue grace period:
      archived issues still count toward quota for 30 days after archiving.
    - All money values in this directory are integer cents. A float appearing
      anywhere in this directory's arithmetic is almost certainly a bug.

### B.3 AGENTS.md / Repository Instructions Equivalent

For tools that use a differently-named convention (Copilot’s repository custom instructions, or an `AGENTS.md` file), the content is functionally identical to CLAUDE.md above — only the filename and, in some tools, the exact frontmatter/format differ. Maintain one canonical source of truth and either symlink or keep both files in sync via a small script if your team uses more than one tool, rather than letting them drift independently. OpenCode reads `AGENTS.md` and falls back to `CLAUDE.md` only when there is no `AGENTS.md`, so a repo with both gets the `AGENTS.md` copy.

------------------------------------------------------------------------

## Reusable workflows

### C.1 Daily Workflow

1.  Pull latest, confirm green baseline (`php artisan test`).
2.  Review context files for anything stale from yesterday’s work; update if a decision changed.
3.  Pick your day’s first task; run a planning prompt before any generation.
4.  Work in small, reviewed, committed increments throughout your working day.
5.  End of your workday: confirm CI is green on your final push, not just local checks.

### C.2 Bug Workflow

1.  Reproduce the bug yourself before involving AI at all.
2.  Gather real evidence — logs, stack traces, a minimal reproduction case.
3.  Ask for narrowing (which layer) before asking for a fix.
4.  Get a proposed fix; explicitly ask whether it addresses root cause or suppresses a symptom.
5.  Verify the fix against your original reproduction, not against the model’s confidence.
6.  Write a regression test encoding the specific bug so it can’t silently reappear.
7.  Commit fix and regression test together, with a message explaining why, not just what.

### C.3 Feature Workflow

1.  Take the ticket as given, however underspecified.
2.  Ask the agent to raise clarifying questions before proposing an implementation.
3.  Resolve ambiguity explicitly, in writing if the answer is non-obvious enough to belong in CLAUDE.md later.
4.  Get a plan with at least two approaches and tradeoffs; choose deliberately.
5.  Build in small, individually reviewable increments.
6.  Apply the Chapter 6 review checklist to each increment: architecture, correctness, security, naming/complexity, performance, maintainability.
7.  Generate edge-case-first tests.
8.  Push through CI; do not merge on a red or skipped check.

### C.4 Refactoring Workflow

1.  Confirm test coverage exists for the code about to change; if not, write characterization tests first, current behavior only, bugs included.
2.  Identify the smallest safe first extraction.
3.  Explicitly instruct: preserve behavior, including existing bugs, unless told otherwise.
4.  Extract, verify against characterization tests, commit.
5.  Repeat for the next extraction, one at a time.
6.  If a genuine bug fix is warranted, do it as a separate, explicitly labeled commit — never silently bundled into a structural change.

### C.5 PR Workflow

1.  Confirm CI is green on the branch before requesting human review.
2.  Write a PR description explaining why, not just what, ideally AI-drafted from the diff and then edited by you for accuracy.
3.  Apply the Chapter 6 checklist yourself before requesting review, even though CI passed — CI catches mechanical issues, not architecture-fit or business-context judgment calls.
4.  Respond to review feedback the same way you would on any human-authored PR — no special deference because “the AI wrote it,” no special dismissal either.

### C.6 Release Workflow

1.  Confirm the full CI pipeline is green, including any scheduled/gated mutation testing runs relevant to the modules touched.
2.  Review the aggregate changelog for the release — does anything touch a high-risk module (billing, authorization, multi-tenant boundaries) that warrants extra manual QA regardless of automated coverage?
3.  Confirm a rollback plan exists and is understood before deploying, especially for any AI-assisted change to a high-risk subsystem.
4.  Deploy; monitor the specific metrics/logs relevant to whatever was changed, for a deliberate window afterward, rather than assuming green CI equals safe production behavior.

------------------------------------------------------------------------

## Checklists

### Code review: work through it in this order

1. **Architecture fit.** Is this change in the right layer?
2. **Correctness.** Does it do what was actually asked, including the edge cases?
3. **Security and authorization.** Give this its own pass; don't fold it into correctness.
4. **Naming and complexity.** Only once 1–3 are clean.
5. **Performance.** Look for N+1 queries, missing eager loads, and unnecessary raw SQL.
6. **Maintainability.** Would a new team member understand this in six months?

For security, ask the specific question ("what happens if this ID belongs to another tenant?") rather than the generic one ("is this secure?").

### Test quality: does your suite actually prove anything?

- [ ] Do the tests cover edge cases and failures, not just the happy path?
- [ ] Do they check what the code does, rather than how it does it?
- [ ] Would they have caught the last real bug you know about?
- [ ] Break one boundary on purpose (for example, change `<` to `<=`). Does a test fail?
- [ ] Make a method always return the same value. Does a test fail?

If a deliberate break doesn't fail a test, that test isn't guarding the code. Write the one that does.
