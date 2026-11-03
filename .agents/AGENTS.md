# mysql2pdo — agent instructions

Open [index.md](index.md) and follow **one** row. Default skill: [skills/mysql2pdo/SKILL.md](skills/mysql2pdo/SKILL.md). Ambiguous or contract-breaking work: [skills/sdd/SKILL.md](skills/sdd/SKILL.md).

`.cursor` and `.claude` symlink to `.agents`. Edit `.agents/` only.

**alvesbeto/mysql2pdo** — Composer library. Restores global `mysql_*` on PHP 8+ via **PDO**. Source: `src/mysql2pdo.class.php`, `src/mysql2pdo.func.php`, `src/Mysql2pdo/`.

## Three-tier boundaries

### ALWAYS DO

- Consult [index.md](index.md) before a wide repo search.
- Limit changes to the user scope or reported line/error.
- Follow [rules/php.md](rules/php.md) and [specs/shim-behavior.md](specs/shim-behavior.md) when touching the shim.
- Run [runbooks/validate.md](runbooks/validate.md) before finishing.
- Update [specs/shim-behavior.md](specs/shim-behavior.md) or [specs/TECH.md](specs/TECH.md) in the same delivery when behavior or public contract changes (maintainer commits).
- When changing a public `mysql_*` symbol, update [knowledge/concepts/mysql-shim-method-registry.md](knowledge/concepts/mysql-shim-method-registry.md) in the same delivery.
- Promote structural decisions to [adrs/](adrs/index.md) per [skills/sdd/references/memory.md](skills/sdd/references/memory.md).

### ASK FIRST

- Breaking change to function signatures or semantics consumed by Webcred and other apps.
- New Composer dependency or PHP version bump.
- CI, Packagist name, or license change.
- Removing or renaming public `mysql_*` shims.

### NEVER DO

- **Do not run `git add`, `git commit`, or `git push`.** Maintainer versions only. Read-only git is OK. See [adrs/ADR-0002-agent-no-git-write.md](adrs/ADR-0002-agent-no-git-write.md).
- Drive-by fixes outside scope.
- Implement ambiguous contract changes without an approved SDD workspace.
- Disable or delete tests to force green.
- Expose secrets in chat or commits.
- Edit `vendor/`.
- Start implementation in the same turn as an unapproved PRD ([workflow](skills/sdd/references/workflow.md)).
