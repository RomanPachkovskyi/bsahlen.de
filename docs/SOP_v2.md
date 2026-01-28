# SOP: WordPress + Git + Plesk

**Studio Standard Workflow (v2.0 — Monorepo)**

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
├── _db/                      ← DB dumps ❌ НЕ Git
├── docker-compose.yml        ← ✅ Git
├── wp-config-local.php       ← ✅ Git
├── wp-config-production.php  ← ✅ Git (template)
├── PROJECT.md                ← ✅ Git
├── SERVER_RULES.md           ← ✅ Git
├── SOP.md                    ← ✅ Git
├── CLAUDE.md                 ← ✅ Git
└── README.md                 ← ✅ Git
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

- `index.php` (router)
- `.htaccess`
- `wp/wp-content/themes/*`
- `wp/wp-content/mu-plugins/*`
- `wp/wp-content/plugins/custom-*` (тільки кастомні плагіни)
- `maintenance/*`
- Docker конфіги
- Документація (PROJECT.md, SERVER_RULES.md, SOP.md, CLAUDE.md)

### 4.2 Що ЗАБОРОНЕНО в Git

- `wp/wp-content/uploads/`
- `wp/wp-admin/`, `wp/wp-includes/` (WP Core)
- `wp/wp-config.php` (активний конфіг)
- `_db/` (database dumps)
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

## 6. Deploy

### 6.1 Ланцюжок

```
Local → GitHub (main) → Plesk auto-pull → Production
```

**З Git деплоїться:**
- Router (`index.php`, `.htaccess`)
- Кастомний код (`themes`, `mu-plugins`, `plugins/custom-*`)
- Maintenance page

**НЕ деплоїться з Git:**
- WordPress Core (встановлюється через Plesk)
- Uploads (залишаються на хостингу)
- wp-config.php (створюється вручну на хостингу)

### 6.2 Plesk Git налаштування

| Параметр | Значення |
|----------|----------|
| Repository | `https://github.com/user/project` |
| Branch | `main` |
| Deploy to | `/httpdocs` (root) |
| Mode | Automatic |

### 6.3 Критичні дії (тільки з підтвердженням власника)

Наступні дії **НЕ можуть** виконуватись ШІ самостійно:
- DB import у production
- Зміна MODE на `'live'` (Go-Live)
- Будь-які зміни wp-config.php на хостингу

---

## 7. Режими роботи (Maintenance ↔ Live)

### 7.1 Два режими

**MODE = 'maintenance'** (за замовчуванням)
| Відвідувач | Бачить |
|------------|--------|
| Публічний | `/maintenance/index.html` |
| Адмін (залогінений) | WordPress |
| Прямий запит `/wp/*` | WordPress |

**MODE = 'live'**
| Відвідувач | Бачить |
|------------|--------|
| Усі | WordPress |

### 7.2 Як перемикати

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

### 7.3 Landing як повноцінна сторінка

- HTTP 200 (не 503)
- Індексується пошуковими системами
- Може використовуватись місяцями до запуску WP

---

## 8. Фото та медіа

- Фото додаються **на продакшні** через WP Admin
- Uploads **НЕ** зберігаються в Git
- Uploads **НЕ** видаляються при Git deploy (Plesk зберігає)

**Локально допускаються:**
- Placeholder-зображення
- Тестові фото для верстки

> Відсутність актуальних фото локально — нормальна ситуація.

---

## 9. База даних (DB)

### 9.1 Ключове правило

**Будь-який перенос БД між середовищами = заміна URL.**

| Напрям | Заміна |
|--------|--------|
| Production → Local | `https://domain.de` → `http://localhost:PORT` |
| Local → Production | `http://localhost:PORT` → `https://domain.de` |

### 9.2 Інструменти

- **WP-CLI** (якщо доступний): `wp search-replace`
- **Better Search Replace** (плагін)
- **SQL dump + sed** (для просунутих)

> Заміна має враховувати серіалізовані дані WordPress.

### 9.3 Контроль

Факт заміни URL фіксується в `PROJECT.md → DB Sync Notes`.

---

## 10. Дозволене на продакшні (WP Admin)

### Дозволено:
- Встановлення/оновлення плагінів
- Налаштування плагінів
- SEO-правки
- Редагування контенту
- Додавання медіа

### Заборонено:
- Правки PHP / JS / CSS файлів
- Правки теми або mu-plugins
- "Тимчасові" код-фікси

---

## 11. Документація проєкту

### 11.1 PROJECT.md (веде ШІ)
- Опис проєкту
- Домени та URL
- **Project State:** BUILD / LANDING / LIVE
- Changelog
- DB Sync Notes
- Blockers / Open Questions

### 11.2 SERVER_RULES.md (веде ШІ)
- Структура на хостингу
- Режими (maintenance / live)
- Чеклист Go-Live
- Чеклист Rollback

### 11.3 CLAUDE.md
- Інструкції для ШІ
- Структура проєкту
- Дозволені/заборонені дії

---

## 12. Коротка формула workflow

```
Bootstrap → Build local → Sync DB → Go live → Code via Git → Content via Admin
```

---

## 13. Bootstrap (старт нового проєкту)

### Команда:
```bash
./bootstrap.sh
```

### Що робить:
1. Запитує назву проєкту, домен, порт
2. Створює повну структуру папок
3. Генерує всі конфіг-файли
4. Створює документацію

### Після bootstrap:
1. `git init && git add . && git commit`
2. Створити GitHub репо, push
3. Налаштувати Plesk Git deploy
4. Створити wp-config.php на хостингу

---

## 14. Для ШІ (обов'язково)

### ШІ зобов'язаний:
- Працювати за цим SOP
- Вести PROJECT.md і SERVER_RULES.md
- Коментарі в коді — **тільки англійською**
- Перевіряти PROJECT.md перед початком роботи

### ШІ заборонено:
- `git push`, `git merge`, `git rebase`
- Критичні дії без підтвердження власника
- Додавати в Git заборонені файли

### STOP-RULE

> Якщо інструкції двозначні, бракує даних, або дія може вплинути на production — **ЗУПИНИТИСЬ і поставити запитання**.

---

## 15. Чеклист: Початок роботи над проєктом

ШІ виконує при кожному старті сесії:

- [ ] Прочитати `PROJECT.md`
- [ ] Перевірити Project State (BUILD / LANDING / LIVE)
- [ ] Перевірити Open Questions / Blockers
- [ ] Прочитати `SERVER_RULES.md` (якщо потрібні серверні дії)
- [ ] Якщо чогось бракує — сформувати питання до власника

---

## Версія документа

| Версія | Дата | Зміни |
|--------|------|-------|
| 1.3 | — | Оригінал (2 репо) |
| 2.0 | 2025-01 | Монорепозиторій, router в Git |
