#!/bin/sh
set -e

echo "==> Setting up Laravel..."

# ----------------------------------------------------------------
# 1. Ensure .env exists (build stage copies .env.example → .env)
#    If for any reason it's missing, recreate from .env.example.
# ----------------------------------------------------------------
if [ ! -f /var/www/html/.env ]; then
    echo "==> .env not found, copying from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# ----------------------------------------------------------------
# 2. Handle APP_KEY
#    On Railway, APP_KEY is set as an environment variable.
#    If it is set, write it into .env so Laravel reads it correctly.
#    If it is NOT set, generate one (first-run convenience).
# ----------------------------------------------------------------
if [ -n "$APP_KEY" ]; then
    echo "==> Writing APP_KEY from environment..."
    # Replace or append APP_KEY in .env
    if grep -q "^APP_KEY=" /var/www/html/.env; then
        sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" /var/www/html/.env
    else
        echo "APP_KEY=${APP_KEY}" >> /var/www/html/.env
    fi
else
    echo "==> APP_KEY not set — generating a new one..."
    php artisan key:generate --force
fi

# ----------------------------------------------------------------
# 3. Write other critical Railway env vars into .env so that
#    cached config still works even if env vars differ per deploy.
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

write_env_var "APP_ENV"      "$APP_ENV"
write_env_var "APP_DEBUG"    "$APP_DEBUG"
write_env_var "APP_URL"      "$APP_URL"
write_env_var "DB_CONNECTION" "$DB_CONNECTION"
write_env_var "DB_HOST"      "$DB_HOST"
write_env_var "DB_PORT"      "$DB_PORT"
write_env_var "DB_DATABASE"  "$DB_DATABASE"
write_env_var "DB_USERNAME"  "$DB_USERNAME"
write_env_var "DB_PASSWORD"  "$DB_PASSWORD"
write_env_var "SESSION_DRIVER" "$SESSION_DRIVER"
write_env_var "CACHE_STORE"  "$CACHE_STORE"
write_env_var "QUEUE_CONNECTION" "$QUEUE_CONNECTION"

# ----------------------------------------------------------------
# 4. Clear any stale cached config, then cache fresh values
# ----------------------------------------------------------------
echo "==> Clearing stale config cache..."
php artisan config:clear  2>/dev/null || true
php artisan cache:clear   2>/dev/null || true

# ----------------------------------------------------------------
# 5. Run migrations
# ----------------------------------------------------------------
echo "==> Running database migrations..."
php artisan migrate --force

# ----------------------------------------------------------------
# 6. Cache for production performance
# ----------------------------------------------------------------
echo "==> Caching configuration for production..."
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# ----------------------------------------------------------------
# 7. Storage symlink (ignore if already exists)
# ----------------------------------------------------------------
echo "==> Creating storage symlink..."
php artisan storage:link || true

# ----------------------------------------------------------------
# 8. Package discovery (was skipped with --no-scripts at build)
# ----------------------------------------------------------------
echo "==> Discovering packages..."
php artisan package:discover --ansi

echo "==> Laravel setup complete. Starting FrankenPHP..."
exec frankenphp run --config /var/www/html/Caddyfile
