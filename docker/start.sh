#!/bin/bash
set -e

# Generate APP_KEY jika belum ada
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Laravel setup
php artisan config:cache
php artisan route:cache
php artisan migrate --force

# Start PHP-FPM background
php-fpm -D

# Start Nginx foreground (port 80)
nginx -g "daemon off;"