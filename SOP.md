# SOP: WordPress + Git + Plesk

**Studio Standard Workflow (v2.1 — Modular)**

---

## 🎯 Почни тут

Ця документація складається з модулів. Вибери що тебе цікавить:

### 📘 Новий проект?

1. Прочитай **[Basics](docs/sop/basics.md)** — структура, Git, Docker, філософія
2. Запусти `docs/scripts/bootstrap.sh` для створення проекту
3. Подивись **[Deployment](docs/sop/deployment.md)** — як налаштувати Plesk і деплоїти

### 🔄 Міграція старого проекту?

1. Прочитай **[Migration](docs/sop/migration.md)** — покрокова інструкція міграції
2. Використай `docs/migration/MIGRATION.md` для детального плану
3. Подивись **[Improvements](docs/sop/improvements.md)** — чого не робити (lessons learned)

### 🚀 Готовий проект?

**Швидкий довідник:**
- **Git правила:** [Basics § 4](docs/sop/basics.md#4-git--правила)
- **Deploy процес:** [Deployment § 1](docs/sop/deployment.md#1-deploy-process)
- **MODE switching:** [Deployment § 3](docs/sop/deployment.md#3-режими-роботи-maintenance--live)
- **Plesk setup:** [Deployment § 2](docs/sop/deployment.md#2-plesk-git-setup-покрокова-інструкція)
- **Database sync:** [Deployment § 5](docs/sop/deployment.md#5-база-даних-db)
- **Для ШІ:** [Basics § 7](docs/sop/basics.md#7-для-ші-обовязково)

### 📚 Lessons Learned

- **[Improvements](docs/sop/improvements.md)** — 10 виявлених gaps в SOP v2.0 на основі реальної міграції bsahlen.de

---

## Модулі документації

| Модуль | Що містить | Для кого |
|--------|------------|----------|
| **[basics.md](docs/sop/basics.md)** | Структура, Git, Docker, філософія, документація | Всі |
| **[deployment.md](docs/sop/deployment.md)** | Plesk setup, deploy, MODE, DB, rollback | Deploy & Production |
| **[migration.md](docs/sop/migration.md)** | Міграція старих проектів на SOP v2.0 (10 фаз) | Migration |
| **[improvements.md](docs/sop/improvements.md)** | 10 gaps + рішення, lessons learned | Best practices |

---

## Quick Reference (bsahlen.de)

**Project specifics для цього проекту.**

### URLs

| Environment | URL | Status |
|-------------|-----|--------|
| Local | http://localhost:8080 | 🟢 Development |
| Production | https://bsahlen.de | 🟢 LIVE |

### Current MODE

```php
// index.php
define('MODE', 'live'); // Сайт публічний
```

### Tech Stack

- **WordPress:** Latest (PHP 8.2)
- **Theme:** Finovate (parent) + bsahlen (child)
- **Page Builder:** Elementor Pro
- **Database:** MariaDB 10.11 (production) / MySQL 8.0 (local)
- **Cache:** Redis 7 (local)
- **Hosting:** Plesk

### Deploy Flow

```
Local → GitHub (main) → Plesk manual pull → Production
```

**Owner actions:**
- Commit + Push to GitHub
- Plesk → Git → Pull Updates → Deploy (manual)

**ШІ actions:**
- Edit files locally
- Test thoroughly
- Prepare commit message
- Update PROJECT.md changelog

---

## Критичні правила

### ✅ Завжди

- Читати **PROJECT.md** перед початком роботи
- Оновлювати **PROJECT.md** після змін (автоматично для ШІ!)
- Тестувати локально перед commit
- Backup перед production deploy
- Regenerate Elementor CSS після структурних змін

### ❌ Ніколи

- `git push`, `git merge`, `git rebase` (тільки власник!)
- Додавати в Git: uploads/, languages/, .env, backups/
- Деплоїти на production без backup
- Змінювати MODE без підтвердження
- Робити зміни без документації в PROJECT.md

### ⚠️ STOP-RULE

**Зупинись і запитай власника якщо:**
- Інструкція неясна або двозначна
- Бракує даних для виконання
- Дія може вплинути на production
- Потрібен Git push
- Критична зміна (DB import, MODE change, etc.)

---

## Для ШІ: Автодокументація

**⚠️ КРИТИЧНО ВАЖЛИВО:**

ШІ **ЗОБОВ'ЯЗАНИЙ** оновлювати PROJECT.md після будь-яких значних змін:

### Коли оновлювати PROJECT.md:

1. **Tech Stack змінився:**
   ```markdown
   ## Tech Stack
   - **Cache:** Redis 7 (додано 2026-01-28)
   ```

2. **Changelog після кожної важливої зміни:**
   ```markdown
   ## Changelog
   | Date | Change | By |
   |------|--------|----|
   | 2026-01-28 | Додано Redis cache для performance | AI |
   ```

3. **Open Questions якщо щось неясно:**
   ```markdown
   ## Open Questions
   - Redis persistence strategy? (In-memory vs RDB)
   ```

4. **DB Sync Notes після операцій з БД:**
   ```markdown
   ## DB Sync Notes
   | Date | Direction | Reason | Notes |
   |------|-----------|--------|-------|
   | 2026-01-28 | Local → Prod | Deploy Redis | 157 replacements |
   ```

### Commit Message Format:

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Приклад:**
```
feat(cache): Add Redis for performance optimization

Changes:
- Added redis service to docker-compose.yml
- Configured Redis Cache plugin
- Updated environment variables for WP_REDIS_*

Tech Stack updated in PROJECT.md
Changelog updated with deployment notes

Co-Authored-By: Claude Sonnet 4.5 <noreply@anthropic.com>
```

**Детально:** [Basics § 7](docs/sop/basics.md#ші-зобовязаний)

---

## Команди (Docker)

```bash
# Navigate
cd ~/Project/bsahlen.de

# Start
docker-compose up -d

# Stop
docker-compose down

# Logs
docker-compose logs -f wordpress

# Access
open http://localhost:8080
open http://localhost:8081  # phpMyAdmin

# DB Backup
docker-compose exec -T db mysqldump -u wp -pwp bsahlen > backups/backup_$(date +%Y%m%d).sql

# Container status
docker ps
```

---

## Структура документації

```
~/Project/bsahlen.de/
├── CLAUDE.md              ← AI entry point (universal, auto-detect)
├── PROJECT.md             ← Knowledge base ⭐ (single source of truth)
├── SOP.md                 ← This file (navigator)
├── SERVER_RULES.md        ← Hosting rules, Go-Live checklist
├── docs/
│   ├── sop/               ← Modular SOP
│   │   ├── basics.md      ← Git, Docker, структура
│   │   ├── deployment.md  ← Plesk, deploy, MODE, DB
│   │   ├── migration.md   ← Міграція старих проектів
│   │   └── improvements.md ← Lessons learned (10 gaps)
│   ├── migration/         ← Migration docs
│   │   ├── MIGRATION.md   ← Universal guide (68 pages)
│   │   └── MIGRATION_PLAN.md ← Project-specific plan
│   └── scripts/
│       └── bootstrap.sh   ← New project creator
└── index.php              ← Router (MODE switching)
```

---

## Історія версій

| Версія | Дата | Зміни |
|--------|------|-------|
| 1.3 | — | Оригінал (2 репо) |
| 2.0 | 2025-01 | Монорепозиторій, router в Git |
| **2.1** | **2026-01** | **Модульна структура, автодокументація, 10 improvements** |

**Що нового в v2.1:**
- ✅ Модульна структура SOP (basics, deployment, migration, improvements)
- ✅ Детальні інструкції для міграції існуючих проектів
- ✅ Plesk Git setup з 8 кроками
- ✅ Rollback procedures (3 рівні)
- ✅ Автодокументація для ШІ (обов'язкове оновлення PROJECT.md)
- ✅ 10 виявлених gaps вирішено
- ✅ Lessons learned з реального проекту

---

## Підтримка

**GitHub:** https://github.com/RomanPachkovskyi/bsahlen.de
**Issues:** https://github.com/RomanPachkovskyi/bsahlen.de/issues

**Документація:**
- Прочитай модулі в `docs/sop/`
- Використай checklist з `docs/migration/`
- Подивись приклади в PROJECT.md

---

**Версія:** 2.1 (Modular)
**Останнє оновлення:** 2026-01-28
**Next:** Прочитай [basics.md](docs/sop/basics.md) або [migration.md](docs/sop/migration.md)
