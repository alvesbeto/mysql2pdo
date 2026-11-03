---
title: "PHP and shim editing"
description: "ROUTING: Read when changing src/mysql2pdo.class.php or src/mysql2pdo.func.php."
tags: [php, pdo]
scope: "src/**"
---

# PHP rules

- PHP **8.0+** per `composer.json`.
- **All PDO usage stays in `src/`** ([ADR-0006](../adrs/ADR-0006-pdo-driver-in-src.md)).
- Preserve global class name `mysql2pdo` and procedural `mysql_*` names for drop-in compatibility.
- Match existing style: no drive-by reformat; surgical diffs only.
- User-facing comments and docs in **English** ([ADR-0005](../adrs/ADR-0005-english-user-facing-docs.md)).
- Before adding a function, grep this repo — add a `Mysql2pdo\Shim\Mysql*` class and a one-line delegate on global `mysql2pdo`.
- After behavior change, update [../specs/shim-behavior.md](../specs/shim-behavior.md) and [../../docs/API.md](../../docs/API.md).

Verify: [../runbooks/validate.md](../runbooks/validate.md).
