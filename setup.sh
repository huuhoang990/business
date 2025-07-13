docker-compose down -v

docker-compose build && docker-compose up -d

docker-compose exec api composer install

docker-compose exec api php artisan migrate

docker-compose exec api php artisan db:seed
