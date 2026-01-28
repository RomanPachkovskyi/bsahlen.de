# AI INSTRUCTIONS: bsahlen.de

> **Universal entry point for ALL AI assistants (Claude, Cursor, Copilot, etc.)**

---

## 🎯 Start Here

**When you first connect to this project, follow these steps:**

### 1. Identify Project Type

Check these files:
```bash
# If these exist → SOP v2.0 project (migrated or new)
- PROJECT.md (main knowledge base)
- wp/ folder (not wordpress/)
- index.php + .htaccess in root (router)

# If these exist → Legacy project (needs migration)
- wordpress/ folder
- No PROJECT.md
- No router files
```

### 2. Read Project Knowledge

**Primary source:** `PROJECT.md`
- Current state (BUILD/LANDING/LIVE)
- Tech stack
- Open questions
- Changelog
- Deploy notes

**Secondary sources:**
- `SERVER_RULES.md` - hosting rules
- `SOP.md` - workflow quick reference
- `README.md` - quick start

### 3. Check Project Path

**Current standard:** `~/Project/[project-name]`
**Old format:** `~/GitHub/[project-name]` (deprecated)

⚠️ If you see `~/GitHub/` anywhere - update to `~/Project/`

---

## 📋 Core Principles

### Documentation

**Single source of truth:** `PROJECT.md`
- Update after every significant change
- Keep Changelog section current
- Mark completed tasks with [x]

**Style:**
- Headers: `#` top, `##` sections, `###` sub-sections
- Code blocks: ` ```bash ` or ` ```php `
- Tables for comparisons
- Lists: `-` for bullets, `1.` for steps
- **Bold** for important, `code` for commands/files

**Code comments:**
- ALWAYS in English
- Variables/functions in English
- User-facing text in project language (DE for bsahlen.de)

### Git Rules

**AI can:**
- Edit files locally
- Create commits with descriptive messages
- Prepare commit messages for owner review

**AI cannot:**
- Execute `git push`
- Execute `git merge`, `git rebase`
- Make force push
- Delete branches

**Owner does:**
- All git push operations (via GitHub Desktop or CLI)
- Branch management
- Production deploys

### File Organization

**In root (important files only):**
- PROJECT.md (knowledge base)
- SERVER_RULES.md (hosting rules)
- SOP.md (quick reference)
- README.md (user-facing)
- CLAUDE.md (this file)

**In docs/ (technical files):**
- `docs/migration/` - migration documentation
- `docs/scripts/` - utility scripts
- `docs/archive/` - old/deprecated files

**Never commit:**
- `.env` files
- `backups/` folder
- Database dumps (*.sql)
- Uploads (`wp/wp-content/uploads/`)
- Languages (`wp/wp-content/languages/`)
- 3rd party plugins (except parent themes if needed)

---

## 🚀 Common Tasks

### Start Local Environment

```bash
cd ~/Project/bsahlen.de

# Check Docker status
docker ps

# Start containers (if not running)
docker-compose up -d

# Wait ~30 seconds
sleep 30

# Test site
open http://localhost:8080
```

### Backup Database

```bash
# Before any major changes
docker-compose exec -T db mysqldump -u wp -pwp bsahlen > \
  backups/backup_$(date +%Y%m%d_%H%M%S).sql
```

### Check Git Status

```bash
git status

# Check for secrets before commit
git status | grep -E '\.env|credentials|password'

# See what's staged
git diff --cached --name-only
```

### Deploy Workflow (Current)

**After SOP v2.0 migration:**
```
Local → GitHub (main) → Plesk Git (MANUAL deploy) → Production
```

**Pre-migration (legacy):**
```
Local → Manual FTPS upload → Production
```

See `PROJECT.md` → Deploy Notes for current method.

---

## 📁 Project Structure (SOP v2.0)

```
~/Project/bsahlen.de/
├── index.php              ← Router (MODE switching)
├── .htaccess              ← Routing rules
├── wp/                    ← WordPress
│   ├── wp-config.php      ← Local config (not in Git)
│   └── wp-content/
│       ├── themes/
│       │   ├── finovate/  ← Parent theme (IN Git)
│       │   └── bsahlen/   ← Child theme (IN Git)
│       ├── plugins/       ← Only custom-* in Git
│       ├── uploads/       ← NOT in Git
│       └── languages/     ← NOT in Git
├── maintenance/           ← Landing page (placeholder)
│   └── index.html
├── backups/               ← NOT in Git
├── docs/                  ← Technical documentation
│   ├── migration/
│   ├── scripts/
│   └── archive/
├── docker-compose.yml     ← Docker config
├── wp-config-local.php    ← Local template (IN Git)
├── wp-config-production.php ← Prod template (IN Git)
├── PROJECT.md             ← Main knowledge base ⭐
├── SERVER_RULES.md        ← Hosting rules
├── SOP.md                 ← Quick reference
├── CLAUDE.md              ← This file
└── README.md              ← User-facing docs
```

---

## 🛠️ Project-Specific Info

### Tech Stack

- **WordPress:** Latest (PHP 8.2)
- **Database:** MySQL 8.0 (local) / MariaDB 10.11 (production)
- **Theme:** Finovate (parent) + bsahlen (child)
- **Page Builder:** Elementor Pro
- **Hosting:** Plesk (SSH disabled)
- **SSL:** Let's Encrypt

### URLs

- **Local:** http://localhost:8080
- **Local Admin:** http://localhost:8080/wp-admin
- **phpMyAdmin:** http://localhost:8081
- **Production:** https://bsahlen.de

### Database

**Local (Docker):**
- Host: `db`
- Name: `bsahlen`
- User: `wp`
- Password: `wp`
- Prefix: `XutfWi7d_`

**Production (Plesk):**
- See `.env` file (not in Git)

### Special Features

**Mega Menu System:**
- Custom overlay with blur effect
- Active state indicators
- See child theme: `wp/wp-content/themes/bsahlen/`

**Elementor:**
- After structure changes: Regenerate CSS!
- wp-admin → Elementor → Tools → Regenerate Files

---

## ⚠️ Critical Rules

### Before ANY Production Changes

1. ✅ Read `PROJECT.md` → Project State
2. ✅ Check for Open Questions
3. ✅ Backup production (files + DB)
4. ✅ Test locally first
5. ✅ Know rollback plan

### STOP Rules

**If ANY of these apply, STOP and ASK owner:**

- ❌ Instruction is unclear or ambiguous
- ❌ Missing required data
- ❌ Action might affect production
- ❌ Need to push to Git
- ❌ Need to import DB to production
- ❌ Change MODE (maintenance ↔ live)
- ❌ Modify wp-config.php on production

### Never Do Without Permission

- Push to Git (owner only)
- Deploy to production (owner decision)
- Delete files from production
- Modify production database directly
- Change production wp-config.php
- Force push or rewrite Git history

---

## 🔄 Workflow for AI

### 1. Session Start

```bash
# Read project status
cat PROJECT.md | grep -A 10 "Project State"

# Check Docker
docker ps | grep bsahlen

# Start if needed
cd ~/Project/bsahlen.de && docker-compose up -d
```

### 2. Make Changes

- Edit files as requested
- Test locally (http://localhost:8080)
- Document changes (mental notes for commit message)

### 3. Commit Preparation

```bash
# Check what changed
git status

# Review changes
git diff

# Stage files
git add [files]

# Prepare commit message (for owner to review)
git commit -m "type: description

Detailed explanation of what and why

Co-Authored-By: Claude Sonnet 4.5 <noreply@anthropic.com>"
```

### 4. Update Documentation

- Update `PROJECT.md` → Changelog
- Update `PROJECT.md` → Tech Stack (if changed)
- Mark completed tasks with [x]

### 5. Inform Owner

Tell owner:
- What was done
- What files changed
- Commit message prepared
- Next steps (push? deploy?)

---

## 📚 Documentation Reference

### For This Project

**Must read first:**
- `PROJECT.md` - current state, knowledge base
- `SERVER_RULES.md` - hosting setup, deploy rules

**Reference:**
- `SOP.md` - workflow quick guide
- `README.md` - quick start for users
- `docs/migration/` - migration history (if applicable)

### For Studio Standards

**In `docs/`:**
- `docs/SOP_v2.md` - full SOP standard
- `docs/SOP_IMPROVEMENTS.md` - lessons learned
- `docs/scripts/bootstrap.sh` - new project creator

### For Migration

**If migrating legacy project:**
- `docs/migration/MIGRATION.md` - general guide
- `docs/migration/MIGRATION_PLAN.md` - step-by-step
- `docs/migration/MIGRATION_AUDIT.md` - analysis template

---

## 🆘 Troubleshooting

### "Site shows white screen"

1. Check Docker logs: `docker-compose logs wordpress`
2. Check wp-config.php paths
3. Check `wp/wp-content/debug.log`

### "Styles broken after changes"

1. Regenerate Elementor CSS
2. Hard refresh: Cmd+Shift+R (Mac) or Ctrl+Shift+R (Win)
3. Clear browser cache

### "Docker volume issues"

1. Check volumes: `docker volume ls`
2. Restart: `docker-compose down && docker-compose up -d`
3. Restore DB from backups/ if needed

### "Git conflicts"

1. Check status: `git status`
2. If unsure: STOP and ask owner
3. Never force push without permission

---

## 📞 Support

**GitHub:** https://github.com/RomanPachkovskyi/bsahlen.de
**Issues:** For production problems
**Owner:** Roman Pachkovskyi

---

## 🎓 Learning Resources

### WordPress + Docker

- Docker commands: `docker-compose --help`
- WP-CLI: `docker-compose run --rm wpcli --help`

### Git Workflow

- Studio standard: see `docs/SOP_v2.md`
- Commit conventions: semantic commit messages

### Plesk Hosting

- Git deploy: see `SERVER_RULES.md`
- No SSH access (use FTP if needed)

---

**Version:** 2.0 (SOP v2.0 compliant)
**Last updated:** 2026-01-28
**Project status:** See `PROJECT.md` for current state

---

## 🚨 Emergency Contacts

**If production is down:**
1. Check `docs/migration/MIGRATION_PLAN.md` Phase 9 (Rollback)
2. Restore from backup (files + DB)
3. Contact owner immediately

**For critical decisions:**
- Always ask owner first
- Document everything in PROJECT.md
- Keep communication clear and concise
