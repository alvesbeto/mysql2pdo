---
title: "Capability map"
description: "ROUTING: Read before a change that spans packaging + API + docs. Skip for a single-file bugfix inside src/."
tags: [capability]
---

# Capability map

Single-module library. No bounded contexts beyond the shim.

| Module id | Responsibility | Depends on |
|-----------|----------------|------------|
| `mysql-shim` | `mysql_*` compatibility over PDO | — |
| `agent-tooling` | OKF bundle, SDD workspaces, method registry in knowledge | reads `mysql-shim` via `src/` |

**Build order:** `mysql-shim` → refresh `agent-tooling` memory when public API or invariants change.

**Dependency direction:** Consumers depend on this package. This package depends on PHP + ext-pdo only. It must not depend on any particular application codebase.

Cross-cutting work (Packagist publish, semver major, new public API surface) → open SDD workspace under [../sdd/](../sdd/index.md).
