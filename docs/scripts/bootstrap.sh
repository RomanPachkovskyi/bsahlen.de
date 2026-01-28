#!/usr/bin/env bash
set -euo pipefail

# =============================================================================
# STUDIO STARTER: Bootstrap for WordPress + Maintenance (Single Monorepo)
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

now() { date +"%Y-%m-%d %H:%M"; }

echo ""
echo "=============================================="
echo "  STUDIO STARTER: WordPress + Maintenance"
echo "  Single Monorepo Strategy"
echo "=============================================="
echo ""

# ---------- Check Docker ----------
check_docker() {
    log_info "Checking Docker..."
    
    # Check if Docker is installed
    if ! command -v docker &> /dev/null; then
        log_error "Docker is not installed!"
        echo "Please install Docker Desktop: https://www.docker.com/products/docker-desktop"
        exit 1
    fi
    
    # Check if Docker daemon is running
    if ! docker info &> /dev/null; then
        log_warn "Docker is not running. Attempting to start..."
        
        # Try to start Docker (works on Mac/Linux with Docker Desktop)
        if [[ "$OSTYPE" == "darwin"* ]]; then
            # macOS
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
            # Linux - try systemctl
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
            # Windows or other
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

PROJECT_DIR="${PROJECT}"
LOCAL_URL="http://localhost:${LOCAL_PORT}"
PROD_URL="https://${PROD_DOMAIN}"

echo ""
log_info "Creating project: ${PROJECT_DIR}"
log_info "Production: ${PROD_URL}"
log_info "Local: ${LOCAL_URL}"
echo ""

# ---------- Create folder structure ----------
mkdir -p "${PROJECT_DIR}"
cd "${PROJECT_DIR}"

# Root structure
mkdir -p wp/wp-content/themes
mkdir -p wp/wp-content/mu-plugins
mkdir -p wp/wp-content/plugins
mkdir -p maintenance
mkdir -p _db

log_ok "Folder structure created"

# ---------- .gitignore ----------
cat > .gitignore << 'GITIGNORE'
# =============================================================================
# Studio Standard .gitignore (Monorepo: WP + Maintenance)
# =============================================================================

# ----- WordPress uploads & generated -----
wp/wp-content/uploads/
wp/wp-content/cache/
wp/wp-content/upgrade/
wp/wp-content/backups/
wp/wp-content/ai1wm-backups/
wp/wp-content/wflogs/
wp/wp-content/et-cache/
wp/wp-content/updraft/

# ----- WordPress core (installed via Docker/Plesk, not Git) -----
wp/wp-admin/
wp/wp-includes/
wp/wp-*.php
wp/index.php
wp/xmlrpc.php
wp/license.txt
wp/readme.html
!wp/wp-content/

# ----- Config files (environment-specific) -----
wp/wp-config.php
wp/.htaccess

# ----- Database -----
_db/
*.sql
*.sql.gz

# ----- Maintenance: React/Node -----
maintenance/node_modules/
maintenance/.next/
maintenance/out/

# ----- Build artifacts (keep only final build in Git if needed) -----
# Uncomment if you commit built maintenance:
# !maintenance/build/

# ----- Logs & temp -----
*.log
.DS_Store
Thumbs.db

# ----- Secrets -----
.env
.env.*
!.env.example

# ----- IDE -----
.idea/
.vscode/
*.swp
*.swo
GITIGNORE

log_ok ".gitignore created"

# ---------- Router: index.php ----------
cat > index.php << 'ROUTER_PHP'
<?php
/**
 * Router: Maintenance ↔ Live WordPress
 * 
 * MODE = 'maintenance' → Public sees /maintenance, admin sees WP
 * MODE = 'live'        → Public sees WP
 * 
 * IMPORTANT: This file IS under Git control.
 * To switch modes: change MODE value, commit, push → Plesk deploys automatically.
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

// MODE: live → everyone sees WordPress
if (MODE === 'live') {
    serve_wordpress($wpIndex);
}

// MODE: maintenance
// Admin (logged in) → WordPress
if (is_wp_admin_logged_in()) {
    serve_wordpress($wpIndex);
}

// Public → maintenance page
serve_maintenance($maintIndex);
ROUTER_PHP

log_ok "Router index.php created"

# ---------- Router: .htaccess ----------
cat > .htaccess << 'HTACCESS'
# =============================================================================
# Studio Standard .htaccess (Monorepo Router)
# =============================================================================

RewriteEngine On

# ----- Force HTTPS (production) -----
# Uncomment on production:
# RewriteCond %{HTTPS} off
# RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# ----- Serve existing files/directories directly -----
# Static assets in /maintenance and /wp are served as-is
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]

# ----- Everything else → Router -----
RewriteRule ^ index.php [L]
HTACCESS

log_ok ".htaccess created"

# ---------- Docker Compose ----------
cat > docker-compose.yml << DOCKER
version: '3.8'

services:
  wordpress:
    image: wordpress:latest
    container_name: ${PROJECT}-wp
    ports:
      - "${LOCAL_PORT}:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
    volumes:
      # Mount entire wp folder for development
      - ./wp/wp-content:/var/www/html/wp-content
      # WordPress config
      - ./wp-config-local.php:/var/www/html/wp-config.php:ro
    depends_on:
      - db
    restart: unless-stopped

  db:
    image: mysql:8.0
    container_name: ${PROJECT}-db
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
      MYSQL_ROOT_PASSWORD: rootpassword
    volumes:
      - db_data:/var/lib/mysql
      # Import folder for DB dumps
      - ./_db:/docker-entrypoint-initdb.d:ro
    restart: unless-stopped

  # Optional: phpMyAdmin for DB management
  phpmyadmin:
    image: phpmyadmin:latest
    container_name: ${PROJECT}-pma
    ports:
      - "$((LOCAL_PORT + 1)):80"
    environment:
      PMA_HOST: db
      PMA_USER: wordpress
      PMA_PASSWORD: wordpress
    depends_on:
      - db
    restart: unless-stopped

volumes:
  db_data:
DOCKER

log_ok "docker-compose.yml created (port: ${LOCAL_PORT}, phpMyAdmin: $((LOCAL_PORT + 1)))"

# ---------- wp-config-local.php ----------
cat > wp-config-local.php << 'WPCONFIGLOCAL'
<?php
/**
 * WordPress Config: LOCAL (Docker)
 * This file is NOT deployed to production.
 */

define('DB_NAME',     'wordpress');
define('DB_USER',     'wordpress');
define('DB_PASSWORD', 'wordpress');
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

$table_prefix = 'wp_';

define('WP_DEBUG',     true);
define('WP_DEBUG_LOG', true);
define('SCRIPT_DEBUG', true);

// Local URL
define('WP_HOME',    'http://localhost:8080');
define('WP_SITEURL', 'http://localhost:8080');

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
WPCONFIGLOCAL

# Update port in wp-config
sed -i "s|localhost:8080|localhost:${LOCAL_PORT}|g" wp-config-local.php

log_ok "wp-config-local.php created"

# ---------- wp-config-production.php (template) ----------
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

log_ok "wp-config-production.php created (template)"

# ---------- PROJECT.md ----------
cat > PROJECT.md << PROJECTMD
# PROJECT: ${PROJECT}

## Snapshot — $(now)

| Environment | URL | Status |
|-------------|-----|--------|
| Production  | ${PROD_URL} | 🔴 Not deployed |
| Local       | ${LOCAL_URL} | 🟡 Ready to start |

## Project State

**Current: BUILD**

- [ ] BUILD — local development
- [ ] LANDING — maintenance page live, WP in development
- [ ] LIVE — WordPress public

## Goals

- **What we build:** 
- **Target audience:** 
- **Key pages:** 

## Tech Stack

- WordPress (latest)
- Theme: TBD
- Key plugins: TBD
- Maintenance: React/HTML (TBD)

## Open Questions (blockers)

1. 
2. 

## Changelog

| Date | Change | By |
|------|--------|----|
| $(now) | Bootstrap created | AI |

## DB Sync Notes

| Date | Direction | Reason | Notes |
|------|-----------|--------|-------|
| — | — | — | No sync yet |

## Deploy Notes

**Goes to Git:**
- \`/wp/wp-content/themes/*\`
- \`/wp/wp-content/mu-plugins/*\`
- \`/wp/wp-content/plugins/custom-*\`
- \`/maintenance/*\` (build)
- \`/index.php\` (router)
- \`/.htaccess\`

**Never in Git:**
- \`/wp/wp-content/uploads/\`
- \`/wp/wp-config.php\`
- \`/_db/\`
- Database dumps
PROJECTMD

log_ok "PROJECT.md created"

# ---------- SERVER_RULES.md ----------
cat > SERVER_RULES.md << 'SERVERRULES'
# SERVER_RULES: Maintenance ↔ WordPress

## Hosting Structure (Plesk)

```
/httpdocs/                    ← Git deploys HERE (root)
├── index.php                 ← Router (from Git)
├── .htaccess                 ← Routing rules (from Git)
├── wp/                       ← WordPress
│   ├── wp-admin/             ← WP Core (from Plesk installer)
│   ├── wp-includes/          ← WP Core (from Plesk installer)
│   ├── wp-content/
│   │   ├── themes/           ← From Git
│   │   ├── mu-plugins/       ← From Git
│   │   ├── plugins/          ← Installed via WP Admin
│   │   └── uploads/          ← Media (NOT in Git)
│   ├── wp-config.php         ← Created manually on hosting
│   └── index.php             ← WP Core
└── maintenance/              ← From Git
    └── index.html            ← Landing page
```

## Modes

### MODE = 'maintenance' (default for new projects)

| Visitor | Sees |
|---------|------|
| Public | `/maintenance/index.html` |
| Admin (logged in) | WordPress |
| Direct `/wp/*` requests | WordPress |

**Use case:** Site under development, but landing page is public and indexed.

### MODE = 'live'

| Visitor | Sees |
|---------|------|
| Everyone | WordPress |

**Use case:** Site launched, WordPress is public.

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

## Checklist: Go Live

- [ ] All content ready in WordPress
- [ ] SEO configured (titles, descriptions, sitemap)
- [ ] SSL certificate active
- [ ] Change `MODE` to `'live'` in `index.php`
- [ ] Commit & Push
- [ ] Clear caches (WP + CDN if any)
- [ ] Test public access
- [ ] Update PROJECT.md state to LIVE

## Checklist: Enable Maintenance (rollback)

- [ ] Change `MODE` to `'maintenance'` in `index.php`
- [ ] Commit & Push
- [ ] Verify public sees maintenance page
- [ ] Verify admin can still access `/wp/wp-admin`
SERVERRULES

log_ok "SERVER_RULES.md created"

# ---------- SOP.md (short version for repo) ----------
cat > SOP.md << 'SOPMD'
# SOP: WordPress + Git + Plesk (Studio Standard)

> Full SOP: see studio documentation. This is a quick reference.

## Source of Truth

| What | Where |
|------|-------|
| Code | GitHub |
| Content/Media | Production |
| Development | Local (90%) |

## Git Rules

- **Push/Merge:** Owner only (via GitHub Desktop)
- **AI can:** Edit files locally, prepare commits
- **AI cannot:** Push, merge, rebase

## Never in Git

- `wp-content/uploads/`
- `wp-config.php`
- Database dumps
- Secrets, keys

## Deploy Flow

```
Local → GitHub (main) → Plesk auto-pull → Production
```

## Mode Switching

Edit `index.php`:
```php
define('MODE', 'maintenance'); // or 'live'
```

Commit → Push → Done.

## AI Rules

1. Follow SOP strictly
2. Maintain PROJECT.md and SERVER_RULES.md
3. Code comments: English only
4. **STOP-RULE:** If unclear or risky → ask owner first
SOPMD

log_ok "SOP.md created"

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

# ---------- CLAUDE.md (AI instructions) ----------
cat > CLAUDE.md << 'CLAUDEMD'
# CLAUDE.md — AI Instructions

## Project Type
WordPress + Maintenance (Single Monorepo)

## Key Files to Read First
1. `PROJECT.md` — current state, domains, blockers
2. `SERVER_RULES.md` — hosting structure, mode switching
3. `SOP.md` — workflow rules

## Structure

```
/
├── index.php              ← Router (MODE switching)
├── .htaccess              ← Routing rules
├── wp/                    ← WordPress
│   └── wp-content/
│       ├── themes/        ← Edit here
│       ├── mu-plugins/    ← Edit here
│       └── plugins/       ← Custom only
├── maintenance/           ← Landing page
├── docker-compose.yml     ← Local environment
├── wp-config-local.php    ← Local DB config
└── wp-config-production.php ← Template for hosting
```

## Commands (Local)

```bash
# Start local environment
docker compose up -d

# Stop
docker compose down

# View logs
docker compose logs -f wordpress

# Access WP CLI (if needed)
docker compose exec wordpress wp --info
```

## What I Can Do

- Edit theme files in `/wp/wp-content/themes/`
- Edit mu-plugins in `/wp/wp-content/mu-plugins/`
- Edit maintenance page in `/maintenance/`
- Update PROJECT.md, SERVER_RULES.md
- Prepare commit messages
- Change MODE in `index.php` (with owner confirmation)

## What I Cannot Do

- `git push`, `git merge`, `git rebase`
- Edit `wp-config.php` on production
- Import DB to production
- Final Go-Live switch (need owner confirmation)

## STOP-RULE

If instruction is unclear, data is missing, or action affects production:
**STOP and ASK owner first.**
CLAUDEMD

log_ok "CLAUDE.md created"

# ---------- README.md ----------
cat > README.md << READMEMD
# ${PROJECT}

WordPress project with maintenance mode support.

## Quick Start (Local)

\`\`\`bash
# Start Docker
docker compose up -d

# Open in browser
open ${LOCAL_URL}

# phpMyAdmin
open http://localhost:$((LOCAL_PORT + 1))
\`\`\`

## Project URLs

| Environment | URL |
|-------------|-----|
| Local | ${LOCAL_URL} |
| Production | ${PROD_URL} |

## Documentation

- \`PROJECT.md\` — project status, changelog
- \`SERVER_RULES.md\` — hosting setup, mode switching
- \`SOP.md\` — workflow rules
- \`CLAUDE.md\` — AI instructions

## Mode Switching

Edit \`index.php\`:
\`\`\`php
define('MODE', 'maintenance'); // or 'live'
\`\`\`

Commit & push to deploy.
READMEMD

log_ok "README.md created"

# ---------- Final output ----------
echo ""
echo "=============================================="
echo -e "${GREEN}  ✓ PROJECT CREATED SUCCESSFULLY${NC}"
echo "=============================================="
echo ""
echo "Project folder: $(pwd)"
echo ""

# ---------- Ask to start Docker Compose ----------
read -r -p "Start Docker containers now? [Y/n]: " START_DOCKER
START_DOCKER="${START_DOCKER:-Y}"

if [[ "$START_DOCKER" =~ ^[Yy]$ ]]; then
    log_info "Starting Docker containers..."
    
    if docker compose up -d; then
        log_ok "Containers started!"
        echo ""
        echo "=============================================="
        echo -e "${GREEN}  🚀 LOCAL ENVIRONMENT READY${NC}"
        echo "=============================================="
        echo ""
        echo "  WordPress:  ${LOCAL_URL}"
        echo "  phpMyAdmin: http://localhost:$((LOCAL_PORT + 1))"
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

echo "2) Initialize Git (you do this):"
echo "   git init"
echo "   git add ."
echo "   git commit -m \"chore: bootstrap project\""
echo ""
echo "3) Create GitHub repo and push:"
echo "   gh repo create ${PROJECT} --private --source=. --push"
echo "   # OR use GitHub Desktop"
echo ""
echo "4) On hosting (Plesk):"
echo "   a) Create domain/subdomain"
echo "   b) Install WordPress via Plesk (creates DB)"
echo "   c) Move WP to /httpdocs/wp/ subfolder"
echo "   d) Copy wp-config-production.php → /httpdocs/wp/wp-config.php"
echo "   e) Configure Git deployment → /httpdocs"
echo ""
echo "5) To switch modes:"
echo "   Edit index.php → MODE='live' or MODE='maintenance'"
echo "   Commit & Push"
echo ""
echo "=============================================="
