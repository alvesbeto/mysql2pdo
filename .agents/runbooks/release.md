# Release and Packagist

## Git remote (SSH)

Prefer SSH so `git push` and tags work without HTTPS credentials:

```bash
git remote set-url origin git@github.com:alvesbeto/mysql2pdo.git
git remote -v
```

## Cut a stable release

1. Ensure `CHANGELOG.md` has the version section.
2. Run [validate.md](validate.md) (`composer validate --strict`, `composer test`).
3. Commit on `main`.
4. Annotated tag (semver):

```bash
git tag -a v1.0.0 -m "Release 1.0.0"
git push origin main
git push origin v1.0.0
```

Force-retag only when a tag pointed at the wrong commit **before** anyone installed it from Packagist:

```bash
git tag -f -a v1.0.0 -m "Release 1.0.0"
git push origin v1.0.0 --force
```

## Packagist sync

Package: [packagist.org/packages/alvesbeto/mysql2pdo](https://packagist.org/packages/alvesbeto/mysql2pdo)

**Policy:** development happens on Git branch **`main`**, but Packagist publishes **stable tags only**. Root [`.packagist.yml`](../../.packagist.yml) excludes branch `main`, so Composer does **not** offer **`dev-main`**. Consumers install **`^1.0`** (or a pinned **`1.x.y`**).

| Composer version | Git source |
|------------------|------------|
| **`1.0.5`, `1.0.4`, …** | Annotated tags `v1.0.5`, `v1.0.4`, … on **`main`** |

Release checklist:

1. Merge or commit on **`main`**, push **`main`**.
2. Create and push tag **`vX.Y.Z`** (this triggers Packagist refresh via webhook and [`.github/workflows/packagist.yml`](../../.github/workflows/packagist.yml)).
3. If versions lag, Packagist → **Update** (manual sync).

Webhook troubleshooting (403 on delivery):

1. GitHub repo → **Settings → Webhooks** → URL `https://packagist.org/api/github?username=alvesbeto`
2. Secret must match **Packagist profile → GitHub Secret** (not the API token).
3. Optional backup: GitHub secret **`PACKAGIST_TOKEN`** for the workflow above.

```bash
curl -XPOST -H 'Content-Type: application/json' \
  -H "Authorization: Bearer alvesbeto:$PACKAGIST_TOKEN" \
  -d '{"repository":"https://github.com/alvesbeto/mysql2pdo"}' \
  'https://packagist.org/api/update-package'
```

## Consumer install (stable apps)

```bash
composer require alvesbeto/mysql2pdo:^1.0
```
