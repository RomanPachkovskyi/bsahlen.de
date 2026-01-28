# bsahlen.de

WordPress corporate website for BSahlen Financial Consulting.

---

## ⚠️ Migration in Progress

**Status:** Migrating from legacy structure to SOP v2.0 (Monorepo)

**Documents:**
- `MIGRATION_AUDIT.md` - Detailed analysis
- `MIGRATION_PLAN.md` - Step-by-step migration guide
- `MIGRATION.md` - General migration guide for old projects

**Current phase:** Phase 1 (Creating new files)

---

## Quick Start (Local Development)

```bash
# Navigate to project
cd ~/Project/bsahlen.de

# Start Docker containers
docker-compose up -d

# Wait ~30 seconds for WordPress to start

# Open in browser
open http://localhost:8080

# phpMyAdmin (database management)
open http://localhost:8081
```

## Project URLs

| Environment | URL | Status |
|-------------|-----|--------|
| Local | http://localhost:8080 | 🟢 Development |
| Production | https://bsahlen.de | 🟢 LIVE |

## Documentation

**Project docs:**
- `PROJECT.md` - Project status, tech stack, changelog
- `SERVER_RULES.md` - Hosting structure, deployment rules
- `SOP.md` - Studio workflow (quick reference)
- `SOP_v2.md` - Full SOP standard (reference)
- `CLAUDE.md` - AI assistant instructions

**Migration docs:**
- `MIGRATION_AUDIT.md` - Current vs target analysis
- `MIGRATION_PLAN.md` - Step-by-step migration guide
- `MIGRATION.md` - General migration guide

## Structure (SOP v2.0)

```
/
├── index.php              ← Router (MODE switching)
├── .htaccess              ← Routing rules
├── wp/                    ← WordPress
│   └── wp-content/
│       ├── themes/        ← Custom themes (in Git)
│       ├── mu-plugins/    ← Must-use plugins (in Git)
│       ├── plugins/       ← 3rd party (NOT in Git)
│       ├── uploads/       ← Media (NOT in Git)
│       └── languages/     ← Auto-downloaded (NOT in Git)
├── maintenance/           ← Landing page
├── docs/                  ← Technical documentation
│   ├── migration/         ← Migration docs
│   ├── scripts/           ← Utility scripts
│   └── archive/           ← Old/deprecated files
├── backups/               ← Database dumps (NOT in Git)
├── docker-compose.yml     ← Local environment
├── wp-config-local.php    ← Local template (in Git)
├── wp-config-production.php ← Production template (in Git)
├── CLAUDE.md              ← AI instructions
├── PROJECT.md             ← Knowledge base ⭐
├── SERVER_RULES.md        ← Hosting rules
├── SOP.md                 ← Quick reference
└── README.md              ← This file
```

## Mode Switching

Edit `index.php`:
```php
define('MODE', 'live'); // or 'maintenance'
```

**Current:** `MODE = 'live'` (site is public)

## Common Commands

```bash
# Docker management
docker-compose up -d          # Start containers
docker-compose down           # Stop containers
docker-compose restart        # Restart all
docker-compose logs -f        # View logs

# Database backup
docker-compose exec -T db mysqldump -u wp -pwp bsahlen > backups/backup_$(date +%Y%m%d).sql

# Check container status
docker ps
```

## Tech Stack

- **WordPress:** Latest (PHP 8.2)
- **Theme:** Finovate (parent) + bsahlen (child)
- **Page Builder:** Elementor Pro
- **Database:** MySQL 8.0 (local) / MariaDB 10.11 (production)
- **Hosting:** Plesk
- **SSL:** Let's Encrypt

## Deploy Workflow

**Current (Legacy):**
```
Local → Manual FTPS upload → Production
```

**Target (SOP v2.0):**
```
Local → GitHub (main) → Plesk Git (manual deploy) → Production
```

## Development

1. Make changes locally
2. Test thoroughly on http://localhost:8080
3. Commit changes with descriptive message
4. Owner pushes to GitHub
5. Deploy to production (manual via Plesk Git)

## Key Features

- Custom mega menu with hover effects
- Child theme for customizations
- Elementor-based pages
- SEO optimized (Yoast)
- Responsive design
- SSL secured

## Support

**Repository:** https://github.com/RomanPachkovskyi/bsahlen.de
**Issues:** https://github.com/RomanPachkovskyi/bsahlen.de/issues

---

**Last updated:** 2026-01-28
