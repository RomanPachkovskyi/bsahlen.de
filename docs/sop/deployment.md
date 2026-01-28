# SOP: Deployment — Plesk, Deploy, MODE, DB

**Studio Standard Workflow (v2.0)**

---

## 1. Deploy Process

### 1.1 Ланцюжок

```
Local → GitHub (main) → Plesk manual pull → Production
```

**З Git деплоїться:**
- Router (`index.php`, `.htaccess`)
- Кастомний код (`themes`, `mu-plugins`, `plugins/custom-*`)
- Maintenance page
- Config templates

**НЕ деплоїться з Git:**
- WordPress Core (встановлюється через Plesk)
- Uploads (залишаються на хостингу)
- `wp-config.php` (створюється вручну на хостингу)
- 3rd party plugins (встановлюються через WP Admin)

### 1.2 Deploy Workflow

**Крок 1: Локальна робота**
1. Працюєш локально на http://localhost:PORT
2. Тестуєш зміни
3. Перевіряєш що все працює

**Крок 2: Git Commit**
1. ШІ готує детальний commit message
2. Власник робить commit
3. Власник пушить в GitHub (main branch)

**Крок 3: Plesk Deploy**
1. Plesk → Git → "Pull Updates"
2. Перевірити Output log
3. Plesk → Git → "Deploy" (manual action)
4. Перевірити сайт на production

**Крок 4: Post-Deploy**
- Перевірити всі сторінки
- Якщо Elementor: Regenerate CSS & Data
- Hard refresh (Ctrl+Shift+R)
- Monitor 24-48h

---

## 2. Plesk Git Setup (Покрокова інструкція)

**Серйозність:** 🔴 CRITICAL — правильне налаштування критично важливе

### Крок 1: Увійти в Plesk

- URL: `https://plesk.yourdomain.com:8443`
- Domains → [your-domain] → Git

### Крок 2: Налаштувати Repository

1. Клікнути **"Manage"**
2. **Repository URL:** `https://github.com/user/project.git`
3. **Repository path:** `/httpdocs` (корінь монорепозиторію)
4. **Branch to deploy:** `main`

### Крок 3: Deployment Mode

**⚠️ ВАЖЛИВО: Спочатку MANUAL!**

- **Mode:** Manual (НЕ Automatic!)
- **Причина:** Контрольований deploy, без сюрпризів
- **Пізніше:** Можна переключити на Automatic після 1-2 днів stable work

### Крок 4: SSH Keys (якщо приватний repo)

1. Plesk → **Generate Key Pair**
2. Копіювати **Public Key**
3. GitHub → Settings → Deploy keys → **Add deploy key**
4. **Title:** `Plesk [domain]`
5. **Key:** [paste public key]
6. **✓ Allow write access:** NO (тільки read!)

### Крок 5: Тестовий Pull

1. Plesk → Git → **"Pull Updates"**
2. Перевірити **Output log:**
   - `"Successfully pulled"` → ✅ OK
   - Errors → перевірити URL, SSH keys
3. **НЕ натискати Deploy!** (поки не готові)

### Крок 6: Deploy Actions (налаштування)

Plesk → Git → Deploy Actions:
- **Run script:** [empty] (поки не потрібно)
- **Additional commands:** [empty]

Можливо знадобиться пізніше для:
- Composer install
- NPM build
- Cache clear

### Крок 7: Перший Manual Deploy

**⚠️ КРИТИЧНО - зробити backup перед deploy!**

1. **Backup production:**
   - Files: Plesk → Backup Manager
   - DB: phpMyAdmin → Export
2. Plesk → Git → **"Deploy"**
3. Перевірити **Output log**
4. Перевірити сайт: `https://domain.com`
5. Якщо щось не так → Rollback (див. SERVER_RULES.md)

### Крок 8: Переключення на Automatic (опційно)

**Після 1-2 днів стабільної роботи:**

1. Plesk → Git → Settings
2. Mode: Manual → **Automatic**
3. Тепер кожен push в `main` автоматично деплоїться

**⚠️ Рекомендація:** Залишити Manual mode якщо:
- Production сайт критичний
- Потрібен контроль кожного deploy
- Немає staging environment

---

## 3. Режими роботи (Maintenance ↔ Live)

### 3.1 Два режими

**MODE = 'maintenance'**

| Відвідувач | Бачить |
|------------|--------|
| Публічний | `/maintenance/index.html` |
| Адмін (залогінений) | WordPress |
| Прямий запит `/wp/*` | WordPress |

**MODE = 'live'**

| Відвідувач | Бачить |
|------------|--------|
| Усі | WordPress |

### 3.2 Default MODE залежно від Project State

**⚠️ ВАЖЛИВО - різні defaults для нових vs існуючих проектів!**

**Нові проєкти (bootstrap.sh):**
- Default: `MODE = 'maintenance'`
- Причина: сайт ще не готовий до публікації

**Існуючі проєкти (міграція):**
- Якщо сайт **живий** → `MODE = 'live'`
- Якщо сайт **в розробці** → `MODE = 'maintenance'`

**При міграції живого сайту завжди встановлюй:**
```php
define('MODE', 'live'); // НЕ 'maintenance'!
```

### 3.3 Як перемикати MODE

**Рекомендований спосіб (через Git):**

1. Редагуєш `index.php` локально:
   ```php
   define('MODE', 'live'); // або 'maintenance'
   ```
2. Commit + Push
3. Plesk автоматично деплоїть → режим змінено

**Екстрений спосіб (напряму на хостингу):**
- Plesk File Manager → `/httpdocs/index.php` → редагувати
- ⚠️ Буде перезаписано при наступному Git deploy!

### 3.4 Landing як повноцінна сторінка

- HTTP 200 (не 503)
- Індексується пошуковими системами
- Може використовуватись місяцями до запуску WP

---

## 4. Фото та медіа

- Фото додаються **на продакшні** через WP Admin
- Uploads **НЕ** зберігаються в Git
- Uploads **НЕ** видаляються при Git deploy (Plesk зберігає)

**Локально допускаються:**
- Placeholder-зображення
- Тестові фото для верстки

> Відсутність актуальних фото локально — нормальна ситуація.

---

## 5. База даних (DB)

### 5.1 Ключове правило

**Будь-який перенос БД між середовищами = заміна URL.**

| Напрям | Заміна |
|--------|--------|
| Production → Local | `https://domain.de` → `http://localhost:PORT` |
| Local → Production | `http://localhost:PORT` → `https://domain.de` |

### 5.2 Інструменти для URL replacement

**WP-CLI (Docker):**
```bash
# Export з заміною URL (для production)
docker-compose run --rm wpcli search-replace \
  'http://localhost:8080' 'https://domain.de' \
  --skip-columns=guid --all-tables \
  --export=/backups/production.sql
```

**Better Search Replace (плагін):**
- WP Admin → Tools → Better Search Replace
- Search: `http://localhost:8080`
- Replace: `https://domain.de`
- Select all tables
- Run as dry run спочатку!

**SQL dump + sed (для просунутих):**
```bash
sed -i 's|http://localhost:8080|https://domain.de|g' dump.sql
```

> ⚠️ Заміна має враховувати серіалізовані дані WordPress.

### 5.3 Контроль

**Факт заміни URL фіксується в PROJECT.md → DB Sync Notes:**

```markdown
## DB Sync Notes

| Date | Direction | Reason | Notes |
|------|-----------|--------|-------|
| 2026-01-28 | Local → Production | Deploy Redis | 157 replacements |
```

### 5.4 Backup перед DB операціями

**Завжди робити backup перед:**
- DB import на production
- URL replacement
- Структурними змінами

```bash
# Local backup
docker-compose exec -T db mysqldump -u wp -pwp dbname > backups/backup_$(date +%Y%m%d).sql

# Production backup
# Plesk → Databases → [db-name] → Export
# або phpMyAdmin → Export
```

---

## 6. Дозволене на продакшні (WP Admin)

### Дозволено:

✅ Встановлення/оновлення плагінів
✅ Налаштування плагінів
✅ SEO-правки (Yoast, meta tags)
✅ Редагування контенту (posts, pages)
✅ Додавання медіа (uploads)
✅ Elementor CSS regeneration

### Заборонено:

❌ Правки PHP / JS / CSS файлів
❌ Правки теми або mu-plugins
❌ "Тимчасові" код-фікси через Theme Editor
❌ Зміни в wp-config.php (тільки з підтвердженням)

**Чому заборонено?**
- Зміни будуть перезаписані при наступному deploy
- Код має бути в Git для version control
- Ризик порушити сайт без можливості rollback

---

## 7. Критичні дії (тільки з підтвердженням власника)

Наступні дії **НЕ можуть** виконуватись ШІ самостійно:

- ❌ DB import у production
- ❌ Зміна MODE на `'live'` (Go-Live)
- ❌ Будь-які зміни `wp-config.php` на хостингу
- ❌ Force push до Git
- ❌ Видалення production files
- ❌ Structural changes на production

**Процес для критичних дій:**
1. ШІ зупиняється (STOP-RULE)
2. ШІ готує детальний план дій
3. Власник переглядає і підтверджує
4. ШІ виконує з детальним логуванням в PROJECT.md

---

## 8. Post-Deploy Checklist

**⚠️ Обов'язково після кожного deploy:**

- [ ] Перевірити головну сторінку
- [ ] Перевірити ключові сторінки (services, contact, etc.)
- [ ] Перевірити навігацію і меню
- [ ] Якщо Elementor: Regenerate CSS & Data
- [ ] Hard refresh браузера (Ctrl+Shift+R)
- [ ] Перевірити на mobile
- [ ] Check Console для JS errors
- [ ] Оновити PROJECT.md → Changelog

**Після структурних змін (router, wp/ rename, etc.):**

- [ ] Regenerate Elementor CSS (WP Admin → Elementor → Tools)
- [ ] Clear all caches (WordPress, Plesk, CDN)
- [ ] Test all Elementor pages
- [ ] Verify uploads folder не зачеплений
- [ ] Monitor error logs 24-48h

---

## 9. Troubleshooting

### Deploy не працює

**Перевірити:**
1. SSH keys правильно додані до GitHub?
2. Repository URL правильний?
3. Branch `main` існує і має commits?
4. Plesk має read access до repo?

**Лог помилок:**
- Plesk → Git → View Log
- GitHub → Settings → Deploy keys → Recently used

### Сайт показує 500 після deploy

**Можливі причини:**
1. `wp-config.php` відсутній або неправильний
2. PHP syntax error в deploy коді
3. Missing dependencies (plugins)
4. Incorrect file permissions

**Швидкий rollback:**
- Plesk → File Manager → restore з backup
- або Git → Deploy попередній commit

### Elementor без стилів

**Рішення:**
1. WP Admin → Elementor → Tools
2. Regenerate CSS & Data
3. Hard refresh браузера

---

**Версія:** 2.1
**Останнє оновлення:** 2026-01-28
**Див. також:** [Basics](basics.md) | [Migration](migration.md) | [Improvements](improvements.md)
