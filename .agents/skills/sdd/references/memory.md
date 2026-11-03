# Memory promotion

| What happened | Write to | Do not write |
|---------------|----------|--------------|
| Any line changed | Git (maintainer) | — |
| Notable delivery | [../../knowledge/log.md](../../knowledge/log.md) | Secrets, full diffs |
| Current behavior / invariants | [../../specs/shim-behavior.md](../../specs/shim-behavior.md) | PRD drafts |
| Per-method lines and guards | [../../knowledge/concepts/mysql-shim-method-registry.md](../../knowledge/concepts/mysql-shim-method-registry.md) | Duplicating full API in specs |
| Stack / layout / tests | [../../specs/TECH.md](../../specs/TECH.md) | — |
| Repeatable decision | [../../adrs/](../../adrs/index.md) | Trial-and-error narrative |
| Non-obvious cross-link | [../../knowledge/concepts/](../../knowledge/index.md) | API duplicates |
| In-flight feature | [../../sdd/](../../sdd/index.md) | Leave in SDD after ship |

**Verify before done:** [../../runbooks/validate.md](../../runbooks/validate.md) and, for shim/DB behavior, `composer test` (Docker MySQL, ephemeral volume).

Log entry format: `## YYYY-MM-DD: summary` at top of `log.md`.

Agent does not run git write ([ADR-0002](../../adrs/ADR-0002-agent-no-git-write.md)).

OKF bundle: [../../knowledge/index.md](../../knowledge/index.md). Root [../../index.md](../../index.md) declares `okf_version` ([ADR-0003](../../adrs/ADR-0003-okf-root-index.md)).
