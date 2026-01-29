#!/usr/bin/env bash
set -euo pipefail

# =============================================================================
# STUDIO STARTER: Bootstrap for WordPress + Maintenance (SOP v3.0)
# =============================================================================
#
# Usage:
#   chmod +x bootstrap.sh
#   ./bootstrap.sh
#
# This script creates a new WordPress project with:
# - Router (maintenance/live modes)
# - Docker environment
# - SOP v3.0 documentation structure
# - Git-ready structure
#
# =============================================================================

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

log_info()  { echo -e "${BLUE}[INFO]${NC} $1"; }
log_ok()    { echo -e "${GREEN}[OK]${NC} $1"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

ask() {
  local var="$1"; local prompt="$2"; local def="${3:-}"
  local val=""
  if [ -n "$def" ]; then
    read -r -p "$prompt [$def]: " val || true
    val="${val:-$def}"
  else
    read -r -p "$prompt: " val || true
  fi
  printf -v "$var" "%s" "$val"
}

now() { date +"%Y-%m-%d"; }

echo ""
echo "=============================================="
echo "  STUDIO STARTER: WordPress + Maintenance"
echo "  SOP v3.0 — Monorepo Strategy"
echo "=============================================="
echo ""

# ---------- Check Docker ----------
check_docker() {
    log_info "Checking Docker..."

    if ! command -v docker &> /dev/null; then
        log_error "Docker is not installed!"
        echo "Please install Docker Desktop: https://www.docker.com/products/docker-desktop"
        exit 1
    fi

    if ! docker info &> /dev/null; then
        log_warn "Docker is not running. Attempting to start..."

        if [[ "$OSTYPE" == "darwin"* ]]; then
            open -a Docker
            echo "Waiting for Docker to start (max 60 seconds)..."
            for i in {1..60}; do
                if docker info &> /dev/null; then
                    log_ok "Docker started successfully!"
                    return 0
                fi
                sleep 1
                printf "."
            done
            echo ""
            log_error "Docker failed to start. Please start Docker Desktop manually."
            exit 1
        elif [[ "$OSTYPE" == "linux-gnu"* ]]; then
            if command -v systemctl &> /dev/null; then
                sudo systemctl start docker 2>/dev/null || true
                sleep 3
                if docker info &> /dev/null; then
                    log_ok "Docker started successfully!"
                    return 0
                fi
            fi
            log_error "Please start Docker manually: sudo systemctl start docker"
            exit 1
        else
            log_error "Please start Docker Desktop manually."
            exit 1
        fi
    fi

    log_ok "Docker is running"
}

check_docker

# ---------- Gather project info ----------
ask PROJECT "Project short name (lowercase, no spaces)" "myproject"
ask PROD_DOMAIN "Production domain (without https://)" "example.de"
ask LOCAL_PORT "Local Docker port" "8080"
ask GITHUB_USER "GitHub username" "username"

PROJECT_DIR="${PROJECT}"
LOCAL_URL="http://localhost:${LOCAL_PORT}"
PROD_URL="https://${PROD_DOMAIN}"
PMA_PORT=$((LOCAL_PORT + 1))

echo ""
log_info "Creating project: ${PROJECT_DIR}"
log_info "Production: ${PROD_URL}"
log_info "Local: ${LOCAL_URL}"
log_info "GitHub: https://github.com/${GITHUB_USER}/${PROJECT_DIR}"
echo ""

# ---------- Create folder structure ----------
mkdir -p "${PROJECT_DIR}"
cd "${PROJECT_DIR}"

# SOP v3.0 structure
mkdir -p wp/wp-content/themes
mkdir -p wp/wp-content/mu-plugins
mkdir -p wp/wp-content/plugins
mkdir -p maintenance
mkdir -p backups
mkdir -p docs

log_ok "Folder structure created"

# ---------- .gitignore ----------
cat > .gitignore << 'GITIGNORE'
# =============================================================================
# Studio Standard .gitignore (SOP v3.0)
# =============================================================================

# ----- WordPress Core (installed via Docker/Plesk, NOT Git) -----
wp/wp-admin/
wp/wp-includes/
wp/wp-*.php
wp/index.php
wp/xmlrpc.php
wp/license.txt
wp/readme.html
!wp/wp-content/

# ----- Uploads & Generated -----
wp/wp-content/uploads/
wp/wp-content/cache/
wp/wp-content/upgrade/
wp/wp-content/backups/
wp/wp-content/wflogs/
wp/wp-content/et-cache/
wp/wp-content/updraft/
wp/wp-content/ai1wm-backups/

# ----- Languages (auto-downloaded by WordPress) -----
wp/wp-content/languages/

# ----- Config (environment-specific) -----
wp/wp-config.php
wp/.htaccess

# ----- 3rd Party Plugins (install via WP Admin, NOT Git) -----
# Add your 3rd party plugins here:
# wp/wp-content/plugins/elementor/
# wp/wp-content/plugins/elementor-pro/
# wp/wp-content/plugins/wordpress-seo/
# etc.

# ----- Keep custom plugins (custom-* pattern) -----
# Example: wp/wp-content/plugins/custom-myplugin/ - WILL be in Git

# ----- Redis drop-in -----
wp/wp-content/object-cache.php

# ----- Database & Backups -----
backups/*.sql
backups/*.sql.gz
*.sql
*.sql.gz

# ----- Secrets -----
.env
.env.*
!.env.example

# ----- Logs & Temp -----
*.log
.DS_Store
Thumbs.db

# ----- IDE -----
.idea/
.vscode/
*.swp
*.swo

# ----- Archive (historical files) -----
docs/archive/
GITIGNORE

log_ok ".gitignore created"

# ---------- Router: index.php ----------
cat > index.php << 'ROUTER_PHP'
<?php
/**
 * Router: Maintenance <-> Live WordPress
 *
 * MODE = 'maintenance' -> Public sees /maintenance, admin sees WP
 * MODE = 'live'        -> Public sees WP
 *
 * IMPORTANT: This file IS under Git control.
 * To switch modes: change MODE value, commit, push -> Plesk deploys.
 */

define('MODE', 'maintenance'); // 'maintenance' | 'live'

$docRoot    = __DIR__;
$wpPath     = $docRoot . '/wp';
$wpIndex    = $wpPath . '/index.php';
$maintIndex = $docRoot . '/maintenance/index.html';

/**
 * Check if user has WordPress admin cookie
 */
function is_wp_admin_logged_in(): bool {
    foreach ($_COOKIE as $name => $value) {
        if (strpos($name, 'wordpress_logged_in_') === 0) {
            return true;
        }
    }
    return false;
}

/**
 * Serve WordPress
 */
function serve_wordpress(string $wpIndex): void {
    if (!is_file($wpIndex)) {
        http_response_code(500);
        die('WordPress not installed. Missing: ' . $wpIndex);
    }

    // Change to WP directory for correct paths
    chdir(dirname($wpIndex));
    require $wpIndex;
    exit;
}

/**
 * Serve maintenance page
 */
function serve_maintenance(string $maintIndex): void {
    if (!is_file($maintIndex)) {
        http_response_code(503);
        header('Retry-After: 3600');
        die('Site under maintenance.');
    }

    // HTTP 200 for SEO (Landing mode, not "site down")
    http_response_code(200);
    header('Content-Type: text/html; charset=utf-8');
    readfile($maintIndex);
    exit;
}

// =============================================================================
// ROUTING LOGIC
// =============================================================================

// Request to /wp/* always goes to WordPress (for admin access)
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
if (preg_match('#^/wp(/|$)#', $requestUri)) {
    serve_wordpress($wpIndex);
}

// MODE: live -> everyone sees WordPress
if (MODE === 'live') {
    serve_wordpress($wpIndex);
}

// MODE: maintenance
// Admin (logged in) -> WordPress
if (is_wp_admin_logged_in()) {
    serve_wordpress($wpIndex);
}

// Public -> maintenance page
serve_maintenance($maintIndex);
ROUTER_PHP

log_ok "Router index.php created"

# ---------- Router: .htaccess ----------
cat > .htaccess << 'HTACCESS'
# =============================================================================
# Studio Standard .htaccess (SOP v3.0 Router)
# =============================================================================

RewriteEngine On

# ----- Force HTTPS (production) -----
# Uncomment on production:
# RewriteCond %{HTTPS} off
# RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# ----- Serve existing files/directories directly -----
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]

# ----- Everything else -> Router -----
RewriteRule ^ index.php [L]
HTACCESS

log_ok ".htaccess created"

# ---------- Docker Compose ----------
cat > docker-compose.yml << DOCKER
services:
  wordpress:
    image: wordpress:latest
    container_name: ${PROJECT}-wp
    ports:
      - "${LOCAL_PORT}:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wp
      WORDPRESS_DB_PASSWORD: wp
      WORDPRESS_DB_NAME: ${PROJECT}
    volumes:
      - ./wp:/var/www/html:cached
    depends_on:
      - db
    restart: unless-stopped

  db:
    image: mysql:8.0
    container_name: ${PROJECT}-db
    environment:
      MYSQL_DATABASE: ${PROJECT}
      MYSQL_USER: wp
      MYSQL_PASSWORD: wp
      MYSQL_ROOT_PASSWORD: root
    volumes:
      - db_data:/var/lib/mysql
    restart: unless-stopped

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: ${PROJECT}-pma
    ports:
      - "${PMA_PORT}:80"
    environment:
      PMA_HOST: db
      PMA_USER: wp
      PMA_PASSWORD: wp
    depends_on:
      - db
    restart: unless-stopped

volumes:
  db_data:
DOCKER

log_ok "docker-compose.yml created (WP: ${LOCAL_PORT}, phpMyAdmin: ${PMA_PORT})"

# ---------- wp-config-local.php ----------
cat > wp-config-local.php << WPCONFIGLOCAL
<?php
/**
 * WordPress Config: LOCAL (Docker)
 * This file is NOT deployed to production.
 */

define('DB_NAME',     '${PROJECT}');
define('DB_USER',     'wp');
define('DB_PASSWORD', 'wp');
define('DB_HOST',     'db');  // Docker service name
define('DB_CHARSET',  'utf8mb4');
define('DB_COLLATE',  '');

// Salts: generate at https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'local-dev-key-change-me-1');
define('SECURE_AUTH_KEY',  'local-dev-key-change-me-2');
define('LOGGED_IN_KEY',    'local-dev-key-change-me-3');
define('NONCE_KEY',        'local-dev-key-change-me-4');
define('AUTH_SALT',        'local-dev-key-change-me-5');
define('SECURE_AUTH_SALT', 'local-dev-key-change-me-6');
define('LOGGED_IN_SALT',   'local-dev-key-change-me-7');
define('NONCE_SALT',       'local-dev-key-change-me-8');

\$table_prefix = 'wp_';

define('WP_DEBUG',     true);
define('WP_DEBUG_LOG', true);
define('SCRIPT_DEBUG', true);

// Local URL
define('WP_HOME',    '${LOCAL_URL}');
define('WP_SITEURL', '${LOCAL_URL}');

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
WPCONFIGLOCAL

log_ok "wp-config-local.php created"

# ---------- wp-config-production.php ----------
cat > wp-config-production.php << WPCONFIGPROD
<?php
/**
 * WordPress Config: PRODUCTION
 *
 * INSTRUCTIONS:
 * 1. Copy this file to hosting: /httpdocs/wp/wp-config.php
 * 2. Update DB credentials from Plesk
 * 3. Generate new salts: https://api.wordpress.org/secret-key/1.1/salt/
 */

define('DB_NAME',     'YOUR_DB_NAME');
define('DB_USER',     'YOUR_DB_USER');
define('DB_PASSWORD', 'YOUR_DB_PASSWORD');
define('DB_HOST',     'localhost');
define('DB_CHARSET',  'utf8mb4');
define('DB_COLLATE',  '');

// IMPORTANT: Generate new salts for production!
// https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'GENERATE-NEW-SALT');
define('SECURE_AUTH_KEY',  'GENERATE-NEW-SALT');
define('LOGGED_IN_KEY',    'GENERATE-NEW-SALT');
define('NONCE_KEY',        'GENERATE-NEW-SALT');
define('AUTH_SALT',        'GENERATE-NEW-SALT');
define('SECURE_AUTH_SALT', 'GENERATE-NEW-SALT');
define('LOGGED_IN_SALT',   'GENERATE-NEW-SALT');
define('NONCE_SALT',       'GENERATE-NEW-SALT');

\$table_prefix = 'wp_';

define('WP_DEBUG', false);

// Production URL
define('WP_HOME',    '${PROD_URL}');
define('WP_SITEURL', '${PROD_URL}/wp');

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
WPCONFIGPROD

log_ok "wp-config-production.php created"

# ---------- README.md (Entry Point for AI) ----------
cat > README.md << 'READMEMD'
# AI Instructions

> **Entry point for AI (Claude, Cursor, Copilot, etc.)**

---

## Read in this order

1. **`SOP.md`** — Standard operating procedure (Git, Docker, Deploy, rules)
2. **`PROJECT.md`** — Project knowledge base (status, tech stack, changelog)

---

## Project Structure

```
[project]/
├── README.md          <- You are here (entry point)
├── SOP.md             <- Standard operating procedure
├── PROJECT.md         <- Project knowledge base
├── SERVER_RULES.md    <- Hosting rules (deploy, modes)
├── index.php          <- Router
├── wp/                <- WordPress
├── maintenance/       <- Landing page
├── docker-compose.yml <- Docker config
└── docs/              <- Additional documentation
```

---

## Quick Start

```bash
# Check Docker
docker ps

# Start
cd ~/Project/[project-name]
docker-compose up -d

# Open
open http://localhost:[port]
```

---

## AI Obligations

1. Read `SOP.md` and `PROJECT.md` before working
2. Maintain `PROJECT.md` (changelog, tech stack, open questions)
3. Code comments — English only
4. Prepare detailed commit messages

---

## AI Restrictions

- `git push`, `git merge`, `git rebase` (owner only!)
- Critical actions without confirmation
- Production changes without testing

---

## STOP-RULE

**Stop and ask if:**
- Instruction is unclear
- Action may affect production
- Need push or critical change

---

**Next:** Read `SOP.md` -> `PROJECT.md`
READMEMD

log_ok "README.md created (AI entry point)"

# ---------- SOP.md (Full v3.0) ----------
cat > SOP.md << 'SOPMD'
# SOP: WordPress + Git + Plesk

**Studio Standard Operating Procedure (v3.0)**

> This document is a universal standard. Copy to any WordPress project — AI will understand how to work.

---

## 0. Foundation

**Key condition:** Only the project owner has access to the site admin panel. Clients and third parties have no access.

This allows controlled bidirectional DB synchronization when needed.

---

## 1. Philosophy

| What | Where | Priority |
|------|-------|----------|
| **Code** | GitHub | Single source of truth |
| **Content / SEO / Media** | Production | Final data |
| **Development** | Local environment | 90% of work |

> Local != production copy. Local = workshop.

---

## 2. Project Structure (Monorepo)

```
[project-name]/
├── index.php                 <- Router (MODE switching) ✅ Git
├── .htaccess                 <- Routing rules ✅ Git
├── wp/                       <- WordPress
│   ├── wp-content/
│   │   ├── themes/           <- ✅ Git (all themes)
│   │   ├── mu-plugins/       <- ✅ Git
│   │   ├── plugins/custom-*  <- ✅ Git (custom only)
│   │   ├── plugins/[others]  <- ❌ NOT Git (install via WP Admin)
│   │   ├── uploads/          <- ❌ NOT Git
│   │   └── languages/        <- ❌ NOT Git (auto-downloaded)
│   ├── wp-admin/             <- ❌ NOT Git (WP Core)
│   ├── wp-includes/          <- ❌ NOT Git (WP Core)
│   └── wp-config.php         <- ❌ NOT Git (env-specific)
├── maintenance/              <- Landing page ✅ Git
│   └── index.html
├── backups/                  <- DB dumps ❌ NOT Git
├── docs/                     <- Documentation ✅ Git
│   └── archive/              <- Historical files ❌ NOT Git
├── docker-compose.yml        <- ✅ Git
├── wp-config-local.php       <- ✅ Git (template)
├── wp-config-production.php  <- ✅ Git (template)
├── SOP.md                    <- ✅ Git (this file)
├── README.md                 <- ✅ Git (entry point for AI)
├── PROJECT.md                <- ✅ Git (knowledge base, AI maintains)
└── SERVER_RULES.md           <- ✅ Git (hosting rules)
```

---

## 3. Git — Rules

### 3.1 What goes in Git

**✅ Stored:**
- Router: `index.php`, `.htaccess`
- Themes: `wp/wp-content/themes/*` (all, including parent)
- MU-plugins: `wp/wp-content/mu-plugins/*`
- Custom plugins: `wp/wp-content/plugins/custom-*`
- Maintenance: `maintenance/*`
- Docker: `docker-compose.yml`, `php.ini`
- Config templates: `wp-config-local.php`, `wp-config-production.php`
- Documentation: `SOP.md`, `README.md`, `PROJECT.md`, `SERVER_RULES.md`

**❌ NOT stored:**
- Uploads: `wp/wp-content/uploads/`
- Languages: `wp/wp-content/languages/`
- WP Core: `wp/wp-admin/`, `wp/wp-includes/`
- Active config: `wp/wp-config.php`
- Secrets: `.env`, `.env.*`
- Backups: `backups/`, `*.sql`
- 3rd party plugins: `wp/wp-content/plugins/[plugin-name]/`

### 3.2 Plugin rules

**✅ In Git:**
- `custom-*` — any custom plugins
- Plugins created by studio from scratch
- Private plugins (not in WP repo)

**❌ NOT in Git:**
- Public plugins from wordpress.org
- Premium plugins (Elementor Pro, ACF Pro, etc.)
- Any plugins installable via WP Admin

### 3.3 Access

| Role | Allowed |
|------|---------|
| **Owner** | `git push` (via GitHub Desktop) |
| **AI** | Edit files, `git add`, `git commit` |

> AI **CANNOT** execute `git push`, `git merge`, `git rebase`.

### 3.4 Branches

- `main` — single production branch, Plesk pulls it
- `feature/*` — optional, for large changes
- `dev` — **NOT used**

---

## 4. Local Environment (Docker)

### 4.1 Standard Configuration

```yaml
# docker-compose.yml
services:
  wordpress:
    image: wordpress:latest
    ports:
      - "[port]:80"
    volumes:
      - ./wp:/var/www/html:cached
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wp
      WORDPRESS_DB_PASSWORD: wp
      WORDPRESS_DB_NAME: [project-name]

  db:
    image: mysql:8.0
    volumes:
      - db_data:/var/lib/mysql
    environment:
      MYSQL_DATABASE: [project-name]
      MYSQL_USER: wp
      MYSQL_PASSWORD: wp
      MYSQL_ROOT_PASSWORD: root

  phpmyadmin:
    image: phpmyadmin:latest
    ports:
      - "[port+1]:80"
```

### 4.2 Commands

```bash
cd ~/Project/[project-name]

# Start
docker-compose up -d

# Stop
docker-compose down

# Logs
docker-compose logs -f wordpress

# DB Backup
docker-compose exec -T db mysqldump -u wp -pwp [project-name] > backups/backup_$(date +%Y%m%d_%H%M%S).sql
```

### 4.3 Local URLs

| Service | URL |
|---------|-----|
| WordPress | `http://localhost:[port]` |
| WP Admin | `http://localhost:[port]/wp-admin` |
| phpMyAdmin | `http://localhost:[port+1]` |

---

## 5. Deploy

### 5.1 Chain

```
Local -> GitHub (main) -> Plesk manual pull -> Production
```

**Deployed from Git:**
- Router (`index.php`, `.htaccess`)
- Themes, mu-plugins, custom plugins
- Maintenance page
- Config templates, documentation

**NOT deployed from Git:**
- WordPress Core (installed via Plesk)
- Uploads (stay on hosting)
- `wp-config.php` (created manually on hosting)
- 3rd party plugins (installed via WP Admin)

### 5.2 Plesk Git Setup

**Step 1:** Plesk -> Domains -> [domain] -> Git

**Step 2:** Repository settings:
- URL: `https://github.com/[user]/[project-name].git`
- Branch: `main`
- Deploy to: `/httpdocs`
- **Mode: MANUAL** (always Manual first!)

**Step 3:** SSH Keys (if private repo):
1. Plesk -> Generate Key Pair
2. Copy Public Key
3. GitHub -> Settings -> Deploy keys -> Add
4. Allow write access: **NO**

**Step 4:** Test Pull (WITHOUT deploy):
- Plesk -> Git -> "Pull Updates"
- Check Output log

**Step 5:** First Deploy:
1. **Backup production!**
2. Plesk -> Git -> "Deploy"
3. Check site

**Step 6:** After stable operation (1-2 days):
- Mode: Manual -> Automatic (optional)

### 5.3 Post-Deploy Checklist

**After each deploy:**
1. Check homepage
2. Check key pages
3. **Elementor: Regenerate CSS** (wp-admin -> Elementor -> Tools)
4. Hard refresh browser (Ctrl+Shift+R)
5. Check on mobile
6. Check Console for JS errors

---

## 6. Mode Switching (Router)

### 6.1 Two Modes

**MODE = 'maintenance'**
| Visitor | Sees |
|---------|------|
| Public | `/maintenance/index.html` |
| Admin (logged in) | WordPress |
| Direct `/wp/*` | WordPress |

**MODE = 'live'**
| Visitor | Sees |
|---------|------|
| Everyone | WordPress |

### 6.2 How to Switch

**Via Git (recommended):**
1. Edit `index.php`:
   ```php
   define('MODE', 'live'); // or 'maintenance'
   ```
2. Commit + Push
3. Plesk -> Deploy

**On hosting (emergency):**
- Plesk File Manager -> `/httpdocs/index.php` -> Edit
- ⚠️ Will be overwritten on next deploy!

### 6.3 Default MODE

| Situation | MODE |
|-----------|------|
| New project (bootstrap) | `'maintenance'` |
| Live site migration | `'live'` |
| Development site migration | `'maintenance'` |

---

## 7. Database

### 7.1 Key Rule

**Any DB transfer = URL replacement.**

| Direction | Replacement |
|-----------|-------------|
| Production -> Local | `https://[domain]` -> `http://localhost:[port]` |
| Local -> Production | `http://localhost:[port]` -> `https://[domain]` |

### 7.2 URL Replacement

**WP-CLI (recommended):**
```bash
docker-compose run --rm wpcli search-replace \
  'http://localhost:[port]' 'https://[domain]' \
  --skip-columns=guid --all-tables \
  --export=/backups/production.sql
```

**Better Search Replace (plugin):**
1. WP Admin -> Tools -> Better Search Replace
2. Search: `http://localhost:[port]`
3. Replace: `https://[domain]`
4. Dry run first!

### 7.3 Backup

**Before any DB operations:**
```bash
# Local
docker-compose exec -T db mysqldump -u wp -pwp [db-name] > backups/backup_$(date +%Y%m%d).sql

# Production
# Plesk -> Databases -> Export
# or phpMyAdmin -> Export
```

---

## 8. Migration of Existing Projects

### 8.1 Signs of Old Project

- `wordpress/` folder instead of `wp/`
- No router files in root
- No `maintenance/` folder
- Git contains uploads/, languages/, 3rd party plugins
- Paths `~/GitHub/` instead of `~/Project/`

### 8.2 Migration Process (10 phases)

```
Phase 0: Backup & Documentation     <- Mandatory!
Phase 1: Create New Files           <- Router, templates, docs
Phase 2: Git Cleanup                <- Remove languages, plugins
Phase 3: Structure Migration        <- wordpress/ -> wp/
Phase 4: Docker Update              <- Update paths
Phase 5: Local Testing              <- Verify everything works
Phase 6: Git Finalization           <- Commit, push
Phase 7: Plesk Setup                <- Git deploy (MANUAL)
Phase 8: Production Deploy          <- Critical phase!
Phase 9: Validation                 <- Monitoring 24-48h
```

**Detailed instructions:** see `docs/MIGRATION.md`

---

## 9. Project Documentation

### 9.1 AI Creates These Files

**PROJECT.md** — project knowledge base:
- Snapshot (environments, status)
- Project State (BUILD/LANDING/LIVE)
- Tech Stack
- Changelog
- DB Sync Notes
- Open Questions

**SERVER_RULES.md** — hosting rules:
- Hosting Structure
- Server Info (IP, PHP, DB)
- Access (FTP, SSH, Plesk)
- Modes
- Go-Live Checklist
- Rollback Checklist

### 9.2 AI Updates Documentation

**When to update PROJECT.md:**
- After adding new services (Redis, CDN, etc.)
- After structural changes
- After every important change -> Changelog
- If something unclear -> Open Questions

---

## 10. AI Rules

### 10.1 AI Obligations

1. **Read before working:** `README.md` -> `SOP.md` -> `PROJECT.md`
2. **Maintain PROJECT.md:** update Changelog, Tech Stack, Open Questions
3. **Code comments:** English only
4. **Prepare commit messages:** detailed, with change descriptions

### 10.2 AI Restrictions

- `git push`, `git merge`, `git rebase`
- Critical actions without owner confirmation
- Adding forbidden files to Git
- Making changes without documentation

### 10.3 STOP-RULE

**Stop and ask owner if:**
- Instruction is unclear or ambiguous
- Missing data for execution
- Action may affect production
- Need Git push
- Critical change (DB import, MODE change, wp-config.php)

### 10.4 Critical Actions (confirmation only)

- DB import to production
- MODE change to `'live'` (Go-Live)
- Changes to `wp-config.php` on hosting
- Force push
- Production file deletion

---

## 11. Troubleshooting

### White screen after deploy

1. Check `wp/wp-config.php` paths
2. Enable debug: `define('WP_DEBUG', true);`
3. Check `wp/wp-content/debug.log`

### Broken styles

1. **Elementor:** wp-admin -> Elementor -> Tools -> Regenerate CSS
2. Hard refresh: Ctrl+Shift+R
3. Clear browser cache

### Docker volume issues

1. `docker volume ls`
2. `docker-compose down && docker-compose up -d`
3. Restore DB from `backups/`

### 404 after deploy

1. Check `.htaccess` in `/httpdocs`
2. Check `WP_SITEURL` in `wp-config.php`
3. Plesk -> Apache settings

---

## 12. Quick Reference

### Session Start

```bash
# 1. Check Docker
docker ps

# 2. Start if needed
cd ~/Project/[project-name] && docker-compose up -d

# 3. Open site
open http://localhost:[port]
```

### Commit Workflow

```bash
# 1. Check changes
git status
git diff

# 2. Stage
git add [files]

# 3. Commit (AI executes)
git commit -m "type: description

Details of what changed

Co-Authored-By: Claude <noreply@anthropic.com>"

# 4. Push (owner only — GitHub Desktop)
git push origin main
```

### Deploy Workflow

```bash
# 1. Local testing complete
# 2. Owner pushes to GitHub
# 3. Plesk -> Git -> Pull Updates
# 4. Plesk -> Git -> Deploy (MANUAL)
# 5. Check production site
# 6. Elementor -> Regenerate CSS
# 7. Update PROJECT.md
```

---

## Version

| Version | Date | Changes |
|---------|------|---------|
| 1.x | — | 2 repositories |
| 2.0 | 2025-01 | Monorepo, router in Git |
| 2.1 | 2026-01 | Modular structure |
| **3.0** | **2026-01** | **Universal SOP, single file** |

---

**This document is a universal standard.**

Copy `SOP.md` to a new project -> AI reads it -> understands how to work.

Replace placeholders:
- `[project-name]` — project name
- `[domain]` — domain (example.com)
- `[port]` — Docker port (8080)
- `[user]` — GitHub username
SOPMD

log_ok "SOP.md created (v3.0 full)"

# ---------- PROJECT.md ----------
cat > PROJECT.md << PROJECTMD
# PROJECT: ${PROJECT}

## Snapshot — $(now)

| Environment | URL | Status |
|-------------|-----|--------|
| Production | ${PROD_URL} | 🔴 Not deployed |
| Local | ${LOCAL_URL} | 🟡 Ready to start |

---

## Project State

**Current: BUILD**

- [ ] BUILD — local development
- [ ] LANDING — maintenance page live, WP in development
- [ ] LIVE — WordPress public

---

## About

**Website:** [Description]
**Client:** [Client name]
**Language:** [DE/EN/etc.]

---

## Tech Stack

- **WordPress:** Latest (via Docker/Plesk)
- **PHP:** 8.x
- **Database:** MySQL 8.0 (local) / MariaDB (production)
- **Theme:** TBD
- **Page Builder:** TBD
- **Key Plugins:** TBD

---

## URLs

| Environment | URL | Port |
|-------------|-----|------|
| Local Site | ${LOCAL_URL} | ${LOCAL_PORT} |
| Local Admin | ${LOCAL_URL}/wp-admin | ${LOCAL_PORT} |
| phpMyAdmin | http://localhost:${PMA_PORT} | ${PMA_PORT} |
| Production | ${PROD_URL} | — |

---

## Database

**Local (Docker):**
- Host: \`db\`
- Database: \`${PROJECT}\`
- User: \`wp\`
- Password: \`wp\`
- Prefix: \`wp_\`

**Production:** See Plesk panel

---

## Hosting

**Provider:** TBD
**Domain:** ${PROD_DOMAIN}
**SSL:** TBD

**Access:**
- FTP/FTPS: TBD
- SSH: TBD
- Plesk Panel: TBD

**Deploy:**
- Method: Plesk Git (MANUAL mode)
- Repository: https://github.com/${GITHUB_USER}/${PROJECT}
- Branch: main
- Deploy to: /httpdocs

---

## Changelog

| Date | Change | By |
|------|--------|----|
| $(now) | Bootstrap project created (SOP v3.0) | AI |

---

## DB Sync Notes

| Date | Direction | Reason | Notes |
|------|-----------|--------|-------|
| — | — | — | No sync yet |

---

## Open Questions

1. Theme selection?
2. Required plugins?
3. Hosting provider details?

---

## Special Notes

*No special notes yet.*
PROJECTMD

log_ok "PROJECT.md created"

# ---------- SERVER_RULES.md ----------
cat > SERVER_RULES.md << SERVERRULES
# SERVER_RULES: ${PROJECT}

## Hosting Structure

\`\`\`
/httpdocs/                    <- Document root (Plesk)
├── index.php                 <- Router (MODE switching)
├── .htaccess                 <- Routing rules
├── wp/                       <- WordPress installation
│   ├── wp-admin/
│   ├── wp-includes/
│   ├── wp-content/
│   │   ├── themes/
│   │   ├── plugins/
│   │   └── uploads/
│   └── wp-config.php         <- Production config
└── maintenance/
    └── index.html            <- Maintenance page
\`\`\`

---

## Server Info

| Parameter | Value |
|-----------|-------|
| **Provider** | TBD |
| **IP** | TBD |
| **Domain** | ${PROD_DOMAIN} |
| **SSL** | TBD |
| **PHP** | 8.x |
| **Database** | TBD |

---

## Access

| Method | Status | Notes |
|--------|--------|-------|
| **FTP/FTPS** | TBD | |
| **SSH** | TBD | |
| **Plesk Panel** | TBD | |
| **phpMyAdmin** | TBD | |

---

## Git Deploy

| Setting | Value |
|---------|-------|
| **Repository** | https://github.com/${GITHUB_USER}/${PROJECT} |
| **Branch** | main |
| **Deploy to** | /httpdocs |
| **Mode** | MANUAL |

**Deploy workflow:**
1. Owner pushes to GitHub (main branch)
2. Plesk -> Git -> Pull Updates
3. Plesk -> Git -> Deploy
4. Verify site
5. Elementor -> Regenerate CSS (if needed)

---

## Modes

### MODE = 'maintenance' (default)

| Visitor | Sees |
|---------|------|
| Public | \`/maintenance/index.html\` |
| Admin (logged in) | WordPress |
| Direct \`/wp/*\` | WordPress |

### MODE = 'live'

| Visitor | Sees |
|---------|------|
| Everyone | WordPress site |

**How to switch:**
1. Edit \`index.php\` line 12: \`define('MODE', 'live');\`
2. Commit + Push
3. Plesk -> Deploy

---

## Go-Live Checklist

- [ ] Content ready
- [ ] SEO configured
- [ ] SSL active
- [ ] MODE = 'live'
- [ ] Tested on desktop
- [ ] Tested on mobile
- [ ] Elementor CSS regenerated

---

## Rollback Checklist

**If something breaks after deploy:**

1. [ ] Switch MODE = 'maintenance'
2. [ ] Identify issue (check \`/wp/wp-content/debug.log\`)
3. [ ] Git rollback if needed
4. [ ] DB restore if needed
5. [ ] Verify site works
6. [ ] Switch MODE = 'live'

---

**Last updated:** $(now)
SERVERRULES

log_ok "SERVER_RULES.md created"

# ---------- Maintenance placeholder ----------
cat > maintenance/index.html << 'MAINTHTML'
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 2rem;
        }
        .container { max-width: 600px; }
        h1 { font-size: 2.5rem; margin-bottom: 1rem; }
        p { font-size: 1.2rem; opacity: 0.9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Coming Soon</h1>
        <p>We're working on something amazing. Stay tuned!</p>
    </div>
</body>
</html>
MAINTHTML

log_ok "maintenance/index.html placeholder created"

# ---------- Final output ----------
echo ""
echo "=============================================="
echo -e "${GREEN}  PROJECT CREATED SUCCESSFULLY (SOP v3.0)${NC}"
echo "=============================================="
echo ""
echo "Project folder: $(pwd)"
echo ""

# ---------- Start Docker ----------
read -r -p "Start Docker containers now? [Y/n]: " START_DOCKER
START_DOCKER="${START_DOCKER:-Y}"

if [[ "$START_DOCKER" =~ ^[Yy]$ ]]; then
    log_info "Starting Docker containers..."

    if docker compose up -d; then
        log_ok "Containers started!"
        echo ""
        echo "=============================================="
        echo -e "${GREEN}  LOCAL ENVIRONMENT READY${NC}"
        echo "=============================================="
        echo ""
        echo "  WordPress:  ${LOCAL_URL}"
        echo "  phpMyAdmin: http://localhost:${PMA_PORT}"
        echo ""
        echo "  Note: WordPress needs ~30 seconds for first start."
        echo "  Then visit ${LOCAL_URL} to complete installation."
        echo ""
    else
        log_error "Failed to start containers. Try manually:"
        echo "  cd $(pwd)"
        echo "  docker compose up -d"
    fi
else
    echo ""
    echo "NEXT STEPS:"
    echo ""
    echo "1) Start Docker:"
    echo "   cd $(pwd)"
    echo "   docker compose up -d"
    echo ""
fi

echo "2) Initialize Git:"
echo "   git init"
echo "   git add ."
echo "   git commit -m \"chore: bootstrap project (SOP v3.0)\""
echo ""
echo "3) Create GitHub repo and push:"
echo "   gh repo create ${PROJECT} --private --source=. --push"
echo "   # OR use GitHub Desktop"
echo ""
echo "4) On hosting (Plesk):"
echo "   a) Create domain/subdomain"
echo "   b) Install WordPress via Plesk (creates DB)"
echo "   c) Copy wp-config-production.php -> /httpdocs/wp/wp-config.php"
echo "   d) Configure Git deployment -> /httpdocs (MANUAL mode)"
echo ""
echo "5) AI reads: README.md -> SOP.md -> PROJECT.md"
echo ""
echo "=============================================="
