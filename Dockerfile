FROM php:8.2-fpm

RUN apt-get update && apt-get install -y libssl-dev

WORKDIR /var/www
COPY . .

RUN composer install

CMD ["php-fpm"]
