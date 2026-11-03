---
type: Concept
title: Project memory model
description: Where specs, OKF knowledge, SDD workspaces, and git divide responsibility in mysql2pdo.
generated:
  at: 2026-11-03T16:45:00-03:00
  by: process:agent
sources:
  - id: adr-0003
    title: ADR-0003 — OKF root index
    path: .agents/adrs/ADR-0003-okf-root-index.md
  - id: shim-behavior
    title: Shim behavior contract
    path: .agents/specs/shim-behavior.md
  - id: adr-0006
    title: ADR-0006 — PDO driver in src
    path: .agents/adrs/ADR-0006-pdo-driver-in-src.md
---

# Project memory model

Agents must not load all of `.agents/` or paste `src/*.php` into specs. Use this map.

## Layers

| Layer | Location | Holds |
|-------|----------|--------|
| **Entry** | [index.md](../../index.md), [AGENTS.md](../../AGENTS.md) | One-row routing, boundaries |
| **Behavior contract** | [specs/shim-behavior.md](../../specs/shim-behavior.md) | Invariants agents must not break |
| **Routers** | [PRODUCT.md](../../specs/PRODUCT.md), [TECH.md](../../specs/TECH.md), [CAPABILITY_MAP.md](../../specs/CAPABILITY_MAP.md) | Product, stack, modules |
| **Per-method memory** | [mysql-shim-method-registry.md](mysql-shim-method-registry.md) | Every `mysql_*` symbol + class helpers |
| **OKF concepts** | [concepts/](.) | Cross-links and workflows |
| **Decisions** | [adrs/](../../adrs/index.md) | Structural choices (PDO: [ADR-0006](../../adrs/ADR-0006-pdo-driver-in-src.md)) |
| **Changelog of agent context** | [log.md](../log.md) | Durable deliveries, newest first |
| **Human API prose** | [docs/API.md](../../../docs/API.md) | Maintainer-facing reference |
| **In-flight SDD** | [sdd/](../../sdd/index.md) | PRD/plan/tasks until promoted |

## Spec-driven workflow (this repo)

| Phase | mysql2pdo artifact |
|-------|---------------------|
| Specify | [sdd/\<feature\>/](../../sdd/index.md) PRD or spec draft — **no code until approved** |
| Plan / tasks | Same workspace under `sdd/` |
| Implement | `src/` (PDO in `Mysql2pdo/Driver/`) + update [shim-behavior.md](../../specs/shim-behavior.md), [method registry](mysql-shim-method-registry.md), [docs/API.md](../../../docs/API.md); run `composer test` when DB behavior changes |
| Promote | [memory-promotion.md](memory-promotion.md) → specs + [knowledge/](.) + [log.md](../log.md) |

Ambiguous or breaking shim work: [skills/sdd/SKILL.md](../../skills/sdd/SKILL.md) before coding.

## Exploring the shim

Use [mysql-shim-method-registry.md](mysql-shim-method-registry.md) and targeted `Read`/`Grep` on `src/` — not a separate graph tool in this repo.

After changing `src/mysql2pdo*.php` or `src/Mysql2pdo/**`, update the method registry line anchors in the same delivery.
