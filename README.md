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
# http://127.0.0.1:8080/api/v1/product - CRUD
```

### Тестирование команды
```bash
# Файлы categories.json и products.json лежат в storage/app/exchange
docker compose exec php php artisan json:read
```

### Тестирование письма
```bash
# Пользователь, кому отправлять уведомления
PRODUCT_LISTENER_EMAIL=pavel.durov@gmail.com

# Тестовые данные SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.yandex.ru
MAIL_PORT=465
MAIL_USERNAME=ebolsunovscky@yandex.ru
MAIL_PASSWORD=dzmgasjfelfrffkb
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=ebolsunovscky@yandex.ru
MAIL_FROM_NAME="${APP_NAME}"
```
