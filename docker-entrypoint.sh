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
# 2. Write Railway env vars into .env
# ----------------------------------------------------------------
write_env_var() {
    local key="$1"
    local value="$2"
    if [ -n "$value" ]; then
        # Strip existing leading/trailing single or double quotes
        value=$(echo "$value" | sed -e 's/^"//' -e 's/"$//' -e "s/^'//" -e "s/'$//")
        
        # Write to .env wrapped in double quotes to handle spaces and special characters safely
        if grep -q "^${key}=" /var/www/html/.env; then
            sed -i "s|^${key}=.*|${key}=\"${value}\"|" /var/www/html/.env
        else
            echo "${key}=\"${value}\"" >> /var/www/html/.env
        fi
    fi
}

write_env_var "APP_ENV"          "$APP_ENV"
write_env_var "APP_DEBUG"        "$APP_DEBUG"
write_env_var "DB_CONNECTION"    "$DB_CONNECTION"
write_env_var "DB_HOST"          "$DB_HOST"
write_env_var "DB_PORT"          "$DB_PORT"
write_env_var "DB_DATABASE"      "$DB_DATABASE"
write_env_var "DB_USERNAME"      "$DB_USERNAME"
write_env_var "DB_PASSWORD"      "$DB_PASSWORD"
write_env_var "SESSION_DRIVER"   "$SESSION_DRIVER"
write_env_var "CACHE_STORE"      "$CACHE_STORE"
write_env_var "QUEUE_CONNECTION" "$QUEUE_CONNECTION"

# Write mail configurations
write_env_var "MAIL_MAILER"       "$MAIL_MAILER"
write_env_var "MAIL_SCHEME"       "$MAIL_SCHEME"
write_env_var "MAIL_HOST"         "$MAIL_HOST"
write_env_var "MAIL_PORT"         "$MAIL_PORT"
write_env_var "MAIL_USERNAME"     "$MAIL_USERNAME"
write_env_var "MAIL_PASSWORD"     "$MAIL_PASSWORD"
write_env_var "MAIL_ENCRYPTION"   "$MAIL_ENCRYPTION"
write_env_var "MAIL_FROM_ADDRESS" "$MAIL_FROM_ADDRESS"
write_env_var "MAIL_FROM_NAME"    "$MAIL_FROM_NAME"

# Force APP_URL to use https:// (Railway always serves over HTTPS)
if [ -n "$APP_URL" ]; then
    HTTPS_URL=$(echo "$APP_URL" | sed 's|^http://|https://|')
    write_env_var "APP_URL" "$HTTPS_URL"
elif grep -q "^APP_URL=" /var/www/html/.env; then
    # Fix any http:// in existing .env
    sed -i 's|^APP_URL=http://|APP_URL=https://|' /var/www/html/.env
fi

# ----------------------------------------------------------------
# 3. Handle APP_KEY
# ----------------------------------------------------------------
if [ -n "$APP_KEY" ]; then
    echo "==> Writing APP_KEY from environment..."
    write_env_var "APP_KEY" "$APP_KEY"
else
    echo "==> APP_KEY not set — generating and saving to .env..."
    php artisan key:generate --force
fi

# ----------------------------------------------------------------
# 4. Ensure storage directories exist with correct permissions
# ----------------------------------------------------------------
echo "==> Preparing storage directories..."
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ----------------------------------------------------------------
# 5. Ensure SQLite database file exists (when using SQLite)
# ----------------------------------------------------------------
DB_CONN="${DB_CONNECTION:-sqlite}"
if [ "$DB_CONN" = "sqlite" ]; then
    DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    if [ ! -f "$DB_FILE" ]; then
        echo "==> Creating SQLite database file at $DB_FILE ..."
        touch "$DB_FILE"
    fi
    chmod 664 "$DB_FILE"
fi

# ----------------------------------------------------------------
# 6. Clear stale caches before reconfiguring
# ----------------------------------------------------------------
echo "==> Clearing stale caches..."
php artisan config:clear  2>/dev/null || true
php artisan cache:clear   2>/dev/null || true
php artisan view:clear    2>/dev/null || true

# ----------------------------------------------------------------
# 7. Run migrations
# ----------------------------------------------------------------
echo "==> Running database migrations..."
php artisan migrate --force

# ----------------------------------------------------------------
# 8. Seed database (firstOrCreate — safe on every deploy)
#    Creates: admin user, membership packages (Silver/Gold/Platinum)
# ----------------------------------------------------------------
echo "==> Seeding database..."
php artisan db:seed --force

# ----------------------------------------------------------------
# 9. Cache for production performance
# ----------------------------------------------------------------
echo "==> Caching for production..."
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# ----------------------------------------------------------------
# 10. Storage symlink (--force recreates even if already exists)
# ----------------------------------------------------------------
echo "==> Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# ----------------------------------------------------------------
# 11. Package discovery (was skipped with --no-scripts at build time)
# ----------------------------------------------------------------
echo "==> Discovering packages..."
php artisan package:discover --ansi

echo "==> All done! Starting FrankenPHP..."
exec frankenphp run --config /var/www/html/Caddyfile
