# ========================
# Основные команды
# ========================

init: restart
up: docker-up
down: docker-down
restart: down up migrate-refresh clear seed frontend-up
start: up frontend-up

# ========================
# Докер-команды
# ========================

docker-up:
	./vendor/bin/sail up -d

docker-down:
	./vendor/bin/sail down

# ========================
# Миграции и данные
# ========================

migrate-refresh:
	./vendor/bin/sail php artisan migrate:refresh

seed:
	./vendor/bin/sail php artisan db:seed

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
# Работа с хранилищем
# ========================

storage-link:
	./vendor/bin/sail php artisan storage:link

storage-unlink:
	./vendor/bin/sail php artisan storage:unlink

# ========================
# Система очистки (единый интерфейс)
# ========================

clear: clear-purifier clear-storage-images clear-storage-tmp clear-laravel-cache clear-logs

clear-purifier:
	@echo "Очистка кэша Purifier..."
	./vendor/bin/sail bash -c "rm -rf storage/app/purifier/*"

clear-storage-images:
	@echo "Очистка изображений..."
	./vendor/bin/sail bash -c "\
		mkdir -p storage/app/public/images && \
		find storage/app/public/images -mindepth 1 -not -name '.gitkeep' -delete \
	"

clear-storage-tmp:
	@echo "Очистка временных файлов..."
	./vendor/bin/sail bash -c "\
		mkdir -p storage/app/public/tmp && \
		find storage/app/public/tmp -mindepth 1 -not -name '.gitkeep' -delete \
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