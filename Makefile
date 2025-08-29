# ========================
# Основные команды
# ========================

init: docker-down-clear docker-pull docker-build docker-up scout-flush clear
up: docker-up
down: docker-down
restart: down up migrate-fresh scout-flush clear seed scout-import frontend-up
start: up frontend-up

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

frontend-up:
	./vendor/bin/sail npm run dev

# ========================
# Утилиты проекта
# ========================

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
# Система очистки (единый интерфейс)
# ========================

clear: clear-purifier clear-storage clear-storage-tmp clear-laravel-cache clear-logs

clear-purifier:
	@echo "Очистка кэша Purifier..."
	./vendor/bin/sail bash -c "rm -rf storage/app/purifier/*"

clear-storage:
	@echo "Очистка изображений..."
	./vendor/bin/sail bash -c "\
		mkdir -p storage/app/public && \
		find storage/app/public -mindepth 1 -not -name '.gitkeep' -delete \
	"

clear-storage-tmp:
	@echo "Очистка временных файлов..."
	./vendor/bin/sail bash -c "\
		mkdir -p storage/media-library/temp && \
		find storage/media-library/temp -mindepth 1 -not -name '.gitkeep' -delete \
	"

clear-laravel-cache:
	@echo "Очистка кэша Laravel..."
	./vendor/bin/sail php artisan cache:clear
	./vendor/bin/sail php artisan view:clear
	./vendor/bin/sail php artisan route:clear
	./vendor/bin/sail php artisan config:clear
	./vendor/bin/sail php artisan event:clear

clear-logs:
	@echo "Очистка логов..."
	./vendor/bin/sail bash -c "\
		mkdir -p storage/logs && \
		find storage/logs -mindepth 1 -not -name '.gitignore' -delete \
	"