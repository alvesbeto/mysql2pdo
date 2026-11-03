---
title: "Product — current behavior"
description: "ROUTING: Read to learn what the library guarantees today. Open shim-behavior before coding. Not a code-style guide."
tags: [product, behavior]
---

# Product — current behavior

Composer package **alvesbeto/mysql2pdo**: legacy **`mysql_*`** API for PHP 8+ — **PDO delegation only**, no connection policy (charset, time zone, SQL modes) imposed by the library.

## Product principle

The installer’s application owns connection settings. This package registers `mysql_*` globals and maps them to PDO via `PdoExecutor`; it does not call `set_charset`, `SET NAMES`, or similar unless the app does via the same public APIs (`mysql_set_charset`, `mysql_query`, etc.).

## Consumers

| Consumer | Integration |
|----------|-------------|
| Legacy PHP apps | `composer require alvesbeto/mysql2pdo` + `vendor/autoload.php` |
| Monorepo / path install | Local path repository; replaces copied shim files in the app |

## Canonical behavior

Do not duplicate long prose here.

| Topic | Spec |
|-------|------|
| Shim invariants (charset, close, deprecations) | [shim-behavior.md](shim-behavior.md) |
| Every `mysql_*` method (agent registry) | [../knowledge/concepts/mysql-shim-method-registry.md](../knowledge/concepts/mysql-shim-method-registry.md) |
| Function list (human reference) | [../../docs/API.md](../../docs/API.md) |
| Install and examples | [../../README.md](../../README.md) |

## Global acceptance

- Behavior change updates [shim-behavior.md](shim-behavior.md) (and [CHANGELOG.md](../../CHANGELOG.md)) in the same PR.
- Repeatable structural choice becomes an ADR.
- Studies without shipped code do not live under `specs/`.

Large or ambiguous features: [../skills/sdd/SKILL.md](../skills/sdd/SKILL.md).
