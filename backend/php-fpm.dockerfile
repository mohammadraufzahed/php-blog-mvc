FROM composer:latest as builder

WORKDIR /app

COPY composer.json .

RUN composer install -n



FROM php:fpm-bullseye


ARG PHP_UPSTREAM_PORT=9000

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app

COPY . .

COPY --from=builder /app/vendor ./vendor
COPY --from=builder /app/composer.lock .



EXPOSE 9000