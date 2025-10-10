# =============================
# Stage 1: Build frontend assets with Node (Vite)
# =============================
FROM node:20-alpine AS frontend
WORKDIR /var/www/html

# Install frontend deps and build
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# =============================
# Stage 2: Build PHP dependencies with Composer
# =============================
FROM php:8.4-cli-alpine AS build

WORKDIR /var/www/html

#Install system dependencies and extensions for composer
RUN apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make \
    && docker-php-ext-install zip pdo pdo_mysql mbstring gd bcmath

#Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress


# Copy compiled Vite assets from frontend stage
COPY --from=frontend /var/www/html/public /var/www/html/public

# =============================
# Stage 3: Nginx + PHP-FPM
# =============================
FROM php:8.3-fpm-alpine

# Install nginx and supervisor
RUN apk add --no-cache nginx supervisor

WORKDIR /var/www/html

# Copy built app from previous stage
COPY --from=build /var/www/html /var/www/html

# Configure Supervisor to run both services
COPY ./docker/supervisord.conf /etc/supervisord.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

# Start Nginx + PHP-FPM via Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
