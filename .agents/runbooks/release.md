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

**How Packagist versions work (not “branch main” vs “dev-main”):**

| Composer version | Git source |
|------------------|------------|
| **`1.0.4`, `1.0.3`, …** | Annotated tags `v1.0.4`, `v1.0.3`, … on **`main`** |
| **`dev-main`** | Tip of GitHub branch **`main`** |

Stable installs use **`composer require alvesbeto/mysql2pdo:^1.0`**, not `dev-main`. If Packagist lists **only** old `dev-main` (e.g. commit `54c0a4a`), the index never refreshed after your tags—**not** because you pushed to the wrong branch.

After push or new tag, Packagist should show **`1.0.x`** and current **`dev-main`**. If only stale `dev-main` appears:

1. Log in on Packagist → [alvesbeto/mysql2pdo](https://packagist.org/packages/alvesbeto/mysql2pdo) → **Update** (manual sync — works without API token).
2. Confirm **GitHub hook** on the repo (`Settings → Webhooks`):
   - URL: `https://packagist.org/api/github?username=alvesbeto`
   - Content type: `application/json`
   - Secret: must match **Packagist profile → GitHub Secret** (not the API token). Deliveries returning **403** mean the secret is wrong — paste the Packagist secret into the GitHub webhook and save, then **Redeliver** a recent push event.
3. **GitHub Actions (recommended backup):** add repo secret **`PACKAGIST_TOKEN`** (Packagist profile → Show API Token). Workflow [`.github/workflows/packagist.yml`](../../.github/workflows/packagist.yml) runs on push to `main` and on tags `v*`.
4. Optional one-off API refresh:

```bash
curl -XPOST -H "Authorization: Bearer $PACKAGIST_TOKEN" \
  'https://packagist.org/api/update-package?username=alvesbeto'
```

## Consumer install (stable apps)

```bash
composer require alvesbeto/mysql2pdo:^1.0
```

Requires Packagist index to include the **`1.0.0`** (or newer) stable version—not `dev-main` only.
