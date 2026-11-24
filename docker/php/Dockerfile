FROM php:8.6-fpm-alpine

RUN apk add --no-cache git unzip libzip \
    && apk add --no-cache --virtual .build-deps libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
