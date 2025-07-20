init: storage-unlink restart storage-link
up: docker-up
down: docker-down
restart: down up migrate-refresh seed frontend-up
start: up frontend-up

docker-up:
	./vendor/bin/sail up -d

docker-down:
	./vendor/bin/sail down

migrate-refresh:
	./vendor/bin/sail php artisan migrate:refresh

seed:
	./vendor/bin/sail php artisan db:seed

storage-link:
	./vendor/bin/sail php artisan storage:link

storage-unlink:
	./vendor/bin/sail php artisan storage:unlink

frontend-up:
	./vendor/bin/sail npm run dev

project-init:
	./vendor/bin/sail php artisan project:init
