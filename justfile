set dotenv-load := true   # автоматически подгружае .env

# UID/GID для Docker
uid := `id -u`
gid := `id -g`

up:
    UID={{uid}} GID={{gid}} docker compose up -d --build

down:
    docker compose down

restart: down up

# Установка зависимостей
install:
    docker compose exec app composer install
    docker compose exec app npm install

# Vite (dev || build)
dev *args:
    docker compose exec app npm run dev {{args}}

build:
	docker compose exec app npm run build

test *args:
    docker compose exec -e APP_ENV=testing -e DB_CONNECTION=sqlite -e DB_DATABASE=:memory: app vendor/bin/pest {{args}}

# Примеры использования:
# just test
# just test --filter=UserTest
# just test --group=unit --parallel
# just test --coverage --filter=Feature

# Запуск JS тестов
test-js *args:
    docker compose exec app npm run test:js {{args}}

migrate:
    docker compose exec app php artisan migrate

migrate-fresh:
    docker compose exec app php artisan migrate:fresh --seed

perms:
    docker compose exec app chmod -R 775 storage bootstrap/cache

shell:
    docker compose exec -u dev app bash

logs:
    docker compose logs -f

# Очереди и Horizon
queue-restart:
    docker compose exec app php artisan queue:restart

queue-flush:
    docker compose exec app php artisan queue:clear

horizon:
    docker compose exec app php artisan horizon

horizon-pause:
    docker compose exec app php artisan horizon:pause

horizon-terminate:
    docker compose exec app php artisan horizon:terminate

# Просмотр статуса Redis
redis-cli:
    docker compose exec redis redis-cli ping