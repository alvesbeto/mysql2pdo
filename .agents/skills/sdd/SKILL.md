---
name: sdd
description: Spec-driven workflow for ambiguous or contract-breaking mysql2pdo changes (PDO shim). Load only the reference for the active phase.
---

# SDD

Use when the change is **ambiguous**, **risky**, or **breaks consumers** (Webcred, semver). Routine fixes follow [../mysql2pdo/SKILL.md](../mysql2pdo/SKILL.md).

**Stack context:** public API is global `mysql_*` + facade `mysql2pdo`; implementation is **PDO** in `Mysql2pdo\Driver\PdoExecutor`. Do not reintroduce a second driver or `mysqli_*` in `src/`.

1. Read [references/workflow.md](references/workflow.md) for the current phase only.
2. Workspace: `.agents/sdd/<feature-kebab>/` — register in [../../sdd/index.md](../../sdd/index.md).
3. After merge, promote per [references/memory.md](references/memory.md).
4. Do **not** copy workspace PRDs into `specs/`; promote **behavior** to [../../specs/shim-behavior.md](../../specs/shim-behavior.md) or [../../specs/TECH.md](../../specs/TECH.md).

Templates: [references/templates.md](references/templates.md).
