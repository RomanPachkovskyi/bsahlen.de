# PROJECT: bsahlen.de

## Snapshot — 2026-01-28

| Environment | URL | Status |
|-------------|-----|--------|
| Production  | https://bsahlen.de | 🟢 LIVE |
| Local       | http://localhost:8080 | 🟢 Running |

## Project State

**Current: LIVE** ✅

- [x] BUILD — local development
- [x] LANDING — maintenance page live, WP in development
- [x] LIVE — WordPress public

**⚠️ MIGRATION IN PROGRESS:** Migrating from legacy structure to SOP v2.0 (Monorepo)

## About

**Website:** Corporate website for financial consulting
**Client:** BSahlen Financial Consulting
**Language:** German (DE)
**CMS:** WordPress with Elementor

## Tech Stack

- **WordPress:** Latest (via Plesk)
- **PHP:** 8.2
- **Database:** MariaDB 10.11.13
- **Theme:** Finovate (parent) + bsahlen (child theme)
- **Page Builder:** Elementor Pro
- **Key Plugins:**
  - Elementor + Elementor Pro
  - Yoast SEO
  - SVG Support
  - Vamtam Elementor Integration

## Goals

**What we maintain:**
- Modern, professional design
- Fast page load
- SEO optimization
- Mobile-first approach
- Custom mega menu with hover effects

**Target audience:**
- B2B clients
- Financial sector professionals
- German-speaking market

**Key pages:**
- Homepage with hero section
- Services overview
- About/Team
- Contact form
- Blog/News section

## Current Features

- ✅ Custom mega menu with overlay and active states
- ✅ Child theme for customizations
- ✅ Elementor-based page builder
- ✅ SEO configured (Yoast)
- ✅ SSL active (Let's Encrypt)
- ✅ Responsive design

## Open Questions (Migration-specific)

1. ✅ Parent theme Finovate: Keep in Git (sometimes needed)
2. ✅ MODE for router: 'live' (site already running)
3. ✅ Maintenance page: Simple placeholder for structure
4. ⏳ Plesk Git auto-deploy: Setup as MANUAL mode
5. ⏳ Production wp-config.php: Need to update paths after migration

## Changelog

| Date | Change | By |
|------|--------|----|
| 2026-01-28 | Started migration to SOP v2.0 monorepo structure | AI |
| 2026-01-21 | Deployed child theme with custom mega menu | AI |
| 2026-01-20 | Setup WordMove and local environment | AI |
| 2026-01-19 | Initial local setup with Docker | AI |

## DB Sync Notes

| Date | Direction | Reason | Notes |
|------|-----------|--------|-------|
| 2026-01-28 | Pre-migration backup | Safety before structure change | 104MB SQL dump |
| 2026-01-21 | Local → Production | Deploy child theme | URL replacement, 157 replacements |
| 2026-01-19 | Production → Local | Initial setup | Full import via phpMyAdmin |

## Deploy Notes

**Current (Legacy):**
- Deploy method: Manual via lftp (FTPS)
- Upload themes, plugins, uploads
- Database via phpMyAdmin

**Target (SOP v2.0):**
- Deploy method: Plesk Git auto-deploy (MANUAL mode)
- Branch: main
- Deploy to: /httpdocs (monorepo root)

**Goes to Git:**
- `/wp/wp-content/themes/*` (including Finovate parent)
- `/wp/wp-content/mu-plugins/*` (if any)
- `/maintenance/*` (placeholder)
- `/index.php` (router)
- `/.htaccess` (routing rules)
- `/wp-config-local.php`, `/wp-config-production.php` (templates)
- Documentation files

**Never in Git:**
- `/wp/wp-content/uploads/`
- `/wp/wp-content/languages/`
- `/wp/wp-content/plugins/*` (3rd party, except custom-*)
- `/wp/wp-config.php` (active config)
- `/_db/` or `/backups/` (database dumps)
- `.env` (secrets)

## Migration Status

**Phase 0:** ✅ Backup & Documentation
**Phase 1:** 🔄 Creating new files
**Phase 2:** ⏳ Git cleanup
**Phase 3:** ⏳ Structure migration (wordpress → wp)
**Phase 4:** ⏳ Docker update
**Phase 5:** ⏳ Local testing
**Phase 6:** ⏳ Git finalization
**Phase 7:** ⏳ Plesk setup
**Phase 8:** ⏳ Production deployment

See: `MIGRATION_PLAN.md` for detailed steps

## Post-Migration TODO

- [ ] Test all pages on production after migration
- [ ] Regenerate Elementor CSS on production
- [ ] Update Plesk Git to MANUAL deploy mode
- [ ] Monitor for 24-48h after migration
- [ ] Clean up old backups after stable period

## Notes

- Local development on Docker (WordPress + MySQL + phpMyAdmin)
- Production hosted on Plesk (SSH disabled by host)
- FTP/FTPS for file access (slow due to SSL + many files)
- Custom child theme path: `wp-content/themes/bsahlen/`
- Mega menu uses custom JS and overlay system
