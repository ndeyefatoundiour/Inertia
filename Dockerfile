# ============================================
# Stage 1 : Build des assets Vue avec Node
# ============================================
FROM node:22-alpine AS node_builder

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY vite.config.* tsconfig.* ./
COPY resources ./resources
COPY public ./public

RUN npm run build

# ============================================
# Stage 2 : PHP-FPM + extensions Laravel
# ============================================
FROM php:8.3-fpm-alpine

# Dépendances système
RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    icu-dev \
    mysql-client

# Extensions PHP nécessaires pour Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier le code
COPY . .

# Copier les assets buildés depuis le stage Node
COPY --from=node_builder /app/public/build ./public/build

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]