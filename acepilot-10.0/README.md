# AcePilot for Claude Code

A self-evolving autonomous engineer for Claude Code. Not a fork, not a wrapper, not a separate tool. It's 12 config files (+5 templates) that install into Claude Code's native config structure, adding a `/acepilot` command that transforms how Claude Code behaves for the rest of that session.

Without it, Claude Code runs 100% stock. With it activated, Claude Code absorbs your full project context, captures proven workflows as replayable playbooks, auto-tunes its own execution engine from data, chains objectives across sessions, and deploys to any platform. Ship today. Ship 42% faster by session 10.

---

## Quickstart

```bash
git clone https://github.com/acepilot-app/acepilot.git
cd acepilot
./acepilot-10.0/install.sh              # Install user-level files
cd your-project && "/path/to/acepilot-10.0/install.sh" --project  # Add hooks to a project
```

Then open Claude Code and type `/acepilot` or say "start ace".

---

## Install

> **iCloud Drive / OneDrive / Dropbox:** If AcePilot is stored in a cloud-synced folder whose path contains spaces (e.g. `~/Library/Mobile Documents/...`), quote the path when invoking the installer: `"/path/with spaces/acepilot-10.0/install.sh"`. The installer itself is path-safe — all internal file operations use proper quoting.

### Using the install script (recommended)

```bash
git clone https://github.com/acepilot-app/acepilot.git
cd acepilot

# Install user-level files (applies to ALL your projects)
./acepilot-10.0/install.sh

# Install project-level files (do this in each project you want AcePilot on)
cd your-project
"/path/to/acepilot-10.0/install.sh" --project
```

The installer detects existing files: missing files are created, identical files are skipped, changed files are backed up (with timestamp) before overwriting. Run `"./acepilot-10.0/install.sh" --check` to verify installation, or `--uninstall` to remove.

> **Note:** If existing files were backed up during install, review the backups and merge any custom content — especially `~/.claude/CLAUDE.md` and `~/.claude/settings.json`.

### Manual install

<details>
<summary>Click to expand manual install steps</summary>

```bash
git clone https://github.com/acepilot-app/acepilot.git
cd acepilot

# Copy user-level files (these apply to ALL your projects)
cp "acepilot-10.0/user-level/CLAUDE.md" ~/.claude/CLAUDE.md
cp "acepilot-10.0/user-level/settings.json" ~/.claude/settings.json
mkdir -p ~/.claude/commands ~/.claude/agents
cp "acepilot-10.0/user-level/commands/acepilot.md" ~/.claude/commands/acepilot.md
cp "acepilot-10.0/user-level/agents/researcher.md" ~/.claude/agents/researcher.md
cp "acepilot-10.0/user-level/agents/reviewer.md" ~/.claude/agents/reviewer.md
cp "acepilot-10.0/user-level/agents/designer.md" ~/.claude/agents/designer.md
cp "acepilot-10.0/user-level/agents/security.md" ~/.claude/agents/security.md
cp "acepilot-10.0/user-level/agents/architect.md" ~/.claude/agents/architect.md
cp "acepilot-10.0/user-level/agents/strategist.md" ~/.claude/agents/strategist.md

# Copy project-level files (do this in each project you want AcePilot on)
cd your-project
mkdir -p .claude/rules .claude/state
cp "/path/to/acepilot-10.0/project-level/settings.json" .claude/settings.json
cp "/path/to/acepilot-10.0/project-level/rules/browser.md" .claude/rules/browser.md

# Optionally, seed the knowledge file (auto-seeds on first run, but you can pre-populate)
cp "/path/to/acepilot-10.0/KNOWLEDGE-TEMPLATE.md" .claude/state/KNOWLEDGE.md
cp "/path/to/acepilot-10.0/PATTERNS-TEMPLATE.md" .claude/state/PATTERNS.md
```

> **Note:** If you already have a `~/.claude/CLAUDE.md` or `~/.claude/settings.json`, merge the contents rather than overwriting.

</details>

---

## What's new in v10.0

**Playbook System** — PLAYBOOKS.md (13th state file). Every successful creation session saves its task sequence, decisions, and specialist routing as a replayable recipe. Next time you give the same type of directive, AcePilot runs the proven playbook instead of figuring it out from scratch. Includes staleness detection (verifies file paths still exist before replay) and evolution tracking (updates playbooks when replays improve on the original).

**Self-Calibrating Engine** — Every 10 sessions, AcePilot reads its own execution data and adjusts: gates that were over/under-protective shift levels, specialists that added noise on specific content types get auto-skipped, ceremony tiers recalibrate from actual cycle times. Token-budgeted reads (last 30 rollups + 50 entries per section). Force with `god --calibrate`.

**Session Chains** — Objectives persist across sessions. End-of-session writes a handoff to CONTEXT.md (objective, progress, next actions, open questions, momentum). Next session picks up where you left off — no re-scanning, no lost context. 5-session chain limit forces re-evaluation with DECISIONS.md write.

**Deploy Pipeline** — Auto-detects your deployment platform (Vercel, Netlify, Docker, GitHub Pages, FTP) during ABSORB. In god/ship modes: builds, deploys, and runs a 7-point verification checklist automatically. GitHub Actions deployments get a pre-push workflow status check. Opt-out with `[no-deploy]` in objective.

**Upgraded Agents** — @researcher gains PLAYBOOK MODE for workflow capture after successful sessions. @strategist gains session chain lens (flags stalled chains) and deploy verification lens.

**ANALYTICS.md expanded** — Two new sections: Calibration Log (tracks every self-calibration cycle) and Chain Log (tracks multi-session objective progress).

---

## The 12 files (+install script) and what each does

### ~/.claude/CLAUDE.md (2 paragraphs)

The trigger. Loaded on every session but does almost nothing — it routes natural language to slash commands. "START ACEPILOT" runs `/acepilot`. "START ACE GO" runs `/acepilot go`. Same for `resume`, `continue`, `plan`, `status`, `review`, `ship`, `auto`, and `god`. Old names ("START AUTOPILOT", "full", "dreamteam", "ceo") are kept as backward-compat aliases. Claude behaves completely normally otherwise.

The second paragraph is a compaction survival instruction. It tells Claude what to preserve when compacting: TASKS.md verbatim, all decisions, current task ID, modified files, and all product knowledge from KNOWLEDGE.md and USER-KNOWLEDGE.md. This instruction lives here — not inside the command — because CLAUDE.md is always loaded, even after compaction wipes the command text. In v19 the compaction instructions were inside the command file, which meant the instructions for surviving compaction were themselves lost during compaction.

### ~/.claude/commands/acepilot.md (~441 lines)

The brain. A Claude Code slash command — a markdown file with YAML frontmatter that defines a reusable prompt. When you run `/acepilot`, Claude Code injects this entire file as instructions for the current session. It contains:

**Prime Rules** (4 rules wrapped in `<important>` tags for maximum adherence):

- Project spec wins — your project's CLAUDE.md overrides AcePilot's defaults
- Match what exists — read the target file first, follow its conventions
- Extend only — never replace or delete without explicit instruction
- Cross-reference — check if the spec already says how, if a util already exists

**Operator profile:** Tells Claude you're a solo founder who gives short directives, so it should interpret intent and make its best call rather than asking clarifying questions. If ambiguous, make the best call and state the assumption in one line.

**Execution flow:**

```
TASK → Orient → Scope check → Confidence gate → The Algorithm → Do → Verify → Specialist review → Qualify → Log → Checkpoint
```

Routes by scope: 1-2 files = direct execution, 3-5 files = plan first, 6+ files = break into subtasks. Parallel execution for independent AUTO-confidence tasks via worktree-isolated subagents (up to 3). Research = @researcher (Haiku), review/qualify = @reviewer (Sonnet), implementation = main session (Opus).

**Confidence gate** (new in v20, refined in v22): Every task is classified before execution:

- **AUTO**: Exact fix, follows existing patterns, touches ≤2 files → execute immediately
- **PLAN**: Known approach, 3-5 files or introduces new pattern → show 3-line plan, proceed unless operator interrupts with Esc
- **ASK**: Multiple valid approaches, touches >5 files, or changes public API → stop, present options, wait for decision

Hard rules: never classify as AUTO if it changes a public interface, deletes functionality, adds a dependency, or the existing pattern is unclear.

**Verification checklist:** Build passes, lint clean, targeted tests pass, browser verification if UI change. Never report done without running these. After modifying a function, check if tests exist — run them specifically or flag `⚠ No tests`. Auto/full modes auto-create missing tests.

**Qualify step** (new in v37, enhanced in v38): After verification passes, independently re-read the actual changes (`git diff`) against the task specification. Reports one of four statuses: DONE, DONE_WITH_CONCERNS (works but has a caveat), NEEDS_CONTEXT (can't determine correctness), or BLOCKED (failed with root cause: intent/spec/code). Quick tasks self-qualify in the main session. Standard/Complex tasks delegate to @reviewer in qualify mode for independent verification. Source: PAUL's Execute/Qualify loop.

**Micro-log:** After each action, emit exactly one line with a task counter:

```
[7/15] ✓ auth.ts:42 — added try/catch around token verify
[7/15] ✗ payments.ts:88 — retry conflicts with timeout (→ ASK)
[8/15] ⊘ skipped webhook.ts — BLOCKED, missing endpoint
```

Four symbols: `✓` done, `✗` problem, `⊘` skipped, `⟳` working. Related changes batch into one line. The `[N/total]` counter means you always know progress at a glance.

**Git checkpoints:** After each verified task, stage changed files and commit with a machine-parseable message: `acepilot: [TASK-ID] verb description`. If verification fails, stash the changes, log the failure in DECISIONS.md with the stash ref, mark the task as Blocked, and continue to the next task. This gives you clean per-task commits for review, cherry-picking, and rollback.

**Circuit breaker** (new in v37, replaces 2-strike): Three states — CLOSED (normal), OPEN (halted after 3 consecutive failures or 3 identical errors), HALF_OPEN (recovery attempt via Plan mode subtask decomposition). If the first subtask succeeds → CLOSED. If it fails → remain OPEN, mark BLOCKED, move on. Source: Ralph's circuit breaker pattern.

**Spiral detection** (new in v26): If the same file is edited 3+ times within a single task, Claude stops, enters Plan mode, and breaks the task into subtasks. This catches the most common go-mode failure: retrying the same broken approach repeatedly.

**Spiral recovery:** When changes snowball — stop, Esc+Esc to rewind, enter Plan mode, break into smaller pieces.

**Context discipline** (wrapped in `<important>` tags):

- Research → delegate to @researcher subagent
- Side questions → /btw (answers discarded from history)
- File reading → view with line ranges, not cat
- Never recap instructions or repeat errors
- Every 5 completed tasks → write all state files + proactively `/compact`

**Token discipline:** Hard rules for protecting the context window:

- Need to read >500 lines → delegate to @researcher
- Need to review >5 files → delegate to @reviewer
- Single task requires >3000 lines total → break into subtasks
- Every 5 tasks → write state files, then `/compact` with explicit preservation instructions

**Safety preferences:** Check existing before installing. Find existing before creating. Use builtins over wrappers. Root-cause over downgrades. `.env.example` over secrets. Feature branches.

**Compounding rule:** When something goes wrong, suggest making it a permanent rule in the appropriate location:

- CLAUDE.md for universal rules
- .claude/rules/ for path-scoped rules
- Hooks for things that must happen 100% of the time
- Skills for domain knowledge loaded on demand
- KNOWLEDGE.md for product-level discoveries (new in v24)

When the @reviewer repeatedly flags the same pattern, AcePilot suggests creating a `.claude/rules/` entry to prevent it permanently. (New in v25.)

**State system** (new in v20, expanded in v24): Four files in `.claude/state/`, each with a distinct purpose:

**TASKS.md** — Strict checklist format. Never summarized during compaction. Structured format that survives any level of context degradation:

```
## Queue
- [ ] `P0` FIX build error in auth middleware — `src/middleware/auth.ts:42` [id:auth-fix] [score:12.0]
- [ ] `P1` FIX failing user tests — `tests/api/users.test.ts` [needs:auth-fix] [score:2.8]
- [x] `P2` FEAT checkout error states — `src/pages/checkout.tsx` [score:2.0]
## Blocked
- [ ] `P1` FEAT webhook verification — BLOCKED: need endpoint URL
```

Tasks tagged with `[id:name]` are blockers. Tasks tagged with `[needs:name]` depend on them. Execution order: blockers first, then by priority, then by dependency chain length. If an IMPROVE task unblocks 3+ other tasks, it gets promoted one priority level.

**DECISIONS.md** — Append-only. Never edited. Never compacted. Records WHY decisions were made, not just what was done:

```
## 2026-03-28 14:22 — auth middleware error handling
CHOSE: custom error class · WHY: existing pattern in src/lib/errors.ts · FILES: auth.ts, errors.ts
---
```

On resume, Claude reads this to understand the reasoning behind past choices. The @reviewer agent also reads it to understand intent when reviewing code.

**CONTEXT.md** — Session scratchpad. Free-form. The only state file that may be condensed during compaction. If it degrades, TASKS.md, DECISIONS.md, and KNOWLEDGE.md still provide everything resume needs.

**KNOWLEDGE.md** — Product understanding. Never compacted. Never summarized. Grows across sessions. Captures what the product does, who it's for, business constraints, tried-and-abandoned approaches, current priorities, non-obvious facts, and **revenue paths** (directories/files that directly handle payments, auth, onboarding, or core user value — tasks touching these paths get Impact=5 in APS automatically). This is understanding, not tasks — prose sections, not checklists. Created automatically on first `/acepilot go` or `/acepilot plan`. The @researcher adds entries when it discovers non-obvious product facts. (New in v24.)

**USER-KNOWLEDGE.md** — User model. Never compacted. Never summarized. Grows across sessions. Six sections: Preferences, Patterns, Corrections, Communication style, Risk tolerance, Blind spots. Every entry date-stamped with source. Written by the compound rule whenever a user correction or confirmed preference surfaces during a task. Read at absorb (step 4b) alongside KNOWLEDGE.md. The moat: as this file grows, every judgment call Ace makes is shaped by accumulated understanding of how _this specific user_ thinks and works — context no competitor has. (New in v47.)

**Task ordering:** Dependency-aware. Blockers execute before dependents. Tasks with `[needs:X]` can't start until X is done. An IMPROVE task that unblocks 3+ other tasks gets promoted one priority level.

**Absorb sequence:** 8 steps that scan the project on activation:

1. Create state directory
2. List .claude/ folder contents
   2b. List .claude/rules/ contents (new in v25 — discover which path-scoped rules exist)
3. Read project config (auto-detects package.json, pyproject.toml, Cargo.toml, go.mod, Makefile)
4. Read existing state files (TASKS.md, DECISIONS.md, CONTEXT.md)
   4b. Read KNOWLEDGE.md (new in v24)
   4c. Read MODE file (new in v34)
5. Check git log, status, and branch
6. Map source files (up to 50)

**Eight modes** (clear escalation: plan → go → auto → ship → god):

- `/acepilot` — absorb, announce ready, wait
- `/acepilot plan [focus/objective]` — scan + plan, don't execute. With objective: strategize first.
- `/acepilot go [focus]` — execute tasks, stop on issues
- `/acepilot status` — quick progress check (instant)
- `/acepilot review` — review all acepilot commits
- `/acepilot resume` — continue previous session (inherits mode + focus, needs MODE file)
- `/acepilot continue` — pick up remaining tasks from TASKS.md (works across sessions, no MODE needed)
- `/acepilot auto [focus/objective]` — self-fixing autonomous execution, stops before push (Pro)
- `/acepilot ship [focus]` — auto + specialist review + push PR (Pro)
- `/acepilot god [focus/objective]` — smart-dispatched specialists, zero stops, objective-driven (Pro)

### ~/.claude/agents/reviewer.md (70 lines, overhauled in v38)

A subagent definition. Runs on Sonnet. Has `memory: project` so it learns your codebase's patterns across sessions. Has read access + git diff/log/show/stash + state files — can't modify code.

**Two modes** (new in v38):

**Review mode** (default) — spins up in a fresh context window (unbiased — hasn't seen the code being written). Diffs against last acepilot checkpoint, reads CLAUDE.md for conventions and DECISIONS.md for intent. Returns severity-rated findings: 🔴 must fix, 🟡 should fix, 🟢 nice to have. Explicitly checks for: regressions, missing error handling, hardcoded values, security issues, untested paths.

**Qualify mode** — independent verification that changes match the task spec. Reads TASKS.md (spec), git diff (changes), DECISIONS.md (intent). Returns one of 4 statuses: DONE, DONE_WITH_CONCERNS, NEEDS_CONTEXT, BLOCKED. This is the critical insight from the PAUL framework — a separate agent verifying the work catches drift that self-review misses.

Called automatically: every 3rd task for quick review (🔴 only), after every P0 task, and for qualify on Standard/Complex tasks.

### ~/.claude/agents/researcher.md (114 lines, git access added in v39)

A subagent definition. Runs on Haiku (cheapest model — near-free). Has read-only access plus search tools, state files, and git read commands (`git log`, `git status`, `git branch` — new in v39).

**Three modes** (new in v38):

**Scan mode** — reads README, CLAUDE.md, specs, TODOs, issues, build output, and now git history. Returns structured task list (MAX 30 lines) with priorities, dependencies, and risk clusters. Supports focus: ★ prefix on matching tasks.

**Explore mode** — broad codebase search, returns structured summary (MAX 20 lines): answer, files, patterns, reusable utils.

**Knowledge mode** — extracts product understanding for KNOWLEDGE.md (MAX 15 lines): revenue paths, product, users, stack, constraints, non-obvious facts.

The key context protection mechanism: the researcher burns through files in ITS context window, not yours. Your main context only pays for the structured summary.

### ~/.claude/agents/designer.md (new in 6.0)

A subagent definition. Runs on Sonnet. UX/UI review, accessibility audit, and design lint. Invoked automatically when diffs touch HTML/CSS/JSX/TSX/Vue/Svelte files, or in dreamteam mode on every task.

Checks: Nielsen's 10 usability heuristics, responsive design (mobile-first per Wroblewski), copy quality (awareness stages per Schwartz/Baymard), and WCAG Big 6 accessibility issues (which cover 96% of real a11y failures). Returns severity-rated findings: 🔴 must fix, 🟡 should fix, 🟢 nice to have.

Grounded in: Nielsen (249 real usability problems), Norman (Gulf of Execution/Evaluation), Refactoring UI (tactical design rules).

### ~/.claude/agents/security.md (new in 6.0)

A subagent definition. Runs on Sonnet. Security audit that scans for OWASP top patterns, hardcoded secrets, injection vulnerabilities, dependency issues, and auth/header misconfigurations. Invoked automatically when diffs touch auth/login/password/token/API key/env files, or in dreamteam mode on every task.

Has access to dependency audit tools (`npm audit`, `pip audit`, `cargo audit`, `go vuln`) in addition to standard read/grep/git access. Returns severity-rated findings with exploitability assessment rather than complexity scores.

Grounded in: Schneier (attacker mindset), OWASP Top 10 (top 3 cover 40%+ of vulnerabilities), Troy Hunt (practical automation), NIST CSF (Identify + Protect).

### ~/.claude/agents/architect.md (new in 6.0)

A subagent definition. Runs on Sonnet. Architecture and performance review that detects coupling issues, N+1 queries, bundle bloat, circular dependencies, and scaling bottlenecks. Invoked automatically when diffs add new modules/imports/dependencies or touch database/query code, or in dreamteam mode on every task.

Checks: Utilization, Saturation, Errors (Gregg's USE method), web performance (Souders' top 3 rules cover 70% of improvements), coupling and cohesion metrics (Fowler/Martin). Returns severity-rated findings focused on "does this cause real problems?" — not architecture astronautics.

Grounded in: Gregg (USE method), Souders (14 web perf rules), Fowler/Martin (coupling + cohesion), Gene Kim (Three Ways), Google SRE (playbooks = 3x MTTR improvement).

### ~/.claude/agents/strategist.md (new in 6.0)

A subagent definition. Runs on Sonnet. Product strategy review that checks analytics instrumentation, testing strategy, documentation quality, and growth patterns. Invoked automatically when diffs add new features/user flows/payment/analytics, or in dreamteam mode on every task.

Checks: critical-path-first testing (Beck/Fowler pragmatic test pyramid), documentation that developers actually read (Divio/Stripe patterns), retention-first metrics (Ellis 40% rule for PMF), activation benchmarks (Rachitsky: time-to-value < 15 min). Returns severity-rated findings.

Grounded in: Beck/Fowler (pragmatic testing), Lean Analytics (OMTM), Divio/Stripe (docs developers read), Ellis (40% PMF rule), Rachitsky (activation benchmarks), Goldratt (Theory of Constraints).

### ~/.claude/settings.json — user level (42 lines, no jq dependency since v34)

User-level permissions and status line. Three blocks:

**Status line** (new in v33, enhanced in v40): Reads `.claude/state/MODE` and `.claude/state/TASKS.md` and displays `AcePilot: [mode] [done/total]` in the Claude Code UI when AcePilot is active (e.g. `AcePilot: auto [5/12]`). Shows mode only when no tasks exist yet. Shows nothing when inactive. This is a shell command that runs on each status line refresh — reads two tiny files, zero cost.

**Allow list:** Every tool Claude uses during normal work:

- Core tools: Read, Write, Edit, MultiEdit, Glob, Grep, WebFetch, WebSearch
- Task tools: TodoRead, TodoWrite
- All bash commands: `Bash(*)` — one wildcard covers everything
- All MCP tools: `mcp__*` — covers Chrome MCP (browser verification), plus any future MCP integrations

Previous versions (v17-v21) used granular bash patterns (`Bash(grep *)`, `Bash(git log *)`, etc.) which fought Claude Code's pattern matching and still missed edge cases. v22 simplified to `Bash(*)`. v23 added `mcp__*` and `TodoRead`/`TodoWrite` to eliminate the remaining prompts.

**Deny list:** Hard blocks that can never execute, even with `Bash(*)` allowing all bash. Deny rules have highest precedence in Claude Code — they can't be overridden by allow rules:

- `rm -rf` (multiple patterns covering `/`, `.`, and `./path` variants)
- `sudo rm`, `sudo chmod`, `chmod 777`
- `git push --force`, `git push -f`
- `curl|bash`, `wget|bash` (pipe-to-shell)
- `DROP TABLE`, `DROP DATABASE`
- `git reset --hard` (discards all uncommitted work, new in v26)
- `git checkout -- .` (discards all uncommitted changes, new in v25)
- `git clean -fd*` (removes untracked files permanently, new in v25)
- `> /dev/sd*`, `mkfs` (disk writes)

### .claude/settings.json — project level (31 lines)

Four hooks — deterministic scripts that fire at specific lifecycle points:

**SessionStart:** Injects current branch + last commit as context. Every session starts knowing where you are in git. If a MODE file exists from a previous interrupted session, also injects: `Previous AcePilot mode: [mode] (run /acepilot resume to continue)`. (Enhanced in v34.)

**PostToolUse (Write|Edit|MultiEdit):** Auto-formats every file Claude edits using the right tool for the language: `ruff`/`black` for Python, `rustfmt` for Rust, `gofmt` for Go, `prettier` for everything else (JS/TS/CSS/HTML/JSON/MD). Falls back silently if the formatter isn't installed. Mechanical, not advisory — happens 100% of the time on every edit. (Polyglot since v40; previously prettier-only.)

**PreToolUse (Bash):** Secondary safety net. Scans every bash command for dangerous patterns before execution using grep. Blocks with exit code 2 and feeds the reason back to Claude. This is redundant with the deny list but provides defense-in-depth — the deny list works at the permission level, this hook works at the command level. In v26, the grep pattern was synced to cover all deny list entries (previously only matched 6 of 14).

**PreCompact:** Copies all state files (TASKS.md, DECISIONS.md, CONTEXT.md, KNOWLEDGE.md, and legacy STATE.md) to `.claude/backups/` with timestamps before every compaction. Runs async so it doesn't block the compaction. Your session state is never lost, even if compaction degrades quality. (Updated in v24 to include KNOWLEDGE.md.)

### .claude/rules/browser.md (10 lines, project level)

A path-scoped rule file. The `paths:` frontmatter means this ONLY loads when Claude is editing UI files (.tsx, .jsx, .vue, .svelte, .css, .scss, anything in src/components/, src/app/, src/pages/). Zero cost when you're working on backend code.

When active, it tells Claude to verify every visual change through browser MCP:

1. Navigate to dev server URL
2. Take accessibility snapshot — verify DOM rendered correctly
3. Check browser console — catch runtime errors
4. If interactive: fill inputs, click buttons, wait for responses, snapshot again
5. Report: `✓ Browser: [what verified]`

In v26, the instructions use tool-agnostic language (plain English actions instead of pseudo-tool-names) so they work regardless of which browser MCP server is configured.

Hard rules: code compiling does not equal rendering correctly. If Chrome connection drops, reconnect with `/chrome`. If login/CAPTCHA/dialog appears, stop and ask the operator.

### .claude/state/KNOWLEDGE.md (template, new in v24)

Product knowledge layer. Created automatically on first `/acepilot go` or `/acepilot plan`. Grows across sessions. Never compacted. Never summarized.

Captures WHY things exist, not just WHAT exists. CLAUDE.md has architecture and conventions. DECISIONS.md has individual choices. KNOWLEDGE.md has product-level understanding that spans multiple sessions:

- What the product does
- Who it's for
- Business constraints
- Tried and abandoned approaches (with WHY — prevents re-discovering dead ends)
- Current priorities and reasoning
- Non-obvious facts (API quirks, platform limitations, vendor requirements)

Format: prose sections, not checklists. This is understanding, not tasks.

---

## The eight modes

### `/acepilot` (or `start ace`)

Absorbs the project: creates state directory, scans .claude/ folder, reads project config (package.json, pyproject.toml, Cargo.toml, etc.), reads existing state files (including KNOWLEDGE.md), checks git history/status/branch, maps source files. Announces ready with project name, stack, and branch. Then waits for you to give it a task.

### `/acepilot go` (or `start ace go`)

Same absorb, then goes autonomous. Delegates the initial codebase scan to @researcher (Haiku subagent), which reads README, specs, TODOs, planning docs, open issues, build output. The researcher returns a prioritized task list. Claude writes this list to TASKS.md with confidence gates, announces how many tasks were found broken down by gate type, and starts executing from the top. If KNOWLEDGE.md is empty or missing, a second researcher call extracts product basics from README/docs and seeds it automatically. (New in v25.)

Each task goes through: confidence gate → execute → verify → micro-log → git checkpoint. Priority order: build errors (P0) → failing tests (P1) → broken features → incomplete features → improvements (P3). Keeps going until you say stop, all tasks are done, or it hits a blocker it can't resolve after 2 attempts (reduced from 3 in v26).

P0 tasks (build-breaking) always get a @reviewer check after completion, regardless of cadence — build fixes are highest risk and a bad fix cascades through everything downstream. (New in v26.) For other tasks, every 3rd completed task, Claude delegates a quick review to @reviewer: "Check last 3 acepilot commits for coherence and pattern violations. 🔴 only." If a 🔴 is found, execution stops and the finding is surfaced. Otherwise, execution continues. (New in v24.)

Every 5 completed tasks, Claude writes all state files to disk and proactively runs `/compact` to free context space, with explicit instructions on what to preserve and what to condense.

### `/acepilot resume` (or `start acepilot resume`)

Absorbs the project (skipping step 6 — file scan — if TASKS.md exists and branch matches, new in v26), then validates state before continuing:

- Reads `.claude/state/MODE` file to detect previous mode (new in v33)
- Checks git log for non-acepilot commits (someone else pushed changes)
- Checks `git diff HEAD --stat` for unexpected uncommitted changes
- Verifies current branch matches the branch recorded in CONTEXT.md
- Checks if KNOWLEDGE.md was modified since last session (new in v25)

If KNOWLEDGE.md was updated: `"KNOWLEDGE.md updated. Review before continuing?"` — priorities or constraints may have changed.

If any other mismatch is detected: `"Drift: [what changed]. Continue / re-scan / wait?"` — you decide whether to proceed with current state, re-scan the codebase, or wait.

If clean and MODE file found: `"Resuming [mode]: [task]. [N] remaining. Continue in [mode]? (y/n)"` — if confirmed, continues with all that mode's behaviors (auto-fix, auto-push, no-ASK gate, etc.). If declined, falls back to basic `go` mode. (New in v33.)

If clean with no MODE file: `"Resuming: [task]. [N] remaining."` — picks up as basic `go`.

This solves the fragility problem: a `full` session that hits context limits or crashes can now resume in `full` instead of dropping to basic mode.

### `/acepilot continue` (or `start acepilot continue`) — new in v7.1

The "keep going" mode. Inherits the last session's mode and never just stops.

Flow: Absorb + Orient. Read CONTEXT.md to detect last session's mode (e.g. "GOD MODE COMPLETE" → god, "AUTO COMPLETE" → auto). If no mode detected, defaults to `go` (Starter) or `auto` (Pro). Then: read TASKS.md — if incomplete `[ ]` tasks exist, execute them in the inherited mode. If all tasks done or no TASKS.md, scan for new work and execute in the inherited mode. Either way, it keeps going.

`"CONTINUING in [mode]. [N] remaining of [T] total."`

**When to use `resume` vs `continue`:**

- `resume` — session crashed or was interrupted (MODE file exists). Inherits exact mode + focus from that mid-session state.
- `continue` — start a new session and keep working. Inherits last session's mode from CONTEXT.md. Picks up remaining tasks or finds new ones.

### `/acepilot plan` (or `start acepilot plan`) — new in v22, enhanced in v24-v25

Same absorb and @researcher scan as `go` mode. Writes the full prioritized task list to TASKS.md with confidence gates, estimated files touched, and scope (S/M/L) for each task. Flags risk clusters — groups of tasks touching the same files. Suggests ordering conflicting tasks consecutively. If KNOWLEDGE.md is empty, seeds it from README/docs (same as `go`, new in v25). Shows you the plan. Then stops.

`"Plan ready. [N] tasks ([risk clusters]). Review TASKS.md. Run /acepilot go or /acepilot auto to execute."`

This fills the gap between "wait for instructions" and "go autonomous." Use it on the first session with a new project, or when you want to validate Claude's understanding of your priorities before it starts executing. Review the plan, adjust TASKS.md if needed, then run `/acepilot go`, `/acepilot auto`, or `/acepilot auto push`.

### `/acepilot status` (or `start acepilot status`) — new in v22, refined in v23

Skips the absorb sequence entirely. Reads `.claude/state/TASKS.md` only. Counts done/remaining/blocked. Returns one line:

`"[12/15] done. [1] blocked. Current: auth middleware fix. Next: checkout error states."`

If no TASKS.md exists: `"No tasks found. Run /acepilot plan or /acepilot go."`

Designed for quick progress checks without burning context on a full project scan. In v22, status ran the full 6-step absorb before reading one file. In v23, it goes straight to the file.

### `/acepilot review` (or `start acepilot review`) — new in v27

On-demand full review of all acepilot work. Skips absorb (fast). Finds all acepilot commits in the current session via `git log --grep="acepilot:"`. Delegates a comprehensive review to @reviewer with all severities (🔴🟡🟢), not just the 🔴-only quick checks that happen during `go` mode.

The reviewer checks for: coherence across tasks, pattern violations, missed edge cases, dead code, and test coverage gaps. Findings are reported grouped by severity with specific fix suggestions for each 🔴.

`"Review complete. [3] must fix, [5] should fix, [2] nice to have."`

Unlike the periodic go-mode reviews, this is a deliberate quality gate you invoke when you're done executing and want a full assessment before shipping. It doesn't auto-fix — it reports and waits for your decision.

### `/acepilot ship` (or `start acepilot ship`) — redesigned in 6.1

"Handle it and push a PR." The complete pipeline in one word. Absorbs, scans, executes all tasks autonomously with self-fixing (same as `auto`), then adds the ship pipeline:

1. **Full specialist review** on the final diff before push (auto-routed specialists, not all)
2. **Full verification:** build + lint + complete test suite (not just targeted tests)
3. **Commit cleanup:** Auto-squash acepilot commits into conventional commits
4. **PR:** `git push` + `gh pr create` with generated description

ASK gate still active — if Claude isn't sure about an approach, it asks. All other safety mechanisms carry over from `auto`.

`"SHIPPED. [N] tasks done, [C] concerns, [B] blocked. PR: [URL]"`

**Feature branches only** — if on `main` or `master`, refuses to push and falls back to `auto` behavior.

Previously `auto push`. Old names still work as aliases.

### Focus directive (new in v35)

Any execution mode (`go`, `auto`, `auto push`, `full`) accepts an optional focus — free text after the mode name that constrains what AcePilot works on:

```
/acepilot auto fix the auth middleware
/acepilot ship refactor API endpoints
/acepilot god build the checkout flow
/acepilot go investigate memory leak in worker threads
```

Or in natural language: `"START ACE AUTO fix auth"` — anything after the mode name becomes the focus.

**What focus changes:**

- **Scan** — @researcher prioritizes tasks matching the focus, marks them with ★
- **Task ordering** — ★ tasks execute first, regardless of priority level
- **Confidence gates** — focused tasks get +1 autonomy (ASK→PLAN, PLAN→AUTO) because the operator chose the focus deliberately
- **Status line** — shows `AcePilot: auto — fix auth middleware`
- **Resume** — inherits both mode and focus

Without focus, behavior is unchanged — full project scan with normal priorities.

### `/acepilot auto` (or `start acepilot auto`) — new in v28

"Handle it. Fix problems yourself. Let me review before pushing." The autonomous lifecycle in one command. Combines go + review + fix without stopping. Accepts an optional focus directive.

**What it does:**

1. Absorbs and scans (same as `go`, constrained by focus if given)
2. Executes all tasks with confidence gates — ASK tasks still stop for operator
3. When @reviewer finds 🔴 during periodic checks → auto-fixes instead of stopping, then re-reviews (max 2 self-fix cycles per finding)
4. When a task is blocked after 2 attempts → tries one alternative approach before marking blocked
5. After all tasks complete → auto-runs full review (all severities)
6. Auto-fixes all 🔴 and trivial 🟡 (one-line changes only), max 2 cycles
7. Auto-runs ship: build + lint + full test suite. If tests fail → auto-fixes (max 2 cycles)
8. Auto-squashes acepilot commits into conventional commits
9. Generates PR description from TASKS.md + DECISIONS.md

`"AUTO COMPLETE. [N] tasks done, [C] concerns, [B] blocked. PR ready. Push? (y/n)"`

**What stays safe:**

- Confidence gates still apply — ASK tasks still require operator input
- Spiral detection still applies — same file ×3 → Plan mode
- Self-fix cycles capped at 2 — prevents infinite fix loops
- Never pushes — the one thing that always needs a human

The difference from `go`: auto mode doesn't stop when it hits friction. It fixes review findings, retries blocked tasks with alternative approaches, and runs the ship pipeline at the end. The lifecycle is: plan → auto → review before push.

**Objective-driven** — when given a goal instead of a scope (`/acepilot auto reduce checkout drop-off`), auto mode uses Working Backwards + @researcher strategy to generate the task list first, then executes it. Same as god mode's strategy phase, but stops before push so you can review before shipping.

Previously aliased as `god`. Now `god` is the peak mode (see below).

### `/acepilot god` (or `start acepilot god`) — redesigned in 6.1

"You ARE the team. Zero stops." The peak mode. Everything AcePilot can do, turned to maximum.

**What makes god mode god mode:**

1. **All 6 specialists review every task** — not just auto-routed, ALL of them on every change
2. **Zero interruptions** — ASK gate disabled, Claude makes its best call + logs reasoning
3. **Objective-driven** — when given a goal (`/acepilot god reduce page load time`), uses Working Backwards + strategy before executing
4. **Mid-task re-orient** — when blocked, re-reads context and generates an alternative approach before circuit-breaking
5. **Auto-push + PR** — the complete pipeline, scan to merged PR

**Every autonomous decision logged in DECISIONS.md:**

```
## 2026-03-28 14:22 — auth middleware restructure
CHOSE: JWT with refresh tokens · WHY: best call — matches existing session pattern in src/lib/session.ts · ALTERNATIVES: OAuth2 PKCE (heavier), session cookies (no mobile support) · FILES: auth.ts, session.ts, middleware/index.ts
---
```

**Execution flow per task:**

1. Orient → Gate → Algorithm → Execute → Verify
2. Full specialist review (ALL specialists in parallel):
   - Batch 1: @reviewer + @designer + @security
   - Batch 2: @architect + @strategist
3. 🔴 → auto-fix + re-verify (max 2 cycles)
4. 🟡 → auto-fix
5. 🟢 → log only
6. Qualify → Checkpoint

**When given an objective** (e.g. `god reduce page load time`):

1. **Working Backwards** — write target PR description first
2. **Strategize** — @researcher in strategy mode
3. **Plan from strategy** — TASKS.md entries with `[objective:slug]`
4. Execute with full god mode pipeline
5. **Measure** — post-check: did changes achieve the objective?

**Session summary includes per-specialist breakdown:**

```
GOD MODE: @designer [N] finds ([R] 🔴 [Y] 🟡 [G] 🟢)
          @security [N] finds ([R] 🔴 [Y] 🟡 [G] 🟢)
          @architect [N] finds ([R] 🔴 [Y] 🟡 [G] 🟢)
          @strategist [N] finds ([R] 🔴 [Y] 🟡 [G] 🟢)
          @reviewer [N] finds ([R] 🔴 [Y] 🟡 [G] 🟢)
```

`"GOD MODE COMPLETE. [N] done, [C] concerns, [B] blocked. [T] specialist findings. [F] auto-fixed. PR: [URL]"`

**What stays safe:** spiral detection, self-fix caps, feature-branch-only push, circuit breaker. Every decision logged. The safety valves that prevent damage are always on — only the ASK interruption is removed.

**Why this mode exists:** A solo founder can't afford a designer, security engineer, architect, product strategist, and senior reviewer. God mode gives them all six, reviewing every line. The team you couldn't hire, reviewing everything you ship. Zero stops.

Previously split across `full`, `dreamteam`, and `ceo`. Now unified as one peak: god mode.

---

## What's always on vs. gated

| Component                                        | When active                                                 | Behavioral impact                                                                                                                                                                             |
| ------------------------------------------------ | ----------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| CLAUDE.md trigger                                | Every session                                               | None — just routes "start acepilot" to /acepilot                                                                                                                                              |
| Compaction instructions in CLAUDE.md             | Every compaction                                            | Preserves state files — no thinking change                                                                                                                                                    |
| Permission allow/deny lists                      | Every session                                               | No behavioral change — just removes prompts                                                                                                                                                   |
| Status line                                      | Every session                                               | Shows `AcePilot: [mode] [done/total]` in UI when active (progress since v40)                                                                                                                  |
| SessionStart hook                                | Every session                                               | Injects git context + stale MODE warning if present                                                                                                                                           |
| PostToolUse auto-format                          | Every file edit                                             | Mechanical formatting, no thinking change                                                                                                                                                     |
| PreToolUse safety blocker                        | Every bash command                                          | Blocks dangerous commands silently                                                                                                                                                            |
| PreCompact backup                                | Every compaction                                            | Copies state files to backups                                                                                                                                                                 |
| Browser rules                                    | Only when editing UI files                                  | Chrome MCP verification                                                                                                                                                                       |
| @reviewer / @researcher                          | Only when explicitly called                                 | Sit dormant until invoked                                                                                                                                                                     |
| @designer / @security / @architect / @strategist | Only when explicitly called, or auto-triggered by diff type | Sit dormant until invoked by specialist review step or dreamteam mode                                                                                                                         |
| **All behavioral rules**                         | **Only after /acepilot**                                    | **Confidence gates, absorb, execution loop, context discipline, self-validation, safety preferences, compounding, state system, micro-log, git checkpoints, token discipline, report format** |

---

## How context is protected

This is the core design problem AcePilot solves. The context window is 200K tokens per session. A big project CLAUDE.md might eat 3-5K tokens. Every file Claude reads costs tokens. Every tool output costs tokens. You can fill 200K in 30 minutes of intensive work.

AcePilot protects context through:

1. **Subagent delegation:** @researcher reads files in its own 200K window, returns a 500-word summary. Your main context only pays for the summary, not the 20 files it read. @reviewer works the same way for code review.
2. **Proactive compaction:** Every 5 completed tasks, Claude writes state to disk and runs `/compact`. The compaction instructions in CLAUDE.md ensure TASKS.md is preserved verbatim, DECISIONS.md and KNOWLEDGE.md are never summarized, and only CONTEXT.md gets condensed. The PreCompact hook backs up everything before compaction starts.
3. **/btw for side questions:** Answers are discarded from history.
4. **! prefix for raw bash:** Skips Claude's narration of the command.
5. **view with line ranges:** Read lines 40-60 instead of cat-ing 500 lines.
6. **No narration rule:** Claude doesn't explain what it's about to do. The micro-log replaces narration with structured one-line entries — ~20-30 tokens each vs ~150-300 for narration. Over a 15-task session, this saves ~2-4K tokens.
7. **No recap rule:** Claude doesn't repeat your instructions back.
8. **Task counter:** `[7/15]` on every line means status checks don't require scanning the full log.

---

## How the state system works

Previous versions (v19 and earlier) used a single STATE.md file for everything — task list, session context, and decision log. This caused three problems: compaction degraded it unpredictably, resume quality decayed over sessions, and there was no way to preserve decisions separately from disposable context.

v20 split state into three files with distinct persistence rules. v24 added a fourth:

**TASKS.md** — the checklist. Machine-parseable. Never edited by compaction. Backed up before every compaction by the PreCompact hook. Format is strict: `[status] priority verb description — file_hint [id/needs tags]`. This format survives any level of context degradation because it's a checklist, not prose.

**DECISIONS.md** — the decision log. Append-only. Never edited. Never compacted. Records CHOSE/WHY/FILES for every non-obvious decision. When resuming, Claude reads this to understand reasoning, not just outcomes. The @reviewer agent reads it to understand intent when reviewing code. This file only grows; it never shrinks.

**CONTEXT.md** — the session scratchpad. Free-form. The only state file that may be condensed during compaction. Holds working notes, current thinking, temporary context. If compaction degrades it, the other files still provide everything resume needs.

**MODE** — (new in v33) a single-line file containing the active AcePilot mode (e.g. `full`). Written on activation, deleted on completion. Read by the status line (shows AcePilot state in the UI) and by resume (to inherit the previous mode). Dedicated file — can't be corrupted by compaction, survives crashes.

**KNOWLEDGE.md** — the product knowledge layer (new in v24). Never compacted. Never summarized. Captures product-level understanding: what it does, who it's for, constraints, abandoned approaches, priorities, non-obvious facts, and revenue paths (new in v42). Unlike DECISIONS.md (which records individual choices), KNOWLEDGE.md captures understanding that spans sessions and informs future decisions. Revenue paths make Impact scoring accurate: once you list your checkout/auth/billing directories, every future scan automatically elevates tasks in those paths to Impact=5. In v7.0, entries get freshness markers (`<!-- verified: YYYY-MM-DD -->`); entries >30 days unverified are flagged during absorb for re-check.

**PATTERNS.md** — execution learning (v7.0+). Never compacted. Append-only. Tracks failure modes with recovery strategies and known false positives. Updated by the compound rule after each session. Specialists read it before reviewing to avoid flagging known false positives. The circuit breaker consults it for recovery strategies before escalating to BLOCKED.

**ANALYTICS.md** — execution data (new in v8.0). Never compacted. The feedback engine. Four sections: Gate Log (every gate decision with predicted vs actual outcome), Specialist Log (every call with actionable finding count), Cycle Times (per-task timing with estimates vs actuals), Session Rollups (cross-session trends). Drives gate calibration, specialist routing optimization, and cycle time estimation. Archive at 200 entries per section.

The compaction instruction in CLAUDE.md says: "Preserve TASKS.md verbatim, never summarize DECISIONS.md, KNOWLEDGE.md, USER-KNOWLEDGE.md, PATTERNS.md, or ANALYTICS.md, only CONTEXT.md may be condensed." Compaction is hardened: an explicit verification checklist runs after compaction to confirm all state files are in context, auto-re-reading any that are missing.

---

## How confidence gating works

Every task is classified before execution. This prevents the main failure mode of autonomous execution: Claude making a wrong architectural call on task 5 that cascades through tasks 6-15.

**AUTO** — clear fix, existing pattern, ≤2 files. Example: fixing a typo in an error message. Execute immediately, no announcement.

**PLAN** — known approach, 3-5 files, or introduces a new pattern. Example: adding error handling to an API endpoint following the existing error handling pattern but across multiple files. Claude shows a 3-line plan and proceeds unless you press Esc. This keeps `go` mode flowing while giving you a window to intervene.

**ASK** — multiple valid approaches, >5 files, or changes a public interface. Example: restructuring the authentication system. Claude stops, presents the options with tradeoffs, and waits for your decision.

Hard rules prevent dangerous AUTO classifications: any public interface change, any deletion of functionality, any new dependency, or any uncertainty about the existing pattern forces PLAN or ASK.

---

## How v49 Decision Intelligence works

v49 adds 7 capabilities derived from research on the world's top builders. Each was critically filtered: only mechanisms that genuinely improve autonomous execution quality made the cut.

**The execution pipeline is now:** `TASK → Orient → Scope → Gate → Algorithm → Do → Verify → Specialist Review → Qualify → Log → Checkpoint`

v7.0 adds a 4-tier ceremony system: **Micro** (<30s, 1 file ≤5 lines — skip orient + algorithm), **Quick** (<60s, 1-2 files — compressed orient), **Standard** (3-5 files — full pipeline with smart-dispatched specialists), **Complex** (6+ files — break into subtasks). Smart dispatch routes specialists by diff content in all modes including god — a CSS fix gets @reviewer + @designer, not all 5.

1. **Orient** (Boyd OODA) — Before each task, AcePilot reads the code, restates the requirement, and identifies the simplest change. Wrong understanding makes every downstream step wrong. Per-session orientation is written to CONTEXT.md.

2. **Reversibility-based confidence gate** (Bezos Type 1/2) — v49 classifies by reversibility, not file count. Two-way doors (reversible with `git revert`) get fast execution. One-way doors (public API changes, data deletion) get careful deliberation. Quick test: "Can I undo this in 60 seconds?"

3. **The Algorithm** (Musk) — Before writing code: Question requirements → Delete unnecessary work → Simplify → Execute → Optimize. Order is load-bearing. The most common waste is optimizing something that shouldn't exist.

4. **Staged verification** (Beck) — Work → Right → Fast. First: does it produce correct behavior? Then: is the code clean? Then: is it fast enough? Most tools try all three simultaneously and achieve none.

5. **Task re-evaluation** (Hastings keeper test) — Every 5 tasks, AcePilot re-evaluates: would we still do this? Completed work changes the landscape. Stale tasks get `[⊘] SKIPPED` status.

6. **Working Backwards** (Bezos PR/FAQ) — CEO mode now writes the desired PR description first, then derives tasks. Forces clarity on what "done" looks like before writing a single line of code.

7. **Cycle time tracking** — Session summary reports avg/fastest/slowest time per task. This is the north star metric for execution quality.

For the full research rationale (which principles were kept, rejected, and why), see `KNOWLEDGE.md § v49 Design Principles`.

---

## How priority scoring works

Every task discovered during a scan gets a numeric **APS (AcePilot Priority Score)** computed by @researcher before writing to TASKS.md. Tasks execute in APS order — highest score first — rather than a flat P0-P3 category sort.

**Formula:**

```
APS = (Impact + Urgency + Unblock) × Confidence / Effort
```

| Dimension      | Values      | What it captures                                                                                |
| -------------- | ----------- | ----------------------------------------------------------------------------------------------- |
| **Impact**     | 1–5         | User/business value: 5=core flow broken, 4=major degradation, 3=noticeable, 2=minor, 1=cosmetic |
| **Urgency**    | 1–5         | Time sensitivity: P0/security=5, P1=3, P2=2, P3=1                                               |
| **Unblock**    | 0–3         | +1 per dependent task this enables (cap 3) — rewards key unlocks                                |
| **Confidence** | 0.5/0.8/1.0 | Certainty: exploratory / known approach / exact fix known                                       |
| **Effort**     | 1/2/3       | Scope: ≤2 files / 3–5 files / 6+ files                                                          |

**Derivation:** Synthesized from WSJF (Cost of Delay / Duration) and ICE (Impact × Confidence × Ease). The numerator `(Impact + Urgency + Unblock)` models WSJF's Cost of Delay — a sum of business value, time criticality, and opportunity enablement. Dividing by Effort (WSJF's Job Duration) means short high-value tasks always win over long low-value ones.

**P0 override:** P0 tasks (build broken, security) always execute first regardless of score. APS ordering applies within P0-cleared sessions.

**Revenue path awareness:** Impact scoring is grounded in explicit business context, not code patterns alone. Seed `KNOWLEDGE.md` with a `## Revenue paths` section listing your checkout, auth, onboarding, and billing directories. Once set, @researcher automatically assigns Impact=5 to any task whose file path matches — because a bug in the payment flow costs money every minute it's live, while the same severity bug in an internal admin tool does not.

**Example scores:**

```
P0 build error, 1 file, exact fix, unblocks 2:    (5+5+2)×1.0/1 = 12.0  ← execute first
P1 auth bug, 2 files, known approach:              (4+3+0)×0.8/2 =  2.8
P2 CSV export feature, 2 files, known:            (3+2+0)×0.8/2 =  2.0
P3 refactor, 3+ files, exploratory:               (2+1+0)×0.5/3 =  0.5  ← execute last
```

A quick P3 improvement that unblocks 3 tasks (`[score:2.0]`) will execute before a slow uncertain P1 fix (`[score:1.6]`) — because removing a blocker has higher realized value than grinding on a risky task. This is the key insight from WSJF that flat priority labels miss.

---

## How KNOWLEDGE.md grows automatically

KNOWLEDGE.md starts as a snapshot of your project (seeded on first run by @researcher). In v46 it becomes a living document that compounds across sessions:

**From scan** — every time @researcher scans the project, it includes a `## Knowledge updates` section with up to 3 non-obvious facts it discovered that aren't in KNOWLEDGE.md yet (API quirks, vendor requirements, architectural gotchas). In `auto`/`full` modes these are silently appended; in `go`/`plan` modes you're shown them first.

**From blocked tasks** — when a task is BLOCKED with root cause `intent issue` (AcePilot misunderstood the domain), the corrected understanding is appended to KNOWLEDGE.md. This prevents the same misunderstanding from recurring in future sessions.

**From implementation** — any time a non-obvious product fact surfaces during a task (e.g. "this endpoint requires idempotency keys", "this migration must run before deploy"), it's appended via the COMPOUND rule.

Every entry is stamped with the date and source (`<!-- added YYYY-MM-DD, source: scan|task|blocked -->`). The session summary reports `KNOWLEDGE: [N] new entries added`.

KNOWLEDGE.md is never compacted and never summarized — it only grows. The result: each session starts with more accurate context than the last.

---

## How pre-flight works

After absorb and before any scan or execution, AcePilot runs two safety checks:

**Branch guard** — AcePilot never commits to `main` or `master`. If you start an execution mode on either:

- `go` / `plan` → hard stop with a message: create a branch first.
- `auto` / `auto push` / `full` → auto-creates `acepilot/YYYYMMDD` (or `acepilot/YYYYMMDD-focus-slug` if focus is set). This is logged to DECISIONS.md. No user input required.
- `ship` / `status` / `review` / `resume` → warn or skip (these modes don't create commits).

**Dirty tree** — If you have uncommitted changes when starting a session, AcePilot stashes them first: `git stash push -m "acepilot: pre-session YYYYMMDD-HHMMSS"`. The stash ref is logged to DECISIONS.md. At the end of the session, `git stash pop` restores your work automatically. If the pop has conflicts, you're notified with the stash ref so you can resolve manually.

`plan` mode skips the stash (it never commits, so a dirty tree is harmless).

---

## How git checkpoints work

After each verified task, Claude commits with a machine-parseable message:

```
acepilot: P1-fix auth middleware error handling
acepilot: P2-feat checkout flow error states
```

This creates a clean per-task commit history. Benefits:

- **Review:** Each task is an isolated commit you can review independently
- **Cherry-pick:** Move individual task results between branches
- **Rollback:** `git reset --soft HEAD~N` undoes the last N tasks while preserving changes as staged
- **Reviewer scoping:** The @reviewer agent diffs against the last acepilot checkpoint to review only the latest changes

If verification fails, Claude stashes the changes (preserving them for later recovery), logs the failure in DECISIONS.md with the stash reference, marks the task as Blocked in TASKS.md, and moves to the next task. No broken code gets committed.

---

## How compounding works

Every session should leave the project better configured for the next one. When Claude makes a mistake or discovers a non-obvious fix, it suggests adding a permanent rule:

- **CLAUDE.md:** Universal rules — naming conventions, architecture patterns, forbidden patterns
- **.claude/rules/:** Path-scoped rules — API patterns only when editing API files, browser verification only for UI files
- **Hooks:** Things that must happen 100% of the time — formatting, security checks, backups
- **Skills:** Domain knowledge loaded on demand — deployment procedures, review checklists
- **KNOWLEDGE.md:** Product-level discoveries — what the product does, who it's for, things that aren't in the code but affect decisions (new in v24)

Over time, Claude makes fewer mistakes because the rules accumulate. Each session builds on the knowledge captured from all previous sessions.

---

## Session summary (new in v29)

When all tasks complete, the operator stops execution, or auto mode finishes, AcePilot outputs a structured summary:

```
SESSION: 12/15 tasks, 2 with concerns, 1 blocked
DECISIONS: 8 logged to DECISIONS.md
TESTS: 45/47 passed, 3 new tests created
QUALIFY: 12/2/1 (done/concerns/blocked)
TIME: 14:22→15:41
```

This gives the operator a clear picture of what happened without scrolling through micro-logs. The summary is saved to CONTEXT.md so that `/acepilot resume` in the next session has full context of what was accomplished.

---

## What it costs

Nothing extra beyond your existing Claude Code subscription. The subagents (@researcher on Haiku, @reviewer on Sonnet) are cheaper models that consume tokens from your existing budget. A typical `/acepilot go` session with initial research scan costs roughly what 2-3 normal Claude Code exchanges would cost. The permission auto-approvals actually save time (and therefore tokens) by eliminating the pause-approve-continue cycle.

---

## Version history

| Version | Key changes                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| ------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| v19     | Original 7-file system. Single STATE.md. Granular permission lists. Three modes (wait/go/resume).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| v20     | Split STATE.md into TASKS.md + DECISIONS.md + CONTEXT.md. Added confidence gating (AUTO/PLAN/ASK). Structured micro-log. Git checkpoints. Token budget heuristics. Task dependencies. Resume validation. Diff-scoped reviewer.                                                                                                                                                                                                                                                                                                                                                                           |
| v21     | Fixed PLAN confidence gate — replaced impossible "wait 30s" with "proceed unless Esc."                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   |
| v22     | Added `/acepilot plan` mode (scan + show, don't execute). Added `/acepilot status` mode (progress check). Task counter `[N/total]` on all output. Proactive compaction every 5 tasks. Moved compaction instructions to CLAUDE.md (survives compaction). Simplified permissions from granular bash patterns to `Bash(*)`. Tighter command (129 lines down from 138).                                                                                                                                                                                                                                      |
| v23     | Added `mcp__*` to permissions (fixes Chrome MCP "Allow once" prompts). Added `TodoRead`/`TodoWrite`. Tightened rm -rf deny patterns. Status mode skips absorb (instant). Fixed reviewer fallback diff (`HEAD~1` instead of `HEAD`). Added `START AUTOPILOT STATUS` routing.                                                                                                                                                                                                                                                                                                                              |
| v24     | Added KNOWLEDGE.md (product knowledge layer, never compacted). Periodic quality gates in go mode (@reviewer every 3 tasks). Enhanced plan mode (file scope, risk clusters, S/M/L sizing). Extended compound rule (product facts → KNOWLEDGE.md). Updated compaction + backup to cover KNOWLEDGE.md.                                                                                                                                                                                                                                                                                                      |
| v25     | Hardening release. Researcher output capped at 20 structured lines (was "under 500 words"). KNOWLEDGE.md auto-seeded from README on first run. Absorb discovers .claude/rules/. Compound rule closes reviewer→rules feedback loop. Resume detects KNOWLEDGE.md drift. Deny list adds `git checkout -- .` and `git clean -fd*`.                                                                                                                                                                                                                                                                           |
| v26     | Precision release. 2-strike escalation (was 3). Spiral detection (same file ×3 → stop). P0 tasks always reviewed. Resume skips file scan. Deny list adds `git reset --hard`. PreToolUse hook synced with full deny list. Browser rules use tool-agnostic language.                                                                                                                                                                                                                                                                                                                                       |
| v27     | Workflow completion. Added `/acepilot review` (full on-demand review, all severities). Added `/acepilot ship` (full verification + review + squash commits + PR description). Lifecycle is now complete: plan → go → review → ship.                                                                                                                                                                                                                                                                                                                                                                      |
| v28     | God mode. Added `/acepilot auto` — full autonomous lifecycle (go + self-fix reviews + ship) in one command. Auto-fixes 🔴 findings, retries blocked tasks with alternatives, auto-ships. Stops only before push.                                                                                                                                                                                                                                                                                                                                                                                         |
| v29     | Awareness. Test-aware verification (check if tests exist for modified code, flag or auto-create). Session summary on completion (tasks/files/decisions/tests/time). God mode auto-creates missing tests.                                                                                                                                                                                                                                                                                                                                                                                                 |
| v30     | Quality audit. Fixed ship/god squash method (`git reset --soft` instead of impossible `git rebase -i`). PreToolUse grep now covers all 18 deny patterns. Review mode scoped to recent commits.                                                                                                                                                                                                                                                                                                                                                                                                           |
| v31     | Polish. Go mode explicitly stops when all tasks done. Plan mode mentions god as execution option. Consistent "confidence-gate" wording.                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |
| v32     | God + Push. Added `/acepilot auto push` (auto-push + PR) and `/acepilot full` (fully autonomous, ASK gate removed). Feature branch safety. Ten modes total.                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| v33     | Resilience. Dedicated MODE file for mode persistence + status line. Resume inherits previous mode. Settings.json gains statusLine (shows AcePilot state in UI). PreCompact backs up MODE file. Compact MODE section (-16% chars).                                                                                                                                                                                                                                                                                                                                                                        |
| v34     | Robustness. Status line drops jq dependency (pure bash/sed). Absorb reads MODE file (step 4c). SessionStart hook warns about stale MODE from interrupted sessions.                                                                                                                                                                                                                                                                                                                                                                                                                                       |
| v35     | Focus. Execution modes accept a focus directive (free text after mode name). ★ tasks execute first, get +1 confidence autonomy. Focus persists in MODE file and survives resume.                                                                                                                                                                                                                                                                                                                                                                                                                         |
| v36     | Naming. `god`→`auto`, `god push`→`auto push`, `god push dont ask`→`full`. Cleaner descriptions. Old names kept as aliases.                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| v37     | Research-driven overhaul. Qualify step (4-status), circuit breaker (3-state), budget limits, scope-adaptive ceremony, conservative interpretation, post-compaction hook, dual-condition exit, attempt tracking, root cause classification. 12 improvements from 32 research findings.                                                                                                                                                                                                                                                                                                                    |
| v38     | Agents + Parallel. Reviewer gains qualify mode (independent spec verification). Researcher gains 3 modes (scan/explore/knowledge). Parallel execution via worktree-isolated subagents (up to 3x on independent tasks).                                                                                                                                                                                                                                                                                                                                                                                   |
| v39     | Distribution. Install script (install, upgrade, check, uninstall in one command). Researcher gains git read access (log, status, branch). README gains quickstart section.                                                                                                                                                                                                                                                                                                                                                                                                                               |
| v40     | Polyglot. Absorb auto-detects project config (package.json, pyproject.toml, Cargo.toml, go.mod, Makefile). Formatter hook supports Python (ruff/black), Rust (rustfmt), Go (gofmt), plus prettier. File scan covers 16 extensions. Status line shows progress counter.                                                                                                                                                                                                                                                                                                                                   |
| v41     | Priority scoring. APS (AcePilot Priority Score) — numeric score on every task at scan time. Formula: (Impact + Urgency + Unblock) × Confidence / Effort. Synthesized from WSJF + ICE. Tasks sort by score, not flat P0-P3 category.                                                                                                                                                                                                                                                                                                                                                                      |
| v42     | Revenue awareness. Revenue paths section in KNOWLEDGE.md and KNOWLEDGE-TEMPLATE.md. Researcher assigns Impact=5 automatically to tasks touching revenue path directories. Knowledge mode extracts revenue paths when seeding KNOWLEDGE.md. Impact scoring grounded in business context, not guessed from code alone.                                                                                                                                                                                                                                                                                     |
| v43     | Path safety. All shell constructs and install instructions quote paths. Works on iCloud Drive, OneDrive, Dropbox, and any directory whose path contains spaces.                                                                                                                                                                                                                                                                                                                                                                                                                                          |
| v44     | Pre-flight. Branch guard (stops on main/master or auto-creates feature branch). Dirty tree stash (labeled, auto-restored at session end). Never starts a session from a dangerous baseline.                                                                                                                                                                                                                                                                                                                                                                                                              |
| v45     | Correctness. 30 bugs fixed: grep BRE alternation on macOS (status line now accurate), circuit breaker persists to CIRCUIT state file (survives compaction), stash ref tracked in STASH_REF (guaranteed restoration), STATE.md removed from PreCompact, revenue path prefix-match defined, researcher gains git diff, ★ focus stickiness resolved, parallel merge algorithm defined, PR description algorithm specified, dependency-wins rule, 15+ definition gaps filled.                                                                                                                                |
| v46     | Knowledge loop. KNOWLEDGE.md grows automatically every session: researcher appends discoveries from scan, blocked intent-issue resolutions captured, COMPOUND rule now auto-appends in auto/full modes. Each entry date-stamped with source. Session summary reports new entries.                                                                                                                                                                                                                                                                                                                        |
| v47     | User model. USER-KNOWLEDGE.md tracks preferences, patterns, corrections, communication style, and risk tolerance per user. Read at absorb alongside KNOWLEDGE.md. Written by compound rule on every correction or confirmed preference. Never compacted. The moat: each session Ace knows more about how you specifically think.                                                                                                                                                                                                                                                                         |
| v48     | Identity. Product renamed to Ace throughout: title, prose, APS acronym, installer labels, ready message, section headers. "START ACEPILOT" is now the primary trigger; "START AUTOPILOT" kept as backward-compat alias. Command interface (/acepilot, autopilot: prefix, file names) unchanged.                                                                                                                                                                                                                                                                                                          |
| v49     | Decision Intelligence. 7 improvements from 50+ research findings on top builders (Bezos, Musk, Boyd, Beck, Hastings, Torvalds, Carmack). ORIENT phase (Boyd OODA). Reversibility-based confidence gate (Bezos Type 1/2). The Algorithm (Musk: Question→Delete→Simplify→Execute→Optimize). Staged verify (Beck Work→Right→Fast). Task re-evaluation every 5 tasks (Hastings keeper test). Working Backwards for CEO mode (Bezos PR/FAQ). Cycle time tracking. SKIPPED task status. Directory renamed to `acepilot-5.0/`.                                                                                  |
| 6.0     | Dream Team Edition. 4 new specialist agents: @designer (UX/a11y/copy), @security (OWASP/secrets/auth), @architect (coupling/performance/devops), @strategist (analytics/testing/docs/growth). Total: 6 agents. Auto-routing heuristic. Specialist Review step. Grounded in research across 40+ practitioners. Directory renamed to `acepilot-6.0/`.                                                                                                                                                                                                                                                      |
| 6.1     | Mode Redesign. 14 modes consolidated to 8. Clear trust ladder: plan → go → auto → ship → god. God mode = the peak (all 6 specialists, zero stops, objective-driven). Ship = auto + push PR. Old names (full, dreamteam, ceo, auto push) preserved as backward-compat aliases. Mid-task re-orient on block. Version display in ready message and status. Research: 10+ competitors analyzed (Cursor, Devin, Cline, Aider, OpenHands, Codex CLI, Windsurf, Continue.dev).                                                                                                                                  |
| 6.2     | Objective Auto. `/acepilot auto` gains objective support — same Working Backwards + @researcher strategy pattern as god mode, now available at a lower trust level. Consistent objective-driven execution across plan, auto, and god modes.                                                                                                                                                                                                                                                                                                                                                              |
| 6.3     | Anti-Mediocrity. @designer gains anti-cliche filter for public-facing pages (flags generic SaaS patterns, AI-tool clichés, weak copy, missing conversion signals). COMPOUND section gains permanent anti-mediocrity rules for landing page work. Grounded in conversion research from Stripe, Linear, Cursor, CXL, Baymard, Copyhackers.                                                                                                                                                                                                                                                                 |
| 6.4     | Token Diet. Every prompt line audited for ROI. 1039 → 627 lines across 7 files (−40%). All agent prompts compressed: verbose domain knowledge replaced with concise references (models already know these). acepilot.md modes compressed to table format. Zero checks removed — same execution quality, fewer tokens. God mode per-task cost: ~500 → ~200 Sonnet tokens.                                                                                                                                                                                                                                 |
| 7.0     | Adaptive Intelligence. Smart specialist dispatch (route by diff content, not blindly all 5 — even in god mode). 4-tier ceremony: micro (<30s), quick (<60s), standard, complex. PATTERNS.md state file for execution learning across sessions (specialist ROI, failure recovery, false positives). Task deduplication via TASKS.md + git log cross-reference. Hardened compaction with post-verification checklist. Session metrics (specialist calls, PASS rate, findings). Knowledge freshness markers (>30d entries flagged). `god --full-team` preserves v6.4 behavior. Directory: `acepilot-10.0/`. |

---

## License

MIT
