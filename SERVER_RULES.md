# SERVER_RULES: Maintenance ↔ WordPress

## ⚠️ MIGRATION IN PROGRESS

**Current state:** Migrating from old structure to SOP v2.0

**Old structure (current production):**
- Path: `/httpdocs` (WordPress root)
- No router
- Deploy: manual FTPS via lftp
- WordPress in root directory

**Target structure (after migration):**
- Path: `/httpdocs` (monorepo root)
- Router: `index.php` + `.htaccess` in root
- Deploy: Git auto-deploy (MANUAL mode)
- WordPress in `/httpdocs/wp/` subdirectory

---

## Hosting Structure (Target - SOP v2.0)

```
/httpdocs/                    ← Git deploys HERE (root)
├── index.php                 ← Router (from Git)
├── .htaccess                 ← Routing rules (from Git)
├── wp/                       ← WordPress
│   ├── wp-admin/             ← WP Core (from Plesk installer)
│   ├── wp-includes/          ← WP Core (from Plesk installer)
│   ├── wp-content/
│   │   ├── themes/           ← From Git (Finovate + bsahlen)
│   │   ├── mu-plugins/       ← From Git (if any)
│   │   ├── plugins/          ← Installed via WP Admin (NOT in Git)
│   │   ├── uploads/          ← Media (NOT in Git)
│   │   └── languages/        ← Auto-downloaded (NOT in Git)
│   ├── wp-config.php         ← Created manually on hosting
│   └── index.php             ← WP Core
├── maintenance/              ← From Git
│   └── index.html            ← Landing page (placeholder)
└── [other git files]         ← docker-compose.yml, docs, etc.
```

## Modes

### MODE = 'live' (current for bsahlen.de)

| Visitor | Sees |
|---------|------|
| Everyone | WordPress |

**Use case:** Site is public and fully operational.

### MODE = 'maintenance' (not used, but available)

| Visitor | Sees |
|---------|------|
| Public | `/maintenance/index.html` |
| Admin (logged in) | WordPress |
| Direct `/wp/*` requests | WordPress |

**Use case:** Site under development, but landing page is public and indexed.

## How to Switch Modes

### Option A: Via Git (recommended)

1. Edit `index.php` locally:
   ```php
   define('MODE', 'live'); // or 'maintenance'
   ```
2. Commit & Push
3. Plesk auto-deploys → mode switched

### Option B: Direct on hosting (emergency only)

1. Plesk File Manager → `/httpdocs/index.php`
2. Edit MODE value
3. Save

⚠️ **Warning:** Direct edits will be overwritten on next Git deploy!

## Checklist: Go Live (Already Done for bsahlen.de)

- [x] All content ready in WordPress
- [x] SEO configured (titles, descriptions, sitemap)
- [x] SSL certificate active
- [x] MODE set to `'live'` in `index.php`
- [x] Public access working
- [x] Site is LIVE

## Checklist: Enable Maintenance (rollback scenario)

- [ ] Change `MODE` to `'maintenance'` in `index.php`
- [ ] Commit & Push
- [ ] Verify public sees maintenance page
- [ ] Verify admin can still access `/wp/wp-admin`

## Production Server Info

**Hosting:** Plesk
**Domain:** bsahlen.de
**IP:** 81.209.248.242
**SSL:** Let's Encrypt (automatic renewal)
**PHP:** 8.2 (configured via Plesk)
**Database:** MariaDB 10.11.13

**Access:**
- **FTP/FTPS:** Available (credentials in .env)
- **SSH:** ❌ Disabled by host
- **Plesk Panel:** Available (web interface)

## Git Deployment (Target)

**Repository:** https://github.com/RomanPachkovskyi/bsahlen.de
**Branch:** main
**Deploy to:** /httpdocs
**Mode:** MANUAL (owner-controlled)

**Deploy process:**
1. Push to GitHub main branch
2. Plesk → Git → "Pull Updates"
3. Review changes
4. Click "Deploy" (manual action)

**What gets deployed:**
- Router files (index.php, .htaccess)
- WordPress themes (from `/wp/wp-content/themes/`)
- MU plugins (if any)
- Maintenance page
- Documentation

**What stays on server (NOT overwritten):**
- `/wp/wp-content/uploads/` (media files)
- `/wp/wp-config.php` (database config)
- `/wp/wp-content/plugins/` (3rd party plugins)
- `/wp/wp-content/languages/` (auto-downloaded)

## File Permissions

After deploy, ensure:
- `/wp/wp-content/uploads/` is writable (755 or 775)
- `/wp/wp-config.php` is protected (644 or 640)

## Backup Strategy

**Before any production deploy:**
1. Backup database via Plesk
2. Backup `/httpdocs` via FTP (or Plesk backup tool)
3. Keep at least 2 recent backups

**Local backups:**
- Database dumps in `/backups/` (excluded from Git)
- Pre-migration backup: `PRE_MIGRATION_20260128_*.sql`

## Emergency Rollback

If deploy breaks production:

1. **Via Git:**
   ```bash
   git checkout backup-pre-migration
   git push origin main --force
   ```
   Then Plesk → Git → Pull & Deploy

2. **Via Plesk File Manager:**
   - Restore from backup directory
   - Restore database from SQL dump

3. **Contact support:** If Plesk/FTP unavailable

## Post-Migration Notes

After successful migration to SOP v2.0:

- ✅ Router active (`index.php` + `.htaccess`)
- ✅ WordPress in `/wp/` subdirectory
- ✅ Git auto-deploy configured (MANUAL mode)
- ✅ All URLs updated in database
- ✅ Elementor CSS regenerated
- ✅ Site tested and verified working

**Last updated:** 2026-01-28
