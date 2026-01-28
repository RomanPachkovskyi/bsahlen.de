# SOP: Migration — Міграція старих проектів на SOP v2.0

**Studio Standard Workflow (v2.0)**

---

## Огляд

SOP v2.0 описує створення **нових** проектів через `bootstrap.sh`. Але що якщо у тебе вже є **існуючий проект** зі старою структурою?

Цей модуль описує **як мігрувати старі проекти на новий стандарт.**

---

## Коли потрібна міграція?

**Ознаки старого проекту:**

- ✅ Папка `wordpress/` замість `wp/`
- ✅ Немає router файлів (`index.php`, `.htaccess` в root)
- ✅ Немає `maintenance/` папки
- ✅ Стара документація (AGENTS.md, log/changelog.md, chats.md)
- ✅ Шляхи `~/GitHub/` замість `~/Project/`
- ✅ Git містить `wp-content/uploads/` або `wp-content/languages/`
- ✅ Немає PROJECT.md або застарілий CLAUDE.md

**Якщо 3+ ознаки збігаються → міграція потрібна!**

---

## Процес міграції (Overview)

```
Existing Project → Audit → Plan → Migrate Local → Test → Deploy Production
```

**10 фаз міграції:**

0. **Backup & Documentation** — повний backup + створення плану
1. **Creating New Files** — router, templates, документація
2. **Git Cleanup** — видалення languages/, 3rd party plugins
3. **Structure Migration** — rename wordpress/ → wp/
4. **Docker Update** — оновити docker-compose.yml
5. **Local Testing** — перевірка що все працює
6. **Git Finalization** — фінальний commit
7. **Plesk Setup** — налаштування Git deploy
8. **Production Deploy** — deploy на хостинг
9. **Validation** — перевірка і monitoring

---

## Quick Start

### 1. Прочитай детальну інструкцію

📖 **[MIGRATION.md](../migration/MIGRATION.md)** — 68-сторінковий універсальний посібник з:
- Детальними командами для кожної фази
- Troubleshooting
- Rollback процедурами
- Checklistами

### 2. Створи Project-Specific Plan

Кожен проект унікальний. Створи свій план міграції:

1. **Audit** — проаналізуй поточну структуру
2. **Identify Gaps** — що не відповідає SOP v2.0?
3. **Plan** — покроковий план для твого проекту

**Template:**
```bash
cp docs/migration/MIGRATION.md docs/migration/MIGRATION_PLAN_[project].md
```

### 3. Виконай міграцію

**⚠️ КРИТИЧНО:**
- Фази 0-6 виконуються **тільки локально**
- Production **НЕ чіпається** до Phase 7
- Завжди можна відкотити через Git tag `pre-migration-backup`

---

## Фаза 0: Backup & Documentation

**Найважливіша фаза — без неї НЕ починай!**

### Backup Local Database

```bash
# Export з Docker
docker-compose exec -T db mysqldump -u wp -pwp dbname > backups/PRE_MIGRATION_$(date +%Y%m%d).sql

# Перевірити що файл не порожній
ls -lh backups/PRE_MIGRATION_*.sql
```

### Git Backup

```bash
# Створити branch для backup
git checkout -b backup-pre-migration
git push origin backup-pre-migration

# Створити tag
git tag -a pre-migration-backup -m "Backup before SOP v2.0 migration"
git push origin pre-migration-backup

# Повернутись на main
git checkout main
```

### Production Backup

**⚠️ Обов'язково backup production!**

1. **Files:** Plesk → Backup Manager → Create Full Backup
2. **Database:** phpMyAdmin → Export

### Створити Audit

Проаналізуй поточну структуру:

```markdown
# MIGRATION_AUDIT.md

## Current State
- WordPress path: wordpress/ або wp/?
- Git repo size: ?
- Files in Git: скільки?
- Languages/ in Git: так/ні?
- 3rd party plugins in Git: які?

## Target State (SOP v2.0)
- WordPress path: wp/
- Router: index.php + .htaccess
- Maintenance: maintenance/index.html
- Docs: docs/ structure

## Gaps
- [ ] wordpress/ → wp/ rename
- [ ] Router missing
- [ ] Git cleanup needed (X files)
- [ ] Documentation restructure
```

---

## Фаза 1-6: Локальна міграція

**Детально описано в [MIGRATION.md](../migration/MIGRATION.md)**

### Checklist

- [ ] **Phase 1:** Router, templates, docs створені
- [ ] **Phase 2:** Git cleanup (languages, plugins removed)
- [ ] **Phase 3:** wordpress/ → wp/ rename
- [ ] **Phase 4:** docker-compose.yml updated
- [ ] **Phase 5:** Local testing passed
- [ ] **Phase 6:** Git commit prepared

**⚠️ Після Phase 6:**
- Local працює на SOP v2.0 ✅
- Production ще на старій структурі
- Можна зупинитись і продовжити пізніше

---

## Фаза 7: Plesk Git Setup

**⚠️ КРИТИЧНА ФАЗА - впливає на production!**

**Детальна інструкція:** [deployment.md § 2](deployment.md#2-plesk-git-setup)

### Ключові моменти:

1. **Mode:** Manual (НЕ Automatic!)
2. **Repository path:** `/httpdocs` (monorepo root)
3. **Branch:** `main`
4. **Test pull** перед deploy
5. **Backup production** перед deploy

---

## Фаза 8: Production Deploy

**⚠️ ТОЧКА НЕПОВЕРНЕННЯ - після deploy production на новій структурі!**

### Pre-Deploy Checklist

- [ ] Local повністю протестовано
- [ ] Production backup створено (Files + DB)
- [ ] Plesk Git налаштовано і протестовано (pull без deploy)
- [ ] wp-config-production.php готовий
- [ ] Rollback план зрозумілий
- [ ] Власник підтвердив deploy

### Deploy Process

**Крок 1: Backup Production**
```bash
# Files backup через Plesk
Plesk → Backup Manager → Create Full Backup

# DB backup
phpMyAdmin → Export → SQL
```

**Крок 2: Deploy через Plesk Git**
```bash
Plesk → Git → "Pull Updates" (перевірити log)
Plesk → Git → "Deploy" (MANUAL action)
```

**Крок 3: Fix wp-config.php**
```bash
# Plesk File Manager → /httpdocs/wp/wp-config.php
# Оновити шляхи:
# define('ABSPATH', dirname(__FILE__) . '/');  ← СТАРЕ (wordpress/)
# define('ABSPATH', dirname(__FILE__) . '/');  ← НОВЕ (wp/)
```

**Крок 4: Post-Deploy**
- [ ] Сайт відкривається?
- [ ] Головна сторінка працює?
- [ ] WP Admin доступний?
- [ ] Elementor CSS regenerate
- [ ] Перевірити ключові сторінки

### Якщо щось не так - Rollback!

**Швидкий rollback:**
```bash
# Відкотити Git deploy
Plesk → File Manager → Restore з backup

# Або через Git
git revert HEAD
git push origin main
Plesk → Git → Deploy
```

---

## Фаза 9: Validation

**Monitoring після deploy:**

**Перші 24 години:**
- [ ] Перевірити всі сторінки сайту
- [ ] Перевірити форми (contact, newsletter)
- [ ] Перевірити SEO meta tags (View Source)
- [ ] Перевірити Google Search Console - чи немає errors
- [ ] Monitor error logs (Plesk → Logs)

**Після 48 годин стабільної роботи:**
- [ ] Видалити старі backups (залишити 1-2 останні)
- [ ] Оновити PROJECT.md → Migration Status: ✅ Complete
- [ ] (Опційно) Plesk Git → Mode: Manual → Automatic

---

## Rollback Plan

**Якщо щось пішло не так на production:**

### Level 1: Quick Rollback (файли)

```bash
# Plesk File Manager
Restore з backup (Files only)
```

### Level 2: Git Rollback

```bash
# Local
git revert HEAD  # або git reset --hard <commit>
git push origin main

# Plesk
Git → Pull Updates → Deploy
```

### Level 3: Full Rollback (Files + DB)

```bash
# Plesk Backup Manager
Restore Full Backup (Files + DB)
```

**⚠️ Level 3 означає втрату всіх змін після backup!**

---

## Common Issues

### Issue 1: Git визнає wordpress/ → wp/ як delete + create

**Рішення:**
```bash
git mv wordpress wp
git commit -m "Rename wordpress to wp"
# Git збереже історію
```

### Issue 2: Uploads зникли після deploy

**Причина:** Git не міг видалити uploads (вони в .gitignore)
**Рішення:** Uploads залишаються на хостингу, все ОК

### Issue 3: Сайт показує 404 після deploy

**Причини:**
1. `.htaccess` неправильний
2. Router `index.php` не працює
3. WordPress в неправильній папці

**Рішення:**
```bash
# Перевірити .htaccess в /httpdocs/
RewriteRule ^ index.php [L]

# Перевірити index.php
$wpPath = $docRoot . '/wp';  # правильний шлях?
```

### Issue 4: Elementor без стилів

**Рішення:**
```bash
WP Admin → Elementor → Tools → Regenerate CSS & Data
```

---

## Lessons Learned

**З міграції bsahlen.de (2026-01-28):**

### ✅ Що спрацювало:

1. **Phases 0-6 локально** - безпечно, можна експериментувати
2. **Git mv** зберіг історію при wordpress/ → wp/
3. **Manual Plesk mode** - контроль кожного deploy
4. **Detailed backup** - 3 рівні backup (Git, Local DB, Production)

### ❌ Що не спрацювало:

1. **README.md** - не потрібен для ШІ-only workflow
2. **Багато SOP файлів** - плутанина (v2, improvements, quick ref)
3. **Без автодокументації** - ШІ не оновлював PROJECT.md
4. **Технічні файли в root** - засмічували структуру

### 📚 Recommendations:

1. Використовуй модульну SOP структуру (basics, deployment, migration)
2. ШІ ЗАВЖДИ оновлює PROJECT.md після змін
3. README.md мінімальний або видалити
4. Технічні файли в docs/

**Детальніше:** [improvements.md](improvements.md)

---

## Детальна документація

- 📖 [MIGRATION.md](../migration/MIGRATION.md) — 68-сторінковий посібник
- 📖 [MIGRATION_PLAN.md](../migration/MIGRATION_PLAN.md) — план для bsahlen.de (приклад)
- 📖 [MIGRATION_AUDIT.md](../migration/MIGRATION_AUDIT.md) — audit bsahlen.de (приклад)

---

## Template для нового проекту

**Створюючи migration plan для нового проекту:**

```bash
# 1. Створи папку
mkdir -p docs/migration

# 2. Copy template
cp docs/migration/MIGRATION.md docs/migration/MIGRATION_PLAN_[project].md

# 3. Створи audit
touch docs/migration/MIGRATION_AUDIT_[project].md

# 4. Заповни специфіку проекту
# 5. Виконуй фази по черзі
# 6. Документуй в PROJECT.md
```

---

**Версія:** 2.1
**Останнє оновлення:** 2026-01-28
**Див. також:** [Basics](basics.md) | [Deployment](deployment.md) | [Improvements](improvements.md)
