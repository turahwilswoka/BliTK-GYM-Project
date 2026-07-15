# ============================================================
# Stage 1: Node — build frontend assets (Vite/Tailwind)
# ============================================================
FROM node:22-bookworm-slim AS node_build

WORKDIR /app

# Copy package files and install deps
COPY package.json package-lock.json ./
RUN npm ci

# Copy source needed by Vite
COPY resources/ resources/
COPY vite.config.js ./
COPY public/ public/

# Build production assets
RUN npm run build


# ============================================================
# Stage 2: Composer — install PHP dependencies
# ============================================================
FROM composer:2 AS composer_build

WORKDIR /app

COPY composer.json composer.lock ./

# Install without scripts (artisan not yet available)
RUN composer install \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts \
    --no-dev \
    --prefer-dist


# ============================================================
# Stage 3: Final image — FrankenPHP with PHP 8.4 pre-installed
# ============================================================
FROM dunglas/frankenphp:php8.4-bookworm

# Install system dependencies and PHP extensions in a single layer
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
        unzip \
        curl \
    && install-php-extensions \
        gd \
        pdo_pgsql \
        pdo_mysql \
        bcmath \
        opcache \
        intl \
        zip \
        mbstring \
        xml \
        ctype \
        tokenizer \
        fileinfo \
        pcre \
        session \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy application source
COPY . .

# Copy vendor from composer stage (no dev deps, no scripts)
COPY --from=composer_build /app/vendor vendor/

# Copy compiled frontend assets from node stage
COPY --from=node_build /app/public/build public/build/

# Run composer scripts now that the full app is present
RUN composer run-script post-autoload-dump --no-interaction 2>/dev/null || true

# Set correct ownership and permissions
RUN chown -R www-data:www-data /var/www/html \
    && mkdir -p storage/framework/{sessions,views,cache,testing} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy & enable entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
