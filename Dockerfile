# =============================================================================
# Dockerfile — Laravel Todo (production)
# Multi-stage : builder (Composer + Node/Vite) → runtime (php-fpm)
# =============================================================================

# ─────────────────────────────────────────────────────────────────────────────
# Stage 1 : builder — compile les dépendances PHP et les assets front
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.3-cli-alpine AS builder

RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        xml \
        gd \
        fileinfo \
        intl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN apk add --no-cache nodejs npm

WORKDIR /app

# Copier TOUT le code source d'abord
COPY . .

# Puis installer les dépendances (artisan est disponible)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --prefer-dist

RUN npm ci
RUN npm run build

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# ─────────────────────────────────────────────────────────────────────────────
# Stage 2 : runtime — image finale allégée avec php-fpm
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.3-fpm-alpine AS runtime

RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxml2-dev \
    oniguruma-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        xml \
        gd \
        fileinfo \
        opcache \
    && apk del libpng-dev libjpeg-turbo-dev libwebp-dev libxml2-dev oniguruma-dev \
    && rm -rf /var/cache/apk/*

RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.save_comments=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

RUN addgroup -g 1000 -S www && adduser -u 1000 -S www -G www

WORKDIR /var/www/html

COPY --from=builder --chown=www:www /app .

RUN mkdir -p storage/framework/{sessions,views,cache} \
             storage/logs \
             bootstrap/cache \
    && chown -R www:www storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER www

EXPOSE 9000

CMD ["php-fpm"]
