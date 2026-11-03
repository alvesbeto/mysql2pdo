---
okf_version: "0.2"
---

# Map

Read this page only. Open **one** row for the task. Human docs: [README.md](../README.md). API listing: [docs/API.md](../docs/API.md).

| Need | Open |
|------|------|
| Entry and limits | [AGENTS.md](AGENTS.md) |
| Routine code change | [skill mysql2pdo](skills/mysql2pdo/SKILL.md) |
| Ambiguous or breaking shim change | [skill sdd](skills/sdd/SKILL.md) |
| Global boundaries | [rules/00-global-boundaries.md](rules/00-global-boundaries.md) |
| PHP / package rules | [rules/index.md](rules/index.md) |
| Product behavior (router) | [specs/PRODUCT.md](specs/PRODUCT.md) |
| Stack and layout (router) | [specs/TECH.md](specs/TECH.md) |
| Module map (single library) | [specs/CAPABILITY_MAP.md](specs/CAPABILITY_MAP.md) |
| Shim invariants agents must preserve | [specs/shim-behavior.md](specs/shim-behavior.md) |
| Every `mysql_*` method (agent registry) | [knowledge/concepts/mysql-shim-method-registry.md](knowledge/concepts/mysql-shim-method-registry.md) |
| Memory layers (OKF / SDD) | [knowledge/concepts/project-memory-model.md](knowledge/concepts/project-memory-model.md) |
| Spec index | [specs/index.md](specs/index.md) |
| Accepted decisions | [adrs/index.md](adrs/index.md) |
| Validate / release checks | [runbooks/index.md](runbooks/index.md) |
| Cross-cutting concepts | [knowledge/index.md](knowledge/index.md) |
| Feature in progress | [sdd/index.md](sdd/index.md) |

`.cursor` and `.claude` are symlinks to `.agents`. Edit `.agents/` only. Agent docs use `.md`. Skill catalog: [skills/index.md](skills/index.md).

Do **not** load the whole `.agents/` tree. Do **not** paste `src/*.php` into specs.
