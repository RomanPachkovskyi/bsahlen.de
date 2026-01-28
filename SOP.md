# SOP: WordPress + Git + Plesk (Studio Standard)

> Full SOP: see `SOP_v2.md`. This is a project-specific quick reference.

## Source of Truth

| What | Where |
|------|-------|
| Code | GitHub (main branch) |
| Content/Media | Production (Plesk hosting) |
| Development | Local (Docker, 90% of work) |

## Git Rules

- **Push/Merge:** Owner only (via GitHub Desktop)
- **AI can:** Edit files locally, prepare commits, create branches
- **AI cannot:** Push, merge, rebase

## Never in Git

- `wp/wp-content/uploads/`
- `wp/wp-content/languages/`
- `wp/wp-content/plugins/*` (3rd party, except custom-*)
- `wp/wp-config.php` (active config)
- Database dumps
- Secrets (.env, credentials)

## Always in Git

- `wp/wp-content/themes/*` (all themes, including parent)
- `wp/wp-content/mu-plugins/*` (if any)
- `maintenance/` (landing page)
- Router files (`index.php`, `.htaccess` in root)
- Config templates (`wp-config-local.php`, `wp-config-production.php`)
- Documentation (PROJECT.md, SERVER_RULES.md, etc.)

## Deploy Flow

```
Local → GitHub (main) → Plesk manual pull → Production
```

**Deploy process:**
1. Work locally
2. Test thoroughly
3. Commit changes
4. Owner pushes to GitHub
5. Plesk → Git → Pull Updates → Deploy (manual action)

## Mode Switching

Edit `index.php` in root:
```php
define('MODE', 'live'); // or 'maintenance'
```

Commit → Push → Plesk Deploy → Done.

**Current for bsahlen.de:** MODE = 'live'

## Local Commands

```bash
# Start environment
docker-compose up -d

# Stop
docker-compose down

# View logs
docker-compose logs -f wordpress

# Access site
open http://localhost:8080

# phpMyAdmin
open http://localhost:8081

# Backup database
docker-compose exec -T db mysqldump -u wp -pwp bsahlen > backups/backup_$(date +%Y%m%d).sql
```

## AI Rules

1. Follow SOP strictly
2. Maintain PROJECT.md and SERVER_RULES.md
3. Code comments: English only
4. **STOP-RULE:** If unclear or risky → ask owner first
5. Never push to Git (owner does this)
6. Always test locally before preparing commit

## Project-Specific Notes

- **Child theme:** `bsahlen` (keep in Git)
- **Parent theme:** `finovate` (keep in Git, sometimes needed)
- **Custom mega menu:** CSS + JS in child theme
- **Elementor:** After structure changes, regenerate CSS
- **Database:** Always use URL replacement when syncing
- **Deploy:** MANUAL mode in Plesk (owner-controlled)

## Critical Actions (Need Owner Approval)

- Database import to production
- Changing MODE to/from 'maintenance'
- Any wp-config.php changes on production
- Force push to Git
- Structural changes to production

## Migration Notes

**Status:** In progress (migrating to SOP v2.0 structure)
**See:** MIGRATION_PLAN.md for detailed steps
**Backup:** Always available via `pre-migration-backup` tag

---

**Version:** 2.0 (adapted for bsahlen.de)
**Last updated:** 2026-01-28
