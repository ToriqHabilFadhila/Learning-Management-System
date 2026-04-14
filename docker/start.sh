#!/bin/bash
set -e

# Buat .env dari environment variables Railway
cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"
APP_TIMEZONE="${APP_TIMEZONE:-UTC}"

LOG_CHANNEL=stderr
LOG_LEVEL="${LOG_LEVEL:-error}"

DB_CONNECTION="${DB_CONNECTION:-pgsql}"
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE:-laravel}"
DB_USERNAME="${DB_USERNAME:-postgres}"
DB_PASSWORD="${DB_PASSWORD:-}"

CACHE_DRIVER="${CACHE_DRIVER:-file}"
SESSION_DRIVER="${SESSION_DRIVER:-file}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"

MAIL_MAILER="${MAIL_MAILER:-log}"

VITE_ENABLED=false
EOF

# Generate APP_KEY jika belum ada
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Build Vite assets (skip jika gagal)
if [ -f "package.json" ]; then
    npm install || echo "NPM install skipped"
    npm run build || echo "Vite build skipped"
fi

# Cache config
php artisan config:cache
php artisan route:cache

# Migrate (skip jika gagal untuk avoid blocking)
php artisan migrate --force || echo "Migration skipped"

# Start services
php-fpm -D
nginx -g "daemon off;"