---
type: Concept
title: Agent context taxonomy
description: How .agents/ folders relate; read once when lost in the tree.
generated:
  at: 2026-11-03T16:45:00-03:00
  by: process:agent
sources:
  - id: adr-0001
    title: ADR-0001 — Agent context in .agents
    path: .agents/adrs/ADR-0001-agent-context-in-agents.md
  - id: adr-0003
    title: ADR-0003 — OKF on root index
    path: .agents/adrs/ADR-0003-okf-root-index.md
---

# Agent context taxonomy

Use when you need a **single** overview. Do not load every folder.

## Entry

1. [AGENTS.md](../../AGENTS.md) — boundaries.
2. [index.md](../../index.md) — **one row**.
3. [skills/mysql2pdo/SKILL.md](../../skills/mysql2pdo/SKILL.md) — routine edits.
4. [skills/sdd/SKILL.md](../../skills/sdd/SKILL.md) — ambiguous or breaking work.
5. [knowledge/concepts/mysql-shim-method-registry.md](../concepts/mysql-shim-method-registry.md) — per-method shim detail.

## Categories

| Category | Path | Load when |
|----------|------|-----------|
| Code constraints | `rules/` | Editing `src/` |
| Behavior | `specs/shim-behavior.md` | Changing shim semantics |
| Method catalog | `knowledge/concepts/mysql-shim-method-registry.md` | Implementing or reviewing a specific `mysql_*` |
| Memory map | `knowledge/concepts/project-memory-model.md` | Unsure where to write a fact |
| Routers | `specs/PRODUCT.md`, `TECH.md`, `CAPABILITY_MAP.md` | Orienting only |
| Decisions | `adrs/` | Why structure is fixed |
| Operations | `runbooks/` | Verify before done |
| Procedures | `skills/` | How to work |
| In-flight feature | `sdd/<feature>/` | Until promoted |
| Concepts | `knowledge/concepts/` | Cross-link not in spec/ADR |

## Low-context rule

- Human-long API: [docs/API.md](../../../docs/API.md) — agents use [mysql-shim-method-registry.md](../concepts/mysql-shim-method-registry.md) + [specs/shim-behavior.md](../../specs/shim-behavior.md) unless adding symbols.
- No schema in this repo.
- Sub-indexes have no OKF frontmatter; root + `knowledge/index.md` declare `okf_version`.

## Git

No git write by agents — [ADR-0002](../../adrs/ADR-0002-agent-no-git-write.md).
