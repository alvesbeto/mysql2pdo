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

After push or new tag, Packagist should show **`1.0.0`** (stable) and **`dev-main`**. If only `dev-main` appears:

1. Log in on Packagist → package page → **Update** (manual sync).
2. Confirm **GitHub hook** on the repo: webhook URL  
   `https://packagist.org/api/github?username=alvesbeto`  
   events: **push** (and tag create if available). Secret from Packagist profile → API / GitHub integration.
3. Optional API refresh (Packagist profile → **Show API Token**):

```bash
curl -XPOST -H "Authorization: Bearer $PACKAGIST_TOKEN" \
  'https://packagist.org/api/update-package?username=alvesbeto'
```

## Consumer install (Webcred / stable apps)

```bash
composer require alvesbeto/mysql2pdo:^1.0
```

Requires Packagist index to include the **`1.0.0`** (or newer) stable version—not `dev-main` only.
