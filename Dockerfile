FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html

COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html

EXPOSE 80
