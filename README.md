# Docker Compose - React, Laravel, MySQL, PhpMyAdmin
Basic single page app project

## Frontend
- NodeJS 20.9.0 Alpine
- React 18.3.1
- Webpack 4

## Backend
- PHP 8.2 apache
- Laravel 10

## MySQL and PhpMyAdmin

MySQL Version: 8.2

## Using the Project

Execute the following command and the Docker will build containers;

```
docker-compose build
```

Execute the following command and the Docker will build containers;

```
docker-compose up -d
```

Execute this command to access laravel container

```
docker exec -it laravel-api bash
```

Run composer command to install package vendor

```
composer install
```

Generate key laravel key

```
php artisan key:generate
```

Run migration database

```
php artisan migrate
```

Run seed database

```
php artisan db:seed
```

## Link
API: http://localhost:8000
Frontend: http://localhost:3001

## Video auto test
https://youtu.be/nNuTiUu6nPU
