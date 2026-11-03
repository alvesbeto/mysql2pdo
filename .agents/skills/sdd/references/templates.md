# SDD templates

## prd.md (minimal)

```markdown
# PRD: <title>

## Objective
## Scope / out of scope
## Consumer impact (Webcred, semver, ShimLink vs legacy handles)
## Success criteria (testable — include `composer test` when DB behavior changes)
## Boundaries (always / ask / never — PDO only in src per ADR-0006)
## Open questions
```

## techspec.md (minimal)

```markdown
# TechSpec: <title>

## Components and files (Driver / Shim / facade / tests)
## Implementation order
## Contracts (signatures, semantics)
## Reuse search (PdoExecutor, existing shims)
## Risks
## Out of scope
```

## tasks.md

```markdown
- [ ] Task …
  - Acceptance: …
  - Verify: composer test / composer validate --strict / …
  - Files: …
```

Promote behavior to [../../specs/shim-behavior.md](../../specs/shim-behavior.md) when the feature ships.
