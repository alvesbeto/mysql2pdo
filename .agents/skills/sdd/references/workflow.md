# SDD workflow

Workspace: `.agents/sdd/<feature-kebab>/`.

## Phase 0 — Capability map

Only if work spans packaging + API + release. Confirm [../../specs/CAPABILITY_MAP.md](../../specs/CAPABILITY_MAP.md) (`mysql-shim` over **PDO**, `agent-tooling`).

## Phase 1 — Specify (STOP GATE)

Create or update `prd.md`.

Minimum: objective, scope, **consumer impact** (including `$link instanceof \Mysql2pdo\Driver\ShimLink`), test/verify plan (`composer test`), boundaries.

**Rule:** Save the file. Summarize open questions. Ask human approval. **End the turn.** No plan or code in the same turn.

## Phase 2 — Plan

After approval, write `techspec.md`: components (`src/Mysql2pdo/Driver/*`, shims), order, contracts, out of scope.

Search this repo before new files; document reuse in `techspec.md`. Driver boundary: [ADR-0006](../../adrs/ADR-0006-pdo-driver-in-src.md).

## Phase 3 — Tasks

Write `tasks.md`. Each task: acceptance + verify command (`composer test`, `composer validate --strict`). Max ~5 files per task.

## Phase 4 — Implement and sync

Implement. Update `specs/shim-behavior.md`, method registry, `docs/API.md`, `CHANGELOG.md`, ADRs as needed. Add/adjust integration tests in `tests/Integration/` when public `mysql_*` behavior changes. See [memory.md](memory.md).

| Phase | File | Exit when |
|-------|------|-----------|
| PRD | `prd.md` | Behavior and success criteria explicit |
| TechSpec | `techspec.md` | Components and contracts explicit |
| Tasks | `tasks.md` | Each task has acceptance + verify |
| QA | `qa.md` | PRD criteria checked or gaps written |
| Review | `review.md` | Spec/code drift resolved |

Small one-file fixes **do not** open a workspace.

Optional IDs in prose: `CA-*`, `TU-*`. No file per ID.
