# Определяем UID и GID текущего пользователя для передачи в Docker
UID := $(shell id -u)
GID := $(shell id -g)

# Запуск проекта (сборка и старт в фоне)
up:
	UID=$(UID) GID=$(GID) docker compose up -d --build

# Остановить всё
down:
	docker compose down

# Перезапуск
restart: down up

# Установка зависимостей (PHP и JS)
install:
	docker compose exec app composer install
	docker compose exec app npm install

# Запуск Vite в режиме разработки
dev:
	docker compose exec app npm run dev

# Сборка фронтенда для продакшена
build:
	docker compose exec app npm run build

# Выполнение миграций
migrate:
	docker compose exec app php artisan migrate
	
# Свежая база с тестовыми данными
migrate-fresh:
	docker compose exec app php artisan migrate:fresh --seed

# Права на папки
perms:
	docker compose exec app chmod -R 775 storage bootstrap/cache

# Запуск PHP тестов
test:
	docker compose exec app vendor/bin/pest --testdox --testdox-summary

test-parallel:
	docker compose exec app vendor/bin/pest --parallel

test-unit:
	docker compose exec app --filter=unit

test-feature:
	docker compose exec app --filter=feature


# Зайти "внутрь" контейнера PHP
shell:
	docker compose exec -u dev app bash

# Просмотр логов в реальном времени
logs:
	docker compose logs -f