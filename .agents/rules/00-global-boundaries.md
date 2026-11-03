---
title: "Global agent boundaries"
description: "ROUTING: Read at the start of a broad task. Skip when mysql2pdo skill already names the scope."
tags: [boundaries, global]
scope: "**/*"
---

# Global boundaries

## ALWAYS DO

- Open [../index.md](../index.md) and only files for that row.
- Keep edits within user scope or the reported error.
- Run [../runbooks/validate.md](../runbooks/validate.md) for PHP changes.
- Record structural decisions in [../adrs/](../adrs/index.md).

## ASK FIRST

- Semver-major or breaking shim semantics.
- New Composer dependency.
- CI or Packagist metadata change.

## NEVER DO

- **`git add`, `git commit`, `git push`** — [ADR-0002](../adrs/ADR-0002-agent-no-git-write.md).
- Out-of-scope drive-by fixes.
- Ambiguous contract work without SDD approval.
- Edit `vendor/`.
