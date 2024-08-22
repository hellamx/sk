### Запуск под докером

```bash
# Создаем .env
cp .env.example .env
```

```
# В .env необходимо указать DOCKER_USER равный пользователю на реальном ПК (если запуск под wsl
на windows, то указываем пользователя под wsl)

DOCKER_USER=laravel
```

```bash
# В .env устанавливаем доступы к БД:
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=dev
DB_USERNAME=root
DB_PASSWORD=root
```

### Разворачивание
```bash
docker compose up -d

docker compose exec php composer install

docker compose exec php php artisan key:generate

docker compose exec php php artisan migrate

# https://localhost:8080 - проект
# https://localhost:8082 - phpMyAdmin
```
