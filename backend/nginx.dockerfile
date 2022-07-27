FROM composer:latest as builder

WORKDIR /app

COPY composer.json .

RUN composer install -n

FROM nginx:latest

WORKDIR /app

COPY --from=builder /app/vendor ./vendor

COPY --from=builder /app/composer.lock .

COPY . .

COPY default.conf /etc/nginx/conf.d