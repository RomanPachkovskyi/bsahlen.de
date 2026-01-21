# bsahlen.de - Проект WordPress

---

## ВАЖЛИВО: Правила ведення документації

### База знань (CLAUDE.md)
- **Оновлювати** після кожної сесії
- Писати **максимально детально**
- Додавати нові секції при потребі
- Зберігати **єдиний стиль** оформлення (заголовки, таблиці, код-блоки)

### Лог змін (log/changelog.md)
- Записувати **ВСЕ** що обговорюємо
- **Супер-детально**: команди, помилки, рішення, причини
- Вказувати **час** кожної дії
- Структура: `### ЧАС - Назва кроку` → опис → код → результат
- Нова сесія = новий розділ `## ДАТА: Опис сесії`

### Стиль оформлення
- Заголовки: `#` для назви, `##` для розділів, `###` для підрозділів
- Код: завжди в блоках ` ```bash ` або ` ```php `
- Таблиці: для порівнянь і структурованих даних
- Списки: `-` для пунктів, `1.` для послідовностей
- **Жирний** для важливого, `код` для команд/файлів

### Код
- **Коментарі в коді — ТІЛЬКИ англійською мовою**
- Назви змінних/функцій — англійською
- Child theme: `bsahlen` (не bsahlen-custom)

---

## Статус
- **Локальне середовище:** налаштовано і працює
- **Docker:** запущено (WordPress + MySQL + phpMyAdmin)
- **Git:** репозиторій ініціалізовано; перший push успішний після видалення `instagram-feed.json`
- **Файли:** скачано з продакшену через FTP (748 MB, 13,072 файлів)
- **База даних:** імпортована з продакшену (105 MB)
- **Дата налаштування:** 2026-01-19

---

## Локальні URL
- **Сайт:** http://localhost:8080
- **Адмінка:** http://localhost:8080/wp-admin
- **phpMyAdmin:** http://localhost:8081

---

## Docker команди
```bash
cd ~/GitHub/bsahlen.de

# Запустити
docker-compose up -d

# Зупинити
docker-compose down

# Логи
docker-compose logs -f

# Перезапуск
docker restart bsahlende-wordpress-1
```

---

## WP-CLI через Docker

```bash
# Інформація про WP-CLI
docker-compose run --rm wpcli --info

# Підготовка SQL для продакшену (URL replace, без зміни локальної БД)
docker-compose run --rm wpcli search-replace \
  'http://localhost:8080' 'https://bsahlen.de' \
  --skip-columns=guid --all-tables \
  --export=/backups/bsahlen.prod.sql
```

---

## Корисні команди

### Бекап БД (перед важливими змінами)
```bash
# Експорт БД з Docker з timestamp
docker-compose run --rm wpcli db export /backups/backup_$(date +%Y%m%d_%H%M%S).sql
```

### Безпека Git (перевірка .env)
```bash
# Перевірити що не комітимо .env або інші секрети
git status | grep -E '\.env|credentials|password'

# Подивитись що в staging перед commit
git diff --cached --name-only
```

### Очистка старих бекапів
```bash
# Показати всі бекапи старші за 7 днів
find backups -name "backup_*.sql" -mtime +7

# Видалити бекапи старші за 7 днів (обережно!)
find backups -name "backup_*.sql" -mtime +7 -delete
```

---

## Git

- **Гілка:** `main`
- **Remote:** `https://github.com/RomanPachkovskyi/bsahlen.de.git`
- **Локальний user.name/user.email:** `Roman Pachkovskyi` / `rpachkovskyi@gmail.com`
- **.gitignore:** WordPress core, uploads, cache, `wp-config.php`, `.env`, `backups/*.sql`, `wordpress/wp-content/themes/finovate/samples/instagram-feed.json`
- **Push Protection:** токен видалено (файл `instagram-feed.json` вилучено з репозиторію)

---

## Структура проекту
```
~/GitHub/bsahlen.de/
├── docker-compose.yml      # Docker конфігурація
├── .gitignore              # Git виключення
├── .env                    # Креденшли продакшену (НЕ комітити!)
├── CLAUDE.md               # Цей файл
├── wordpress/              # WordPress (весь сайт)
│   ├── wp-config.php       # Локальні налаштування БД
│   ├── wp-content/
│   │   ├── themes/finovate/  # Активна тема
│   │   ├── plugins/
│   │   └── uploads/
│   └── ...
└── backups/
    └── wp_ynu3n.sql        # Дамп бази з продакшену
```

---

## База даних

### Локальна (Docker)
- Host: `db`
- Database: `bsahlen`
- User: `wp`
- Password: `wp`
- Table prefix: `XutfWi7d_`

### Продакшен (Plesk)
- Host: `localhost:3306` (MariaDB v10.11.13)
- Database: `wp_ynu3n`
- User/Password: див. `.env`

---

## Хостинг (Plesk)

### FTP доступ
- Протокол: FTPS (SSL обов'язковий)
- Креденшли: див. `.env`
- Шлях: `/httpdocs`

### SSH
- **Заборонено** хостером

### Сервер
- IP: 81.209.248.242
- SSL: Let's Encrypt
- URL: https://bsahlen.de

---

## wp-config.php

**ВАЖЛИВО:** wp-config.php різний на локалці і продакшені!

Файл в `.gitignore` — не синхронізується.

При деплої WordMove автоматично пропускає wp-config.php.

---

## Тема

- **Назва:** Finovate
- **Шлях:** `wordpress/wp-content/themes/finovate/`
- **Тип:** Преміум тема з Elementor

---

## Інструменти встановлені

- **Docker Desktop** — локальний сервер
- **lftp** — FTP клієнт для синхронізації
- **WordMove** — встановлено як user gem (потрібен PATH `~/.gem/ruby/2.6.0/bin`)
- **PHP CLI** — встановлено (Homebrew, `/usr/local/bin/php`)
- **WP-CLI** — встановлено (`/usr/local/bin/wp`)
- **gh** (GitHub CLI) — авторизований як RomanPachkovskyi

---

## Перевірки 2026-01-20

- **WordMove:** встановлено (user gem); PATH `~/.gem/ruby/2.6.0/bin`
- **lftp:** встановлено (`/usr/local/bin/lftp`)
- **Movefile:** створено (sql_adapter: `default`, використовує `.env`)
- **.env:** існує і не порожній (креденшли зберігаються тут)
- **PATH:** рядок для WordMove додано у `~/.zshrc`
- **PHP CLI:** встановлено (`/usr/local/bin/php`)
- **WP-CLI:** працює, але є warning через PHP 8.5 (`react/promise` deprecation)

---

## Workflow деплою

### Локальна розробка → Production

```bash
# 1. Працюємо локально (localhost:8080)
# 2. Тестуємо зміни

# 3. Експорт БД з заміною URL
docker-compose run --rm wpcli search-replace \
  'http://localhost:8080' 'https://bsahlen.de' \
  --skip-columns=guid --all-tables \
  --export=/backups/bsahlen.prod.sql

# 4. Заливання child theme через lftp
lftp -u "bsahlen.de_ftp,***" -e "set ssl:verify-certificate no; set ftp:ssl-force true; \
  mirror -R --verbose wordpress/wp-content/themes/bsahlen httpdocs/wp-content/themes/bsahlen; quit" \
  81.209.248.242

# 5. Імпорт БД на хостингу (Plesk → phpMyAdmin)
# 6. Активувати child theme (wp-admin → Appearance → Themes)
```

**Примітка:** WordMove має проблеми з Ruby dependencies — використовуємо WP-CLI + lftp напряму.

### Останній деплой
- **Дата:** 2026-01-20
- **Що залито:** Child theme bsahlen (mega menu hover фікси)
- **БД:** Оновлено з localhost URL → production URL
- **Статус:** ✅ Все працює на https://bsahlen.de

---

## Child Theme: bsahlen

**Шлях:** `wordpress/wp-content/themes/bsahlen/`

```
bsahlen/
├── style.css           ← Custom CSS
├── functions.php       ← Enqueue styles/scripts
└── assets/
    └── js/
        └── custom.js   ← Custom JavaScript
```

### Mega Menu система

**Класи:**
- `bsa-mega-overlay` — темний overlay з blur (opacity 0.5, blur 3px)
- `bsa-mega-open` — на body коли меню відкрите
- `bsa-mega-active` — на активному пункті меню (чиє mega menu відкрите)
- `.e-current` — пункт меню поточної сторінки (WordPress)

**Hover логіка:**
- Elementor hover: `#F7F5F1` (світло-бежевий), padding 12px/14px, border-radius 30px
- Активний пункт (`.bsa-mega-active`) — **завжди** має Elementor hover стилі (зафіксовано через CSS)
- При hover на будь-який пункт → текст темний `#233D3A`
- Поточна сторінка (`.e-current`) — завжди темний текст `#233D3A` коли mega menu відкрите

**Кольори тексту:**
- Неактивні пункти при відкритому меню: `#f7f5f1` (світлий)
- Активний пункт / hover / поточна сторінка: `#233D3A` (темний)

---

## Що ще потрібно зробити

1. [x] Встановити WordMove (Ruby gem)
2. [x] Створити Movefile для синхронізації
3. [x] Налаштувати Git репозиторій
4. [ ] Доробити mega-menu UI (тестування)

---

## Команди для швидкого старту наступної сесії

```bash
# 1. Перевірити чи Docker запущено
docker ps

# 2. Якщо контейнери не запущені
cd ~/GitHub/bsahlen.de && docker-compose up -d

# 3. Відкрити сайт
open http://localhost:8080
```

---

## Примітки

- Логін в адмінку — той самий що на продакшені (bsahlen.de)
- FTP повільний через SSL + багато дрібних файлів
- SSH заборонено на хостингу — використовуємо FTP
- Docker акаунт не потрібен для роботи (вийшли з нього)
- WordMove встановлено як user gem: додати `export PATH="$HOME/.gem/ruby/2.6.0/bin:$PATH"` у shell профіль або запускати `~/.gem/ruby/2.6.0/bin/wordmove`
- WordMove 5.2.2: за замовчуванням адаптер `wpcli`; потрібен WP-CLI або зміна адаптера в Movefile
- WP-CLI під PHP 8.5 показує deprecation warning (react/promise); на роботу не впливає
- Movefile використовує `global.sql_adapter: default` (файловий sync без WP-CLI; для DB sync потрібен доступ до MySQL з хоста або запуск WordMove у контейнері)
- Для роботи з локальною БД використовувати `docker-compose run --rm wpcli ...` (контейнер бачить `db`)
