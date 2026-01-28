# MIGRATION PLAN: bsahlen.de → SOP v2.0

**Проєкт:** bsahlen.de
**Версія плану:** 1.0
**Дата створення:** 2026-01-28
**Статус:** 🟡 DRAFT - Очікує підтвердження власника

---

## ⚠️ ПЕРЕДУМОВИ

**Перед початком міграції ОБОВ'ЯЗКОВО:**

1. ✅ Прочитати `MIGRATION_AUDIT.md` повністю
2. ✅ Відповісти на всі питання з розділу "Відкриті питання"
3. ✅ Власник підтверджує готовність до міграції
4. ✅ Є доступ до Plesk адмін-панелі
5. ✅ Є актуальний бекап production БД та файлів
6. ✅ Docker запущений і працює локально

---

## ФАЗА 0: Backup & Documentation

**Мета:** Зберегти всю критичну інформацію перед змінами
**Ризик:** Немає
**Можна відкотити:** N/A
**Тривалість:** 15-20 хв

### 0.1. Бекап локальної бази даних

```bash
cd ~/Project/bsahlen.de

# Переконатись що Docker запущено
docker ps | grep bsahlende

# Експорт поточної БД з timestamp
docker-compose run --rm wpcli db export \
  /backups/PRE_MIGRATION_$(date +%Y%m%d_%H%M%S).sql

# Перевірити що файл створено
ls -lh backups/PRE_MIGRATION_*.sql
```

**Очікуваний результат:** Файл `backups/PRE_MIGRATION_20260128_HHMMSS.sql` (~105MB)

### 0.2. Зафіксувати поточний стан Git

```bash
# Перевірити статус
git status

# Якщо є незакомічені зміни - зберегти їх
git add .
git commit -m "chore: save state before SOP v2.0 migration"

# Створити тег для швидкого rollback
git tag -a pre-migration-backup -m "State before SOP v2.0 migration (2026-01-28)"
git push origin pre-migration-backup

# Створити бекап-гілку
git branch backup-pre-migration
git push origin backup-pre-migration
```

**Очікуваний результат:** Тег `pre-migration-backup` і гілка `backup-pre-migration` в GitHub

### 0.3. Документувати поточні URL і креденшли

```bash
# Зберегти копію .env
cp .env .env.backup

# Задокументувати поточні Docker порти
echo "Current ports:" > MIGRATION_STATE.txt
grep -A5 "ports:" docker-compose.yml >> MIGRATION_STATE.txt

# Задокументувати поточну структуру
echo -e "\nCurrent structure:" >> MIGRATION_STATE.txt
ls -la >> MIGRATION_STATE.txt
du -sh wordpress/* >> MIGRATION_STATE.txt
```

**Очікуваний результат:** Файл `MIGRATION_STATE.txt` зі всією інформацією

### 0.4. Скріншот поточного локального сайту

```bash
# Відкрити сайт
open http://localhost:8080

# Вручну зробити скріншоти:
# - Головна сторінка
# - Будь-яка внутрішня сторінка
# - WP Admin dashboard

# Зберегти в ~/Desktop/bsahlen-pre-migration/
```

---

## ФАЗА 1: Local Changes (Safe Zone)

**Мета:** Додати нові файли згідно SOP v2.0
**Ризик:** 🟢 Немає (тільки додавання файлів)
**Можна відкотити:** ✅ Так (git reset)
**Тривалість:** 30-40 хв

### 1.1. Створити PROJECT.md

```bash
cd ~/Project/bsahlen.de
```

Створити файл `PROJECT.md` (використати template з bootstrap.sh, адаптувати для bsahlen.de):

**Ключові зміни від template:**
- Project State: **LIVE** (не BUILD, сайт вже працює)
- Заповнити реальні дані про проєкт
- Додати інформацію про поточні themes/plugins
- Вказати що міграція в процесі

### 1.2. Створити SERVER_RULES.md

Скопіювати з bootstrap.sh output, але додати секцію:

```markdown
## ⚠️ MIGRATION IN PROGRESS

**Current state:** Migrating from old structure to SOP v2.0

**Old structure (production):**
- Path: `/httpdocs` (WordPress root)
- No router
- Deploy: manual FTPS

**Target structure (after migration):**
- Path: `/httpdocs` (monorepo root)
- Router: `index.php` + `.htaccess`
- Deploy: Git auto-deploy
```

### 1.3. Створити SOP.md (short version)

```bash
# Скопіювати template з bootstrap.sh
# Це буде скорочена версія для проєкту
```

### 1.4. Створити папку maintenance з placeholder

```bash
mkdir -p maintenance

# Створити maintenance/index.html (з bootstrap.sh template)
# Адаптувати для bsahlen.de (німецька мова, брендинг)
```

### 1.5. Створити router files (НЕ активовувати)

```bash
# Створити index.php в root (з bootstrap.sh)
# ⚠️ ВАЖЛИВО: MODE = 'live' (не 'maintenance')

# Створити .htaccess в root (з bootstrap.sh)
```

**⚠️ КРИТИЧНО:** Ці файли поки НЕ будуть використовуватись локально (Docker прямо монтує wordpress/)

### 1.6. Створити wp-config templates

```bash
# Створити wp-config-local.php
# - Базується на поточному wordpress/wp-config.php
# - Зберегти всі налаштування (DB, salts, etc.)

# Створити wp-config-production.php
# - Template для production
# - URL: https://bsahlen.de/wp (якщо буде піддиректорія)
# - Або https://bsahlen.de (якщо в корені)
```

### 1.7. Оновити README.md

Додати секцію про міграцію:

```markdown
## ⚠️ Migration in Progress

This project is being migrated from legacy structure to SOP v2.0 (Monorepo).

See:
- `MIGRATION_AUDIT.md` - Analysis
- `MIGRATION_PLAN.md` - Step-by-step plan
- `MIGRATION.md` - General migration guide
```

### 1.8. Commit Phase 1 changes

```bash
git status

# Перевірити що додаємо тільки нові файли
git add PROJECT.md SERVER_RULES.md SOP.md README.md
git add maintenance/
git add index.php .htaccess
git add wp-config-local.php wp-config-production.php

git commit -m "docs: add SOP v2.0 project structure (Phase 1)"

# НЕ pushити поки
```

---

## ФАЗА 2: Git Cleanup

**Мета:** Видалити зайві файли з Git history
**Ризик:** 🟡 Середній (зміна Git history)
**Можна відкотити:** ✅ Так (є бекап гілка)
**Тривалість:** 20-30 хв

### 2.1. Оновити .gitignore

```bash
cd ~/Project/bsahlen.de

# Відкрити .gitignore і додати:
# wordpress/wp-content/languages/
# wordpress/wp-content/plugins/duplicate-post/
# wordpress/wp-content/plugins/elementor/
# wordpress/wp-content/plugins/elementor-pro/
# wordpress/wp-content/plugins/lasoon-maintenance/
# wordpress/wp-content/plugins/svg-support/
# wordpress/wp-content/plugins/vamtam-*/
# wordpress/wp-content/plugins/wordpress-seo/

# Залишити в Git тільки child theme
# wordpress/wp-content/themes/bsahlen/

# 3rd party parent theme також видалити
# wordpress/wp-content/themes/finovate/
```

**⚠️ ОБГОВОРИТИ:** Чи потрібна parent theme `finovate` в Git?
- Якщо це преміум тема → ❌ НЕ в Git
- Якщо це сильно кастомізована тема → ✅ можливо в Git

### 2.2. Видалити файли з Git (але зберегти локально)

```bash
# Видалити languages з Git, але зберегти локально
git rm -r --cached wordpress/wp-content/languages/

# Видалити 3rd party plugins з Git
git rm -r --cached wordpress/wp-content/plugins/duplicate-post/
git rm -r --cached wordpress/wp-content/plugins/elementor/
git rm -r --cached wordpress/wp-content/plugins/elementor-pro/
git rm -r --cached wordpress/wp-content/plugins/lasoon-maintenance/
git rm -r --cached wordpress/wp-content/plugins/svg-support/
git rm -r --cached wordpress/wp-content/plugins/vamtam-*/
git rm -r --cached wordpress/wp-content/plugins/wordpress-seo/

# Якщо видаляємо parent theme (ОБГОВОРИТИ!)
# git rm -r --cached wordpress/wp-content/themes/finovate/

# Перевірити що файли залишились локально
ls wordpress/wp-content/languages/ | head -5
ls wordpress/wp-content/plugins/
```

### 2.3. Commit cleanup

```bash
git status

git commit -m "chore: remove languages and 3rd party plugins from git

- Languages are auto-downloaded by WordPress
- 3rd party plugins should not be in Git (SOP v2.0)
- Keep only custom child theme (bsahlen)"

# НЕ pushити поки
```

### 2.4. Перевірити розмір репозиторію

```bash
# Подивитись розмір .git
du -sh .git

# Якщо дуже великий (>100MB) - потрібен git filter-branch
# Але для першого етапу можемо залишити історію
```

---

## ФАЗА 3: Structure Migration (LOCAL ONLY)

**Мета:** Перейменувати `wordpress/` → `wp/` локально
**Ризик:** 🟡 Середній (зміна структури)
**Можна відкотити:** ✅ Так (git restore + бекап БД)
**Тривалість:** 20-30 хв

### 3.1. Зупинити Docker

```bash
cd ~/Project/bsahlen.de

docker-compose down

# Перевірити що контейнери зупинені
docker ps | grep bsahlende
```

### 3.2. Бекап поточної wordpress/ папки

```bash
# На всяк випадок
cp -R wordpress wordpress.backup

# Перевірити розмір
du -sh wordpress.backup
```

### 3.3. Перейменувати wordpress/ → wp/

```bash
mv wordpress wp

# Перевірити
ls -la wp/
```

### 3.4. Оновити .gitignore

```bash
# Замінити всі `wordpress/` на `wp/` в .gitignore
sed -i '' 's/wordpress\//wp\//g' .gitignore

# Перевірити
cat .gitignore | grep "wp/"
```

### 3.5. Git tracking update

```bash
# Git має відстежити переміщення
git add -A

git status
# Має показати: renamed: wordpress/* -> wp/*

git commit -m "refactor: rename wordpress/ to wp/ (SOP v2.0 structure)"

# НЕ pushити поки
```

---

## ФАЗА 4: Docker Update

**Мета:** Оновити docker-compose.yml під нову структуру
**Ризик:** 🟡 Середній (може пересоздати БД volume)
**Можна відкотити:** ✅ Так (є бекап БД)
**Тривалість:** 15-20 хв

### 4.1. Бекап docker-compose.yml

```bash
cp docker-compose.yml docker-compose.yml.old
```

### 4.2. Оновити docker-compose.yml

Змінити:

```yaml
services:
  wordpress:
    volumes:
      # OLD: - ./wordpress:/var/www/html:cached
      # NEW: Mount only wp-content (SOP v2.0 approach)
      - ./wp/wp-content:/var/www/html/wp-content:cached
      # Add wp-config
      - ./wp-config-local.php:/var/www/html/wp-config.php:ro
```

**⚠️ ПИТАННЯ:** Чи монтувати весь `wp/` чи тільки `wp-content`?
- Bootstrap.sh монтує тільки `wp-content`
- Але тоді WP Core береться з image
- Для міграції безпечніше весь `wp/`

**Рекомендація:** Монтувати весь `wp/` на час міграції:

```yaml
    volumes:
      - ./wp:/var/www/html:cached
      - ./php.ini:/usr/local/etc/php/conf.d/custom.ini:ro
```

### 4.3. Перевірити volume для БД

```bash
# Подивитись поточні volumes
docker volume ls | grep bsahlen

# Переконатись що volume має дані
docker volume inspect bsahlende_db_data
```

**Важливо:** Volume `db_data` повинен зберегтись (він не перестворюється якщо існує)

### 4.4. Запустити Docker з новою структурою

```bash
# Запустити
docker-compose up -d

# Чекаємо ~30 секунд
sleep 30

# Перевірити логи
docker-compose logs wordpress | tail -20
docker-compose logs db | tail -20

# Перевірити що контейнери запущені
docker ps
```

### 4.5. Тестування локального сайту

```bash
# Відкрити сайт
open http://localhost:8080

# Перевірити:
# ✅ Головна сторінка відкривається
# ✅ Внутрішні сторінки працюють
# ✅ Зображення показуються
# ✅ Меню працює
# ✅ Можна залогінитись в /wp-admin
# ✅ Elementor працює

# Якщо щось не працює - ЗУПИНИТИСЬ і діагностувати
```

### 4.6. Перевірка БД

```bash
# Перевірити що БД на місці
docker-compose run --rm wpcli db check

# Подивитись таблиці
docker-compose run --rm wpcli db tables
```

### 4.7. Commit Docker changes

```bash
git add docker-compose.yml .gitignore

git commit -m "chore: update docker-compose for wp/ structure"

# НЕ pushити поки
```

---

## ФАЗА 5: Local Testing & Validation

**Мета:** Переконатись що все працює локально
**Ризик:** 🟢 Немає (тільки тестування)
**Тривалість:** 30-40 хв

### 5.1. Функціональне тестування

**Checklist:**

- [ ] Головна сторінка завантажується (http://localhost:8080)
- [ ] Всі внутрішні сторінки працюють
- [ ] Зображення показуються
- [ ] Mega menu працює (hover, overlay, active states)
- [ ] Форми працюють (якщо є)
- [ ] Можна залогінитись в wp-admin (http://localhost:8080/wp-admin)
- [ ] Elementor editor відкривається
- [ ] Можна редагувати сторінку в Elementor
- [ ] Зміни зберігаються
- [ ] Child theme стилі працюють
- [ ] Custom JS працює (перевірити в консолі)

### 5.2. Технічна перевірка

```bash
# Перевірити шляхи в wp-config
docker-compose exec wordpress cat wp-config.php | grep "ABSPATH"

# Перевірити що WP бачить правильні шляхи
docker-compose run --rm wpcli option get home
docker-compose run --rm wpcli option get siteurl

# Має бути: http://localhost:8080
```

### 5.3. Перевірка child theme

```bash
# Перевірити functions.php на абсолютні шляхи
cat wp/wp-content/themes/bsahlen/functions.php

# Якщо є посилання на '../wordpress/' - замінити на '../wp/'
```

### 5.4. Elementor CSS Regeneration

```bash
# Зайти в wp-admin
open http://localhost:8080/wp-admin

# Elementor → Tools → Regenerate CSS & Data
# Клікнути "Regenerate Files"

# Hard refresh сайт
# Cmd+Shift+R (Mac) або Ctrl+Shift+R (Windows)
```

### 5.5. Бекап після успішного тестування

```bash
# Якщо все працює - бекап нової структури
docker-compose run --rm wpcli db export \
  /backups/POST_MIGRATION_LOCAL_$(date +%Y%m%d_%H%M%S).sql

ls -lh backups/POST_MIGRATION_LOCAL_*.sql
```

---

## ФАЗА 6: Git Finalization

**Мета:** Підготувати репозиторій до push
**Ризик:** 🟢 Низький
**Тривалість:** 10-15 хв

### 6.1. Додати всі нові файли

```bash
git status

# Переконатись що нічого зайвого
# Має бути:
# - PROJECT.md, SERVER_RULES.md, SOP.md (нові)
# - maintenance/ (нова папка)
# - index.php, .htaccess (нові, в root)
# - wp-config-local.php, wp-config-production.php (нові)
# - wp/ (перейменовано з wordpress/)
# - .gitignore (оновлено)
# - docker-compose.yml (оновлено)

git add .
```

### 6.2. Фінальний commit

```bash
git commit -m "feat: migrate to SOP v2.0 monorepo structure

BREAKING CHANGE: Project structure migrated to SOP v2.0

Changes:
- Renamed wordpress/ → wp/
- Added router (index.php + .htaccess) for MODE switching
- Added maintenance/ folder with landing page
- Created PROJECT.md, SERVER_RULES.md, SOP.md
- Created wp-config templates (local + production)
- Removed languages/ and 3rd party plugins from Git
- Updated docker-compose.yml for new structure
- Updated .gitignore per SOP v2.0

Migration docs:
- MIGRATION_AUDIT.md (analysis)
- MIGRATION_PLAN.md (step-by-step)

Status: ✅ Local tested, ready for production deployment

Co-Authored-By: Claude Sonnet 4.5 <noreply@anthropic.com>"
```

### 6.3. Перевірити що все закомічено

```bash
git status
# Має бути: "working tree clean"

# Подивитись що в останньому коміті
git show --stat
```

### 6.4. Push (з дозволу власника)

```bash
# ⚠️ ТІЛЬКИ з дозволу власника!

# Спочатку push бекап гілки (якщо ще не пушилась)
git push origin backup-pre-migration

# Потім main
git push origin main

# Перевірити на GitHub що все залилось правильно
```

---

## ФАЗА 7: Plesk Setup (Production Preparation)

**Мета:** Налаштувати Plesk Git auto-deploy
**Ризик:** 🟡 Середній (потрібна акуратність)
**Можна відкотити:** ✅ Так (не активовувати deploy)
**Тривалість:** 30-45 хв

### ⚠️ ВАЖЛИВО: Цю фазу робить ТІЛЬКИ власник з доступом до Plesk!

### 7.1. Підготовка на Plesk

1. **Зайти в Plesk:**
   - Домен bsahlen.de → Git → Manage

2. **Налаштувати Git репозиторій:**
   - Repository URL: `https://github.com/RomanPachkovskyi/bsahlen.de.git`
   - Branch: `main`
   - Deploy to: `/httpdocs`
   - Deployment mode: **Manual** (поки що НЕ automatic!)

3. **SSH Keys:**
   - Згенерувати SSH key в Plesk
   - Додати public key в GitHub (Settings → Deploy keys)
   - Або використати Personal Access Token

### 7.2. Тестовий clone (без deploy)

```bash
# В Plesk Git interface:
# 1. Клікнути "Pull Updates"
# 2. Подивитись логи - чи є помилки
# 3. НЕ ЗАПУСКАТИ deploy actions!
```

### 7.3. Зберегти поточну production структуру

**Вручну через Plesk File Manager:**

1. Зайти в `/httpdocs`
2. Створити папку `/httpdocs.backup.20260128`
3. Скопіювати туди ВСЕ з `/httpdocs`

**Або через FTP (якщо є доступ):**

```bash
# Локально (якщо є FTPS доступ)
# Завантажити ВСЕ з production

lftp -u "$FTP_USER,$FTP_PASS" -e "set ssl:verify-certificate no; \
  mirror --verbose /httpdocs ~/bsahlen-production-backup-$(date +%Y%m%d); \
  quit" "$FTP_HOST"
```

### 7.4. Бекап production БД

```bash
# В Plesk → Databases → Export
# Зберегти як bsahlen_prod_backup_20260128.sql
# Завантажити локально
```

---

## ФАЗА 8: Production Deployment (CRITICAL)

**Мета:** Активувати нову структуру на production
**Ризик:** 🔴 ВИСОКИЙ
**Можна відкотити:** ⚠️ Складно (потрібен підготовлений rollback)
**Тривалість:** 45-60 хв

### ⚠️ СТОП! Перед Phase 8:

**Checklist готовності:**
- [ ] Фази 0-7 завершені без помилок
- [ ] Локальний сайт працює ідеально з новою структурою
- [ ] Є свіжий бекап production файлів і БД
- [ ] Plesk Git налаштований і протестований
- [ ] Є план rollback (див. Phase 9)
- [ ] Власник підтверджує GO
- [ ] Є 1-2 години вільного часу БЕЗ перерв

### 8.1. Enable Maintenance Mode (опційно)

Якщо є плагін maintenance або можна змінити:

```bash
# Опція 1: WordPress maintenance mode
# Створити wp-content/.maintenance на production
```

Або пропустити цей крок якщо сайт малотрафічний.

### 8.2. Backup current production wp-config.php

**В Plesk File Manager:**
```
/httpdocs/wp-config.php → Download → Зберегти локально
```

### 8.3. Deploy via Plesk Git

**В Plesk → Git:**

1. Перевірити branch: `main`
2. Клікнути **"Pull Updates"**
3. Подивитись логи - чи є помилки
4. Якщо OK → клікнути **"Deploy"**

**⚠️ Що станеться:**
- Plesk створить нову структуру в `/httpdocs`
- Старі файли будуть перезаписані
- НО: uploads та wp-config.php мають залишитись (вони в .gitignore)

### 8.4. Перевірити structure після deploy

**Plesk File Manager → /httpdocs:**

Очікувана структура:
```
/httpdocs/
├── index.php              ← NEW (router)
├── .htaccess              ← NEW (routing rules)
├── wp/                    ← NEW (renamed from root WP)
│   ├── wp-content/
│   │   ├── themes/
│   │   ├── plugins/
│   │   └── uploads/       ← OLD (має залишитись)
│   └── wp-config.php      ← OLD (має залишитись)
├── maintenance/           ← NEW
├── docker-compose.yml     ← NEW (не використовується на production)
└── ...
```

### 8.5. Виправити wp-config.php paths

**⚠️ КРИТИЧНО:** wp-config.php може мати старі шляхи!

**Plesk File Manager → Edit `/httpdocs/wp/wp-config.php`:**

```php
// Перевірити і виправити:
define('ABSPATH', dirname(__FILE__) . '/');  // Має бути так

// Перевірити URL:
define('WP_HOME',    'https://bsahlen.de');
define('WP_SITEURL', 'https://bsahlen.de/wp');  // Якщо WP в піддиректорії
// АБО
define('WP_HOME',    'https://bsahlen.de');
define('WP_SITEURL', 'https://bsahlen.de');     // Якщо WP в корені
```

**⚠️ ПИТАННЯ ДЛЯ ВЛАСНИКА:** Де має бути WordPress після міграції?
- Варіант A: `https://bsahlen.de` (в корені через router)
- Варіант B: `https://bsahlen.de/wp` (в піддиректорії)

**Рекомендація:** Варіант A (router керує всім)

### 8.6. Перевірити router MODE

**Plesk File Manager → Edit `/httpdocs/index.php`:**

```php
// Переконатись:
define('MODE', 'live');  // НЕ 'maintenance'!
```

### 8.7. Test production site

```bash
# Локально або в браузері
curl -I https://bsahlen.de

# Має повернути: HTTP/2 200
# НЕ 404, НЕ 500!

# Відкрити в браузері
open https://bsahlen.de
```

**Checklist тестування:**
- [ ] Головна сторінка відкривається
- [ ] Стилі завантажились (якщо ні → Elementor regenerate CSS!)
- [ ] Меню працює
- [ ] Внутрішні сторінки працюють
- [ ] Зображення показуються
- [ ] Можна залогінитись в wp-admin

### 8.8. Elementor CSS Regeneration (ОБОВ'ЯЗКОВО!)

```bash
# Зайти в production wp-admin
open https://bsahlen.de/wp-admin

# Elementor → Tools → Regenerate CSS & Data
# Клікнути "Regenerate Files"

# Hard refresh сайт
# Cmd+Shift+R
```

### 8.9. Перевірка всіх критичних сторінок

Відкрити і перевірити:
- [ ] Головна: https://bsahlen.de
- [ ] Усі пункти меню
- [ ] Контактна форма (якщо є)
- [ ] Блог/новини
- [ ] SEO meta tags (View Page Source)

### 8.10. Disable Maintenance Mode

Якщо вмикали на кроці 8.1 - вимкнути.

---

## ФАЗА 9: Rollback Plan (На випадок проблем)

**Використовувати ТІЛЬКИ якщо щось пішло не так у Фазі 8!**

### 9.1. Швидкий rollback (Git)

**Якщо проблема в коді:**

```bash
# Локально
cd ~/Project/bsahlen.de

# Повернутись до бекапу
git checkout backup-pre-migration

# Пуш force (ТІЛЬКИ якщо критична ситуація!)
git push origin main --force

# Plesk → Git → Pull Updates → Deploy
```

### 9.2. Повний rollback (Files + DB)

**Якщо Git rollback не допоміг:**

1. **Plesk File Manager:**
   - Видалити `/httpdocs/*`
   - Скопіювати `/httpdocs.backup.20260128/*` → `/httpdocs/`

2. **Відновити БД:**
   - Plesk → Databases → Import
   - Вибрати `bsahlen_prod_backup_20260128.sql`

3. **Перевірити сайт:**
   - https://bsahlen.de

### 9.3. Локальний rollback

Якщо щось зламалось локально:

```bash
cd ~/Project/bsahlen.de

# Зупинити Docker
docker-compose down

# Відновити стару структуру
rm -rf wp
mv wordpress.backup wordpress

# Відновити БД
docker-compose up -d
sleep 30
docker-compose run --rm wpcli db import \
  /backups/PRE_MIGRATION_20260128_HHMMSS.sql

# Відновити старі конфіги
git checkout pre-migration-backup -- docker-compose.yml .gitignore

# Запустити
docker-compose restart
```

---

## ФАЗА 10: Post-Migration Cleanup

**Після успішної міграції (через 1-2 дні стабільної роботи)**

### 10.1. Видалити бекапи (опційно)

```bash
# Локально
rm -rf wordpress.backup
rm docker-compose.yml.old
rm MIGRATION_STATE.txt

# Production (Plesk File Manager)
# Видалити /httpdocs.backup.20260128
```

### 10.2. Оновити документацію

```bash
# Оновити CLAUDE.md
# - Видалити секцію про міграцію
# - Оновити структуру проєкту

# Оновити PROJECT.md
# - Project State: LIVE
# - Додати запис в Changelog про успішну міграцію
# - Видалити "Open Questions" що були вирішені

# Commit
git add CLAUDE.md PROJECT.md
git commit -m "docs: update after successful migration to SOP v2.0"
git push
```

### 10.3. Налаштувати Plesk Git auto-deploy

**Після 1-2 днів стабільної роботи:**

```bash
# Plesk → Git → Settings
# Deployment mode: Manual → Automatic

# Тепер кожен push в main автоматично деплоїться
```

### 10.4. Видалити старі гілки і теги (опційно)

```bash
# Після 1 тижня стабільної роботи
git branch -D backup-pre-migration
git push origin --delete backup-pre-migration

# Теги можна залишити для історії
```

---

## Чеклист підсумків

### ✅ Успішна міграція означає:

- [ ] Локально: сайт працює з структурою `wp/`, router, docker-compose оновлено
- [ ] Git: нова структура закомічена і запушена
- [ ] Production: Git auto-deploy налаштовано (manual mode)
- [ ] Production: deploy виконано, сайт працює
- [ ] Production: всі сторінки відкриваються, стилі працюють
- [ ] Production: Elementor CSS регенеровано
- [ ] Production: wp-config.php має правильні шляхи
- [ ] Документація: PROJECT.md, SERVER_RULES.md, SOP.md створені і актуальні
- [ ] Бекапи: є свіжі бекапи до і після міграції

### ⚠️ Якщо щось НЕ так:

1. **НЕ ПАНІКУВАТИ**
2. Подивитись логи (Plesk, Docker)
3. Перевірити Phase 9 (Rollback Plan)
4. Якщо потрібно - відкотити до backup-pre-migration
5. Задокументувати проблему в MIGRATION_AUDIT.md → "Known Issues"

---

## Контакти і підтримка

**Документація:**
- `MIGRATION_AUDIT.md` - Аналіз і ризики
- `MIGRATION_PLAN.md` - Цей документ (покрокова інструкція)
- `MIGRATION.md` - Загальний посібник для міграції старих проектів
- `SOP_v2.md` - Повний SOP стандарт студії

**GitHub:**
- Repository: https://github.com/RomanPachkovskyi/bsahlen.de
- Issues: https://github.com/RomanPachkovskyi/bsahlen.de/issues

---

**Версія:** 1.0 DRAFT
**Статус:** 🟡 Очікує підтвердження власника
**Останнє оновлення:** 2026-01-28
