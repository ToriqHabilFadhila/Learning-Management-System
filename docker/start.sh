#!/bin/bash
set -e

echo "=== Generating .env from environment ==="

cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_TIMEZONE="${APP_TIMEZONE:-Asia/Jakarta}"
APP_URL="${APP_URL}"
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
BCRYPT_ROUNDS=12
LOG_CHANNEL=stderr
LOG_LEVEL=error
DB_CONNECTION=pgsql
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync
MAIL_MAILER=log
ASSET_URL="${APP_URL}"
FORCE_HTTPS=true
EOF

echo "✅ .env generated"

php artisan config:cache
php artisan route:cache
php artisan view:clear
php artisan migrate --force && echo "✅ Migrated" || echo "⚠️ Migration skipped"
php artisan storage:link 2>/dev/null || true

php-fpm -D

echo "🚀 Starting Nginx..."
exec nginx -g "daemon off;"
