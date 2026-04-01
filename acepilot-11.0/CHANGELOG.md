# AcePilot — Changelog

## Versioning reset

Starting with this release, AcePilot uses public versioning: **Major.Minor** (e.g. 5.0, 5.01). Internal iteration count (v49) is retired. The product has matured past internal build numbers.

- `v49.1` (internal) = `5.0` (public) — first public release
- Minor bumps (5.01, 5.02...) for fixes and refinements
- Major bumps (6.0, 7.0...) for architectural changes
- Directory: `acepilot-11.0/` (current)

## 11.0 — Growth Engine

Every product needs users. AcePilot 11.0 auto-generates acquisition infrastructure alongside the product — not as an afterthought, but as part of the first build. Say "website" and get SEO, comparison pages, viral loops, blog scaffold, analytics, email capture, and launch assets. Research-backed: modeled on how Stripe, Vercel, Supabase, Linear, and Cursor grew from 0 to escape velocity.

### What changed:

- **Growth Engine** — new system that runs after Directive Expansion. For every product build, auto-generates: SEO infrastructure (robots.txt, sitemap, JSON-LD, canonicals), comparison pages (top 3 competitors), programmatic SEO (/use-cases/ template pages), blog scaffold with seed posts, badge/embed system with ref tracking, share mechanics, email capture with value exchange, and analytics on all pages.
- **Launch Sequence** — automated launch preparation via `god --launch`. Pre-launch checklist (10 items), then generates Show HN post, Twitter/X threads, Reddit post, demo assets. Stores in LAUNCH-ASSETS.md. Post-launch tracking in CONTEXT.md handoff.
- **Content Engine** — auto-generates SEO-optimized blog post tasks: origin story, tutorial, comparison, deep dive, changelog. Content calendar for creation directives.
- **Conversion Optimization Framework** — applied to every generated landing page. Above-fold rules (H1 + sub + CTA + social proof + install snippet), smart CTAs (scroll-triggered sticky bar, exit-intent modal, time-delayed nav highlight), pricing page optimization (anchor, friction reducers, FAQ).
- **Viral Loop Mechanics** — badge system (4 variants, ref tracking, copy snippets), referral mechanics (share links with ref codes, reward logic), open source social proof (star counter, contributors section).
- **GROWTH.md (14th state file)** — acquisition-specific metrics: channels (visitors, signups, conversion%, CAC), funnel (visit→signup→activate→paid), content performance, viral metrics (K-factor), launch log. Read during ABSORB to inform channel prioritization.
- **ABSORB expanded** — step 8 now reads GROWTH.md alongside METRICS.md. Step 11 runs Growth Engine after directive expansion.
- **`god --launch` flag** — triggers Launch Sequence after task completion.

### What was NOT changed:

- All v10.0 features (Playbooks, Self-Calibration, Session Chains, Deploy Pipeline) — still active
- All v9.0 features (Directive Expansion Engine, IDENTITY.md, Business Decision Framework) — still active
- All v8.0 features (four feedback loops, data-driven dispatch) — still active

---

## 10.0 — Self-Evolving Autonomy

Ship today. Ship faster tomorrow. AcePilot now captures what works, replays proven workflows, and auto-tunes its own execution engine from data. Session 1 figures it out. Session 10 runs it from memory.

### What changed:

- **Playbook System** (PLAYBOOKS.md, 13th state file) — after a successful creation session, AcePilot captures the task sequence, decisions, and specialist routing as a replayable playbook. Next time a matching directive arrives, the playbook replays instead of re-deriving everything. Proven workflows get faster every time.
- **Self-Calibration Engine** — every 10 sessions, AcePilot reads its own ANALYTICS.md and adjusts: gate thresholds shift based on accuracy, specialist routing weights update based on precision trends, ceremony tiers recalibrate based on cycle time drift. Not just logging data — changing behavior from data.
- **Session Chains** — objectives persist across sessions via handoff state in CONTEXT.md. Next session resumes with momentum instead of re-scanning. 5-session chain limit forces re-evaluation. Chains are what separate a tool from a teammate.
- **Deploy Pipeline** — auto-detects platform (Vercel, Netlify, Docker, GitHub Pages, FTP/file manager), builds, deploys, and runs the full VERIFY DEPLOY checklist. The last mile, automated. Opt-out with `[no-deploy]`.
- **@researcher PLAYBOOK MODE** — new mode for capturing and analyzing execution workflows during playbook creation.
- **@strategist gains chain + deploy lenses** — reviews session chain continuity and deployment verification alongside existing analytics and growth analysis.
- **ANALYTICS.md expanded** — two new sections: Calibration Log (self-calibration cycle results) and Chain Log (multi-session objective tracking).
- **ABSORB expanded** — three new steps: session chain pickup (step 9), playbook match (step 10), deploy detection (step 12). Smarter startup.

### What was NOT changed:

- All v9.0 features (Directive Expansion Engine, IDENTITY.md, Business Decision Framework) — still active
- Four feedback loops (gate calibration, specialist precision, cycle time, error recovery) — still active
- Revenue-first Algorithm step — still active
- Never-compact policy on all append-only files
- Two-attempts-max circuit breaker
- Flat file state system

## 9.0 — Autonomous Business Intelligence

Say "website" and AcePilot decides everything — tech stack, design, copy, pricing, conversion strategy, analytics, deployment — then ships it. The first autonomous business operator for Claude Code. Every decision optimizes for revenue.

### What changed:

- **Directive Expansion Engine** — minimal directives (1-5 words like "website", "SaaS", "landing page") auto-expand into full business specifications. AcePilot classifies the product archetype, decides revenue model, selects tech stack, determines design aesthetic, writes conversion-optimized copy, sets up analytics, and creates tasks — all without asking.
- **Business Decision Framework** — codified pricing psychology (3-tier charm pricing, annual discount anchoring, "Most Popular" badge), conversion defaults (CTA placement, copy patterns, social proof positioning), copy rules (outcome-first, ≤10 word headlines), analytics defaults, and SEO configuration. Every default backed by research.
- **IDENTITY.md** (12th state file) — persistent project identity that survives across sessions. Stores brand archetype, visual identity (colors, typography, spacing), revenue model, target audience, conversion strategy. Auto-generated by Directive Expansion Engine or manually seeded from IDENTITY-TEMPLATE.md. Never compacted.
- **OPERATOR shift** — "Interpret expansively for creation, conservatively for modification." Creation directives ("website") trigger full autonomous specification. Modification directives ("fix", "clean up") scope to specific files. Creation directives never ask — they decide and ship.
- **Revenue-first Algorithm** — step 2 added: "Does this decision maximize revenue?" Between two equivalent approaches, AcePilot picks the one that generates or captures more value.
- **@strategist business intelligence lens** — now audits pricing structure, conversion patterns, CTA placement, copy quality, and revenue alignment alongside analytics and testing.
- **@researcher BUSINESS MODE** — new mode for directive expansion support. Generates full business specification from minimal directives using project context.
- **Identity cascade** — decision priority: IDENTITY.md (explicit) → USER-KNOWLEDGE.md (operator patterns) → archetype defaults (framework).

### What was NOT changed:

- All v8.1 features (METRICS.md, deploy verification, asset integrity, metrics-weighted gates) — still active
- Four feedback loops (gate calibration, specialist precision, cycle time, error recovery) — still active
- Never-compact policy on all append-only files
- Two-attempts-max circuit breaker
- Flat file state system

## 8.1 — Metrics-Aware Intelligence

AcePilot now knows which code paths matter most. Paste your analytics numbers into METRICS.md — pageviews, transactions, error rates — and task prioritization, confidence gates, and specialist routing all adjust to real impact. A checkout bug with 500 daily transactions gets P0. An admin page with 5 users gets skipped. Broken images never ship again.

### What changed:

- **METRICS.md** (optional state file) — product metrics snapshot from your analytics dashboard. Routes mapped to pageviews, transactions, error rates, conversion rates, Core Web Vitals. User refreshes it before each session (2-3 min from dashboard). AcePilot reads it during ABSORB and uses it everywhere: Impact scoring, confidence gates, specialist dispatch, Orient context. Fully backward compatible — if absent, heuristics used.
- **Metrics-weighted confidence gates** — reversibility alone doesn't determine risk. A reversible CSS fix on a high-traffic checkout page is riskier than an irreversible change to dead code. New gate matrix weighs reversibility × route traffic. >10k DAU or revenue-critical routes get PLAN or ASK even for reversible changes.
- **Orient gains business context** — fourth question added: "What's the user/business impact?" Checked against METRICS.md before deciding approach. A bug on a 500-transaction/day checkout gets different scrutiny than a bug on a 5-user admin page.
- **Impact scoring calibrated by data** — @researcher now uses METRICS.md for Impact 1-5 scoring. Impact 5 = critical path (signup/checkout) OR >1% error rate OR >50% funnel drop-off. No more guessing whether a fix matters.
- **VERIFY DEPLOY protocol** — new section in the execution pipeline. After any deployment: fetch live page, verify all assets load (images, CSS, JS), check for mixed content, test critical paths, check mobile viewport, verify zero console errors, test forms. Deploy is not done until verify-deploy passes. Common deployment mistakes auto-checked (wrong paths, missing files, hardcoded URLs).
- **@designer asset integrity check** — for every diff touching HTML/templates: verify all `<img>`, `<link>`, `<script>` paths exist. Check for broken relative paths, mixed content, localhost/staging URLs in production. Missing asset = 🔴.
- **Smart dispatch gains metrics step** — Step 0: check METRICS.md. <100 DAU route → skip all specialists except @reviewer. >10k DAU route → add @strategist even if heuristic wouldn't trigger. >1% error rate → full review.

### What was NOT changed:

- Four feedback loops from v8.0 (gate calibration, specialist precision, cycle time, error recovery) — all still active
- ANALYTICS.md structure — unchanged
- Never-compact policy on all append-only files
- Flat file state system — 11 files now (METRICS.md is optional)

## 8.0 — Data-Driven Intelligence

Session 10 is measurably faster and more accurate than session 1. AcePilot now tracks its own gate accuracy, specialist ROI, and cycle time — and adjusts automatically. Confidence gates self-calibrate from historical accuracy. Low-ROI specialist calls get cut. Cycle time estimates improve with every task. The agent gets better the more you use it.

### What changed:

- **ANALYTICS.md** — new state file (10th). Five structured sections: Gate Log, Specialist Log, Cycle Times, Recovery Log, Session Rollups. Never compacted. Archive at 200 entries per section to `.claude/state/ANALYTICS-archive-YYYY-MM.md`. This is the feedback engine — without it, AcePilot repeats mistakes; with it, every session makes the next one better.
- **Gate calibration** — every confidence gate decision recorded with predicted vs actual outcome. Calibration triggers every 10 sessions (session count tracked in Session Rollups). Requires ≥20 decisions per category before adjusting — no premature tightening on small samples. AUTO <95% → tighten; PLAN >90% for a category → loosen.
- **Specialist precision tracking** — two metrics: find-precision (actionable findings / total findings) and correct-PASS rate (PASS when nothing needed fixing). Prevents the bias where specialists who find real issues but get selectively applied trend toward demotion. Precision per specialist per content type drives routing: <50% → skip, ≥70% → always include. Data overrides the heuristic.
- **Data-informed smart dispatch** — Step 1: check ANALYTICS.md for historical precision on this content type. Step 2: fall back to diff-based heuristic only when no data exists. Proven specialists get routed more; low-ROI calls get cut.
- **Cycle time as north star** — every micro-log entry now includes cycle time. Per-tier averages tracked (micro target <30s, quick <60s). Estimates recalibrate after 5 tasks per tier. Session summary leads with avg cycle time, not task count.
- **ABSORB data insights** — new step 7 in absorb: analyze ANALYTICS.md Session Rollups for trends (gate accuracy, specialist precision, cycle time). Surface top 3 insights in CONTEXT.md. Orient is now data-informed.
- **Session metrics expanded** — now reports: cycle time per tier, gate accuracy %, specialist precision %, and data delta vs last session. God mode completion includes accuracy and precision stats.
- **Strategist data lens** — @strategist now checks: (1) Is there instrumentation to know if a feature works? (2) Is there a feedback loop? (3) Is there a kill metric? Missing all three after 5+ sessions = 🔴. Cold start (<5 sessions) = 🟡 "not yet instrumented."
- **Compound rule extended** — every gate decision, specialist call, and task completion auto-appends to ANALYTICS.md. Data compound is automatic, not optional.

### How data guides decisions (the four feedback loops):

1. **Gate calibration loop**: gate decision → predicted outcome → actual outcome → accuracy measured → thresholds tuned → better future gates
2. **Specialist routing loop**: specialist call → actionable findings measured → precision calculated → routing weight adjusted → smarter dispatch
3. **Cycle time loop**: estimate → actual time → delta measured → next estimate refined → more accurate planning
4. **Error recovery loop**: circuit break → failure reason → pattern stored → next time, proven recovery applied first

### What was NOT changed (load-bearing):

- Confidence gate structure (AUTO/PLAN/ASK) — calibration is new, structure is proven
- Never-compact policy on DECISIONS.md, KNOWLEDGE.md, USER-KNOWLEDGE.md, PATTERNS.md, ANALYTICS.md
- Two-attempts-max circuit breaker
- Flat file state system — 10 files now, still no database
- Append-only DECISIONS.md

## 7.3 — Adaptive Specialists

Every specialist now reads KNOWLEDGE.md to detect project type and adjusts what they look for. A landing page gets deep copy + conversion review. A CLI tool skips a11y checks and focuses on help text. An API gets deep auth + validation. No wasted findings.

- **Project archetypes** — `landing-page`, `web-app`, `api`, `cli`, `library`, `mobile`. Each shifts specialist priorities. Detected from KNOWLEDGE.md during review.
- **@designer copy depth** — deep copy lens activates when diff touches user-facing text: voice consistency, clarity for non-native speakers, scannability, emotional tone (does error copy blame the user?), specificity ("Your card was declined" not "Something went wrong").
- **Per-agent adaptation** — each agent has archetype-specific focus areas:
  - @researcher: scan priorities shift (landing-page → copy/SEO, api → endpoints/auth)
  - @reviewer: review focus shifts (web-app → state/errors, library → API compat)
  - @designer: skips entirely for pure API/CLI, deep copy for landing pages
  - @security: OWASP depth for web-apps, supply chain for libraries, form injection for landing pages
  - @architect: render perf for landing pages, query patterns for web-apps, startup time for CLIs
  - @strategist: conversion funnel for landing pages, DX for APIs, first-run for CLIs

No new agents. Same 6, smarter about context.

## 7.2 — Voice

ACE and all 6 specialists now have defined personalities — through perspective, not theater.

- **ACE core voice** — mission ops. Terse status reports. Confident, not cocky. Says less, does more. Never apologizes, never hedges. Status reads like ops comms, not conversation.
- **@researcher** — the scout. Curious, thorough, connects dots others miss.
- **@reviewer** — the quality gate. Precise, direct, no hand-wringing.
- **@designer** — the user advocate. Sees the product through the user's eyes. "A user would..."
- **@security** — the adversary. Paranoid by design. "An attacker with X could Y."
- **@architect** — the systems thinker. Sees blast radius, coupling, what breaks at 10x.
- **@strategist** — the outcome filter. Cuts what doesn't move the needle.

Personality is functional: each voice shapes what the specialist notices and how they frame findings. No names, no catchphrases, no theater.

## 7.1 — Continue Mode

The "keep going" mode. Inherits last session's mode and never just stops.

- **`/acepilot continue`** — reads CONTEXT.md for last mode (god, auto, go, etc.) and continues in that mode. If incomplete tasks exist, executes them. If all done, scans for new work. Never just stops.
- **Mode inheritance** — ran `god` last session? `continue` picks up in god. Ran `go`? Continues in go. No mode detected? Defaults to `go` (Starter) or `auto` (Pro).
- **resume vs continue** — `resume` = mid-session recovery (MODE file exists). `continue` = cross-session continuity (reads CONTEXT.md, works after clean exit).
- **Mode picker** — no-arg `/acepilot` now shows `continue` option when TASKS.md has incomplete tasks.
- 9 modes (was 8). `continue` available in both Starter and Pro.

## 7.0 — Adaptive Intelligence

God mode routes the right specialists to each task instead of calling all five every time — typical sessions use ~60% fewer subagent calls. Execution patterns accumulate across sessions so AcePilot gets smarter the more you use it. Trivial changes (typo, config value) complete in under 30 seconds. Compaction can no longer silently drop state.

### What changed:

- **Smart dispatch** — specialists routed by diff content in ALL modes including god. A CSS fix gets @reviewer + @designer, not all 5. Saves ~60% of subagent calls on typical tasks. `god --full-team` preserves v6.4 behavior when maximum coverage is needed.
- **Sub-30s execution for trivial changes** — new "Micro" tier: typo fix, config value change, single rename. Skips orient and algorithm entirely — just execute + verify + self-qualify. Scope grows mid-task? Auto-upgrades to Quick tier.
- **PATTERNS.md** — new state file (9th). Tracks specialist ROI per file type, failure modes with recovery strategies, false positive patterns, gate accuracy. Never compacted. Compound rule appends after each session. Specialists read it to avoid known false positives.
- **Task deduplication** — scan cross-references TASKS.md, git log, and PATTERNS.md before creating tasks. Prevents scanning and creating tasks that were already fixed in recent commits.
- **Hardened compaction** — explicit verification checklist after compaction. Missing state → auto re-read before continuing. Logged: `COMPACTED at task [N/total]. State verified.`
- **Recovery consultation** — when circuit breaker fires, checks PATTERNS.md for known recovery strategies before escalating to BLOCKED.
- **Session metrics** — tracks and reports specialist call count, PASS rate, findings per specialist. Shows which specialists catch real issues vs. which consistently PASS — reveals where to cut.
- **Knowledge freshness** — KNOWLEDGE.md entries get `<!-- verified: YYYY-MM-DD -->` markers. Entries >30 days unverified flagged during absorb. Prevents acting on stale product facts.
- **All 6 agents** — now read PATTERNS.md for false positive awareness and project-specific conventions.

### What was NOT changed (load-bearing):

- Confidence gate calibration (AUTO/PLAN/ASK) — proven across 50+ versions
- Never-compact on DECISIONS.md, KNOWLEDGE.md, USER-KNOWLEDGE.md, PATTERNS.md
- Two-attempts-max circuit breaker
- Flat file state (no database)
- Append-only DECISIONS.md
- 8 modes, same escalation ladder

---

## 6.4 — Token Diet

Every prompt line audited for ROI. 1039 → 627 lines across 7 files (−40%). Zero checks removed — same execution quality, fewer tokens burned.

### What changed:

- **acepilot.md** — Removed 6.0 Research Foundation docs (45 lines of citations never used for execution). Compressed mode descriptions to table format. Compressed absorb steps and session summary template. 428 → 326 lines (−24%).
- **All 6 agent prompts** — Compressed verbose domain knowledge descriptions to concise references. Models already know Nielsen's heuristics, OWASP Top 10, etc. — paying tokens to explain them was waste. Kept: checklist structure, output format, severity scales, skip rules, PASS shortcut. designer: 82→30, security: 103→34, architect: 114→32, strategist: 128→31, researcher: 114→52, reviewer: 70→39.
- **God mode per-task cost** — 5 specialist prompts dropped from ~500 to ~200 Sonnet tokens per review cycle.

---

## 6.3 — Anti-Mediocrity

Designer agent and main command gain anti-cliche awareness. Every public-facing output is now checked against conversion-quality standards derived from Stripe, Linear, Cursor, and CRO research (CXL, Baymard, Copyhackers).

### What changed:

- **@designer anti-cliche filter** — flags generic SaaS patterns (text-as-icons, vanity stats, template layouts), AI-tool clichés (dark-purple sameness, generic feature copy), weak copy (mechanism over outcome), missing conversion signals, and visual sameness. Applied automatically when diff touches public-facing pages.
- **COMPOUND anti-mediocrity rules** — permanent rules for landing page work: outcome-focused stats, outcome-first feature copy, earn-your-place sections, real SVG icons, clean CSS (no !important, no inline styles, no br-for-spacing).
- **Research foundation** — anti-cliche principles grounded in: Stripe (clarity + product proof), Linear (distinctive dark done right), Cursor (restraint), CXL/Baymard (conversion specificity), Copyhackers (voice of customer > brand copy).

---

## 6.2 — Objective Auto

Auto mode gains objective support — same Working Backwards + @researcher strategy pattern as god mode, now available at a lower trust level.

### What changed:

- **`/acepilot auto [objective]`** — when given an outcome goal (e.g. `auto reduce page load time`), runs Working Backwards → @researcher strategy → TASKS.md entries with `[objective:slug]`, then executes. Consistent with plan and god modes.

---

## 6.1 — God Mode

Mode redesign. 14 modes consolidated to 8. Clear trust ladder: plan → go → auto → ship → god.

### What changed:

- **God mode** — the peak. All 6 specialists review every task. Zero interruptions. Objective-driven when given a goal. Combines the best of full, dreamteam, and ceo into one unified mode. `/acepilot god` or `START ACEPILOT GOD`.
- **Ship mode** — redesigned from utility to execution mode. Now means "handle it and push a PR." Auto + specialist review + squash + PR in one word. Replaces `auto push`.
- **8 modes total** (down from 14): plan, go, auto, ship, god + status, review, resume
- **Mid-task re-orient** — when blocked after 2 attempts, re-reads context and generates an alternative approach before circuit-breaking. From Devin research.
- **Version display** — ready message and status now show AcePilot version (e.g. "ACEPILOT 6.1 ON")
- **Backward compat** — all old names still work: full→god, dreamteam→god, ceo→god, auto push→ship

### What was removed (and where it went):

- `auto push` → absorbed by `ship`
- `full` → absorbed by `god`
- `dreamteam` → absorbed by `god` (all-specialist review is what makes god mode god mode)
- `ceo` → absorbed by `god` with objective (give god a goal, it does Working Backwards + strategy)
- `ceo plan` → absorbed by `plan` with objective
- `overview` → removed (low usage, barely documented)

### Research foundation (6.1):

Competitive analysis across 10+ tools: Cursor (Automations, background agents), Devin (dynamic re-planning, confidence evaluation), Cline (computer use, cost tracking), Aider (repo maps, architect/editor split), OpenHands (CodeAct, agent delegation), Codex CLI (path-based agents, enterprise hooks), Windsurf (Cascade, persistent memory), Continue.dev (CI-integrated AI checks, auto-rule writing), Sweep (PR feedback loop), plus community patterns from awesome-claude-code, HN, Reddit.

Key competitive gaps identified for future versions: event-driven triggers, token burn rate display, PR comment feedback loop, background agent mode.

---

## 6.0 — Dream Team Edition

CEO objective: "Research all missing skills, make AcePilot the best digital product team in the world."

Deep research across 40+ practitioners in 12 domains. Applied The Algorithm: only what has a proven mechanism for improving output. Rejected theory, buzzwords, and theater.

### New in 6.0:

- **4 new specialist agents** — @designer (UX/a11y/copy), @security (OWASP/secrets/auth), @architect (coupling/performance/devops), @strategist (analytics/testing/docs/growth). Total: 6 agents.
- **dreamteam mode** — All 5 specialist agents review every task in parallel. The full product team you couldn't hire, reviewing everything you ship. `START ACEPILOT DREAMTEAM` or `/acepilot dreamteam`.
- **Specialist review step** — New step in execution pipeline between Verify and Qualify. Auto-routes relevant specialists based on diff content. dreamteam mode skips routing and invokes ALL specialists.
- **Auto-routing heuristic** — In go/auto/full modes, automatically picks which specialists to invoke: HTML/CSS → @designer, auth/tokens → @security, new modules/DB → @architect, features/flows → @strategist.
- **CEO mode uses dreamteam** — CEO execution now runs all specialists on every task (previously used full mode behavior).
- **Per-specialist session summary** — Session report now shows findings per specialist with severity breakdown.

### Research foundation (new agents grounded in):

**Design:** Nielsen (10 heuristics from 249 real problems), Norman (Gulf of Execution/Evaluation), Wroblewski (mobile-first), Refactoring UI (tactical design rules), Schwartz/Baymard (copy quality), WCAG Big 6 (96% of a11y failures).

**Security:** Schneier (attacker mindset), OWASP Top 10 (top 3 = 40%+ coverage), Troy Hunt (practical automation), NIST CSF (Identify + Protect).

**Architecture:** Gregg (USE method), Souders (14 perf rules, top 3 = 70%), Fowler/Martin (coupling + cohesion), Gene Kim (Three Ways), Google SRE (playbooks = 3x MTTR).

**Strategy:** Beck/Fowler (pragmatic testing), Lean Analytics (OMTM), Divio/Stripe (docs developers read), Ellis (40% PMF rule), Rachitsky (activation benchmarks), Goldratt (Theory of Constraints).

### What was explicitly rejected (and why):

- "User-centered design" as vague principle → replaced with mechanical heuristic checklists
- Security complexity scores → replaced with exploitability assessment
- 100% test coverage dogma → replaced with critical-path-first testing
- "Clean code" religion → replaced with coupling/cohesion measurement
- Growth hacking myths → replaced with retention-first metrics
- Architecture astronautics → replaced with "does this cause real problems?"
- Comprehensive documentation → replaced with "what do developers actually read?"

### File structure (6.0):

```
acepilot-6.0/
  user-level/
    CLAUDE.md                    # trigger + compaction survival
    settings.json                # permissions + status line + hooks
    USER-KNOWLEDGE.md            # user model seed template
    commands/acepilot.md         # the brain (~400 lines)
    agents/researcher.md         # Haiku scanner
    agents/reviewer.md           # Sonnet code reviewer
    agents/designer.md           # Sonnet UX/a11y/copy
    agents/security.md           # Sonnet vulnerability scanner
    agents/architect.md          # Sonnet architecture/performance
    agents/strategist.md         # Sonnet product/growth
  project-level/
    settings.json                # project hooks
  KNOWLEDGE-TEMPLATE.md          # product knowledge seed
  install.sh                     # install/upgrade/check/uninstall
  README.md                      # full documentation
  CHANGELOG.md                   # version history
```

---

## 5.0 — The Complete Package

CEO objective: "Make AcePilot the best autonomous execution tool in the world."

Generated 60+ improvement ideas. Applied The Algorithm: Question → Delete → Simplify. Kept 7 that genuinely improve the product. Rejected 53+ with reasons.

### New in 5.0 (on top of v49.1 Fast Path):

- **Implicit Orient** — Known codebases (KNOWLEDGE.md has 10+ entries) get compressed orientation. Session 10 is faster than session 1 without losing quality.
- **Intent disambiguation via USER-KNOWLEDGE** — Ambiguous operator directives resolved from user patterns before asking. The system learns how you think.
- **Progress estimation** — Every 3rd task: "~N remaining, ~M min est." No more guessing when the session finishes.
- **Moat metric** — Session summary shows total accumulated KNOWLEDGE + USER-KNOWLEDGE entries. Makes the competitive advantage visible.
- **Confidence calibration** — Tracks gate accuracy (AUTO/PLAN/ASK). Reports in session summary. Reveals whether gates are too conservative or too loose.
- **Graceful degradation** — Explicit fallback strategy: build fails → read error, fix, rebuild. Never retry blindly. Failure of approach A is data for approach B.
- **Error class memory** — COMPOUND rule now auto-appends error patterns to KNOWLEDGE.md. AcePilot never makes the same class of mistake twice.

### Carried from v49.1 Fast Path:

- Fast Path for quick tasks (<60s target)
- Compressed Orient, fused Algorithm, inline self-qualify
- CEO plan mode (strategize without executing)

### Carried from v49 Decision Intelligence:

- ORIENT phase (Boyd OODA)
- Reversibility-based confidence gate (Bezos Type 1/2)
- The Algorithm (Musk: Question→Delete→Simplify→Execute→Optimize)
- Staged verify (Beck Work→Right→Fast)
- Task re-evaluation every 5 tasks (Hastings keeper test)
- Working Backwards for CEO mode (Bezos PR/FAQ)
- Cycle time tracking

---

## v49.1 — Fast Path (internal, now part of 5.0)

CEO objective: "reduce time from user intent to working verified code to under 60 seconds for any task under 3 files, while maintaining or improving output quality."

**Before:** Quick tasks ran the full 10-step pipeline (~2-5 min). Every task got the same ceremony regardless of size.
**After:** Quick tasks (≤2 files, clear fix, reversible) get a compressed "Fast Path" — same quality checks, less ceremony. Target: <60s.

### Changes to acepilot.md:

- **Fast Path scope** — Quick tasks skip scope re-check and get compressed Orient, fused Algorithm, and inline self-qualify
- **Compressed Orient** — One-pass: read file, confirm understanding, identify change. Escalates to full Orient if anything feels off
- **Fused Algorithm** — Steps 1-3 (Question/Delete/Simplify) compressed to one question for quick tasks: "Is every part needed? Simpler way?"
- **Conditional Verify** — Step 3 (performance) skipped unless hot path. Tests never skipped.
- **Inline self-qualify** — Quick tasks: one-line confirmation vs spec. Standard/Complex: full @reviewer delegation unchanged.
- **Batched micro-log** — Log per completed task, not per action
- **CEO plan mode** — `ceo plan` strategizes without executing. Resume picks up the plan.

### Quality preservation:

- Orient (catches wrong direction) — kept, compressed
- Tests (catches bugs) — kept, never skipped
- Confidence gate (catches irreversibility) — kept, unchanged
- Qualify (catches wrong problem) — kept, compressed for quick tasks

---

## v48 → v49

### Theme: Decision Intelligence

v49 incorporates principles from the world's most successful builders — Bezos (reversibility-based decisions), Musk (The Algorithm), Boyd (OODA orientation), Hastings (keeper test), Beck (Work→Right→Fast), Torvalds (eliminate edge cases through better abstraction), and Carmack (gradient descent execution). Every addition was critically filtered: only changes that genuinely improve autonomous execution quality made the cut.

### Versioning strategy (v49+)

- **Major** (v49, v50...): Architectural changes, new capabilities
- **Minor** (v49.1, v49.2...): Bug fixes, refinements, non-breaking improvements
- Named releases for marketing: v49 = "Decision Intelligence"

### user-level/commands/acepilot.md

- **ORIENT phase** — New step in execution pipeline. Per-task: 3 questions (what does code do, what does task need, simplest change). Per-session: 3-line orientation statement written to CONTEXT.md. Based on Boyd's OODA loop — Orient determines whether execution hits or misses.
- **Reversibility-based confidence gate** — Replaced file-count heuristic with reversibility assessment. AUTO = two-way door (reversible with `git revert`). PLAN = two-way door, higher stakes. ASK = one-way door (irreversible). Quick test: "Can I undo this in 60 seconds?"
- **The Algorithm** — 5-step pre-execution sequence: Question requirements → Delete unnecessary work → Simplify → Execute → Optimize. Order is load-bearing — never optimize before deleting. Based on Musk's manufacturing process.
- **Staged verification** — Work→Right→Fast sequence replaces flat verify. First: does it work? Then: is the code clean? Then: performance. Based on Kent Beck's TDD principle.
- **Task re-evaluation** — Every 5 tasks: keeper test (would we still do this?), inflection check (has priority changed?), score refresh, stale task culling. Prevents wasting cycles on obsoleted work.
- **Working Backwards for CEO mode** — Write desired PR description FIRST, then derive tasks from it. Forces clarity before execution. Based on Bezos's PR/FAQ process.
- **Cycle time tracking** — North star metric. Tasks track `⏱ Xmin`. Session summary reports avg/fastest/slowest cycle time.
- **BLOCKED reframed** — "BLOCKED is data, not failure." Intent issues auto-append corrected understanding to KNOWLEDGE.md.
- **SKIPPED status** — New `[⊘]` status for tasks killed by re-evaluation. Prevents phantom task accumulation.
- **Execution pipeline updated** — `TASK → Orient → Scope → Gate → Algorithm → Do → Verify → Qualify → Log → Checkpoint`

### Directory rename

- `autopilot-v48/` → `acepilot-v49/` — completing the rename started in v48

---

## v47 → v48

### Theme: Identity

The product is now Ace. "Autopilot" was the internal project name across 47 versions. v48 completes the rename to the product persona everywhere it refers to identity — while keeping the technical command interface (`/autopilot`, `autopilot:` git prefix, file names, directory names) unchanged for backward compatibility.

### user-level/commands/autopilot.md

- **Command description** — `"AUTOPILOT."` → `"ACE."`
- **Section header** — `# AUTOPILOT — ON` → `# ACE — ON`
- **Ready message** — `"AUTOPILOT ON."` → `"ACE ON."`

### user-level/CLAUDE.md

- **Primary triggers** — `"START ACE"` is now the primary phrase; all "START AUTOPILOT" variants kept as backward-compat aliases
- **Focus example** — `"START AUTOPILOT AUTO fix auth"` → `"START ACE AUTO fix auth"`

### user-level/agents/researcher.md

- **APS acronym** — `"Autopilot Priority Score"` → `"Ace Priority Score"`

### install.sh

- **Output labels** — "Autopilot installer", "Checking Autopilot installation", "Removing Autopilot files", "Autopilot is ready." → "Ace" throughout

### README.md

- **Title** — `# Autopilot for Claude Code` → `# Ace for Claude Code`
- **Prose identity** — 12 occurrences: "Ace runs two safety checks", "Ace never commits to main", "Ace stashes them first", "Ace suggests creating", "Ace outputs a structured summary", etc.
- **APS acronym** — all occurrences updated
- **CLAUDE.md description** — updated to show "START ACE" as new primary trigger
- **Version history** — added v48 row
- **Path refs** — updated all `autopilot-v47` → `autopilot-v48`

### CHANGELOG.md

- **Header** — `# AUTOPILOT — Changelog` → `# AcePilot — Changelog`

### What was NOT renamed (intentional)

- `/autopilot` command name — don't break existing users
- `autopilot:` git commit prefix — don't rewrite history
- `autopilot.md` file name — core config file path
- `autopilot-vN/` directory names — distribution folders
- All historical CHANGELOG entries — don't rewrite history
- ~~`AUTOPILOT: [mode]` status line display~~ — renamed to `ACE:` in v48.1
- `--grep="autopilot:"` in reviewer.md — git history search

### Unchanged

- user-level/agents/reviewer.md
- project-level/rules/browser.md
- KNOWLEDGE-TEMPLATE.md
- USER-KNOWLEDGE.md

---

## v48.1 → v48.2

### Theme: Command rename

The slash command is now `/acepilot`. Previously `/autopilot` was kept as a technical interface even after the product renamed to Ace. v48.2 completes the rename: the command name, file name, git commit prefix, branch prefix, stash message prefix, and all tooling grep patterns now use `acepilot`.

### user-level/commands/autopilot.md → acepilot.md

- **File renamed** — `commands/autopilot.md` → `commands/acepilot.md`
- **Command name** — `name: autopilot` → `name: acepilot` (controls slash command picker display)
- **Description** — `/autopilot [go|...]` → `/acepilot [go|...]`
- **Git commit prefix** — `autopilot: [TASK-ID]` → `acepilot: [TASK-ID]`
- **Branch prefix** — `autopilot/YYYYMMDD` → `acepilot/YYYYMMDD`
- **Stash message** — `autopilot: pre-session` → `acepilot: pre-session`
- **Review mode** — `git log --grep="autopilot:"` → `git log --grep="acepilot:"`

### user-level/CLAUDE.md

- **All triggers** — `run /autopilot` → `run /acepilot` throughout

### user-level/agents/reviewer.md

- **Checkpoint search** — `--grep="autopilot:"` → `--grep="acepilot:"`

### install.sh

- **All file paths** — `commands/autopilot.md` → `commands/acepilot.md`

### project-level/settings.json

- **SessionStart prompt** — `run /autopilot resume` → `run /acepilot resume`

### README.md

- **All `/autopilot` command references** → `/acepilot`
- **Section headers** — `(or 'start autopilot')` → `(or 'start ace')`
- **Commit/branch/grep examples** updated throughout

### What was NOT renamed

- `autopilot-vN/` distribution folder names — don't break existing clones
- `start autopilot` backward-compat trigger in CLAUDE.md — still routes to `/acepilot`
- Historical CHANGELOG entries — don't rewrite history

---

## v48 → v48.1

### Theme: Identity completion

v48 conservatively kept the status line display as `AUTOPILOT:` and marked it as "tied to settings.json grep patterns." No grep patterns depend on the output string — it's a plain echo. The status line is the most user-visible branding element. Fixed, along with missed identity references in README and SessionStart hook.

### user-level/settings.json

- **Status line display** — `AUTOPILOT: $mode` → `ACE: $mode`

### project-level/settings.json

- **SessionStart prompt** — `Previous autopilot mode:` → `Previous Ace mode:`

### README.md

- **Quickstart** — `say "start autopilot"` → `say "start ace"`
- **Manual install comment** — `you want Autopilot on` → `you want Ace on`
- **Status line docs** (3 occurrences) — `AUTOPILOT: [mode]` → `ACE: [mode]`
- **MODE file description** — `active autopilot mode` → `active Ace mode`
- **SessionStart description** — `Previous autopilot mode:` → `Previous Ace mode:`
- **Version history v33** — `shows autopilot state in UI` → `shows Ace state in UI`

## File count: unchanged

## autopilot.md: ~281 lines (unchanged)

---

## v46 → v47

### Theme: User model

Autopilot has always accumulated product knowledge (KNOWLEDGE.md — what the project does). v47 adds a second accumulating layer: user knowledge (USER-KNOWLEDGE.md — how this specific person thinks and works). As corrections and confirmed preferences accumulate, every judgment call in future sessions is shaped by who you are, not just what your project does.

### user-level/USER-KNOWLEDGE.md — new file

- **New seed template** with six named sections: Preferences, Patterns, Corrections, Communication style, Risk tolerance, Blind spots
- Append-only. Never compacted. Date-stamped entries with source tag
- Not an active config file — it grows through use, starting empty

### user-level/commands/autopilot.md — ~281 lines (was ~278)

- **Absorb step 4b** — now reads both `KNOWLEDGE.md` and `USER-KNOWLEDGE.md` on every session start. User model context available before scan.
- **COMPOUND rule** — added: "Learned user fact (preference, pattern, correction, communication style, risk tolerance) → auto-append to USER-KNOWLEDGE.md under the relevant section (auto/full modes) or propose (go/plan). Include date and source."
- **Compaction strategy** — added USER-KNOWLEDGE.md to the post-compaction re-read list and compact instruction
- **TOKEN DISCIPLINE compact prompt** — updated to preserve USER-KNOWLEDGE.md verbatim alongside KNOWLEDGE.md
- **SESSION SUMMARY** — updated KNOWLEDGE line to report both KNOWLEDGE.md and USER-KNOWLEDGE.md new entries
- **STATE SYSTEM** — added USER-KNOWLEDGE.md as 8th state file with full description

### user-level/CLAUDE.md — unchanged line count

- **Compaction instruction** — updated: "Never summarize DECISIONS.md, KNOWLEDGE.md, **or USER-KNOWLEDGE.md**." Both knowledge files now explicitly protected.

### README.md

- **State system** — added USER-KNOWLEDGE.md entry after KNOWLEDGE.md
- **CLAUDE.md description** — updated compaction note to mention USER-KNOWLEDGE.md
- **Version history** — added v47 row
- **Path refs** — updated all `autopilot-v46` → `autopilot-v47`

### Unchanged

- user-level/settings.json
- user-level/agents/researcher.md
- user-level/agents/reviewer.md
- project-level/settings.json
- project-level/rules/browser.md
- KNOWLEDGE-TEMPLATE.md
- install.sh

## File count: 7 + 1 user model template + 1 product knowledge template + 1 install script

## autopilot.md: ~281 lines (was ~278)

## USER-KNOWLEDGE.md: new file (~18 lines)

---

## v45 → v46

### Theme: Knowledge loop

KNOWLEDGE.md was seeded once and then stagnated. Every session @researcher discovers non-obvious facts — API quirks, constraints, architectural decisions — and they were lost. v46 closes that loop: facts flow into KNOWLEDGE.md automatically, making each future session smarter than the last.

### researcher.md — ~62 lines (was ~59)

- **SCAN MODE output: `## Knowledge updates` block** — max 3 non-obvious facts discovered during scan that aren't already in KNOWLEDGE.md. Format: `- [area]: [fact]`. Omit section if nothing new. Researcher already reads KNOWLEDGE.md before scanning (RULES line), so deduplication is free.

### autopilot.md — ~278 lines (was ~277)

- **Scan step** — after writing TASKS.md, if researcher returned Knowledge updates: append each to KNOWLEDGE.md immediately (auto/full: silently; go/plan: show and confirm first)
- **Qualify step** — BLOCKED with `intent issue` → corrected domain understanding appended to KNOWLEDGE.md. Prevents the same misunderstanding recurring in future sessions.
- **COMPOUND rule** — changed from "suggest KNOWLEDGE.md entry" to **auto-append** in auto/full modes, propose in go/plan. Every entry date-stamped: `<!-- added YYYY-MM-DD, source: scan|task|blocked -->`
- **SESSION SUMMARY** — added `KNOWLEDGE: [N] new entries added to KNOWLEDGE.md | none` line

### README.md

- **New section: "How KNOWLEDGE.md grows automatically"** — explains scan updates, blocked-task capture, COMPOUND rule, date stamping, compounding effect
- **Version history** — added v46 row
- **Path refs** — updated all `autopilot-v45` → `autopilot-v46`

### Unchanged

- user-level/CLAUDE.md
- user-level/settings.json
- user-level/agents/reviewer.md
- project-level/settings.json
- project-level/rules/browser.md
- KNOWLEDGE-TEMPLATE.md
- install.sh

## File count: 7 + 1 template + 1 install script (unchanged)

## researcher.md: ~62 lines (was ~59)

## autopilot.md: ~278 lines (was ~277)

---

## v44 → v45

### Theme: Correctness

30 bugs identified by systematic audit and fixed. Grouped by file.

### project-level/settings.json

- **PreCompact STATE.md** (#7) — removed non-existent `STATE.md` from backup loop; added `CIRCUIT` and `STASH_REF` to backup list
- **Notification hook grep** (#6) — `grep -c '...\|...'` (BRE alternation fails on macOS BSD grep) → `grep -cE '...|...'` (ERE alternation, cross-platform)

### user-level/settings.json

- **Status line grep** (#6) — same BRE → ERE fix for done-count grep. Status line now returns accurate `[d/t]` on macOS.

### user-level/agents/researcher.md

- **git diff in allowed-tools** (#21) — added `"Bash(git diff*)"` so researcher can see recent changes during scan
- **Revenue path matching algorithm** (#5) — defined as prefix match: task file path must start with the listed revenue path (e.g. `src/auth/login.ts` matches `src/auth/`)

### user-level/commands/autopilot.md — 18 fixes

- **Circuit breaker persistence** (#2) — CIRCUIT state file: `OPEN` written on trip, deleted on reset. Absorb reads it. Resume detects and offers reset. Survives compaction.
- **Stash ref persistence** (#1) — STASH_REF state file: written by pre-flight dirty tree stash, read by session summary for `git stash pop`, deleted after successful pop. Guaranteed restoration.
- **Resume stash/circuit validation** (#3) — resume now checks STASH_REF (restores if found) and CIRCUIT (offers reset if OPEN) before continuing
- **★ focus stickiness** (#8) — ★ ordering only active when focus is in MODE. Without active focus, ★ tasks execute in normal APS order.
- **+1 autonomy cap** (#10) — AUTO tasks under focus stay AUTO (no overflow above AUTO)
- **Dependency wins over score** (#15) — explicit rule: `[needs:X]` always blocks regardless of score
- **Attempt counter meaning** (#19) — `(attempt: 2/3)` now documented as "2nd attempt, 3 max"
- **Dual-condition exit checks** (#12) — specified: build success + targeted tests pass. Lint = concerns not blockers. Full suite only for ship pipeline.
- **Parallel merge algorithm** (#4) — DECISIONS.md appended sequentially; TASKS.md merged by task ID with priority DONE > DONE_WITH_CONCERNS > BLOCKED
- **Parallel budget scoping** (#16) — `--max-tasks` is session-level across all worktrees, not per-worktree
- **PR description algorithm** (#26) — from TASKS.md `[x]/[~]` titles + git log since first autopilot commit + DONE_WITH_CONCERNS DECISIONS entries as caveats
- **Task deduplication rule** (#24) — same issue in multiple files = one task; different approaches = separate tasks with `[needs:]` chaining
- **DECISIONS.md archival** (#18) — at 500 lines, archive to backups/, start fresh
- **MODE validation** (#17) — empty/unreadable MODE treated as absent
- **Micro-log truncation** (#30) — truncate with … if over 12 words
- **FOCUS stickiness clarified** (#8) — focus section updated with same rule
- **File scan determinism** (#11) — `| sort | head -100` (was unordered `head -50`)
- **State system count** — updated from "Five" to "Seven files", added CIRCUIT and STASH_REF entries

### Unchanged

- user-level/CLAUDE.md
- user-level/agents/reviewer.md
- project-level/rules/browser.md
- KNOWLEDGE-TEMPLATE.md
- install.sh

## File count: 7 + 1 template + 1 install script (unchanged)

## Command length: ~295 lines (was ~265)

## researcher.md: ~62 lines (was ~60)

---

## v43 → v44

### Theme: Pre-flight

Two safety checks run after absorb and before any scan or execution. Most sessions that waste tokens do so because they start from a bad baseline — wrong branch or uncommitted changes that create merge noise. Pre-flight eliminates both.

### Command (user-level/commands/autopilot.md) — ~265 lines (was ~245)

- **New `## PRE-FLIGHT` section** — defines two checks and their behavior per mode:
  - **Branch guard**: on `main`/`master` → stop (go/plan) or auto-create `autopilot/YYYYMMDD[-focus]` branch (auto/auto-push/full). Ship/status/review/resume: warn or skip. No-git: skip.
  - **Dirty tree**: uncommitted changes → `git stash push -m "autopilot: pre-session YYYYMMDD-HHMMSS"`. Stash ref logged to DECISIONS.md. Auto-restored at session end via `git stash pop`. Conflict on pop → warn, leave stash, log ref. `plan` mode skips stash (no commits).
- **Mode one-liners updated** — `go`, `plan`, `auto`, `auto push`, `full`, `ship`, `resume` all reference pre-flight behavior explicitly
- **`auto push`** — "Feature branches only" note now references pre-flight as the guarantee (not a runtime check)
- **SESSION SUMMARY** — added `STASH: restored | skipped | conflict` line; added stash pop step to exit sequence

### README.md

- **New section: "How pre-flight works"** — explains branch guard and dirty tree behavior for each mode, stash recovery flow
- **Version history** — added v44 row
- **Path refs** — updated all `autopilot-v43` → `autopilot-v44`

### Unchanged

- user-level/CLAUDE.md
- user-level/settings.json
- user-level/agents/researcher.md
- user-level/agents/reviewer.md
- project-level/settings.json
- project-level/rules/browser.md
- KNOWLEDGE-TEMPLATE.md
- install.sh

## File count: 7 + 1 template + 1 install script (unchanged)

## Command length: ~265 lines (was ~245)

---

## v42 → v43

### Theme: Path Safety

Autopilot is stored on iCloud Drive, OneDrive, Dropbox, and other cloud-synced directories whose paths contain spaces (e.g. `~/Library/Mobile Documents/...`). Three targeted fixes ensure all shell operations and install instructions work correctly on any path.

### install.sh — 2 fixes

- **`install_project` echo**: `cp $SCRIPT_DIR/KNOWLEDGE-TEMPLATE.md ...` → `cp "$SCRIPT_DIR/KNOWLEDGE-TEMPLATE.md" ...` — prevents broken copy-paste when path contains spaces
- **`uninstall` echo**: `rm $CLAUDE_HOME/CLAUDE.md ...` → `rm "$CLAUDE_HOME/CLAUDE.md" ...` — same fix for uninstall suggestion

### project-level/settings.json — 1 fix

- **PreCompact hook**: `$(basename $f)` → `$(basename "$f")` — technically safe today (values are hardcoded relative paths) but correct shell quoting

### README.md

- **Install script section**: Quoted `"/path/with spaces/install.sh"` in all examples + added iCloud/OneDrive/Dropbox compatibility note
- **Manual install section**: All `cp` source paths now quoted — safe to copy-paste from any working directory
- **Quickstart**: Quoted project-level install path
- **Version history**: Added v43 row

### What was already safe (no changes needed)

- **PostToolUse formatter hook**: `$CLAUDE_TOOL_INPUT_FILE_PATH` was already assigned as `f="$..."` and used as `"$f"` ✓
- **Status line**: `$cwd` was already used quoted throughout ✓
- **All hooks**: Use relative paths (`.claude/state/...`) — unaffected by spaces in parent directories ✓
- **Absorb commands**: All relative paths — unaffected ✓

### Unchanged

- user-level/CLAUDE.md
- user-level/settings.json
- user-level/commands/autopilot.md
- user-level/agents/researcher.md
- user-level/agents/reviewer.md
- KNOWLEDGE-TEMPLATE.md
- project-level/rules/browser.md

## File count: 7 + 1 template + 1 install script (unchanged)

## Command length: ~245 lines (unchanged)

## install.sh: ~131 lines (unchanged)

---

## v41 → v42

### Theme: Revenue Awareness

Impact scoring is grounded in explicit business context. A bug in the payment flow and a bug in an internal admin tool no longer score the same Impact — revenue paths in KNOWLEDGE.md make the difference automatic.

### KNOWLEDGE-TEMPLATE.md — 25 lines (was 20)

- **New `## Revenue paths` section** — first section in the template (highest-priority product knowledge). Instructs operators to list directories/files that directly handle payments, conversion, auth, or core user value. Documents the Impact=5 effect.

### Researcher agent (user-level/agents/researcher.md) — ~60 lines (was 54)

- **SCAN MODE — Impact override** — added revenue path override to the Impact dimension: if KNOWLEDGE.md has a `## Revenue paths` section, any task whose file/directory matches gets Impact=5 automatically, regardless of other factors. Documents the business rationale inline ("bug there costs money every minute").
- **KNOWLEDGE MODE — Revenue paths extraction** — added `**Revenue paths:**` as the first output field. When seeding KNOWLEDGE.md on first run, @researcher now identifies which modules handle payments, auth, conversion, or core user value.

### Command (user-level/commands/autopilot.md) — ~245 lines (unchanged)

- **TASK ORDERING** — added one-line note: "Revenue paths in KNOWLEDGE.md anchor Impact scores: tasks touching those paths get Impact=5 automatically."

### README.md

- **"How priority scoring works"** — new "Revenue path awareness" paragraph explaining the mechanism and how to seed it
- **State system — KNOWLEDGE.md entry** — updated to mention revenue paths as a first-class field
- **Researcher knowledge mode description** — updated to list revenue paths first
- **KNOWLEDGE.md deep section** — updated to include revenue paths and explain the Impact=5 effect
- **Version history** — added v42 row
- **Path refs** — updated all `autopilot-v41` → `autopilot-v42`

### Unchanged

- user-level/CLAUDE.md
- user-level/settings.json
- user-level/agents/reviewer.md (48 lines)
- project-level/settings.json (hooks)
- project-level/rules/browser.md
- install.sh (~130 lines)

## File count: 7 + 1 template + 1 install script (unchanged)

## Command length: ~245 lines (unchanged)

## Researcher length: ~60 lines (was 54)

---

## v40 → v41

### Theme: Priority Scoring

Every task gets a numeric score at scan time. Tasks execute in score order — not flat P0-P3 categories.

### Researcher agent (user-level/agents/researcher.md) — 54 lines (was 44)

- **APS scoring block** — SCAN MODE now computes APS (Autopilot Priority Score) for each task before listing. Formula: `(Impact + Urgency + Unblock) × Confidence / Effort`. Five dimensions, each with a fixed scale:
  - Impact 1-5 (user/business value)
  - Urgency 1-5 (P0/security=5, P1=3, P2=2, P3=1)
  - Unblock 0-3 (+1 per dependent task enabled, cap 3)
  - Confidence 0.5/0.8/1.0 (exploratory / known approach / exact fix)
  - Effort 1/2/3 (≤2 files / 3-5 files / 6+ files)
- **`[score:X.X]` tag** — included on every task line in scan output, written to TASKS.md
- **Sorted by APS descending** — P0 tasks hard-first regardless; all others ranked by score

### Command (user-level/commands/autopilot.md) — ~245 lines (unchanged)

- **TASKS.md format** — updated example to show `[score:X.X]` tag on task lines
- **TASK ORDERING** — updated to "P0 hard-first → ★ focused → sort by `[score:X.X]` descending → respect `[needs:X]`". Added one-line formula reference.

### README.md

- **New section: "How priority scoring works"** — APS formula, dimension table, example scores, design rationale (WSJF + ICE synthesis)
- Updated TASKS.md format examples in state system section
- Added v41 to version history

### Research basis

- WSJF (Weighted Shortest Job First, SAFe): Cost of Delay / Job Duration. Numerator models economic urgency. Favors short high-value jobs.
- ICE (Intercom): Impact × Confidence × Ease. Multiplicative penalizes uncertainty.
- Key synthesis: additive numerator (WSJF-style) instead of multiplicative (ICE) prevents a single zero from collapsing the score. Confidence and Effort as multiplier/divisor preserve the ratio incentive.

### Unchanged

- user-level/CLAUDE.md
- user-level/settings.json
- user-level/agents/reviewer.md (48 lines)
- project-level/settings.json (hooks)
- project-level/rules/browser.md
- KNOWLEDGE-TEMPLATE.md
- install.sh (~130 lines)

## File count: 7 + 1 template + 1 install script (unchanged)

## Command length: ~245 lines (unchanged)

## Researcher length: 54 lines (was 44)

---

## v48.1 → v48.2 — Command rename. `/autopilot` → `/acepilot`. File renamed, git prefix `acepilot:`, branch prefix `acepilot/`, grep patterns updated. "start autopilot" backward-compat alias preserved.

## v48 → v48.1 — Identity completion. Status line `AUTOPILOT:` → `ACE:`. SessionStart prompt `Previous autopilot mode` → `Previous Ace mode`. Missed identity refs in README fixed. Domain: acepilot.app.

## v47 → v48 — Identity. The product is now Ace. Product-identity prose renamed throughout. "START ACE" is the new primary trigger; "START AUTOPILOT" kept as alias. APS = Ace Priority Score. Command interface (/autopilot, autopilot: prefix, file names) unchanged for backward compat.

## v46 → v47 — User model. USER-KNOWLEDGE.md accumulates preferences, patterns, corrections, and communication style per user. Read at absorb alongside KNOWLEDGE.md. Written by compound rule. Never compacted. Each session knows more about how you specifically think.

## v45 → v46 — Knowledge loop. KNOWLEDGE.md auto-grows from scan discoveries, blocked task resolutions, and COMPOUND rule. Date-stamped entries. Session summary reports new entries.

## v44 → v45 — Correctness. 30 bugs fixed. grep BRE on macOS, circuit breaker persistence, stash ref tracking, STATE.md removed, revenue path prefix matching, 15+ definition gaps.

## v43 → v44 — Pre-flight. Branch guard (stop on main/master or auto-create branch). Dirty tree stash (labeled, auto-restored). Safe baseline before every session.

## v42 → v43 — Path safety. Quoted paths in install.sh output, PreCompact basename fix, quoted manual install examples. Works on iCloud Drive, OneDrive, Dropbox.

## v41 → v42 — Revenue awareness. Revenue paths in KNOWLEDGE.md. Impact=5 auto-assigned for tasks touching revenue paths. Knowledge mode extracts revenue paths when seeding.

## v39 → v40 — Polyglot. Absorb auto-detects project config. Formatter hook: ruff/black/rustfmt/gofmt/prettier. File scan covers 16 extensions. Status line shows progress counter.

## v38 → v39 — Distribution. Install script (install, upgrade, check, uninstall). Researcher gains git read access.

## v37 → v38 — Agents + Parallel. Reviewer gains qualify mode. Researcher gains 3 modes. Parallel execution via worktree-isolated subagents.

## v36 → v37 — Research overhaul. Qualify step, circuit breaker, budget, post-compaction hook, 4-status, scope-adaptive, dual-condition exit.

## v35 → v36 — Naming. `god`→`auto`, `god push`→`auto push`, `god push dont ask`→`full`.

## v34 → v35 — Focus directives on execution modes.

## v33 → v34 — Robustness. No jq, absorb reads MODE, SessionStart warns stale.

## v32 → v33 — Resilience. MODE file, resume inherits mode, status line.

## v31 → v32 — God + Push. `god push` + `god push dont ask`.

## v30 → v31 — Polish.

## v29 → v30 — Quality audit.

## v28 → v29 — Awareness.

## v27 → v28 — God mode.

## v26 → v27 — Workflow completion.

## v25 → v26 — Precision.

## v24 → v25 — Hardening.

## v23 → v24 — Knowledge layer.

## v22 → v23 — MCP permissions.
