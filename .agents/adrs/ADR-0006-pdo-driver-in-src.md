---
title: "PDO driver in src"
description: "ROUTING: Read when adding database access or splitting packages."
tags: [pdo, adr]
status: accepted
date: 2026-11-03
---

# ADR-0006 — PDO driver under `src/`

## Context

The mysql2pdo package implements the legacy `mysql_*` API on PDO while keeping procedural function names unchanged.

## Decision

All PDO usage and shim link/result handles (`Mysql2pdo\Driver\*`) stay in `src/`. Consumers continue to call `mysql_*` only.

## Consequences

Reviews treat direct PDO usage outside `src/` as a boundary violation. Link validity for app code: `$link instanceof \Mysql2pdo\Driver\ShimLink`.

Supersedes [ADR-0004](ADR-0004-legacy-driver-superseded.md) for this repository.
