#!/bin/sh
set -eu

mkdir -p /var/lib/laravel
if [ ! -f /var/lib/laravel/.env ]; then
    cp .env.example /var/lib/laravel/.env
fi
ln -sf /var/lib/laravel/.env /var/www/html/.env

if ! grep -q '^APP_KEY=base64:' /var/lib/laravel/.env; then
    app_key=$(php artisan key:generate --show)
    if grep -q '^APP_KEY=' /var/lib/laravel/.env; then
        sed -i "s|^APP_KEY=.*|APP_KEY=$app_key|" /var/lib/laravel/.env
    else
        printf '\nAPP_KEY=%s\n' "$app_key" >> /var/lib/laravel/.env
    fi
fi

chown -R www-data:www-data /var/lib/laravel
if [ ! -f "$DB_DATABASE" ]; then
    touch "$DB_DATABASE"
fi
chown www-data:www-data "$DB_DATABASE"

php artisan migrate --force
php artisan db:seed --force

exec apache2-foreground