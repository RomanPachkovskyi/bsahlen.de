# PROJECT: bsahlen.de

## Snapshot — 2026-01-29

| Environment | URL | Status |
|-------------|-----|--------|
| Production | https://bsahlen.de | 🟢 LIVE |
| Local | http://localhost:8080 | 🟢 Running |

---

## Project State

**Current: LIVE**

Site is public and operational.

---

## About

**Website:** Corporate website for financial consulting
**Client:** BSahlen Financial Consulting
**Language:** German (DE)

---

## Tech Stack

- **WordPress:** Latest (via Plesk)
- **PHP:** 8.2
- **Database:** MariaDB 10.11.13 (production) / MySQL 8.0 (local)
- **Cache:** Redis 7 (local)
- **Theme:** Finovate (parent) + bsahlen (child)
- **Page Builder:** Elementor Pro
- **Key Plugins:**
  - Elementor + Elementor Pro
  - Yoast SEO
  - SVG Support
  - Vamtam Elementor Integration
  - Redis Object Cache (local)

---

## URLs

| Environment | URL | Port |
|-------------|-----|------|
| Local Site | http://localhost:8080 | 8080 |
| Local Admin | http://localhost:8080/wp-admin | 8080 |
| phpMyAdmin | http://localhost:8081 | 8081 |
| Production | https://bsahlen.de | — |

---

## Database

**Local (Docker):**
- Host: `db`
- Database: `bsahlen`
- User: `wp`
- Password: `wp`
- Prefix: `XutfWi7d_`

**Production:** See `.env` file (not in Git)

---

## Hosting

**Provider:** Plesk
**Domain:** bsahlen.de
**IP:** 81.209.248.242
**SSL:** Let's Encrypt (auto-renewal)

**Access:**
- FTP/FTPS: Available
- SSH: Disabled by host
- Plesk Panel: Available

**Deploy:**
- Method: Plesk Git (MANUAL mode)
- Repository: https://github.com/RomanPachkovskyi/bsahlen.de
- Branch: main
- Deploy to: /httpdocs

---

## Current Features

- Custom mega menu with overlay and blur effect
- Child theme for customizations
- Elementor-based page builder
- SEO configured (Yoast)
- Responsive design
- SSL active

---

## Changelog

| Date | Change | By |
|------|--------|----|
| 2026-01-29 | DB optimization: removed revisions/trash, cleared transients/cache; new production-ready DB export after cleanup | AI |
| 2026-01-29 | Fixed missing header logo (used correct custom_logo ID in theme template) | AI |
| 2026-01-29 | Aggressive media cleanup: removed uploads/2025/02 and /2025/03 + DB references | AI |
| 2026-01-29 | Prepared production-ready DB export (search-replace localhost → https://bsahlen.de) | AI |
| 2026-01-29 | Added workspace/ folder for file exchange (AI/owner), updated SOP, bootstrap | AI |
| 2026-01-29 | Added .htaccess legacy rewrites for /wp-content, /wp-includes, /wp-admin; documented /wp migration fixes | AI |
| 2026-01-29 | Updated bootstrap.sh to SOP v3.0 (removed CLAUDE.md, full SOP, backups/) | AI |
| 2026-01-29 | Added SERVER_RULES.md (SOP v3.0 compliance) | AI |
| 2026-01-29 | Simplified documentation: SOP v3.0 (single file), removed CLAUDE.md, cleaned docs/ | AI |
| 2026-01-28 | Created modular SOP v2.1, added Redis cache, reorganized project structure | AI |
| 2026-01-28 | Completed SOP v2.0 migration (Phases 0-6): wordpress→wp, Git cleanup, router added | AI |
| 2026-01-21 | Deployed child theme with custom mega menu to production | AI |
| 2026-01-20 | Setup local development environment with Docker | AI |
| 2026-01-19 | Initial local setup, imported production DB (105MB) | AI |

---

## DB Sync Notes

| Date | Direction | Reason | Notes |
|------|-----------|--------|-------|
| 2026-01-29 | Local → Production | Production-ready DB export after DB optimization | 122 replacements, file `backups/production_ready_20260129_163303.sql` (pending import) |
| 2026-01-29 | Local → Production | Prepared DB export after cleanup | 419 replacements, file `backups/production_ready_20260129_161323.sql` (pending import) |
| 2026-01-29 | Local → Production | Deploy service images + workspace | 243 replacements, 114MB |
| 2026-01-28 | Local → Production | Deploy local changes | 228 replacements, 112MB |
| 2026-01-21 | Local → Production | Deploy child theme | 157 replacements |
| 2026-01-19 | Production → Local | Initial setup | Full import via phpMyAdmin |

---

## Open Questions

*No open questions currently.*

---

## Special Notes

**Mega Menu System:**
- Custom overlay with blur effect
- Active state indicators
- Located in child theme: `wp/wp-content/themes/bsahlen/`

**After any structure changes:**
- Regenerate Elementor CSS (wp-admin → Elementor → Tools)
- Hard refresh browser (Ctrl+Shift+R)

**DB/MEDIA maintenance (2026-01-29):**
- Aggressive cleanup removed `wp/wp-content/uploads/2025/02` and `wp/wp-content/uploads/2025/03`
- DB references stripped; backup before cleanup: `backups/backup_before_aggressive_cleanup_20260129_160814.sql`

**Deploy workflow:**
1. Test locally
2. Owner commits and pushes
3. Plesk → Git → Pull Updates → Deploy
4. Check production
5. Regenerate Elementor CSS if needed
