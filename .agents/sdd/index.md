# SDD workspaces

Temporal execution. Shipped behavior promotes to [../specs/](../specs/index.md), [../knowledge/](../knowledge/index.md), or [../adrs/](../adrs/index.md) per [../knowledge/concepts/memory-promotion.md](../knowledge/concepts/memory-promotion.md).

| Feature | State | Notes |
|---------|--------|--------|
| [pdo-driver-migration](pdo-driver-migration/shipped.md) | **Shipped** (1.0.0) | mysqli → PDO; `Mysql2pdo` namespace; [ADR-0006](../adrs/ADR-0006-pdo-driver-in-src.md) |
| [compat-pdo-peers](compat-pdo-peers/shipped.md) | **Shipped** (1.0.0) | Peer-library compat: close/SSL/new_link/result/info/helpers |

When opening a workspace, add a row and link `prd.md`.

Durable memory (method registry, shim contract, log): [../knowledge/index.md](../knowledge/index.md).
