#!/bin/bash
set -e

# Copy .env.production to .env
cp /var/www/html/.env.production /var/www/html/.env

# Generate APP_KEY
php artisan key:generate --force

# Build Vite assets
if [ -f "package.json" ]; then
    npm install || echo "NPM install skipped"
    npm run build || echo "Vite build skipped"
fi

# Clear cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache config & routes
php artisan config:cache
php artisan route:cache

# Migrate
php artisan migrate --force || echo "Migration skipped"

# Start services
php-fpm -D
nginx -g "daemon off;"