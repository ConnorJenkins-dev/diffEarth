# ---------- Stage 1: Build frontend, Script provided by ChatGPT ----------
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


# ---------- Stage 2: PHP ----------
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    bash \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql intl zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy built frontend from stage 1
COPY --from=frontend /app/public/build ./public/build

# Fix permissions
RUN mkdir -p \
    storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    storage/logs \
    bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
