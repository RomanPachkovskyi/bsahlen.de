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
- **gh** (GitHub CLI) — авторизований як RomanPachkovskyi

---

## Перевірки 2026-01-20

- **WordMove:** встановлено (user gem); PATH `~/.gem/ruby/2.6.0/bin`
- **lftp:** встановлено (`/usr/local/bin/lftp`)
- **Movefile:** відсутній у репозиторії
- **.env:** існує і не порожній (креденшли зберігаються тут)
- **PATH:** рядок для WordMove додано у `~/.zshrc`

---

## Workflow (заплановано)

```
1. Працюємо локально (localhost:8080)
2. Тестуємо зміни
3. WordMove push --themes/--plugins на продакшен
4. Якщо на хостингу оновили WP/плагіни:
   WordMove pull --wordpress --plugins
```

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
- `bsa-mega-overlay` — темний overlay з blur
- `bsa-mega-open` — на body коли меню відкрите
- `bsa-mega-active` — на активному пункті меню

**Кольори:**
- Неактивні пункти при відкритому меню: `#f7f5f1` (світлий)
- Активний пункт: `#233D3A` (темний) + білий фон

---

## Що ще потрібно зробити

1. [x] Встановити WordMove (Ruby gem)
2. [ ] Створити Movefile для синхронізації
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
