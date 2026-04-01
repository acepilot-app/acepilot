# AcePilot — Execution Analytics

Data drives decisions. Every gate call, specialist review, task completion, and circuit break feeds back into the next session.

**Cold start:** This file starts empty. Calibration begins after 5 sessions (cycle times), 10 sessions (gate accuracy), and 5 specialist calls per content type (routing). Check CONTEXT.md for `DATA:` insights after each session.

## Gate Log

Track every confidence gate decision. Calibration runs when session count mod 10 = 0, requires ≥20 decisions per category.

```
date       | gate | task-type | predicted-outcome | actual-outcome | correct?
```

## Specialist Log

Track every specialist call. Two precision metrics: find-precision and correct-PASS rate.

```
date       | @agent | content-type | findings | actionable | find-precision% | pass-correct?
```

## Cycle Times

Track every task's cycle time. Compare estimates to actuals. Recalibrate after 5 tasks per tier.

```
date       | task-id | tier | estimated-s | actual-s | delta%
```

## Recovery Log

Track every circuit break. Failure patterns accumulate, proven recoveries get reused.

```
date       | task-id | failure-type | recovery-applied | result
```

## Calibration Log

Track every self-calibration cycle. Runs every 10 sessions or via `god --calibrate`.

```
date       | session-count | gates-adjusted | routes-adjusted | tiers-adjusted | playbooks-pruned
```

## Chain Log

Track multi-session objective chains. Cumulative progress across sessions.

```
date       | objective | session-in-chain | tasks-this-session | cumulative-tasks | status
```

## Session Rollups

Append after each session. This is the cross-session trend that drives calibration. Include session count for calibration trigger.

```
date       | session# | mode | tasks | avg-cycle-s | gate-accuracy% | specialist-precision% | data-delta
```
