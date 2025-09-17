# =============================
# Stage 1: Build frontend assets with Node (Vite)
# =============================
FROM node:20 AS frontend
WORKDIR /app

# Install frontend deps and build
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# =============================
# Stage 2: Build PHP dependencies with Composer
# =============================
FROM composer:2 AS build
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader
COPY . .

# Copy compiled Vite assets from frontend stage
COPY --from=frontend /app/public /app/public

# =============================
# Stage 3: PHP + Apache
# =============================
FROM php:8.2-apache
WORKDIR /var/www/html

# Enable Apache mod_rewrite (needed for Laravel routes)
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath

# Copy Laravel app from build stage
COPY --from=build /app ./

# Permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Render listens on port 10000
EXPOSE 10000
