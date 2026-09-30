FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

FROM php:8.3-apache-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends curl libonig-dev libsqlite3-dev unzip \
    && docker-php-ext-install mbstring opcache pdo_sqlite \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY --from=frontend /app/public/build ./public/build
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/laravel-entrypoint

RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --optimize-autoloader --no-scripts \
    && php artisan package:discover --ansi \
    && mkdir -p /var/lib/laravel \
    && chown -R www-data:www-data storage bootstrap/cache /var/lib/laravel \
    && chmod +x /usr/local/bin/laravel-entrypoint

ENV APP_ENV=production \
    APP_DEBUG=false \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/var/lib/laravel/database.sqlite

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl --fail --silent http://localhost/up || exit 1

ENTRYPOINT ["laravel-entrypoint"]