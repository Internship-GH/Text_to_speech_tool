# =============================
# Stage 1: Build frontend assets with Node (Vite)
# =============================
FROM node:20-alpine AS frontend
WORKDIR /app

# Install frontend deps and build
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# =============================
# Stage 2: Build PHP dependencies with Composer
# =============================
FROM php:8.4-cli AS build
WORKDIR /app

#Install system dependencies and extensions for composer
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

#Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress


# Copy compiled Vite assets from frontend stage
COPY --from=frontend /app/public /app/public

# =============================
# Stage 3: PHP + Apache
# =============================
FROM php:8.4-apache
WORKDIR /var/www/html

# Enable Apache mod_rewrite (needed for Laravel routes)
RUN a2enmod rewrite

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring bcmath zip gd \
    && rm -rf /var/lib/apt/lists/*


# Copy Laravel app from build stage
COPY --from=build /app /var/www/html


# Set Apache DocumentRoot to /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# Allow .htaccess overrides for Laravel
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Allow Apache to follow symlinks in /public/storage
RUN echo '<Directory /var/www/html/public/storage>\n\
    Options FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/storage.conf \
    && a2enconf storage


# Permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Recreate storage symlink manually (no artisan needed)
RUN rm -rf /var/www/html/public/storage \
    && ln -s /var/www/html/storage/app/public /var/www/html/public/storage

ARG PORT=8080
ENV PORT=${PORT}

# Using the dynamically allocated port
RUN sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf \
    && sed -i "s/:80/:${PORT}/" /etc/apache2/sites-available/000-default.conf

# Expose to the port automatically set by railway
EXPOSE ${PORT}

# Start Apache
CMD ["apache2-foreground"]