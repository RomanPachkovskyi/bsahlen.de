# MIGRATION GUIDE: Legacy WordPress → SOP v2.0

**Загальний посібник для міграції існуючих WordPress проектів на SOP v2.0 (Monorepo)**

**Версія:** 1.0
**Дата:** 2026-01-28
**Автор:** Studio Standard Workflow Team

---

## Для кого цей документ

Цей посібник для **міграції існуючих WordPress проектів** на новий стандарт SOP v2.0.

Якщо створюєш **новий проект** - використовуй `bootstrap.sh` скрипт.

---

## Перед початком

### ⚠️ Критичні питання

**Відповісти ПЕРЕД міграцією:**

1. **Чи є production сайт живим?**
   - Так → MODE='live', обережна міграція
   - Ні → MODE='maintenance', простіша міграція

2. **Чи є доступ до Plesk admin panel?**
   - Так → можна налаштувати Git auto-deploy
   - Ні → залишаємось на ручному deploy

3. **Чи є staging environment?**
   - Так → тестуємо там перше
   - Ні → будь обережним на production

4. **Які плагіни кастомні?**
   - Зазвичай: тільки custom-* плагіни
   - 3rd party (Elementor, Yoast, etc.) → НЕ в Git

5. **Чи потрібна parent theme в Git?**
   - Преміум купована → ❌ НЕ в Git
   - Сильно кастомізована → ✅ в Git (обговорити)

---

## Філософія міграції

**3 принципи:**

1. **Безпека:** Завжди є rollback план
2. **Поступовість:** Маленькі кроки, тестування після кожного
3. **Документація:** Все записуємо

**Що НЕ робимо:**

- ❌ Міграція без бекапів
- ❌ Зміни на production без тестування локально
- ❌ Великі коміти "зробив все за раз"
- ❌ Push без перевірки .gitignore

---

## Загальний план (10 фаз)

```
Phase 0: Backup & Documentation          ← Безпечно, обов'язково
Phase 1: Create New Files                ← Безпечно (тільки додаємо)
Phase 2: Git Cleanup                     ← Безпечно (видаляємо зайве з Git)
Phase 3: Structure Migration (rename)    ← Середній ризик
Phase 4: Docker Update                   ← Середній ризик
Phase 5: Local Testing                   ← Безпечно
Phase 6: Git Finalization                ← Безпечно
Phase 7: Plesk Setup                     ← Середній ризик (MANUAL mode)
Phase 8: Production Deployment           ← ВИСОКИЙ ризик (точка неповернення)
Phase 9: Rollback Plan (якщо потрібно)   ← Аварійний
Phase 10: Post-Migration Cleanup         ← Безпечно
```

---

## Детальна інструкція

### PHASE 0: Backup & Documentation

**Мета:** Зберегти можливість повернутись

**Дії:**

```bash
cd /path/to/project

# 1. Бекап БД (через Docker або WP-CLI)
docker-compose run --rm wpcli db export \
  backups/PRE_MIGRATION_$(date +%Y%m%d_%H%M%S).sql

# 2. Git tag для rollback
git tag -a pre-migration-backup -m "Before SOP v2.0 migration ($(date +%Y-%m-%d))"
git branch backup-pre-migration

# 3. Копія .env
cp .env .env.backup

# 4. Задокументувати стан
echo "Pre-migration snapshot: $(date)" > MIGRATION_STATE.txt
docker ps >> MIGRATION_STATE.txt
ls -la >> MIGRATION_STATE.txt
```

**Результат:**
- Бекап БД у `backups/`
- Git tag `pre-migration-backup`
- Бекап гілка `backup-pre-migration`
- Документ `MIGRATION_STATE.txt`

---

### PHASE 1: Create New Files

**Мета:** Додати SOP v2.0 файли

**1.1. PROJECT.md**

```markdown
# PROJECT: [project-name]

## Snapshot — [date]

| Environment | URL | Status |
|-------------|-----|--------|
| Production  | https://domain.com | 🟢 LIVE |
| Local       | http://localhost:PORT | 🟢 Running |

## Project State

**Current: [BUILD/LANDING/LIVE]**

## Tech Stack
...
```

**1.2. SERVER_RULES.md**

Описати:
- Hosting structure (Plesk/інше)
- MODE switching logic
- Deploy checklist
- Go-Live checklist

**1.3. SOP.md**

Quick reference (скопіювати з SOP_v2.md, адаптувати).

**1.4. README.md**

Описати:
- Quick start
- Project URLs
- Common commands
- Tech stack

**1.5. Router Files**

Створити в **root**:

```php
// index.php
<?php
define('MODE', 'live'); // or 'maintenance'
// ... (rest from bootstrap.sh)
```

```apache
# .htaccess
RewriteEngine On
# Legacy paths → /wp (when WP moved out of root)
RewriteRule ^wp-content/(.*)$ /wp/wp-content/$1 [L,NC]
RewriteRule ^wp-includes/(.*)$ /wp/wp-includes/$1 [L,NC]
RewriteRule ^wp-admin/(.*)$ /wp/wp-admin/$1 [L,NC]
RewriteRule ^wp-login\.php$ /wp/wp-login.php [L,NC]
# ... (rest from bootstrap.sh)
```

**1.6. Maintenance Page**

```bash
mkdir -p maintenance
# Create maintenance/index.html (simple HTML placeholder)
```

**1.7. wp-config Templates**

```bash
# wp-config-local.php (from current wp-config.php)
# wp-config-production.php (template)
```

**Commit:**

```bash
git add PROJECT.md SERVER_RULES.md SOP.md README.md
git commit -m "docs: add SOP v2.0 project documentation"

git add index.php .htaccess maintenance/
git commit -m "feat: add router and maintenance page (SOP v2.0)"

git add wp-config-local.php wp-config-production.php
git commit -m "feat: add wp-config templates"
```

---

### PHASE 2: Git Cleanup

**Мета:** Видалити зайві файли з Git (але зберегти локально)

**2.1. Оновити .gitignore**

```gitignore
# Замінити всі `wordpress/` на `wp/`

# Languages (auto-downloaded)
wp/wp-content/languages/

# 3rd party plugins
wp/wp-content/plugins/[plugin-name]/
# ... (всі крім custom-*)

# Backup files
.env.backup
*.backup
MIGRATION_STATE.txt
```

**2.2. Видалити з Git (зберегти локально)**

```bash
# Languages
git rm -r --cached wp-content/languages/

# 3rd party plugins
git rm -r --cached wp-content/plugins/elementor/
git rm -r --cached wp-content/plugins/yoast-seo/
# ... (всі крім custom-*)

# Перевірити що файли залишились локально
ls wp-content/languages/ | head -5
ls wp-content/plugins/
```

**Commit:**

```bash
git add .gitignore
git commit -m "chore: remove languages and 3rd party plugins from Git

Removed from Git tracking (kept locally):
- wp-content/languages/ (auto-downloaded by WP)
- 3rd party plugins (installed via WP Admin)

Keep in Git:
- Custom themes (all)
- Custom plugins (custom-* only)

Per SOP v2.0 requirements"
```

---

### PHASE 3: Structure Migration

**Мета:** Перейменувати `wordpress/` → `wp/`

**⚠️ КРИТИЧНО:** Це точка де структура змінюється

**3.1. Зупинити Docker**

```bash
docker-compose down
```

**3.2. Перейменувати**

```bash
mv wordpress wp
```

**3.3. Оновити .gitignore**

```bash
sed -i '' 's/wordpress\//wp\//g' .gitignore
```

**3.4. Git tracking**

```bash
git add -A

# Перевірити що Git розпізнав як rename
git status
# Має бути: "renamed: wordpress/... -> wp/..."

git commit -m "refactor: rename wordpress/ to wp/ (SOP v2.0 structure)

BREAKING CHANGE: WordPress directory renamed

- Renamed: wordpress/ → wp/
- Updated .gitignore paths
- Git preserves file history (tracked as rename)

Docker needs update to mount new path.

Part of SOP v2.0 migration"
```

---

### PHASE 4: Docker Update

**Мета:** Оновити docker-compose.yml під wp/

**4.1. Backup docker-compose.yml**

```bash
cp docker-compose.yml docker-compose.yml.old
```

**4.2. Оновити paths**

```yaml
services:
  wordpress:
    volumes:
      # OLD: - ./wordpress:/var/www/html:cached
      - ./wp:/var/www/html:cached
      # ...

  wpcli:
    volumes:
      # OLD: - ./wordpress:/var/www/html:cached
      - ./wp:/var/www/html:cached
      # ...
```

**4.3. Запустити Docker**

```bash
docker-compose up -d

# Чекаємо ~30 секунд
sleep 30

# Перевірити
curl -I http://localhost:PORT
docker-compose logs wordpress | tail -20
```

**4.4. Перевірити БД**

```bash
docker-compose exec -T db mysql -u USER -pPASS DATABASE -e "SELECT COUNT(*) FROM wp_posts;"
```

**Commit:**

```bash
git add docker-compose.yml
git commit -m "chore: update docker-compose for wp/ structure

- Changed mount path: ./wordpress → ./wp
- Database volume preserved (no data loss)

Part of SOP v2.0 migration"
```

---

### PHASE 5: Local Testing

**Мета:** Переконатись що все працює

**Checklist:**

```
□ Homepage loads (http://localhost:PORT)
□ Internal pages work
□ Images display
□ Menus work
□ Forms work (if any)
□ Can login to wp-admin
□ Elementor editor works (if used)
□ Can edit & save pages
□ Custom theme styles work
□ Custom JS works
```

**Технічна перевірка:**

```bash
# WP paths
docker-compose run --rm wpcli option get home
docker-compose run --rm wpcli option get siteurl

# Child theme paths (check for hardcoded 'wordpress/')
grep -r "wordpress/" wp/wp-content/themes/[child-theme]/
```

**Якщо Elementor:**

```
1. wp-admin → Elementor → Tools
2. Regenerate CSS & Data → "Regenerate Files"
3. Hard refresh site (Cmd+Shift+R)
```

**Бекап після успішного тесту:**

```bash
docker-compose exec -T db mysqldump -u USER -pPASS DATABASE > \
  backups/POST_MIGRATION_LOCAL_$(date +%Y%m%d_%H%M%S).sql
```

---

### PHASE 6: Git Finalization

**Мета:** Підготувати до push

**6.1. Фінальна перевірка**

```bash
git status
# Має бути: "working tree clean" або "ahead by X commits"

git log --oneline -10
# Перевірити commit messages
```

**6.2. Push (власник проєкту)**

```bash
# Push бекап гілки (опційно)
git push origin backup-pre-migration

# Push tag
git push origin pre-migration-backup

# Push main
git push origin main
```

**6.3. Перевірити на GitHub**

- Структура відповідає SOP v2.0?
- wp/ folder на місці?
- Немає .env, backups/,files?
- Router files (index.php, .htaccess) в root?

---

### PHASE 7: Plesk Setup

**Мета:** Налаштувати Git auto-deploy (MANUAL mode)

**⚠️ Тільки власник з доступом до Plesk!**

**7.1. Backup Production**

В Plesk:
1. Databases → Export DB
2. Backup manager → Create full backup
3. Завантажити локально

**7.2. Налаштувати Git в Plesk**

1. **Git:**
   - Repository: `https://github.com/user/project.git`
   - Branch: `main`
   - Deploy to: `/httpdocs`
   - Mode: **MANUAL** (не automatic!)

2. **SSH Keys:**
   - Згенерувати в Plesk
   - Додати в GitHub (Settings → Deploy keys)

3. **Тестовий Pull (БЕЗ deploy):**
   - Plesk → Git → "Pull Updates"
   - Перевірити логи
   - **НЕ натискати Deploy!**

---

### PHASE 8: Production Deployment

**🔴 КРИТИЧНО! Точка неповернення.**

**Checklist готовності:**

```
□ Фази 0-7 завершені без помилок
□ Локальний сайт працює ідеально
□ Є свіжі бекапи production (файли + БД)
□ Plesk Git налаштований і протестований
□ Є rollback план
□ Власник підтверджує GO
□ Є 1-2 години вільного часу
```

**8.1. Deploy Files**

Plesk → Git → "Deploy" (MANUAL)

**8.2. Перевірити структуру**

Plesk File Manager → `/httpdocs`:

```
/httpdocs/
├── index.php      ← NEW (router)
├── .htaccess      ← NEW (routing rules)
├── wp/            ← NEW (WordPress moved here)
│   ├── wp-content/
│   │   ├── themes/
│   │   ├── plugins/
│   │   └── uploads/   ← OLD (має залишитись!)
│   └── wp-config.php  ← OLD (має залишитись!)
└── maintenance/   ← NEW
```

**8.3. Виправити wp-config.php**

Edit `/httpdocs/wp/wp-config.php`:

```php
// Перевірити:
define('ABSPATH', dirname(__FILE__) . '/');

// URL (залежно від структури):
// Option A: Router в корені, WP показується через router
define('WP_HOME',    'https://domain.com');
define('WP_SITEURL', 'https://domain.com/wp');

// Option B: WP в корені (якщо не використовуєш router)
define('WP_HOME',    'https://domain.com');
define('WP_SITEURL', 'https://domain.com');
```

**8.4. Перевірити router MODE**

Edit `/httpdocs/index.php`:

```php
define('MODE', 'live'); // НЕ 'maintenance'!
```

**8.5. Test Production**

```bash
curl -I https://domain.com
# Має бути: HTTP/2 200
```

В браузері:
```
□ Homepage відкривається
□ Стилі завантажені
□ Меню працює
□ Внутрішні сторінки працюють
□ Зображення показуються
□ Можна залогінитись в wp-admin
```

**8.6. Elementor CSS Regeneration (ОБОВ'ЯЗКОВО!)**

```
1. https://domain.com/wp-admin
2. Elementor → Tools → Regenerate CSS & Data
3. "Regenerate Files"
4. Hard refresh (Cmd+Shift+R)
```

**8.7. Verify All Critical Pages**

Перевірити:
- Головна
- Всі меню items
- Форми (якщо є)
- Блог/новини
- Контакти

---

### PHASE 9: Rollback Plan

**Використовувати ТІЛЬКИ якщо щось пішло не так!**

**9.1. Quick Rollback (Git)**

```bash
# Локально
git checkout backup-pre-migration
git push origin main --force

# Plesk → Git → Pull Updates → Deploy
```

**9.2. Full Rollback (Files + DB)**

Plesk:
1. File Manager → видалити `/httpdocs/*`
2. Відновити з backup
3. Databases → Import бекап SQL

**9.3. Local Rollback**

```bash
docker-compose down
rm -rf wp
mv wordpress.backup wordpress
docker-compose up -d
# Відновити БД з backups/PRE_MIGRATION_*.sql
```

---

### PHASE 10: Post-Migration Cleanup

**Після 1-2 днів стабільної роботи**

**10.1. Видалити бекапи (опційно)**

```bash
rm -rf wordpress.backup
rm docker-compose.yml.old
rm MIGRATION_STATE.txt
```

**10.2. Оновити документацію**

```bash
# PROJECT.md
# - Project State: LIVE
# - Додати запис в Changelog
# - Видалити "Open Questions"

git add PROJECT.md
git commit -m "docs: update after successful SOP v2.0 migration"
git push
```

**10.3. Plesk Git Auto-deploy**

Якщо все працює стабільно:

```
Plesk → Git → Settings
Deployment mode: Manual → Automatic
```

**10.4. Clean Old Branches**

```bash
# Після 1 тижня стабільної роботи
git branch -D backup-pre-migration
git push origin --delete backup-pre-migration
```

---

## Поширені проблеми

### 1. "Сайт показує білий екран після deploy"

**Причини:**
- wp-config.php має неправильні шляхи
- Відсутній wp-content/uploads/

**Рішення:**
```php
// wp-config.php
define('ABSPATH', dirname(__FILE__) . '/');
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

// Перевірити: /wp/wp-content/debug.log
```

### 2. "Стилі зламані після deploy"

**Причина:** Elementor CSS не регенеровано

**Рішення:**
```
wp-admin → Elementor → Tools → Regenerate CSS
```

### 3. "Git не розпізнає rename"

**Причина:** Занадто багато змін одночасно

**Рішення:**
```bash
git config merge.renameLimit 999999
git add -A
```

### 4. "Docker volume видалив БД"

**Причина:** Ім'я volume змінилось

**Рішення:**
```bash
# Відновити з backups/PRE_MIGRATION_*.sql
docker-compose up -d
docker-compose exec -T db mysql -u USER -pPASS DATABASE < backups/PRE_MIGRATION_*.sql
```

### 5. "Production сайт 404 після deploy"

**Причини:**
- Router .htaccess не працює
- WP_HOME/WP_SITEURL неправильні

**Рішення:**
```
1. Перевірити .htaccess в /httpdocs (має бути router rules)
2. Перевірити wp-config.php (WP_SITEURL)
3. Перевірити Plesk Apache settings
```

---

## Чеклист успішної міграції

### Локально (після Phase 5)
- [ ] Сайт працює на http://localhost:PORT
- [ ] Всі сторінки відкриваються
- [ ] Стилі і JS працюють
- [ ] wp-admin доступний
- [ ] БД збережена і працює
- [ ] Git structure відповідає SOP v2.0

### Git (після Phase 6)
- [ ] Push успішний
- [ ] Структура на GitHub правильна
- [ ] Немає .env, backups/, чутливих файлів
- [ ] wp/ folder на місці
- [ ] Router files в root

### Production (після Phase 8)
- [ ] Сайт працює на https://domain.com
- [ ] Всі сторінки доступні
- [ ] Стилі завантажені
- [ ] Зображення показуються
- [ ] Форми працюють
- [ ] wp-admin доступний
- [ ] Elementor CSS регенеровано
- [ ] SEO meta tags на місці

---

## Додаткові ресурси

**Документи проєкту:**
- `MIGRATION_AUDIT.md` - Аналіз для конкретного проєкту
- `MIGRATION_PLAN.md` - Детальний план для конкретного проєкту
- `SOP_v2.md` - Повний SOP стандарт

**Інструменти:**
- `bootstrap.sh` - Скрипт для нових проєктів
- `.gitignore` - Template з bootstrap.sh
- `wp-config-*.php` - Templates

**Підтримка:**
- GitHub Issues: проєкт репозиторій
- Studio documentation: внутрішня база знань

---

**Версія документа:** 1.0
**Останнє оновлення:** 2026-01-28
**Протестовано на:** bsahlen.de (успішна міграція)
