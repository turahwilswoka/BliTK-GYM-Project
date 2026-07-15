#!/bin/sh
set -e

echo "==> Setting up Laravel..."

# ----------------------------------------------------------------
# 1. Ensure .env exists
# ----------------------------------------------------------------
if [ ! -f /var/www/html/.env ]; then
    echo "==> .env not found, copying from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# ----------------------------------------------------------------
# 2. Write all Railway env vars into .env
# ----------------------------------------------------------------
write_env_var() {
    local key="$1"
    local value="$2"
    if [ -n "$value" ]; then
        if grep -q "^${key}=" /var/www/html/.env; then
            sed -i "s|^${key}=.*|${key}=${value}|" /var/www/html/.env
        else
            echo "${key}=${value}" >> /var/www/html/.env
        fi
    fi
}

write_env_var "APP_ENV"          "$APP_ENV"
write_env_var "APP_DEBUG"        "$APP_DEBUG"
write_env_var "APP_URL"          "$APP_URL"
write_env_var "DB_CONNECTION"    "$DB_CONNECTION"
write_env_var "DB_HOST"          "$DB_HOST"
write_env_var "DB_PORT"          "$DB_PORT"
write_env_var "DB_DATABASE"      "$DB_DATABASE"
write_env_var "DB_USERNAME"      "$DB_USERNAME"
write_env_var "DB_PASSWORD"      "$DB_PASSWORD"
write_env_var "SESSION_DRIVER"   "$SESSION_DRIVER"
write_env_var "CACHE_STORE"      "$CACHE_STORE"
write_env_var "QUEUE_CONNECTION" "$QUEUE_CONNECTION"

# ----------------------------------------------------------------
# 3. Handle APP_KEY
# ----------------------------------------------------------------
if [ -n "$APP_KEY" ]; then
    echo "==> Writing APP_KEY from Railway environment..."
    write_env_var "APP_KEY" "$APP_KEY"
else
    echo "==> APP_KEY not set — generating and saving to .env..."
    php artisan key:generate --force
fi

# ----------------------------------------------------------------
# 4. Ensure all storage directories exist with correct permissions
# ----------------------------------------------------------------
echo "==> Preparing storage directories..."
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ----------------------------------------------------------------
# 5. Clear any stale cached config
# ----------------------------------------------------------------
echo "==> Clearing stale caches..."
php artisan config:clear  2>/dev/null || true
php artisan cache:clear   2>/dev/null || true
php artisan view:clear    2>/dev/null || true

# ----------------------------------------------------------------
# 6. Run migrations
# ----------------------------------------------------------------
echo "==> Running database migrations..."
php artisan migrate --force

# ----------------------------------------------------------------
# 7. Cache for production performance
#    view:cache runs AFTER storage dirs are confirmed to exist
# ----------------------------------------------------------------
echo "==> Caching for production..."
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# ----------------------------------------------------------------
# 8. Storage symlink (ignore if already exists)
# ----------------------------------------------------------------
echo "==> Creating storage symlink..."
php artisan storage:link 2>/dev/null || true

# ----------------------------------------------------------------
# 9. Package discovery
# ----------------------------------------------------------------
echo "==> Discovering packages..."
php artisan package:discover --ansi

echo "==> All done. Starting FrankenPHP..."
exec frankenphp run --config /var/www/html/Caddyfile
