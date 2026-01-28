# SOP: Basics — Структура, Git, Docker

**Studio Standard Workflow (v2.0)**

---

## 0. Ключова умова (фундамент)

- **Доступ до адмін-панелі сайту має ТІЛЬКИ власник проєкту.**
- Клієнти та сторонні особи доступу не мають.

Це дозволяє контрольовану двосторонню синхронізацію БД за потреби.

---

## 1. Філософія та Source of Truth

| Що | Де |
|----|-----|
| **Код** | GitHub (єдине джерело правди) |
| **Контент / SEO / Медіа** | Production (хостинг) |
| **Розробка** | Локальне середовище (90% роботи) |

> Локалка ≠ копія продакшна. Локалка = майстерня.

---

## 2. Структура проєкту (Монорепозиторій)

```
/project/
├── index.php                 ← Router (MODE switching) ✅ Git
├── .htaccess                 ← Routing rules ✅ Git
├── wp/                       ← WordPress
│   ├── wp-content/
│   │   ├── themes/           ← ✅ Git
│   │   ├── mu-plugins/       ← ✅ Git
│   │   ├── plugins/custom-*  ← ✅ Git (тільки кастомні)
│   │   └── uploads/          ← ❌ НЕ Git
│   ├── wp-admin/             ← ❌ НЕ Git (WP Core)
│   ├── wp-includes/          ← ❌ НЕ Git (WP Core)
│   └── wp-config.php         ← ❌ НЕ Git (env-specific)
├── maintenance/              ← Landing page ✅ Git
│   └── index.html
├── backups/                  ← DB dumps ❌ НЕ Git
├── docs/                     ← Documentation ✅ Git
├── docker-compose.yml        ← ✅ Git
├── wp-config-local.php       ← ✅ Git (template)
├── wp-config-production.php  ← ✅ Git (template)
├── CLAUDE.md                 ← ✅ Git (AI instructions)
├── PROJECT.md                ← ✅ Git (knowledge base)
└── SERVER_RULES.md           ← ✅ Git (hosting rules)
```

---

## 3. Локальне середовище

- Docker з фіксованим портом (визначається при bootstrap)
- Стандартний URL: `http://localhost:PORT`
- phpMyAdmin: `http://localhost:PORT+1`

### Локально виконується:
- структура сайту
- тексти сторінок
- SEO (title, description, schema)
- верстка (Elementor / theme)
- стилі, логіка, кастомний код

**Локальний сайт = 90–95% фінального.**

> **STOP-RULE:** якщо інструкція неясна або бракує даних — будь-які дії зупиняються до уточнення у власника.

---

## 4. Git — правила

### 4.1 Що зберігається в Git

**✅ В Git:**
- `index.php` (router)
- `.htaccess`
- `wp/wp-content/themes/*` (всі теми, включно з parent)
- `wp/wp-content/mu-plugins/*`
- `wp/wp-content/plugins/custom-*` (тільки кастомні плагіни)
- `maintenance/*`
- Docker конфіги (`docker-compose.yml`, `php.ini`)
- Config templates (`wp-config-local.php`, `wp-config-production.php`)
- Документація (PROJECT.md, SERVER_RULES.md, CLAUDE.md, docs/)

#### Плагіни в Git: Детальні правила

**✅ В Git:**
- `wp/wp-content/plugins/custom-*` - будь-які custom плагіни
- Плагіни створені студією з нуля
- Приватні плагіни (недоступні в WP repo)

**❌ НЕ в Git:**
- Публічні плагіни з wordpress.org
- Преміум плагіни (Elementor Pro, ACF Pro, etc.)
- Будь-які плагіни що можна встановити через WP Admin

**⚠️ Особливі випадки:**
- Якщо 3rd party плагін має customizations → fork + rename до `custom-*`
- Якщо плагін недоступний (deprecated) → обговорити з командою

### 4.2 Що ЗАБОРОНЕНО в Git

**❌ НЕ в Git:**
- `wp/wp-content/uploads/`
- `wp/wp-content/languages/` (auto-downloaded by WordPress)
- `wp/wp-admin/`, `wp/wp-includes/` (WP Core)
- `wp/wp-config.php` (активний конфіг)
- `backups/` або `_db/` (database dumps)
- `.env` файли з секретами

### 4.3 Доступ до Git

| Роль | Дозволено |
|------|-----------|
| **Власник** | Commit, Push, Merge (через GitHub Desktop) |
| **ШІ** | Редагувати файли локально, готувати commit message |

> ШІ **НЕ має права** виконувати `git push`, `git merge`, `git rebase`.

---

## 5. Гілки

- **main** — єдина продакшн-гілка
- Plesk тягне саме `main`
- Feature-гілки (`feature/*`) — опційно, для великих змін
- `dev`-гілка **НЕ використовується**

---

## 6. Документація проєкту

### 6.1 PROJECT.md (веде ШІ автоматично!)

**Що містить:**
- Опис проєкту
- Домени та URL
- **Project State:** BUILD / LANDING / LIVE
- Tech stack
- **Changelog** (оновлюється після кожної значної зміни)
- DB Sync Notes
- Open Questions / Blockers

**⚠️ ВАЖЛИВО для ШІ:**
- PROJECT.md — це **база знань проекту**
- ШІ **ЗОБОВ'ЯЗАНИЙ** оновлювати PROJECT.md після будь-яких значних змін
- Додавати в Changelog з датою та описом
- Оновлювати Tech Stack якщо додано нові інструменти

### 6.2 SERVER_RULES.md

**Що містить:**
- Структура на хостингу
- Режими (maintenance / live)
- Чеклист Go-Live
- Чеклист Rollback

### 6.3 CLAUDE.md

**Що містить:**
- Інструкції для ШІ (універсальні, auto-detect)
- Структура проєкту
- Дозволені/заборонені дії
- STOP rules
- Правила автодокументації

---

## 7. Для ШІ (обов'язково)

### ШІ зобов'язаний:

1. **Працювати за SOP**
2. **Автоматично оновлювати PROJECT.md:**
   - Після додавання Redis/нових сервісів → Tech Stack
   - Після значних змін → Changelog
   - Після змін структури → Structure
3. **Коментарі в коді — тільки англійською**
4. **Перевіряти PROJECT.md перед початком роботи**
5. **Готувати commit message з детальним описом змін**

### ШІ заборонено:

- `git push`, `git merge`, `git rebase`
- Критичні дії без підтвердження власника
- Додавати в Git заборонені файли (uploads, languages, etc.)
- Робити зміни без документації в PROJECT.md

### STOP-RULE

> Якщо інструкції двозначні, бракує даних, або дія може вплинути на production — **ЗУПИНИТИСЬ і поставити запитання**.

---

## 8. Чеклист: Початок роботи над проєктом

ШІ виконує при кожному старті сесії:

- [ ] Прочитати `CLAUDE.md` (entry point)
- [ ] Прочитати `PROJECT.md` (база знань)
- [ ] Перевірити Project State (BUILD / LANDING / LIVE)
- [ ] Перевірити Open Questions / Blockers
- [ ] Прочитати `SERVER_RULES.md` (якщо потрібні серверні дії)
- [ ] Якщо чогось бракує — сформувати питання до власника

---

## 9. Bootstrap (старт нового проєкту)

### Команда:
```bash
./docs/scripts/bootstrap.sh
```

### Що робить:
1. Запитує назву проєкту, домен, порт
2. Створює повну структуру папок
3. Генерує всі конфіг-файли
4. Створює документацію (PROJECT.md, CLAUDE.md, etc.)

### Після bootstrap:
1. `git init && git add . && git commit`
2. Створити GitHub репо, push
3. Налаштувати Plesk Git deploy (див. [deployment.md](deployment.md))
4. Створити wp-config.php на хостингу

---

**Версія:** 2.1
**Останнє оновлення:** 2026-01-28
**Див. також:** [Deployment](deployment.md) | [Migration](migration.md) | [Improvements](improvements.md)
