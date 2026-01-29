# SERVER_RULES: bsahlen.de

## Hosting Structure

```
/httpdocs/                    ← Document root (Plesk)
├── index.php                 ← Router (MODE switching)
├── .htaccess                 ← Routing rules
├── wp/                       ← WordPress installation
│   ├── wp-admin/
│   ├── wp-includes/
│   ├── wp-content/
│   │   ├── themes/
│   │   │   ├── finovate/     ← Parent theme
│   │   │   └── bsahlen/      ← Child theme (customizations)
│   │   ├── plugins/          ← All plugins (installed via WP Admin)
│   │   └── uploads/          ← Media files
│   └── wp-config.php         ← Production config
└── maintenance/
    └── index.html            ← Maintenance page
```

---

## Server Info

| Parameter | Value |
|-----------|-------|
| **Provider** | Plesk |
| **IP** | 81.209.248.242 |
| **Domain** | bsahlen.de |
| **SSL** | Let's Encrypt (auto-renewal) |
| **PHP** | 8.2 |
| **Database** | MariaDB 10.11.13 |
| **DB Prefix** | XutfWi7d_ |

---

## Access

| Method | Status | Notes |
|--------|--------|-------|
| **FTP/FTPS** | Available | Via Plesk credentials |
| **SSH** | Disabled | Blocked by hosting provider |
| **Plesk Panel** | Available | Full admin access |
| **phpMyAdmin** | Available | Via Plesk |

---

## Git Deploy

| Setting | Value |
|---------|-------|
| **Repository** | https://github.com/RomanPachkovskyi/bsahlen.de |
| **Branch** | main |
| **Deploy to** | /httpdocs |
| **Mode** | MANUAL |

**Deploy workflow:**
1. Owner pushes to GitHub (main branch)
2. Plesk → Git → Pull Updates
3. Plesk → Git → Deploy
4. Verify site
5. Elementor → Regenerate CSS (if needed)

---

## Modes

### MODE = 'live' (current)

| Visitor | Sees |
|---------|------|
| Everyone | WordPress site |

### MODE = 'maintenance'

| Visitor | Sees |
|---------|------|
| Public | `/maintenance/index.html` |
| Admin (logged in) | WordPress |
| Direct `/wp/*` | WordPress |

**How to switch:**
1. Edit `index.php` line 12: `define('MODE', 'maintenance');`
2. Commit + Push
3. Plesk → Deploy

**Emergency switch (on server):**
- Plesk File Manager → `/httpdocs/index.php` → Edit
- Will be overwritten on next deploy!

---

## Go-Live Checklist

- [x] Content ready
- [x] SEO configured (Yoast)
- [x] SSL active (Let's Encrypt)
- [x] MODE = 'live'
- [x] Tested on desktop
- [x] Tested on mobile
- [x] Elementor CSS regenerated
- [x] Custom mega menu working

**Status: LIVE since 2026-01-21**

---

## Rollback Checklist

**If something breaks after deploy:**

1. [ ] Switch MODE = 'maintenance' (emergency via Plesk File Manager)
2. [ ] Identify issue (check `/wp/wp-content/debug.log`)
3. [ ] Git rollback if needed:
   ```bash
   git revert HEAD
   git push origin main
   # Plesk → Git → Deploy
   ```
4. [ ] DB restore if needed (Plesk → Databases → Import)
5. [ ] Verify site works
6. [ ] Switch MODE = 'live'

---

## Special Notes

**Mega Menu:**
- Custom overlay with blur effect
- Located in child theme: `wp/wp-content/themes/bsahlen/`
- Active state indicators on menu items

**After structure changes:**
- Always regenerate Elementor CSS (wp-admin → Elementor → Tools)
- Hard refresh browser (Ctrl+Shift+R)

**Database sync:**
- URL replacement required when moving DB between environments
- Local: `http://localhost:8080`
- Production: `https://bsahlen.de`

---

## Emergency Contacts

| Role | Access |
|------|--------|
| Project Owner | GitHub, Plesk, WP Admin |
| AI Assistant | Local files only (no push) |

---

**Last updated:** 2026-01-29
