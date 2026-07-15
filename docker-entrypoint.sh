#!/bin/sh
set -e

echo "==> Setting up Laravel..."

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "==> Running database migrations..."
php artisan migrate --force

# Cache configuration for production
echo "==> Caching configuration..."
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Storage link (may already exist, ignore error)
echo "==> Creating storage symlink..."
php artisan storage:link || true

# Run artisan package discovery (was skipped during composer install)
echo "==> Discovering packages..."
php artisan package:discover --ansi

echo "==> Starting FrankenPHP..."
exec frankenphp run --config /var/www/html/Caddyfile
