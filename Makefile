# ========================
# Основные команды
# ========================

init: docker-down-clear docker-pull docker-build docker-up scout-flush clear
up: docker-up
down: docker-down
restart: down up migrate-fresh scout-flush clear seed
start: up frontend

# ========================
# Докер-команды
# ========================

docker-up:
	./vendor/bin/sail up -d

docker-down:
	./vendor/bin/sail down --remove-orphans

docker-down-clear:
	./vendor/bin/sail down -v --remove-orphans

docker-pull:
	./vendor/bin/sail pull

docker-build:
	./vendor/bin/sail build

# ========================
# Миграции и данные
# ========================

migrate:
	./vendor/bin/sail php artisan migrate

migrate-fresh:
	./vendor/bin/sail php artisan migrate:fresh

migrate-refresh:
	./vendor/bin/sail php artisan migrate:refresh

seed:
	./vendor/bin/sail php artisan db:seed

scout-flush:
	@echo "Очистка Meilisearch..."
	./vendor/bin/sail artisan scout:flush "App\Models\Product"

scout-import:
	@echo "Импорт в Meilisearch..."
	./vendor/bin/sail artisan scout:import "App\Models\Product"

# ========================
# Фронтенд
# ========================

frontend:
	./vendor/bin/sail npm run dev

# ========================
# Утилиты проекта
# ========================

schedule:
	./vendor/bin/sail php artisan schedule:work

queue:
	./vendor/bin/sail php artisan queue:work --queue=high,default

project-init:
	./vendor/bin/sail php artisan project:init

generate-models-phpdoc:
	./vendor/bin/sail php artisan ide-helper:models -RW

# ========================
# Тестирование
# ========================

test:
	./vendor/bin/sail php artisan test

# ========================
# Работа с хранилищем
# ========================

storage-link:
	./vendor/bin/sail php artisan storage:link

storage-unlink:
	./vendor/bin/sail php artisan storage:unlink

# ========================
# Система очистки
# ========================

clear: clear-cache-laravel clear-cache-debugbar clear-cache-purifier clear-logs clear-media

clear-cache-laravel:
	./vendor/bin/sail php artisan cache:clear
	./vendor/bin/sail php artisan view:clear
	./vendor/bin/sail php artisan route:clear
	./vendor/bin/sail php artisan config:clear
	./vendor/bin/sail php artisan event:clear

clear-cache-debugbar:
	./vendor/bin/sail php artisan debugbar:clear

clear-cache-purifier:
	rm -rf storage/purifier

clear-logs:
	./vendor/bin/sail php artisan log:clear

clear-public-storage:
	find storage/app/public/* -type f -not -name ".gitignore" -delete 2>/dev/null || true
	find storage/app/public/* -type d -not -name ".gitignore" -exec rm -rf {} + 2>/dev/null || true

clear-media:
	./vendor/bin/sail php artisan media-library:clean

# ========================
# Подакшн
# ========================

prod: prod-down-clear prod-build prod-up prod-migrate prod-seed

prod-up:
	docker compose -f docker-compose.prod.yml --env-file .env.production up -d

prod-down:
	docker compose -f docker-compose.prod.yml --env-file .env.production down

prod-down-clear:
	docker compose -f docker-compose.prod.yml --env-file .env.production down -v --remove-orphans

prod-build:
	docker compose -f docker-compose.prod.yml --env-file .env.production build

prod-migrate:
	docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan migrate --force

prod-seed:
	docker compose -f docker-compose.prod.yml --env-file .env.production exec app php artisan db:seed --force

build:
	docker compose -f docker-compose.prod.yml --env-file .env.production build