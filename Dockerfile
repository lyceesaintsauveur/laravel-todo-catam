# =============================================================================
# Dockerfile — Laravel Todo (production)
# Multi-stage : builder (Composer + Node/Vite) → runtime (php-fpm)
# =============================================================================

# ─────────────────────────────────────────────────────────────────────────────
# Stage 1 : builder — compile les dépendances PHP et les assets front
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.3-cli-alpine AS builder

LABEL stage=builder
# Mainteneur et métadonnées
# Dépendances runtime uniquement (pas de compilateurs)
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

# Installer Composer depuis l'image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js pour Vite (build des assets Inertia/Vue/React)
RUN apk add --no-cache nodejs npm

WORKDIR /app

# Copier les fichiers de dépendances en premier (cache Docker layer)
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --prefer-dist

COPY package.json package-lock.json ./
RUN npm ci

# Copier le reste du code source
COPY . .

# Build des assets Vite (génère public/build/manifest.json)
RUN npm run build

# Optimisations Laravel pour la production
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# ─────────────────────────────────────────────────────────────────────────────
# Stage 2 : runtime — image finale allégée avec php-fpm
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.3-fpm-alpine AS runtime

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

# Configuration OPcache pour la production
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.save_comments=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Créer l'utilisateur applicatif (sécurité : pas de root)
RUN addgroup -g 1000 -S www && adduser -u 1000 -S www -G www

WORKDIR /var/www/html

# Copier le code compilé depuis le stage builder
COPY --from=builder --chown=www:www /app .

# Dossiers Laravel qui nécessitent l'écriture
RUN mkdir -p storage/framework/{sessions,views,cache} \
             storage/logs \
             bootstrap/cache \
    && chown -R www:www storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER www

EXPOSE 9000

CMD ["php-fpm"]
