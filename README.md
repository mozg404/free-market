# 🚀 Marketplace Demo Project

## 📋 О проекте

Демонстрационный маркетплейс для продажи цифровых ключей. Проект создан для демонстрации навыков full-stack разработки на современном стеке технологий.

## 🛠 Технологический стек

**Backend:** Laravel 12, PostgreSQL, Meilisearch, Mailpit  
**Frontend:** Vue 3, Inertia.js 2, Tailwind CSS 4, ShadCN/Vue, JavaScript ES6+  
**Инфраструктура:** Docker, Laravel Sail, Makefile

## ⚡ Быстрый старт

```bash
git clone https://github.com/mozg404/free-market.git
cd free-market
make init
```

После установки запустите в **отдельных терминалах:**

```bash
# Терминал 1: Очереди
make queue

# Терминал 2: Планировщик  
make schedule

# Терминал 3: Фронтенд
make frontend
```

Основная часть данных генерируется именно в очереди, однако для просмотра достаточно и того, что создаст сидер на горячую, поэтому минимальный набор команд будет:

```bash
make init
make frontend
```
**Примечание:** Если порты 80, 5432, 1025 или 8025 заняты на вашей машине, измените их в файле `.env` перед запуском.


## Ссылки

**Сайт:** http://localhost:8080

**Админка:** http://localhost:8080/admin

**Telescope:** http://localhost:8080/telescope

**Mailpit:** http://localhost:8025

**Meilisearch:** http://localhost:7700

## Данные для авторизации

**Логин:** user@gmail.com

**Пароль:** 12345678