---
title: "Agent context in .agents"
description: "ROUTING: Historical baseline. Symlinks .cursor / .claude → .agents."
tags: [agents, adr]
status: accepted
date: 2026-11-03
---

# ADR-0001 — Agent context in `.agents`

## Context

Agent rules and specs must not sprawl across IDE-specific trees with duplicated content.

## Decision

- Rules, specs, ADRs, runbooks, skills, SDD workspaces, and OKF knowledge live under `.agents/`.
- `.cursor` and `.claude` symlink to `.agents/`.
- Root `AGENTS.md` symlinks to `.agents/AGENTS.md`.
- Default procedure: `.agents/skills/mysql2pdo/SKILL.md`.

## Consequences

Edit `.agents/` only. Agents start from [index.md](../index.md) and open one map row.

## Validation

Symlinks exist at repo root. `index.md` links resolve.
