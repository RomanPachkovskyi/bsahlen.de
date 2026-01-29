# AI Instructions

> **Entry point для ШІ (Claude, Cursor, Copilot, etc.)**

---

## Читай в такому порядку

1. **`SOP.md`** — стандарт роботи (Git, Docker, Deploy, правила)
2. **`PROJECT.md`** — база знань цього проекту (статус, tech stack, changelog)

---

## Структура проекту

```
[project]/
├── README.md          ← Ти тут (entry point)
├── SOP.md             ← Стандарт роботи
├── PROJECT.md         ← База знань проекту
├── index.php          ← Router
├── wp/                ← WordPress
├── maintenance/       ← Landing page
├── docker-compose.yml ← Docker config
└── docs/              ← Додаткова документація
```

---

## Quick Start

```bash
# Перевірити Docker
docker ps

# Запустити
cd ~/Project/[project-name]
docker-compose up -d

# Відкрити
open http://localhost:[port]
```

---

## ШІ зобов'язаний

1. Читати `SOP.md` і `PROJECT.md` перед роботою
2. Вести `PROJECT.md` (changelog, tech stack, open questions)
3. Коментарі в коді — тільки англійською
4. Готувати детальні commit messages

---

## ШІ заборонено

- `git push`, `git merge`, `git rebase` (тільки власник!)
- Критичні дії без підтвердження
- Зміни на production без тестування

---

## STOP-RULE

**Зупинись і запитай якщо:**
- Інструкція неясна
- Дія може вплинути на production
- Потрібен push або критична зміна

---

**Далі:** Читай `SOP.md` → `PROJECT.md`
